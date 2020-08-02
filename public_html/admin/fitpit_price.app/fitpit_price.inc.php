
<?php 
  $show_results = true;
  $filelist = [];

  $filesdir = FS_DIR_HTTP_ROOT . '/excel/';
  $filelist = scandir($filesdir, 1);

  $fields = ['manufacturer','category','name','size','flavour','expiration','pack','base','rrp','sale','additional','qty','rank'];

  if (isset($_POST['lookup_dsc'])) {
    var_dump('lookup_dsc');
    $pq = database::query("select id,
      (select name from products_info where product_id = products.id and language_code = 'uk' limit 1) as title, 
      (select name from manufacturers where id = products.manufacturer_id limit 1) as brand
    from products");
    $bq = database::query("select * from _base_products");

    $prods = [];
    if (database::num_rows($pq) > 0) {
    while ($prow = database::fetch($pq)) {
      //if($prow['brand'] == $brand && $prow['title'] == $row['title']) {
        array_push($prods, $prow);
      // }
    }}
    $count = 0;
    if (database::num_rows($bq) > 0) {
      while ($row = database::fetch($bq)) {
        $brand = str_replace('_', ' ', 
          str_replace( '_'.str_replace(' ', '_', $row['title']), '', $row['sku'])
        );
        foreach($prods as $prow) {
          if($prow['brand'] == $brand && $prow['title'] == $row['title']) {
            $count++;
            database::query(
              "update _base_products set
              lc_id = '". database::input($prow['id']) ."'
                where id = ". database::input($row['id'])
            );
            // var_dump($prow['title']);
          }
        }
        //var_dump($brand, $row['title']);
      }
    }
    var_dump($count);
  }

  if (isset($_POST['add_dsc'])) {
    var_dump('add_dsc');
    $count = 0;
    
    $bq = database::query("select * from _base_products where  lc_id IS NOT NULL and mark = 0 limit 500");
    var_dump(database::num_rows($bq) );
    if (database::num_rows($bq) > 0) {
      while ($row = database::fetch($bq)) {

        $product = new ctrl_product((int)$row['lc_id']);
        /*if($row['dsc'] !='') {
          database::query(
            "update ". DB_TABLE_PRODUCTS_INFO ." set
            description = '". database::input($row['dsc']) ."'
            where product_id = ". (int)$row['lc_id'] ."
            "
          );
          $count++;
        }*/

        $attr = json_decode($row['atr']);
        //var_dump($attr->image); die;
        if(@is_array($attr->image)) {
          
        foreach($attr->image as $img) {
          if($img->path != '') {
            $path = explode('/',$img->path);
            $fn = '/products/' . $path[count($path)-1];
            // var_dump($fn);
            $img = file_get_contents('https:' . $img->path);
            file_put_contents(FS_DIR_HTTP_ROOT  . WS_DIR_IMAGES . $fn, $img);
            $product->add_image(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $fn);
            // var_dump($img); die;
          }
        }}
      }
    }
    var_dump($count);
    die;
  }

  if (isset($_POST['update_config_se'])) {
    database::query(
      "update _excel_config set
        val = '". database::input($_POST['start_col']) ."'
        where conf = 'start_col'"
    );
    database::query(
      "update _excel_config set
        val = '". database::input($_POST['start_val']) ."'
        where conf = 'start_val'"
    );
    database::query(
      "update _excel_config set
        val = '". database::input($_POST['end_col']) ."'
        where conf = 'end_col'"
    );
    database::query(
      "update _excel_config set
        val = '". database::input($_POST['end_val']) ."'
        where conf = 'end_val'"
    );
  }

  if (isset($_POST['update_config_cols'])) {
    foreach($fields as $field) {
      database::query(
        "update _excel_config set
          col = '". database::input($_POST[$field.'_col']) ."'
          where field = '".$field."'"
      );
    }

  }


  $config_query = database::query("select * from _excel_config");
  $config_se = [];
  $config_cols = [];

  if (database::num_rows($config_query) > 0) {
    while ($row = database::fetch($config_query)) {
      if(!is_null($row['conf'])) {
        $config_se[$row['conf']] = $row['val'];
      } else {
        $config_cols[$row['field']] = $row['col'];
      }
      
    }
  }
  //var_dump($config_se);
  //var_dump($config_cols);

  if (isset($_POST['import_products'])) {
    //if ($_POST['import_products_key'] == $key) {
      var_dump('import_products');
      try {
  
        if (!isset($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
          throw new Exception(language::translate('error_must_select_file_to_upload', 'You must select a file to upload'));
        }
        
        $now = date('Y-m-d-H-i-s');
        $data = file_get_contents($_FILES['file']['tmp_name']);
        $file_link = '/excel/'.$now.'.xlsx';
        $file = FS_DIR_HTTP_ROOT . '/excel/'.$now.'.xlsx';
        move_uploaded_file( $_FILES['file']['tmp_name'], $file);
  
        $size = $_FILES['file']['tmp_name'];
        // read
          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          $spreadsheet = $reader->load($file);
          $worksheet = $spreadsheet->getActiveSheet();
  
          $rows = []; $started =  false; $ended = false;
          foreach ($worksheet->getRowIterator() as $r => $row) {
            //var_dump($r);
            if(!$started) {
              $var = $worksheet->getCell($config_se['start_col'] . $r)->getValue();
              if($var === $config_se['start_val']) {
                $started = true;  
                continue;
              }
            }

            if($started) {
              $var = $worksheet->getCell($config_se['end_col'] . $r)->getValue();
              if($var === $config_se['end_val']) $ended = true;

              if(!$ended) {
                $data = [];
                $skip = false;
                foreach($fields as $field) {
                  switch ($field) {
                    case 'category':
                      $data[$field] = $worksheet->getCell($config_cols[$field] . $r)->getValue();
                      if(is_null($data[$field]) || empty($data[$field]) || $data[$field] == '') $skip = true;
                      break;
                    case 'name':
                      $data[$field] = $worksheet->getCell($config_cols[$field] . $r)->getValue();
                      $data['bg'] = $worksheet->getStyle($config_cols[$field] . $r)->getFill()->getStartColor()->getRGB();
                      break;
                    case 'expiration':
                      $data[$field] = $worksheet->getCell($config_cols[$field] . $r)->getFormattedValue();
                      break;
                    
                    default:
                      $data[$field] = $worksheet->getCell($config_cols[$field] . $r)->getValue();
                      break;
                  }

                }
                /*if($r == 906) {
                  echo '<pre>';
                  var_dump($skip);
                  var_dump($data);
                  die;
                }*/
                if($skip === false) {
                  $data['ord'] = $r;
                  array_push($rows,$data);
                }
                //
              }
            }
          }
          
          // database::query('TRUNCATE TABLE _excel');
          foreach ($rows as $key => $row) {

            if($row['category'] === '') continue;

            $hash = md5( implode('|', [
              fp_trim($row['manufacturer']),fp_trim($row['category']),fp_trim($row['name']),$row['size'],$row['flavour']
            ]));

            $product_hash = md5( implode('|', [
              fp_trim($row['manufacturer']),fp_trim($row['category']),fp_trim($row['name'])
            ]));

            $search_query = database::query("select * from _excel where hash = '".$hash."'");
            if (database::num_rows($search_query) == 0) {
              // insert
              database::query("insert into _excel (hash, created) values ('".database::input($hash)."','".date('Y-m-d H:i:s')."')");
            }

            if (database::num_rows($search_query) > 1) {
              // log error
            }

            $query = [];
            foreach($fields as $field) {
              array_push($query, $field . " = '". database::input($row[$field]) ."'");
            }

            database::query(
              "update _excel set ".implode(',', $query).", 
                product_hash = '".$product_hash."', 
                ord = ".$row['ord'].", 
                bg = '".$row['bg']."', 
                updated = '".date('Y-m-d H:i:s')."' 
               where  hash = '".$hash."'"
            );
  
          }
  
          database::query(
            "update _excel set mark = 0"
          );
        //$spreadsheet = $reader->load($filepath);
        //exit;
        //$show_results = true;
        //ob_clean();
        //unset($_POST['import_products_key']);
        //unlink($file);
        /*?>
        <script>
        console.log('@'); 
        window.location.href = '/admin/?app=catalog&doc=excel';
        simulateClick = function (elem) {
          // Create our event (with options)
          var evt = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
          });
          // If cancelled, don't dispatch our event
          var canceled = !elem.dispatchEvent(evt);
        };
        document.getElementById('doc-excel');
        </script> 
        <?*/
        // die; // 
          //  <script> console.log('@'); window.location.reload();</script> 
      } catch (Exception $e) {
        notices::add('errors', $e->getMessage());
      }
    }

    function fp_trim($str) {
      return trim(str_replace('  ', '', $str));
    }
?>
<div class="row">
  <div class="col-md-4"><h3><?php echo $app_icon; ?> <?php echo language::translate('title_excel_import_export', 'Excel Import'); ?></h3></div>
  <div class="col-md-8 text-right">
  <a class="btn" role="button" data-toggle="collapse" href="#collapseSettings" aria-expanded="false" aria-controls="collapseSettings">
    Settings
  </a>

  <div class="collapse" id="collapseSettings">
    <div class="panel panel-default">
      <div class="panel-body">

        <div class="row text-left">
          <div class="col-md-6">
            <fieldset class="well">
              <legend>Start / End</legend>
              <?php echo functions::form_draw_form_begin('form_config_se', 'post', '', true); ?>
              <table class="table">
                <tr>
                  <th>Type</th>
                  <th>Col</th>
                  <th>Val</th>
                </tr>
                <tr>
                  <th>Start</th>
                  <td><?php echo functions::form_draw_text_field('start_col', $config_se['start_col']); ?></td>
                  <td><?php echo functions::form_draw_text_field('start_val', $config_se['start_val']); ?></td>
                </tr>
                <tr>
                  <th>End</th>
                  <td><?php echo functions::form_draw_text_field('end_col', $config_se['end_col']); ?></td>
                  <td><?php echo functions::form_draw_text_field('end_val', $config_se['end_val']); ?></td>
                </tr>
              </table>
              <?php echo functions::form_draw_button('update_config_se', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
              <?php echo functions::form_draw_form_end(); ?>
            </fieldset>
          </div>
          <div class="col-md-6">
            <fieldset class="well">
              <legend>Cols</legend>
              <?php echo functions::form_draw_form_begin('form_config_cols', 'post', '', true); ?>
              <table class="table">
                <tr>
                  <th>Field</th>
                  <th>Excell col</th>
                </tr>
                <? foreach($fields as $field) { ?>
                <tr>
                  <th><?php echo $field ?></th>
                  <td><?php echo functions::form_draw_text_field( $field.'_col', $config_cols[$field]); ?></td>
                </tr>
                <? } ?>
              </table>
              <?php echo functions::form_draw_button('update_config_cols', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
              <?php echo functions::form_draw_form_end(); ?>

              <?php echo functions::form_draw_form_begin('form_lookup', 'post', '', true); ?>
              <?php echo functions::form_draw_button('lookup_dsc', 'find ids', 'submit', '', 'save'); ?>
              <?php echo functions::form_draw_form_end(); ?>
              <?php echo functions::form_draw_form_begin('form_addlookup', 'post', '', true); ?>
              <?php echo functions::form_draw_button('add_dsc', 'add dsc', 'submit', '', 'save'); ?>
              <?php echo functions::form_draw_form_end(); ?>
            </fieldset>
          </div>
          
        </div>

      </div>
    </div>
    </div>
  </div>

</div>

<div class="row">
  

  <div class="col-md-12">
    <div class="row">
      <div class="col-md-4">
        <fieldset class="well">
          <legend><?php echo language::translate('title_import_from_csv', 'Import From XLS'); ?></legend>

          <?php echo functions::form_draw_form_begin('import_products_form', 'post', '', true); ?>
          
            <div class="form-group">
              
              <?php echo functions::form_draw_file_field('file'); ?>
            </div>

            <input type="hidden" name="import_products_key" value="<?=date('YmdHi');?>">
            <button class="btn btn-default" type="submit" name="import_products" value="val">Считать</button>
            <?php 
             //echo functions::form_draw_button('import_products', language::translate('title_import', 'Import'), 'submit'); 
             ?>
            
          <?php echo functions::form_draw_form_end(); ?>
        </fieldset>
      </div>

      <div class="col-md-4">
        <fieldset class="well">
          <legend>Обработка</legend>
          <?php if($show_results === true || 1==1) { /* var_dump($_POST); $key; */ ?> 
            <progress max="100" value="0" style="width:100% "></progress>
            <button class="btn btn-default" type="submit" id="process" value="Обработать" >Обработать</button> <!--disabled-->
            <div id="stat" style="display:none;">
            <div> Всего: <span id="res_count"></span><!--<?php echo count($rows_errors)+count($rows);?>--></div>
            <div> Строк обработано: <span id="res_processed"></span><!--<?php echo count($rows_errors)+count($rows);?>--></div>
            <div> Успешно: <span id="res_success"></span><!--<?php echo count($rows);?>--></div>
            <div> Ошибок: <span id="res_errors"></span><!--<?php echo count($rows_errors);?>--></div>
            </div>

            <div id="res_file" style="margin-top:5px;"></div>

          <!--
          <div> <?php var_dump($rows_errors);?> </div>

          
            <div><a href="<?php echo $file_link;?>" target="_blank">Обработанный файл</a></div>
          
            <div><?php echo $log;?></div>
          -->
          <?php } ?>
        </fieldset>
      </div>

      <div class="col-md-4">
        <fieldset class="well">
          <legend>Архив</legend>
          <? $i = 0;
           foreach ($filelist as $key => $file) { if($file == '.' || $file == '..' || $file == 'bak' || $file == 'template.xlsx') continue;
            $i++; if($i == 4) break;
            ?> <a href="/excel/<?php echo $file;?>" target="_blank"><?php echo $file;?></a><br> <?
            
          }?>
          <a href="/excel/" target="_blank">All</a><br>
          
        </fieldset>
      </div>
<!--
      <?php echo functions::form_draw_form_begin('import_images_form', 'post', '', true); ?>
      <?php echo functions::form_draw_button('import_images', language::translate('import_images', 'import images'), 'submit'); ?>
      <?php echo functions::form_draw_form_end(); ?>

      <?php echo functions::form_draw_form_begin('import_desc_form', 'post', '', true); ?>
      <?php echo functions::form_draw_button('import_desc', language::translate('import_desc', 'import desc'), 'submit'); ?>
      <?php echo functions::form_draw_form_end(); ?>
-->
    </div>
    <div class="row">
    <!--// pid,option_id,manufacturer,category,name,variant,price,quantity,status,sale,short_description -->
    <div class="col-md-12">
      <br>
    </div>
    <div class="col-md-12">
      <!--<input type="text" id="searchInput" onkeyup="search()" placeholder="Search..">-->
    </div>

    <div class="col-md-12">
    <table id="importTable" class="table table-striped table-hover data-table" style=" display: block; width:100%;">
      <!--
      <thead  style=" display: block; width:100%;">
        <tr>
            <th>id</th>
            <th>pid</th>
            <th>option_id</th>
            <th>manufacturer</th>
            <th>category</th>
            <th>name</th>
            <th>variant</th>
            <th>price</th>
            <th>quantity</th>
            <th>status</th>
            <th>sale</th>
            <th>short_description</th>
        </tr>
      </thead>
      -->

      <tbody style="max-height:60vH;  display: block; width:100%; overflow:auto">
      <?
      $rows = database::query("select * from _excel order by manufacturer");
      if (database::num_rows($rows) > 0) {
        while ($row = database::fetch($rows)) {
          //var_dump($row);
          ?>
          <tr>
          <? foreach ($row as $k => $cell) { ?>
            <? switch ($k) {
                case 'mark':
                    ?>
                    <td  id="cell<?=$row['id'];?><?=$k;?>" title="cell<?=$row['id'];?><?=$k;?>" class="cell-<?=$k;?>">
                      <? if($cell == "1") { ?>
                        <i class="fa fa-circle" style="color: #88cc44;"></i>
                      <? } else { ?>
                        <i class="fa fa-circle" style="color: #ff6644;"></i>
                      <? } ?>
                    </td>
                    <?
                  break;
                default:
                  ?><td id="cell<?=$row['id'];?><?=$k;?>" class="cell-<?=$k;?>"><?=$cell;?></td><?
            }?>
            
          <? } ?>
          </tr>
          <?
                   
        }
      }
      ?>
      </tbody>
    </table>
    </div>
    </div>
  </div>
</div>

<style>
  #searchInput {
    border-radius:4px;
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
  }
</style>
<script>
  setInterval(function() {
    $('[name="import_products_key"]').val(format(new Date(), "yyyyMMddhhmm"))
  }, 500)
  function search() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("importTable");
    console.log(table)
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
      
      td = tr[i].getElementsByTagName("td");
      txtValue = '';
      for (j = 0; j < td.length; j++) {
        txtValue = td[j].textContent || td[j].innerText;
      }
      if (td) {
        // txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }
  var timerCart = null;
  $('#process').click(function(e){
    e.preventDefault();
    console.log('#process');
    processImport();
    // $('#process').attr('disabled',true);
    // timerCart = setInterval("processImport()", 5000);
  })
  $('#process').attr('disabled',false);

  window.processImport = function(data) {
    /*
    url: '<?php echo document::link(WS_DIR_ADMIN, array('doc' => 'attribute_values.json'), array('app')); ?>&group_id=' + $(this).val(),
      type: 'get',
      cache: true,
      async: true,
      dataType: 'json',
      error: function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR.readyState + '\n' + textStatus + '\n' + errorThrown.message);
      },*/
    $.ajax({
      url: '<?php echo document::link(WS_DIR_ADMIN, array('doc' => 'import.json'), array('app')); ?>',
      type: 'get',
      data: {type:'processImport'},
      cache: false,
      async: true,
      dataType: 'json',
      beforeSend: function(jqXHR) {
        jqXHR.overrideMimeType('text/html;charset=' + $('meta[charset]').attr('charset'));
      },
      error: function(jqXHR, textStatus) {
        // if (data) alert('Error while process import');
        console.warn('Error while process import');
        console.warn(jqXHR.responseText, jqXHR, textStatus);
      },
      success: function(json) {
        console.log('success', json);
        //$('#stat').show();
        
        /*Object.keys(json).map( k => {
          $('#res_'+k).text(json[k]);
        });*/
        
        
        json.ids.map( id => {
          //console.log('#cell'+id+'mark')
          $('#cell'+id+'mark').html('<i class="fa fa-circle" style="color: #88cc44;"></i>');
        });

        if(json.count > json.processed) {
          $('progress').attr('value', Math.round((json.processed*100)/json.count));
          processImport();
        }
        if(json.count == json.processed) {
          $('#res_file').html('Done!');
          $('progress').attr('value',100);
        }
      },
      complete: function() {
        // if (data) $('*').css('cursor', '');
      }
    });

  }


  format = function date2str(x, y) {
    var z = {
        M: x.getMonth() + 1,
        d: x.getDate(),
        h: x.getHours(),
        m: x.getMinutes(),
        s: x.getSeconds()
    };
    y = y.replace(/(M+|d+|h+|m+|s+)/g, function(v) {
        return ((v.length > 1 ? "0" : "") + eval('z.' + v.slice(-1))).slice(-2)
    });

    return y.replace(/(y+)/g, function(v) {
        return x.getFullYear().toString().slice(-v.length)
    });
}
</script>
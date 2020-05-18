<?php

  $show_results = false; 

  $log = '';
  $file_link = '';
  $rows = array(); 
  $rows_errors = array(); 

  $filesdir = FS_DIR_HTTP_ROOT . '/excel/';
  $filelist = scandir($filesdir, 1);

  // rsort($filelist, SORT_STRING);
 
  if (isset($_POST['import_images'])) {
    $rows = database::query("select * from per_product_img where lc_id != 0");
    if (database::num_rows($rows) > 0) {
      while ($row = database::fetch($rows)) {
        //var_dump(FS_DIR_HTTP_ROOT);
        $ids = explode(',',$row['lc_ids']);
        foreach ($ids as $id) {
          if($id == $row['lc_id']) continue;

          $product = new ctrl_product($id);

          $name = explode('/',$row['img']);
          $fn = '/products/' . $row['code'] . '_' . $name[count($name)-1];
          $pfn = 'products/' . $row['code'] . '_' . $name[count($name)-1];
          copy($row['img'], FS_DIR_HTTP_ROOT  . WS_DIR_IMAGES . $fn);
          
          $product->add_image(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $fn);
          
        }
        
      }
    }
  }

  if (isset($_POST['import_desc'])) {
    $rows = database::query("select * from per_product_lang where lc_id != 0");
    if (database::num_rows($rows) > 0) {
      while ($row = database::fetch($rows)) {
        //var_dump(FS_DIR_HTTP_ROOT);
        $ids = explode(',',$row['lc_ids']);
        foreach ($ids as $id) {
          if($id == $row['lc_id']) continue;

          database::query(
            "update ". DB_TABLE_PRODUCTS_INFO ." set
            short_description = '". database::input($row['description_short']) ."',
            description = '". database::input($row['description']) ."',
            head_title = '". database::input($row['meta_title']) ."',
            meta_description = '". database::input($row['meta_description']) ."'
            where id = ". (int)$id ."
            limit 1;"
          );
          
        }
        
      }
    }
  }
  
  $key = date('YmdHi');
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

        // index, key, default, required
        $row_map = array(
          array(1,'id',             false,  false),
          array(2,'option_id',      false,  false),
          array(3,'manufacturer',   '',     true),
          array(4,'category',       '',     false),
          array(5,'name',           '',     true),
          array(6,'option',         false,  false),
          array(7,'price',          0,      false),
          array(8,'quantity',       0,      false),
          array(9,'status',         1,      false),
          array(10,'sale',          false,  false),
          array(11,'short_description','',     false),
          //array(12,'code','',     false),
        );
        foreach ($worksheet->getRowIterator() as $r => $row) {
          if($r==1) continue;

          // mapping row
          $erow = array(); $error = false;
          foreach ($row_map as $rules) {
            $var = $worksheet->getCellByColumnAndRow($rules[0], $r)->getValue();
            if($var == '=FALSE()') {$var = false;}
            if(empty($var)) { //is_null($var) || 
              if($rules[3] === true) {
                $error = true;
                $erow[$rules[1]] = $rules[2];
              } else {
                $erow[$rules[1]] = $rules[2];
              }

            } else {
              $erow[$rules[1]] = $var;
            }

            // col HIDE invert
            if($rules[1] == 'status' && $var == 1) {
              $erow[$rules[1]] = 0;
            }

            
          }
          if($error === true) {
            array_push($rows_errors,$erow);
          } else {
            array_push($rows,$erow);
          }
          
        }

        database::query('TRUNCATE TABLE _excel');
        foreach ($rows as $key => $erow) {
          if($erow['id'] == false) {$erow['id'] = 0;}
          if($erow['option_id'] == false) {$erow['option_id'] = 0;}
          if($erow['option'] == false) {$erow['option'] = '';}
          if($erow['sale'] == false) {$erow['sale'] = 0;}
            
            /*
              ".database::input($erow['id']).",
              ".database::input($erow['option_id']).",
            */

          database::query("insert into _excel 
            (manufacturer,category,name,variant,price,quantity,status,sale,short_description) 
            values (
              '".database::input($erow['manufacturer'])."',
              '".database::input($erow['category'])."',
              '".database::input($erow['name'])."',
              '".database::input($erow['option'])."',
              ".database::input($erow['price']).",
              ".database::input($erow['quantity']).",
              ".database::input($erow['status']).",
              ".database::input($erow['sale']).",
              '".database::input($erow['short_description'])."'
            );");

        }

        
      //$spreadsheet = $reader->load($filepath);
      //exit;
      $show_results = true;
      ob_clean();
      //unset($_POST['import_products_key']);
      unlink($file);
      ?>
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
      <?
      die; // 
        //  <script> console.log('@'); window.location.reload();</script> 
    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }


?>
<h1><?php echo $app_icon; ?> <?php echo language::translate('title_excel_import_export', 'Excel Import'); ?></h1>

<div class="row">
  

  <div class="col-md-12">

    <h2>
          Обновление / добавление товаров, производителей, категорий (superbody format)
    <!--  <?php echo language::translate('title_products', 'Products'); ?>-->
        
    </h2>

    <div class="row">
      <div class="col-md-4">
        <fieldset class="well">
          <legend><?php echo language::translate('title_import_from_csv', 'Import From XLS'); ?></legend>

          <?php echo functions::form_draw_form_begin('import_products_form', 'post', '', true); ?>
          
            <div class="form-group">
              
              <?php echo functions::form_draw_file_field('file'); ?>
            </div>

            <!--
            <div class="form-group">
              <label><?php echo functions::form_draw_checkbox('insert_products', 'true', true, 'checked="true"'); ?> <?php echo language::translate('text_insert_new_products', 'Insert new products'); ?></label>
            </div>
            <div class="form-group">
              <label><?php echo functions::form_draw_checkbox('insert_options', 'true', true,  'checked="true"'); ?> <?php echo language::translate('text_insert_new_options', 'Insert new options'); ?></label>
            </div>

            <div class="form-group">
              <label><?php echo functions::form_draw_checkbox('insert_categories', 'true', true,  'checked="true"'); ?> <?php echo language::translate('text_insert_new_categories', 'Insert new categories'); ?></label>
            </div>

            <div class="form-group">
              <label><?php echo functions::form_draw_checkbox('insert_manufacturers', 'true', true,  'checked="true"'); ?> <?php echo language::translate('text_insert_new_manufacturers', 'Insert new manufacturers'); ?></label>
            </div>-->

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
            <button class="btn btn-default" type="submit" id="process" value="Обработать" disabled>Обработать</button>
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
    $.ajax({
      url: window.config.platform.url + 'ajax/import.json',
      type: 'get',
      data: {type:'processImport'},
      cache: false,
      async: true,
      dataType: 'json',
      beforeSend: function(jqXHR) {
        jqXHR.overrideMimeType('text/html;charset=' + $('meta[charset]').attr('charset'));
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (data) alert('Error while process import');
        console.error('Error while process import');
        console.debug(jqXHR.responseText);
      },
      success: function(json) {
        console.log(json);
        // $('#stat').show();
        
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
          $('#res_file').html('<a href="'+json.file+'" target="_blank">'+json.file+'</a>');
          $('progress').attr('value',100);
        }
      },
      complete: function() {
        if (data) $('*').css('cursor', '');
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
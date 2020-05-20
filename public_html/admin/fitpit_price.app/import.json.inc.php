<?php
  header('Content-type: application/json; charset='. language::$selected['charset']);

  
  $log = [];
  $res = database::query("select * from _excel where mark = 0 limit 80");
  $prods = [];
  if (database::num_rows($res) > 0) {
    while ($r = database::fetch($res)) {
      array_push($prods,database::input($r['product_hash']));
    }
  }

  $prods = array_unique($prods);
  


  $res = database::query("select * from _excel where product_hash in ('".implode("','",$prods)."')");
  $rows = [];
  if (database::num_rows($res) > 0) {
    while ($r = database::fetch($res)) {
      $q = [];
      foreach($r as $k => $v ) {
        switch($k) {
          case 'id':
          case 'qty':            
          case 'pack':
            $q[$k] = (int)$v;
            break;
          case 'sale':   
          case 'rrp':   
          case 'base':   
            $q[$k] = (float)$v;         
            break;
          default:
            $q[$k] = $v;
        }
      }
      array_push($rows,$q);
    }
  }

  

  // manufacturers 
  foreach ($rows as $key => $erow) {
    $manufacturer_name = trim($erow['manufacturer']);
    if ($manufacturer = database::fetch(database::query("select id from ". DB_TABLE_MANUFACTURERS ." where name = '". database::input($manufacturer_name) ."'  limit 1;"))) {

      //$log .= "Linking existing manufacturer ". $manufacturer_name ."<br>";
      array_push($log,  'Linking existing manufacturer: '. $manufacturer_name .'; id: '. $manufacturer['id']);
      $rows[$key]['manufacturer_id'] = $manufacturer['id'];
    
    } else {
      
      database::query("insert into ". DB_TABLE_MANUFACTURERS ." (date_created, name, code, status) values ('". date('Y-m-d H:i:s') ."','". database::input($manufacturer_name)."' ,'". database::input(strtolower(str_replace(' ','_',$manufacturer_name)))."' , 1);");
      $manufacturer_id = database::insert_id();
      
      array_push($log,  'Creating new manufacturer: '. $manufacturer_name .'; id: '. $manufacturer['id']);
      $rows[$key]['manufacturer_id'] = $manufacturer_id;
    }
  }

  // categories
  foreach ($rows as $key => $erow) {
    $category_name = trim($erow['category']);
    if ($category = database::fetch(database::query("select category_id from ". DB_TABLE_CATEGORIES_INFO ." where name = '". database::input( $category_name) ."' AND language_code = 'uk'  limit 1;"))) {

      array_push($log,  'Linking existing category: '. $category_name .'; id: '. $category['category_id']);
      $rows[$key]['default_category_id'] = $category['category_id'];
    
    } else {

      database::query("insert into ". DB_TABLE_CATEGORIES ." (date_created, status) values ('". date('Y-m-d H:i:s') ."', 1);");
      $category_id = database::insert_id();
      database::query(
        "insert into ". DB_TABLE_CATEGORIES_INFO ."
        (category_id, name, language_code)
        values ('". $category_id ."', '". database::input($category_name) ."','uk');"
      );

      array_push($log,  'Creating new category: '. $category_name.'; id: '. $category['category_id']);
      $rows[$key]['default_category_id'] = $category_id;
    }
  }

  

  //products
  foreach ($rows as $key => $row) {
    // identify

    if ($product = database::fetch(database::query("select id from ". DB_TABLE_PRODUCTS ." where product_hash = '". $row['product_hash'] ."' limit 1;"))) {
      $rows[$key]['product_id'] = $product['id'];
      $product = new ctrl_product($product['id']);

      array_push($log,  "Updating existing product " . $row['name'] ." from line ". $row['ord']);
    } else {
      
      database::query("insert into ". DB_TABLE_PRODUCTS ." (product_hash, date_created) values ('". $row['product_hash'] ."', '". date('Y-m-d H:i:s') ."');");
      $product_id = database::insert_id();
      $rows[$key]['product_id'] = $product_id;
      $product = new ctrl_product($product_id);

      array_push($log,  'Creating new product: '. $row['name'] . "; id: ". $product_id);
    }

   
    // update with data
    $fields = array(
      'manufacturer_id',
      'default_category_id',
    );

    // Set new product data //'code',
    $product->data['status'] = 1;
    $product->data['categories'] = array($row['default_category_id']);
    foreach ($fields as $field) {
      if (isset($row[$field])) $product->data[$field] = $row[$field];
    }

    foreach (array('name') as $field) {
      if ($row[$field] != '') {
        $product->data[$field]['uk'] = str_replace("'", "`", $row[$field]);
      }
    }

    if ($row['base'] != 0) {
      $product->data['prices']['EUR'] = $row['base'];
    }

    /*$product->data['campaigns'] = [];
    if($row['sale'] != 0) {
      $product->data['campaigns']['new_'.time()] = array(
        'percentage' => $row['sale'],
        'EUR' => $row['price'] * ((100-$row['sale'])/100),
        'UAH' => '',
        'USD' => '',
      );
    }*/

    if($row['size'] == '' && $row['flavour'] == '') {
      $product->data['quantity'] = @$row['quantity'];
    }

    // var_dump($product);die;
    // save
    $product->save();
    $rows[$key]['product_id'] = (int)$product->data['id'];

  }
  
  
  //options
  foreach ($rows as $key => $row) {
    if($row['size'] != '' || $row['flavour'] != '' || $row['expiration'] != '') {
      $rows[$key]['options'] = [];
    }
    $params = ['', 'size', 'flavour', 'expiration'];
    foreach($params as $group => $prm) {
      if($group != '') {

        if($row[$prm] != '') {
          if ($option = database::fetch(database::query("select value_id from ". DB_TABLE_OPTION_VALUES_INFO ." where name = '". database::input($row[$prm]) ."' and language_code = 'uk' limit 1;"))) {
            $rows[$key]['options'][$prm] = ['value_id' => $option['value_id'], 'group_id' => $group];
          } else {
            database::query("insert into ". DB_TABLE_OPTION_VALUES ." (group_id) values (".$group.");");
            $value_id = database::insert_id();
            $rows[$key]['options'][$prm] = ['value_id' => $value_id, 'group_id' => $group];
            database::query(
              "insert into ". DB_TABLE_OPTION_VALUES_INFO ."
              (value_id, name, language_code)
              values (".(int)$value_id .", '". $row[$prm] ."','uk');"
            );
          }
        }
      }
    }

    foreach($params as $group => $prm) {
      if($group != '') {
        if($row[$prm] != '') {

          if($option = database::fetch(database::query(
            "select id from ". DB_TABLE_PRODUCTS_OPTIONS ." where 
              value_id = ". (int)$rows[$key]['options'][$prm]['value_id'] ." and 
              group_id = ". (int)$rows[$key]['options'][$prm]['group_id'] ." and 
              product_id = ". (int)$row['product_id'] ."  limit 1;"))) {
            database::query(
              "update ". DB_TABLE_PRODUCTS_OPTIONS ."
                set EUR = ". (float)$row['base'] ."
                where product_id = ". (int)$row['product_id'] ."
                and id = ". (int)$option['id'] ."
                limit 1;"
            );
          } else {
            database::query(
              "insert into ". DB_TABLE_PRODUCTS_OPTIONS ."
                (product_id, group_id, value_id, EUR, date_created)
                values (
                  ". (int)$row['product_id'] .",
                  ". (int)$rows[$key]['options'][$prm]['group_id'] .",
                  ". (int)$rows[$key]['options'][$prm]['value_id'] .", 
                  ". (float)$row['base'] .", 
                  '". date('Y-m-d H:i:s') ."');"
            );
          }

        }
      }
    }
  }

  
  
  // stock
  foreach ($prods as $hash) {
    $stock = []; 
    $product_id = 0;
    $quantity_sum = 0;
    $now = date('Y-m-d H:i:s');

    foreach ($rows as $key => $row) {
      if($row['product_hash'] == $hash) {
        $product_id = $row['product_id'];
        $combination = [];
        if(isset($row['options'])) {
          foreach($row['options'] as $opt) {
            array_push($combination, $opt['group_id'].'-'.$opt['value_id']);
          } 
          
          $combination = implode(',',$combination);

          array_push($stock, [
            'product_id' => $row['product_id'],
            'combination' => $combination,
            'hash' => $row['hash'],
            'quantity' => $row['qty'],
            'price' => $row['base']
          ]);
        }
      }
    }
      
      
    foreach($stock as $opt) {
      if ($stock_opt = database::fetch(database::query("select id from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." where hash = '". $opt['hash'] ."' limit 1;"))) {
        database::query(
          "update ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." set 
            quantity = ". (int)$opt['quantity'] .",
            price = ". (float)$opt['price'] .",
            date_updated = '". $now ."'
            where product_id = ". (int)$opt['product_id'] ."
            and id = ". (int)$stock_opt['id'] ."
            limit 1;"
        );
      } else {
        database::query(
          "insert into ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
            (product_id, combination, hash, quantity, price, date_created, date_updated)
            values (
              ". (int)$opt['product_id'] .",
              '". $opt['combination'] ."',
              '". $opt['hash'] ."', 
              ". (int)$opt['quantity'] .", 
              ". (float)$opt['price'] .", 
              '". $now ."',
              '". $now ."'
            );"
        );
      }
      $quantity_sum += $opt['quantity'];
    }

    database::query(
      "update ". DB_TABLE_PRODUCTS ." set
          quantity = ". (float)$quantity_sum ."
        where id = ". (int)$product_id ."
        limit 1;"
    );

    database::query(
      "delete from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." where product_id = '". (int)$product_id ."' and date_updated != '". $now ."';"
    );
      
    
  
  }

  // echo json_encode(['status' => 'success', 'prods' => $prods, 'rows' => $rows, 'log' => $log]); exit;

  $ids = [];
  foreach ($rows as $key => $erow) {
    array_push($ids,$erow['id']);
    database::query(
      "update _excel set
      mark = 1
      where id = ". (int)$erow['id'] ."
      limit 1;"
    );
  }

  $count = database::query("select * from _excel order by manufacturer");
  $processed = database::query("select * from _excel where mark != 0");
  $success = database::query("select * from _excel where mark = 1");
  $errors = database::query("select * from _excel where mark = -1");
  
  

  $json = [
    'count' => database::num_rows($count),
    'processed' => database::num_rows($processed),
    'success' => database::num_rows($success),
    'errors' => database::num_rows($errors),
    //'log' => $log,
    'rows' => $rows,
    'prods' => $prods,
    //'products' => $products,
    'ids' => $ids
  ];

  /*if($json['count'] == $json['processed']) {
    $now = date('Y-m-d-H-i-s');
    $filesdir = FS_DIR_HTTP_ROOT . '/excel/';
    $tpl = $filesdir . 'template.xlsx';
    $xls = FS_DIR_HTTP_ROOT . '/excel/'.$now.'.xlsx';
    $xls_link = '/excel/'.$now.'.xlsx';

    
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($tpl);
    $worksheet = $spreadsheet->getActiveSheet();

    if (database::num_rows($count) > 0) {
      $row = 2;
      
      while ($r = database::fetch($count)) {

        if($r['status'] == 1) {
          $r['status'] = 0;
        } else {
          if($r['status'] == 0) {
            $r['status'] = 1;
          }
        }

        $worksheet->setCellValueByColumnAndRow(1, $row, $r['id']);
        $worksheet->setCellValueByColumnAndRow(2, $row, 0);
        $worksheet->setCellValueByColumnAndRow(3, $row, $r['manufacturer']);
        $worksheet->setCellValueByColumnAndRow(4, $row, $r['category']);
        $worksheet->setCellValueByColumnAndRow(5, $row, $r['name']);
        $worksheet->setCellValueByColumnAndRow(6, $row, $r['variant']);
        $worksheet->setCellValueByColumnAndRow(7, $row, $r['price']);
        $worksheet->setCellValueByColumnAndRow(8, $row, $r['quantity']);
        $worksheet->setCellValueByColumnAndRow(9, $row, $r['status']);
        $worksheet->setCellValueByColumnAndRow(10, $row, $r['sale']);
        $worksheet->setCellValueByColumnAndRow(11, $row, $r['short_description']);

        $row++;
      }
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($xls);
    $json['file'] = $xls_link;
  }*/
  

  if (!empty(notices::$data['warnings'])) {
    $warnings = array_values(notices::$data['warnings']);
    $json['alert'] = array_shift($warnings);
  }

  if (!empty(notices::$data['errors'])) {
    $errors = array_values(notices::$data['errors']);
    $json['alert'] = array_shift($errors);
  }

  notices::reset();

  language::convert_characters($json, language::$selected['charset'], 'UTF-8');
  $json = json_encode($json);

  language::convert_characters($json, 'UTF-8', language::$selected['charset']);
  echo $json;

  exit;


?>
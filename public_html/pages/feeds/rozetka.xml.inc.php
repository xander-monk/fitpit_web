<?php

  // language::set(settings::get('store_language_code'));

  $misc_query =  database::query(
    "select * from _misc where var = 'eur';"
  );
  $eur = 1;
  while ($row = database::fetch($misc_query)) {
    $eur = (float)$row['val'];
  }


  $output = '<?xml version="1.0" encoding="UTF-8"?> ' . PHP_EOL . '
  <!DOCTYPE yml_catalog SYSTEM "shops.dtd"> ' . PHP_EOL . '
  <yml_catalog date="'.date("Y-m-d H:i").'"> ' . PHP_EOL . '
    <shop> ' . PHP_EOL . '
      <name>FitPit</name> ' . PHP_EOL . '
      <company>FitPit</company> ' . PHP_EOL . '
      <url>https://fitpit.com.ua/</url> ' . PHP_EOL . '
                
      <currencies> ' . PHP_EOL . '
        <currency id="EUR" rate="'.$eur.'"/> ' . PHP_EOL . '
      </currencies> ' . PHP_EOL . '
    </shop> ' . PHP_EOL . '
              
    <categories>' . PHP_EOL;


  function custom_output_categories($ids, &$output) {
    $categories_query =  database::query(
      "select c.id, c.parent_id, ci.name
      from ". DB_TABLE_CATEGORIES ." c
      left join ". DB_TABLE_CATEGORIES_INFO ." ci on (ci.category_id = c.id and ci.language_code = 'uk')
      where c.id in (".implode(',',$ids).");"
    );;

    while ($category = database::fetch($categories_query)) {


      $output .= '<category id="'.$category['id'].'">'.$category['name'].'</category>' . PHP_EOL;

      //custom_output_categories($category['id'], $output);
    }
  }

  

  $manufacturers = [2];

  $products_query = database::query(
    "select 
        p.id, ps.id sid, p.date_updated, p.default_category_id, p.image,
        pi.name, pi.description, 
        _e.manufacturer, _e.qty, _e.size, _e.flavour, _e.base_eur
      from products p
        left join products_info pi on (pi.product_id = p.id and pi.language_code = 'uk')
        left join _excel _e on (p.product_hash = _e.product_hash)
        left join products_options_stock ps on (ps.hash = _e.hash)
      where _e.qty >0 and p.status and p.manufacturer_id in (2)
        order by id;"
  );

  $cat_ids = [];
  $products = [];
  while ($product = database::fetch($products_query)) {
    array_push($cat_ids, $product['default_category_id']);
    array_push($products,$product);
  }

  custom_output_categories($cat_ids, $output);

  $output .= '
  </categories> ' . PHP_EOL . '
  <offers>' . PHP_EOL;

  foreach($products as $product) {

    $old_price = '';
    $picture = '';
    if($product['image'] != '') {
      $picture = '<picture>https://fitpit.com.ua/images/' . $product['image'] .'</picture> ' . PHP_EOL;
    }
    
    $output .= '  
          <offer id="'.$product['sid'].'" available="true"> ' . PHP_EOL . '
          
            <price>'.$product['base_eur'].'</price>'.$old_price.' ' . PHP_EOL . '
            <currencyId>EUR</currencyId> ' . PHP_EOL . '
            <categoryId>'.$product['default_category_id'].'</categoryId> ' . PHP_EOL . '
            '.$picture.' ' . PHP_EOL . '
            <vendor>'.$product['manufacturer'].'</vendor> ' . PHP_EOL . '
            <stock_quantity>'.$product['qty'].'</stock_quantity> ' . PHP_EOL . '
            <name>'.htmlspecialchars($product['manufacturer'].' '.$product['name'].' '.$product['flavour'].' '.$product['size']).'</name> ' . PHP_EOL . '
            <description><![CDATA['.htmlspecialchars($product['description']).']]></description> ' . PHP_EOL . '
            <param name="Смак">'.htmlspecialchars($product['flavour']).'</param> ' . PHP_EOL . '
            <param name="Упаковка">'.htmlspecialchars($product['size']).'</param> ' . PHP_EOL . '
          </offer> ' . PHP_EOL;
  }

  $output .= '
    </offers> ' . PHP_EOL . '
  </yml_catalog> ' . PHP_EOL;

  $output = language::convert_characters($output, language::$selected['charset'], 'UTF-8');

  header('Content-type: application/xml; charset=UTF-8');

  echo $output;
  exit;

<?php
  $query = trim($_GET['query']);
  $json = array();
  header('Content-type: application/json; charset='. language::$selected['charset']);
  if(empty($_GET['offset'])) {
    $categories_query = database::query(

      "select c.id, ci.name from ". DB_TABLE_CATEGORIES ." c
      left join ". DB_TABLE_CATEGORIES_INFO ." ci on (ci.category_id = c.id and ci.language_code = '". database::input(language::$selected['code']) ."')
      where c.status
      and ci.name like '%". database::input($_GET['query']) ."%'
      order by ci.name asc
      limit " . (int)$_GET['limit']
    );
    $i = 0;
    while($category = database::fetch($categories_query)) {
      $json[] = array('value' => $category['name'],
        'data' => array(
          'type' => 'category',
          'count' => !empty($_GET['decount']) ? false : true,
          'id' => $category['id'],
          'link' => document::ilink('category', array('category_id' => $category['id']))
      ));
      $i++;
    }
    if(empty($_GET['decount'])) {
      $_GET['limit'] = (int)$_GET['limit'] - $i;
    }
  }

  $products_query = functions::catalog_keywords_search(true);
  //$product = database::fetch($products_query);
  //var_dump($product);
  while($product = database::fetch($products_query)) {
    
    $short_description = preg_replace('/\r\n|\r|\n/', ' ', html_entity_decode(strip_tags($product['short_description'])));
    $short_description = functions::general_removeAccents($short_description);
    $long_description = preg_replace('/\r\n|\r|\n/', ' ', html_entity_decode(strip_tags($product['description'])));
    $long_description = functions::general_removeAccents($long_description);

    $desc = '';

    $pos = stripos($short_description, $query);
    if($pos) {
      $desc = $short_description;
    } else {
      $pos = stripos($long_description, $query);
      if($pos) {
        $desc = $long_description;
      }
    }
    if(!$pos) {
      $desc = $short_description ? $short_description : $long_description;
    }

    $desc = strip_tags (($pos > 20 ? '...' : '') . substr($desc, $pos > 20 ? $pos-20 : 0, 101) . (strlen($desc) > 100 ?  '...' : ''));
    $html_name = str_ireplace ($query, '<b>' . $query . '</b>', $product['name']);
    $desc  = str_ireplace ($query, '<b>' . $query . '</b>', $desc);
    $link = document::ilink('product', array('product_id' => $product['id']));
    $img_src = document::link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $product['image'], 64, 64, settings::get('product_image_clipping'), settings::get('product_image_trim')));
    
    $orderable = false;
    if($product['quantity'] > 0) {
      $orderable = true;
    }

    array_push($json, 
    array(
      'value' => $product['name'],
      'data' => array(
        'type' => 'product',
        'count' => true,
        'id' => $product['id'],
        'link' => $link,
        'name' => $product['name'],
        'manufacturer' => $product['manufacturer_name'],
        // 'desc' => $desc,
        'orderable' => $orderable,
        'img_src' => $img_src
        //'html' => '<img src="' . $img_src . '" alt=""/><div><button class="btn btn-success hidden-xs"' . (($product['quantity'] <= 0 && !$product['orderable']) ? 'disabled="disabled"' : '') .'><i class="fa fa-cart-plus"></i></button>' . $html_name . '<small>' . ($desc ? '<br><em>' . $desc . '</em>' : '') . '<br/>' . $product['manufacturer_name'] . '</small></div>'
    )));
  }
  //var_dump($product);
  language::convert_characters($json, language::$selected['charset'], 'UTF-8');
  $json = json_encode($json, JSON_UNESCAPED_SLASHES);

  language::convert_characters($json, 'UTF-8', language::$selected['charset']);
  echo $json;

exit;
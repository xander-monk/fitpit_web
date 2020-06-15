<?php
  if (!settings::get('box_latest_products_num_items')) return;

  functions::draw_lightbox();

  $box_latest_products_cache_token = cache::token('box_latest_products', array('language', 'currency', 'prices'), 'file');
  if (cache::capture($box_latest_products_cache_token)) {

    $product_ids = array();
    $products_ids = database::query(
      "select id from ". DB_TABLE_PRODUCTS ." p where new = 1;"
    );
    while($id = database::fetch($products_ids)) {
      array_push($product_ids,$id['id']);
    }
    // var_dump($product_ids);

    // $products_query = functions::catalog_products_query(array('products' => $product_ids, 'limit' => settings::get('box_popular_products_num_items')*2,'sql_where'=> 'p.quantity > 0'));

    $products_query = functions::catalog_products_query(array(
      'products' => $product_ids,
      'limit' => settings::get('box_latest_products_num_items'),
    ));

    if (database::num_rows($products_query)) {

      $box_latest_products = new ent_view();

      $box_latest_products->snippets['products'] = array();
      while ($listing_product = database::fetch($products_query)) {
        $box_latest_products->snippets['products'][] = $listing_product;
      }

      echo $box_latest_products->stitch('views/box_latest_products');
    }

    cache::end_capture($box_latest_products_cache_token);
  }

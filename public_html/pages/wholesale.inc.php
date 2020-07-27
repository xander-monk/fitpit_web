<?php

  header('X-Robots-Tag: noindex');
  document::$layout = 'wholesale';

  document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
  document::$snippets['title'][] = language::translate('wholesale:head_title', 'Wholesale');

  // var_dump(customer::require_wholesale()); die;
  customer::require_wholesale();

  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

  $_page = new ent_view();

  $_page = new ent_view();
  $_page->snippets['cart'] = array(
    'items' => array(),
    'link' => document::ilink('checkout'),
    'num_items' => cart::$total['items'],
  );

  foreach (cart::$items as $key => $item) {
    $item['thumbnail'] = functions::image_thumbnail(FS_DIR_APP . 'images/' . $item['image'], 64, 64, 'FIT_USE_WHITESPACING');
    $_page->snippets['cart']['items'][$key] = $item;
  }

  if (!empty(customer::$data['display_prices_including_tax'])) {
    $_page->snippets['cart_total'] = currency::format(cart::$total['value'] + cart::$total['tax']);
  } else {
    $_page->snippets['cart_total'] = currency::format(cart::$total['value']);
  }

  $_page->snippets['EUR'] = $eur = 29; // round(1/currency::$currencies['UAH']['value']);
  $_page->snippets['discount'] = $discount = customer::$data['discount'];
  $query = database::query("select *,
    (select id from products where product_hash = _excel.product_hash ) as product_id,
    '' as cart_key,
    0 as cart1, 
    0 as cart2, 
    0 as user_price 
  from _excel");//  limit 200
  $data = [];
  if (database::num_rows($query) > 0) {
    while ($row = database::fetch($query)) {
      $salemod = 0;
      if(!empty($row['sale']) && $row['sale'] != '') {
        $salemod = (float)$row['sale'] * 100 * -1;
      }
      $row['user_price_eur'] =  ceil (( $row['base'] * (1-(int)$salemod/100) ) * (1-(int)$discount/100));;
      $row['user_price'] = ceil (( $row['base'] * (1-(int)$salemod/100) ) * (1-(int)$discount/100) * $eur);
      array_push($data, $row);
    }
  }

  
  /*foreach (currency::$currencies as $currency_code => $currency) {
    $_page->snippets['currency'][$currency_code] = $currency;
  }*/
  $data = json_encode($data);
  $data = str_replace('\r','',$data);
  $data = str_replace('\n','<br>',$data);
  $data = str_replace("'",'&#39;',$data);
  $_page->snippets['data'] = $data;
  /*
  echo json_encode([
    'data' => $data
  ]);
  */
  
  
  echo $_page->stitch('pages/wholesale');

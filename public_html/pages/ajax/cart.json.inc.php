<?php
  header('Content-type: application/json; charset='. language::$selected['charset']);

  $query = database::query("SELECT * FROM `currencies`");
  $db_currencies = [];
  if (database::num_rows($query) > 0) {
    while ($row = database::fetch($query)) {
      $db_currencies[$row['code']] = $row;
    }  
  }

  $eur = 1/$db_currencies['UAH']['value'];
  $discount = customer::$data['discount'];

  $total = cart::$total['value'];
  if((int)$discount > 0) {
    // var_dump(cart::$total['value' ], 100-(int)$discount);
    $total = ( cart::$total['value' ] / (100-(int)$discount) ) * (int)$discount + cart::$total['value' ];
  }

  $json = array(
    'items' => array(),
    'quantity' => cart::$total['items'],
    'value' => !empty(customer::$data['display_prices_including_tax']) ? cart::$total['value'] + cart::$total['tax'] : cart::$total['value'],
    'formatted_value' => !empty(customer::$data['display_prices_including_tax']) ? currency::format(cart::$total['value'] + cart::$total['tax']) : currency::format(cart::$total['value']),
    'text_total' => language::translate('title_total', 'Total'),

    'v' => cart::$total['value'],
    'discount' => (int)$discount,
    'with_discount_eur' => round ((cart::$total['value']*(1-(int)$discount/100)), 2),
    'with_discount' => currency::format(cart::$total['value']*(1-(int)$discount/100))
  );
  //var_dump(cart::$items);die;
  foreach (cart::$items as $key => $item) {
    $json['items'][] = array(
      'product_id' => $item['product_id'],

      'product_hash' => $item['product_hash'],
      'hash' => @$item['hash'],
      'key' => $key,

      'name' => $item['name'],
      'quantity' => (float)$item['quantity'],
      'price' => (float)$item['price'],
      'formatted_price' => currency::format($item['price']),
      'link' => document::ilink('product', array('product_id' => $item['product_id'])),
    );
  }

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
  $json = json_encode($json, JSON_UNESCAPED_SLASHES);

  language::convert_characters($json, 'UTF-8', language::$selected['charset']);
  echo $json;

  exit;

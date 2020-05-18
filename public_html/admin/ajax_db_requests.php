<?php
  require_once('../includes/app_header.inc.php');
  user::require_login();
  document::$layout = 'ajax';

  if(empty($_POST['request'])) {
    exit;
  }

  function get($value) {
    if(empty($_POST[$value])) {
      exit;
    } else {
      return database::input($_POST[$value]);
    }
  }

  $query = '';
  $json = array();

  switch($_POST['request']) {
    case 'update_product_code' :
      $query = "update ". DB_TABLE_PRODUCTS ." set " . get('code_name') . " = '" . urldecode(get('code_value')) . "' where id = '" . get('product_id') . "'";
      break;
    case 'update_product_name' :
      $query = "update ". DB_TABLE_PRODUCTS_INFO ." set name = '" . urldecode(get('name')) . "' where product_id = '" . get('product_id') . "' and language_code = '" . language::$selected['code'] . "'";
      break;
    case 'update_product_purchase_price' :
      $query = "update ". DB_TABLE_PRODUCTS ." set purchase_price = '" . get('purchase_price') . "' where id = '" . get('product_id') . "'";
      break;
    case 'delete_product_campaigns' :
      $query = "delete from ". DB_TABLE_PRODUCTS_CAMPAIGNS ." where product_id = '" . get('product_id') . "'";
      break;
    case 'insert_product_campaign' :
      if(empty($_POST['currencies'])) {
        exit;
      }
      $fields = array();
      $values = array();
      foreach($_POST['currencies'] as $field => $value) {
        $fields[] = $field;
        $values[] = database::input($value);
      }
      $query = "insert into ". DB_TABLE_PRODUCTS_CAMPAIGNS ." (product_id, end_date, " . implode(', ', $fields) . ") values ('" . get('product_id') . "', date_format('" . get('date') . "', '%y-%m-%d'), '" . implode("',' ", $values) . "')";
      break;
    case 'update_product_price' :
      $query = "update ". DB_TABLE_PRODUCTS_PRICES ." set " . get('currency') . " = '" . get('price') . "' where product_id = '" . get('product_id') . "'";
      break;
    case 'update_product_quantity' :
      if(!empty($_POST['option_id']) && !empty($_POST['product_id'])) {
        database::query("update ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." set quantity = '" . get('quantity')  . "' where id = '" . database::input($_POST['option_id']) . "'");
        $results = database::fetch(database::query("select sum(quantity) as quantity from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." where product_id = '" . database::input($_POST['product_id']) . "'"));
        $json[] = $results['quantity'];
        $query = "update ". DB_TABLE_PRODUCTS . " set quantity = '" . $results['quantity'] . "' where id = '" . get('product_id') . "'";
      } else {
        $query = "update ". DB_TABLE_PRODUCTS . " set quantity = '" . get('quantity') . "' where id = '" . get('product_id') . "'";
      }
      break;
    case 'update_product_status' :
      $query = "update ". DB_TABLE_PRODUCTS . " set status = '" . get('status') . "' where id = '" . get('product_id') . "'";
      break;
  }

  if($query) {
    $results = database::query($query);

    if(substr($query, 0, 6) == 'select' && database::num_rows($results)) {
      while ($row = database::fetch($query)) {
        $json[] = $row;
      }
    }
  }

  if(count($json)) {
    header('Content-type: application/json; charset='. language::$selected['charset']);

    language::convert_characters($json, language::$selected['charset'], 'UTF-8');
    $json = json_encode($json, JSON_UNESCAPED_SLASHES);

    language::convert_characters($json, 'UTF-8', language::$selected['charset']);
    echo $json;
  }
  exit;

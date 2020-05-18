<?php
  function get_product_stock($product_id, $sku = 0, $decimals = 0) {
    if($product_id) {
      $query = "select * from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
      where product_id = ". (int)$product_id."
      order by priority";

    } elseif($sku) {
      $query = "select * from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
      where sku = '". database::input($sku) . "'";
    }
    $products_options_stock_query = database::query($query);
    $fields = '';
    $option = array('name'=> '', 'field' => '');
    while ($option_stock = database::fetch($products_options_stock_query)) {
      foreach (explode(',', $option_stock['combination']) as $combination) {
        list($group_id, $value_id) = explode('-', $combination);

        $options_values_query = database::query(
          "select ovi.value_id, ovi.name from ". DB_TABLE_OPTION_VALUES_INFO ." ovi where ovi.value_id = ". (int)$value_id ." and language_code = '". language::$selected['code'] . "'"
        );

        while ($option_value_info = database::fetch($options_values_query)) {
          foreach ($option_value_info as $key => $value) {
            if (empty($option_name[$key][$option_value_info['value_id']])) $option_name[$key][$option_value_info['value_id']] = $value;
          }
        }
      }
      $option['name'] = implode(', ', $option_name['name']);
      $option['field'] = form_draw_qty_fields($option_stock['quantity'], $decimals, 'data-id="' . $option_stock['id'] .'"');

      $fields .= '<p>' . $option['name'] . ':</p>' . $option['field'];
    }
    return $sku ? $option : $fields;
  }

  $stock_mvnt = empty($_COOKIE['stock_mvnt']) ? 'balance' : $_COOKIE['stock_mvnt'];
  function form_draw_qty_fields($quantity, $decimals, $parameters = '') {
    global $stock_mvnt;

    return

    functions::form_draw_decimal_field('qty_in', 0, $decimals, 0, null, $stock_mvnt == 'in' ? '' : 'style="display:none"') .
    functions::form_draw_decimal_field('qty_out', 0, $decimals, 0, null, $stock_mvnt == 'out' ? '' : 'style="display:none"') .
    functions::form_draw_decimal_field('qty', $quantity, $decimals, 0, null, $parameters . ' data-val="' . $quantity . '"' . ($stock_mvnt == 'balance' ? '' : ' style="display:none"'));
  }

  if(!empty($_GET['get_product_stock'])) {
    echo get_product_stock($_GET['get_product_stock'], 0, (int)$_GET['qtydec']);
    exit;
  }

  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

  if (empty($_GET['category_id'])) $_GET['category_id'] = 0;

  if (isset($_POST['enable']) || isset($_POST['disable'])) {

    try {
      if (empty($_POST['categories']) && empty($_POST['products'])) {
        throw new Exception(language::translate('error_must_select_categories_or_products', 'You must select categories or products'));
      }

      if (!empty($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
          $category = new ent_category($category_id);
          $category->data['status'] = !empty($_POST['enable']) ? 1 : 0;
          $category->save();
        }
      }

      if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product_id) {
          $product = new ent_product($product_id);
          $product->data['status'] = !empty($_POST['enable']) ? 1 : 0;
          $product->save();
        }
      }

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link());
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['duplicate'])) {

    try {
      if (!empty($_POST['categories'])) throw new Exception(language::translate('error_cant_duplicate_category', 'You can\'t duplicate a category'));
      if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));
      if (empty($_POST['category_id'])) throw new Exception(language::translate('error_must_select_category', 'You must select a category'));

      foreach ($_POST['products'] as $product_id) {
        $original = new ent_product($product_id);
        $product = new ent_product();

        $product->data = $original->data;
        $product->data['id'] = null;
        $product->data['status'] = 0;
        $product->data['code'] = '';
        $product->data['sku'] = '';
        $product->data['mpn'] = '';
        $product->data['gtin'] = '';
        $product->data['categories'] = array($_POST['category_id']);
        $product->data['image'] = null;
        $product->data['images'] = array();

        foreach (array('attributes', 'campaigns', 'options', 'options_stock') as $field) {
          if (empty($product->data[$field])) continue;
          foreach (array_keys($product->data[$field]) as $key) {
            $product->data[$field][$key]['id'] = null;
          }
        }

        if (!empty($original->data['images'])) {
          foreach ($original->data['images'] as $image) {
            $product->add_image(FS_DIR_APP . 'images/' . $image['filename']);
          }
        }

        foreach (array_keys($product->data['name']) as $language_code) {
          $product->data['name'][$language_code] .= ' (copy)';
        }

        $product->data['status'] = 0;
        $product->save();
      }

      notices::add('success', sprintf(language::translate('success_duplicated_d_products', 'Duplicated %d products'), count($_POST['products'])));
      header('Location: '. document::link(WS_DIR_ADMIN, array('category_id' => $_POST['category_id']), true));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['copy'])) {

    try {
      if (!empty($_POST['categories'])) throw new Exception(language::translate('error_cant_copy_category', 'You can\'t copy a category'));
      if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));
      if (isset($_POST['category_id']) && $_POST['category_id'] == '') throw new Exception(language::translate('error_must_select_category', 'You must select a category'));

      foreach ($_POST['products'] as $product_id) {
        $product = new ent_product($product_id);
        $product->data['categories'][] = $_POST['category_id'];
        $product->save();
      }

      notices::add('success', sprintf(language::translate('success_copied_d_products', 'Copied %d products'), count($_POST['products'])));
      header('Location: '. document::link(WS_DIR_ADMIN, array('category_id' => $_POST['category_id']), true));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['move'])) {

    try {
      if (empty($_POST['categories']) && empty($_POST['products'])) throw new Exception(language::translate('error_must_select_category_or_product', 'You must select a category or product'));
      if (isset($_POST['category_id']) && $_POST['category_id'] == '') throw new Exception(language::translate('error_must_select_category', 'You must select a category'));
      if (isset($_POST['category_id']) && isset($_POST['categories']) && in_array($_POST['category_id'], $_POST['categories'])) throw new Exception(language::translate('error_cant_move_category_to_itself', 'You can\'t move a category to itself'));

      if (isset($_POST['category_id']) && isset($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
          if (in_array($_POST['category_id'], array_keys(functions::catalog_category_descendants($category_id)))) {
            throw new Exception(language::translate('error_cant_move_category_to_descendant', 'You can\'t move a category to a descendant'));
            break;
          }
        }
      }

      if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product_id) {
          $product = new ent_product($product_id);
          $product->data['categories'] = array($_POST['category_id']);
          $product->save();
        }
        notices::add('success', sprintf(language::translate('success_moved_d_products', 'Moved %d products'), count($_POST['products'])));
      }

      if (!empty($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
          $category = new ent_category($category_id);
          $category->data['parent_id'] = $_POST['category_id'];
          $category->save();
        }
        notices::add('success', sprintf(language::translate('success_moved_d_categories', 'Moved %d categories'), count($_POST['categories'])));
      }

      header('Location: '. document::link(WS_DIR_ADMIN, array('category_id' => $_POST['category_id']), true));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['unmount'])) {

    try {
      if (empty($_POST['categories']) && empty($_POST['products'])) throw new Exception(language::translate('error_must_select_category_or_product', 'You must select a category or product'));
      if (empty($_GET['category_id'])) throw new Exception(language::translate('error_category_must_be_nested_in_another_category_to_unmount', 'A category must be nested in another category to be unmounted'));

      if (!empty($_POST['categories'])) {
        foreach ($_POST['categories'] as $category_id) {
          $category = new ent_category($category_id);
          if ($category->data['parent_id'] == $_GET['category_id']) {
            $category->data['parent_id'] = 0;
            $category->save();
          }
        }
        notices::add('success', sprintf(language::translate('success_unmounted_d_categories', 'Unmounted %d categories'), count($_POST['categories'])));
      }

      if (!empty($_POST['products'])) {
        foreach ($_POST['products'] as $product_id) {
          $product = new ent_product($product_id);
          foreach (array_keys($product->data['categories']) as $key) {
            if ($product->data['categories'][$key] == $_GET['category_id']) {
              unset($product->data['categories'][$key]);
              $product->save();
            }
          }
        }
        notices::add('success', sprintf(language::translate('success_unmounted_d_products', 'Unmounted %d products'), count($_POST['products'])));
      }

      if (isset($_POST['categories']) && in_array($_GET['category_id'], $_POST['categories'])) unset($_GET['category_id']);

      header('Location: '. document::link(WS_DIR_ADMIN, array(), true));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (!empty($_POST['categories'])) throw new Exception(language::translate('error_only_products_are_supported', 'Only products are supported for this operation'));
      if (empty($_POST['products'])) throw new Exception(language::translate('error_must_select_products', 'You must select products'));

      foreach ($_POST['products'] as $product_id) {
        $product = new ent_product($product_id);
        $product->delete();
      }

      notices::add('success', sprintf(language::translate('success_deleted_d_products', 'Deleted %d products'), count($_POST['products'])));
      header('Location: '. document::link());
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }


  if(!isset($_COOKIE['products_ref'])) {
    $_COOKIE['products_ref'] = 'gtin';
  }
  if(!isset($_COOKIE['products_currency'])) {
    $_COOKIE['products_currency'] = settings::get('store_currency_code');
  }
  $output = '';
  $tax_classes = array();
  $tax_class_query = database::query("select * from ". DB_TABLE_TAX_CLASSES);
  while ($tax_class = database::fetch($tax_class_query)) {
    $tax_classes[$tax_class['id']] = tax::get_tax(100, $tax_class['id'], 'store');
  }
  $currencies_mysql_cols = array();
  foreach (currency::$currencies as $currency) {
    $currencies_mysql_cols[] = 'pp.'. $currency['code'] . ', pc.' . $currency['code'] . ' as pc_' . $currency['code'];
  }
  $currencies_mysql_cols = implode(', ', $currencies_mysql_cols);

  $select_sql = "p.*, pi.name, p2c.category_id, m.id as manufacturer_id, m.name as manufacturer_name, qu.decimals as qty_decimals, " . $currencies_mysql_cols . ", pc.start_date, pc.end_date";

  $from_sql = DB_TABLE_PRODUCTS ." p
  left join ". DB_TABLE_PRODUCTS_PRICES ." pp on pp.product_id = p.id
  left join ". DB_TABLE_PRODUCTS_CAMPAIGNS ." pc on pc.id =
  (
  select id from ". DB_TABLE_PRODUCTS_CAMPAIGNS ."
  where product_id = p.id and start_date < now() and end_date >= now()
  order by end_date desc
  limit 1
  )
  left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
  left join ". DB_TABLE_PRODUCTS_TO_CATEGORIES ." p2c on (p2c.product_id = p.id)
  left join ". DB_TABLE_MANUFACTURERS ." m on (p.manufacturer_id = m.id)
  left join ". DB_TABLE_QUANTITY_UNITS ." qu on (qu.id = p.quantity_unit_id)";

  $category_id = '';
  $query = '';
  $ajax_single_product_row = false;

  if (!empty($_GET['manufacturer_id'])) {
    $products_query = database::query("select $select_sql from $from_sql
      where m.id = '". (int)$_GET['manufacturer_id'] ."'
      group by p.id
      order by pi.name asc;"
    );
  } else if (!empty($_GET['query'])) {
    $query = database::input($_GET['query']);

    if(!empty($_GET['ref'])) {
      $ajax_single_product_row = true;

      if($_GET['ref'] == 'SKU') {
        $products_query = database::query("select $select_sql, pos.sku as option_sku
          from $from_sql
          left join ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ." pos on pos.product_id = p.id and pos.sku = '". $query ."'
          where pos.sku = '" . $query . "'");
      } else {
        $products_query = database::query("select $select_sql
          from $from_sql
          where p.id = '" . $query . "'");
      }
    } else {
      $code_regex = database::input(functions::format_regex_code($query));

      $products_query = database::query("select $select_sql,
        (
        if(p.id = '". $query ."', 10, 0)
        + (match(pi.name) against ('*". $query ."*'))
        + (match(pi.short_description) against ('*". $query ."*') / 3)
        + (match(pi.description) against ('*". $query ."*') / 4)
        + (match(pi.name) against ('". $query ."' in boolean mode))
        + (match(pi.short_description) against ('". $query ."' in boolean mode) / 2)
        + (match(pi.description) against ('". $query ."' in boolean mode) / 3)
        + if(m.name like '%". $query ."%', 4, 0)
        + if(pi.name like '%". $query ."%', 3, 0)
        + if(pi.short_description like '%". $query ."%', 2, 0)
        + if(pi.description like '%". $query ."%', 1, 0)
        + if(p.code regexp '". $code_regex ."', 5, 0)
        + if(p.sku regexp '". $code_regex ."', 5, 0)
        + if(p.mpn regexp '". $code_regex ."', 5, 0)
        + if(p.gtin regexp '". $code_regex ."', 5, 0)
        + if (p.id in (
        select product_id from ". DB_TABLE_PRODUCTS_OPTIONS_STOCK ."
        where sku regexp '". $code_regex ."'
        ), 5, 0)
        ) as relevance
        from $from_sql
        having relevance > 0
        order by relevance desc;"
      );
    }
  } else {
    if($_GET['page'] == 1) {
      $categories_query = database::query(
        "select c.id, c.status, ci.name
        from ". DB_TABLE_CATEGORIES ." c
        left join ". DB_TABLE_CATEGORIES_INFO ." ci on (ci.category_id = c.id and ci.language_code = '". language::$selected['code'] ."')
        where c.parent_id = ". (int)$_GET['category_id'] ."
        order by c.priority asc, ci.name asc;"
      );

      while ($category = database::fetch($categories_query)) {
        $output .= '<tr class="box category'. (!$category['status'] ? ' semi-transparent"' : '') .'">
        <td></td>
        <td>'. functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($category['status']) ? '#88cc44' : '#ff6644') .';"') .'</td>
        <td><a href="'. document::href_link('', array('category_id' => $category['id']), true) .'">'. functions::draw_fonticon('fa-folder', 'style="color: #cccc66;font-size: 64px;"') . '</a></td>
        <td colspan="7"><a href="'. document::href_link('', array('category_id' => $category['id']), true) .'">' . ($category['name'] ? $category['name'] : '[untitled]') .'</a></td>
        </tr>';
      }
    }

    $products_query = database::query("select $select_sql from $from_sql
      where p2c.category_id = '". (int)$_GET['category_id'] ."'
      group by p.id
      order by pi.name asc;"
    );
  }
  $num_product_rows = 0;
  $num_rows = database::num_rows($products_query);

  if (database::num_rows($products_query) && $num_rows > ($_GET['page']-1) * settings::get('data_table_rows_per_page')) {

    if ($_GET['page'] > 1) database::seek($products_query, (settings::get('data_table_rows_per_page') * ($_GET['page']-1)));

    $page_items = 0;
    while ($product = database::fetch($products_query)) {
      $num_product_rows++;

      try {
        $warning = null;

        if ($product['date_valid_from'] > date('Y-m-d H:i:s')) {
          throw new Exception(strtr(language::translate('text_product_cannot_be_purchased_until_x', 'The product cannot be purchased until %date'), array('%date' => language::strftime(language::$selected['format_date'], strtotime($product['date_valid_from'])))));
        }

        if ($product['date_valid_to'] > '1971' && $product['date_valid_to'] < date('Y-m-d H:i:s')) {
          throw new Exception(strtr(language::translate('text_product_expired_at_x', 'The product expired at %date and can no longer be purchased'), array('%date' => language::strftime(language::$selected['format_date'], strtotime($product['date_valid_to'])))));
        }

        if ($product['quantity'] <= 0) {
          throw new Exception(language::translate('text_product_is_out_of_stock', 'The product is out of stock'));
        }

      } catch (Exception $e) {
        $warning = $e->getMessage();
      }

      $is_campaign = strtotime($product['end_date']) > time();
      $output .=
      '<tr class="box product' . (!$product['status'] ? ' semi-transparent' : '') . '" data-id="' . $product['id'] . '"' . (document::$layout == 'ajax' && !$ajax_single_product_row ? ' style="display:none"' : '') . ' data-qtydec="' . (int)$product['qty_decimals']  . '" data-ref="' . (!empty($product['option_sku']) ? 'SKU=' . $product['option_sku'] : 'PID=' . $product['id']) . '">
      <td class="check">' . functions::form_draw_checkbox('products['. $product['id'] .']', $product['id']) . '</td>

      <td class="status">' . functions::draw_fonticon('fa-circle', 'style="color: '. (!empty($product['status']) ? '#88cc44' : '#ff6644') .';"') . '</td>

      <td class="image"><a href="' . document::href_link('', array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => $product['id'])) . '" target="_blank"><img src="'. document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $product['image'], 64, 64, 'FIT_USE_WHITESPACING')) .'" alt="" /></a></td>';

      $option = array('name' => '', 'field' => '');
      if($ajax_single_product_row) {
        $option = get_product_stock(0, $query, $product['qty_decimals']);
      }

      $output .=
      '<td class="name-code click">' . ($option['name'] ?  '<div class="option">' . $option['name'] . '</div>' : '') . functions::form_draw_input('name', $product['name'], 'text', 'class="name"') . '<a style="display:none">' . $product['name'] . '</a><div class="manufacturer" data-id="' . $product['manufacturer_id'] . '">' . functions::draw_fonticon('fa-edit') . '<a href="' . document::href_link('', array('app' => 'catalog', 'doc' => 'catalog', 'manufacturer_id' => $product['manufacturer_id']), false) . '"><small>' . $product['manufacturer_name'] . '</small></a></div>' .

      (!empty($product['option_sku']) ? '<div class="sku">SKU:</div>' . functions::form_draw_input('option_sku', $product['option_sku'], 'text', 'readonly') :
        functions::form_draw_input('code', $product['code'], 'text', ($_COOKIE['products_ref'] == 'code' ? '' : 'style="display:none"')) .
        functions::form_draw_input('sku', $product['sku'], 'text', ($_COOKIE['products_ref'] == 'sku' ? '' : 'style="display:none"')) .
        functions::form_draw_input('mpn', $product['mpn'], 'text', ($_COOKIE['products_ref'] == 'mpn' ? '' : 'style="display:none"')) .
        functions::form_draw_input('upc', $product['upc'], 'text', ($_COOKIE['products_ref'] == 'upc' ? '' : 'style="display:none"')) .
        functions::form_draw_input('gtin', $product['gtin'], 'text', ($_COOKIE['products_ref'] == 'gtin' ? '' : 'style="display:none"')) .
        functions::form_draw_input('taric', $product['taric'], 'text', ($_COOKIE['products_ref'] == 'taric' ? '' : 'style="display:none"'))) .

      '</td>';

      $output .=
      '<td class="purchase price click">' . functions::form_draw_decimal_field('purchase_price', $product['purchase_price'], currency::$currencies[settings::get('store_currency_code')]['decimals'] + 2, 0, null, ' ~2?purchase_price') . '</td>
      <td class="selling price' . (user::is_permitted('modify_selling_price') && $is_campaign ? ' campaign' : '') .'">' . functions::form_draw_date_field('date', $product['end_date'], !user::is_permitted('modify_selling_price') ? 'disabled="disabled"' : '') . functions::draw_fonticon('fa-times-circle fa-lg');

      foreach (currency::$currencies as $currency) {
        $tax = (1 + $tax_classes[$product['tax_class_id']] / 100);
        $initial_price = $product[$currency['code']] * $tax;
        $campaign_price = '';
        if($is_campaign) {
          $campaign_price = $product['pc_' . $currency['code']] * $tax;
        }
        $output .= functions::form_draw_decimal_field('price_' . $currency['code'], $is_campaign ? $campaign_price : $initial_price, currency::$currencies[$currency['code']]['decimals'], 0, null, 'data-currency="' . $currency['code'] . '" data-tax="' . $tax_classes[$product['tax_class_id']] . '" data-initial="' . $initial_price . '" class="form-control ' . ($_COOKIE['products_currency'] == $currency['code'] ? 'active' : '') . '" ~2?selling_price');
      }

      $output .= '</td>';

      $output .=
      '<td class="stock' . ($ajax_single_product_row ? ' field' : '') . '">';

      if (user::is_permitted('read_stock_quantities')) {
        if($ajax_single_product_row) {
          $output .= $option['field'] ? $option['field'] : form_draw_qty_fields($product['quantity'], $product['qty_decimals']);
        } else {
          $output .= '<div class="stock-wrapper">' .  number_format($product['quantity'], $product['qty_decimals']) . '</div><div class="stock-fields"><p>' . language::translate('title_qty', 'Qty') . ': </p>' . form_draw_qty_fields($product['quantity'], $product['qty_decimals'], 'data-id="0"') . '</div>';
        }
      } else {
        $output .= 'XXX';
      }

      $output .= '</td>
      <td class="warning actions click">' . (!empty($warning) ? functions::draw_fonticon('fa-exclamation-triangle', 'title="'. htmlspecialchars($warning) .'"') : '') . '</td>

      <td class="actions"><a href="' . document::href_ilink('product', array('product_id' => $product['id'])) . '" title="' . language::translate('title_view', 'View') . '" target="_blank">' . functions::draw_fonticon('fa-external-link') . '</a></td>

      <td class="actions text-right"></td>
      </tr>';
      if (empty($_GET['all']) && ++$page_items == settings::get('data_table_rows_per_page')) break;
    }
  }

  $num_pages = ceil($num_rows/settings::get('data_table_rows_per_page'));
  if(document::$layout == 'ajax') {
    if($num_product_rows) {
      echo $output;
    }
    exit;
  }
?>
<style>
  #content {
    padding-bottom: 0;
  }
  .warning {
    color: #f00;
  }
  .panel-app .panel-action {
    text-align: right;
    white-space: nowrap;
    position: relative;
  }
  .blur {
    -webkit-filter: saturate(0%);
    -moz-filter: saturate(0%);
    -o-filter: saturate(0%);
    -ms-filter: saturate(0%);
    filter: saturate(0%);
    pointer-events: none;
    opacity: 0.5
  }
  .data-table {
    border-collapse: initial;
    padding-bottom: 40px;
  }
  .data-table .product:hover {
    cursor: pointer;
  }
  .data-table .selected > * {
    background-color: rgba(50, 75, 145, 0.3);
  }
  .data-table th {
    position: sticky;
    top: -1px;
    background: #f5f5f5;
    z-index:100;
  }
  .data-table th, .data-table td {
    vertical-align: middle !important;
  }
  .data-table .price, .data-table .name-code, .data-table .stock.field {
    vertical-align: bottom !important;
  }
  .data-table .selling.price input[name^="price"] {
    display:none
  }
  .data-table .selling.price input[name^="price"].active {
    display:block;
  }
  .data-table .selling.price.campaign input[name^="price"] {
    background-color: #e0e0e0;
  }
  .data-table .selling.price .fa-times-circle {
    margin-top: 6px;
    color:#bbb;
  }
  .data-table .selling.price.campaign .fa-times-circle {
    color:#cc3333;
    cursor:pointer;
  }
  .data-table div.manufacturer {
    float:left;
    margin: 4px;
  }
  .data-table a {
    color: #555;
    font-weight: bold;
  }
  .data-table input[name="name"] {
    width: 100%;
    border: none;
    background: none;
    color: #555;
    font-weight: bold;
    text-overflow: ellipsis;
  }
  .data-table td.name-code {
    width:80%;
    position:relative;
  }
  .data-table .option {
    color: #555;
    margin-left: 2px;
    font-size: smaller;
    line-height: 10px;
  }
  .data-table .sku {
    position: absolute;
    right: 160px;
    bottom: 12px;
    color: #bbb;
  }
  .data-table input[type="number"], .data-table input[type="text"]:not([name="name"]), .data-table .select-wrapper {
    width: 150px;
    float: right;
  }
  .data-table input[name^="qty"] {
    width: 70px;
  }
  .data-table input[name="qty_in"], .data-table button[name="stock_mvnt"].in {
    background-color: #b2e6a2;
  }
  .data-table input[name="qty_out"], .data-table button[name="stock_mvnt"].out {
    background-color: #e6a2a2;
  }
  .data-table input[type="date"] {
    padding: 2px;
    max-width: 130px !important;
    display: inline;
    float: left;
  }
  .data-table a:hover {
    text-decoration: none;
  }
  .data-table .stock {
    position: relative;
  }
  .data-table .stock-fields {
    text-align: left;
  }
  .data-table .stock p {
    margin-bottom: 0;
  }
  .data-table .stock-fields {
    display: none;
    min-width: 160px;
    min-height: 80px;
    position: absolute;
    border-radius: 4px 0 0 4px;
    background-color: #f5f5f5;
    padding: 4px;
    box-shadow: 3px 0 10px rgba(1,1,1,0.5);
    z-index: 100;
    top: 0;
  }
  .data-table .stock-wrapper.wrap {
    position: absolute;
    border-radius: 0 4px 4px 0;
    background-color: #f5f5f5;
    top: 0;
    left:-1.5px;
    z-index: 101;
    box-shadow: 3px 0 10px rgba(1,1,1,0.5);
    border-left: 0;
    vertical-align: middle;
    line-height: 80px;
    height: 80px;
    text-align: center;
    clip-path: inset(-10px -10px -10px 0.5px);
  }
  .data-table .stock-fields p {
    margin: 0;
    clear: both;
  }
  .data-table .quantity {
    width: 60px !important;
  }
  .semi-transparent .actions * {
    visibility: hidden;
  }
  .data-table .semi-transparent input:not([type='checkbox']) {
    pointer-events:none;
  }
  .panel-footer {
    text-align:center;
  }
  #action-bar {
    position:fixed;
    width: 100%;
    bottom: 0;
    background: #f5f5f5;
    border-top: 1px solid #bbb;
    margin: 0 -20px 0 -20px;
  }
</style>
<?php
  functions::draw_lightbox();

  $name_query = '';
  if(!empty($_GET['manufacturer_id'])) {
    $name_query = database::query("select name from ". DB_TABLE_MANUFACTURERS ." where id = '". (int)$_GET['manufacturer_id'] . "'");
  } else if(!empty($_GET['category_id']) && empty($_GET['query'])) {
    $name_query = database::query("select name from ". DB_TABLE_CATEGORIES_INFO ." where id = '". (int)$_GET['category_id'] . "' and language_code ='" . language::$selected['code'] . "'");
  }
  if($name_query && database::num_rows($name_query)) {
    $result = database::fetch($name_query);
    $title = $result['name'];
  } else {
    if(!empty($_GET['query'])) {
      $title = language::translate('text_search_products', 'Search products') . ': ' . $_GET['query'];
    } else {
      $title = language::translate('title_catalog', 'Catalog');
    }
  }
?>
<div class="panel catalog panel-app<?php echo !empty($_GET['query']) ? ' query' : ''?>">
  <div class="panel-heading">
    <?php echo $app_icon . $title ?>
  </div>
  <?php
    if($num_rows > 1) {
  ?>
    <div class="panel-filter" style="margin-top: 4px;">
      <div class="input-group" style="width: 100%;margin:auto;">
        <span class="input-group-icon"><i class="fa fa-search fa-fw"></i></span>
        <input class="form-control" type="search" name="search_products" value="" data-type="search" placeholder="<?php echo language::translate('text_search_products', 'Search products') ?>" onkeydown="if (event.keyCode == 13) location=('<?php  echo  document::link(WS_DIR_ADMIN, array(), true, array('page', 'query', 'category_id')) .'&query=' ?>' + encodeURIComponent(this.value))">
      </div>
      <?php
        if(empty($_GET['query'])) {
      ?>
        <ul class="list-inline" style="white-space:nowrap;">
          <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc'=> 'edit_category', 'category_id' => $_GET['category_id'], 'iframe' => 'true')), language::translate('title_edit_category', 'Edit Category'), 'data-toggle="lightbox" data-type="iframe" data-width="1030px" data-height="50vh"', 'fa-pencil'); ?></li>
          <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc'=> 'edit_category', 'parent_id' => $_GET['category_id'], 'iframe' => 'true')), language::translate('title_add_new_category', 'Add New Category'), 'data-toggle="lightbox" data-type="iframe" data-width="1030px" data-height="50vh"', 'add'); ?></li>
          <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('app' => $_GET['app'], 'doc'=> 'edit_product', 'iframe' => 'true'), array('category_id')), language::translate('title_add_new_product', 'Add New Product'), 'data-toggle="lightbox" data-type="iframe" data-width="1030px" data-height="50vh"', 'add'); ?></li>
        </ul>
      <?php
        }
      ?>
    </div>
  <?php
    }
    $sign = $stock_mvnt == 'balance' ? 'balance-scale' : 'sign-' . $stock_mvnt;
  ?>

  <?php echo functions::form_draw_form_begin('catalog_form', 'post'); ?>
  <table class="table table-hover data-table">
    <thead>
      <tr>
        <th><?php echo functions::form_draw_checkbox('check_all', false); ?></th>
        <th colspan="2"></th>
        <th class="name-code"><?php echo '<div style="float:left;margin-top: 7.5px;">' . language::translate('title_name', 'Name') . '</div>' . form_draw_select_field('products_ref', array(array('CODE','code'), array('SKU','sku'), array('MPN','mpn'), array('UPC','upv'), array('GTIN','gtin'), array('TARIC','taric')), $_COOKIE['products_ref']); ?></th>
        <th class="input"><?php echo language::translate('title_purchase_price', 'Purchase Price'); ?></th>
        <th class="input"><?php echo form_draw_currencies_list('products_currency', $_COOKIE['products_currency']); ?></th>
        <th class="input qty"><?php echo language::translate('title_stock', 'Stock'); ?></th>
        <th colspan="3"><?php echo (user::is_permitted('modify_stock_quantities') ? '<button type="button" name="stock_mvnt" class="btn ' . $stock_mvnt .'"><i class="fa fa-' . $sign . '"></i></button>' : '') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
        echo $output;
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5"><?php echo language::translate('title_products', 'Products'); ?>: <span id="nb_products"><?php echo $num_product_rows; ?></span></td>
      </tr>
    </tfoot>
  </table>

  <ul id="action-bar" class="list-inline">
    <li class="actions"><?php echo language::translate('text_with_selected', 'With selected'); ?>:</li>
    <li class="actions">
      <div class="btn-group">
        <?php echo functions::form_draw_button('enable', language::translate('title_enable', 'Enable'), 'submit', '', 'on'); ?>
        <?php echo functions::form_draw_button('disable', language::translate('title_disable', 'Disable'), 'submit', '', 'off'); ?>
      </div>
    </li>
    <li class="actions onselected">
      <div class="btn-group">
        <?php echo functions::form_draw_button('move', language::translate('title_move', 'Move'), 'button', 'data-message="' . language::translate('warning_mounting_points_will_be_replaced', 'Warning: All current mounting points will be replaced.') . '"'); ?>
        <?php echo functions::form_draw_button('copy', language::translate('title_copy', 'Copy'), 'button'); ?>
        <?php echo functions::form_draw_button('duplicate', language::translate('title_duplicate', 'Duplicate'), 'button'); ?>
      </div>
    </li>
    <li class="actions">
      <div class="btn-group">
        <?php echo functions::form_draw_button('unmount', language::translate('title_unmount', 'Unmount'), 'submit'); ?>
        <?php echo functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. str_replace("'", "\\\'", language::translate('text_are_you_sure', 'Are you sure?')) .'\')) return false;"'); ?>
      </div>
    </li>
    <li id="operate" style="display:none">
      <?php echo functions::form_draw_button('ok', language::translate('title_ok', 'OK'), 'submit', 'id="ok-button"') ?>
      <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'id="cancel-button"'); ?>
    </li>
  </ul>

  <?php echo functions::form_draw_form_end(); ?>
</div>
<script>
  function getCookie(name, def) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : (typeof def != 'undefined' ? def : '');
  }
  function setCookie(name, value) {
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*365);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
  }
  function pId(that) {
    return that.closest('.product').data('id');
  }

  $(document).ready(function() {
    var product_link = '<?php echo document::link('', array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => '__', 'iframe' => 'true')) ?>';
    var manufacturer_link = '<?php echo document::link('', array('app' => 'catalog', 'doc' => 'edit_manufacturer', 'manufacturer_id' => '__', 'iframe' => 'true')) ?>';

    $('.data-table .product').find('input, a, i:not(.fa-edit), .stoc').on('click', function(e) {
      e.stopPropagation();
    });

    $('.data-table .product td.click').on('click', function(e) {
      e.stopPropagation();
      $.featherlight(product_link.replace('__', pId($(this))),  {type: 'iframe', width: '1030px', height: '50vh' });
    });
    $('.data-table .manufacturer').on('click', function(e) {
      e.stopPropagation();
      $.featherlight(manufacturer_link.replace('__', $(this).closest('.manufacturer').data('id')),  {type: 'iframe', width: '1030px', height: '50vh' });
    });


    $('select[name="products_ref"]').on('change', function() {
      setCookie('products_ref', $(this).val());
      $('.data-table .name-code input').css('display', 'none');
      $('.data-table .name-code input[name="' + $(this).val() + '"]').css('display', '');
    });
    $('select[name="products_currency"]').on('change', function() {
      setCookie('products_curreny', $(this).val());
      $('.data-table .selling.price input[name^="price"]').removeClass('active');
      $('.data-table .selling.price input[name^="price"][data-currency="' + $(this).val() + '"]').addClass('active');
    });

    $('.data-table').on({
      'focus': function() {
        $('form[name="catalog_form"] input[type="submit"]').prop("disabled", true);
      },
      'blur': function() {
        $('form[name="catalog_form"] input[type="submit"]').prop("disabled", false);
      }
    });

    // input fields

    function updateAjax(data, response) {
      $.ajax({
        data: data,
        url: 'ajax_db_requests.php', type: 'post', cache: false, async: true, dataType: 'json',
        success: function(data) {
          if(response) {
            response(data);
          }
        },
        beforeSend: function(jqXHR) { jqXHR.overrideMimeType('text/html;charset=' + $('meta[charset]').attr('charset')); }
      });
    }

    var a = '';
    var b = '';
    var backup = false;
    var operation = false;

    $('.data-table').on({
      'focus': function() {
        $(this).select();
        setCookie('input_focus', $(this).attr('name'));
      },
      'keydown': function(e) {
        if($.inArray(e.key, ['Enter', 'Tab']) !== -1) {
          e.preventDefault();
          var move_to_next = true;
          var do_it = true;

          if($(this).closest('.product').has('input[type="checkbox"]:checked').length) {
            var selected = $('.data-table tr, .data-table div').has('input[type="checkbox"]:checked').find('input[name="' + $(this).attr('name') + '"]');
            if(selected.length > 1) {
              var message = '<?php echo language::translate('confirm_modify_x_values', 'Modify %d selected values ?') ?>';
              if(window.confirm(message.replace('%d', selected.length))) {
                move_to_next = false;
                if(operation && backup) {
                  a = ''; b = $(this).val();
                  selected.not($(this)).trigger('operation');
                } else {
                  selected.val($(this).val()).trigger('update');
                  if($(this).attr('type') == 'number') {
                    $(this).trigger('change');
                  }
                }
              } else {
                do_it = false;
                $(this).trigger('restore');
              }
            }
          }
          if(do_it && operation && backup) {
            a = backup; b = '';
            $(this).trigger('operation');
          }
          $(this).trigger('reset').trigger('update');
          if(e.key == 'Enter' && $('.panel').hasClass('query')) {
            $('#search input[name="query"]').focus().select();
          } else if(move_to_next) {
            $(this).trigger('move_to_next', {shift: e.shiftKey});
          }
        } else if(e.key == 'Escape') {
          $(this).trigger('restore').select();
        } else if($.inArray(e.key, ['+', '-', '*', '/']) !== -1 && ($(this).prop('type') == 'number' || operation)) {
          if($(this).attr('name').match('qty_')) {
            e.preventDefault();
          } else {
            if(!operation) {
              operation = $(this).val();
              $(this).prop('type', 'text');
              backup = $(this).val();
            }
            $(this).val('');
          }
        } else if($(this).val() != '' && !backup) {
          backup = $(this).val();
        }
      },
      'reset': function() {
        if(operation) {
          $(this).prop('type', 'number');
        }
        operation = false;
        backup = false;
      },
      'restore': function() {
        if(backup) {
          $(this).val(backup).trigger('reset');
        }
        backup = false;
      },
      'operation': function() {
        $(this).val(Number(eval(a + $(this).val() + b)).toFixed($(this).data('decimals'))).trigger('change');
      },
      'move_to_next': function(e, p) {
        var next = false;
        var is_qty = false;
        if($(this).attr('name').match('qty')) {
          is_qty = true;
          next = p.shift ? $(this).prevAll('input').not(':hidden').first() : $(this).nextAll('input').not(':hidden').first();
        }
        if(!next || !next.length) {
          var nexts = p.shift ? $(this).closest('.product').prevAll('.product') : $(this).closest('.product').nextAll('.product');
          next = nexts.not('.semi-transparent').first().find('input[name="' + $(this).attr('name') + '"]').first();
        }
        if(next.length) {
          if(is_qty) {
            next.trigger('onfocus').focus().select();
          } else {
            next.focus();
          }
          if(next.offset().top > $(window).scrollTop + $(window).height()) {
            $(window).scrollTop($(window).scrollTop() + (next.offset().top - $(this).offset().top));
          }
        }
      },
      'blur': function() {
        $(this).trigger('restore');
      }
      }, 'input[type!="checkbox"]');

    // Code and names
    $('.data-table').on({
      'change': function() {
        updateAjax({request: 'update_product_code', code_name: $(this).attr('name'), code_value: encodeURI($(this).val()), product_id: pId($(this))});
      }
      }, '.name-code input[name!="name"]');

    $('.data-table').on({
      'change': function() {
        $(this).next('a').text($(this).val());
        updateAjax({request: 'update_product_name', name: encodeURI($(this).val()), product_id: pId($(this))});
      }
      }, '.name-code input[name="name"]');

    // Purchase prices
    <?php
      if(user::is_permitted('modify_purchase_price')) {
    ?>
      $('.data-table').on({
        'change': function() {
          updateAjax({request: 'update_product_purchase_price', purchase_price: Number($(this).val()), product_id: pId($(this))});
        }
        }, '.purchase.price input');

      // Selling prices
      <?php
      }
      if(user::is_permitted('modify_selling_price')) {
      ?>

      $('.data-table').on({
        'click': function(e) {
          if($(this).parent().hasClass('campaign')) {
            $(this).siblings('input[name="date"]').val('').trigger('update');
            $(this).siblings('input[name^="price"]').each(function() {
              $(this).val($(this).data('initial')).trigger('change');
            });
          }
        }
        }, '.selling.price .fa-times-circle');

      // Campaigns date && price
      $('.data-table').on({
        'change': function() {
          var e = $.Event('keydown');
          e.key = 'Enter';
          $(this).trigger(e);
        },
        'update': function() {
          if(Number($(this).siblings('input[name^="price"].active').val()).toFixed(2) !=
            Number($(this).siblings('input[name^="price"].active').data('initial')).toFixed(2)) {
            if(new Date($(this).val()) > new Date('<?php echo date('Y-m-d'); ?>')) {
              $(this).trigger('insert').parent().addClass('campaign');
            } else {
              $(this).trigger('delete').parent().removeClass('campaign');
            }
          }
        },
        'delete': function() {
          updateAjax({request: 'delete_product_campaigns', product_id: pId($(this))});
        },
        'insert': function() {
          $(this).trigger('delete');
          var data = {request: 'insert_product_campaign', product_id: pId($(this)), date: $(this).val(), currencies: {}};
          $(this).siblings('input[name^="price"]').each(function() {
            data['currencies'][$(this).attr('name').split('_')[1]] = Number($(this).val() / (1+($(this).data('tax')/100)));
          });
          updateAjax(data);
        }
        }, '.selling.price input[name="date"]');

      // Regular rice
      $('.data-table').on({
        'change': function() {
          var date = $(this).siblings('input[name="date"]');

          if(date.val() == '') {
            $(this).trigger('update');
          } else {
            date.trigger('update');
          }
        },
        'update': function() {
          updateAjax({request: 'update_product_price', currency: $(this).data('currency'), price: Number($(this).val() / (1+($(this).data('tax')/100))), product_id: pId($(this))});
        }
        }, '.selling.price input[name^="price"]');
    <?php
      }
    ?>

    // Stock fields
    <?php
      if(user::is_permitted('modify_stock_quantities')) {
    ?>
      function qDec(that) {
        return that.closest('.product').data('qtydec')
      }

      $('.data-table').on({
        'click mouseenter mouseleave onfocus': function(e) {
          e.stopPropagation();
          $('.stock-fields').hide();
          $('.stock-wrapper').removeClass('wrap');
          width = $('.data-table th.qty').outerWidth();
          if(e.type != 'mouseleave' && !$(this).closest('.product').hasClass('semi-transparent')) {
            $(this).find('.stock-wrapper').addClass('wrap').css('height', '100%').css('width', $(this).width());
            $(this).find('.stock-fields').show().css('min-height', '100%').css('right', width);
            if(e.type != 'onfocus') {
              $(this).find('input').not(':hidden').first().focus().select();
            }

            if(!$(this).hasClass('loaded')) {
              $(this).addClass('loaded');
              $('body').css('cursor', 'wait');
              $.get({
                url: '<?php echo document::link('', array('app' => 'catalog', 'doc' => 'catalog'), false) ?>&get_product_stock=' +  pId($(this)) + '&qtydec' + qDec($(this)),
                context: $(this),
                success: function(data) {
                  if (data != '') {
                    $(this).find('.stock-fields').html(data);
                  }
                  $('body').css('cursor', '');
                },
                complete: function() {
                  $(this).find('.stock-fields').css('top', (-1 * $(this).find('.stock-fields').outerHeight()/2) + $(this).outerHeight()/2);
                  $(this).trigger('mouseenter');
                }
              });
            }
          }
        }
        },'.stock');

      // Qty fields
      $('.data-table').on({
        'click': function(e) {
          e.stopPropagation();
        },
        'change': function(e, flag) {
          var that = $(this);
          var optionId = parseInt(that.data('id'));
          var pid = pId(that);
          var qty = Number(that.val());

          if(optionId) {
            updateAjax({request: 'update_product_quantity', quantity: qty, option_id: optionId, product_id: pid}, function(data) {
              that.trigger('update', {val : data[0]});
            });
          } else {
            updateAjax({request: 'update_product_quantity', quantity: qty, product_id: pid});
            that.trigger('update', {val : qty});
          }
          if(typeof flag == 'undefined' || flag.backup != false) {
            $(this).data('val', $(this).val());
            $(this).prevAll('input[name="qty_in"]').first().val(0);
            $(this).prevAll('input[name="qty_out"]').first().val(0);
          }
        },
        'update': function(e, qty) {
          if(typeof qty != 'undefined') {
            $(this).closest('td').find('.stock-wrapper').text(Number(qty.val).toFixed(qDec($(this))));
            $(this).closest('tr').find('td.warning').removeClass('.warning').find('i').removeClass().addClass('fa fa-recycle').attr('title', '');
          }
        }
        }, '.stock input[name="qty"]');

      $('.data-table').on({
        'change' : function(e) {
          $(this).next('input').val(0);
          var qty_field = $(this).nextAll('input[name="qty"]').first();
          var new_qty = Number(qty_field.data('val')) + Number($(this).val());
          qty_field.val(new_qty).trigger('change', {backup : false});
        }
        }, 'input[name="qty_in"]');

      $('.data-table').on({
        'change': function(e) {
          $(this).prev('input').val(0);
          var qty_field = $(this).nextAll('input[name="qty"]').first();
          var cur_qty = Number(qty_field.data('val'));
          var new_qty = cur_qty - Number($(this).val());
          var confirm = true;
          if (new_qty < 0) {
            var message = '<?php echo language::translate('text_qty_would_become_negative', 'This would set a negative stock, change for %d ?') ?>';
            confirm = window.confirm(message.replace('%d', cur_qty));
            if(confirm) {
              new_qty = 0;
              $(this).val(cur_qty);
            }
          }
          if(confirm) {
            qty_field.val(new_qty).trigger('change', {backup : false});
          }
        }
        }, 'input[name="qty_out"]');

      var bascule = 1;
      var offset;
      offset = $('button[name="stock_mvnt"]').find('i').hasClass('fa-balance-scale') ? 0 :
      ($('button[name="stock_mvnt"]').find('i').hasClass('fa-sign-in') ? 1 : -1);

      $('button[name="stock_mvnt"]').on('click', function(e) {
        offset = offset + bascule;
        if(Math.abs(offset) > 1) {
          offset = 0;
          bascule = bascule * -1;
        }

        switch (offset) {
          case -1 :
            setCookie('stock_mvnt', 'out');
            new_class = 'fa fa-sign-out';
            $(this).removeClass('balance').removeClass('in').addClass('out');
            $('.data-table input[name="qty"]').css('display', 'none');
            $('.data-table input[name="qty_in"]').css('display', 'none');
            $('.data-table input[name="qty_out"]').css('display', '');
            break;
          case 0 :
            setCookie('stock_mvnt', 'balance')
            new_class = 'fa fa-balance-scale';
            $(this).removeClass('in').removeClass('out').addClass('balance');
            $('.data-table input[name="qty"]').css('display', '');
            $('.data-table input[name="qty_in"]').css('display', 'none');
            $('.data-table input[name="qty_out"]').css('display', 'none');
            break;
          case 1 :
            setCookie('stock_mvnt', 'in');
            new_class = 'fa fa-sign-in'
            $(this).removeClass('balance').removeClass('out').addClass('in');
            $('.data-table input[name="qty"]').css('display', 'none');
            $('.data-table input[name="qty_in"]').css('display', '');
            $('.data-table input[name="qty_out"]').css('display', 'none');
            break;
        }
        $(this).find('i').removeClass();
        $(this).find('i').addClass(new_class);
      });
      $('button[name="stock_mvnt"] i').on('click', function(e) {
        e.stopPropagation();
        $(this).parent().trigger('click');
      });

    <?php
      }
    ?>

    // Actions

    var message = '';
    var to_blur = $('#treeview input[type="checkbox"], #logotype, #top-bar, .panel-heading, .panel-action, .panel-filter, .data-table');
    $('.actions.onselected button').on('click', function() {
      $('.actions').hide();
      $('#ok-button').attr('name', $(this).attr('name'));
      $('#operate').show();
      $('#treeview input[type="checkbox"]:not(:checked), #treeview i.fa-circle, .data-table i.fa-circle').css('visibility', 'hidden');
      to_blur.addClass('blur');
      $('#treeview input[type="radio"]').show();
      if(typeof $(this).data('message') !== 'undefined') {
        message = $(this).data('message');
      } else {
        message = '';
      }
    });
    $('#cancel-button').on('click', function() {
      $('.actions').show();
      $('#operate').hide();
      $('#treeview input[type="checkbox"]:not(:checked), #treeview i.fa-circle, .data-table i.fa-circle').css('visibility', 'visible');
      to_blur.removeClass('blur');
      $('#treeview input[type="radio"]').hide();
    });
    $('form[name="catalog_form"]').on('submit', function(e) {
      if(message == '' || (message != '' && window.confirm(message))) {
        $('#treeview').css('visibility', 'hidden').appendTo($(this));
      } else {
        e.preventDefault();
        $('#cancel-button').trigger('click');
      }
    });
    $('.actions button').prop('disabled', true);
    $('#treeview input[type="checkbox"]').on('click', function() {
      var buttons = $('button[name="enable"], button[name="disable"], button[name="move"], button[name="unmount"]');
      $('.actions button').prop('disabled', true);
      $('.data-table input[type="checkbox"]').prop('checked', false);
      if($('#treeview input[type="checkbox"]:checked').length) {
        buttons.prop('disabled', false);
      } else {
        buttons.prop('disabled', true);
      }
    });

    // Handle chechkboxes

    $('input[name="check_all"]').on('click', function() {
      $('.data-table input[type="checkbox"]').prop('checked', $(this).prop('checked')).trigger('change');
    });

    $('.data-table').on({
      'change': function() {

        $('input[name="check_all"]').prop('checked', $('.data-table input[type="checkbox"]:checked').length);

        $('#treeview input[type="checkbox"]').prop('checked', false);
        if($('.data-table input[type="checkbox"]:checked').length) {
          $('.actions button').prop('disabled', false);
        } else {
          $('.actions button').prop('disabled', true);
        }

        if($(this).prop('checked')) {
          $(this).closest('.product, .category').addClass('selected');
        } else {
          $(this).closest('.product, .category').removeClass('selected');
        }

      }
    }, 'input[type="checkbox"]');

    // Infinite scroll

    var loadInProcess = false;
    var contentQueue = '';
    var endOfContent = <?php echo ($num_rows > $num_product_rows) ? 'false' : 'true' ?>;
    var catalogBaseUrl = '<?php echo document::link('', array('app' => 'catalog', 'doc' => 'catalog'), false); ?>';
    var pageUrl = catalogBaseUrl + '&category_id=' + '<?php echo (int)$_GET['category_id'] ?>';
    var nextPage = <?php echo $_GET['page'] + 1; ?>;

    function getNextPage(all) {
      loadInProcess = true;
      $.get(pageUrl + '&page=' + nextPage, function(data) {
        if (data.replace(/^\s\s*/, '') == '') {
          contentQueue = '';
          endOfContent = true;
        } else {
          contentQueue = data;
          nextPage++;
          loadInProcess = false;
        }
        $('body').css('cursor', '');
      });
    }
    $(window).scroll(function () {
      if ($(window).scrollTop() >= $(document).height() - $(window).height() - $('.product:last').height()) {
        if (!loadInProcess && !endOfContent && contentQueue) {
          $('body').css('cursor', 'wait');
          $('.product:last').after(contentQueue);
          $('.product:hidden').fadeIn();
          getNextPage();
        }
      }
    }).trigger('scroll');
    if(!endOfContent) {
      getNextPage();
    }
    $(document).ajaxComplete(function() {
      $('#nb_products').text($('.product').length);
    });

    // Search in table

    $('input[name="search_products"]').on('input', function() {
      if(!loadInProcess && !endOfContent) {
        $('body').css('cursor', 'wait');
        loadInProcess = true;
        var jqxhr = $.get(
          pageUrl + '&page=' + nextPage + '&all=true',
          function(data) {
            if (data.replace(/^\s\s*/, '') != '') {
              $('.product:last').after(contentQueue + data);
              endOfContent = true;
              contentQueue = '';
              loadInProcess = false;
            }
            $('body').css('cursor', '');
            $(this).trigger('input');
        }).done;
      }

      var val = $(this).val().toLowerCase();
      if($('.data-table').find('a').text().toLowerCase().indexOf(val) !== -1) {
        $('.data-table tbody tr').each(function() {
          if($(this).find('a').text().toLowerCase().indexOf(val) !== -1) {
            $(this).css('display', '');
          } else {
            $(this).css('display', 'none');
          }
        });
      }
    });

    // Fast product search & change (usefull for barcode scanners)

    $('#search input[name="query"]').on('keydown', function(e){
      if(e.key == 'Enter' && $('#search .result').length == 1 && $('#search .result').data('ref') != '') {
        $('#search .result').trigger('click');
      }
    });

    $('body').on({
      'click': function() {
        if($(this).data('ref') != '') {
          var ref = $('#search .result').data('ref');
          $('#search .results').hide();
          var row = $('.data-table tr[data-ref="' + ref + '"]');
          if(row.length) {
            $('html, body').animate({scrollTop: row.offset().top}, 1000, 'swing');
          } else {
            $('body').css('cursor', 'wait');
            $.get({
              url : catalogBaseUrl + '&query=' + ref.split('=')[1] + '&ref=' + ref.split('=')[0],
              success: function(data) {
                $('body').css('cursor', '');
                if(!$('.panel').hasClass('query')) {
                  $('.panel').addClass('query');
                  $('.data-table tr.category').remove();
                  $('.data-table tr.product').remove();
                }
                $('.panel-filter').remove();
                $('.panel-heading').text('<?php echo language::translate('title_catalog', 'Catalog') ?>');
                $('.breadcrumb li').slice(2).remove();
                $('#treeview .current').removeClass('current');
                $('.data-table').append(data);
              },
              complete: function() {
                $('.data-table input[name="' + getCookie('input_focus', 'qty') + '"]').last().focus();
              }
            });
          }
        }
      }
      }, '#search .result');
  });
</script>
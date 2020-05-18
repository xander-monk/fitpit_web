<?php

  $output = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
          . '<rss version="2.0">' . PHP_EOL
          . '  <channel>' . PHP_EOL
          . '    <title>'. htmlspecialchars(settings::get('store_name')) .'</title>' . PHP_EOL
          . '    <description>'. htmlspecialchars(settings::get('store_name')) .'</description>' . PHP_EOL
          . '    <link>'. htmlspecialchars(document::ilink('')) .'</link>' . PHP_EOL
          . '    <lastBuildDate>'. date('r') .'</lastBuildDate>' . PHP_EOL
          . '    <pubDate>'. date('r') .'</pubDate>' . PHP_EOL;

  $products_query = functions::catalog_products_query(array('sort' => 'name'));
  while ($product = database::fetch($products_query)) {
    $product = new ref_product($product['id']);

    if (!$product->status) continue;

    $output .= '  <item>' . PHP_EOL
             . '    <title>'. $product->name .'</title>' . PHP_EOL
             . '    <description>'.
                      htmlspecialchars(
                        (count($product->images) ? '<p><img src="'. htmlspecialchars(htmlspecialchars(document::link(WS_DIR_IMAGES . $product->image))) .'" width="200" /></p>' : '') .
                        $product->description
                      )
             . '    </description>' . PHP_EOL
             . '    <link>'. htmlspecialchars(document::ilink('product', array('product_id' => $product->id))) .'</link>' . PHP_EOL
             . '    <pubDate>'. date('r', strtotime($product->date_created)) .'</pubDate>' . PHP_EOL
             . '  </item>' . PHP_EOL;
  }

  $output .= '  </channel>' . PHP_EOL
           . '</rss>';

  if (strtolower(language::$selected['charset']) != 'utf-8') {
    $output = language::convert_characters($output, language::$selected['charset'], 'UTF-8');
  }

  header('Content-type: application/rss+xml; charset=UTF-8');

  echo $output;
  exit;

<?php

  class om_google_ecommerce {
    public $id = __CLASS__;
    public $name = 'Google Analytics E-commerce Tracking (Universal Analytics)';
    public $description = '';
    public $author = 'LiteCart Dev Team';
    public $version = '2.0';
    public $website = 'http://www.google-analytics.com/';
    public $priority = 0;

    public function success($order) {

      if (empty($this->settings['status'])) return;

    /*
    // Override account id per domain
      if (!preg_match('#^/'. preg_quote(basename(WS_DIR_ADMIN), '/#') .'.*#', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        switch (preg_replace('#^(.*\.)?(.*\..*)$#', '$2', $_SERVER['HTTP_HOST'])) {
          case 'mysite.com':
            $this->settings['account_id'] = '';
            break;
          case 'anothersite.com':
            $this->settings['account_id'] = '';
            break;
        }
      }
    */

      $output = '<script>' . PHP_EOL
              . '  (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){' . PHP_EOL
              . '  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),' . PHP_EOL
              . '  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)' . PHP_EOL
              . '  })(window,document,"script","//www.google-analytics.com/analytics.js","ga");' . PHP_EOL
              . PHP_EOL
              . '  ga("create", "'. $this->settings['account_id'] .'", {"name": "orderTracker"});' . PHP_EOL
              . PHP_EOL
              . '  ga("orderTracker.require", "ecommerce", "ecommerce.js");' . PHP_EOL
              . PHP_EOL
              . '  ga("orderTracker.ecommerce:addTransaction", {' . PHP_EOL
              . '    "id": "'. $order->data['id'] .'",' . PHP_EOL
              . '    "affiliation": "'. settings::get('store_name') .'",' . PHP_EOL
              . '    "revenue": "'. $this->_format_raw($order->data['payment_due'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
              . '    "shipping": "0",' . PHP_EOL
              . '    "tax": "'. $this->_format_raw($order->data['tax_total'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
              . '    "currencyCode": "'. (!empty($this->settings['force_store_currency']) ? settings::get('store_currency_code') : $order->data['currency_code']) .'"' . PHP_EOL
              . '  });' . PHP_EOL . PHP_EOL;

      foreach (array_keys($order->data['items']) as $key) {
        $output .= '  ga("orderTracker.ecommerce:addItem", {' . PHP_EOL
                 . '    "id": "'. $order->data['id'] .'",' . PHP_EOL
                 . '    "name": "'. $order->data['items'][$key]['name'] .'",' . PHP_EOL
                 . '    "sku": "'. $order->data['items'][$key]['id'] .'",' . PHP_EOL
                 . '    "category": "freight",' . PHP_EOL
                 . '    "price": "'. currency::format_raw($order->data['items'][$key]['price'] + $order->data['items'][$key]['tax'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
                 //. '    "price": "'. currency::format_raw($order->data['items'][$key]['price'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
                 . '    "quantity": "1"' . PHP_EOL
                 . '  });' . PHP_EOL . PHP_EOL;
      }

      foreach (array_keys($order->data['order_total']) as $key) {
        if (empty($order->data['order_total'][$key]['calculate'])) continue;
        $output .= '  ga("orderTracker.ecommerce:addItem", {' . PHP_EOL
                 . '    "id": "'. $order->data['id'] .'",' . PHP_EOL
                 . '    "name": "'. $order->data['order_total'][$key]['title'] .'",' . PHP_EOL
                 . '    "sku": "'. $order->data['order_total'][$key]['module_id'] .'",' . PHP_EOL
                 . '    "category": "fees",' . PHP_EOL
                 . '    "price": "'. currency::format_raw($order->data['order_total'][$key]['value'] + $order->data['order_total'][$key]['tax'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
                 //. '    "price": "'. currency::format_raw($order->data['order_total'][$key]['value'], $order->data['currency_code'], $order->data['currency_value']) .'",' . PHP_EOL
                 . '    "quantity": "1"' . PHP_EOL
                 . '  });' . PHP_EOL . PHP_EOL;
      }

      $output .= '  ga("orderTracker.ecommerce:send");' . PHP_EOL
               . '</script>';

      return $output;
    }

    function _format_raw($amount, $currency, $currency_value) {

      if (!empty($this->settings['force_store_currency'])) {
        return currency::format_raw($amount, settings::get('store_currency_code'));
      } else {
        return currency::format_raw($amount, $currency, $currency_value);
      }
    }

    function settings() {

      return array(
        array(
          'key' => 'status',
          'default_value' => '1',
          'title' => language::translate(__CLASS__.':title_status', 'Status'),
          'description' => language::translate(__CLASS__.':description_status', 'Enables or disables the module.'),
          'function' => 'toggle("e/d")',
        ),
        array(
          'key' => 'account_id',
          'default_value' => 'UA-XXXXX-X',
          'title' => language::translate(__CLASS__.':title_account_id', 'Account ID'),
          'description' => language::translate(__CLASS__.':description_account_id', 'Your Google Analytics account ID.'),
          'function' => 'input()',
        ),
        array(
          'key' => 'force_store_currency',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_force_store_currency', 'Force Store Currency'),
          'description' => language::translate(__CLASS__.':description_force_store_currency', 'Always use store currency for all transactions (Not recommended by Google).'),
          'function' => 'toggle("e/d")',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_module_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ),
      );
    }

    public function install() {}

    public function uninstall() {}
  }

?>
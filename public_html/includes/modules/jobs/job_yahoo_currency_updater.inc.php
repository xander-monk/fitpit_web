<?php

  class job_yahoo_currency_updater {
    public $id = __CLASS__;
    public $name = 'Yahoo Finance - Currency Updater';
    public $description = 'Currency conversion rates by Yahoo Finance.';
    public $author = 'LiteCart Dev Team';
    public $version = '1.0';
    public $website = 'http://www.litecart.net';
    public $priority = 0;
    
    public function process($force=false) {
      
      if (empty($force)) {
        if (empty($this->settings['status'])) return;
        
        switch ($this->settings['update_frequency']) {
          case 'Daily':
            if (strtotime(settings::get('yahoo_currency_updater_last_run')) > strtotime('-1 day')) return; 
            break;
          case 'Weekly':
            if (strtotime(settings::get('yahoo_currency_updater_last_run')) > strtotime('-1 week')) return; 
            break;
          case 'Monthly':
            if (strtotime(settings::get('yahoo_currency_updater_last_run')) > strtotime('-1 month')) return; 
            break;
        }
      }
      
      database::query(
        "update ". DB_TABLE_SETTINGS ."
        set value = '". date('Y-m-d H:i:s') . "'
        where `key` = 'yahoo_currency_updater_last_run'
        limit 1;"
      );
      
      foreach (array_keys(currency::$currencies) as $currency_code) {
        
        if ($currency_code == settings::get('store_currency_code')) continue;
        
        $url = 'http://data.fixer.io/api/latest?access_key=ffe57cd2abfc46026be4166b00df2853&symbols='. $currency_code .'&format=1';
        //'http://download.finance.yahoo.com/d/quotes.csv?f=l1&s='. settings::get('store_currency_code') . $currency_code .'=X';
      /*
      Get data from Fixer.IO

      URL (free for EUR > UAH with my Access_Key) http://data.fixer.io/api/latest?access_key=ffe57cd2abfc46026be4166b00df2853&symbols=UAH&format=1

      result JSON
      {
        "success":true,
        "timestamp":1579857666,
        "base":"EUR",
        "date":"2020-01-24",
        "rates":{
          "UAH":27.142427
        }
      }
       */
        
        $result = json_decode(file_get_contents($url));
        // var_dump($result);
        
        if (empty($result)) {
          trigger_error('Could not update currency value for '. $currency_code .': No data ('. $url .')', E_USER_WARNING);
          continue;
        }
        
        $value = (float)$result->rates->$currency_code; // (float)trim($result) * currency::$currencies[settings::get('store_currency_code')]['value'];
        
        if (empty($value)) {
          trigger_error('Could not update currency value for '. $currency_code .': No value ('. $url .')', E_USER_WARNING);
          continue;
        }
        
        echo 'Updating ' . $currency_code . ' to '. $value  . PHP_EOL;
        
        database::query(
          "update ". DB_TABLE_CURRENCIES ."
          set value = '". 1 / (float)$value ."'
          where code = '". database::input($currency_code) ."'
          limit 1;"
        );
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
          'key' => 'update_frequency',
          'default_value' => 'Daily',
          'title' => language::translate(__CLASS__.':title_update_frequency', 'Update Frequency'),
          'description' => language::translate(__CLASS__.':description_update_frequency', 'How often the currency values should be updated.'),
          'function' => 'radio("Daily","Weekly","Monthly")',
        ),
        array(
          'key' => 'priority',
          'default_value' => '0',
          'title' => language::translate(__CLASS__.':title_priority', 'Priority'),
          'description' => language::translate(__CLASS__.':description_priority', 'Process this module in the given priority order.'),
          'function' => 'int()',
        ),
      );
    }
    
    public function install() {
      database::query(
        "insert into ". DB_TABLE_SETTINGS ."
        (title, description, `key`, value, date_created, date_updated)
        values ('Currencies Last Updated', 'Time when currencies where last updated by the background job.', 'yahoo_currency_updater_last_run', '', '". date('Y-m-d H:i:s') ."', '". date('Y-m-d H:i:s') ."');"
      );
    }
    
    public function uninstall() {
      database::query(
        "delete from ". DB_TABLE_SETTINGS ."
        where `key` = 'yahoo_currency_updater_last_run';"
      );
    }
  }
  
?>
<?php

######################################################################
## Files and Directories #############################################
######################################################################

  define('BACKEND_ALIAS', 'admin');

// File System
  define('DOCUMENT_ROOT',      rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])), '/'));

  define('FS_DIR_APP',         DOCUMENT_ROOT . rtrim(str_replace(DOCUMENT_ROOT, '', str_replace('\\', '/', realpath(__DIR__.'/..'))), '/') . '/');
  define('FS_DIR_ADMIN',       FS_DIR_APP . BACKEND_ALIAS . '/');

// Web System
  define('WS_DIR_APP',         rtrim(str_replace(DOCUMENT_ROOT, '', str_replace('\\', '/', realpath(__DIR__.'/..'))), '/') . '/');
  define('WS_DIR_ADMIN',       WS_DIR_APP . BACKEND_ALIAS . '/');

######################################################################
## Backwards Compatible Directory Definitions (LiteCart <2.2) ########
######################################################################

// File System
  define('FS_DIR_HTTP_ROOT', DOCUMENT_ROOT);

// Web System
  define('WS_DIR_HTTP_HOME', rtrim(str_replace(DOCUMENT_ROOT, '', str_replace('\\', '/', realpath(__DIR__.'/..'))), '/') . '/');

  define('WS_DIR_CACHE',       WS_DIR_HTTP_HOME . 'cache/');
  define('WS_DIR_DATA',        WS_DIR_HTTP_HOME . 'data/');
  define('WS_DIR_EXT',         WS_DIR_HTTP_HOME . 'ext/');
  define('WS_DIR_IMAGES',      WS_DIR_HTTP_HOME . 'images/');
  define('WS_DIR_INCLUDES',    WS_DIR_HTTP_HOME . 'includes/');
  define('WS_DIR_LOGS',        WS_DIR_HTTP_HOME . 'logs/');
  define('WS_DIR_PAGES',       WS_DIR_HTTP_HOME . 'pages/');

  define('WS_DIR_BOXES',       WS_DIR_INCLUDES  . 'boxes/');
  define('WS_DIR_CLASSES',     WS_DIR_INCLUDES  . 'classes/');
  define('WS_DIR_CONTROLLERS', WS_DIR_INCLUDES  . 'controllers/'); // Deprecated in favour of Entities
  define('WS_DIR_FUNCTIONS',   WS_DIR_INCLUDES  . 'functions/');
  define('WS_DIR_LIBRARY',     WS_DIR_INCLUDES  . 'library/');
  define('WS_DIR_MODULES',     WS_DIR_INCLUDES  . 'modules/');
  define('WS_DIR_REFERENCES',  WS_DIR_INCLUDES  . 'references/');
  define('WS_DIR_ROUTES',      WS_DIR_INCLUDES  . 'routes/');
  define('WS_DIR_TEMPLATES',   WS_DIR_INCLUDES  . 'templates/');

######################################################################
## Database ##########################################################
######################################################################

// Database
  define('DB_TYPE', 'mysql');
  define('DB_SERVER', 'webbrain.mysql.tools');
  define('DB_USERNAME', 'webbrain_fpdev');
  define('DB_PASSWORD', '7p(9V2E&yu');
  define('DB_DATABASE', 'webbrain_fpdev');
  define('DB_TABLE_PREFIX', 'lc_');
  define('DB_CONNECTION_CHARSET', 'utf8');
  define('DB_PERSISTENT_CONNECTIONS', 'false');

// Database tables
  define('DB_TABLE_ATTRIBUTE_GROUPS',                  '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'attribute_groups`');
  define('DB_TABLE_ATTRIBUTE_GROUPS_INFO',             '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'attribute_groups_info`');
  define('DB_TABLE_ATTRIBUTE_VALUES',                  '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'attribute_values`');
  define('DB_TABLE_ATTRIBUTE_VALUES_INFO',             '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'attribute_values_info`');
  define('DB_TABLE_CART_ITEMS',                        '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'cart_items`');
  define('DB_TABLE_CATEGORIES',                        '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'categories`');
  define('DB_TABLE_CATEGORIES_FILTERS',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'categories_filters`');
  define('DB_TABLE_CATEGORIES_INFO',                   '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'categories_info`');
  define('DB_TABLE_COUNTRIES',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'countries`');
  define('DB_TABLE_CURRENCIES',                        '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'currencies`');
  define('DB_TABLE_CUSTOMERS',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'customers`');
  define('DB_TABLE_DELIVERY_STATUSES',                 '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'delivery_statuses`');
  define('DB_TABLE_DELIVERY_STATUSES_INFO',            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'delivery_statuses_info`');
  define('DB_TABLE_EMAILS',                            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'emails`');
  define('DB_TABLE_GEO_ZONES',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'geo_zones`');
  define('DB_TABLE_LANGUAGES',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'languages`');
  define('DB_TABLE_MANUFACTURERS',                     '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'manufacturers`');
  define('DB_TABLE_MANUFACTURERS_INFO',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'manufacturers_info`');
  define('DB_TABLE_MODULES',                           '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'modules`');
  define('DB_TABLE_OPTION_GROUPS',                     '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'option_groups`');
  define('DB_TABLE_OPTION_GROUPS_INFO',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'option_groups_info`');
  define('DB_TABLE_OPTION_VALUES',                     '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'option_values`');
  define('DB_TABLE_OPTION_VALUES_INFO',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'option_values_info`');
  define('DB_TABLE_ORDER_STATUSES',                    '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'order_statuses`');
  define('DB_TABLE_ORDER_STATUSES_INFO',               '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'order_statuses_info`');
  define('DB_TABLE_ORDERS',                            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'orders`');
  define('DB_TABLE_ORDERS_COMMENTS',                   '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'orders_comments`');
  define('DB_TABLE_ORDERS_ITEMS',                      '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'orders_items`');
  define('DB_TABLE_ORDERS_TOTALS',                     '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'orders_totals`');
  define('DB_TABLE_PAGES',                             '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'pages`');
  define('DB_TABLE_PAGES_INFO',                        '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'pages_info`');
  define('DB_TABLE_PRODUCTS',                          '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products`');
  define('DB_TABLE_PRODUCTS_ATTRIBUTES',               '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_attributes`');
  define('DB_TABLE_PRODUCTS_CAMPAIGNS',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_campaigns`');
  define('DB_TABLE_PRODUCTS_OPTIONS',                  '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_options`');
  define('DB_TABLE_PRODUCTS_OPTIONS_STOCK',            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_options_stock`');
  define('DB_TABLE_PRODUCTS_IMAGES',                   '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_images`');
  define('DB_TABLE_PRODUCTS_INFO',                     '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_info`');
  define('DB_TABLE_PRODUCTS_PRICES',                   '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_prices`');
  define('DB_TABLE_PRODUCTS_TO_CATEGORIES',            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'products_to_categories`');
  define('DB_TABLE_QUANTITY_UNITS',                    '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'quantity_units`');
  define('DB_TABLE_QUANTITY_UNITS_INFO',               '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'quantity_units_info`');
  define('DB_TABLE_SETTINGS',                          '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'settings`');
  define('DB_TABLE_SETTINGS_GROUPS',                   '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'settings_groups`');
  define('DB_TABLE_SLIDES',                            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'slides`');
  define('DB_TABLE_SLIDES_INFO',                       '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'slides_info`');
  define('DB_TABLE_SOLD_OUT_STATUSES',                 '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'sold_out_statuses`');
  define('DB_TABLE_SOLD_OUT_STATUSES_INFO',            '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'sold_out_statuses_info`');
  define('DB_TABLE_SUPPLIERS',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'suppliers`');
  define('DB_TABLE_TAX_RATES',                         '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'tax_rates`');
  define('DB_TABLE_TAX_CLASSES',                       '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'tax_classes`');
  define('DB_TABLE_TRANSLATIONS',                      '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'translations`');
  define('DB_TABLE_USERS',                             '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'users`');
  define('DB_TABLE_ZONES',                             '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'zones`');
  define('DB_TABLE_ZONES_TO_GEO_ZONES',                '`'. DB_DATABASE .'`.`'. DB_TABLE_PREFIX . 'zones_to_geo_zones`');

// Database tables (Add-ons)
  /* Your added tables here ... */

######################################################################
## Application #######################################################
######################################################################

// Errors
  error_reporting(version_compare(PHP_VERSION, '5.4.0', '<') ? E_ALL | E_STRICT : E_ALL);
  ini_set('ignore_repeated_errors', 'On');
  ini_set('log_errors', 'On');
  ini_set('error_log', FS_DIR_APP . 'logs/errors.log');
  ini_set('display_startup_errors', 'Off');
  ini_set('display_errors', 'Off');
  if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '31.43.238.7'))) {
    ini_set('display_startup_errors', 'On');
    ini_set('display_errors', 'On');
  }

// Password Encryption Salt
  define('PASSWORD_SALT', 'jz2UJKdeueezyPhtiLzkV8Esu6wa5Q1BdieD6JDGqINVf9zXHOQ5MFUmayKdVXn3fOqrchz2BsakKqiZiH3WoJvbqmeBlTociCMsZ204BRN77fpiUrDkOtI6ojJlQCI7');

<?php

  $app_config = array(
    'name' => language::translate('title_settings', 'Settings'),
    'default' => 'store_info',
    'priority' => 80,
    'theme' => array(
      'color' => '#959595',
      'icon' => 'fa-cogs',
    ),
    'menu' => array(),
    'docs' => array(),
  );

  $settings_groups_query = database::query(
    "select * from ". DB_TABLE_SETTINGS_GROUPS ."
    order by priority, `key`;"
  );

  while ($group = database::fetch($settings_groups_query)) {
    $app_config['menu'][] = array(
      'title' => language::translate('settings_group:title_'.$group['key'], $group['name']),
      'doc' => $group['key'],
      'params' => array(),
    );
    $app_config['docs'][$group['key']] = 'settings.inc.php';
  }

  $app_config['menu'][] = array(
    'title' => language::translate('title_currencies', 'Currencies'),
    'doc' => 'currencies',
    'params' => array(),
  );
  $app_config['docs']['currencies'] = 'currencies.inc.php';
  $app_config['docs']['edit_currency'] = 'edit_currency.inc.php';

  return $app_config;
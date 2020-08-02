<?php

  return $app_config = array(
    'name' => language::translate('title_mods', 'Mods'),
    'default' => 'vqmods',
    'priority' => 99,
    'theme' => array(
      'color' => '#77d2cd',
      'icon' => 'fa-plug',
    ),
    'menu' => array(),
    'docs' => array(
      'view' => 'view.inc.php',
      'download' => 'download.inc.php',
      'vqmods' => 'vqmods.inc.php',
      'test' => 'test.inc.php',
    ),
  );

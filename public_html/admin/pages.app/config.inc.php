<?php

  return $app_config = array(
    'name' => language::translate('title_content', 'Content'),
    'default' => 'pages',
    'priority' => 50,
    'theme' => array(
      'color' => '#adb4a3',
      'icon' => 'fa-file-text',
    ),
    'menu' => array(
      array(
        'title' => language::translate('title_pages', 'Pages'),
        'doc' => 'pages',
        'params' => array('type'=> 'pages'),
      ),
      array(
        'title' => language::translate('title_icons', 'Icons'),
        'doc' => 'pages',
        'params' => array('type'=> 'icons'),
      ),
      array(
        'title' => language::translate('title_blog', 'Blog'),
        'doc' => 'pages',
        'params' => array('type'=> 'blog'),
      ),
      array(
        'title' => language::translate('title_quotes', 'Quotes'),
        'doc' => 'pages',
        'params' => array('type'=> 'quotes'),
      ),
      array(
        'title' => language::translate('title_video', 'Video'),
        'doc' => 'pages',
        'params' => array('type'=> 'video'),
      ),
      array(
        'title' => language::translate('title_slides', 'Slides'),
        'doc' => 'slides',
        'params' => array(),
      ),
      array(
        'title' => language::translate('title_csv_import_export', 'CSV Import/Export'),
        'doc' => 'csv',
        'params' => array(),
      ),
    ),
    'docs' => array(
      'pages' => 'pages.inc.php',
      'edit_page' => 'edit_page.inc.php',
      'slides' => 'slides.inc.php',
      'edit_slide' => 'edit_slide.inc.php',
      'csv' => 'csv.inc.php',
    ),
  );

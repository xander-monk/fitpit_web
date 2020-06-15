<?php

  header('X-Robots-Tag: noindex');
  document::$layout = 'wholesale';

  document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
  document::$snippets['title'][] = language::translate('wholesale:head_title', 'Wholesale');

  // var_dump(customer::require_wholesale()); die;
  customer::require_wholesale();

  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;

  breadcrumbs::add(language::translate('title_account', 'Account'));
  breadcrumbs::add(language::translate('title_wholesale', 'Wholesale'));

  $_page = new ent_view();

  
  echo $_page->stitch('pages/wholesale');

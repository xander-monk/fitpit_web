<?php

  document::$snippets['title'] = array(language::translate('index:head_title', 'Online Store'), settings::get('store_name'));
  document::$snippets['description'] = language::translate('index:meta_description', '');
  document::$snippets['head_tags']['canonical'] = '<link rel="canonical" href="'. document::href_ilink('') .'" />';
  document::$snippets['head_tags']['opengraph'] = '<meta property="og:url" content="'. document::href_ilink('') .'" />' . PHP_EOL
                                                . '<meta property="og:type" content="website" />' . PHP_EOL
                                                . '<meta property="og:image" content="'. document::href_link(WS_DIR_APP . 'images/logotype.png') .'" />';

  $_page = new ent_view();

  $content_query = database::query(
    "select p.id, p.type,p.media, p.priority, pi.title, pi.content from ". DB_TABLE_PAGES ." p
    left join ". DB_TABLE_PAGES_INFO ." pi on (p.id = pi.page_id and pi.language_code = '". language::$selected['code'] ."')
    "
  );

  $_page->snippets['content'] = array();

  while ($page = database::fetch($content_query)) {
    $_page->snippets['content'][$page['id']] = array(
      'type' => $page['type'],
      'id' => $page['id'],
      'title' => $page['title'],
      'content' => $page['content'],
      'link' => document::ilink('information', array('page_id' => $page['id'])),
      'blog_link' => document::ilink('blog', array('article_id' => $page['id'])),
      'image' => null,
      'media' => $page['media'],
      'subitems' => array(),
      'priority' => $page['priority'],
    );
  }

  echo $_page->stitch('pages/index');

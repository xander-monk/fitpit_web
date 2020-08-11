<?php

  

    
    document::$snippets['title'][] = language::translate('video_page_title', 'Videos');
    document::$snippets['description'] = '';

    breadcrumbs::add( language::translate('video_page_title', 'Videos'));

    $_page = new ent_view();

    $_page->snippets = array(
      
    );

    echo $_page->stitch('pages/video');

  

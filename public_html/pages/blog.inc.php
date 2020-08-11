<?php

  try {

    if (empty($_GET['article_id'])) throw new Exception('Missing page_id', 400);

    $page = reference::page($_GET['article_id']);

    if (empty($page->id)) {
      throw new Exception(language::translate('error_410_gone', 'The requested file is no longer available'), 410);
    }

    if (empty($page->status)) {
      throw new Exception(language::translate('error_404_not_found', 'The requested file could not be found'), 404);
    }

    document::$snippets['title'][] = !empty($page->head_title) ? $page->head_title : $page->title;
    document::$snippets['description'] = !empty($page->meta_description) ? $page->meta_description : '';

    breadcrumbs::add($page->title);

    $_page = new ent_view();

    $_page->snippets = array(
      'title' => $page->title,
      'content' => $page->content,
      'media' => $page->media,
    );

    echo $_page->stitch('pages/blog');

  } catch (Exception $e) {
    http_response_code($e->getCode());
    notices::add('errors', $e->getMessage());
  }

<?php
  $draw_page = function($page, $page_path, $depth=1, &$draw_page) {
    echo '<li class="page-'. $page['id'] . (!empty($page['opened']) ? ' opened' : '') . (!empty($page['active']) ? ' active' : '') .'">' . PHP_EOL
       . '  <a href="'. htmlspecialchars($page['link']) .'">'. $page['title'] .'</a>' . PHP_EOL;
    if (!empty($page['subpages'])) {
      echo '  <ul class="nav nav-pills nav-stacked">' . PHP_EOL;
      foreach ($page['subpages'] as $subpage) {
        echo PHP_EOL . $draw_page($subpage, $page_path, $depth+1, $draw_page);
      }
      echo '  </ul>' . PHP_EOL;
    }
    echo '</li>' . PHP_EOL;
  };
?>

<section id="box-information-links" class="box">

  <h2 class="title"><span><?php echo language::translate('title_customer_service', 'Customer Service'); ?></span></h2>

  <ul class="nav nav-stacked nav-pills">
    <?php foreach ($pages as $page) $draw_page($page, $page_path, 0, $draw_page); ?>
  </ul>

</section>
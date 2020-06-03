

<!--
<div id="sidebar">
  <?php // include vmod::check(FS_DIR_APP . 'includes/boxes/box_category_tree.inc.php'); ?>

  <?php // include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
</div>-->

<div id="content">
  {snippet:notices}

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_manufacturer_logotypes.inc.php'); ?>

  <section id="box-process" class="box hidden-xs hidden-sm">
    <h1 class="title"><small>Потрясающий</small>Процесс дополнения</h1>
    <div class="process-list">
      <div class="process-bx">
        <img src="../images/process/icon-process-1.png" alt="">
        <p>Сжигай калории</p>
      </div>
      <div class="process-bx">
        <img src="../images/process/icon-process-2.png" alt="">
        <p>Подавляй аппетит</p>
      </div>
      <div class="process-bx">
        <img src="../images/process/icon-process-4.png" alt="">
        <p>Увеличивай энергичность</p>
      </div>
      <div class="process-bx">
        <img src="../images/process/icon-process-8.png" alt="">
        <p>Наслаждайся жизнью</p>
      </div>
    </div>
  </section>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_campaign_products.inc.php'); ?>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_popular_products.inc.php'); ?>

  <section id="box-how-it-works" class="box hidden-xs hidden-sm">
    <div class="video-container">
      <iframe src="https://www.youtube.com/embed/f4lMRYVXM3I" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="how-it-works-text">
      <h1 class="title"><small>Как</small>Это работает</h1>
      <p>Добавить какого-то текста</p>
      <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form humour the and randomised words which don't look even slightly believable. If you are is going to use a passage of Lorem Ipsum</p>
      <a href="#" class="btn btn-success">Do SMTH</a>
    </div>
  </section>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_latest_products.inc.php'); ?>

</div>

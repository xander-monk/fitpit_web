<div class="grid-out">
  <div id="sidebar">
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_blog_links.inc.php'); ?>
  </div>

  <div id="content">
    {snippet:notices}

    <section id="box-information" class="box">
      <img src="/images/<?=$media;?>" alt="" style="    max-width: 50%;    margin: 24px auto;    display: block;">
      <?php echo $content; ?>
    </section>

  </div>
</div>

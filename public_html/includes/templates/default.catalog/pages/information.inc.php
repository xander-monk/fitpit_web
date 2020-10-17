<div class="grid-out">
  <div id="sidebar">
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_information_links.inc.php'); ?>
  </div>

  <div id="content">
    {snippet:notices}

    <section id="box-information" class="box">

      <?php if(isset($extra['price'])) {
        echo $extra['price']. '&nbsp;&nbsp;';
      }?>

      <?php if(isset($extra['enter'])) {
        echo $extra['enter']. '<br><hr>';
      }?>

      <?php echo $content; ?>
    </section>

  </div>
</div>

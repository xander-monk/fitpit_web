<section id="box-manufacturer-links" class="box">

  <h2 class="title"><span><?php echo language::translate('title_manufacturers', 'Manufacturers'); ?></span></h2>

  <ul class="nav nav-stacked nav-pills">
    <?php foreach ($manufacturers as $manufacturer) { ?>
    <li<?php echo (!empty($manufacturer['active']) ? ' class="active"' : ''); ?>><a href="<?php echo htmlspecialchars($manufacturer['link']); ?>"><?php echo $manufacturer['name']; ?></a></li>
    <?php } ?>
  </ul>

</section>

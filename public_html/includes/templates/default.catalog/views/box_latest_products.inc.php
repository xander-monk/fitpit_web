<section id="box-latest-products" class="box">

  <h2 class="title"><span><?php echo language::translate('title_latest_products', 'Latest Products'); ?></span></h2>

  <div class="listing products latest_slider">
    <?php foreach ($products as $product) echo functions::draw_listing_product($product); ?>
  </div>

</section>
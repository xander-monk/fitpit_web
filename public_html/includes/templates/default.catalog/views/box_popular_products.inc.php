<section id="box-popular-products" class="box">

  <h2 class="title"><span><?php echo language::translate('title_popular_products', 'Popular Products'); ?></span></h2>

  <div class="listing products popular_slider">
    <?php foreach ($products as $product) echo functions::draw_listing_product($product); ?>
  </div>

</section>
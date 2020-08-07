<section id="box-similar-products" class="box">

  <h2 class="title"><span><?php echo language::translate('title_similar_products', 'Similar Products'); ?></span></h2>

  <section class="listing products similar_slider">
    <?php foreach ($products as $product) echo functions::draw_listing_product($product); ?>
  </section>

</section>
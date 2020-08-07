<section id="box-campaign-products" class="box">

  <h2 class="title"><span><?php echo language::translate('title_campaign_products', 'Campaign Products'); ?></span></h2>

  <div class="listing products">
    <?php foreach ($products as $product) echo functions::draw_listing_product($product); ?>
  </div>

</section>
<!--
<div id="sidebar">
  <?php // include vmod::check(FS_DIR_APP . 'includes/boxes/box_category_tree.inc.php'); ?>

  <?php // include vmod::check(FS_DIR_APP . 'includes/boxes/box_recently_viewed_products.inc.php'); ?>
</div>-->
<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_slides.inc.php'); ?>

<div id="content">
	{snippet:notices}

	<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_manufacturer_logotypes.inc.php'); ?>

	<section id="box-process" class="box hidden-xs hidden-sm">
		<h1 class="title"><small><?php echo language::translate('awesome', 'Awesome'); ?></small><?php echo language::translate('process_string', 'process'); ?></h1>
		<div class="process-list">
			<? $i=0; foreach(	$content as $item) {  if ($item['type']=='icons') { $i++;?>
			<div class="process-bx">
			<a href="<?=htmlspecialchars($item['link']);?>"><img src="/images/<?=$item['media'];?>" alt="<?=$item['title'];?>"></a>
				<p><a href="<?=htmlspecialchars($item['link']);?>"><?=$item['title'];?></a></p>
				<? if($i < 4) { ?>
				<div class="line"></div>
				<? } ?>
			</div>
			<? }} ?>
		</div>
	</section>

	<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_campaign_products.inc.php'); ?>

	<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_popular_products.inc.php'); ?>

	<section id="box-how-it-works" class="box hidden-xs hidden-sm">
		<? $i=0; foreach(	$content as $item) {  if ($item['type']=='video') {
			if($i>0) continue; 
			$i++; ?>
			<div class="video-container">
				<iframe src="<?=$item['media'];?>" frameborder="0"
					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen></iframe>
			</div>
			<div class="how-it-works-text">
				<h1 class="title"><?=$item['title'];?></h1>
				<?=$item['content'];?>
				<a href="/videos" class="btn btn-success"><?php echo language::translate('all_video', 'All Videos'); ?></a>
			</div>
		<? }} ?>
		
	</section>

	<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_latest_products.inc.php'); ?>

	<section id="box-reviews-slider">
		<div class="reviews-slider">
			<? foreach(	$content as $item) { if ($item['type']=='quotes') {?>
			<div class="slide">
				<div class="review-out">
					<p class="qt">“</p>
					<div style="max-width: 60%;"><?=$item['content'];?></div>
					<div class="review-author">
						<!--<div class="pic"><img src="/images/<?=$item['media'];?>" alt=""></div>-->
						<div>
							<span class="name"><?=$item['title'];?></span>
						</div>
					</div>
				</div>
			</div>
			<? }} ?>
		</div>
	</section>

	<section id="box-articles">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<? $in = 'in'; $collapse = ''; $expanded = 'true'; foreach(	$content as $item) { if ($item['type']=='blog') {?>
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="heading<?=$item['id'];?>" onClick="changePic('pic<?=$item['id'];?>')">
					<h1 class="panel-title" style="font-size: 1.35em;">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$item['id'];?>"
							aria-expanded="<?=$expanded;?>" aria-controls="collapse<?=$item['id'];?>" class="<?=$collapse;?>">
							<?=$item['title'];?>
						</a>
					</h1>
				</div>
				<div id="collapse<?=$item['id'];?>" class="panel-collapse collapse <?=$in;?>" role="tabpanel" aria-labelledby="heading<?=$item['id'];?>">
					<div class="panel-body"><?=substr(strip_tags($item['content']),0,300);?>... <a href="<?=htmlspecialchars($item['blog_link']);?>"><?php echo language::translate('read_more', 'Read more'); ?></a></div>
				</div>
			</div>
			<? $in = ''; $collapse = 'collapsed'; $expanded = 'false'; }} ?>
			
		</div>
		<? $style=""; foreach(	$content as $item) { if ($item['type']=='blog') {?>
		<div class="pic-wrapp"  style="<?=$style;?>" id="pic<?=$item['id'];?>">
			<img src="/images/<?=$item['media'];?>" alt="">
		</div>
		<? $style="display:none;"; }} ?>
	</section>
	<script>
		function changePic(id) {
			$('.pic-wrapp').hide();
			$('#'+id).show();
		}
	</script>

</div>



  <h2 class="title"><span><?php echo language::translate('title_videos', 'Videos'); ?></span></h2>

  <div class="row" style="z-index: 999; position: relative;">
    <?php foreach ($pages as $page) { ?>
      <div class="video-container col-md-4">
				<iframe src="<?=$page['media'];?>" frameborder="0" style="width: 100%;    min-height: 300px;"
					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen></iframe>
			</div>
    <?}?>
  </div>

<ul id="region" class="nav navbar-nav navbar-right">
	<li class="region dropdown">
		<a href="<?php echo document::href_ilink('regional_settings'); ?>"
							data-toggle="lightbox" class="dropdown-toggle">
			<img src="/images/icon_settings.svg" alt="">
		</a>
		<div id="region-modal">
			<ul class="dropdown-menu region-dropdown">
				<li>
					<div class="language"><?php echo language::$selected['name']; ?></div>
					<div class="currency" title="<?php echo currency::$selected['name']; ?>">
						<span><?php echo currency::$selected['code']; ?></span></div>
					<div class="country"><img
							src="<?php echo document::href_link('images/countries/'. strtolower(customer::$data['country_code']) .'.png'); ?>"
							style="vertical-align: baseline;"
							alt="<?php echo reference::country(customer::$data['country_code'])->name; ?>"
							title="<?php echo reference::country(customer::$data['country_code'])->name; ?>" /></div>
					<div class="change"><a href="<?php echo document::href_ilink('regional_settings'); ?>"
							data-toggle="lightbox"
							class="btn btn-default"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></div>
				</li>
			</ul>
			</div>
	</li>
</ul>
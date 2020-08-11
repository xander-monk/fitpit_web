<ul class="nav1 navbar1-nav">

	<?php if ($categories) { ?>

	<?php foreach ($categories as $item) { ?>
	<li class="categorie dropdown">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $item['title']; ?> <b
				class="caret"></b></a>
		<ul class="dropdown-menu">
			<?php foreach ($item['sub'] as $sub_item) { ?>
			<li><a
					href="<?php echo htmlspecialchars($sub_item['link']); ?>"><?php echo $sub_item['title']; ?></a>
			</li>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>

	<?php } ?>

	<?php if ($manufacturers) { ?>
	<li class="manufacturers dropdown">
		<a href="#" data-toggle="dropdown"
			class="dropdown-toggle"><?php echo language::translate('title_manufacturers', 'Manufacturers'); ?>
			<b class="caret"></b></a>
		<ul class="dropdown-menu">
			<?php foreach ($manufacturers as $item) { ?>
			<li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>

	<?php if ($pages) { ?>
	<li class="information dropdown">
		<a href="#" data-toggle="dropdown"
			class="dropdown-toggle"><?php echo language::translate('title_information', 'Information'); ?> <b
				class="caret"></b></a>
		<ul class="dropdown-menu">
			<?php foreach ($pages as $item) { ?>
			<li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>


	<li class="information dropdown">
		<a href="/uk/wholesale"><?php echo language::translate('title_wholesale', 'Wholesale'); ?></a>
	</li>

</ul>

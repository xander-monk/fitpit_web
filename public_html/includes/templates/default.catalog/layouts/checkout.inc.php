<!DOCTYPE html>
<html lang="{snippet:language}" dir="{snippet:text_direction}">
<head>
<title>{snippet:title}</title>
<meta charset="{snippet:charset}" />
<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{snippet:template_path}css/framework.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/app.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/checkout.min.css" />
{snippet:head_tags}
{snippet:style}
</head>
<body>

<div class="hide_below_1200">
			<div class="navbar-contacts">
				<div class="box-content phones">
					<img src="/images/icon_phone.png" alt="">
					<a href="tel:+380992940566">+38 (099) 294 05 66</a>
					<span> | </span>
					<a href="tel:+380934196469">+38 (093) 419 64 69</a>
				</div>
				<a rel="nofollow" class="email" href="mailto:shop@fitpit.com.ua">
					<img src="/images/icon_mail.png" alt="">
					<span>Contact us</span>
				</a>
			</div>

			<div class="nav-out-custom">
				<a class="logotype" href="<?php echo document::href_ilink(''); ?>">
					<img src="<?php echo document::href_link('images/logotype.png'); ?>"
						alt="<?php echo settings::get('store_name'); ?>"
						title="<?php echo settings::get('store_name'); ?>" />
					<span>
						FitPit
						<span>спортивное питание и аксессуары</span>
					</span>
				</a>

				<div class="nav-out-custom_nav">
					<div class="navbar-header">
						<?php echo functions::form_draw_form_begin('search_form', 'get', document::ilink('search'), false, 'class="navbar-form"'); ?>
						<?php  // echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;"'); ?>
						<?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;" autocomplete="off"'); ?>
						<?php echo functions::form_draw_form_end(); ?>

						<div class="text-right user_nav">
							<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
							<a href="#"  data-toggle="lightbox" data-target="#account-modal" class="dropdown-toggle">
								<!--<?php echo functions::draw_fonticon('fa-user'); ?>-->
								<img src="/images/icon_user.svg" alt="">
							</a>
							
							<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_region.inc.php'); ?>
						</div>

						<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_contacts_header.inc.php'); ?>

						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
				</div>
			</div>
			<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_menu.inc.php'); ?>
		</div>

		<div class="show_below_1200">
			<div class="nav_row n0">
				<div class="navbar-contacts">
					<div class="box-content phones">
						<img src="/images/icon_phone.png" alt="">
						<a href="tel:+380992940566">+38 (099) 294 05 66</a>
						<span> | </span>
						<a href="tel:+380934196469">+38 (093) 419 64 69</a>
					</div>
				</div>
			</div>

			<div class="nav-out-custom">
				<div class="nav_row n1">
					<a class="logotype" href="<?php echo document::href_ilink(''); ?>">
						<img src="<?php echo document::href_link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
						<span>FitPit</span>
					</a>
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
				</div>
				<div  id="mobile-menu" aria-expanded="false" style="height:0">
					<div class="site_menu__top_row">
						<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_mobilemenu.inc.php'); ?>
					</div>
					<div class="site_menu__bottom_row">
						
						<a rel="nofollow" class="email" href="mailto:shop@fitpit.com.ua">
							<img src="/images/icon_mail.png" alt="">
							<span>Contact us</span>
						</a>
						<div class="user_nav">
							<a href="#"  data-toggle="lightbox" data-target="#account-modal" class="dropdown-toggle">
								<!--<?php echo functions::draw_fonticon('fa-user'); ?>-->
								<img src="/images/icon_user.svg" alt="">
							</a>
							<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_region.inc.php'); ?>
						</div>
					</div>
				</div>
				<div class="nav_row n2">
					<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_contacts_header.inc.php'); ?>
					<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
				</div>
				<div class="nav_row n3">
					<div class="navbar-header">
						<?php echo functions::form_draw_form_begin('search_form', 'get', document::ilink('search'), false, 'class="navbar-form"'); ?>
						<?php  // echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;"'); ?>
						<?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;" autocomplete="off"'); ?>
						<?php echo functions::form_draw_form_end(); ?>
					</div>
				</div>
			</div>
    </div>
    <style>
			#mobile-menu[aria-expanded="false"] {
				overflow:hidden;
			}
		</style>




<main id="page" class="container"  style="position: relative; max-width:80%; margin: 0 auto;">
  {snippet:content}
</main>

{snippet:foot_tags}
<script src="{snippet:template_path}js/app.min.js"></script>
{snippet:javascript}

<script>
		var ss_limit = 8;
		$('.hide_below_1200 form[name="search_form"] input[name="query"]').autocomplete({
			appendTo: '.hide_below_1200 .navbar-header',
			source: '/ajax/search.json',
			limit: ss_limit,
			onSelect: function (e, term, item) {
				window.location.href = item.data('link');
			},
			onShow: function () {
				console.log('autocomplete onShow')
				$('document').ready(function () {
					var suggestionHeight = $('.hide_below_1200 .autosuggestion').first().outerHeight();
					var sw = $('[name="search_form"]').width();
					$('.hide_below_1200 .autosuggestions').css('max-height', ss_limit * suggestionHeight);
					$('.hide_below_1200 .autosuggestions').css('width', sw);
				});
			},
			renderItem: function (item, search) {
				console.log('autocomplete renderItem', item, search);
				var btn =
					'<button class="btn btn-success hidden-xs" disabled="disabled"><i class="fa fa-cart-plus"></i></button>';
				if (item.data.orderable) {
					btn = '<button class="btn btn-success hidden-xs"><i class="fa fa-cart-plus"></i></button>';
				}
				item.data.html = '<img src="' + item.data.img_src + '" alt=""/><div>' + btn + item.data.name +
					'<br><small>' + item.data.manufacturer + '</small></div>';
				return '<div class="autosuggestion ' + (item.data.type) + '" data-val="' + item.value +
					'" data-link = "' + item.data.link + '">' +
					(item.data.type == 'product' ? item.data.html : '<i class="fa fa-caret-right fa-fw"></i>' +
						item.value) +
					'</div>';
			}
		}).on({
			'focus': function () {
				if ($('.navbar-toggle').is(':visible')) {
					$('.navbar-toggle').trigger('click');
				}
			},
			'blur': function () {
				//$(this).val('')
			}
		});

		$('.show_below_1200 form[name="search_form"] input[name="query"]').autocomplete({
			appendTo: '.show_below_1200 .navbar-header',
			source: '/ajax/search.json',
			limit: ss_limit,
			onSelect: function (e, term, item) {
				window.location.href = item.data('link');
			},
			onShow: function () {
				console.log('autocomplete onShow')
				$('document').ready(function () {
					var suggestionHeight = $('.show_below_1200 .autosuggestion').first().outerHeight();
					var sw = $('.show_below_1200 [name="search_form"]').width();
					$('.show_below_1200 .autosuggestions').css('max-height', ss_limit * suggestionHeight);
					$('.show_below_1200 .autosuggestions').css('width', sw);
				});
			},
			renderItem: function (item, search) {
				console.log('autocomplete renderItem', item, search);
				var btn =
					'<button class="btn btn-success hidden-xs" disabled="disabled"><i class="fa fa-cart-plus"></i></button>';
				if (item.data.orderable) {
					btn = '<button class="btn btn-success hidden-xs"><i class="fa fa-cart-plus"></i></button>';
				}
				item.data.html = '<img src="' + item.data.img_src + '" alt=""/><div>' + btn + item.data.name +
					'<br><small>' + item.data.manufacturer + '</small></div>';
				return '<div class="autosuggestion ' + (item.data.type) + '" data-val="' + item.value +
					'" data-link = "' + item.data.link + '">' +
					(item.data.type == 'product' ? item.data.html : '<i class="fa fa-caret-right fa-fw"></i>' +
						item.value) +
					'</div>';
			}
		}).on({
			'focus': function () {
				if ($('.navbar-toggle').is(':visible')) {
					$('.navbar-toggle').trigger('click');
				}
			},
			'blur': function () {
				//$(this).val('')
			}
		});
	</script>
	<style>

		.featherlight-content .dropdown-menu {
			display:block;
			position: static;
		}

		.autosuggestions {
			position: absolute;
			overflow-y: scroll;
			z-index: 1000;
			background-color: #fdfdfd;
			top: 76px;
			border-radius: 0px;
			box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
		}

		.show_below_1200 [name="search_form"] {
			position:relative;
		}

		.show_below_1200 {
			position:relative;
			z-index:99;
		}
		.show_below_1200 .autosuggestions {
			position: absolute;
			overflow-y: scroll;
			z-index: 1000;
			background-color: #fdfdfd;
			top: 50px;
			border-radius: 0px;
			box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
		}

		.autosuggestion {
			background: #f3f3f3;
			height: 68px;
			padding: 4px;
			border-radius: 4px;
			transition: all 125ms linear;
			cursor: pointer;
			clear: both;
			overflow: hidden;
			position: relative;
		}

		.autosuggestion.category {
			line-height: 68px;
			vertical-align: middle;
		}

		.autosuggestion img {
			float: left;
			padding-right: 7.5px;
		}

		.autosuggestion div {
			overflow: hidden;
			text-overflow: ellipsis;
			line-height: 16px;
			white-space: nowrap;
			padding-top: 8px;
		}

		.autosuggestion button {
			position: absolute;
			right: 4px;
			margin-top: 3px
		}

		.autosuggestion small {
			color: #bbb
		}

		.autosuggestion b {
			color: #000;
		}

		.autosuggestion.selected {
			background: rgba(0, 0, 0, 0.05);
		}

		@media (max-width: 768px) {
			.autosuggestions {
				width: 100%;
				top: 38px;
			}
		}
	</style>

<script>
		$('#box-slides').slick({
			centerMode: true,
			centerPadding: '300px',
			slidesToShow: 1,

			prevArrow: '<button class="slick-arrow slick-prev"><span><i class="fa fa-chevron-left" aria-hidden="true"></i></span></button>',
			nextArrow: '<button class="slick-arrow slick-next"><span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>',

			responsive: [{
					breakpoint: 1200,
					settings: {
						arrows: false,
						centerPadding: '200px'
					}
				},
				{
					breakpoint: 830,
					settings: {
						arrows: false,
						centerPadding: '100px'
					}
				},
				{
					breakpoint: 500,
					settings: {
						arrows: false,
						centerPadding: '50px'
					}
				}
			]
		});

		$('.reviews-slider').slick({
			dots: true,
			//fade: true,
			infinite: true,
			//speed: 100,
			slidesToShow: 1,
			autoplay: true,
			autoplaySpeed: 5000,

			prevArrow: '<button class="slick-arrow slick-prev"><span><i class="fa fa-chevron-left" aria-hidden="true"></i></span></button>',
			nextArrow: '<button class="slick-arrow slick-next"><span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>',

			responsive: [{
				breakpoint: 1200,
				settings: {
					arrows: false
				}
			}]
		});

		$('.popular_slider, .latest_slider, .similar_slider').slick({
			dots: true,
			//centerMode: true,
			infinite: true,
			slidesToShow: 5,
			slidesToScroll: 3,
			autoplay: true,
			autoplaySpeed: 5000,

			prevArrow: '<button class="slick-arrow slick-prev"><span><i class="fa fa-chevron-left" aria-hidden="true"></i></span></button>',
			nextArrow: '<button class="slick-arrow slick-next"><span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>',

			responsive: [{
				breakpoint: 1200,
				settings: {
					arrows: false,
					slidesToShow: 3,
					slidesToScroll: 1
				}
			}, {
				breakpoint: 800,
				settings: {
					arrows: false,
					slidesToShow: 2,
					slidesToScroll: 1,
					centerMode: false
				}
			}, {
				breakpoint: 500,
				settings: {
					arrows: false,
					slidesToShow: 1,
					slidesToScroll: 1,
					centerMode: true
				}
			}]
		});
	</script>
</body>
</html>
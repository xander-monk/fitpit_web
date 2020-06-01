<!DOCTYPE html>
<html lang="{snippet:language}" dir="{snippet:text_direction}">
<head>
<title>{snippet:title}</title>
<meta charset="{snippet:charset}" />
<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Open+Sans:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{snippet:template_path}css/framework.min.css" />
<link rel="stylesheet" href="{snippet:template_path}css/app.min.css" />

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
{snippet:head_tags}
{snippet:style}
</head>
<body>

<div id="page" class="twelve-eighty">

  <?php include vmod::check(FS_DIR_TEMPLATE . 'views/box_cookie_notice.inc.php'); ?>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_menu.inc.php'); ?>

  <header id="header" class="hidden-print">
    <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
      <img src="<?php echo document::href_link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
      <span>FitPit</span>
    </a>

    <div class="text-center hidden-md">
      <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_region.inc.php'); ?>
      <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_contacts_header.inc.php'); ?>
    </div>

    <div class="text-right">
      <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
    </div>
  </header>

  <main>
    <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_slides.inc.php'); ?>
    <section id="main">
      {snippet:content}
    </section>
  </main>

  <?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_site_footer.inc.php'); ?>
</div>

<a id="scroll-up" class="hidden-print" href="#">
  <?php echo functions::draw_fonticon('fa-chevron-circle-up fa-3x'); ?>
</a>

{snippet:foot_tags}
<script src="{snippet:template_path}js/app.min.js"></script>
{snippet:javascript}
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
  $('#box-slides').slick({
    centerMode: true,
    centerPadding: '300px',
    slidesToShow: 1,

    prevArrow: '<button class="slick-arrow slick-prev"><span><i class="fa fa-chevron-left" aria-hidden="true"></i></span></button>',
    nextArrow: '<button class="slick-arrow slick-next"><span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>',

    responsive: [
      {
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
</script>
</body>
</html>
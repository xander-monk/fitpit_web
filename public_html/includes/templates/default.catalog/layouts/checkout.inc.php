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

<header id="header" class="twelve-eighty">
  <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
    <img src="<?php echo document::href_link('images/logotype.png'); ?>" alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />
  </a>

  <div class="middle hidden-xs hidden-sm"></div>

  <div class="customer-service hidden-xs">
    <div class="title"><?php echo language::translate('title_customer_service', 'Customer Service'); ?></div>
    <div class="phone"><?php echo settings::get('store_phone'); ?></div>
  </div>
</header>


<main id="page">
  {snippet:content}
</main>

{snippet:foot_tags}
<script src="{snippet:template_path}js/app.min.js"></script>
{snippet:javascript}
</body>
</html>
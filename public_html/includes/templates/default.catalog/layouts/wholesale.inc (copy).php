<!DOCTYPE html>
<html lang="{snippet:language}" dir="{snippet:text_direction}">
<head>
<title>{snippet:title}</title>
<meta charset="{snippet:charset}" />
<meta name="description" content="{snippet:description}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
{snippet:_env}

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.3.5/css/autoFill.bootstrap4.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.5.2/css/keyTable.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.0.2/css/scroller.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/1.1.1/css/searchPanes.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css"/>
 

<link rel="stylesheet" href="{snippet:template_path}css/wholesale.min.css" />

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.5/js/dataTables.autoFill.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.5/js/autoFill.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/keytable/2.5.2/js/dataTables.keyTable.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.2/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.1.1/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.1.1/js/searchPanes.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

</head>
<body>

<div id="page" class="twelve-eighty">

    <?php include vmod::check(FS_DIR_TEMPLATE . 'views/box_cookie_notice.inc.php'); ?>


    <header id="header">

        <div class="nav-out-custom">
        
            <a class="logotype" href="<?php echo document::href_ilink(''); ?>">
                <img src="<?php echo document::href_link('images/logotype.png'); ?>"
                alt="<?php echo settings::get('store_name'); ?>" title="<?php echo settings::get('store_name'); ?>" />

                <img src="<?php echo document::href_link('images/wholesale_logos.png'); ?>">

            </a>

            <? 
                $EUR = round(1/currency::$currencies['UAH']['value']);
                // echo var_dump(currency::$currencies); 
            ?>

            <div class="nav-out-custom_nav">
                <?php // include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
                <nav id="site-menu" class="navbar hidden-print">
                    <div class="navbar-header">
                    <div id="contacts_header">

                        <div class="box-content">
                            <div class="phone">
                                <a href="tel:+380992940566">+38 (099) 294 05 66</a>
                            </div>
                            <div class="phone">
                                <a href="tel:+380934196469">+38 (093) 419 64 69</a>
                            </div>
                            </div>

                            <div class="icons right">
                                <a rel="nofollow" class="icon email" href="mailto:shop@fitpit.com.ua">
                                    <i class="fa fa-envelope-o"></i>
                                </a>
                                <a rel="nofollow" class="icon telegram " href="tg://">
                                    <i class="fa fa-paper-plane-o"></i>
                                </a>
                                <a rel="nofollow" class="icon viber " href="viber://chat?number=+38-095-904-42-13">
                                    <i class="fa fa-phone"></i>
                                </a>                            
                            </div>
                        </div>
                        <div class="box-account">
                            
                        </div>
                        <div class="box-cart text-right"  id="cart">
                            <div class="row">
                                <div class="col-8 text-right">Замовлення, шт</div>
                                <div class="col-4 quantity" ><?php echo cart::$total['items'];?></div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-right">Сума замовлення, грн</div>
                                <div class="col-4 formatted_value"><?php echo currency::format(cart::$total['value']);?></div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-right">Знижка</div>
                                <div class="col-4 discount" ><?php echo customer::$data['discount'];?>%</div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-right">Курс</div>
                                <div class="col-4 eur-currency"><?php echo $EUR;?></div>
                            </div>
                            <div class="row">
                                <div class="col-8 text-right">До оплати, грн</div>
                                <div class="col-4 total"></div>
                            </div>
                            <!--<div class="row">
                                <div class="col-8 text-right">€</div>
                                <div class="col-4 total-eur"></div>
                            </div>-->

                            <div class=" text-right">
                                <a href="<?php echo document::href_ilink('order_history'); ?>" class="btn btn-secondary btn-sm">
                                    <?php echo functions::draw_fonticon('fa-user'); ?> <?php echo customer::$data['company'];?>  
                                </a>
                                <a class="btn btn-primary btn-sm" href="<?php echo htmlspecialchars(document::ilink('checkout')); ?>">
                                    Оформити
                                </a>
                            </div>
                        </div>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>
        
      
    </header>


    <main id="main" class="mx-1">
        {snippet:content}
    </main>

</div>

<a id="scroll-up" class="hidden-print" href="#">
  <?php echo functions::draw_fonticon('fa-chevron-circle-up fa-3x', 'style="color: #000;"'); ?>
</a>

<style> 
    #header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #header img {max-height: 64px;}
</style>
</body>
</html>
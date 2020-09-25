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

<link href="//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.css" type="text/css" rel="stylesheet" />

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

<script src="//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body class="wholasale-page">
    <? 
        $query = database::query("SELECT * FROM `currencies`");
        $db_currencies = [];
        if (database::num_rows($query) > 0) {
        while ($row = database::fetch($query)) {
            $db_currencies[$row['code']] = $row;
        }  
        }
    
        $EUR = 1/$db_currencies['UAH']['value']; // round(1/currency::$currencies['UAH']['value']);
        $USD = $EUR*$db_currencies['USD']['value']; // round(1/currency::$currencies['UAH']['value']);
        // $EUR = round(1/currency::$currencies['UAH']['value']);
        // echo var_dump(currency::$currencies); 
    ?>

<div id="page" class="twelve-eighty">
<?php include vmod::check(FS_DIR_TEMPLATE . 'views/box_cookie_notice.inc.php'); ?>
    <div id="ws-header">
        <div class="hide_below_1200">
			<div class="navbar-contacts">
				<div class="box-content phones">
					<img src="/images/icon_phone.png" alt="">
					<a href="tel:+380997479754">+38 (099) 747-97-54</a>
				</div>
				<a rel="nofollow" class="email" href="mailto:shop@fitpit.com.ua">
					<img src="/images/icon_mail.png" alt="">
					<span><? echo language::translate('title_contact_us', 'Contact us');?></span>
				</a>
			</div>

			<div class="nav-out-custom">
				<a class="logotype" href="<?php echo document::href_ilink(''); ?>">
					<img src="<?php echo document::href_link('images/logotype.png'); ?>"
						alt="<?php echo settings::get('store_name'); ?>"
						title="<?php echo settings::get('store_name'); ?>" />
					<span>
						FitPit
						<span><? echo language::translate('store_slogan', 'sports nutrition and accessories');?></span>
					</span>
				</a>

				<div class="nav-out-custom_nav">
					<div class="navbar-header">
                        <div id="ws-info">
                            <div class="row cart-block" style="font-size: 14px;">
                                <div class="col-md-4">
                                    
                                    <div class="row">
                                        <div class="col-8 text-right">Курс €:</div>
                                        <div class="col-4 eur-currency"><?php echo number_format($EUR,2);?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 text-right">Курс $:</div>
                                        <div class="col-4 eur-currency"><?php echo number_format($USD,2);?></div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-8 text-right">Сума замовлення, грн</div>
                                        <div class="col-4 formatted_value"><?php echo currency::format(cart::$total['value']);?> (<span class="quantity"><?php echo cart::$total['items'];?></span>)</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 text-right">Знижка</div>
                                        <div class="col-4 discount" ><?php echo customer::$data['discount'];?>%</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 text-right">До оплати, грн</div>
                                        <div class="col-4 total"></div>
                                    </div>
                                </div>
                                
                            </div>
                           
                            
                            
                        </div>
						<div class="text-right user_nav">
							<div id="cart">
                                <a href="/checkout">
                                    <img class="image" src="{snippet:template_path}images/<?php echo !empty($num_items) ? 'cart_filled.svg' : 'cart.svg'; ?>" alt="" />
                                    
                                    <div class="badge quantity"><?php echo cart::$total['items'];?></div>
                                </a>
                            </div>
							<a href="<?php echo document::href_ilink('order_history'); ?>">
								<!--<?php echo functions::draw_fonticon('fa-user'); ?>-->
                                <img src="/images/icon_user.svg" alt="">
                                
							</a>
							
							
						</div>

						

						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
				</div>
			</div>
			
		</div>

		<div class="show_below_1200">
			<div class="nav_row n0">
				<div class="navbar-contacts">
					<div class="box-content phones">
						<img src="/images/icon_phone.png" alt="">
						<a href="tel:+380997479754">+38 (099) 747-97-54</a>
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
                    <div class="site_menu__bottom_row" style="font-size: 12px;">
						<div class="row">
                            <div class="col-8 text-right">Знижка:</div>
                            <div class="col-4 discount" ><?php echo customer::$data['discount'];?>%</div>
                        </div>
                        <div class="row">
                            <div class="col-8 text-right">Курс:</div>
                            <div class="col-4 eur-currency"><?php echo $EUR;?></div>
                        </div>
						
					</div>
					<div class="site_menu__bottom_row">
						
						<a rel="nofollow" class="email" href="mailto:shop@fitpit.com.ua">
							<img src="/images/icon_mail.png" alt="">
							<span>Contact us</span>
						</a>
						<div class="user_nav">
							<a href="<?php echo document::href_ilink('order_history'); ?>">
								<!--<?php echo functions::draw_fonticon('fa-user'); ?>-->
								<img src="/images/icon_user.svg" alt="">
							</a>
						</div>
					</div>
				</div>
				<div class="nav_row n2">
					<div class="cart-block " style="font-size: 12px; width:70%">
                        <div class="row text-right" style="display: block;">
                            <span>Сума:</span>&nbsp;
                            <span class="formatted_value"><?php echo currency::format(cart::$total['value']);?></span>&nbsp;грн
                        </div>
                        <div class="row text-right"  style="display: block;">
                            <span>До оплати:</span>&nbsp;<span class="total"></span>&nbsp;грн
                        </div>
                    </div>
					<?php include vmod::check(FS_DIR_APP . 'includes/boxes/box_cart.inc.php'); ?>
				</div>
				
			</div>
        </div>
        <style>
                #mobile-menu[aria-expanded="false"] {
                    overflow:hidden;
                }
        </style>
    </div>
    


    


    <main id="main" class="mx-1" style="position: relative;  z-index: 99;">
        {snippet:content}
    </main>

</div>

<a id="scroll-up" class="hidden-print" href="#">
  <?php echo functions::draw_fonticon('fa-chevron-circle-up fa-3x', 'style="color: #000;"'); ?>
</a>

<div id="account-modal" class="account dropdown">
    <ul class="dropdown-menu account-dropdown">
            <?php if (!empty(customer::$data['id'])) { ?>
            <li><a
                    href="<?php echo document::href_ilink('order_history'); ?>"><?php echo language::translate('title_order_history', 'Order History'); ?></a>
            </li>
            <li><a
                    href="<?php echo document::href_ilink('edit_account'); ?>"><?php echo language::translate('title_edit_account', 'Edit Account'); ?></a>
            </li>
            <li><a
                    href="<?php echo document::href_ilink('logout'); ?>"><?php echo language::translate('title_logout', 'Logout'); ?></a>
            </li>
            <?php } else { ?>
            <li>
                <?php echo functions::form_draw_form_begin('login_form', 'post', document::ilink('login'), false, 'class="navbar-form"'); ?>
                <?php echo functions::form_draw_hidden_field('redirect_url', !empty($_GET['redirect_url']) ? $_GET['redirect_url'] : document::link()); ?>

                <div class="form-group">
                    <?php echo functions::form_draw_email_field('email', true, 'required="required" placeholder="'. language::translate('title_email_address', 'Email Address') .'"'); ?>
                </div>

                <div class="form-group">
                    <?php echo functions::form_draw_password_field('password', '', 'placeholder="'. language::translate('title_password', 'Password') .'"'); ?>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label><?php echo functions::form_draw_checkbox('remember_me', '1'); ?>
                            <?php echo language::translate('title_remember_me', 'Remember Me'); ?></label>
                    </div>
                </div>

                <div class="btn-group btn-block">
                    <?php echo functions::form_draw_button('login', language::translate('title_sign_in', 'Sign In')); ?>
                </div>
                <?php echo functions::form_draw_form_end(); ?>
            </li>
            <li class="text-center">
                <a
                    href="<?php echo document::href_ilink('create_account'); ?>"><?php echo language::translate('text_new_customers_click_here', 'New customers click here'); ?></a>
            </li>

            <li class="text-center">
                <a
                    href="<?php echo document::href_ilink('reset_password'); ?>"><?php echo language::translate('text_lost_your_password', 'Lost your password?'); ?></a>
            </li>
            <?php } ?>
        </ul>
    </li>
</div>	

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
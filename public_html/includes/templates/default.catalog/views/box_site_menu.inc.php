<nav id="site-menu" class="navbar hidden-print">

  <div class="navbar-header">
    <?php echo functions::form_draw_form_begin('search_form', 'get', document::ilink('search'), false, 'class="navbar-form"'); ?>
      <?php  // echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;"'); ?>
      <?php echo functions::form_draw_search_field('query', true, 'placeholder="'. language::translate('text_search_products', 'Search products') .' &hellip;" autocomplete="off"'); ?>
    <?php echo functions::form_draw_form_end(); ?>

    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#default-menu">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>

  <div id="default-menu" class="navbar-collapse collapse">

    <ul class="nav navbar-nav">
      <li class="hidden-xs">
        <a href="<?php echo document::ilink(''); ?>" title="<?php echo language::translate('title_home', 'Home'); ?>"><?php echo functions::draw_fonticon('fa-home'); ?></a>
      </li>

      <?php if ($categories) { ?>
      
      <?php foreach ($categories as $item) { ?>
        <li class="categorie dropdown">
          <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $item['title']; ?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php foreach ($item['sub'] as $sub_item) { ?>
            <li><a href="<?php echo htmlspecialchars($sub_item['link']); ?>"><?php echo $sub_item['title']; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
      
      <?php } ?>

      <?php if ($manufacturers) { ?>
      <li class="manufacturers dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo language::translate('title_manufacturers', 'Manufacturers'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <?php foreach ($manufacturers as $item) { ?>
          <li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>

      <?php if ($pages) { ?>
      <li class="information dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo language::translate('title_information', 'Information'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <?php foreach ($pages as $item) { ?>
          <li><a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['title']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>

    <ul class="nav navbar-nav navbar-right">
      <li class="customer-service">
        <a href="<?php echo document::href_ilink('customer_service'); ?>"><?php echo language::translate('title_customer_service', 'Customer Service'); ?></a>
      </li>

      <li class="account dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo functions::draw_fonticon('fa-user'); ?> <?php echo !empty(customer::$data['id']) ? customer::$data['firstname'] : language::translate('title_sign_in', 'Sign In'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu account-dropdown">
          <?php if (!empty(customer::$data['id'])) { ?>
            <li><a href="<?php echo document::href_ilink('order_history'); ?>"><?php echo language::translate('title_order_history', 'Order History'); ?></a></li>
            <li><a href="<?php echo document::href_ilink('edit_account'); ?>"><?php echo language::translate('title_edit_account', 'Edit Account'); ?></a></li>
            <li><a href="<?php echo document::href_ilink('logout'); ?>"><?php echo language::translate('title_logout', 'Logout'); ?></a></li>
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
                    <label><?php echo functions::form_draw_checkbox('remember_me', '1'); ?> <?php echo language::translate('title_remember_me', 'Remember Me'); ?></label>
                  </div>
                </div>

                <div class="btn-group btn-block">
                  <?php echo functions::form_draw_button('login', language::translate('title_sign_in', 'Sign In')); ?>
                </div>
              <?php echo functions::form_draw_form_end(); ?>
            </li>
            <li class="text-center">
              <a href="<?php echo document::href_ilink('create_account'); ?>"><?php echo language::translate('text_new_customers_click_here', 'New customers click here'); ?></a>
            </li>

            <li class="text-center">
              <a href="<?php echo document::href_ilink('reset_password'); ?>"><?php echo language::translate('text_lost_your_password', 'Lost your password?'); ?></a>
            </li>
          <?php } ?>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<script>
var ss_limit = 8;
  $('form[name="search_form"] input[name="query"]').autocomplete({
    appendTo: '.navbar-header',
    source: '/ajax/search.json',
    limit: ss_limit,
    onSelect: function (e, term, item) {
      window.location.href = item.data('link');
    },
    onShow: function() {
      $('document').ready(function() {
        var suggestionHeight = $('.autosuggestion').first().outerHeight();
        $('.autosuggestions').css('max-height', ss_limit * suggestionHeight);
      });
    },
    renderItem: function (item, search){
      return '<div class="autosuggestion ' + (item.data.type) + '" data-val="' + item.value + '" data-link = "' + item.data.link +'">' +
      (item.data.type == 'product' ? item.data.html :  '<i class="fa fa-caret-right fa-fw"></i>' + item.value)
      +  '</div>';
    }
  }).on({
    'focus' : function() {
      if($('.navbar-toggle').is(':visible')) {
        $('.navbar-toggle').trigger('click');
      }
    },
    'blur' : function() {
      $(this).val('')
    }
  });
</script>
<style>
  .autosuggestions {
    position: absolute;
    overflow-y: scroll;
    z-index: 1000;
    background-color: #fdfdfd;
    top: 47px;
    border-radius: 4px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.175);
  }
  .autosuggestion {
    background: #f3f3f3;
    height: 68px;
    padding: 4px;
    border-radius: 4px;
    transition: all 125ms linear;
    cursor: pointer;
    clear:both;
    overflow: hidden;
    position: relative;
  }
  .autosuggestion.category {
    line-height: 68px;
    vertical-align: middle;
  }
  .autosuggestion img {
    float:left;
    padding-right:7.5px;
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
    margin-top: -12px
  }
  .autosuggestion small {
    color: #bbb
  }
  .autosuggestion b  {
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

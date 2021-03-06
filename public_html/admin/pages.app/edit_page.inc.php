<?php

  if (empty($_GET['type'])) $_GET['type'] = 'pages';
  if (!empty($_GET['page_id'])) {
    $page = new ent_page($_GET['page_id']);
  } else {
    $page = new ent_page();
  }

  if (empty($_POST)) {
    foreach ($page->data as $key => $value) {
      $_POST[$key] = $value;
    }
  }

  breadcrumbs::add(!empty($page->data['id']) ? language::translate('title_edit_page', 'Edit Page') : language::translate('title_create_new_'.$_GET['type'], 'Create New '.$_GET['type']));

  if (isset($_POST['save'])) {

    try {
      if (empty($_POST['title'])) throw new Exception(language::translate('error_missing_title', 'You must enter a title.'));

      if (empty($_POST['status'])) $_POST['status'] = 0;
      if (empty($_POST['dock'])) $_POST['dock'] = array();

      $fields = array(
        'status',
        'type',
        'parent_id',
        'title',
        'content',
        'dock',
        'priority',
        'head_title',
        'meta_description',
        'media',
        'date',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $page->data[$field] = $_POST[$field];
      }
      //var_dump($_FILES);die;
      if($_POST['type'] != 'video') {
        if (is_uploaded_file($_FILES['media']['tmp_name'])) {
          // var_dump($_FILES['media']['tmp_name']);die;
          $page->save_image($_FILES['media']['tmp_name']);
        }
        
      }

      $page->save();

      if (!empty($_POST['delete_image'])) $page->delete_image();

      

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'pages'), true, array('page_id')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (empty($page->data['id'])) throw new Exception(language::translate('error_must_provide_page', 'You must provide a page'));

      $page->delete();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'pages'), true, array('page_id')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo !empty($page->data['id']) ? language::translate('title_edit_'.$_GET['type'], 'Edit '.$_GET['type']) : language::translate('title_create_new_'.$_GET['type'], 'Create New '.$_GET['type']); ?>
  </div>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('pages_form', 'post', false, true, 'style="max-width: 640px;"'); ?>
      <?php echo functions::form_draw_hidden_field('type', true); ?>
      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_status', 'Status'); ?></label>
          <?php echo functions::form_draw_toggle('status', (isset($_POST['status'])) ? $_POST['status'] : '1', 'e/d'); ?>
        </div>
        <? if($_GET['type'] == 'pages') { ?>
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_priority', 'Priority'); ?></label>
          <?php echo functions::form_draw_number_field('priority', true); ?>
        </div>
        <? } ?>
        <? if($_GET['type'] == 'blog') { ?>
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_date', 'Date'); ?></label>
          <?php echo functions::form_draw_datetime_field('date', true); ?>
        </div>
        <? } ?>
      </div>

      <div class="row">
        <? if($_GET['type'] == 'pages' || $_GET['type'] == 'icons') { ?>
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_dock', 'Dock'); ?></label>
          <div class="checkbox">
            <label><?php echo functions::form_draw_checkbox('dock[]', 'menu', true); ?> <?php echo language::translate('text_dock_in_site_menu', 'Dock in site menu'); ?></label><br />
            <label><?php echo functions::form_draw_checkbox('dock[]', 'customer_service', true); ?> <?php echo language::translate('text_dock_in_customer_service', 'Dock in customer service'); ?></label><br />
            <label><?php echo functions::form_draw_checkbox('dock[]', 'information', true); ?> <?php echo language::translate('text_dock_in_information', 'Dock in information'); ?></label>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_parent', 'Parent'); ?></label>
          <?php echo functions::form_draw_pages_list('parent_id', true); ?>
        </div>
        <? } ?>
      </div>
      
      <? if($_GET['type'] != 'video') { ?>
      <div class="row">

        <div id="image" class=" col-md-12">
          <div class="thumbnail" style="margin-bottom: 15px;">
            <img src="<?php echo document::href_link(WS_DIR_APP . functions::image_thumbnail(FS_DIR_APP . 'images/' . $page->data['media'], 400, 100)); ?>" alt="" />
          </div>

          <div class="form-group">
            <label><?php echo ((isset($page->data['media']) && $page->data['media'] != '') ? language::translate('title_new_image', 'New Image') : language::translate('title_image', 'Image')); ?></label>
            <?php echo functions::form_draw_file_field('media', ''); ?>
            <?php if (!empty($page->data['media'])) { ?>
            <div class="checkbox">
              <label><?php echo functions::form_draw_checkbox('delete_image', 'true', true); ?> <?php echo language::translate('title_delete', 'Delete'); ?></label>
            </div>
            <?php } ?>
          </div>
        </div>

      </div>
      <? } else { ?>
        <div class="row">
    
          <?php if (!empty($page->data['media'])) echo '<p><a target="_blank" href="'. $page->data['media'] .'" alt="" />'.$page->data['media'].'</a></p>'; ?>

          <div class="form-group  col-md-12">
            <label><?php echo language::translate('title_link', 'Link'); ?></label>
            <?php echo functions::form_draw_text_field('media', true); ?>
          </div>

        </div>
      <? } ?>

      <ul class="nav nav-tabs">
        <?php foreach (language::$languages as $language) { ?>
          <li<?php echo ($language['code'] == language::$selected['code']) ? ' class="active"' : ''; ?>><a data-toggle="tab" href="#<?php echo $language['code']; ?>"><?php echo $language['name']; ?></a></li>
        <?php } ?>
      </ul>

      <div class="tab-content">
        <?php foreach (array_keys(language::$languages) as $language_code) { ?>
        <div id="<?php echo $language_code; ?>" class="tab-pane fade in<?php echo ($language_code == language::$selected['code']) ? ' active' : ''; ?>">
          <div class="form-group">
            <label><?php echo language::translate('title_title', 'Title'); ?></label>
            <?php echo functions::form_draw_regional_input_field($language_code, 'title['. $language_code .']', true, ''); ?>
          </div>

          <div class="form-group">
            <label><?php echo language::translate('title_content', 'Content'); ?></label>
            <?php echo functions::form_draw_regional_wysiwyg_field($language_code, 'content['. $language_code .']', true, 'style="height: 400px;"'); ?>
          </div>

          <div class="form-group">
            <label><?php echo language::translate('title_head_title', 'Head Title'); ?></label>
            <?php echo functions::form_draw_regional_input_field($language_code, 'head_title['. $language_code .']', true); ?>
          </div>

          <div class="form-group">
            <label><?php echo language::translate('title_meta_description', 'Meta Description'); ?></label>
            <?php echo functions::form_draw_regional_input_field($language_code, 'meta_description['. $language_code .']', true); ?>
          </div>
        </div>
        <?php } ?>
      </div>

      <div class="panel-action btn-group">
        <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
        <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
        <?php echo (isset($page->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>
</div>

<script>
  $('input[name^="title"]').bind('input propertyChange', function(e){
    var language_code = $(this).attr('name').match(/\[(.*)\]$/)[1];
    $('input[name="head_title['+language_code+']"]').attr('placeholder', $(this).val());
  }).trigger('input');
</script>
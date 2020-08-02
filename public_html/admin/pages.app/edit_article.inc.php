<?php
  
  if (!empty($_GET['article_id'])) {
    $page = new ctrl_blog($_GET['article_id']);
  } else {
    $page = new ctrl_blog();
  }

  if (empty($_POST)) {
    foreach ($page->data as $key => $value) {
      $_POST[$key] = $value;
    }
  }

  breadcrumbs::add(!empty($page->data['id']) ? language::translate('title_edit_page', 'Edit Page') : language::translate('title_create_new_pages', 'Create New Page'));

  if (isset($_POST['save'])) {

    try {
      if (empty($_POST['title'])) throw new Exception(language::translate('error_missing_title', 'You must enter a title.'));
      //if (empty($_POST['dock'])) throw new Exception(language::translate('error_missing_dock', 'You must select a dock.'));

      if (empty($_POST['status'])) $_POST['status'] = 0;

      $fields = array(
        'status',
        'title',
        'content',
        'image',
        'date',
        'head_title',
        'meta_description',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $page->data[$field] = $_POST[$field];
      }

      if (is_uploaded_file($_FILES['image']['tmp_name'])) $page->save_image($_FILES['image']['tmp_name']);
     
      $page->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved successfully'));
      header('Location: '. document::link('', array('doc' => 'articles'), true, array('article_id')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (empty($page->data['id'])) throw new Exception(language::translate('error_must_provide_page', 'You must provide a page'));

      $page->delete();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved successfully'));
      header('Location: '. document::link('', array('doc' => 'articles'), true, array('page_id')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

?>
<h1><?php echo $app_icon; ?> <?php echo !empty($page->data['id']) ? language::translate('title_edit_article', 'Edit Article') : language::translate('title_create_new_pages', 'Create New Page'); ?></h1>

<?php echo functions::form_draw_form_begin('pages_form', 'post', false, true, 'style="max-width: 640px;"'); ?>

  <div class="row">
    <div class="form-group col-md-6">
      <label><?php echo language::translate('title_status', 'Status'); ?></label>
      <?php echo functions::form_draw_toggle('status', (isset($_POST['status'])) ? $_POST['status'] : '1', 'e/d'); ?>
    </div>
  </div>

  <div class="row">

    <div class="form-group col-md-12">
      <label><?php echo language::translate('title_date', 'Date'); ?></label>
      <?php echo functions::form_draw_datetime_field('date', true); ?>
    </div>

  </div>

  <div class="row">
    
    <?php if (!empty($page->data['image'])) echo '<p><img src="'. WS_DIR_IMAGES . $page->data['image'] .'" alt="" class="img-responsive" /></p>'; ?>

    <div class="form-group  col-md-12">
      <label><?php echo language::translate('title_image', 'Image'); ?></label>
      <?php echo functions::form_draw_file_field('image'); ?>
      <?php echo (!empty($page->data['image'])) ? '</label>' . $page->data['image'] : ''; ?>
    </div>

  </div>

  <div class="row">
  <!--<div class="form-group col-md-6">
      <label><?php echo language::translate('title_priority', 'Priority'); ?></label>
      <?php echo functions::form_draw_number_field('priority', true); ?>
    </div>
  </div>-->
  <div class=" col-md-12">
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

  <p class="btn-group">
    <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
    <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
    <?php echo (isset($page->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
  </p>
  </div>
  </div>

<?php echo functions::form_draw_form_end(); ?>
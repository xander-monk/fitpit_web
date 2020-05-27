<?php

  if (!empty($_GET['group_id'])) {
    $group = new ent_customer_group($_GET['group_id']);
  } else {
    $group = new ent_customer_group();
  }

  if (empty($_POST)) {
    foreach ($group->data as $key => $value) {
      $_POST[$key] = $value;
    }
  }

  $groups_query = database::query(
    "select * from ". DB_TABLE_CUSTOMERS_GROUPS .";"
  );

  $groups = ['0'=> ''];
  $page_items = 0;
  while ($group_option = database::fetch($groups_query)) {
    $groups[$group_option['id']] = [$group_option['name'],$group_option['id']];
    if (++$page_items == settings::get('data_table_rows_per_page')) break;
  }

  breadcrumbs::add(language::translate('title_customer_groups', 'Customer groups'), document::link(WS_DIR_ADMIN, array('doc' => 'groups'), array('app')));
  breadcrumbs::add(!empty($supplier->data['id']) ? language::translate('title_edit_customer_group', 'Edit customer group') : language::translate('title_add_new_customer_group', 'Add New Customer group'));

  if (isset($_POST['save'])) {

    try {
      if (empty($_POST['name'])) throw new Exception(language::translate('error_name_missing', 'You must enter a name.'));

      if (!isset($_POST['status'])) $_POST['status'] = '0';

      $fields = array(
        'name',
        'parent'
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $group->data[$field] = $_POST[$field];
      }

      $group->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'groups'), array('app')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (empty($supplier->data['id'])) throw new Exception(language::translate('error_must_provide_supplier', 'You must provide a supplier'));

      $group->delete();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'groups'), array('app')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo !empty($supplier->data['id']) ? language::translate('title_edit_customer_group', 'Edit Customer group') : language::translate('title_add_new_customer_group', 'Add New Supplier'); ?>
  </div>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('customer_group_form', 'post', false, false, 'style="max-width: 640px;"'); ?>

      <div class="row">


        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_name', 'Name'); ?></label>
          <?php echo functions::form_draw_text_field('name', true); ?>
        </div>
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_parent', 'Parent'); ?></label>
          <?php echo functions::form_draw_select_field('parent', $groups, true); ?>
        </div>

      </div>

      <div class="panel-action btn-group">
        <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
        <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
        <?php echo (isset($supplier->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>
</div>

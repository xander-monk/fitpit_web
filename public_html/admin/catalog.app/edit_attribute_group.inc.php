<?php

  if (!empty($_GET['group_id'])) {
    $attribute_group = new ent_attribute_group($_GET['group_id']);
  } else {
    $attribute_group = new ent_attribute_group();
  }

  if (empty($_POST)) {
    foreach ($attribute_group->data as $key => $value) {
      $_POST[$key] = $value;
    }
  }

  breadcrumbs::add(language::translate('title_attribute_groups', 'Attribute Groups'), document::link(WS_DIR_ADMIN, array('doc' => 'attribute_groups'), array('app')));
  breadcrumbs::add(!empty($attribute_group->data['id']) ? language::translate('title_edit_attribute_group', 'Edit Attribute Group') : language::translate('title_create_new_attribute_group', 'Create New Attribute Group'));

  if (isset($_POST['save'])) {

    try {
      if (empty($_POST['values'])) $_POST['values'] = array();

      $fields = array(
        'code',
        'name',
        'values',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $attribute_group->data[$field] = $_POST[$field];
      }

      $attribute_group->save();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'attribute_groups'), array('app')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }

  if (isset($_POST['delete'])) {

    try {
      if (empty($attribute_group->data['id'])) throw new Exception(language::translate('error_must_provide_attribute', 'You must provide a attribute'));

      $attribute_group->delete();

      notices::add('success', language::translate('success_changes_saved', 'Changes saved'));
      header('Location: '. document::link(WS_DIR_ADMIN, array('doc' => 'attribute_groups'), array('app')));
      exit;

    } catch (Exception $e) {
      notices::add('errors', $e->getMessage());
    }
  }
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo !empty($attribute_group->data['id']) ? language::translate('title_edit_attribute_group', 'Edit Attribute Group') : language::translate('title_create_new_attribute_group', 'Create New Attribute Group'); ?>
  </div>

  <div class="panel-body">
    <?php echo functions::form_draw_form_begin('attribute_form', 'post', false, false, 'style="max-width: 640px;"'); ?>

      <div class="row">
        <div class="form-group col-md-6">
          <label><?php echo language::translate('title_code', 'Code'); ?></label>
          <?php echo functions::form_draw_text_field('code', true); ?>
        </div>
      </div>

      <div class="form-group">
        <label><?php echo language::translate('title_name', 'Name'); ?></label>
        <?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_input_field($language_code, 'name['. $language_code .']', true, ''); ?>
      </div>

      <div id="product-values">
        <h2><?php echo language::translate('title_values', 'Values'); ?></h2>

        <table class="table table-striped table-hover data-table">
          <thead>
            <tr>
              <th><?php echo language::translate('title_id', 'ID'); ?></th>
              <th class="main"><?php echo language::translate('title_name', 'Name'); ?></th>
              <th class="text-center"><?php echo empty($attribute_group->data['id']) ? '' : language::translate('title_products', 'Products'); ?></th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
    if (!empty($_POST['values'])) foreach ($_POST['values'] as $key => $group_value) {

      $products_query = database::query(
        "select distinct id from ". DB_TABLE_PRODUCTS_ATTRIBUTES ."
        where group_id = ". (int)$attribute_group->data['id'] .";"
      );
      $num_products = database::num_rows($products_query);
?>
            <tr>
              <td><?php echo $group_value['id']; ?><?php echo functions::form_draw_hidden_field('values['. $key .'][id]', $group_value['id']); ?></td>
              <td><?php foreach (array_keys(language::$languages) as $language_code) echo functions::form_draw_regional_input_field($language_code, 'values['. $key .'][name]['. $language_code .']', true, ''); ?></td>
              <td class="text-center"><?php echo $num_products; ?></td>
              <td class="text-right"><?php echo empty($num_products) ? '<a href="#" class="remove" title="'. language::translate('title_remove', 'Remove') .'">'. functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"') .'</a>' : false; ?></td>
            </tr>
<?php
  }
?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4"><a class="add" href="#"><?php echo functions::draw_fonticon('fa-plus-circle', 'style="color: #66cc66;"'); ?> <?php echo language::translate('title_add_group', 'Add Group Value'); ?></a></td>
            </tr>
          </tfoot>
        </table>

      </div>

      <div class="panel-action btn-group">
        <?php echo functions::form_draw_button('save', language::translate('title_save', 'Save'), 'submit', '', 'save'); ?>
        <?php echo functions::form_draw_button('cancel', language::translate('title_cancel', 'Cancel'), 'button', 'onclick="history.go(-1);"', 'cancel'); ?>
        <?php echo (!empty($attribute_group->data['id'])) ? functions::form_draw_button('delete', language::translate('title_delete', 'Delete'), 'submit', 'onclick="if (!window.confirm(\''. language::translate('text_are_you_sure', 'Are you sure?') .'\')) return false;"', 'delete') : false; ?>
      </div>

    <?php echo functions::form_draw_form_end(); ?>
  </div>
</div>

<script>
  var new_value_index = 1;
  $('form[name="attribute_form"]').on('click', '.add', function(e) {
    e.preventDefault();
    while ($("input[name^='values[new_"+ new_value_index +"][id]']").length) new_value_index++;
<?php
    $name_fields = '';
    foreach (array_keys(language::$languages) as $language_code) $name_fields .= functions::form_draw_regional_input_field($language_code, 'values[new_value_index][name]['. $language_code .']', '', '');
?>
    var output = '<tr>'
               + '  <td><?php echo functions::general_escape_js(functions::form_draw_hidden_field('values[new_value_index][id]', '')); ?></td>'
               + '  <td><?php echo functions::general_escape_js($name_fields); ?></td>'
               + '  <td>&nbsp;</td>'
               + '  <td class="text-right"><a class="remove" href="#" title="<?php echo functions::general_escape_js(language::translate('title_remove', 'Remove'), true); ?>"><?php echo functions::general_escape_js(functions::draw_fonticon('fa-times-circle fa-lg', 'style="color: #cc3333;"')); ?></a></td>'
               + '</tr>';
    output = output.replace(/new_value_index/g, 'new_' + new_value_index);
    $(this).closest('table').find('tbody').append(output);
  });

  $('form[name="attribute_form"]').on('click', '.remove', function(e) {
    e.preventDefault();
    $(this).closest('tr').remove();
  });
</script>
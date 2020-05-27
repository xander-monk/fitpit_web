<?php
  if (empty($_GET['page']) || !is_numeric($_GET['page'])) $_GET['page'] = 1;


// Table Rows
  $groups = array();

  

  $groups_query = database::query(
    "select *, (select name from ". DB_TABLE_CUSTOMERS_GROUPS ." where id = cg.parent) as parent_name from ". DB_TABLE_CUSTOMERS_GROUPS ." as cg;"
  );


  $page_items = 0;
  while ($group = database::fetch($groups_query)) {
    $groups[] = $group;
    if (++$page_items == settings::get('data_table_rows_per_page')) break;
  }

// Number of Rows
  $num_rows = database::num_rows($groups_query);

// Pagination
  $num_pages = ceil($num_rows/settings::get('data_table_rows_per_page'));
?>
<div class="panel panel-app">
  <div class="panel-heading">
    <?php echo $app_icon; ?> <?php echo language::translate('title_customerts_groups', 'Customers'); ?>
  </div>

  <div class="panel-action">
    <ul class="list-inline">
      <li><?php echo functions::form_draw_link_button(document::link(WS_DIR_ADMIN, array('doc' => 'edit_customer_group'), true), language::translate('title_add_new_customer_group', 'Add New Customer Group'), '', 'add'); ?></li>
    </ul>
  </div>

  <div class="panel-body">
    

      <table class="table table-striped table-hover data-table">
        <thead>
          <tr>
            
            <th><?php echo language::translate('title_id', 'ID'); ?></th>
            <th><?php echo language::translate('title_parent_name', 'Parent'); ?></th>
            <th><?php echo language::translate('title_name', 'Name'); ?></th>
            <th>&nbsp;</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($groups as $group) { ?>
          <tr>
            <td><?php echo $group['id']; ?></td>
            <td><?php echo $group['parent_name']; ?></td>
            <td><a href="<?php echo document::href_link('', array('doc' => 'edit_customer_group', 'group_id' => $group['id']), true); ?>"><?php echo $group['name']; ?></a></td>
            <td class="text-right"><a href="<?php echo document::href_link('', array('doc' => 'edit_customer_group', 'group_id' => $group['id']), true); ?>" title="<?php echo language::translate('title_edit', 'Edit'); ?>"><?php echo functions::draw_fonticon('fa-pencil'); ?></a></td>
          </tr>
          <?php } ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="8"><?php echo language::translate('title_customerts_groups', 'Customers'); ?>: <?php echo $num_rows; ?></td>
          </tr>
        </tfoot>
      </table>

      

    
  </div>

  
</div>

<?php

  class ent_customer_group {
    public $data;
    public $previous;

    public function __construct($group_id=null) {

      if (!empty($group_id)) {
        $this->load($group_id);
      } else {
        $this->reset();
      }
    }

    public function reset() {

      $this->data = array();

      $fields_query = database::query(
        "show fields from ". DB_TABLE_CUSTOMERS_GROUPS .";"
      );

      while ($field = database::fetch($fields_query)) {
        $this->data[$field['Field']] = null;
      }

      $this->previous = $this->data;
    }

    public function load($group_id) {

      if (!preg_match('#^[0-9]+$#', $group_id)) throw new Exception('Invalid group (ID: '. $group_id .')');

      $this->reset();

      $group_query = database::query(
        "select *, (select name from ". DB_TABLE_CUSTOMERS_GROUPS ." where id = parent) as parent_name  from ". DB_TABLE_CUSTOMERS_GROUPS ."
        where id=". (int)$group_id ."
        limit 1;"
      );

      if ($group = database::fetch($group_query)) {
        $this->data = array_replace($this->data, array_intersect_key($group, $this->data));
      } else {
        throw new Exception('Could not find group (ID: '. (int)$group_id .') in database.');
      }

      $this->previous = $this->data;
    }

    public function save() {

      if (empty($this->data['id'])) {
        database::query(
          "insert into ". DB_TABLE_CUSTOMERS_GROUPS ."
          (date_created)
          values ('". ($this->data['date_created'] = date('Y-m-d H:i:s')) ."');"
        );
        $this->data['id'] = database::insert_id();
      }

      database::query(
        "update ". DB_TABLE_CUSTOMERS_GROUPS ." set
        name = '". database::input($this->data['name']) ."',
        parent = ". (int)$this->data['parent'] .",
        date_updated = '". ($this->data['date_updated'] = date('Y-m-d H:i:s')) ."'
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $this->previous = $this->data;

      cache::clear_cache('group');
    }

    public function delete() {

      if (empty($this->data['id'])) return;

      database::query(
        "delete from ". DB_TABLE_CUSTOMERS_GROUPS ."
        where id = ". (int)$this->data['id'] ."
        limit 1;"
      );

      $this->reset();

      cache::clear_cache('group');
    }
  }

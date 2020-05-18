<?php

class sm_nova_poshta
{
    public $id = __CLASS__;
    public $name = 'Nova Poshta';
    public $description = '';
    public $author = 'AVV';
    public $version = '1.0';
    public $website = 'http://avv-software.com';

    public function __construct()
    {
        $this->name = language::translate(__CLASS__ . ':title_nova_poshta');
    }

    public function options($items, $subtotal, $tax, $currency_code, $customer)
    {

        if (empty($this->settings['status'])) return;

        // If destination is not in geo zone
        if (!empty($this->settings['geo_zone_id'])) {
            if (!functions::reference_in_geo_zone($this->settings['geo_zone_id'], $customer['shipping_address']['country_code'], $customer['shipping_address']['zone_code'])) return;
        }

        $options = array(
            'title' => language::translate(__CLASS__ . ':title_nova_poshta'),
            'options' => array(
                array(
                    'id' => 'nova_poshta',
                    'icon' => $this->settings['icon'],
                    'name' => language::translate(__CLASS__ . ':title_option_nova_poshta'),
                    'description' => language::translate(__CLASS__ . ':description_option_nova_poshta', ''),
                    'fields' => language::translate(__CLASS__ . ':title_nova_poshta_office_address') .
                        ': <br><textarea class="form-control" name="nova_poshta_office_1">' . @$_SESSION['nova_poshta_office_1'] . '</textarea>',
                    'cost' => 0,
                    'tax_class_id' => 0,
                    'exclude_cheapest' => true,
                ),
                array(
                    'id' => 'nova_poshta_cod',
                    'icon' => $this->settings['icon'],
                    'name' => language::translate(__CLASS__ . ':title_option_nova_poshta_cod'),
                    'description' => language::translate(__CLASS__ . ':description_option_nova_poshta_cod'),
                    'fields' => language::translate(__CLASS__ . ':title_nova_poshta_office_address') .
                        ': <br><textarea class="form-control" name="nova_poshta_office_2">' . @$_SESSION['nova_poshta_office_2'] . '</textarea>',
                    'cost' => 0,
                    'tax_class_id' => 0,
                    'exclude_cheapest' => true,
                ),
            )
        );

        return $options;
    }

    public function before_select($module_id, $option_id)
    {
        if (!empty($_POST['nova_poshta_office_1'])) {
            $_SESSION['nova_poshta_office_1'] = $_POST['nova_poshta_office_1'];
        }
        if (!empty($_POST['nova_poshta_office_1'])) {
            $_SESSION['nova_poshta_office_2'] = $_POST['nova_poshta_office_2'];
        }
    }

    public function after_process($order)
    {
        $office = language::translate(__CLASS__ . ':title_nova_poshta') . ": "
            . $_POST['nova_poshta_office_1'] . " " . $_POST['nova_poshta_office_2'];
        $order->data['customer']['shipping_address']['address1'] = $office . " | " . $order->data['customer']['shipping_address']['address1'];
        $order->data['customer']['shipping_address']['address2'] = $office . " | " . $order->data['customer']['shipping_address']['address2'];
        @mkdir($_SERVER['DOCUMENT_ROOT'] . "/logs/orders");
        @file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/orders/" . $order->data['id'] . ".json",
            json_encode($order->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $order->save();
    }

    function settings()
    {
        return array(
            array(
                'key' => 'status',
                'default_value' => '1',
                'title' => language::translate(__CLASS__ . ':title_status', 'Status'),
                'description' => language::translate(__CLASS__ . ':description_status', 'Enables or disables the module.'),
                'function' => 'toggle("e/d")',
            ),
            array(
                'key' => 'icon',
                'default_value' => '/images/shipping/novaposhta.png',
                'title' => language::translate(__CLASS__ . ':title_icon', 'Icon'),
                'description' => language::translate(__CLASS__ . ':description_icon', 'Web path of the icon to be displayed.'),
                'function' => 'input()',
            ),
            array(
                'key' => 'geo_zone_id',
                'default_value' => '',
                'title' => language::translate(__CLASS__ . ':title_geo_zone_limitation', 'Geo Zone Limitation'),
                'description' => language::translate(__CLASS__ . ':description_geo_zone', 'Limit this module to the selected geo zone. Otherwise leave blank.'),
                'function' => 'geo_zones()',
            ),
            array(
                'key' => 'priority',
                'default_value' => '0',
                'title' => language::translate(__CLASS__ . ':title_priority', 'Priority'),
                'description' => language::translate(__CLASS__ . ':description_priority', 'Process this module by the given priority value.'),
                'function' => 'int()',
            ),
        );
    }

    public function install()
    {
        language::translate(__CLASS__ . ':title_nova_poshta', 'Nova Poshta');
        $this->set_translation('uk', __CLASS__ . ":title_nova_poshta", 'Нова Пошта');
        $this->set_translation('ru', __CLASS__ . ":title_nova_poshta", 'Новая Почта');

        language::translate(__CLASS__ . ':title_nova_poshta_office_address', 'Office number and address');
        $this->set_translation('uk', __CLASS__ . ":title_nova_poshta_office_address", 'Адреса і номер відділення (складу)');
        $this->set_translation('ru', __CLASS__ . ":title_nova_poshta_office_address", 'Адрес и номер отделения (склада)');

        language::translate(__CLASS__ . ':title_option_nova_poshta', 'Prepayment delivery');
        $this->set_translation('uk', __CLASS__ . ":title_option_nova_poshta", 'Передоплата');
        $this->set_translation('ru', __CLASS__ . ":title_option_nova_poshta", 'Предоплата');

        language::translate(__CLASS__ . ':title_option_nova_poshta_cod', 'Cash on delivery');
        $this->set_translation('uk', __CLASS__ . ":title_option_nova_poshta_cod", 'Накладений платіж');
        $this->set_translation('ru', __CLASS__ . ":title_option_nova_poshta_cod", 'Наложенный платеж');

    }

    public function uninstall()
    {
        database::query(
            "delete from " . DB_TABLE_TRANSLATIONS . "
          where code like '%" . __CLASS__ . "%';"
        );
    }

    public function set_translation($language_code, $key, $text)
    {
        database::query(
            "update " . DB_TABLE_TRANSLATIONS . "
          set `text_" . $language_code . "` = '" . $text . "',
          date_updated = '" . date('Y-m-d H:i:s') . "'
          where code = '" . $key . "';"
        );
    }
}

<?php


  customer::require_login();
  header('Content-type: application/json; charset='. language::$selected['charset']);


  $eur = 29; // round(1/currency::$currencies['UAH']['value']);
  $discount = customer::$data['discount'];

  $query = database::query("select *, 0 as cart1, 0 as cart2, 0 as user_price  from _excel limit 10");//  limit 200
  $data = [];
  if (database::num_rows($query) > 0) {
    while ($row = database::fetch($query)) {
      $salemod = 0;
      if(!empty($row['sale']) && $row['sale'] != '') {
        $salemod = (float)$row['sale'] * 100 * -1;
      }
      $row['user_price'] = ceil (( $row['base'] * (1-(int)$salemod/100) ) * (1-(int)$discount/100) * $eur);
      array_push($data, $row);
    }
  }

  echo json_encode([
    'data' => $data
  ]);
  
  /*
  $table = '_excel';
 
  // Table's primary key
  $primaryKey = 'id';
  
  // Array of database columns which should be read and sent back to DataTables.
  // The `db` parameter represents the column name in the database, while the `dt`
  // parameter represents the DataTables column identifier. In this case simple
  // indexes
  $columns = array(
      array( 'db' => 'manufacturer', 'dt' => 0 ),
      array( 'db' => 'category',  'dt' => 1 ),
      array( 'db' => 'name',   'dt' => 2 ),
      array( 'db' => 'size',     'dt' => 3 ),
      array( 'db' => 'flavour',     'dt' => 4 ),
      array( 'db' => 'base',     'dt' => 5 ),
      array( 'db' => 'sale',     'dt' => 6 ),
      array( 'db' => 'rrp',     'dt' => 7 ),
      array( 'db' => 'qty',     'dt' => 8 ),
      array( 'db' => 'expiration',     'dt' => 9 ),
  );
  
  // SQL server connection information
  $sql_details = array(
      'user' => 'webbrain_bb2',
      'pass' => 's%76G%iRx2',
      'db'   => 'webbrain_bb2',
      'host' => 'webbrain.mysql.tools'
  );



  //var_dump(json_encode(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )));

  echo json_encode(
      SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
  );*/

exit;
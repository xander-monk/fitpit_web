<div class="row">
	<div class="widget col-xs-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo language::translate('title_most_visited', 'Most visited products'); ?></h3>
			</div>
			<div class="panel-body table-responsive">
			
				<table class="table table-hover">
					<thead>
						<tr>
							<th data-column="image">&nbsp;</th>
							<th data-column="image"><?php echo language::translate('title_name_product', 'Name'); ?></th>
							<th data-column="views"><?php echo language::translate('title_views', 'Views'); ?></th>
						</tr>
					</thead>
					<tbody>
	<?php
		$num_product_rows = 0;
		$sum = 0;


	 
		
		$products_query = database::query(
			"select p.*, pi.name
			from ". DB_TABLE_PRODUCTS ." p
			left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
			order by views desc
			limit 10;"
		);

		if (database::num_rows($products_query) > 0) {
		 
			while ($product = database::fetch($products_query)) {
			 $num_product_rows++;
			 $sum += $product['views'];
	?>
						<tr>
							<td><?php echo '<img src="'. (!empty($product['image']) ? functions::image_resample(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product['image'], FS_DIR_HTTP_ROOT . WS_DIR_CACHE, 32, 32, 'FIT_USE_WHITESPACING') : WS_DIR_IMAGES .'no_image.png') .'" width="32" height="32" align="absbottom" />'; ?></td>
							<td><a href="<?php echo document::href_link('', array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => $product['id'])); ?>"><?php echo $product['name']; ?></a></td>     
							<td><?php echo $product['views']; ?></td>
						</tr>
	<?php
				
			}
		}
	?>
					</tbody>
					<tfoot>
						<tr class="footer">
							<td colspan="11"><?php echo language::translate('title_products', 'Products'); ?>: <?php echo $num_product_rows; ?></td>
						</tr>
						<tr class="footer">
							<td colspan="11"><?php echo language::translate('title_total_views', 'Total Views'); ?>: <?php echo $sum; ?></td>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
	<div class="widget col-xs-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo language::translate('title_most_sold_products', 'Most sold products'); ?></h3>
			</div>
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th data-column="image">&nbsp;</th>
							<th data-column="name"><?php echo language::translate('title_name_product', 'Name'); ?></th>
							<th data-column="views"><?php echo language::translate('title_sales', 'Sales'); ?></th>
						</tr>
					</thead>
					<tbody>
<?php
	$num_product_rows = 0;
	$sum = 0;

	
	$products_query = database::query(
		"select p.*, pi.name, count(poi.product_id) as MostSold 
		from ". DB_TABLE_PRODUCTS ." p
		left join ". DB_TABLE_PRODUCTS_INFO ." pi on (pi.product_id = p.id and pi.language_code = '". language::$selected['code'] ."')
		left join ". DB_TABLE_ORDERS_ITEMS ." poi on (poi.product_id = p.id)
		Group By poi.product_id
		ORDER BY MostSold  DESC
		limit 10;"
	);

	if (database::num_rows($products_query) > 0) {
	 
		while ($product = database::fetch($products_query)) {
			
$num_product_rows++;
$sum += $product['MostSold'];

?>
						<tr>
							<td><?php echo '<img src="'. (!empty($product['image']) ? functions::image_resample(FS_DIR_HTTP_ROOT . WS_DIR_IMAGES . $product['image'], FS_DIR_HTTP_ROOT . WS_DIR_CACHE, 32, 32, 'FIT_USE_WHITESPACING') : WS_DIR_IMAGES .'no_image.png') .'" width="32" height="32" align="absbottom" />'; ?></td>
							<td><a href="<?php echo document::href_link('', array('app' => 'catalog', 'doc' => 'edit_product', 'product_id' => $product['id'])); ?>"><?php echo $product['name']; ?></a></td>     
							<td style="text-right"><?php echo $product['MostSold']; ?></td>
						</tr>
<?php	
		}
	}
?>
					</tbody>
					<tfoot>
						<tr class="footer">
							<td colspan="11"><?php echo language::translate('title_products', 'Products'); ?>: <?php echo $num_product_rows; ?></td>
						</tr>
					<tr class="footer">
							<td colspan="11"><?php echo language::translate('title_total_sales', 'Total Sales'); ?>: <?php echo $sum; ?></td>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
	</div>
</div>


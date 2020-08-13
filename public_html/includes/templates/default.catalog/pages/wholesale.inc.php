
<script>!function(e){"use strict";function t(t){var n=e(t),a=e(":focus"),r=0;if(1===a.length){var i=n.index(a);i+1<n.length&&(r=i+1)}n.eq(r).focus()}function n(t){var n=e(t),a=e(":focus"),r=n.length-1;if(1===a.length){var i=n.index(a);i>0&&(r=i-1)}n.eq(r).focus()}function a(t){function n(t){return e.expr.filters.visible(t)&&!e(t).parents().addBack().filter(function(){return"hidden"===e.css(this,"visibility")}).length}var a,r,i,u=t.nodeName.toLowerCase(),o=!isNaN(e.attr(t,"tabindex"));return"area"===u?(a=t.parentNode,r=a.name,t.href&&r&&"map"===a.nodeName.toLowerCase()?(i=e("img[usemap=#"+r+"]")[0],!!i&&n(i)):!1):(/input|select|textarea|button|object/.test(u)?!t.disabled:"a"===u?t.href||o:o)&&n(t)}e.focusNext=function(){t(":focusable")},e.focusPrev=function(){n(":focusable")},e.tabNext=function(){t(":tabbable")},e.tabPrev=function(){n(":tabbable")},e.extend(e.expr[":"],{data:e.expr.createPseudo?e.expr.createPseudo(function(t){return function(n){return!!e.data(n,t)}}):function(t,n,a){return!!e.data(t,a[3])},focusable:function(t){return a(t,!isNaN(e.attr(t,"tabindex")))},tabbable:function(t){var n=e.attr(t,"tabindex"),r=isNaN(n);return(r||n>=0)&&a(t,!r)}})}(jQuery);</script>
<main id="content">
  {snippet:notices}

  <div id="box-wholesale" class="box">

  	<table id="wholesale-data" class="table table-striped table-bordered" style="width:100%">
	  	<thead>
            <tr>
				<td>manufacturer</td>
				<td>category</td>
				<td>name</td>
				<td>size</td>
				<td>flavour</td>
				<td>base</td>
				<td>sale</td>
				<td>additional</td>
				<td>rrp</td>
				<td>qty</td>
				<td>expiration</td>
				<td>cart1</td>
				<td>cart2</td>
			</tr>
		</thead>
		
	</table>
  </div>
</main>


<?php 
	echo functions::form_draw_form_begin('buy_now_form', 'post');
	echo functions::form_draw_form_end();
// var_dump($data); ?>

<script>

	var dataSet = <?php echo $data; ?>; 
	

	$(document).ready(function() {

		/*$('#wholesale-data tfoot th').each( function () {
			

			var title = $(this).text();
			console.log('tfoot each', title);
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		});*/

		$.fn.dataTable.ext.errMode = ( settings, techNote, message ) => {
			console.error(settings, techNote, message);
			
		};
		var wholesale = $('#wholesale-data').DataTable( {
			//ajax: "/ajax/wholesale_data.json",
			data: dataSet,
			responsive: {
				details: {
					type: 'column',
					target: 'tr'
				}
			},
			deferRender: true,
			processing: true,
			stateSave:false,

			language: {
				url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Ukranian.json",
				searchPanes: {
                	title:{
						_: 'Вибрано фільтрів - %d',
						0: 'Фільтр'
					},
					clearAll: 'Очистити'
				}
			},

			drawCallback: function( settings ) {
				// console.log( 'DataTables has redrawn the table' , settings, changed, dataSet, $('.waddcart[data-row="'+changed+'"]'));
				setTimeout(() => {
					$('.waddcart[data-row="'+changed+'"]').focus();	
				}, 200);
				$('.waddcart[data-row="'+changed+'"]').focus();
				// changed = -1;
			},
			
			buttons: [
				{
                text: 'Excel',
                action: function ( e, dt, node, config ) {
                    alert('in progress');
                }},
            	{
                text: 'Фільтр',
                action: function ( e, dt, node, config ) {
                    $('.dtsp-panes.dtsp-container').slideToggle();
                }}
			],
			dom: 'BfPrtip', // 'Bfrtip', // 'Pfrtip'
			//dom: '<"dtsp-verticalContainer"<"dtsp-verticalPanes"P><"dtsp-dataTable"frtilp>>',
			searchPanes:{
				columns:[0,1,3,4],
				cascadePanes: true,
				layout: 'columns-4',
				dataLength: 30,
				dtOpts: {
					select: {
						style: 'multi'
					}
				}
				
			},
			pageLength: 50,
			lengthMenu: [ 25, 50, 75, 100 ],
			columns: [
			/* 0 */ 	{ data: "manufacturer" , title: "Виробник", className: "col_manufacturer" },
			/* 1 */ 	{ data: "category" , title: "Категорія", className: "col_category"},
			/* 2 */ 	{ data: "name" , title: "Назва", className: "col_name"},
			/* 3 */ 	{ data: "size" , title: "Вага/об'єм/розмір", className: "col_size"},
			/* 4 */ 	{ data: "flavour" , title: "Смак/колір", className: "col_flavour"},
			/* 5 */ 	{ data: "expiration" , title: "Термін придатності", className: "col_expiration"},
			/* 6 */ 	{ data: "base" , title: "Ціна, Євро", className: "col_base"},
			/* 7 */ 	{ data: "sale" , title: "Уцінка", className: "col_sale"},
			/* 8 */ 	{ data: "additional" , title: "Акція", className: "col_additional"},
			/* 9 */ 	{ data: "user_price" , title: "Ціна, грн", className: "col_rrp"},
			/* 10 */ 	{ data: "qty" , title: "Наявність", className: "col_qty"},
			/* 11 */ 	{ data: "cart1" , title: "Замовлення", className: "col_cart"},
			/* 12 */ 	{ data: "cart2" , title: "Сума", className: "col_summ"}
			],
			// pageLength: 10,
			columnDefs:[
				{ responsivePriority: 2, targets: 0 },
				{ responsivePriority: 3, targets: 1 },
				{ responsivePriority: 1, targets: 2 },
				{ responsivePriority: 1, targets: 3 },
				{ responsivePriority: 1, targets: 4 },
				{ responsivePriority: 5, targets: 5 },
				{ responsivePriority: 1, targets: 6 },
				{ responsivePriority: 6, targets: 7 },
				{ responsivePriority: 6, targets: 8 },
				{ responsivePriority: 1, targets: 9 },
				{ responsivePriority: 99, targets: 10 },
				{ responsivePriority: 99, targets: 11 },
				{ responsivePriority: 99, targets: 12 },
				{ 
					width: "30%", 
					targets: [2] 
				},
				{
					orderable: false,
					targets: [10,11,12]
				},
				{
					searchPanes:{
						columns:[0,1,3,4,5],
						show: true,
						cascadePanes: true,
					}
				},
				{
					searchPanes:{
						show: false,
					},
					targets: [2,6,7,8,9,10,11,12],
				},
				{	
					render: function ( data, type, row ) {
							if(data != undefined && data!='') {
								//console.log(data);
								return '<div>' +100*parseFloat(data) + ' %</div>';
							} else {
								return data;
							}
					},
					targets: 7
				},
				{	
					render: function ( data, type, row ) {
							if(data != undefined && data!='') {
								//console.log(data);
								return '<div>' +data + ' </div>';
							} else {
								return data;
							}
					},
					targets: 8
				},
				{	
					render: function ( data, type, row ) {
						var img = '"';
						if(row.image != '/') {
							img = 'product-with-image" href="'+row.image+'" data-featherlight="image" ';
						}
						return '<div style="background-color:#'+row.bg+'" class="product-name '+img+'  data-link="'+row.link+'">' + data + '</div>';
					},
					targets: 2
				},
				{	
					render: function ( data, type, row ) {
						return '<input type="number" class="waddcart" data-row="'+row.id+'" value="'+data+'" >';
					},
					targets: 11
				},
				{	
					render: function ( data, type, row ) {
						return row.user_price * row.cart1;
					},
					targets: 12
				}
			]/*,
			language: {
				"lengthMenu": "Display _MENU_ records per page",
				"zeroRecords": "Nothing found - sorry",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)"
			}*/
			
		});
	

		

		$('.dtsp-panes.dtsp-container').slideUp();
		setTimeout(() => {
			$('.dtsp-panes.dtsp-container').slideUp();

			console.log($('.dtsp-clearAll'));
			$('.dtsp-clearAll').text('Очистити');
		}, 500);
		$('td.col_flavour').mouseenter(function(e){
			$(this).attr('title', $(this).text());
		});
		// $('#wholesale-data tbody tr:first').addClass('first');

		var changed = -1;

		$('body').on('keydown', '.waddcart', function(e){
		//$('.waddcart').on('keydown', function(e){
			console.log(e.keyCode, );
			var val = parseInt($(this).val()) || 0;
			if(e.keyCode == 38) {
				event.preventDefault();
				if(!$(this).closest('tr').is(':first-child')) {
					$.tabPrev();
				}
			}

			if(e.keyCode == 40 || e.keyCode == 13) {
				event.preventDefault();
				$.tabNext();
			}

			if(e.keyCode == 39) {
				event.preventDefault();
				$(this).val(val+1);
				$(this).trigger('change');
				//console.log('39',$(this).val());
			}

			if(e.keyCode == 37) {
				event.preventDefault();
				if((val-1)>=0) {
					$(this).val(val-1);
					$(this).trigger('change');
				}
			}
			
		})
		$('body').on('change', '.waddcart', function(e){
			
			var id = parseInt($(this).attr('data-row'));
			var val = $(this).val();
			console.log('change', id, val);
			if(val>=0) {
				var state = false;
				wholesale.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
					var row = this.data();
					if(row.id == id) {
						//if(val <= row.qty) {
							var oldval = row.cart1;
							row.cart1 = val;
							row.cart2 = val * row.user_price;
							console.log('every', row);
							this.invalidate();
							// updateCart($(form).serialize() + '&add_cart_product=true');
							updateCart(row, oldval);
							changed = id;
							state = true;
						//}
					}
				});
				console.log()
				if(state == false) {
					return false;
				}
				wholesale.draw(false);
			}

			
		})
		
		$('body').on('click', '.product-name', function(e){
			console.log($(this).attr('data-link'));
		});
		

		console.log(window.config.platform.url);
		
		window.updateCart = function(row, oldval = 0) {
			console.log(row);
			if (row) $('*').css('cursor', 'wait');
			if (row) {
				var data = {
					token: $('[name="token"]').val(),
					product_id: row.product_id,
					'options[Expiration]': row.expiration,
					'options[Size]': row.size,
					'options[Flavour]': row.flavour,
					quantity: row.cart1,
					
					price: row.user_price_eur,
					product_hash: row.product_hash,
					hash:  row.hash,

				}
				

				if(oldval == 0) {
					data.add_cart_product = true;
				} else {
					data['item['+row.cart_key+'][quantity]'] = row.cart1;
					data.update_cart_item = row.cart_key;
				}
			


			}
			$.ajax({
			url: window.config.platform.url + 'ajax/cart.json',
			type: data ? 'post' : 'get',
			data: data,
			cache: false,
			async: true,
			dataType: 'json',
			beforeSend: function(jqXHR) {
				jqXHR.overrideMimeType('text/html;charset=' + $('meta[charset]').attr('charset'));
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if (data) alert('Error while updating cart');
				console.error('Error while updating cart');
				console.debug(jqXHR.responseText);
			},
			success: function(json) {
				if (json['alert']) alert(json['alert']);
				$('.cart-block .items').html('');
				if (json['items']) {
					var changes = 0;
					$.each(json['items'], function(i, item){

						wholesale.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
							var row = this.data();
							if(item.hash == row.hash && item.quantity != row.cart1) {
								console.log('change', item, row);
								row.cart1 = item.quantity;
								row.cart2 = item.quantity * row.user_price;
								this.invalidate();
								changes++;
							}
							if(row.cart_key == '' && item.hash == row.hash) {
								row.cart_key = item.key;
							}
						});
						$('.cart-block .items').append('<li><a href="'+ item.link +'">'+ item.quantity +' x '+ item.name +' - '+ item.formatted_price +'</a></li>');
					});
					if(changes>0) {
						wholesale.draw(false);
					}
					$('.cart-block .items').append('<li class="divider"></li>');
				}
				$('.cart-block .items').append('<li><a href="' + config.platform.url + 'checkout"><i class="fa fa-shopping-cart"></i> ' + json['text_total'] + ': <span class="formatted-value">'+ json['formatted_value'] +'</a></li>');
				$('.cart-block .quantity').html(json['quantity'] ? json['quantity'] : '');
				$('.cart-block .formatted_value').html(json['formatted_value']);

				$('.cart-block .total').html(json['with_discount']);
				$('.cart-block .total-eur').html(json['with_discount_eur']);
				
			},
			complete: function() {
				if (data) $('*').css('cursor', '');
			}
			});
		}
		updateCart()
		var timerCart = setInterval("updateCart()", 60000);
	});

</script>
<style>
	
</style>
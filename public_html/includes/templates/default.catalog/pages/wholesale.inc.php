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
				<td>rrp</td>
				<td>qty</td>
				<td>expiration</td>
				<td>order</td>
			</tr>
		</thead>
		
	</table>
  </div>
</main>

<script>

	$(document).ready(function() {

		/*$('#wholesale-data tfoot th').each( function () {
			

			var title = $(this).text();
			console.log('tfoot each', title);
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		});*/

		var wholesale = $('#wholesale-data').DataTable( {
			ajax: "/ajax/wholesale_data.json",
			deferRender: true,
			processing: true,
			stateSave:true,
			
			/*buttons:[
				'searchPanes'
			],*/
			//dom: 'Pfrtip', // 'Bfrtip', // 'Pfrtip'
			dom: '<"dtsp-verticalContainer"<"dtsp-verticalPanes"P><"dtsp-dataTable"frtilp>>',
			searchPanes:{
				columns:[0,1,3,4,5],
				cascadePanes: true,
				layout: 'columns-1',
				dataLength: 30,
				
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
			/* 7 */ 	{ data: "rrp" , title: "Ціна, грн", className: "col_rrp"},
			/* 8 */ 	{ data: "qty" , title: "Наявність", className: "col_qty"},
			/* 9 */ 	{ data: "cart" , title: "Замовлення", className: "col_cart"},
			/* 10 */ 	{ data: "cart" , title: "Сума", className: "col_summ"}
			],
			// pageLength: 10,
			columnDefs:[
				{ 
					width: "30%", 
					targets: [2] 
				},
				{
					orderable: false,
					targets: [9,10]
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
					targets: [2,6,7,8,9, 10],
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
	

		
		
		wholesale.columns().every( function () {
			var that = this;
	
			$( 'input', this.footer() ).on( 'keyup change', function () {
				if ( that.search() !== this.value ) {
					that
						.search( this.value )
						.draw();
				}
			} );
		});
		
	});

	/*$.ajax({
      url: '/ajax/wholesale_data.json',
      type: 'post',
      data: {type:'processImport', query: { category: 0}},
      cache: false,
      async: true,
      dataType: 'json',
      beforeSend: function(jqXHR) {
        jqXHR.overrideMimeType('text/html;charset=' + $('meta[charset]').attr('charset'));
      },
      error: function(jqXHR, textStatus) {
        // if (data) alert('Error while process import');
        console.warn('Error while process import');
        console.warn(jqXHR.responseText, jqXHR, textStatus);
      },
      success: function(json) {
        console.log('success', json);
      },
      complete: function() {
        // if (data) $('*').css('cursor', '');
      }
    });*/

</script>
<style>
	
</style>
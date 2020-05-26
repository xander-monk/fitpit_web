<main id="content">
  {snippet:notices}

  <div id="box-wholesale" class="box">

  	<table id="wholesale-data" class="display" style="width:100%">
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
			</tr>
		</thead>
		<tfoot>
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
			</tr>
		</tfoot>
	</table>
  </div>
</main>

<script>

	$(document).ready(function() {
		$('#wholesale-data').DataTable( {
			"ajax": "/ajax/wholesale_data.json",
			"deferRender": true,
			"processing": true,
			"dom": "Pfrtip",
			"columns": [
				{ "data": "manufacturer" },
				{ "data": "category" },
				{ "data": "name" },
				{ "data": "size" },
				{ "data": "flavour" },
				{ "data": "base" },
				{ "data": "sale" },
				{ "data": "rrp" },
				{ "data": "qty" },
				{ "data": "expiration" }
			]
			
		} );
	} );

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

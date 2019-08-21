n
<script type="text/javascript">
	$(document).ready( function () {
    	$('#table_custom').DataTable({
    		"pageLength": 50 ,
    		'bAutoWidth': true,
    		'bSortClasses': true,
    		"bPaginate": true,
    		"orderable": false,
        	"bFilter": true
        });
	});
	
	$(document).ready( function () {
    	dialog_support.init("a.modal-dlg");
    	$('.offer_toggle').bootstrapToggle();

    	$('.offer_toggle').on('change', function(){
      	var id = $(this).closest('tr').attr('id');
      	var status = $(this).prop('checked');
      	      	
     	 $.post('<?php echo site_url($controller_name."/change_custom_status"); ?>', {'id': id,'status':status}, function(data) {
				console.log(data);
    	  });
    	});
    });	
   
</script>
<table id="table_custom">
<thead>
	<tr>
		<th>id</th>
		<th>Title</th>
		<th>Alias</th>
		<th>Varchar Value</th>
		<th>Tag </th>
		<th>Int</th>
		<th>Status</th>
	</tr>
</thead>	
<tbody>
<?php
$count = 1;
foreach($data as $row){ ?>		
	<tr  id="<?php echo $row->id ; ?>">
		<td><?php echo $count ; ?></td>
		<td><?php echo $row->title; ?></td>
		<td><?php echo $row->alias; ?></td>
		<td><?php echo $row->varchar_value; ?></td>
		<td><?php echo $row->tag; ?></td>
		<td><?php echo $row->int_value; ?></td>
		<td ><input type="hidden" id="data_hr" value="<?php echo $row->id; ?>">
        <style>
          .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
          .toggle.ios .toggle-handle { border-radius: 20px; }
        </style>
        <input id="status_id" data-href="<?php echo $row->id; ?>" type="checkbox" class="offer_toggle" data-toggle="toggle" <?php if($row->status) echo 'checked';?> data-onstyle="success"data-offstyle="danger" data-style="ios" data-size="mini" />
      </td>
	</tr>

<?php 
	$count++;
}
?>	</tbody> 
</table>
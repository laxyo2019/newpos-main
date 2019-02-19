<hr>
<?php //echo json_encode($transfer_list); ?>
<!-- <div class="row">
	<button id="accept" class="btn btn-danger pull-right">CLOSURE</button>
</div>	 -->
<!-- <hr> -->
<div class="table_list">
	<table id="list" class="display" style="width:100%">
		<thead>
			<tr>
				<th>Challan ID</th>
        <th>Date & Time</th>
        <th>From</th>
        <th>To</th>
        <th>Dispatched By</th>
        <th>Description</th>
        <th>Status</th>
			</tr>
		</thead>
		<tbody>
      <?php foreach($transfer_list as $row){ ?>
        <tr>
          <td><?php echo $row['receiving_id']; ?></td>
          <td><?php echo $row['receiving_time']; ?></td>
          <td><?php echo $this->Stock_location->get_location_name2($row['employee_id']); ?></td>
          <td><?php echo $this->Stock_location->get_location_name2($row['destination']); ?></td>
          <td><?php echo $row['dispatcher_id']; ?></td>
          <td><?php //echo $row['comment']; ?></td>
          <td><?php echo $row['completed']; ?></td>
        </tr>
      <?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	$('#list').DataTable();

	$('#accept').on('click', function(){
		var recv = $('#recv').val();
		var fn_cmnt = $('#final_comment').val();
		console.log('Completing Receiving ID - ' + recv);
		if(!recv){
			alert('Please select a stock transfer');
		}else{
      // Looping to check and addback good inventory to source
      $.post('<?php echo site_url($controller_name."/st_complete");?>', {'recv': recv, 'final_comment': fn_cmnt}, function(data) {
        alert(data);
        location.reload();
      	});
		}
	});

	$('#recv').on('change', function(){
		var id = $(this).val();

		$.post('<?php echo site_url($controller_name."/st_fetch_instance");?>', {'receiving_id': id}, function(data) {
			$('.table_list').html(data);
			$('#list').DataTable();
		})
	});

});
</script>
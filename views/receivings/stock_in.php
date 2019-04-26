<?php //$this->load->view("partial/header"); ?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<div class="row">
	<div class='col-xs-8'>
		<?php echo form_dropdown('receivings', $receivings, 'test', array('class'=>'form-control', 'id'=>'recv')); ?>
	</div>
	<button id="accept" class="btn btn-danger pull-right">CLOSURE</button>
</div>	
<hr>
<div class="table_list">
	<table id="list" class="display" style="width:100%">
		<thead>
			<tr>
				<th>Data</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<hr>
<label for="comment">Comments</label>
<textarea class="form-control" id="final_comment" cols="30" rows="3"></textarea>

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
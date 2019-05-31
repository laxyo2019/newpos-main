<?php //$this->load->view("partial/header"); ?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-hover">
			<thead>
			  <tr>
				<th>All Receivings</th>
				
				<th></th>
			  </tr>
			</thead>
			<tbody>
			<?php foreach($receivings as $key=>$value) : ?>
				<tr>
				  <td><?php echo $value;?></td>				 
				  <td><input type="button" value="Process" id="<?php echo $key;?>" name ="recv_data" class="recv btn btn-sm btn-info"></td>
				</tr>
			<?php  endforeach; ?>
			</tbody>
		</table>

	<div class="modal fade" id="receiving_modal" role="dialog">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
	    <div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Receiving Details</h4>
	  	  </div>
			<div class="modal-body" id="table_list" >
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       	<button type="button" class="btn btn-info"  id= "accept"  >Accept</button>
			</div>
		</div>
	  </div>
	</div>

<script type="text/javascript">
$(document).ready(function()
{
	$('.recv').click(function () {
		var id = $(this).attr('id');
		$.post('<?php echo site_url("Receivings/st_fetch_instance");?>', {'receiving_id': id}, function(data){
			$('#table_list').html(data);
			$('#receiving_modal').modal('show');
		})
		
	});
});
</script>
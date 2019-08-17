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
			<?php $rec_arr = explode('|',$value);
			?>
				<tr class='<?php echo $key; ?>'>
				  <td><?php echo $value;?></td>				 
				  <td>
				  	<input type="button" value="View" id="<?php echo $key;?>" name ="recv_data" class="recv btn btn-sm btn-info">
				  	<button style='display:none' class="btn btn-success btn-sm <?php echo 'process_'.$key;?>">
						  <i class="fa fa-spinner fa-spin"></i>Processing
						</button>
				  	<input type="button" value="Accept" id="<?php echo $key;?>" name ="accept_data" class="<?php echo 'accept_'.$key;?> accept btn btn-sm btn-success " style='margin:0 10px'>
				  	<?php if(empty(trim($rec_arr[1]))): ?>
				  		<input type="button" value="Add Comment" id="<?php echo $key;?>" name ="f_comment" class="f_comment btn btn-sm btn-warning">
						<?php endif; ?>
				  </td>
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
       <!-- 	<button type="button" class="btn btn-info"  id= "accept"  >Accept</button>  accept receivings-->
			</div>
		</div>
	  </div>
	</div>
	<div class="modal fade" id="comment_modal" role="dialog">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
	    <div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Comment</h4>
	  	  </div>
			<div class="modal-body form-group" id="table_list" >
				<textarea  class='form-control' name="comment" id="comment" rows="5"></textarea>
				<input  class='form-control hidden' value='' type='text' id="comment_rec_id" rows="5"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       	<button type="button" class="btn btn-info"  id= "save_comment" >Save</button>
			</div>
		</div>
	  </div>
	</div>

<script type="text/javascript">
$(document).ready(function()
{
	//show all item in receivings
	$('.recv').click(function () {
		var id = $(this).attr('id');
		$.post('<?php echo site_url("Receivings/st_fetch_instance");?>', {'receiving_id': id}, function(data){
			$('#table_list').html(data);
			$('#receiving_modal').modal('show');
		})
	});
	//accept recevings 
	$('.accept').click(function () {
		var id = $(this).attr('id');
		$('.accept_'+id).toggle();
		$('.process_'+id).toggle();
		$.post('<?php echo site_url("Receivings/accept_receiving_items");?>', {'receiving_id': id}, function(data){
			if(data=='Accepted Succesully'){
				alert(data);
				$('.'+id).hide();
			}else{
				alert(data);
			}
			$('.accept_'+id).toggle();
			$('process_'+id).toggle();
		})
	});
	//open modal to add comment
	$('.f_comment').click(function () {
		var id = $(this).attr('id');
		$('#comment_rec_id').val(id);
		$('#comment_modal').modal('show');
	});
	//Add comment to that receiving
	$('#save_comment').click(function () {
		var f_comment = $.trim($("#comment").val());
		var id = $("#comment_rec_id").val();
		$.post('<?php echo site_url("Receivings/add_comment");?>', {'receiving_id': id,'f_comment':f_comment}, function(data){
			alert('succesfully added.');
			$('#comment_modal').modal('hide');
			console.log('f_comment',f_comment);
			if(f_comment.length!=0){
				$('.f_comment').hide();
			}
		})
	});
});
</script>
<input type="hidden" id="type" value="<?php echo $type; ?>">
<?php if($type == 'subcategories'): ?>
	<div class="form-group">
		<?php echo form_label('Category Filter', 'parent_id', array('class'=>'control-label col-md-3')); ?>
		<div class='col-md-9'>
				<?php echo form_dropdown('parent_id', $categories, 'test', array('class'=>'form-control', 'id'=>'parent_id')); ?>
		</div>
	</div>
	<br><br><br>
<?php endif; ?>
<div class="table_list">
<table id="list" class="display nowrap" style="width:100%">
    <thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<!-- <th>Action</th> -->
			</tr>
    </thead>
    <tbody>
			<?php foreach ($$type as $key => $value): ?>
				<tr>
					<td><?php echo $key; ?></td>
					<td class="v-data" id="<?php echo $key; ?>"><span><?php echo $value; ?></span></td>
					<!-- <td class="v-trash" id="<?php //echo $key; ?>"><span class="glyphicon glyphicon-trash"></span></td> -->
				</tr>
			<?php endforeach; ?>
    </tbody>
</table>
</div>
<script type="text/javascript">
	$(document).ready( function () {
		$('#list').DataTable({
        dom: 'Bfrtip',
        buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

		$(".table_list").on('click', '.v-data', function(event){
			var type = $('#type').val();
			var id = $(this).attr('id');
			var text = $(this).find("span").text();
			if($('#parent_id').val() != null){
				var parent_id = $('#parent_id').val();
			}else{
				var parent_id = '';
			}
			

			// event.preventDefault();
			// event.stopPropagation();
			var new_val = prompt("Please enter value", text);
			if (new_val != null)
			{   
				$.post('<?php echo site_url($controller_name."/mci_update");?>', {'name': new_val, 'id': id, 'type': type, 'parent_id': parent_id}, function(data) {
	        console.log(data);
					$('#'+id).find("span").text(new_val);
      	});
			}
		});

	// Filter subcategory on change in category dropdown
		$("#parent_id").on('change', function(){
			var parent_id = $(this).val();
			if(parent_id)
			{
				$.post('<?php echo site_url($controller_name."/ajax_fetch_subcategories");?>', {'parent_id': parent_id}, function(data) {
	        $('.table_list').html(data);
					$('#list').DataTable();
      	});
			}
		});

		// $(".table_list").on('click', '.v-trash', function(event){
		// 	var type = $('#type').val();
		// 	var id = $(this).attr('id');

		// 	if(confirm("Are you sure?"))
		// 	{
		// 		$.post('<?php //echo site_url($controller_name."/mci_delete");?>', {'id': id, 'type': type}, function(data) {
		// 			if(data == 'failed'){
		// 				alert('Cannot Delete (in-use)');
		// 			}else{
		// 				$('.modal-dlg').modal('hide');
		// 			}
		// 		});
		// 	}
		// });	
				
	});
</script>
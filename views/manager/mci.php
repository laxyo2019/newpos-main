<hr>
<div class="row">
	<span class="col-md-2 pull-right">
		<select id="select_mci" class="form-control">
			<option value="">Select MCI</option>
			<option value="categories">Category</option>
			<option value="subcategories">Subcategory</option>
			<option value="brands">Brand</option>
			<option value="sizes">Size</option>
			<option value="colors">Color</option>
		</select>
	</span>
	
	<span class="col-md-6">
		<input type="text" id="create_mci" class="form-control input-sm" placeholder="Create MCI">
		<span id="liveresults_area"></span>
	</span>
	<span class="col-md-1">
		<input type="text" id="index" class="form-control input-sm" placeholder="ID" style="display:none">
	</span>
	<span class="col-md-2">
		<button id="save" class="btn btn-sm btn-success">Create</button>
	</span>
</div>
<hr>
<div class="row">
	<div class="col-md-6 col-md-offset-3" id="mci_sublist">
		
	</div>
	<div class="col-md-3">
		<select id="cSwitch" class="form-control" style="display:none">
			<?php foreach($this->Item->get_mci_data('categories') as $row): ?>
				<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<script>
	$(document).ready(function() 
	{
		$('#create_mci').on('keyup', function(){ //livesearch
			var selected_mci = $('#select_mci').val();
			console.log(selected_mci);
			if(selected_mci != "")
			{
				var mci = $('#create_mci').val();
				if(mci.length > 0)
				{
					$.post('<?php echo site_url($controller_name."/mci_livesearch");?>', {'keyword': mci, 'type': selected_mci}, function(data) {
						$('#liveresults_area').html(data);
					});
				}
				else
				{
					$('#liveresults_area').html('');
				}
				
			}
			else
			{
				alert("Please select the MCI");
			}
		});

		$('#select_mci').on('change', function(){
			var selected_mci = $(this).val();
			var main = ["categories", "subcategories"];
			var main2 = ["subcategories"];
			$('#index').toggle(main.includes(selected_mci));
			$('#cSwitch').toggle(main2.includes(selected_mci));
			$.post('<?php echo site_url($controller_name."/get_mci_list"); ?>', {'type': selected_mci}, function(data) {
				$('#mci_sublist').html(data);
				$('#list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

		$('#cSwitch').on('change', function(){
			var parent_id = $(this).val();
			// console.log(parent_id);
			$.post('<?php echo site_url($controller_name."/get_mci_sublist");?>', {'parent_id': parent_id}, function(data) {
				$('#mci_sublist').html(data);
				$('#list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

		$('#save').on('click', function(){
			<?php if($this->Item->is_superadmin()){ ?>
				var id = "";
				var type = $('#select_mci').val();
				var name = $('#create_mci').val();
				var parent_id = "";
				if(type == 'categories')
				{
					id = $('#index').val();
				}
				if(type == 'subcategories')
				{
					id = $('#index').val();
					parent_id = $('#cSwitch').val();
				}
				$.post('<?php echo site_url($controller_name."/mci_save");?>', {'type': type, 'id': id, 'name': name, 'parent_id': parent_id}, function(data) {
					alert(data);
					// location.reload();
				});

			<?php }else{ ?>
				alert('Access Denied!');
			<?php } ?>
		});

	});
</script>
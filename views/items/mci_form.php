<div class="form-group">
	<?php 
		if($type == 'subcategories')
		{
			echo '<span class="col-xs-8"> <label>Parent Category</label> </span>';
			echo form_dropdown('parent_id', $categories, 'test', array('class' => 'form-control','id' => 'parent_id'));
		}
		echo form_input(array(
			'id'=>'master_type',
			'type' => 'hidden',
			'value' => $type
		));
	?>
	<br>

	
		<?php 
			if($type == 'subcategories')
			{
				echo "<span class='col-md-3'>";
					echo form_input(array(
					'id'=>'primary_id',
					'class'=>'form-control',
					'placeholder' => 'ID'
				));
				echo "</span>";
			}
		?>
	
	<span class='col-md-9'>
		<?php echo form_input(array(
			'id'=> 'name',
			'class'=>'form-control',
			'placeholder' => 'Name'
		));
		?>
	</span>
</div><br><br>
<center><button class="btn btn-success" id="save">Save</button></center>

<script>
	$(document).ready(function(){
		$('#save').on('click', function(){
			var id = "";
			var type = $('#master_type').val();
			var name = $('#name').val();
			var parent_id = "";
			if(type == 'subcategories')
			{
				id = $('#primary_id').val();
				parent_id = $('#parent_id').val();
			}
			$.post('<?php echo site_url($controller_name."/mci_save");?>', {'type': type, 'id': id, 'name': name, 'parent_id': parent_id}, function(data) {
				alert(data);
			});
			
		});
	});
</script>
<table id="cashier_list" class="display nowrap" style="width:100%">
	<thead>
		<tr>
			<th>Sale Code</th>
			<th>Name</th>
			<th>Shop</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cashiers as $row): ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><span><?php echo $this->Employee->get_info($row['shop_id'])->first_name; ?></span></td>
				<!-- <span class="glyphicon glyphicon-edit"></span> -->
				<td id="<?php echo $row['id']; ?>"><span style="padding-left: 20px" class="glyphicon glyphicon-trash"></span></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>


<script type="text/javascript">
	$(document).ready(function(){
		$('#cashier_list').DataTable();

		$('.glyphicon-edit').on('click', function(){
			var id = $(this).closest('td').attr('id');
		});

		$('.glyphicon-trash').on('click', function(){
			var id = $(this).closest('td').attr('id');
			console.log(id);
			$.post('<?php echo site_url($controller_name."/cashier_delete");?>', {'id': id}, function(data) {
				alert(data);
				location.reload();
      });
		});
	
	});
</script>
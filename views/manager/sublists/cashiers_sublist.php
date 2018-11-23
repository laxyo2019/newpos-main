<table id="cashier_list" class="display nowrap" style="width:100%">
	<thead>
		<tr>
			<th>Sale Code</th>
			<th>Name</th>
			<th>Shop</th>
			<th>Contact</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cashiers as $row): ?>
			<tr id="<?php echo $row['id']; ?>">
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><span><?php echo $this->Employee->get_info($row['shop_id'])->first_name; ?></span></td>
				<td><?php echo $row['contact']; ?></td>
				<td>
					<style>
						.toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
						.toggle.ios .toggle-handle { border-radius: 20px; }
					</style>
					<input type="checkbox" class="cashier_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>


<script type="text/javascript">
	$(document).ready(function(){
		$('.cashier_toggle').bootstrapToggle();
		$('#cashier_list').DataTable();

		$('.cashier_toggle').on('change', function(){
      var id = $(this).closest('tr').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/cashier_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });
	
	});
</script>
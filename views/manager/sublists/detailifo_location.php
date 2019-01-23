<style>
	th{
		text-align: center;
	}
</style>
<table id="edit_action_list" class="table table-bordered table table-hover" style="width:100%">
	<thead>
		<tr>
			<th>Id</th>
			<th>Location Name</th>
			<th>Shop Incharge</th>
			<th>Alias</th>
			<th>Address</th>
			<th>T&C</th>
			<th>Action</th>
			<!-- <th>Action</th> -->
			
		</tr>
	</thead>
	<tbody>
		<?php foreach ($editadd as $row): ?>
			<tr>
				<td><?php echo $row['location_id']; ?></td>
				<td><?php echo $row['location_name']; ?></td>
				<td><?php echo $row['shop_incharge']; ?></td>
				<td><?php echo $row['alias']; ?></td>
				<td><?php echo $row['address']; ?></td>
				<td><?php echo $row['tnc']; ?></td>
				<td><a class="modal-dlg-wide" title="Edit" href="<?php echo site_url($controller_name."/edit_stocklocation/".$row['location_id']); ?>"><span class="glyphicon glyphicon-pencil"></a></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script>
	 $(document).ready( function () {
	dialog_support.init("a.modal-dlg-wide, button.modal-dlg");
});
</script>
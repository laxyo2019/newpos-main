<table id="edit_action_list" class="display nowrap" style="width:100%">
	<thead>
		<tr>
			<th>Id</th>
			<th>Location</th>
			<th>Owner</th>
			<th>Alias</th>
			<th>Address</th>
			<th>Shop Incharge</th>
			<th>T&C</th>
			<th>Deleted</th>
			<!-- <th>Action</th> -->
			
		</tr>
	</thead>
	<tbody>
		<?php foreach ($editadd as $row): ?>
			<tr>
				<td><?php echo $row['location_id']; ?></td>
				<td><?php echo $row['location_name']; ?></td>
				<td><?php echo $row['location_owner']; ?></td>
				<td><?php echo $row['alias']; ?></td>
				<td><?php echo $row['address']; ?></td>
				<td><?php echo $row['shop_incharge']; ?></td>
				<td><?php echo $row['tnc']; ?></td>
				<td><?php echo $row['deleted']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

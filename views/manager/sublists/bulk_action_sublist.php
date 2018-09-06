<table id="bulk_action_list" class="display nowrap" style="width:100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>User</th>
			<th>Method</th>
			<th>Info</th>
      <th>Time</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($bulk_actions as $row): ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $this->Person->get_info($row['user_id'])->first_name; ?></td>
				<td><?php echo $row['method']; ?></td>
				<td>
					<?php foreach(json_decode($row['info']) as $key=>$value)
					echo $key.': '.$value.' | ';
					?>
				</td>
        <td><?php echo $row['time']; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

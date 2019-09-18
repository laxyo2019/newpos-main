<table class="table table-hover table-bordered" >
	<thead >
		<tr>
			<th>Login Time</th>
			<th>Logout Time</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($login as $row) :?>
			<tr>
				<td><?php echo $row->logintime;?></td>
				<td><?php echo $row->logouttime;?></td>
				<td><?php echo $row->date ; ?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>
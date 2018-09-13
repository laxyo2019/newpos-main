<table id="extras_list" class="display nowrap" style="width:100%">
	<thead>
		<tr>
      <th>Barcodes</th>
		</tr>
	</thead>
	<tbody>
    <?php foreach (json_decode($items) as $row): ?>
			<tr>
				<td><?php echo $row; ?></td>
			</tr>
    <?php endforeach; ?>
	</tbody>
</table>
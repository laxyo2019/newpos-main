<?php $this->load->view("partial/header"); ?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<table id="list" class="display">
  <thead>
		<tr>
			<th>Sn.</th>
			<th>Barcode</th>
			<th>Name of goods</th>
			<th>Category</th>
			<th>Subcategory</th>
			<th>Quantity</th>
		</tr>
	</thead>
  <tbody>
		<?php $i = 1; ?>
		<?php foreach($items as $row): ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $row->item_number; ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo $row->category; ?></td>
				<td><?php echo $row->subcategory; ?></td>
				<td><?php echo $row->quantity; ?></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready(function(){
    $('#list').DataTable({
			"pageLength": 20,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
  });
</script>

<?php $this->load->view("partial/footer"); ?>
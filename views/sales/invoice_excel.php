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
			<th>Barcode</th>
			<th>Particulars</th>
			<th>HSN>
			<th>MRP</th>
      <th>Discount</th>
      <th>Discounted Price</th>
      <th>Quantity</th>
      <th>Tax Rate</th>
      <th>Tax Amount</th>
		</tr>
	</thead>
  <tbody>
		<?php foreach($cart as $line=>$item): ?>
			<tr>
				<td><?php echo $item['item_number']; ?></td>
				<td><?php echo $item['name']; ?></td>
				<td><?php echo $item['custom1']; ?></td>
				<td><?php echo $item['price']; ?></td>
        <td><?php echo $item['discount']; ?></td>
        <td><?php echo $item['price'] - ($item['price'] * ($item['discount'] / 100)); ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $this->Item_taxes->get_item_invoice_tax_rate($item['item_id'], $item['price'], $item['discount']); ?></td>
        <td><?php echo $item['taxable_total']; ?></td>
			</tr>
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
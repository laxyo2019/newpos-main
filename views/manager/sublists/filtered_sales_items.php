<table id="myTable">
		<thead>
			<th>Sale Id</th>
			<th>Sale Time</th>
			<th>Customer Name</th>
			<th>Customer GST No.</th>
			<th>Invoice No.</th>
			<th>Shop ID</th>
			<th>Barcode</th>
			<th>Item Name</th>
			<th>Item Category</th>
			<th>Item Subcategory</th>
			<th>Item Brand</th>
			<th>Taxable value</th>
			<th>HSN</th>
			<th>CGST %</th>
			<th>CGST Amt.</th>
			<th>SGST %</th>
			<th>SGST Amt.</th>
			<th>IGST %</th>
			<th>IGST Amt.</th>
			<th>Quantity</th>
			<th>Item Type</th>
			<th>Discount</th>
			<th>Gross Value</th>
			<th>Sale Payments</th>
			<th>Sale Type</th>
			<th>Sale Status</th>
			<th>Customer Type</th>
			<th>Stock Edition</th>
		</thead>
 		<tbody>
			<?php  foreach($report_results as $row):
				$discounted_price = $row['item_price'] - bcmul($row['item_price'], bcdiv($row['item_discount'], 100));
	      $tax_data = $this->Item_taxes->get_sales_tax($row['sale_id'], $row['item_id']);
	  
	  
	      $total_tax = ((empty($tax_data['tax_percents']['CGST'])) ? 0.00 : $tax_data['tax_percents']['CGST']) + ((empty($tax_data['tax_percents']['SGST'])) ? 0.00 : $tax_data['tax_percents']['SGST']) + ((empty($tax_data['tax_percents']['IGST'])) ? 0.00 : $tax_data['tax_percents']['IGST']);
  
	        $price = bcmul($discounted_price, $row['quantity']);
	        $a = $price * $total_tax;
	        $b = 100 + $total_tax;
	        $taxable_value = $a / $b;
	        $customer_info = $this->Customer->get_info($row['customer_id']);
				  $sale_payments = $this->Sale->get_sale_payment_types($row['sale_id']);
				  $sale_payment ='';
				  foreach($sale_payments as $pays){
				  $sale_payment .= $pays['payment_type']." ";
				  } 
      ?>
      <tr>
      <td><?php echo $row['sale_id'] ?></td>
      <td><?php echo $row['sale_time'] ?></td>
      <td><?php echo $customer_info->first_name." ".$customer_info->last_name  ?></td>
      <td><?php echo $customer_info->gstin; ?></td>
      <td><?php echo $row['tally_number']; ?></td>
      <td><?php echo $this->Stock_location->get_location_name2($row['employee_id']) ?></td>
      <td><?php echo $row['item_number'] ;?></td>
      <td><?php echo $row['name'];?></td>
      <td><?php echo $row['category'];?></td>
      <td><?php echo $row['subcategory'];?></td>
      <td><?php echo $row['brand'];?></td>
      <td><?php echo $price - round($taxable_value, 2);?></td>
      <td><?php echo $row['custom1'];?></td>
      <td><?php echo (empty($tax_data['tax_percents']['CGST'])) ? NULL : $tax_data['tax_percents']['CGST'];?></td>
      <td><?php echo (empty($tax_data['tax_amounts']['CGST'])) ? NULL : $tax_data['tax_amounts']['CGST'];?></td>
      <td><?php echo (empty($tax_data['tax_percents']['SGST'])) ? NULL : $tax_data['tax_percents']['SGST'];?></td>
      <td><?php echo  (empty($tax_data['tax_amounts']['SGST'])) ? NULL : $tax_data['tax_amounts']['SGST'];?></td>
      <td><?php echo (empty($tax_data['tax_percents']['IGST'])) ? NULL : $tax_data['tax_percents']['IGST'];?></td>
      <td><?php echo (empty($tax_data['tax_amounts']['IGST'])) ? NULL : $tax_data['tax_amounts']['IGST'];?></td>
      <td><?php echo  to_quantity_decimals($row['quantity']);?></td>
      <td><?php echo ($row['unit_price'] == 0.00) ? "FP" : "DISC";?></td>
      <td><?php echo $row['item_discount'];?></td>
      <td><?php echo $price;?></td>
      <td><?php echo $sale_payment;?></td>
      <td><?php echo ($row['sale_type'] == 1) ? "Invoice" : "Credit Note";?></td>
      <td><?php echo ($row['sale_status'] == 0) ? "Active" : "Cancelled";?></td>
      <td><?php echo ($row['bill_type'] == 'ys') ? "Special Approval" : ucfirst($row['bill_type']);?></td>
      <td><?php echo  $row['custom6'];?></td>
    </tr>
			<?php endforeach; ?>
		</tbody> 
</table>
<script>
	$(document).ready(function () {

		 $('#myTable').DataTable({
		 	dom: 'Bfrtip',
	        order: [[0, 'desc']],
	        buttons: [
	          'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
		 });

	})
</script>
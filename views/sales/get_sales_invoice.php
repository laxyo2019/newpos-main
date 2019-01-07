<table id="report_list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>Sale ID</th>
      <th>Sale Time</th>
      <th>Customer Name</th>
      <th>Tally Number</th>
      <th>Invoice Number</th>
      <th>Amount Due</th>
      <th>Barcode</th>
      <th>Taxable Value</th>
      <th>Quantity</th>
      <th>Discount</th>
      <th>Gross Value</th>
      <th>Sale Status</th>
      </tr>
  </thead>
  <tbody>
    <?php foreach ($sales_results as $row): 
      $discounted_price = $row['item_price'] - bcmul($row['item_price'], bcdiv($row['item_discount'], 100));
      $tax_data = $this->Item_taxes->get_sales_tax($row['sale_id'], $row['item_id']);

      $total_tax = ((empty($tax_data['tax_percents']['CGST'])) ? 0.00 : $tax_data['tax_percents']['CGST']) + ((empty($tax_data['tax_percents']['SGST'])) ? 0.00 : $tax_data['tax_percents']['SGST']) + ((empty($tax_data['tax_percents']['IGST'])) ? 0.00 : $tax_data['tax_percents']['IGST']);

      $price = bcmul($discounted_price, $row['quantity']);
      $a = $price * $total_tax;
      $b = 100 + $total_tax;
      $taxable_value = $a / $b;

      $item_info = $this->Item->get_info($row['item_id']);
      $customer_info = $this->Customer->get_info($row['customer_id']);
      $sale_payments = $this->Sale->get_sale_payment_types($row['sale_id']);
      ?>
      <tr>
        <td><?php echo $row['sale_id']; ?></td>
        <td><?php echo $row['sale_time']; ?></td>
        <td><?php echo $customer_info->first_name." ".$customer_info->last_name; ?></td>
        <td><?php echo $row['tally_number']; ?></td>
        <td><?php echo $row['invoice_number']; ?></td>
        <td><?php echo $row['payment_amount']; ?></td>
        <td><?php echo $item_info->item_number; ?></td>
        <td><?php echo $price - round($taxable_value, 2); ?></td>
        <td><?php echo to_quantity_decimals($row['quantity']); ?></td>
        <td><?php echo $row['item_discount']; ?></td>
        <td><?php echo $price; ?></td>
        <td><?php echo ($row['sale_status'] == 0) ? "Active" : "Cancelled"; ?></td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
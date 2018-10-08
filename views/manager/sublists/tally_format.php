<table id="report_list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>Sale ID</th>
      <th>Sale Time</th>
      <th>Customer Name</th>
      <th>Invoice Number</th>
      <th>Shop ID</th>
      <th>Item Name</th>
      <th>Taxable Value</th>
      <th>CGST %</th>
      <th>CGST Amt.</th>
      <th>SGST %</th>
      <th>SGST Amt.</th>
      <th>IGST %</th>
      <th>IGST Amt.</th>
      <th>Quantity</th>
      <th>Gross Value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($report_results as $row): 
      $discounted_price = $row['item_price'] - bcmul($row['item_price'], bcdiv($row['item_discount'], 100));
      $tax_data = $this->Item_taxes->get_sales_tax($row['sale_id'], $row['item_id']);

      $total_tax = 18.00;
      // (empty($tax_data['tax_percents']['CGST'])) ? 0.00 : $tax_data['tax_percents']['CGST'] + (empty($tax_data['tax_percents']['SGST'])) ? 0.00 : $tax_data['tax_percents']['SGST'] + (empty($tax_data['tax_percents']['IGST'])) ? 0.00 : $tax_data['tax_percents']['IGST'];

      $price = bcmul($discounted_price, $row['quantity']);
      $a = $price * $total_tax;
      $b = 100 + $total_tax;
      $taxable_value = $a / $b;

      ?>
      <tr>
        <td><?php echo $row['sale_id']; ?></td>
        <td><?php echo $row['sale_time']; ?></td>
        <td><?php echo $row['customer_id']; ?></td>
        <td><?php echo $row['tally_number']; ?></td>
        <td><?php echo $row['employee_id']; ?></td>
        <td><?php echo $row['item_id']; ?></td>
        <td><?php echo $price - round($taxable_value, 2); ?></td>
        <td><?php echo (empty($tax_data['tax_percents']['CGST'])) ? NULL : $tax_data['tax_percents']['CGST']; ?></td>
        <td><?php echo (empty($tax_data['tax_amounts']['CGST'])) ? NULL : $tax_data['tax_amounts']['CGST']; ?></td>
        <td><?php echo (empty($tax_data['tax_percents']['SGST'])) ? NULL : $tax_data['tax_percents']['SGST']; ?></td>
        <td><?php echo (empty($tax_data['tax_amounts']['SGST'])) ? NULL : $tax_data['tax_amounts']['SGST']; ?></td>
        <td><?php echo (empty($tax_data['tax_percents']['IGST'])) ? NULL : $tax_data['tax_percents']['IGST']; ?></td>
        <td><?php echo (empty($tax_data['tax_amounts']['IGST'])) ? NULL : $tax_data['tax_amounts']['IGST']; ?></td>
        <td><?php echo to_quantity_decimals($row['quantity']); ?></td>
        <td><?php echo $price; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<table id="report_list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Time</th>
      <th>Customer Name</th>
       <th>Tally Number</th>
       <th>Invoice Number</th>
      <th> Total Amount</th>
      <th>Payment Mode</th>
      <th>Sale Type</th>
      <th>Bill Type</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($report as $row): ?>
  
      <tr>
        <td><?php echo $row['sale_id']; ?></td>
        <td><?php echo $row['sale_time']; ?></td>
        <td><?php echo $row['customer_name']; ?></td>
        <td><?php echo $row['tally_number']; ?></td>
        <td><?php echo $row['invoice_number']; ?></td>
        <td><?php echo $row['amount_tendered']; ?></td>
        <td><?php echo $row['payment_type']; ?></td>
        <td><?php echo ($row['sale_type'] == 1) ? "Invoice" : "Credit Note"; ?></td>
        <td><?php echo ($row['bill_type'] == 'ys') ? "Special Approval" : ucfirst($row['bill_type']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
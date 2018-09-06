<table id="report_list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>Sale ID</th>
      <th>Sale Time</th>
      <!-- <th>Customer Name</th> -->
      <th>Shop</th>
      <th>Invoice#</th>
      <th>Barcode</th>
      <th>Price</th>
      <!-- <th>Discount</th> -->
      <th>Quantity</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($report_results as $row): 
      $price = $row['item_unit_price'] - bcmul($row['item_unit_price'], bcdiv($row['discount_percent'], 100)) ;
      ?>
      <tr>
        <td><?php echo $row['sale_id']; ?></td>
        <td><?php echo date('d-m-Y', strtotime($row['sale_time'])); ?></td>
        <!-- <td><?php //echo $row['customer_id']; ?></td> -->
        <td><?php echo $this->Stock_location->get_location_name($row['item_location']); ?></td>
        <td><?php echo $row['invoice_number']; ?></td>
        <td><?php echo $this->Item->get_info($row['item_id'])->item_number; ?></td>
        <td><?php echo $price ; ?></td>
        <!-- <td><?php //echo to_quantity_decimals($row['discount_percent']).'%'; ?></td> -->
        <td><?php echo to_quantity_decimals($row['quantity_purchased']); ?></td>
        <td><?php echo bcmul($price, $row['quantity_purchased']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<table id="list" class="display nowrap" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>SubCategory</th>
      <th>Brand</th>
      <th>Size</th>
      <th>Color</th>
      <th>Model</th>
      <th>MRP</th>
      <th>HSN</th>
      <th>Disc % (Retail)</th>
      <th>Disc % (Wholesale)</th>
      <th>Disc % (Franchise)</th>
      <th>FP (Retail)</th>
      <th>FP (Wholesale)</th>
      <th>FP (Franchise)</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $item): ?>
    <tr style="text-align: center;">
      <td><?php echo $item['item_id']; ?></td>
      <td><?php echo $item['item_number']; ?></td>
      <td><?php echo $item['name']; ?></td>
      <td><?php echo $item['category']; ?></td>
      <td><?php echo $item['subcategory']; ?></td>
      <td><?php echo $item['brand']; ?></td>
      <td><?php echo $item['custom2']; ?></td>
      <td><?php echo $item['custom3']; ?></td>
      <td><?php echo $item['custom4']; ?></td>
      <td><?php echo $item['unit_price']; ?></td>
      <td><?php echo $item['custom1']; ?></td>
      <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->retail); ?></td>
      <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->wholesale); ?></td>
      <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->franchise); ?></td>
      <td><?php echo to_currency(json_decode($item['cost_price'])->retail); ?></td>
      <td><?php echo to_currency(json_decode($item['cost_price'])->wholesale); ?></td>
      <td><?php echo to_currency(json_decode($item['cost_price'])->franchise); ?></td>
      <td><?php echo $item['quantity']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
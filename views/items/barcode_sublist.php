<table id="list" class="display" style="width:100%; font-size: 0.8em">
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>HSN</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>SubCategory</th>
      <th>Brand</th>
      <th>Size</th>
      <th>Color</th>
      <th>Model</th>
      <th>MRP</th>
      <th>CGST %</th>
      <th>SGST %</th>
      <th>IGST %</th>
      <th>Retail Discount</th>
      <th>Retail FP</th>
      <th>MH Qty</th>
      <th>BK Qty</th>
      <th>IP Qty</th>
      <th>AP Qty</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $item): 
    $taxes = $this->Item_taxes->get_specific_tax($item['item_id']);
    $multiqty = $this->Item_quantity->get_multilocation_quantity($item['item_id']);
  ?>
    <tr>
      <td><?php echo $item['item_id']; ?></td>
      <td><?php echo $item['item_number']; ?></td>
      <td><?php echo $item['custom1']; ?></td>
      <td><?php echo $item['name']; ?></td>
      <td><?php echo $item['category']; ?></td>
      <td><?php echo $item['subcategory']; ?></td>
      <td><?php echo $item['brand']; ?></td>
      <td><?php echo $item['custom2']; ?></td>
      <td><?php echo $item['custom3']; ?></td>
      <td><?php echo $item['custom4']; ?></td>
      <td><?php echo $item['unit_price']; ?></td>
      <td><?php echo $taxes['CGST']; ?></td>
      <td><?php echo $taxes['SGST']; ?></td>
      <td><?php echo $taxes['IGST']; ?></td>
      <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->retail); ?></td>
      <td><?php echo to_currency(json_decode($item['cost_price'])->retail); ?></td>
      <td><?php echo $multiqty['11']; ?></td>
      <td><?php echo $multiqty['7']; ?></td>
      <td><?php echo $multiqty['6']; ?></td>
      <td><?php echo $multiqty['8']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
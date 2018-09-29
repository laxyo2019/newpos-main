<table id="list" class="display nowrap" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Item Name</th>

      <?php //if($options['mci'] == 1){ ?>
        <th>Category</th>
        <th>SubCategory</th>
        <th>Brand</th>
        <th>Size</th>
        <th>Color</th>
      <?php //} ?>

      <th>Model</th>
      <th>MRP</th>

      <?php //if($options['taxes'] == 1){ ?>
        <th>HSN</th>
        <th>CGST %</th>
        <th>SGST %</th>
        <th>IGST %</th>
      <?php //} ?>

      <?php //if($options['discounted'] == 1){ ?>  
        <th>Disc % (Retail)</th>
        <th>Disc % (Wholesale)</th>
        <th>Disc % (Franchise)</th>
        <th>Disc % (Special)</th>
      <?php //} ?>

      <?php //if($options['fixed'] == 1){ ?>  
        <th>FP (Retail)</th>
        <th>FP (Wholesale)</th>
        <th>FP (Franchise)</th>
        <th>FP (Special)</th>
      <?php //} ?>

      <?php foreach ($locations as $row)
      {
        echo '<th>Qty('.$this->Stock_location->get_location_name($row).')</th>';
      }
      ?>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $item): 
    $taxes = $this->Item_taxes->get_specific_tax($item['item_id']);
    $multiqty = $this->Item_quantity->get_multilocation_quantity($item['item_id']);
  ?>
    <tr style="text-align: center;">
      <td><?php echo $item['item_id']; ?></td>
      <td><?php echo $item['item_number']; ?></td>
      <td><?php echo $item['name']; ?></td>

      <?php //if($options['mci'] == 1){ ?>
        <td><?php echo $item['category']; ?></td>
        <td><?php echo $item['subcategory']; ?></td>
        <td><?php echo $item['brand']; ?></td>
        <td><?php echo $item['custom2']; ?></td>
        <td><?php echo $item['custom3']; ?></td>
      <?php //} ?>

      <td><?php echo $item['custom4']; ?></td>
      <td><?php echo $item['unit_price']; ?></td>

      <?php //if($options['taxes'] == 1){ ?>
        <td><?php echo $item['custom1']; ?></td>
        <td><?php echo $taxes['CGST']; ?></td>
        <td><?php echo $taxes['SGST']; ?></td>
        <td><?php echo $taxes['IGST']; ?></td>
      <?php //} ?>

      <?php //if($options['discounted'] == 1){ ?>
        <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->retail); ?></td>
        <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->wholesale); ?></td>
        <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->franchise); ?></td>
        <td><?php echo to_quantity_decimals(json_decode($item['discounts'])->ys); ?></td>
      <?php //} ?>

      <?php //if($options['fixed'] == 1){ ?>
        <td><?php echo to_currency(json_decode($item['cost_price'])->retail); ?></td>
        <td><?php echo to_currency(json_decode($item['cost_price'])->wholesale); ?></td>
        <td><?php echo to_currency(json_decode($item['cost_price'])->franchise); ?></td>
        <td><?php echo to_currency(json_decode($item['cost_price'])->ys); ?></td>
      <?php //} ?>

      <?php 
        foreach ($locations as $row)
        {
          echo '<td>'.$multiqty[$row].'</td>';
        }
      ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
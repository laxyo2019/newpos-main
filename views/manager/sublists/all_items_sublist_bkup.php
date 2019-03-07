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
      <th>CGST</th>
      <th>SGST</th>
      <th>IGST</th>
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
  <?php 
  
  foreach ($items as $item): 
    $location_qty = $this->db->where(
      array(
        'item_id' => $item['item_id'],
        'location_id' => $location_id
      ))->get('item_quantities')->row()->quantity;

   $tax_info = $this->Item_taxes->get_specific_tax($item['item_id']); 


    $ds = json_decode($item['discounts']);
    $fp = json_decode($item['cost_price']);

    $ds_retail = (isset($ds->retail)) ? $ds->retail : "";
    $ds_wholesale = (isset($ds->wholesale)) ? $ds->wholesale : "";
    $ds_franchise = (isset($ds->franchise)) ? $ds->franchise : "";

    $fp_retail = (isset($fp->retail)) ? $fp->retail : "";
    $fp_wholesale = (isset($fp->wholesale)) ? $fp->wholesale : "";
    $fp_franchise = (isset($fp->franchise)) ? $fp->franchise : "";
    
  ?>
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
      <td><?php  echo $tax_info['cgst']; ?></td>
      <td><?php echo $tax_info['sgst']; ?></td>
      <td><?php echo $tax_info['igst']; ?></td>
      <td><?php echo round($ds_retail); ?></td>
      <td><?php echo round($ds_wholesale); ?></td>
      <td><?php echo round($ds_franchise); ?></td>
      <td><?php echo round($fp_retail); ?></td>
      <td><?php echo round($fp_wholesale); ?></td>
      <td><?php echo round($fp_franchise); ?></td>
      <td><?php echo $location_qty; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
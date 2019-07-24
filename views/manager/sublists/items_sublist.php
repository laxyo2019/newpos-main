
<table id="list" class="display nowrap" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>SubCategory</th>
      <th>Brand</th>
      <th>Expiry Date</th>
      <th>Stock Edition</th>
      <th>Size</th>
      <th>Color</th>
      <th>Model</th>
      <th>MRP</th>
      <th>HSN</th>
      <th>CGST %</th>
      <th>SGST %</th>
      <th>IGST %</th>
      <th>Disc % (Retail)</th>
      <th>Disc % (Wholesale)</th>
      <th>Disc % (Franchise)</th>
      <th>Disc % (Special)</th>
      <th>FP (Retail)</th>
      <th>FP (Wholesale)</th>
      <th>FP (Franchise)</th>
      <th>FP (Special)</th>
     <?php  if($selected_location !='all') {?>
      <th>Quantity</th>
     <?php } else { ?>
      <th>AP_Quantity</th>
      <th>BT_Quantity</th>
      <th>IP_Quantity</th>
      <th>KDR_Quantity</th>
      <th>LH_Quantity</th>
      <th>MH_Quantity</th>
      <th>SHOP114_Quantity</th>
     <?php } ?>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($itemss as $item): 
  //echo "<pre>";  print_r($item); die;
  $loc = '{'.$item['All_locations'].'}';
    $al= json_decode($loc);
      $al_8 = (isset($al->loc_8)) ? $al->loc_8: 0;
      $al_16 = (isset($al->loc_16)) ? $al->loc_16: 0;
      $al_6 = (isset($al->loc_6)) ? $al->loc_6: 0;
      $al_21 = (isset($al->loc_21)) ? $al->loc_21: 0;
      $al_4 = (isset($al->loc_4)) ? $al->loc_4: 0;
      $al_11 = (isset($al->loc_11)) ? $al->loc_11: 0;
      $al_20 = (isset($al->loc_20)) ? $al->loc_20: 0;
      $taxes = $this->Item_taxes->get_specific_tax($item['item_id']);
  ?>
    <tr style="text-align: center;">
      <td><?php echo $item['item_id']; ?></td>
      <td><?php echo $item['item_number']; ?></td>
      <td><?php echo $item['name']; ?></td>
      <td><?php echo $item['category']; ?></td>
      <td><?php echo $item['subcategory']; ?></td>
      <td><?php echo $item['brand']; ?></td>
      <td><?php echo $item['custom5']; ?></td>
      <td><?php echo $item['custom6']; ?></td>
      <td><?php echo $item['custom2']; ?></td>
      <td><?php echo $item['custom3']; ?></td>
      <td><?php echo $item['custom4']; ?></td>                
      <td><?php echo $item['unit_price']; ?></td>
      <td><?php echo $item['custom1']; ?></td>
      <td><?php echo isset($taxes['CGST']) ? $taxes['CGST'] : '0.0' ; ?></td>
      <td><?php echo isset($taxes['SGST']) ? $taxes['SGST'] : '0.0' ; ?></td>
      <td><?php echo isset($taxes['IGST']) ? $taxes['IGST'] : '0.0' ;  ?></td>
      <td><?php echo isset(json_decode($item['discounts'])->retail) ? to_quantity_decimals(json_decode($item['discounts'])->retail) : '0'; ?></td>
      <td><?php echo isset(json_decode($item['discounts'])->wholesale) ? to_quantity_decimals(json_decode($item['discounts'])->wholesale) : '0'; ?></td>
      <td><?php echo isset(json_decode($item['discounts'])->franchise) ? to_quantity_decimals(json_decode($item['discounts'])->franchise) : '0'; ?></td>
      <td><?php echo isset(json_decode($item['discounts'])->ys) ? to_quantity_decimals(json_decode($item['discounts'])->ys) : '0'; ?></td>
      
      <td><?php  echo isset(json_decode($item['cost_price'])->retail) ? to_currency(json_decode($item['cost_price'])->retail) : '0';
       ?></td>
       <td><?php  echo isset(json_decode($item['cost_price'])->wholesale) ? to_currency(json_decode($item['cost_price'])->wholesale) : '0';
       ?></td>
       <td><?php  echo isset(json_decode($item['cost_price'])->franchise) ? to_currency(json_decode($item['cost_price'])->franchise) : '0';
       ?></td>
       <td><?php  echo isset(json_decode($item['cost_price'])->ys) ? to_currency(json_decode($item['cost_price'])->ys) : '0';
       ?></td>
      <?php  if($selected_location !='all') {?>
      <td><?php echo $item['quantity']; ?></td>
      <?php }else { ?>
          <td><?php echo isset($al->loc_8) ? $al->loc_8 :'0'; ?></td>
          <td><?php echo isset($al->loc_16) ? $al->loc_16 :'0'; ?></td>
          <td><?php echo isset($al->loc_6) ? $al->loc_6 :'0'; ?></td>
          <td><?php echo isset($al->loc_21) ? $al->loc_21 :'0'; ?></td>
          <td><?php echo isset($al->loc_4) ? $al->loc_4 :'0'; ?></td>
          <td><?php echo isset($al->loc_11) ? $al->loc_11 :'0'; ?></td>
          <td><?php echo isset($al->loc_20) ? $al->loc_20 :'0'; ?></td>
      <?php } ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table> 

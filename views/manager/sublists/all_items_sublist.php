<?php
$CGST=$IGST=$SGST="";
$custom_attributes = $this->Appconfig->get_additional_ten_col_name();
?>

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
      <?php
        foreach($custom_attributes as $custom_attribute){
          echo "<th>".$custom_attribute->value."</th>";
        }
      ?>
    </tr>
  </thead>
  <tbody>
  
  <?php foreach ($items as $item): 
  // $tax_info = $this->Item_taxes->get_specific_tax($item->item_id); 
    $ds = json_decode($item->discounts);
    $fp = json_decode($item->cost_price);

    $tax_array= explode(',',$item->percent);
   // print_R($tax_array);die;
   if($tax_array[0]){
    $CGST= $tax_array[0];
   }
   if($tax_array[1]){
    $IGST= $tax_array[1];
   }
   if($tax_array[2]){
    $SGST= $tax_array[2];
   }
    $ds_retail = (isset($ds->retail)) ? $ds->retail : "";
    $ds_wholesale = (isset($ds->wholesale)) ? $ds->wholesale : "";
    $ds_franchise = (isset($ds->franchise)) ? $ds->franchise : "";

    $fp_retail = (isset($fp->retail)) ? $fp->retail : "";
    $fp_wholesale = (isset($fp->wholesale)) ? $fp->wholesale : "";
    $fp_franchise = (isset($fp->franchise)) ? $fp->franchise : "";
    
  ?>
    <tr >
      <td><?php echo $item->item_id; ?></td>
      <td><?php echo $item->item_number; ?></td>
      <td><?php echo $item->name; ?></td>
      <td><?php echo $item->category; ?></td>
      <td><?php echo $item->subcategory; ?></td>
      <td><?php echo $item->brand; ?></td>
      <td><?php echo $item->custom2; ?></td>
      <td><?php echo $item->custom3; ?></td>
      <td><?php echo $item->custom4; ?></td>
      <td><?php echo $item->unit_price; ?></td>
      <td><?php echo $item->custom1; ?></td>
      <td><?php  echo $CGST ?></td>
      <td><?php echo $SGST; ?></td>
      <td><?php echo $IGST; ?></td>
      <td><?php echo round($ds_retail); ?></td>
      <td><?php echo round($ds_wholesale); ?></td>
      <td><?php echo round($ds_franchise); ?></td>
      <td><?php echo round($fp_retail); ?></td>
      <td><?php echo round($fp_wholesale); ?></td>
      <td><?php echo round($fp_franchise); ?></td>
      <td><?php echo $item->quantity; ?></td>
      <td><?php echo $item->column10; ?></td>
      <td><?php echo $item->column1; ?></td>
      <td><?php echo $item->column2; ?></td>
      <td><?php echo $item->column3; ?></td>
      <td><?php echo $item->column4; ?></td>
      <td><?php echo $item->column5; ?></td>
      <td><?php echo $item->column6; ?></td>
      <td><?php echo $item->column7; ?></td>
      <td><?php echo $item->column8; ?></td>
      <td><?php echo $item->column9; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
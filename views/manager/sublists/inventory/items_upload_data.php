<style>
.mar_r_7{
  margin-right:7px;
}
.padding-5{
  padding: 5px;
}
</style>
<div class='' style='position:relative;'>
<?php
if(isset($sheets[0])){ ?>
<div class='col-sm-12 bg-info'  style='color: white;padding: 10px;margin-bottom: 20px;font-size: 15px;'> Sheet Name : <?php echo $sheets[0]->sheet_name;?></div>
<?php }
?>
<div class='clearfix' style='margin-bottom:50px;min-height: 61px;'></div>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Temporary Barcode</th>
      <th>Name</th>
      <th>HSN</th>
      <th>Category</th>
      <th>Subcategory</th>
      <th>Brand</th>
      <th>Price</th>
      <th>IGST</th>
      <th>Retail Discount</th>
      <th>Whole Discount</th>
      <th>Franchise Discount</th>
      <th>YS Discount</th>
      <th>Retail FP</th>
      <th>Whole FP</th>
      <th>Franchise FP</th>
      <th>Damaged FP</th>
      <th>Expiry Date</th>
      <th>Stock Edition</th>
      <th>Model</th>
      <th>COlor</th>
      <th>Size</th>
      <th>Description</th>
      <th>Reorder Level</th>
      <th>Location ID</th>
      <th>Location Qty</th>
    </tr>
  </thead>
  <tbody>
  
	  <?php 
	  $error_count = 0;
	  $class='';
	  foreach ($sheets as $sheet):
	    $class='';
	    if($sheets[0]->sheet_status!=='approved'){
	      if((!(strlen($sheet->barcode)==8 || strlen($sheet->barcode)==9)) || $sheet->retail_discount>100.00 || $sheet->wholesale_discount>100.00 || $sheet->franchise_discount>100.00 || $sheet->ys_discount>100.00 || (!preg_match("/^[0-9]+$/",trim($sheet->hsn)))|| $sheet->retail_fp<0 || $sheet->wholesale_fp<0 || $sheet->franchise_fp<0 || $sheet->damaged_fp<0 || (!in_array($sheet->igst,array(0.00,5.00,12.00,18.00,28.00)))){
	          $error_count++;
	          $class= 'text-danger';
	      }
	    }
	    
	  ?>
	    <tr>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->id; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->barcode; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->name; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->hsn; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->category; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->subcategory; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->brand; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->price; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->igst; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->retail_discount; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->wholesale_discount; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->franchise_discount; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->ys_discount; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->retail_fp; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->wholesale_fp; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->franchise_fp; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->damaged_fp; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->expiry_date; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->stock_edition; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->model; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->color; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->size; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->item_description; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->reorder_level; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->location_id; ?></td>
	      <td class='<?php echo $class; ?>'><?php echo $sheet->location_qty; ?></td>
	    </tr>
	  <?php endforeach; ?>
	</tbody>
</table>

<div class='col-sm-12' style='position: absolute;top: 11px;right: 0;text-align: right;color: #fff;'><?php echo $error_count;?> Out of <?php echo count($sheets);?> entries is inccorect
</div>
</div>
<?php
if(isset($sheets[0])){ ?>
<div class='pull-right <?php if($sheets[0]->sheet_status != 'pending'){echo 'hide';}?>'>
<?php }


?>

<div style="margin:35px 0;   top: 95px;position: absolute;right: 16px;">
<?php if($error_count==0 && isset($sheets[0])):?>

<button class='btn btn-xs padding-5 bg-warning pull-right' style="margin-left:10px;" onclick='sheet_action(<?php echo $sheet->id;?>,"approve");'><span class='fa fa-check-square-o mar_r_7'></span>Approve</button>
<?php endif;?>

<?php if($sheet->status == 'pending'){  ?>
<button class='btn btn-xs padding-5 bg-danger pull-right'  onclick='sheet_action(<?php echo $sheet->id;?>,"discard");'><span class='fa fa-times-circle mar_r_7'></span>Discard</button>
<?php } ?>
</div>
</div>
<script>
  $(document).ready( function () {
    $('table').DataTable({
        "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
    
  });

//Action Either Approve or Discarded
function sheet_action(sheet_id,action){

 
          $('.content').html("<img src='<?php echo base_url('images/loader.gif'); ?>' alt='Loading'>");
          if(action=='approve'){  //Execute if action is approve
            $.post('<?php echo site_url();?>/items/upload_sheet_data_items/'+sheet_id,{},function(){
              swal({
                title: "",
                text: 'Approved.',
                icon: "success",
              }).then(()=> {
                all_sheets('not_processed');
              });
            });
          } else if(action=='discard'){ //execute if action id discard
            $.post('<?php echo site_url();?>/items/discard_sheet_data_items/'+sheet_id,{},function(){
              swal({
                title: "",
                text: 'Discarded.',
                icon: "success",
              }).then(()=> {
                all_sheets('not_processed');
              });
            });
          }

    
  
}

</script>
<div class="row">
  <div class="col-md-4">
    <div class="form-group form-group-md">
      <?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control', 'id'=>'category')); ?>
    </div>
  </div>
  <!-- <div class="col-md-4">
    <div class="form-group form-group-md">
      <select name="subcategory" class="form-control" id="subcategory">
        <option value="">Loading...</option>
      </select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group form-group-md">
      <?php //echo form_dropdown('brands', $brands, 'test', array('class'=>'form-control', 'id'=>'brand')); ?>
    </div>
  </div> -->
  <div class="col-md-4">
    <button id="fetch_stock_up" class="btn btn-sm btn-default">Stock Ups</button>
  </div>

</div>

<div class="table_list">
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
</div>
<script type="text/javascript">
	$(document).ready( function () {
		$('#list').DataTable({
        dom: 'Bfrtip',
        buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#fetch_stock_up').on('click', function(){
      $.post('<?php echo site_url($controller_name."/fetch_stock_up") ?>', {'test': 'test'}, function(data) {
	        $('.table_list').html(data);
          $('#list').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
      });
    });

	// Filter items on change in category dropdown
		$("#category").on('change', function(){
      $('.table_list').html('<div align="center"><img height="320" src="<?php echo base_url();?>/images/loader.gif"></div>');      
      var mci_value = $('#category').val();
      $.post('<?php echo site_url($controller_name."/custom_items_filter");?>', {'mci_type': 'category', 'mci_value': mci_value}, function(data) {
        $('.table_list').html(data);
        $('#list').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
      });
		});

	});
</script>
<div class="row">
    <input type="hidden" value="<?php echo $warehouse_item_data->id; ?>" id="item_id1">
  <div class="form-group">
    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $warehouse_item_data->pointer_id; ?>" id="barcode1" placeholder="Edit Barcode">
  </div>
  <div class="form-group">
    <input type="text" class="form-control input-sm" value="<?php echo $warehouse_item_data->barcode; ?>" id="barcode1" placeholder="Edit Barcode">
  </div>
  <div class="form-group">
    <input type="text" class="form-control input-sm" value="<?php echo $warehouse_item_data->quantity; ?>" id="quantity1" placeholder="Edit Quantity">
  </div>
  <div class="form-group">
    <button class="btn btn-info" id="warehouse_submit1">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#warehouse_submit1').on('click', function(){
    var id = $('#item_id1').val();
    var barcode1 = $('#bacode1').val();
    var quantity1 = $('#quantity1').val();
    $.post('<?php echo site_url($controller_name."/save_warehouse_item"); ?>', {'id': id, 'barcode': barcode1, 'quantity': quantity1}, function(data) {
      $.post('<?php echo site_url($controller_name."/active_pointer_window"); ?>', {}, function(data) {
        $('#active_pointer_window').html(data);
      });
    });
  });
});
</script>
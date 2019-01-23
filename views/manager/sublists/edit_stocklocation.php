<?php foreach($editadd as $row): ?>

<div class="row">
    <input type="hidden" value="<?php echo $row['location_id']; ?>" id="location_id1">
  <div class="form-group">
    <h5><b>location Name</b></h5>
    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $row['location_name']; ?>">
  </div>
  <div class="form-group">
    <h5><b>Shop Incharge</b></h5>
    <input type="text" id="shop_incharge1" class="form-control input-sm" value="<?php echo $row['shop_incharge']; ?>" >
  </div>
  <div class="form-group">
    <h5><b>Alias</b></h5>
    <input type="text" class="form-control input-sm" value="<?php echo $row['alias']; ?>" id="alias1">
  </div>
   <div class="form-group">
    <h5><b>Address</b></h5>
    <textarea rows="5" cols="8" class="form-control input-sm" id="address1" value=""><?php echo strip_tags($row['address']); ?></textarea>
 </div>
  <div class="form-group">
    <h5><b>T&C</b></h5>
    <textarea rows="9" cols="14" class="form-control input-sm" id="tnc1" value=""><?php echo strip_tags($row['tnc']); ?></textarea>
  </div>
  <?php endforeach; ?>
  <div class="form-group">
    <button class="btn btn-info" id="save">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#save').on('click', function(){
    var location_id = $('#location_id1').val();
    var shop_incharge1 = $('#shop_incharge1').val();
    var alias1 = $('#alias1').val();
    var address1 = $('#address1').val();
    var tnc1 = $('#tnc1').val();
    $.post('<?php echo site_url($controller_name."/save_stocklocation"); ?>', {'location_id': location_id, 
      'shop_incharge': shop_incharge1, 'alias': alias1, 'address': address1, 'tnc':tnc1}, function(data) {
    });
  });
});
</script>

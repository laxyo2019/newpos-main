<div class="row">
  <input type="hidden" value="<?php echo $bogo_data->id; ?>" id="bg_id1">
  <div class="form-group">
    <input type="number" class="form-control" value="<?php echo $bogo_data->bogo_fp; ?>" id="bg_fp1" placeholder="Enter new amount">
  </div>
  <div class="form-group">
    <input type="number" class="form-control" value="<?php echo $bogo_data->bogo_val; ?>" id="bg_value1" placeholder="Enter new amount">
  </div>
  <div class="form-group">
    <button class="btn btn-info" id="bogo_submit1">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#bogo_submit1').on('click', function(){
    var id = $('#bg_id1').val();
    var bogo_fp1 = $('#bg_fp1').val();
    var bogo_val1 = $('#bg_value1').val();

    $.post('<?php echo site_url($controller_name."/save_bogo"); ?>', {'id': id, 'bogo_fp': bogo_fp1, 'bogo_val': bogo_val1}, function(data) {
      $.post('<?php echo site_url($controller_name."/active_bogo_window"); ?>', {}, function(data) {
        $('#active_bogo_window').html(data);
      });
    });
  });
});
</script>
<div class="row">
  <input type="hidden" value="<?php echo $bogo_data->id; ?>" id="bg_id1">
  <div class="form-group">
    <select class="form-control" id="bg_count1">
      <option value="">New Item Count</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select>
  </div>
  <div class="form-group">
    <input type="number" class="form-control" value="<?php echo $bogo_data->bogo_val; ?>" id="bg_value1" placeholder="Enter new amount">
  </div>
  <div class="form-group">
    <button class="btn btn-info ajax_submit">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.ajax_submit').on('click', function(){
    var id = $('#bg_id1').val();
    var bogo_count = $('#bg_count1').val();
    var bogo_val = $('#bg_value1').val();

    $.post('<?php echo site_url($controller_name."/save_bogo"); ?>', {'id': id, 'bogo_count': bogo_count, 'bogo_val': bogo_val}, function(data) {
      $('.modal-dlg').toggle;
      $.post('<?php echo site_url($controller_name."/active_bogo_window"); ?>', {'id': id, 'bogo_count': bogo_count, 'bogo_val': bogo_val}, function(data) {
        $('#active_bogo_window').html(data);
      });
    });
  });
});
</script>
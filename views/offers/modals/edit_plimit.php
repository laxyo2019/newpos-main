<div class="row">
    <input type="hidden" value="<?php echo $plimit_data->id; ?>" id="plimit_id">
  <div class="form-group">
    <input type="number" class="form-control" value="<?php echo $limit_data->quantity; ?>" id="plimit_val" placeholder="Enter new limit">
  </div>
  <div class="form-group">
    <button class="btn btn-info ajax_submit">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.ajax_submit').on('click', function(){
    var id = $('#plimit_id').val();
    var quantity = $('#plimit_val').val();

    $.post('<?php echo site_url($controller_name."/save_plimit"); ?>', {'id': id, 'quantity': quantity}, function(data) {
      $('.modal-dlg').toggle;
      $.post('<?php echo site_url($controller_name."/active_plimit_window"); ?>', {'id': id, 'quantity': quantity}, function(data) {
        $('#active_plimit_window').html(data);
      });
    });
  });
});
</script>
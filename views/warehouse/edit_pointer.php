<div class="row">
  <input type="hidden" value="<?php echo $pointer_data->id; ?>" id="pt_id1">
  <div class="form-group">
    <input type="text" class="form-control input-sm" value="<?php echo $pointer_data->pointer; ?>" id="pointer1" placeholder="Add new pointer">
  </div>
  
  <div class="form-group">
    <button class="btn btn-info" id="pointer_submit1">Submit</button>
  </div>
</div>

<script>
$(document).ready(function(){
  $('#pointer_submit1').on('click', function(){
    var id = $('#pt_id1').val();
    var pointer1 = $('#pointer1').val();
   
    $.post('<?php echo site_url($controller_name."/save_pointer"); ?>', {'id': id, 'pointer': pointer1}, function(data) {
      $.post('<?php echo site_url($controller_name."/active_pointer_window"); ?>', {}, function(data) {
        $('#active_pointer_window').html(data);
      });
    });
  });
});
</script>
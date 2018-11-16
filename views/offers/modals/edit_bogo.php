<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="form-group">
      <label class="checkbox-inline">
        <input id="delete_bogo" type="checkbox" value="1">Delete
      </label>
    </div>
    <div class="form-group">
      <input type="hidden" value="<?php echo $bogo_data->id; ?>" id="bogo_id">
      <select id="bg_count1" class="form-control">
        <option value="">Item Count</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>
    <div class="form-group">
      <input type="number" class="form-control" value="<?php echo $bogo_data->bogo_val; ?>" id="bg_value1">
    </div>
    <button id="edit_bogo" type="submit" class="btn btn-success">Submit</button>
  </div>  
</div>

<script>
	$(document).ready( function () {
    $('#edit_bogo').on('click', function(){
      var id = $('#bogo_id').val();
      var insert_data = {
        deleted: $('#delete_bogo:checked').val(),
        bogo_count: $('#bg_count1').val(),
        bogo_val: $('#bg_value1').val()
      };

			$.post('<?php echo site_url($controller_name."/save_bogo"); ?>', {'id': id, 'insert_data': insert_data}, function(data) {
        $.post('<?php echo site_url($controller_name."/active_bogo_window"); ?>', {}, function(data) {
          $('#active_bogo_window').html(data);
          $('.modal-dlg').modal('hide');
			  });
			});
		});

	});
</script>
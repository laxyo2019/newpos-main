<div class="row">
  <h3 class="text-center">Create Limit</h3>

  <div class="col-md-12">
    <div class="form-inline">
      <div class="form-group">
        <select id="plimit_category">
          <option value="">Select Category</option>
          <?php
            foreach($mci_data['categories'] as $row)
            {
              echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <input type="number" id="plimit_count">
      </div>
      <button id="create_plimit" type="submit" class="btn btn-sm btn-success">Submit</button>
    </div>
  </div>

</div>
<hr>

<div id="active_plimit_window">
  <div class="col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Active Limit</h3>
      </div>

      <?php foreach($this->db->get('purchase_limiter')->result_array() as $row){ ?>
        <div class="panel-body">
          <a href="<?php echo site_url($controller_name."/edit_plimit/".$row['id']); ?>" title="Edit" class="modal-dlg"><?php echo $row['mci_value'].' | '.$row['quantity'];?></a>
          
          <span class="pull-right" id="<?php echo $row['id']; ?>">
            <style>
              .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
              .toggle.ios .toggle-handle { border-radius: 20px; }
            </style>
            <input type="checkbox" class="plimit_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
          </span>
        </div>
      <?php } ?> 
    </div>
  </div>
</div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('.plimit_toggle').bootstrapToggle();

    $('#create_plimit').on('click', function(){
      var mci_value = $('#plimit_category').val();
      var quantity = $('#plimit_count').val();

			$.post('<?php echo site_url($controller_name."/save_plimit"); ?>', {'mci_value': mci_value, 'quantity': quantity}, function(data) {
        $.post('<?php echo site_url($controller_name."/active_plimit_window"); ?>', {}, function(data) {
          $('#active_plimit_window').html(data);
			  });
        
			});
		});

    $('.plimit_toggle').on('change', function(){
      var id = $(this).closest('span').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/plimit_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });

	});
</script>
<div class="col-md-6 col-md-offset-3">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Active Limits</h3>
    </div>

    <?php foreach($this->db->get('purchase_limiter')->result_array() as $row){ ?>
      <div class="panel-body">
        <a href="<?php echo site_url($controller_name."/edit_plimit/".$row['id']); ?>" title="Edit" class="modal-dlg"><?php echo $row['mci_value'].' | '.$row['quantity'];?></span></a>

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

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('.plimit_toggle').bootstrapToggle();

    $('.plimit_toggle').on('change', function(){
      var id = $(this).closest('span').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/plimit_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });
	});
</script>
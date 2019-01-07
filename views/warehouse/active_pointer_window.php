<div class="col-md-6 col-md-offset-3">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Active pointer</h3>
    </div>

    <?php foreach($this->db->get('warehouse')->result_array() as $row){ ?>
      <div class="panel-body">
        <a href="<?php echo site_url($controller_name."/edit_pointer/".$row['id']); ?>" title="Edit" class="modal-dlg"><?php echo $row['pointer'];?></a>

       
            <span class="pull-right" id="<?php echo $row['id']; ?>">
             <a class="modal-dlg-wide" title="See List" href="<?php echo site_url($controller_name."/get_warehouse_item/".$row['id']); ?>">
          <span class="glyphicon glyphicon-eye-open"></span></a>
          <a title="Delete">
              <span id="<?php echo $row['id']; ?>" class="glyphicon glyphicon-trash delete-basic" style="cursor:pointer"></span>
              </a>
      </div>
    <?php } ?> 
  </div>
</div>  

 <script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('.bogo_toggle').bootstrapToggle();

    $('.bogo_toggle').on('change', function(){
      var id = $(this).closest('span').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/bogo_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });
	});
</script>
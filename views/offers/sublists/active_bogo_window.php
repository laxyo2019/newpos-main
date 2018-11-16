<div class="col-md-6 col-md-offset-3">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Active Plans</h3>
    </div>

    <?php foreach($this->db->where('deleted', 0)->get('special_bogo')->result_array() as $row){ ?>
      <div class="panel-body">
        <?php echo $row['category'].' | '.$row['subcategory'].' | '.$row['brand'].' | '.$row['bogo_count'].' | '.$row['bogo_val'];?>
        <div class="pull-right">
          <a href='<?php echo site_url($controller_name."/edit_bogo/".$row['id']); ?>' title='Edit BOGO' class='modal-dlg'><span class="glyphicon glyphicon-edit"></span></a>
        </div>
      </div>
    <?php } ?> 
  </div>
</div>  

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");
	});
</script>
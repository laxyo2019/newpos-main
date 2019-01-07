<?php $this->load->view("partial/header"); ?>

<div class="row">
  <h3 class="text-center">Add Pointer</h3>

  <div class="col-md-12">
    <div class="form-inline">
      <div class="form-group">
        <input type="text" placeholder="Add Pointer" id="pointer" class="form-control input-sm">
      </div>
      
      <button id="pointer_submit" type="submit" class="btn btn-sm btn-success">Submit</button>
    </div>
  </div>

</div>
<hr>

<div id="active_pointer_window">
  <div class="col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Pointer List</h3>
      </div>

      <?php foreach($this->db->get('warehouse')->result_array() as $row){ ?>
        <div class="panel-body">
          <a href="<?php echo site_url($controller_name."/edit_pointer/".$row['id']); ?>" title="Edit" class="modal-dlg"><?php echo $row['pointer']; ?></a>

          <span class="pull-right">
             <a class="modal-dlg-wide" title="See List" href="<?php echo site_url($controller_name."/get_warehouse_item/".$row['id']); ?>">
              <span class="glyphicon glyphicon-eye-open"></span>
              </a>
            <a title="Delete">
              <span id="<?php echo $row['id']; ?>" class="glyphicon glyphicon-trash delete-basic" style="cursor:pointer"></span>
              </a>
          </span>
        </div>
      <?php } ?> 
    </div>
  </div>
</div>
<?php $this->load->view("partial/footer"); ?>
<script>
  $(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('#pointer_submit').on('click', function(){

      var pointer = $('#pointer').val();
      $.post('<?php echo site_url($controller_name."/save_pointer"); ?>', {
          'pointer': pointer,
        }, function(data) {
        $.post('<?php echo site_url($controller_name."/active_pointer_window"); ?>', {}, function(data) {
          $('#active_pointer_window').html(data);
        });
        
      });
    });

    $('.delete-basic').on('click', function(){
      if(confirm('Are you sure, you wish to delete this pointer?')){
        var id = this.id;
        var that = this;
        $.post('<?php echo site_url($controller_name."/delete_pointer"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('div').fadeOut();
        });
      }
    });

  });
</script>
 

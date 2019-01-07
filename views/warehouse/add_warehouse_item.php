<div class="col-md-12">
  <div class="form-group">
    <input type="text" placeholder="Add Barcode" id="barcode" class="form-control input-sm">
      </div>
      <div class="form-group">
    <input type="text" placeholder="Add Quantity" id="quantity" class="form-control input-sm">
      </div>
      <div class="form-group">

    <button class="btn btn-info" id="item_submit">Submit</button>
  </div>
    </div>
    <script>
  $(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('#item_submit').on('click', function(){

      var barcode= $('#barcode').val();
      var quantity= $('#quantity').val();
      $.post('<?php echo site_url($controller_name."/save_warehouse_item"); ?>', {
           'barcode': barcode, 'quantity':quantity,
        }, function(data) {
        $.post('<?php echo site_url($controller_name."/active_pointer_window"); ?>', {}, function(data) {
          $('#active_pointer_window').html(data);
        });
        
      });
    });

    /*$('.delete-basic').on('click', function(){
      if(confirm('Are you sure, you wish to delete this pointer?')){
        var id = this.id;
        var that = this;
        $.post('<?php echo site_url($controller_name."/delete_pointer"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('div').fadeOut();
        });
      }
    });*/

  });
</script>
 
  
<?php $this->load->view("partial/header"); ?>
<button class='btn btn-info btn-sm modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
  title='Excel Import'>Excel Import</button>
   
<button class='btn btn-info btn-sm modal-dlg' data-href='<?php echo site_url($controller_name."/add_warehouse_item/"); ?>'
  title='Add Warehouse Item' id>Add New </button> 


  <table id="warehouse_list" class="display" style="width: 100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Pointer ID</th>
        <th>Barcode</th>
        <th>Quantity</th>
        <th>Delete</th>
        <th>Edit</th>
      </tr>
    </thead>
     <tbody>
    <?php foreach ($warehouse_list as $row):
      //$customer_info = $this->Customer->get_info($row['customer_id']);
      ?>
      <tr id="<?php echo $row['id']; ?>">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['pointer_id']; ?></td>
        <td><?php echo $row['barcode']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><span style="cursor:pointer" class="glyphicon glyphicon-trash delete-basic"></span></td>
        <td>
        <a class="modal-dlg-wide" title="Edit" href="<?php echo site_url($controller_name."/edit_warehouse_item/".$row['id']); ?>"><span class="glyphicon glyphicon-pencil"></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

<?php $this->load->view("partial/footer"); ?>
<script>
  $(document).ready( function () {

    $('#warehouse_list').DataTable({
      "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
    $('.delete-basic').on('click', function(){
      if(confirm('Are you sure, you wish to delete this offer?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/delete_warehouse_item"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });
    
    dialog_support.init("a.modal-dlg-wide, button.modal-dlg");
    // $('.voucher_toggle').bootstrapToggle();

    // $('.voucher_toggle').on('change', function(){
    //   var id = $(this).closest('tr').attr('id');
    //   var status = $(this).prop('checked');
    //   $.post('<?php //echo site_url($controller_name."/voucher_toggle"); ?>', {'id': id, 'status': status}, function(data) {
		// 		console.log(data);
    //   });
    // });

    // $('.delete-voucher').on('click', function(){
    //   if(confirm('Are you sure, you wish to delete this offer?')){
    //     var id = $(this).closest('tr').attr('id');
    //     var that = this;
    //     $.post('<?php //echo site_url($controller_name."/delete_voucher"); ?>', {'id': id}, function(data) {
    //       alert(data);
    //       $(that).closest('tr').fadeOut();
    //     });
    //   }
    // });
  });
</script>
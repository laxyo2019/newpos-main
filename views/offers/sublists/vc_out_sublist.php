<table id="vc_out_list" class="display" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Customer Name</th>
      <th>Phone Number</th>
      <th>Generate Sale ID</th>
      <th>Redeem Sale ID</th>
      <th>Create Time</th>
      <th>Redeem Time</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($all_vc_out_list as $row):
    $customer_info = $this->Customer->get_info($row['customer_id']);
    ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $customer_info->first_name." ".$customer_info->last_name; ?></td>
      <td><?php echo $row['phone']; ?></td>
      <td><?php echo $row['generate_sale_id']; ?></td>
      <td><?php echo $row['redeem_sale_id']; ?></td>
      <td><?php echo $row['create_time']; ?></td>
      <td><?php echo $row['redeem_time']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {

    $('#vc_out_list').DataTable({
      "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
    
    //dialog_support.init("a.modal-dlg-wide");
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
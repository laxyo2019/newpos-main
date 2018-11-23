<div class="row">
  <table id="vc_list" class="cell-border">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Mobile</th>
        <th>Gen. Sale ID</th>
        <th>Redeem Sale ID</th>
        <th>Redeemed At</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($vc_list as $row):
      $customer_info = $this->Customer->get_info($row['customer_id']);
      ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $customer_info->first_name." ".$customer_info->last_name; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['generate_sale_id']; ?></td>
        <td><?php echo $row['redeem_sale_id']; ?></td>
        <td><?php echo $row['redeemed_at']; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  $(document).ready( function () {

    $('#vc_list').DataTable({
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
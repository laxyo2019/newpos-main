<table id="list" class="display" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Voucher ID</th>
      <th>Employee ID</th>
      <th>Customer ID</th>
      <th>Phone Number</th>
      <th>Create Time</th>
      <th>Redeem Time</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($special_vouchers as $row): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['voucher_id']; ?></td>
      <td><?php echo $row['emp_id']; ?></td>
      <td><?php echo $row['customer_id']; ?></td>
      <td><?php echo $row['phone']; ?></td>
      <td><?php echo $row['create_time']; ?></td>
      <td><?php echo $row['redeem_time']; ?></td>
      <td><?php echo $row['status']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    // dialog_support.init("a.modal-dlg-wide");
    // $('.offer_toggle').bootstrapToggle();

    // $('.offer_toggle').on('change', function(){
    //   var id = $(this).closest('tr').attr('id');
    //   var status = $(this).prop('checked');
    //   $.post('<?php //echo site_url($controller_name."/offer_toggle"); ?>', {'id': id, 'status': status}, function(data) {
		// 		console.log(data);
    //   });
    // });

    // $('.delete-basic').on('click', function(){
    //   if(confirm('Are you sure, you wish to delete this offer?')){
    //     var id = $(this).closest('tr').attr('id');
    //     var that = this;
    //     $.post('<?php //echo site_url($controller_name."/delete_basic"); ?>', {'id': id}, function(data) {
    //       alert(data);
    //       $(that).closest('tr').fadeOut();
    //     });
    //   }
    // });
    
  });
</script>
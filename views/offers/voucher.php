  <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url($controller_name."/create_voucher"); ?>'
            title='Create New Voucher'>
      Create
  </button>

<hr>
<div id="vouchers_table_area">
  <table id="voucher_list" class="display" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>VC Code</th>
        <th>VC Distributed</th>
        <th>VC Redeemed</th>
        <th>Show Details</th>
        <th>VC Value</th>
        <th>VC Threshold</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="text-center">
    <?php foreach ($this->Pricing->get_special_vouchers() as $row):
      //$customer_info = $this->Customer->get_info($row['customer_id']);
      ?>
      <tr id="<?php echo $row['id']; ?>">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['voucher_code']; ?></td>
        <td><button class='btn btn-info btn-xs modal-dlg-wide' data-href='<?php echo site_url($controller_name."/get_all_vc_out_sublist/".$row['id']); ?>'
					title='All Vouchers'><?php echo "See List"; ?>
	      </button></td>
        <td><button class='btn btn-info btn-xs modal-dlg-wide' data-href='<?php echo site_url($controller_name."/get_redeemed_vc_out_sublist/".$row['id']); ?>'
					title='Redeemed Vouchers'><?php echo "See List"; ?>
	      </button></td>
        <td><button class='btn btn-info btn-xs modal-dlg-wide' data-href='<?php echo site_url($controller_name."/get_vc_out_details/".$row['id']); ?>'
					title='Voucher Details'><?php echo "View Details"; ?>
	      </button></td>
        <td><?php echo to_currency($row['vc_val']); ?></td>
        <td><?php echo to_currency($row['vc_thres']); ?></td>
        <td>
        <style>
          .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
          .toggle.ios .toggle-handle { border-radius: 20px; }
        </style>
        <input type="checkbox" class="voucher_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
        </td>
        <td><button class='btn btn-info btn-xs modal-dlg-wide' data-href='<?php echo site_url($controller_name."/vc_edit/".$row['id']); ?>'
					title='Edit Voucher'><?php echo "Edit"; ?>
	      </button></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('.voucher_toggle').bootstrapToggle();

    $('#voucher_list').DataTable({
      "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });

    $('.voucher_toggle').on('change', function(){
      var id = $(this).closest('tr').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/voucher_toggle"); ?>', {'id': id, 'status': status}, function(data) {
        console.log(data);
      });
    });

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

    $('#select_voucher').on('change', function(){
			var selected_plan = $(this).val();
			$.post('<?php echo site_url($controller_name."/get_vouchers_sublist"); ?>', {'plan': selected_plan}, function(data) {
				$('#vouchers_table_area').html(data);
				$('#voucher_list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

	});
</script>

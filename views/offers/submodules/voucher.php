<!-- <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php //echo site_url($controller_name."/create_voucher"); ?>'
          title='Create New Voucher'>
    Create
</button> -->

<div id="vouchers_table_area">
  <table id="voucher_list" class="display cell-border" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Out</th>
        <th>Redeemed</th>
        <th>View Details</th>
        <th>Offer Amt</th>
        <th>Threshold Amt</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="text-center">
    <?php foreach ($this->db->get('special_vc')->result_array() as $row):
      //$customer_info = $this->Customer->get_info($row['customer_id']);
      ?>
      <tr id="<?php echo $row['id']; ?>">
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['voucher_code']; ?></td>
        <td><a class="modal-dlg-wide" title="See List" href="<?php echo site_url($controller_name."/get_vc_out/".$row['id']); ?>">
          <span class="glyphicon glyphicon-eye-open"></span>
        </a></td>
        <td><a class="modal-dlg-wide" title="See List" href="<?php echo site_url($controller_name."/get_vc_redeemed/".$row['id']); ?>">
          <span class="glyphicon glyphicon-eye-open"></span>
        </a></td>
        <td>
        <!-- <a class="modal-dlg-wide" title="View Details" href="<?php //echo site_url($controller_name."/get_vc_details/".$row['id']); ?>">
          <span class="glyphicon glyphicon-eye-open"></span>
        </a> -->
        </td>
        <td><?php echo to_currency($row['vc_val']); ?></td>
        <td><?php echo to_currency($row['vc_thres']); ?></td>
        <td>
        <!-- <a class="modal-dlg-wide" title="Edit" href="<?php //echo site_url($controller_name."/view_vc/".$row['id']); ?>">
          <span class="glyphicon glyphicon-eye-open"></span>
        </a> -->
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('#voucher_list').DataTable();

    
	});
</script>

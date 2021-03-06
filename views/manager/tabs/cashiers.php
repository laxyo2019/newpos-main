<hr>
<div class="row">
  <span class="col-md-2">
    <button class='btn btn-info modal-dlg' data-href='<?php echo site_url($controller_name."/cashier_add"); ?>'
            title='Create New Cashier'>
        </span>Add Cashier
    </button>
  </span>
  <span class="col-md-2">
    <!-- <a href="<?php //echo site_url($controller_name."/select_location")?>" class="btn btn-info btn-sm" 
  id="sales_takings_button" title="Daily Sales"><h6>Select Location</h6></a> -->
  </span>
  <!-- <span class="col-md-2 pull-right">
    <div class="form-group">
      <select class="form-control" id="creport_mode">
        <option value="cashiers">Cashiers</option>
        <option value="incentives">Incentives</option>
      </select>
    </div>
  </span>
  <span class="col-md-2 pull-right">
    <div class="form-group">
      <?php //echo form_dropdown('shops', $active_shops, '', array('class'=>'form-control shops','id'=>'shops')); ?>
    </div>
  </span> -->
</div>

<hr>

<div id="cashier_table_area">
  <table id="cashier_list" class="display nowrap" style="width:100%">
    <thead>
      <tr>
        <th>Sale Code</th>
        <th>Name</th>
        <th>Shop</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($cashiers as $row): ?>
        <tr id="<?php echo $row['id']; ?>">
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td>
            <?php foreach(json_decode($row['shops']) as $shop)
            {
              echo $this->Employee->get_info($shop)->first_name;
              echo ' | ';
            }
            ?>
          </td>
          <td><?php echo $row['contact']; ?></td>
          <td>
            <style>
              .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
              .toggle.ios .toggle-handle { border-radius: 20px; }
            </style>
            <input type="checkbox" class="cashier_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
  $(document).ready(function(){
    dialog_support.init("button.modal-dlg");
    $('.cashier_toggle').bootstrapToggle();

    $('#cashier_list').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]
      });

    $('.cashier_toggle').on('change', function(){
      var id = $(this).closest('tr').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/cashier_toggle"); ?>', {'id': id, 'status': status}, function(data) {
        console.log(data);
      });
    });
    $('#edit_stock_location').on('change', function(){
      var report_type = $('#edit_stock_location').val();
      $('#bulk_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/edit_stock_location") ?>', {'report_type': report_type}, function(data) {
          $('#bulk_table_area').html(data);
          $('#bulk_action_list').DataTable({
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
<?php 
 $cashiers = $this->db->get('cashiers')->result_array();
?>
<div class="row">
  <span class="col-md-2">
    <button class='btn btn-info modal-dlg' data-href='<?php echo site_url($controller_name."/cashier_add"); ?>'
            title='Create New Cashier'>
        </span>Add Cashier
    </button>
  </span>  
</div>
<hr>
<div id="cashier_table_area">
  <table id="cashier_list" class="display nowrap" style="width:100%">
    <thead>
      <tr>
        <th>Sale Code</th>
        <th>Name</th>
        <th>Webkey</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($cashiers as $row): ?>
     
        <tr id="<?php echo $row['id']; ?>">
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['webkey']; ?></td>
          <td><?php echo $row['contact']; ?></td>
          <td>
          	<a class='modal-dlg' href='<?php echo site_url();?>/offers/cashier_edit_view/<?php echo $row['id'];?>' style='font-size:20px'><i class='fa fa-pencil'></i></a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    dialog_support.init(".modal-dlg");
    $('.cashier_toggle').bootstrapToggle();

    $('#cashier_list').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        order: [[0, 'desc']],
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

  });
</script>
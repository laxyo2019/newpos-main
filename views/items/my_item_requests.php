<table id="req_list" class="display" style="width:100%">
  <thead>
    <tr>
      <th>Cancel</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Time</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $row): 
    $item_info = $this->Item->get_info($row['item_id']);
    ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><span style="cursor:pointer" class="glyphicon glyphicon-trash cancel-request"></span></td>
      <td><?php echo $item_info->item_number; ?></td>
      <td><?php echo $item_info->name; ?></td>
      <td><?php echo ($item_info->unit_price < 1) ? json_decode($item_info->cost_price)->retail : $item_info->unit_price; ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo $row['created_at']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {

    $('#req_list').DataTable({
      "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });

    $('.cancel-request').on('click', function(){
      if(confirm('Are you sure, you wish to cancel this request?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/request_item_cancel"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });
    
  });
</script>
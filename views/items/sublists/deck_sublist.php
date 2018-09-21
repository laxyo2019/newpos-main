<table id="deck_sublist" class="display" style="width:100%">
  <thead>
    <tr>
      <th>Complete</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Price</th>
      <th>Available Quantity</th>
      <th>Required Quantity</th>
      <th>Time</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $row): 
    $item_info = $this->Item->get_info($row['item_id']);
  ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><span style="cursor:pointer" class="glyphicon glyphicon-trash complete-request"></span></td>
      <td><?php echo $item_info->item_number; ?></td>
      <td><?php echo $item_info->name; ?></td>
      <td><?php echo ($item_info->unit_price < 1) ? json_decode($item_info->cost_price)->retail : $item_info->unit_price; ?></td>
      <td><?php echo $this->Item_quantity->get_item_quantity($row['item_id'], $this->Stock_location->get_location_id_2($row['requester']))->quantity ; ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo $row['created_at']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    $('.complete-request').on('click', function(){
      if(confirm('Are you sure, you wish to complete this request?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/request_item_complete"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });
    
  });
</script>
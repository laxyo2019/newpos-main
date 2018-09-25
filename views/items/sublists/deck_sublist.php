<table id="deck_sublist" class="display" style="width:100%">
  <thead>
    <tr>
      <th>Accepted</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Available Quantity</th>
      <th>Required Quantity</th>
      <th>Time</th>
      <th>Declined</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $row): 
    $item_info = $this->Item->get_info($row['item_id']);
  ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><span style="cursor:pointer" class="glyphicon glyphicon-ok accept-request"></span></td>
      <td><?php echo $item_info->item_number; ?></td>
      <td><?php echo $item_info->name; ?></td>
      <td><?php echo $this->Item_quantity->get_item_quantity($row['item_id'], $this->Stock_location->get_location_id_2($row['requester']))->quantity ; ?></td>
      <td><?php echo $row['quantity']; ?></td>
      <td><?php echo $row['created_at']; ?></td>
      <td><span style="cursor:pointer" class="glyphicon glyphicon-trash decline-request"></span></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    $('.accept-request').on('click', function(){
      if(confirm('Are you sure, you wish to accept this request?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/request_item_accept"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });

    $('.decline-request').on('click', function(){
      if(confirm('Are you sure, you wish to decline this request?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/request_item_decline"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });
    
  });
</script>
<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <select class="form-control" id="deck_switch">
      <option value="">ALL</option>
      <?php
      foreach($this->Pricing->get_active_shops() as $row)
      {
        echo '<option value="'.$row['person_id'].'">'.strtoupper($row['username']).'</option>';
      }
      ?>
    </select>
  </div>
</div>

<hr>

<div id="table_area">
  <table id="deck_list" class="display" style="width:100%">
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
</div>

<script>
  $(document).ready( function () {

    $('#deck_list').DataTable({
      "scrollX": true,
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });

    $('#deck_switch').on('change', function(){
      var shop_id = $(this).val();
      $.post('<?php echo site_url($controller_name."/switch_deck"); ?>', {'shop_id': shop_id}, function(data) {
        $('#table_area').html(data);
        $('#deck_sublist').DataTable({
          "scrollX": true,
          dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
          ]
        });
      });
    });

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
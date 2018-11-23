<table id="dynamic_prices" class="display" style="width:100%">
  <thead>
    <tr>
      <th>Delete</th>
      <th>Offer ID</th>
      <th>Location</th>
      <th>Pointer</th>
      <th>Price</th>
      <th>Discount</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Switch</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($dynamic_prices as $row): ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><span style="cursor:pointer" class="glyphicon glyphicon-trash delete-basic"></span></td>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $this->Stock_location->get_location_name2($row['locations']); ?></td>
      <td><?php echo $row['pointer']; ?></td>
      <td><?php echo $row['price']; ?></td>
      <td><?php echo $row['discount']; ?></td>
      <td><?php echo $row['start_time']; ?></td>
      <td><?php echo $row['end_time']; ?></td>
      <td>
        <style>
          .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
          .toggle.ios .toggle-handle { border-radius: 20px; }
        </style>
        <input type="checkbox" class="offer_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    dialog_support.init("a.modal-dlg-wide");
    $('.offer_toggle').bootstrapToggle();

    $('.offer_toggle').on('change', function(){
      var id = $(this).closest('tr').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/offer_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });

    $('.delete-basic').on('click', function(){
      if(confirm('Are you sure, you wish to delete this offer?')){
        var id = $(this).closest('tr').attr('id');
        var that = this;
        $.post('<?php echo site_url($controller_name."/delete_basic"); ?>', {'id': id}, function(data) {
          alert(data);
          $(that).closest('tr').fadeOut();
        });
      }
    });
    
  });
</script>
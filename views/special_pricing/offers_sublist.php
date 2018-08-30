<table id="list" class="display" style="width:100%">
  <thead>
    <tr class="text-center">
      <th>Offer ID</th>
      <th>Location</th>
      <th>Pointer</th>
      <th>Price</th>
      <th>Discount</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($offers as $row): ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $this->Stock_location->get_location_name2($row['locations']); ?></td>
      <td><?php echo $row['pointer']; ?></td>
      <td><?php echo $row['price']; ?></td>
      <td><?php echo $row['discount']; ?></td>
      <td><?php echo $row['start_time']; ?></td>
      <td><?php echo $row['end_time']; ?></td>
      <td>
        <?php //echo anchor($controller_name."/add_basic_form/".$row['id'], '<span class="glyphicon glyphicon-edit"></span>',
      //array('class' => 'modal-dlg-wide print_hide', 'title' => $this->lang->line($controller_name.'_update'))
      //);?>
        <?php if($row['status'] == 1){ ?>
          <span style="cursor:pointer" class="glyphicon glyphicon-trash deactivate" title="Deactivate"></span>
        <?php } ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    dialog_support.init("a.modal-dlg-wide");

    $('.deactivate').on('click', function(){
      var id = $(this).closest('tr').attr('id');
      var that = this; // 'this' reference for usage inside post request
      console.log(id);
      $.post('<?php echo site_url($controller_name."/deactivate_offer"); ?>', {'id': id}, function(data) {
				(data == "success") ? $(that).closest('tr').fadeOut() : alert("Server Error"); 
      });
    });
  });
</script>
	
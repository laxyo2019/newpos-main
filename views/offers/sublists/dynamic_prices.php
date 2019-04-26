<table id="dynamic_prices" class="display" style="width:100%">
  <thead>
    <tr>
      <th>ID</th>
      <th>Pointer</th>
      <th>Location</th>
      <th>Title</th>
      <th>Discount</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($dynamic_prices as $row): ?>
    <tr id="<?php echo $row['id']; ?>">
      <td><?php echo $row['id']; ?></td>
      <?php
          $where = array('id'=>$row['pointer_group_id']);
          $pointer_title =	$this->Control_Panel->fetch_single_column('offer_pointer_groups','title',$where);
      ?>
      <td><a href='<?php echo site_url();?>offers/get_pointer_group/<?php echo $row['pointer_group_id']; ?>' class='modal-dlg' title='Pointer Group' style='text-decoration:none;'><?php  echo( $pointer_title); ?></a></td>
      <?php
          $where = array('id'=>$row['location_group_id']);
          $location_title =	$this->Control_Panel->fetch_single_column('offer_location_groups','title',$where);
      ?>
      <td><a href='<?php echo site_url();?>offers/get_loc_group/<?php echo $row['location_group_id']; ?>' class='modal-dlg' title='Location Group'  style='text-decoration:none;'><?php  echo( $location_title); ?></a></td>
      <td><?php echo $row['title']; ?></td>
      <td><?php echo $row['discount']; ?></td>
      <td><?php echo $row['start_time']; ?></td>
      <td><?php echo $row['end_time']; ?></td>
      <td>
        <style>
          .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
          .toggle.ios .toggle-handle { border-radius: 20px; }
        </style>
        <input type="checkbox" class="offer_toggle" data-toggle="toggle" <?php if($row['status']) echo 'checked';?> data-onstyle="success"data-offstyle="danger" data-style="ios" data-size="mini" />
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    dialog_support.init("a.modal-dlg");
    $('.offer_toggle').bootstrapToggle();

    $('.offer_toggle').on('change', function(){
      var id = $(this).closest('tr').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/offer_toggle2/dynamic_prices"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });

    // $('.delete-basic').on('click', function(){
    //   if(confirm('Are you sure, you wish to delete this offer?')){
    //     var id = $(this).closest('tr').attr('id');
    //     var that = this;
    //     $.post('<?php  //echo site_url($controller_name."/delete_basic"); ?>', {'id': id}, function(data) {
    //       alert(data);
    //       $(that).closest('tr').fadeOut();
    //     });
    //   }
    // });
    
  });
</script>
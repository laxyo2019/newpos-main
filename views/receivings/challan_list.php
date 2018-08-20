<div class="table_list">
  <table id="list" class="display">
    <thead>
      <tr>
        <th>Challan ID</th>
        <th>From</th>
        <th>To</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($challans as $row): ?>
      <tr>
        <td><?php echo $row['receiving_id']; ?></td>
        <td><?php echo $this->Stock_location->get_location_name2($row['employee_id']) ?></td>
        <td><?php echo $this->Stock_location->get_location_name2($row['destination']) ?></td>
        <td><?php echo $row['receiving_time']; ?></td>
        <td><a href="receivings/delivery_challan/<?php echo $row['receiving_id'] ?>"><span class="glyphicon glyphicon-list-alt"></span></a></td>
      </tr>
    <?php endforeach; ?>  
    </tbody>
  </table>
</div>

<script>
  $(document).ready(function(){
    $('#list').DataTable({
        "order": [[ 0, "desc" ]]
    } );
  });


</script>
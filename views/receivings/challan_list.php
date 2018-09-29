<div class="table_list">
  <table id="list" class="display">
    <thead>
      <tr>
        <th>ID</th>
        <th>From</th>
        <th>To</th>
        <th>Dispatcher</th>
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
        <td><?php echo (empty($row['dispatcher_id'])) ? "" : $this->Sale->get_cashier_detail($row['dispatcher_id'], 'name'); ?></td>
        <td><?php echo $row['receiving_time']; ?></td>
        <td>
          <a href="receivings/delivery_challan/<?php echo $row['receiving_id'] ?>"><span title="Show DC" class="glyphicon glyphicon-list-alt"></span></a>
          &nbsp;&nbsp;&nbsp;
          <a href="receivings/challan_excel/<?php echo $row['receiving_id'] ?>"><span title="Show Excel" class="glyphicon glyphicon glyphicon-barcode"></span></a>
        </td>
      </tr>
    <?php endforeach; ?>  
    </tbody>
  </table>
</div>

<script>
  $(document).ready(function(){
    $('#list').DataTable({
        "order": [[ 0, "desc" ]]
    });
  });
</script>
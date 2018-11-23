<div class="row">
<div class="container">
<table id="customer_list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Mobile Number</th>
      <th>Email Address</th>
      <th>GST Number</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($customers as $row): ?>
      <tr>
        <td><?php echo $row['person_id']; ?></td>
        <td><?php echo $row['first_name']; ?></td>
        <td><?php echo $row['last_name']; ?></td>
        <td><?php echo $row['phone_number']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['gstin']; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
</div>

<script>
$(document).ready(function(){
  $('#customer_list').DataTable({
    "scrollX": true,
    dom: 'Bfrtip',
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });
});

</script>
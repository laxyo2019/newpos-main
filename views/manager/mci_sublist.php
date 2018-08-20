<table id="list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($mci_data as $row): ?>
    <tr style="text-align: center;">
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['name']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
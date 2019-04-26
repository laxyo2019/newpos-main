
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>Subcategory</th>
      <th>Brand</th>
      <th>Size</th>
      <th>Color</th>
      <th>MRP</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($sheets as $sheet):  ?>
    <tr>
      <td><?php echo $sheet->id; ?></td>
      <td><?php echo $sheet->barcode; ?></td>
      <td><?php echo $sheet->name; ?></td>
      <td><?php echo $sheet->category; ?></td>
      <td><?php echo $sheet->subcategory; ?></td>
      <td><?php echo $sheet->brand; ?></td>
      <td><?php echo $sheet->price; ?></td>
      <td><?php echo $sheet->color; ?></td>
      <td><?php echo $sheet->size; ?></td>
      <td><?php echo $sheet->quantity; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {
    $('table').DataTable({

      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
    
  });

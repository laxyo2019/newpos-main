<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name of Sheet</th>
      <th>Sheet Uploader</th>
      <th>Sheet Status</th>
      <th>Creted At</th>
      <th>View</th
    </tr>
  </thead>
  <tbody>
  
  <?php foreach ($sheets as $sheet): ?>
  <?php 
    if($sheet->status=='approved'){
      $class='text-success';
    }else if($sheet->status=='discarded'){
      $class='text-danger';
    }else{
      $class='';
    }
  ?>
  <tr >
      <td class='<?php echo $class;?>'><?php echo $sheet->id; ?></td>
      <td class='<?php echo $class;?>'><?php echo $sheet->name; ?></td>
      <td class='<?php echo $class;?>'><?php echo $sheet->title; ?></td>
      <td class='<?php echo $class;?>'><?php echo strtoupper($sheet->status); ?></td>
      <td class='<?php echo $class;?>'><?php echo $sheet->created_at;?></td>
      <td class='<?php echo $class;?>'><span style='font-size: 22px;' onclick='sheet_data(<?php echo $sheet->id; ?>,"<?php echo $sheetStatus;?>")' class='fa fa-file-text-o'></span></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready( function () {

    $('table').DataTable({
      dom: 'Bfrtip',
      "order": [[ 0, "desc" ]],
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
    
  });
</script>
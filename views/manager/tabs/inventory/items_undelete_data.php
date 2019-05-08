<?php $this->load->view("partial/header"); ?>
<div class="row">
<div class="col-md-12" >
<div class="bg-info" style="color:#fff;padding:10px;margin-bottom:20px;">
      <a style="color:#fff" href="<?php echo site_url();?>manager"><h4 style="display:inline">Manager</h4> </a>&gt;&gt; Inventory 
</div>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
  
  <?php foreach ($items as $item): 
    
    $class= $item->status!='undeleted' ? 'text-danger' : 'text-success';
    ?>
  <tr >
      <td class='<?php echo $class; ?>'><?php echo $item->id; ?></td>
      <td class='<?php echo $class; ?>'><?php echo $item->barcode; ?></td>
      <td class='<?php echo $class; ?>'><?php echo $item->status; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
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
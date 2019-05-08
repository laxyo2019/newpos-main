<?php $this->load->view("partial/header"); ?>
<?php
 $this->db->select('sheet_uploads.*,custom_fields.title');
 $this->db->join('custom_fields','sheet_uploads.sheet_uploader_id=custom_fields.id');
 $this->db->where('sheet_uploads.type','undelete_stock');
 $sheets = $this->db->get('sheet_uploads')->result();

?>
<div class="row">
<div class="col-md-12" >
<div class="bg-info" style="color:#fff;padding:10px;margin-bottom:20px;">
      <a style="color:#fff" href="<?php echo site_url();?>manager"><h4 style="display:inline">Manager</h4> </a>&gt;&gt; Inventory 
</div>
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
  <tr >
      <td><?php echo $sheet->id; ?></td>
      <td><?php echo $sheet->name; ?></td>
      <td><?php echo $sheet->title; ?></td>
      <td><?php echo strtoupper($sheet->status); ?></td>
      <td><?php echo $sheet->created_at;?></td>
      <td><a target="_blank" href="<?php echo site_url();?>manager/items_undelete_data/<?php echo $sheet->id;?>"><span style='font-size: 22px;' class='fa fa-file-text-o'></span></a></td>
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
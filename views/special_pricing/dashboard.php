<?php $this->load->view("partial/header"); ?>

<div class="pull-left">
  <p>Total Items: <?php echo $this->Item->get_total_rows(); ?></p>
</div>

<div class="pull-right">
  <button id="excel_export" class="btn btn-success btn-sm  modal-dlg-wide", data-href='<?php echo site_url($controller_name."/add_item_form"); ?>'
        title='Add Item'>
          Add Item
  </button>
</div>

<table id="list" class="display">
  <thead>
    <tr>
      <th>Item ID</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Actual Price</th>
      <th>Special Price</th>
      <th>Valid Till</th>
      <th>Action</th>
    </tr> 
  </thead>
  <tbody>
    <?php foreach($this->Pricing->get_all(10) as $row): ?>
      <tr>
        <td><?php echo $row['item_id']; ?></td>
        <td><?php echo $row['item_number']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo ($row['unit_price'] < 1) ? json_decode($row['cost_price'])->retail : $row['unit_price']; ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo date_format(date_create($row['validity']), 'd F, Y'); ?></td>
        <td>
          <?php echo ($this->Pricing->check_validity($row['validity'])) ? '<button class="btn btn-xs btn-warning">Edit</button>' : '<button class="btn btn-xs btn-danger">Renew</button>'; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(document).ready(function(){
    $('#list').DataTable();

    dialog_support.init("a.modal-dlg, button.modal-dlg-wide");
  });
</script>

<?php $this->load->view("partial/footer"); ?>
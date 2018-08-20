<table id="list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Barcode</th>
      <th>Item Name</th>
      <th>Category</th>
      <th>SubCategory</th>
      <th>Brand</th>
      <th>Size</th>
      <th>Color</th>
      <th>MRP</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
  <?php
    if(!empty($items))
    { 
      foreach ($items as $item): 
  ?>
      <tr>
        <td><?php echo $item->item_id; ?></td>
        <td><?php echo $item->barcode; ?></td>
        <td><?php echo $item->name; ?></td>
        <td><?php echo $item->category; ?></td>
        <td><?php echo $item->subcategory; ?></td>
        <td><?php echo $item->brand; ?></td>
        <td><?php echo $item->size; ?></td>
        <td><?php echo $item->color; ?></td>
        <td><?php echo $item->price; ?></td>
        <td><?php echo $item->quantity; ?></td>
      </tr>
  <?php endforeach;
    }
  ?>
  </tbody>
</table>
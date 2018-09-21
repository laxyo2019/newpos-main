<?php
  echo '<p style="text-align:center; font-weight:bold">'.$barcode.' | '.$item_name.' | '.$item_data_type.'</p>';
?>

<ul class="list-group">
  <li class="list-group-item">
    <span class="badge"><?php echo json_decode($item_data)->retail; ?></span>
    RETAIL
  </li>

  <?php if($this->Item->check_auth(array('admin', 'superadmin'))){ ?>
    <li class="list-group-item">
      <span class="badge"><?php echo json_decode($item_data)->wholesale; ?></span>
      WHOLESALE
    </li>
  <?php } ?>

  <li class="list-group-item">
    <span class="badge"><?php echo json_decode($item_data)->franchise; ?></span>
    FRANCHISE
  </li>

  <li class="list-group-item">
    <span class="badge"><?php echo json_decode($item_data)->ys; ?></span>
    SPECIAL APPROVAL
  </li>
</ul>

<?php
  echo '<h3 class="text-center text-success" style="font-weight:bold">'.$pointer.'</h3>';
?>
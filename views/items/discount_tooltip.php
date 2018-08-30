<?php
  echo '<p style="text-align:center; font-weight:bold">'.$barcode.' | '.$item_name.' | '.$item_data_type.'</p>';
?>

<ul class="list-group">
  <li class="list-group-item">
    <span class="badge"><?php echo json_decode($item_data)->retail; ?></span>
    RETAIL
  </li>

  <?php if($this->Item->is_both()){ ?>
    <li class="list-group-item">
      <span class="badge"><?php echo json_decode($item_data)->wholesale; ?></span>
      WHOLESALE
    </li>
  <?php } ?>

  <li class="list-group-item">
    <span class="badge"><?php echo json_decode($item_data)->franchise; ?></span>
    FRANCHISE
  </li>

  <?php if(!$this->Item->is_franchise()){ ?>
    <li class="list-group-item">
      <span class="badge"><?php echo json_decode($item_data)->ys; ?></span>
      SPECIAL APPROVAL
    </li>
  <?php } ?>
</ul>
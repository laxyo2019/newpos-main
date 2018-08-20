<?php
  echo "<p>Barcode: <strong>".$barcode."</strong></p>";
  echo "<p>Item Name: <strong>".$item_name."</strong></p>";
?>
<table class="table table-bordered">
  <tbody>
    <?php if($item_data_type == 'discounted'){ ?>
      <tr>
        <th>Type</th>
        <th>Discount</th>
      </tr>
      <?php
        foreach(json_decode($item_data) as $key=>$value){
            $label = ($key == 'ys') ? "DAMAGED" : strtoupper($key);    
          echo '<tr>';
          echo '<td>'.$label.'</td>';
          echo '<td>'.$value.'%</td>';
          echo '</tr>';
        }
      ?>
    <?php }else if($item_data_type == 'fixed'){ ?>
      <tr>
        <th>Type</th>
        <th>Fixed Price</th>
      </tr>
      <?php
        foreach(json_decode($item_data) as $key=>$value){
            $label = ($key == 'ys') ? "DAMAGED" : strtoupper($key);
          echo '<tr>';
          echo '<td>'.$label.'</td>';
          echo '<td>'.$value.'</td>';
          echo '</tr>';
        }
      ?>
    <?php } ?>

  </tbody>


</table>

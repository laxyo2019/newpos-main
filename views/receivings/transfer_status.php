<?php foreach($pending_transfers as $row): ?>
<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse<?php echo $row['receiving_id'] ?>">
          <?php echo $this->Stock_location->get_location_name2($row['employee_id']) ?>
          >>
          <?php echo $this->Stock_location->get_location_name2($row['destination']) ?>
          <span>(<?php echo $row['receiving_time'] ?>)</span>
        </a>
        <p class="pull-right">Items Left: <?php echo $this->Receiving->get_items_left($row['receiving_id'], 'rows'); ?></p>
      </h4>
    </div>
    <div id="collapse<?php echo $row['receiving_id'] ?>" class="panel-collapse collapse">
      <ul class="list-group">
        <?php foreach($this->Receiving->get_items_left($row['receiving_id']) as $item) : ?>
          <li class="list-group-item">
            <?php echo $this->Item->get_info($item['item_id'])->item_number; ?>
            <span class="pull-right"><?php echo $item['quantity'] ?></span>
          </li>
        <?php endforeach; ?>  
      </ul>
      <div class="panel-footer">Total Quantity: <?php echo $this->Receiving->get_items_left($row['receiving_id'], 'quantity'); ?></div>
    </div>
  </div>
</div>
<?php endforeach; ?>
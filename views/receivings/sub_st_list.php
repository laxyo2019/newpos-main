<table id="list" class="display" style="width:100%">
  <thead>
      <tr>
        <th class="text-center">Action</th>
        <th class="text-center">Barcode</th>
        <th class="text-center">Item Name</th>
				<th class="text-center">Dispatched Qty</th>
				<th class="text-center">Accept Qty</th>
      </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $item): ?>
    <tr class="itemrow" id="<?php echo $item['item_id']; ?>">
      <input type="hidden" id="receiving_id" value="<?php echo $item['receiving_id']; ?>">
      <input type="hidden" id="item_id" value="<?php echo $item['item_id']; ?>">
      <td style="font-size:1.5em; text-align:center"><span class="save glyphicon glyphicon-save"></span></td>
      <td class="text-center"><?php echo $this->Item->get_info($item['item_id'])->item_number; ?></td>
      <td class="text-center"><?php echo $this->Item->get_info($item['item_id'])->name; ?></td>
      <td class="text-center"><input type="number" class="form-control inputz" id="quantity" readonly="true" value="<?php echo $item['quantity']; ?>" min="0"></td>
      <td class="text-center"><input type="number" class="form-control inputz" id="takeIn" value="<?php echo $item['quantity']; ?>" min="0"></td>
      <input type="hidden" class="inputz" id="good" value="0" min="0" style="width:70px">
      <input type="hidden" class="inputz" id="bad" value="0" min="0" style="width:70px">
      <input type="hidden" class="inputz" id="scrap" value="0" min="0" style="width:70px">
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(".inputz").keypress(function (e) { // allow positive numbers only
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });

  $('.itemrow').on('click', '.save', function(){
    var rowId = $(this).parent().parent().attr('id');
    var receiving_id = $('#'+rowId).find('#receiving_id').val();
    var item_id = $('#'+rowId).find('#item_id').val();
    var quantity = $('#'+rowId).find('#quantity').val();
    var accept = eval($('#'+rowId).find('#takeIn').val());
    var good = eval($('#'+rowId).find('#good').val());
    var bad = eval($('#'+rowId).find('#bad').val());
    var scrap = eval($('#'+rowId).find('#scrap').val());
    var that = this;
    if(quantity == accept)
    {
      $.post('<?php echo site_url($controller_name."/st_process");?>', {'receiving_id': receiving_id, 'item_id': item_id, 'accept': accept, 'good': good, 'bad': bad, 'scrap': scrap}, function(data) {
        $(that).parent().parent().remove();
      });
    }
    else
    {
      alert('Invalid Entry!');
    }
	});
</script>
<?php 
     $item_id = array();
     $quantity =array();
    foreach ($items as $item): 
        $item_id[] = $item['item_id'];
        $quantity[] = $item['quantity'];
        endforeach; 
?>
<div id="recving">
<table id="list" class=" display table table-bordered" style="width:100%">
  <thead>
      <tr>
        <th class="text-center">Barcode</th>
        <th class="text-center">Item Name</th>
				<th class="text-center">Dispatched Qty</th>
      </tr>
  </thead>
  <tbody>
  <?php foreach ($items as $item): ?>
    <tr class="itemrow" id="<?php echo $item['item_id']; ?>">
      <input type="hidden" id="receiving_id" value="<?php echo $item['receiving_id']; ?>">
      <input type="hidden" id="item_id" value="<?php echo $item['item_id']; ?>">
      <td class="text-center"><?php echo $this->Item->get_info($item['item_id'])->item_number; ?></td>
      <td class="text-center"><?php echo $this->Item->get_info($item['item_id'])->name; ?></td>
      <td class="text-center quantity" id= "<?php echo $item['quantity']; ?>"><?php echo $item['quantity']; ?></td>
      <input type="hidden" class="inputz" id="good" value="0" min="0" style="width:70px">
      <input type="hidden" class="inputz" id="bad" value="0" min="0" style="width:70px">
      <input type="hidden" class="inputz" id="scrap" value="0" min="0" style="width:70px">
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<hr>
<?php foreach ($items as $item): ?>
  <input type="hidden" id="recv_id" value="<?php echo $item['receiving_id']; ?>">
<?php endforeach; ?>
<label for="comment">Comments</label>
<textarea class="form-control" id="final_comment" cols="30" rows="3"></textarea>
</div>
<script>
  $(".inputz").keypress(function (e) { // allow positive numbers only
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });

 	$('#accept').on('click', function(){
	   	var fn_cmnt = $('#final_comment').val();
      var rowId = $('.itemrow').attr('id');
      var receiving_id = $('#'+rowId).find('#receiving_id').val();
      var item_id = $('#'+rowId).find('#item_id').val();
      var quantity = $('#'+rowId).find('.quantity').attr('id');
      var accept = eval($('#'+rowId).find('#takeIn').val());
      var good = eval($('#'+rowId).find('#good').val());
      var bad = eval($('#'+rowId).find('#bad').val());
      var scrap = eval($('#'+rowId).find('#scrap').val());
      var item_id = <?php echo json_encode($item_id); ?>;
      var quantity = <?php echo json_encode($quantity); ?>;
      var recv_id = $('#recv_id').val();
      $.post('<?php echo site_url($controller_name."/st_process");?>', {'receiving_id': receiving_id, 'item_id': item_id, 'quantity': quantity, 'good': good, 'bad': bad, 'scrap': scrap, 'recv_id':recv_id,'fn_cmnt': fn_cmnt}, function(data) {
           // alert(data);
           location.reload();
      });

   });

</script>
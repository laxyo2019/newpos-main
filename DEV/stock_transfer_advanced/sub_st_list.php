<table id="list" class="display" style="width:100%">
  <thead>
      <tr>
        <th>Action</th>
        <th>Item Name</th>
				<th>Quantity</th>
				<th>Accept</th>
				<th>Good</th>
				<th>Bad</th>
				<th>Scrap</th>
      </tr>
  </thead>
  <tbody>
  <?php echo sizeof(json_decode($jsondata)); ?>
  <?php foreach (json_decode($jsondata) as $row): ?>
    <tr class="itemrow" id="<?php echo $row->item_id; ?>" style="font-size:0.8em">
      <input type="hidden" id="receiving_id" value="<?php echo $row->receiving_id; ?>">
      <input type="hidden" id="item_id" value="<?php echo $row->item_id; ?>">
      <td style="font-size:1.5em; text-align:center"><span class="save glyphicon glyphicon-save"></span></td>
      <td style="text-align:center"><?php $this->Item->get_item_name($row->item_id); ?></td>
      <td><input type="number" class="inputz" id="quantity" readonly="true" value="<?php echo $row->quantity; ?>" min="0" style="width:70px"></td>
      <td><input type="number" class="inputz" id="takeIn" value="<?php echo $row->quantity; ?>" min="0" style="width:70px"></td>
      <td><input type="number" class="inputz" id="good" value="0" min="0" style="width:70px"></td>
      <td><input type="number" class="inputz" id="bad" value="0" min="0" style="width:70px"></td>
      <td><input type="number" class="inputz" id="scrap" value="0" min="0" style="width:70px"></td>
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
    if(quantity == accept+good+bad+scrap){
      $.post('<?php echo site_url($controller_name."/st_process");?>', {'receiving_id': receiving_id, 'item_id': item_id, 'accept': accept, 'good': good, 'bad': bad, 'scrap': scrap}, function(data) {
        if(data){
          console.log(data);
        }else{
          alert('Server Down!');
        }
      });
      $(this).removeClass('glyphicon-save').addClass('glyphicon-saved');
      $(this).parent().parent().remove();
    }else{
      alert('Invalid Entry!');
    }
	});
</script>
<div class="row">
  <div class="form-group col-xs-8">
    <select class="form-control" multiple="multiple" id="cashier_locations">
      <?php foreach($shops as $key=>$value):?>
        <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-xs-2 pull-right">
    <button class="btn btn-sm btn-success" id="cashier_save">Submit</button>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-xs-6 col-xs-offset-3">
    <?php echo form_input(array(
      'id'=>'cashier_name',
      'placeholder'=>'Name',
      'class'=>'form-control input-sm'
    ));
    ?>

    <?php echo form_input(array(
      'id'=>'cashier_code',
      'placeholder'=>'Cashier Sale Code',
      'class'=>'form-control input-sm'
    ));
    ?>

    <?php echo form_input(array(
      'id'=>'contact',
      'placeholder'=>'Cashier Contact',
      'class'=>'form-control input-sm'
    ));
    ?>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#cashier_locations').select2();

    $('#cashier_save').on('click', function(){
			var shops = $('#cashier_locations').val();
			var name = $('#cashier_name').val();
      var sale_code = $('#cashier_code').val();
      console.log(shops);
			$.post('<?php echo site_url($controller_name."/cashier_save");?>', {'shops': shops, 'name': name, 'sale_code': sale_code, 'contact': contact}, function(data) {
				alert(data);
      });
		});
  });
</script>

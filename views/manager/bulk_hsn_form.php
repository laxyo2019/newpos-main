<div class="row">
  <div class="col-xs-6 col-xs-offset-3">
    <div class="form-group form-group-sm">
        <?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control','id'=>'level1')); ?>
        <br>
        <select name="subcategory" class="form-control" id="level2">
          <option value="">None</option>
        </select>
    </div>
  </div>

  <div class="col-xs-6">
    <div class="form-group form-group-sm">
    <?php echo form_input(array(
        'name'=>'hsn',
        'type'=>'number',
        'id' => 'hsn',
        'placeholder' => 'HSN Code',
        'class'=>'form-control input-sm'));
      ?>
    </div>
  </div>

  <div class="col-xs-6">
    <div class="form-group form-group-sm">
      <?php echo form_input(array(
          'name'=>'tax',
          'type'=>'number',
          'id' => 'tax',
          'placeholder' => 'TAX',
          'class'=>'form-control input-sm'));
      ?>
    </div>
  </div>

  <div class="col-xs-12">
    <button class="btn btn-success col-xs-6 col-xs-offset-3" id="proceed">Proceed</button>
  </div>
  
</div>

<script>
	$(document).ready(function() {
		$('#level1').on('change',function(){
      var level1 = $(this).val();
      if(level1){
          $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
            $('#level2').html(data);
          });
      }else{
          $('#level2').html('<option value="">Loading...</option>');
      }
		});

    $('#proceed').on('click', function(){
      var category = $('#level1').val();
      var subcategory = $('#level2').val();
      var hsn = $('#hsn').val();
      var tax = $('#tax').val();

    $.post('<?php echo site_url($controller_name."/bulk_hsn_update");?>', {'category': category, 'subcategory': subcategory, 'hsn': hsn, 'tax': tax}, function(data) {
            alert(data);
        });
    });

  });
</script>
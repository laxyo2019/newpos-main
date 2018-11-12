<div class="row">
  <div class="col-md-6">
    <input type="radio" name="radio" value="category"> On Category<br>
    <div class="form-group form-group-sm">
        <?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control', 'id'=>'bd_category')); ?>
    </div>
  </div>

  <div class="col-md-6">
    <input type="radio" name="radio" value="brand"> On Brand<br>
    <div class="form-group form-group-sm">
        <?php echo form_dropdown('brand', $brands, 'test', array('class'=>'form-control', 'id'=>'bd_brand')); ?>
    </div>
    <br>
  </div>    

  <div class="col-md-6 col-md-offset-3">
    <input type="radio" name="radio" value="subcategory"> On Subcategory<br>
    <div class="form-group form-group-sm">
        <?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control','id'=>'bd_level1')); ?>
        <br>
        <select name="db_subcategory" class="form-control" id="bd_level2">
          <option value="">None</option>
        </select>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group form-group-sm">
        <?php echo form_label('Type', 'dtype', array('class'=>'control-label col-xs-3')); ?>
        <?php echo form_dropdown('dtype', $custom_discounts, 'test', array('class'=>'form-control','id'=>'dtype')); ?>
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group form-group-sm">
      <?php echo form_label('Value', 'dvalue', array('class'=>'control-label col-xs-3')); ?>
      <?php echo form_input(array(
        'name'=>'dvalue',
        'type'=>'number',
        'id' => 'dvalue',
        'class'=>'form-control input-sm',
        'value'=>'0'));
      ?>
    </div>
  </div>
  <div class="col-md-12">
  <center><button class="btn btn-success" id="proceed">Proceed</button></center>
  </div>
  
</div>

<script>
	$(document).ready(function() {
		$('#bd_level1').on('change',function(){
		    var level1 = $(this).val();
		    // console.log(val);
		    if(level1){
            $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
              $('#bd_level2').html(data);
            });
		    }else{
		        $('#bd_level2').html('<option value="">Loading...</option>');
		    }
		});
	});

  $('#proceed').on('click', function(){
    var radio = $("input[name='radio']:checked").val();
    if(radio == null)
    {
      alert('Please select an option to proceed!');
    }
    else
    {
      var dtype = $('#dtype').val();
      var dvalue = $('#dvalue').val();

      if(radio == 'subcategory')
      {
        var key1 = $('#bd_level1').val();
        var key2 = $('#bd_level2').val();
      }
      else
      {
        var key1 = $('#bd_'+radio).val();
        var key2 = "";
      }

      console.log(dtype+' '+dvalue+' '+key1+' '+key2+' '+radio);
      
      $.post('<?php echo site_url($controller_name."/bulk_discount_update");?>', {'radio': radio, 'key1': key1, 'key2': key2, 'dtype': dtype, 'dvalue': dvalue}, function(data) {
	        alert(data);
      });
    }
  });
</script>
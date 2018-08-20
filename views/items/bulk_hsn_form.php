<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="form-group form-group-sm">
        <?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control','id'=>'level1')); ?>
        <br>
        <select name="subcategory" class="form-control" id="level2">
          <option value="">None</option>
        </select>
    </div>
    <hr>
  </div>

  <div class="col-md-6 col-md-offset-3">
    <div class="form-group form-group-sm">
    <?php echo form_input(array(
        'name'=>'hsn',
        'type'=>'number',
        'id' => 'hsn',
        'placeholder' => 'HSN Code',
        'class'=>'form-control input-sm'));
      ?>
    </div>
    <hr>
  </div>

  <div class="col-md-12">
    <span class="form-group form-group-sm col-md-4">
      <?php echo form_input(array(
          'name'=>'cgst',
          'type'=>'number',
          'id' => 'cgst',
          'placeholder' => 'CGST',
          'class'=>'form-control input-sm'));
      ?>
    </span>

    <span class="form-group form-group-sm col-md-4">
      <?php echo form_input(array(
            'name'=>'sgst',
            'type'=>'number',
            'id' => 'sgst',
            'placeholder' => 'SGST',
            'class'=>'form-control input-sm'));
        ?>
    </span>

    <span class="form-group form-group-sm col-md-4">
      <?php echo form_input(array(
            'name'=>'igst',
            'type'=>'number',
            'id' => 'igst',
            'placeholder' => 'IGST',
            'class'=>'form-control input-sm'));
        ?>
    </span>
  </div>
  <div class="col-md-12">
  <center><button class="btn btn-success" id="proceed">Proceed</button></center>
  </div>
  
</div>

<script>
	$(document).ready(function() {
		$('#level1').on('change',function(){
		    var level1 = $(this).val();
		    // console.log(val);
		    if(level1){
            $.post('<?php echo site_url($controller_name."/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
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

      var cgst = $('#cgst').val();
      var sgst = $('#sgst').val();
      var igst = $('#igst').val();

      console.log('category '+category+' subcategory '+subcategory+' hsn '+hsn+' cgst '+cgst+' sgst '+sgst+' igst '+igst);

        
    $.post('<?php echo site_url($controller_name."/bulk_hsn_update");?>', {'category': category, 'subcategory': subcategory, 'hsn': hsn, 'cgst': cgst, 'sgst': sgst, 'igst': igst}, function(data) {
            alert(data);
        });
    });

  });
</script>
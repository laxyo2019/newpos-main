<div class="row">
  <span class="col-md-4">
    <div class="form-group">
      <?php echo form_dropdown('locations', $active_shops, '', array('class'=>'form-control locations','id'=>'locations')); ?>
    </div>
  </span>
  <span class="col-md-1">
    <button class="btn btn-info" id="submit">Submit</button>
  </span>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">

      <button class='btn btn-info btn-sm modal-dlg' id="barcode_list" data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/make_barcode_list"); ?>'
        title='Quick Excel Stock Transfer' style="display:none">
      Upload Barcodes
      </button>

      <input type="text" id="item_id" class="form-control pointer" placeholder="Insert Barcode" style="display:none" />
      
      <?php echo form_dropdown('category', $categories, '', array('class'=>'form-control pointer','id'=>'category', 'style'=>'display:none')); ?>

      <?php echo form_dropdown('subcategory', $subcategories, '', array('class'=>'form-control pointer','id'=>'subcategory', 'style'=>'display:none')); ?>

      <?php echo form_dropdown('brand', $brands, '', array('class'=>'form-control pointer', 'id'=>'brand', 'style'=>'display:none')); ?>

    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <input type="number" class="form-control" id="price" placeholder="Temporary Price Value" style="display:none" />
    </div>
    <div class="form-group">
      <input type="number" class="form-control" id="discount" placeholder="Temporary Discount Value" min="1" max="30"/>
    </div>
  </div>

  <!-- <div class="col-md-4">
    <div class="form-group">
      <div class='input-group date datetimepicker' id='datetimepicker1'>
          <input type='text' class="form-control" id="start_time" placeholder="Start Time" />
          <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
    </div>
    <div class="form-group">
      <div class='input-group date datetimepicker' id='datetimepicker2'>
          <input type='text' class="form-control" id="end_time" placeholder="End Time" />
          <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
    </div>
  </div> -->
</div>

<script>
	$(document).ready( function () {
    // $('.datetimepicker').datetimepicker({
    //   // format: 'dd.mm.yyyy',
    //   // minView: 2,
    //   // maxView: 4,    
    //   autoclose: true
    // });
  
    var selected_plan = $('#select_plan').val();
    var main = ["single"];
    var main2 = ["category"];
    var main3 = ["subcategory"];
    var main4 = ["brand"];

    $('#item_id, #price').toggle(main.includes(selected_plan));
    $('#category').toggle(main2.includes(selected_plan));
    $('#subcategory').toggle(main3.includes(selected_plan));
    $('#brand').toggle(main4.includes(selected_plan));

    if(selected_plan == 'mixed')
    {
      var main5 = ["mixed"];
      $('#category, #subcategory, #brand').toggle(main5.includes(selected_plan));
    }
    
    $('#category').on('change', function(){
      var selected_plan = $('#select_plan').val();
      if(selected_plan = 'mixed')
      {
        var level1 = $(this).val();
        // console.log(val);
        if(level1){
            $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category' : level1}, function(data) {
              $('#subcategory').html(data);
          }); 
        }else{
            $('#subcategory').html('<option value="">Loading...</option>');
        }
      }
    });

    $('#submit').on('click', function(){
      var locations = $('.locations').val();
      var plan = $('#select_plan').val();
      if(plan == 'mixed')
      {
        var pointer = [$('#category').val(), $('#subcategory').val(), $('#brand').val()];
      }else{
        var pointer = $('.pointer:visible').val();
      }
      var price = $('#price').val();
      var discount = $('#discount').val();
      // var start_time = $('#start_time').val();
      // var end_time = $('#end_time').val();

      console.log(pointer);
      $.post('<?php echo site_url($controller_name."/add_basic_save"); ?>', 
      {
        'plan':plan,
        'locations':locations,
        'pointer':pointer,
        'price':price,
        'discount':discount,
        // 'start_time':start_time,
        // 'end_time':end_time
      },
      function(data) {
        alert(data);
			});
    });

	});
</script>
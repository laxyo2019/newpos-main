<div class="row">
  <span class="col-md-3">
    <div class="form-group">
      <input type="text" id="vc_code" class="form-control" placeholder="Voucher Code" />
    </div>
  </span>
  <span class="col-md-6 ">
    <select class="form-control" multiple="multiple" id="categories_covered">
      <?php foreach($categories as $key=>$value): ?>
        <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
      <?php endforeach; ?>
    </select>
  </span>
  <span class="col-md-1">
    <button class="btn btn-info" id="submit">Submit</button>
  </span>
</div>
<hr>
<div class="row">

  <div class="col-md-4">
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
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <input type="number" class="form-control" id="vc_val" placeholder="Voucher Value" />
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <input type="number" class="form-control" id="vc_thres" placeholder="Voucher Threshold" />
    </div>
    <!-- <div class="form-group">
      <input type="number" class="form-control" id="discount" placeholder="Temporary Discount Value" min="1" max="30"/>
    </div> -->
  </div>

</div>

<script>
	$(document).ready( function () {

    $('#categories_covered').select2();

    $('.datetimepicker').datetimepicker({
      // format: 'dd.mm.yyyy',
      // minView: 2,
      // maxView: 4,    
      autoclose: true
    });
  
    var selected_plan = $('#select_voucher').val();
    // switch (selected_plan) { 
    //   case 'single': 
    //     $('#item_id, #price').toggle(["single"].includes(selected_plan));
    //     break;
    //   case 'category':
    //     $('#category').toggle(["category"].includes(selected_plan));
    //     break;
    //   case 'subcategory':  
    //     $('#subcategory').toggle(["subcategory"].includes(selected_plan));
    //     break;		
    //   case 'brand':  
    //     $('#brand').toggle(["brand"].includes(selected_plan));
    //     break;
    //   case 'mixed':
    //     $('#category, #subcategory, #brand').toggle(["mixed"].includes(selected_plan));
    //     break;
    //   case 'mixed2':
    //     $('#category, #brand').toggle(["mixed2"].includes(selected_plan));
    //     break;
    //   default:
    //     alert('Please select a plan first');
    // }

    $('#submit').on('click', function(){
      var voucher = $('#select_voucher').val();
      
      var start_time = $('#start_time').val();
      var end_time = $('#end_time').val();

      $.post('<?php echo site_url($controller_name."/generate_voucher"); ?>', 
      {
        'voucher':voucher,
        'categories':categories,
        'vc_val':vc_val,
        'vc_thres':vc_thres,
        'start_time':start_time,
        'end_time':end_time
      },
      function(data) {
        var obj = JSON.parse(data);
        if(obj.type == "success" || obj.type == "error"){
          alert(obj.message);
        }else if(obj.type == "update"){
          if(confirm('Do you wish to overwrite an existing offer?'))
          {
            $.post('<?php echo site_url($controller_name."/update_basic"); ?>', 
            {
              // 'plan':plan,
              // 'locations':locations,
              // 'pointer':pointer,
              'id': obj.offer_id,
              'price':price,
              'discount':discount,
              'start_time':start_time,
              'end_time':end_time
            },
            function(data) {
              alert(data);
            });
          }
        }
			});
    });

	});
</script>
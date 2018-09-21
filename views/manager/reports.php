<div class="row">
  <span class="col-md-6">
    <div class="form-group" style="padding-top:10px;">
      <select class="form-control" multiple="multiple" id="report_locations">
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-sm btn-info" id="allSold">All Sold</button>
    <button class="btn btn-sm btn-warning" id="getReport">Get Report</button>
    <!-- <button class="btn btn-sm btn-success" id="stockupItems">Stockup Items</button> -->
  </span>
  <span class="pull-right">
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
  </span>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <select name="category3" id="category3">
        <option value="">Select Category</option>
        <?php foreach($mci_data['categories'] as $row)
        {
          echo '<option id="'.$row['id'].'">'.$row['name'].'</option>';
        }
        ?>
      </select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <select name="subcategory3" id="subcategory3"></select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <select name="brand3" id="brand3">
        <option value="">Select Brand</option>
        <?php foreach($mci_data['brands'] as $row)
        {
          echo '<option id="'.$row['id'].'">'.$row['name'].'</option>';
        }
        ?>
      </select>
    </div>
  </div>
</div>

<div class="row" id="extraMci3" style="display:none">
  <div class="col-md-3 col-md-offset-3">
    <div class="form-group">
      <select name="size3" id="size3">
        <option value="">Select Size</option>
        <?php foreach($mci_data['sizes'] as $row)
        {
          echo '<option id="'.$row['id'].'">'.$row['name'].'</option>';
        }
        ?>
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <select name="color3" id="color3">
        <option value="">Select Color</option>
        <?php foreach($mci_data['colors'] as $row)
        {
          echo '<option id="'.$row['id'].'">'.$row['name'].'</option>';
        }
        ?>
      </select>
    </div>
  </div>
</div>

<div id="report_table_area"></div>


<script>
	$(document).ready( function () {
    $('#report_locations').select2();
    <?php $this->load->view('partial/daterangepicker'); ?>

    $('#category3').on('change',function(){
      var level1 = $(this).val();
      var wearables = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
      $('#extraMci3').toggle(wearables.includes(level1));
      // console.log(val);
      if(level1){
          $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
            $('#subcategory3').html(data);
          });
      }
    });
    
    $('#getReport').on('click', function(){
      // var locations = $('#report_locations').val();
      var category = $('#category3').val();
      if(category != "")
      {
        $('#report_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
        var subcategory = $('#subcategory3').val();
        var brand = $('#brand3').val();
        var size = $('#size3').val();
        var color = $('#color3').val();
        var filterData = {
          'category': category,
          'subcategory': subcategory,
          'brand': brand,
          'custom2': size,
          'custom3': color
        };
        $.post('<?php echo site_url($controller_name."/report_sales");?>', {'filter': filterData, 'start_date': start_date, 'end_date': end_date}, function(data) {
            $('#report_table_area').html(data);
            $('#report_list').DataTable({
                  "scrollX": true,
                  dom: 'Bfrtip',
                  buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                  ]
              });
            });
      }
      else
      {
        alert('Please select a category');
      }
    });

    $('#allSold').on('click', function(){
      $('#report_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');

      $.post('<?php echo site_url($controller_name."/report_sales");?>', {'filter': 'all', 'start_date': start_date, 'end_date': end_date}, function(data) {
          $('#report_table_area').html(data);
          $('#report_list').DataTable({
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                  'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
          });
    });

	});
</script>

<hr>
<div class="row">
  <span class="col-md-12">
    <div class="form-group">
      <select class="form-control" multiple="multiple" id="list_locations">
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-sm btn-info" id="allItems">All Items</button>
    <button class="btn btn-sm btn-warning" id="filterItems">Filter Items</button>
    <button class="btn btn-sm btn-success" id="stockupItems">Stockup Items</button>
    <button class="btn btn-sm btn-success" id="ne2wItems">New Items</button>
    <!-- <span class="pull-right">
      <label class="checkbox-inline">
        <input type="checkbox" value="mci">MCI
      </label>
      <label class="checkbox-inline">
        <input type="checkbox" value="taxes">HSN + GST
      </label>
      <label class="checkbox-inline">
        <input type="checkbox" value="discounted">Discounts
      </label>
      <label class="checkbox-inline">
        <input type="checkbox" value="fixed">Fixed Prices
      </label>
    </span> -->
  </span>
  
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <select name="category2" id="category2" class="form-control">
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
      <select name="subcategory2" id="subcategory2" class="form-control"></select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <select name="brand2" id="brand2" class="form-control">
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

<div class="row" id="extraMci2" style="display:none">
  <div class="col-md-3 col-md-offset-3">
    <div class="form-group">
      <select name="size2" id="size2" class="form-control">
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
      <select name="color2" id="color2" class="form-control">
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

<div id="table_area"></div>


<script>
	$(document).ready( function () {
    $('#list_locations').select2();

    $('#category2').on('change',function(){
      var level1 = $(this).val();
      var wearables = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
      $('#extraMci2').toggle(wearables.includes(level1));
      // console.log(val);
      if(level1){
        $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
          $('#subcategory2').html(data);
        });
      }
    });
    
    $('#allItems').on('click', function(){
      var locations = $('#list_locations').val();
      console.log(locations);
      if(locations != null)
      {
        $('#table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
        $.post('<?php echo site_url($controller_name."/list_all_items");?>', {'locations': locations}, function(data){
          $('#table_area').html(data);
          $('#list').DataTable({
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
        alert('Please select a location');
      }
    });

    $('#filterItems').on('click', function(){
      var locations = $('#list_locations').val();
      var category = $('#category2').val();
      console.log(locations);
      if(locations != null && category != "")
      {
        $('#table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
        var subcategory = $('#subcategory2').val();
        var brand = $('#brand2').val();
        var size = $('#size2').val();
        var color = $('#color2').val();
        var filterData = {
          'category': category,
          'subcategory': subcategory,
          'brand': brand,
          'custom2': size,
          'custom3': color
        };
        $.post('<?php echo site_url($controller_name."/list_filtered_items");?>', {'filter': filterData, 'locations': locations}, function(data) {
        $('#table_area').html(data);
          $('#list').DataTable({
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
        alert('Please select a location and category');
      }
    });


    $('#stockupItems').on('click', function(){
      $('#table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/fetch_stockup_items") ?>', {'test': 'test'}, function(data) {
	        $('#table_area').html(data);
          $('#list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
        });
      });

    $('#newItems').on('click', function(){
      $('#table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/fetch_new_items") ?>', {'test': 'test'}, function(data) {
	        $('#table_area').html(data);
          $('#list').DataTable({
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
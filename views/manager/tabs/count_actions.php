<hr>
<div class="row">
  <span class="col-md-6">
    <div class="form-group">
      <select class="form-control"  id="count_locations">
        <option value="<?php echo json_encode(array_keys($stock_locations)); ?>">All Locations</option>
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>

      </select>
    </div>
    <button class="btn btn-sm btn-info" id="getAll">All Items</button>
    <button class="btn btn-sm btn-success" id="getCount">Get Count</button>
  </span>
  <div class="col-lg-3 col-xs-6 pull-right">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3 id="count"></h3>

        <p>Items</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <select name="category" id="category" class="form-control">
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
      <select name="subcategory" id="subcategory" class="form-control"></select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <select name="brand" id="brand" class="form-control">
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

<div class="row" id="extraMci" style="display:none">
  <div class="col-md-3 col-md-offset-3">
    <div class="form-group">
      <select name="size" id="size" class="form-control">
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
      <select name="color" id="color" class="form-control">
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

<script>
  $(document).ready(function(){
    // $('#count_locations').select2();
    
    $('#category').on('change',function(){
      var level1 = $(this).val();
      var wearables = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
      $('#extraMci').toggle(wearables.includes(level1));
      // console.log(val);
      if(level1){
          $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
            $('#subcategory').html(data);
          });
      }
		});

    $('#getAll').on('click', function(){
      var locations = $('#count_locations').val();
      console.log(locations);
      if(locations != null)
      {
        $('#count').html('<img src="<?php echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');
        $.post('<?php echo site_url($controller_name."/count_all_items");?>', {'locations': locations}, function(data) {
              $('#count').html(data);
            });
      }
      else
      {
        alert('Please select a location');
      }
    });

    $('#getCount').on('click', function(){
      var locations = $('#count_locations').val();
      var category = $('#category').val();
      console.log(locations);
      if(locations != null && category != "")
      {
        $('#count').html('<img src="<?php echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');
        var subcategory = $('#subcategory').val();
        var brand = $('#brand').val();
        var size = $('#size').val();
        var color = $('#color').val();
        var filterData = {
          'category': category,
          'subcategory': subcategory,
          'brand': brand,
          'custom2': size,
          'custom3': color
        };
        $.post('<?php echo site_url($controller_name."/get_count");?>', {'filter': filterData, 'locations': locations}, function(data) {
              $('#count').html(data);
      // console.log(data);
            });
      }
      else
      {
        alert('Please select a location and category');
      }
    });

  });
</script>
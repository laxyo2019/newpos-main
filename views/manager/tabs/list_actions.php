<?php $this->load->view("partial/header"); 

$stock_locations = $this->Stock_location->get_allowed_locations();
$mci_data = $this->Item->get_mci_data('all');
?>

<div class="row">
  <span class="col-md-12">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> List Actions 
  </div>
    <div class="form-group">
      <select class="form-control" id="location_id">
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
   <!-- <button class="btn btn-sm btn-primary" id="allItems">All Items</button> -->
    <a class="btn btn-sm btn-primary" id="excelExport" href='<?php echo site_url($controller_name."/list_all_items/4");?>'>All Items</a>
    <button class="btn btn-sm btn-warning" id="filterItems">Filter Items</button>
    <button class="btn btn-sm btn-default" id="stockupItems">Stockup Items</button>
    <button class="btn btn-sm btn-success" id="newItems">New Items</button>
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

//     $('#allItems').on('click', function(){
//       var location_id = $('#location_id').val();
//       console.log('location_id', location_id);

//       $('#table_area').html('<img src="<?php  //echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');

//       $.get('<?php // echo site_url($controller_name."/list_all_items/");?>'+location_id, function(data) {
//         $('#table_area').html(data);
//         $('#list').DataTable({
//           "scrollX": true,
//           dom: 'Bfrtip',
//           buttons: [
//             'copy', 'csv', 'excel', 'pdf', 'print'
//           ]
//         });
//       });
//     });
    $('#location_id').on('change',function(){
      location_id = $(this).val();
      url = '<?php echo site_url().$controller_name."/list_all_items/";?>'+location_id;
      console.log(url);
      $('#excelExport').attr('href',url);
    });
    $('#filterItems').on('click', function(){
      var location_id = $('#location_id').val();
      var category = $('#category2').val();
      console.log('location_id', location_id);
      if(location_id != null && category != "")
      {
        $('#table_area').html('<img src="<?php echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');
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
        $.post('<?php echo site_url($controller_name."/list_filtered_items/");?>'+location_id, {'filter': filterData}, function(data) {
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
      $('#table_area').html('<img src="<?php echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');
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
      $('#table_area').html('<img src="<?php echo base_url('images/pacman-loader.gif'); ?>" alt="loading" />');
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
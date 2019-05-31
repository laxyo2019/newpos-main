<?php $this->load->view("partial/header"); 

$stock_locations = $this->Stock_location->get_allowed_locations();
$mci_data = $this->Item->get_mci_data('all');
?>

<div class="row">
  <span class="col-md-12">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> List Actions 
  </div>

<div class="row">
  <div class="form-group col-md-6">
    <label>Locations</label>
    <select class="form-control" id="location_id">
    <option value="all">ALL LOCATIONS</option>
      <?php foreach($stock_locations as $key=>$value): ?>
        <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label>Report Type</label>
      <select class="form-control" id="report_type">
          <option value=''>Select</option>
          <option value="type_1">All Items</option>
          <option value="type_2">Filter Items</option>
      </select>
  </div>
</span>
  
</div>
<div class="row" id="extraMci21" style="display:none">
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
<div class="row" style="margin-bottom:20px;">
  <div class="col-md-12 text-center  ">
    <button class="btn btn-md btn-info" style="width:70px;" id="get_button">Get</button>
  </div>
</div>
<div id="table_area"></div>


<script>
$(document).ready( function () {
  $('#report_type').on('change',function(){
      var level1 =$(this).val();
      if(level1 == "type_1" || level1 == '') {
        $("#table_area").html('');
        $("#category2 option:selected").prop('selected' , false);
        $("#subcategory2 option:selected").prop('selected' , false);
        $('#subcategory2').html('');
      }
  });
 
  $('#category2').on('change',function(){
      var level1 = $(this).val();
      var wearables = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
      $('#extraMci2').toggle(wearables.includes(level1));
     
      if(level1){
        $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category': level1}, function(data) {
          $('#subcategory2').html(data);
        });      
      }
      if(level1==""){
        $.post('<?php echo site_url("items/ajax_fetch_subcategories") ?>',{},function(data){
                $('#subcategory2').html(data);
           });
      }
  });

  $('#report_type').on('change',function(){
      if ( this.value == 'type_2')
      {
        $("#extraMci21").show();
      }
      else
      {
        $("#extraMci21").hide();
        $("#extraMci2").hide();
      }
  });
    
  $('#get_button').on('click',function(){
      var selected_location = $('#location_id').val(); 
      var report_type = $('#report_type').val();
      var category = $('#category2').val();
      var wearables = ["MEN'S CLOTHING", "WOMEN'S CLOTHING", "KID'S CLOTHING", "MEN'S FOOTWEAR", "WOMEN'S FOOTWEAR", "KID'S FOOTWEAR"];
      if(jQuery.inArray(category, wearables) != -1){
          var mci = {
            'category': $('#category2').val(),
            'subcategory': $('#subcategory2').val(),
            'brand': $('#brand2').val(),
            'size': $('#size2').val(),
            'color': $('#color2').val(),
            };
      }else{
          var mci = {
          'category': $('#category2').val(),
          'subcategory': $('#subcategory2').val(),
          'brand': $('#brand2').val()
          };
      }
      
      if(selected_location == ''){

      }
      else{
          if(report_type =='type_1' ){
            if(selected_location !='all'){
              location.href = '<?php echo site_url().$controller_name."/list_all_items/";?>'+selected_location;
            }
            else {
              location.href = '<?php echo site_url().$controller_name."/list_all_locations/";?>'+selected_location;
            }
            }
          else if(category !='' && report_type =='type_2' && mci !=''){
            $('#table_area').html("<p class= 'text-muted' style='text-align:center; font-size:22px;'> Loading... </p>");
                var category = $('#category2').val();
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
                $.post('<?php echo site_url($controller_name."/list_filtered_items/");?>'+selected_location, {'filter': filterData}, function(data) {
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
          else {
            swal({
						title: "",
						text: 'Select Filter',
						icon: "error",
					});
          }
      } 
  });
});
</script>






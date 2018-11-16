<div class="row">
  <h3 class="text-center">Create Plan</h3>

  <div class="col-md-12">
    <div class="form-inline">
      <div class="form-group">
        <select id="bg_category">
          <option value="">Select Category</option>
          <?php
            foreach($mci_data['categories'] as $row)
            {
              echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <select id="bg_subcategory">
          <option value="">Select Subcategory</option>
          <?php
            foreach($mci_data['subcategories'] as $row)
            {
              echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <select id="bg_brand">
          <option value="">Select Brand</option>
          <?php
            foreach($mci_data['brands'] as $row)
            {
              echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <select id="bg_count">
          <option value="">Item Count</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
      <div class="form-group">
        <input type="number" id="bg_value">
      </div>
      <button id="create_bogo" type="submit" class="btn btn-sm btn-success">Submit</button>
    </div>
  </div>

</div>
<hr>

<div id="active_bogo_window">
  <div class="col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Active Plans</h3>
      </div>

      <?php foreach($this->db->where('deleted', 0)->get('special_bogo')->result_array() as $row){ ?>
        <div class="panel-body">
          <?php echo $row['category'].' | '.$row['subcategory'].' | '.$row['brand'].' | '.$row['bogo_count'].' | '.$row['bogo_val'];?>
          <div class="pull-right">
            <a href='<?php echo site_url($controller_name."/edit_bogo/".$row['id']); ?>' title='Edit BOGO' class='modal-dlg'><span class="glyphicon glyphicon-edit"></span></a>
          </div>
        </div>
      <?php } ?> 
    </div>
  </div>
</div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('#bg_category').on('change', function(){
      var level1 = $(this).val();
      if(level1){
          $.post('<?php echo site_url("items/ajax_fetch_subcategories");?>', {'category' : level1}, function(data) {
            $('#bg_subcategory').html(data);
        }); 
      }else{
          $('#bg_subcategory').html('<option value="">Loading...</option>');
      }
    });

    $('#create_bogo').on('click', function(){
      var insert_data = {
        category: $('#bg_category').val(),
        subcategory: $('#bg_subcategory').val(),
        brand: $('#bg_brand').val(),
        bogo_count: $('#bg_count').val(),
        bogo_val: $('#bg_value').val()
      };

			$.post('<?php echo site_url($controller_name."/save_bogo"); ?>', {'insert_data': insert_data}, function(data) {
        $.post('<?php echo site_url($controller_name."/active_bogo_window"); ?>', {}, function(data) {
          $('#active_bogo_window').html(data);
			  });
        
			});
		});

	});
</script>
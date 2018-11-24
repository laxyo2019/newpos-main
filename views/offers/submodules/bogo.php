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
        <input type="number" placeholder="Enter Amt." id="bg_fp">
      </div>
      <div class="form-group">
        <input type="number" placeholder="Enter Amt." id="bg_value">
      </div>
      <button id="bogo_submit" type="submit" class="btn btn-sm btn-success">Submit</button>
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

      <?php foreach($this->db->get('special_bogo')->result_array() as $row){ ?>
        <div class="panel-body">
          <a href="<?php echo site_url($controller_name."/edit_bogo/".$row['id']); ?>" title="Edit" class="modal-dlg"><?php echo $row['category'].' | '.$row['subcategory'].' | '.$row['brand'].' | '.$row['bogo_fp'].' | 2 | '.$row['bogo_val']; ?></a>

          <span class="pull-right" id="<?php echo $row['id']; ?>">
            <style>
              .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
              .toggle.ios .toggle-handle { border-radius: 20px; }
            </style>
            <input type="checkbox" class="bogo_toggle" <?php echo $row['status'] ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-style="ios" data-size="mini" />
          </span>
        </div>
      <?php } ?> 
    </div>
  </div>
</div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg, a.modal-dlg");

    $('.bogo_toggle').bootstrapToggle();

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

    $('.bogo_toggle').on('change', function(){
      var id = $(this).closest('span').attr('id');
      var status = $(this).prop('checked');
      $.post('<?php echo site_url($controller_name."/bogo_toggle"); ?>', {'id': id, 'status': status}, function(data) {
				console.log(data);
      });
    });

    $('#bogo_submit').on('click', function(){

      var category = $('#bg_category').val();
      var subcategory= $('#bg_subcategory').val();
      var brand= $('#bg_brand').val();
      var bogo_fp= $('#bg_fp').val();
      var bogo_val= $('#bg_value').val();

			$.post('<?php echo site_url($controller_name."/save_bogo"); ?>', {
          'category': category,
          'subcategory': subcategory,
          'brand': brand,
          'bogo_fp': bogo_fp,
          'bogo_val': bogo_val,
        }, function(data) {
        $.post('<?php echo site_url($controller_name."/active_bogo_window"); ?>', {}, function(data) {
          $('#active_bogo_window').html(data);
			  });
        
			});
		});

	});
</script>
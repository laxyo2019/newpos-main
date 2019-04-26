<?php $this->load->view("partial/header"); ?>
<div class="row">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> Extras 
  </div>
  <div class="col-md-4">
    <button class='btn btn-info btn-sm modal-dlg col-md-6 col-md-offset-3' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_convert"); ?>'
              title='Quick Transfer/Billing'>
          Quick Convert
    </button>
    <select id="extSwitch" class="form-control">
      <option value="">Select an Option</option>
      <option value="active_items">Active Items</option>
      <option value="deleted_items">Deleted Items</option>
    </select>
  </div>

  <?php if($this->Item->is_superadmin()){ ?>
    <div class="col-md-4">
      <button class='btn btn-info btn-sm modal-dlg col-md-6 col-md-offset-3' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_taxes"); ?>'
                title='Fetch Item Taxes'>
            Tax me up!
      </button>
      <select id="extSwitch1" class="form-control">
        <option value="">Select an Option</option>
        <option value="items_taxes">Show Item Taxes</option>
      </select>
    </div>
  <?php } ?>

  <!-- <div class="col-md-4">
    <button class='btn btn-info btn-sm modal-dlg col-md-6 col-md-offset-3' data-btn-submit='<?php //echo $this->lang->line('common_submit') ?>' data-href='<?php //echo site_url($controller_name."/quick_prices"); ?>'
              title='Fetch Item Prices'>
          Get Prices
    </button>
    <select id="extSwitch2" class="form-control">
      <option value="">Select an Option</option>
      <option value="items_prices">Show Item Prices</option>
    </select>
  </div> -->

</div>
<hr>

<div id="extras_table_area"></div>

<script>
  $(document).ready(function(){
    dialog_support.init("button.modal-dlg");

    $('#extSwitch').on('change', function(){
      var extSwitch = $(this).val();
      $.post('<?php echo site_url($controller_name."/get_processed_list");?>', {'type': extSwitch}, function(data) {
          $('#extras_table_area').html(data);
          $('#extras_sublist').DataTable({
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
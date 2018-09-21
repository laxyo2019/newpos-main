<div class="row">
  <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_conversion"); ?>'
            title='Excel Conversion'>
        Excel Conversion
  </button>

  <span class="col-md-2">
    <select id="extSwitch" class="form-control">
      <option value="">Select an Option</option>
      <option value="active_items">Active Items</option>
      <option value="deleted_items">Deleted Items</option>
    </select>
  </span>
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
<hr>
<div class="row">
  <span class="col-md-3">
		<select id="bulk_action_report" class="form-control">
			<option value="">-- Select Report --</option>
			<option value="bulk_hsn">Bulk HSN</option>
			<option value="bulk_discount">Bulk Discount</option>
		</select>
	</span>
  <span class="col-md-4 pull-right">
    <button class='btn btn-info btn-sm modal-dlg' data-href='<?php echo site_url($controller_name."/bulk_hsn_view"); ?>'
            title='Bulk HSN Update'>
        </span>Bulk HSN
    </button>
    <button class='btn btn-info btn-sm modal-dlg' data-href='<?php echo site_url($controller_name."/bulk_discount_view"); ?>'
            title='Bulk Discount Update'>
        </span>Bulk Discounts
    </button>
    <button class='btn btn-info btn-sm modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_bulk_discount"); ?>'
              title='Quick Discount Update'>
            Quick Discounts
    </button>
</div>
<hr>
<div id="bulk_table_area"></div>

<footer>
  <p class="text-danger">Please use this option cautiously. As the database is centralized, any bulk changes made in Tax, HSN and Discount will reflect on all active shops.</p>
</footer>

<script>
	$(document).ready(function(){

		$('#bulk_action_report').on('change', function(){
			var report_type = $('#bulk_action_report').val();
      $('#bulk_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/bulk_action_report") ?>', {'report_type': report_type}, function(data) {
	        $('#bulk_table_area').html(data);
          $('#bulk_action_list').DataTable({
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


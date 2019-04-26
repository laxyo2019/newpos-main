<?php $this->load->view("partial/header"); ?>
<?php if($this->Item->check_auth(array('superadmin', 'admin'))){ ?>
<div class="row">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> Bulk Actions 
  </div>
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
        Bulk HSN
    </button>
    
    <button class='btn btn-info btn-sm modal-dlg' data-href='<?php echo site_url($controller_name."/bulk_discount_view"); ?>'
            title='Bulk Discount Update'>
        Bulk Discounts
    </button>
    <button class='btn btn-info btn-sm modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_bulk_discount"); ?>'
              title='Quick Discount Update'>
            Quick Discounts
    </button>
    </span>
</div>
<hr>
<div id="bulk_table_area"></div>

<footer>
  <p class="text-danger">Please use this option cautiously. As the database is centralized, any bulk changes made in Tax, HSN and Discount will reflect on all active shops.</p>
</footer>
<?php } else{ ?>
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'>Manager </a>>> Reports 
  </div>
<div class='text-danger'><h3 class='text-center'> Persmission Denied</h3></div>
<?php } ?>
<script>
	$(document).ready(function(){
    dialog_support.init("button.modal-dlg");
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

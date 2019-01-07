<?php $this->load->view("partial/header"); ?>
<hr>
<div class="row">
  <span class="col-md-6">
     <button class="btn btn-sm btn-primary" id="get_sales">Get Sales</button>
  </span>
  <span class="pull-right">
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
   </span>
</div>
<hr>
<div id="report_table_area"></div>
<script>
	$(document).ready( function () {
    $('#filters').on('hidden.bs.select', function(e)
	{
    table_support.refresh();
    });
	
  
	
    //$('#report_locations').select2();
    <?php $this->load->view('partial/daterangepicker'); ?>
    
    $("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});
    $('#get_sales').on('click', function(){
      $('#report_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');

      $.post('<?php echo site_url($controller_name."/get_sale");?>', 
      {'start_date': start_date, 'end_date': end_date}, function(data) {
          $('#report_table_area').html(data);
          $('#report_list').DataTable({
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


<?php $this->load->view("partial/footer"); ?>


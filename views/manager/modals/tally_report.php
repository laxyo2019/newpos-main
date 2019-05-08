<?php $this->load->view("partial/header"); ?>
<h5 style="text-align: center; text-decoration-line: underline;">Sales Report - Tally Format</h5>
<br>
<div class="row">
  <span class="col-md-6">
    <a class="btn btn-sm btn-primary" id='Excel_file' href=''>Get Sales</a>

    <input id='hidden_link' value='<?php echo site_url($controller_name."/tally_format");?>' type='hidden'>
  </span>
  <span class="pull-right">
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
  </span>
</div>
<hr>

<?php $this->load->view("partial/footer"); ?>
<script>
	$(document).ready( function () {

    <?php $this->load->view('partial/daterangepicker'); ?>

    url = $('#hidden_link').val()+'/'+start_date+'/'+end_date;
      $('#Excel_file').attr('href',url);

    $('#report_locations').select2();
   

    $('#daterangepicker').on('change',function(){
      url = $('#hidden_link').val()+'/'+start_date+'/'+end_date;
      $('#Excel_file').attr('href',url);
      console.log(url);
    });

	});
</script>

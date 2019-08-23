<?php $this->load->view("partial/header");?>
<h5 style="text-align: center; text-decoration-line: underline;">Sales Report - Tally Format</h5>
<br>
<div class="row">
	<select id="filter_category" class="">
				<option value="">Select Category</option>
				<?php
					foreach ($mci_data['categories'] as $row) { ?>
						<option value = "<?php echo $row['name'] ; ?>"><?php echo $row['name'] ; ?></option>
			<?php	}
				 ?>
	</select>
	<select id="filter_subcategory" class="">
				<option value="">Select Subcategory</option>
	</select>
	<select id="filter_brand" class="">
				<option value="">Select Brand</option>
				<?php
					foreach ( $mci_data['brands'] as $row) { ?>
						<option value = "<?php echo $row['name'] ; ?>"><?php echo $row['name'] ; ?></option>
			<?php	}
				 ?>
	</select>
  <span class="col-md-6" style="margin-top:15px">
    <a class="btn btn-sm btn-primary" id='Excel_file' href='' target="_blank">Get Sales</a>
		 <a class="btn btn-sm btn-primary" id='data_table' href='javascript:void(0)' >Get Sales</a>
    <input id='hidden_link' value='<?php echo site_url($controller_name."/tally_format");?>' type='hidden'>
  </span>
  <span class="pull-right">
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
  </span>
</div>
<hr>
<div id="table_holder" style="width:100%;overflow-x:auto">

</div>

<?php $this->load->view("partial/footer"); ?>
<script>
	$(document).ready( function () {
		$('#data_table').hide();
		 $('#filter_category,#filter_subcategory,#filter_brand').on('change',function(){
			var category = $('#filter_category').val();
   		var subcategory = $('#filter_subcategory').val();
   		var brand = $('#filter_brand').val();
		 	if(category == '' && subcategory == '' && brand ==''){
		 		$('#data_table').hide();
		 		$('#Excel_file').show();
		 	}else{
		 		$('#data_table').show();
		 		$('#Excel_file').hide();
		 	}
		 });
		$('#filter_category').on('change',function(){
			category = $(this).val();
			if(category!=''){
				$.post('<?php echo site_url("manager/get_subcategory"); ?>',{'category':category},function(data){
					$('#filter_subcategory').html(data);
				})
			}else{
				var data = '<option value="">Select Subcategory</option>';
				 $('#filter_subcategory').html(data);
			}
		});
		$('#filter_category, #filter_subcategory, #filter_brand').select2();
    <?php $this->load->view('partial/daterangepicker'); ?>

    url = $('#hidden_link').val()+'/'+start_date+'/'+end_date;
      $('#Excel_file').attr('href',url);

    $('#report_locations').select2();
   	$('#data_table').on('click',function(){
   		var category = $('#filter_category').val();
   		var subcategory = $('#filter_subcategory').val();
   		var brand = $('#filter_brand').val();
   			$.post('<?php echo site_url("manager/get_filtered_sales"); ?>',
   				{
   					'category':category,
   					'subcategory':subcategory,
   					'brand':brand,
   					'start_date':start_date,
   					'end_date':end_date
   				},
   				function(data){
					$('#table_holder').html(data);
				})
   	});

    $('#daterangepicker').on('change',function(){
      url = $('#hidden_link').val()+'/'+start_date+'/'+end_date;
      $('#Excel_file').attr('href',url);
      console.log(url);
    });

	});
</script>

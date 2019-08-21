<script type="text/javascript">
	$(document).ready( function () {
    	$('#table_custom').DataTable({
    		"pageLength": 50 ,
    		'bAutoWidth': true,
    		'bSortClasses': true,
    		"bPaginate": true,
    		"orderable": false,
        	"bFilter": true,
        	"bInfo": true
        });
        
	});
	
   
</script>

<div style="margin-bottom: 24px; text-align: end;padding-bottom: 50px;border-bottom: 1px solid #dce4ec;">

	<select style=" margin-right: 14px;padding: 7px 26px;background-color: #fff;color: #000;border: 2px solid #dce4ec;border-radius: 4px;" id="custom_field" class="show-menu-arrow custom_field" name="custom_field" data-style="btn-default btn-sm" data-width="fit" tabindex="-98">
		<option>Select Tag..</option>
	<?php 
		 $data = $this->Offers_manage->get_custom_data();

		 foreach($data as $row){ ?>
			<option value="<?php echo $row->tag; ?>"><?php echo strtoupper($row->tag); ?></option>
	<?php 	}
	?>

	</select>
	<span class="pull-right add_tag_btn">		
		<button id="add_tag" class='btn btn-info btn-sm modal-dlg-1' data-href='<?php echo site_url($controller_name."/create_tag"); ?>' title='Add Tag'>
        Add New
    </button>

    </span>
</div>
<div class="custom_field_tbl"></div>

<script type="text/javascript">
	dialog_support.init("button.modal-dlg-1");
	$("tr:odd").css("background-color", "#f9f9f9");
	 $(document).on('change','#custom_field',function(){
	 	var id = $('.custom_field').val();
	 	 $.post('<?php echo site_url($controller_name."/get_custom_tags"); ?>',{'id':id},
	 	 	function(data){
	 	 		$('.custom_field_tbl').html(data);
		});
	});

	  // $(document).on('click','#add_tag',function(){
	  // 	var tag_name = $('#custom_field').val();
	
	 	// //dialog_support.init("button.modal-dlg");
	 	// if(tag_name == 'Select Tag..' || tag_name == ''){
	  // 		tag_name = ''; 
	  // 	}
		
	  //  $.post('<?php //echo site_url($controller_name."/create_tag"); ?>',{'tag_name':tag_name},
		 	
	  //  		function(data){
	  //  			dialog_support.init("button.modal-dlg-1");
	  //  		});
	  // });

	//  $(document).on('click','#add_tag', function () {
	//  	var tag_name =  $('#custom_field').val();
	// 	$.post('<?php //echo site_url($controller_name."/create_tag"); ?>',{'tag_name':tag_name},
	// 	 	dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide"),
	//   		function(data){

	//   		});
	// })

	 // $(document).on('change','#custom_field',function () {
	 // 	var id = $('#custom_field').val();
	 // 	$("#add_tag").attr("data-href", "");
	 // 	$("#add_tag").attr("data-href", "<?php //echo site_url("offers/create_tag/"); ?>"+id);
	 // });
</script>

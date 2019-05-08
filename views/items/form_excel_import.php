<?php
//Fetch Sheet Uploaders
	$this->db->select('id,title');
	$sheet_uploaders = $this->db->get_where('custom_fields',array('tag'=>'sheet_uploader','status'=>1))->result();
?>
<ul id="error_message_box" class="error_message_box"></ul>
<?php echo form_open_multipart('items/do_excel_import2/', array('id'=>'excel_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">
		<div class="form-group form-group-sm">
			<div class="col-xs-4">
				<label>Sheet Uploader</label>
			</div>
			<div class="col-xs-8">
				<select class='form-control'  name='sheet_uploader' required>
					<option value=''>Select Name</option>
					<?php foreach($sheet_uploaders as $sheet_uploader): ?>
					<option value='<?php echo $sheet_uploader->id; ?>'><?php echo $sheet_uploader->title; ?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<div class="col-xs-4">
				<label>Password</label>
			</div>
			<div class="col-xs-8">
				<input class='form-control' type='password' name='password' required>
			</div>
		</div>
		<div class="form-group form-group-sm">
			<div class="col-xs-4">
				<label>Function</label>
			</div>
			<div class="col-xs-8">
					<select name="sheet_type">
						<option value=''>-- Select --</option>
						<option value='new_stock'>Excel Import</option>
						<!--<option value='update_stock'>Excel Update</option>-->
						<option value='undelete_stock'>Excel Undelete</option>
					</select>
			</div>
		</div>
		<div class="form-group form-group-sm">	
			<div class="col-xs-12">
				<a href="<?php echo site_url('items/excel'); ?>"><?php echo $this->lang->line('common_download_import_template'); ?></a>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<div class='col-xs-12'>
				<div class="fileinput fileinput-new input-group" data-provides="fileinput">
					<div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i><span class="fileinput-filename"></span></div>
					<span class="input-group-addon input-sm btn btn-default btn-file"><span class="fileinput-new"><?php echo $this->lang->line("common_import_select_file"); ?></span><span class="fileinput-exists"><?php echo $this->lang->line("common_import_change_file"); ?></span><input type="file" id="file_path" name="file_path" accept=".csv"></span>
					<a href="#" class="input-group-addon input-sm btn btn-default fileinput-exists" data-dismiss="fileinput"><?php echo $this->lang->line("common_import_remove_file"); ?></a>
				</div>
			</div>
		</div>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{	
	$('#excel_form').validate($.extend({
		submitHandler:function(form) {
			sheet_uploader_id = $('[name="sheet_uploader"]').val();
			pwd = $('[name="password"]').val();
			sheet_type = $('[name="sheet_type"]').val();
			$.post('<?php echo site_url();?>items/verify_sheet_uploader',{sheet_uploader_id:sheet_uploader_id,pwd:pwd},function(data){
				if(data==1){
					if(sheet_type=='undelete_stock'){
						new_url ="<?php echo site_url();?>items/do_excel_undelete";
						$('form').attr('action',new_url);
					}
					$(form).ajaxSubmit({
						success:function(response)
						{
							dialog_support.hide();
							$.notify(response.message, { type: response.success ? 'success' : 'danger'} );
						},
						dataType: 'json'
					});
				}else{
					// alert('Incorrect Password');
					swal({
						title: "",
						text: 'Incorrect Password',
						icon: "error",
					});
				}
			});
			
			// 
		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			file_path: "required",
			password: "required",
			sheet_uploader: "required",
			sheet_type:"required",
   		},
		messages: 
		{
   			file_path: "<?php echo $this->lang->line('common_import_full_path'); ?>",
			password: "Password is required",
			sheet_uploader: "Sheet Uploader's Name is required",
			sheet_type: "Function required"
		}
	}, form_support.error));
});
</script>

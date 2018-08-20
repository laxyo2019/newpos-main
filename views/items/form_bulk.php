<div id="required_fields_message"><?php echo $this->lang->line('items_edit_fields_you_want_to_update'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('items/bulk_update/', array('id'=>'item_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="bulk_item_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_name'), 'name', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'name',
						'id'=>'name',
						'class'=>'form-control input-sm')
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_category'), 'category', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('category', $categories, 'test', array('class'=>'form-control','id'=>'level1')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_subcategory'), 'subcategory', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<select name="subcategory" class="form-control" id="level2">
						
				</select>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Brand', 'brand', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('brand', $brands, 'test', array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Size', 'custom2', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('custom2', $sizes, 'test', array('class'=>'form-control')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Color', 'custom3', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('custom3', $colors, 'test', array('class'=>'form-control')); ?>
			</div>
		</div>
		<!-- Removed allow alt desc and serialize divs from here -->
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#level1').on('change',function(){
		var level1 = $(this).val();
		// console.log(val);
		if(level1){
			  $.post('<?php echo site_url($controller_name."/ajax_fetch_subcategories");?>', {'category' : level1}, function(data) {
	        $('#level2').html(data);
      }); 
		}else{
				$('#level2').html('<option value="">Loading...</option>');
		}
	});
	// $("#category").autocomplete({source: "<?php //echo site_url('items/suggest_category');?>", appendTo:'.modal-content', delay:10});

	var confirm_message = false;
	$("#tax_percent_name_2, #tax_name_2").prop('disabled', true),
	$("#tax_percent_name_1, #tax_name_1").blur(function() {
		var disabled = !($("#tax_percent_name_1").val() + $("#tax_name_1").val());
		$("#tax_percent_name_2, #tax_name_2").prop('disabled', disabled);
		confirm_message =  disabled ? "" : "<?php echo $this->lang->line('items_confirm_bulk_edit_wipe_taxes') ?>";
	});

	$('#item_form').validate($.extend({
		submitHandler:function(form)
		{
			if(!confirm_message || confirm(confirm_message))
			{
				$(form).ajaxSubmit({
					beforeSubmit: function(arr, $form, options) {
						arr.push({name: 'item_ids', value: table_support.selected_ids().join(": ")});
					},
					success:function(response)
					{
						dialog_support.hide();
						table_support.handle_submit('<?php echo site_url('items'); ?>', response);
					},
					dataType:'json'
				});
			}
		},
		rules:
		{
			unit_price:
			{
				number:true
			},
			tax_percent:
			{
				number:true
			},
			quantity:
			{
				number:true
			},
			reorder_level:
			{
				number:true
			}
		},
		messages:
		{
			unit_price:
			{
				number: "<?php echo $this->lang->line('items_unit_price_number'); ?>"
			},
			tax_percent:
			{
				number: "<?php echo $this->lang->line('items_tax_percent_number'); ?>"
			},
			quantity:
			{
				number: "<?php echo $this->lang->line('items_quantity_number'); ?>"
			},
			reorder_level:
			{
				number: "<?php echo $this->lang->line('items_reorder_level_number'); ?>"
			}
		}
	}, form_support.error));
});
</script>

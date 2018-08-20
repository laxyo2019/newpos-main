<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<div id="item-suggestions"></div>

<?php echo form_open('items/save/'.$item_info->item_id, array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">

		<?php echo form_hidden('item_number', $item_info->item_number); ?>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_name'), 'name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'name',
						'id'=>'name',
						'class'=>'form-control input-sm',
						'value'=>$item_info->name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_category'), 'category', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('category', $categories, $selected_category, array('class'=>'form-control','id'=>'level1')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Subcategory', 'subcategory', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('subcategory', $subcategories, $selected_subcategory, array('class'=>'form-control','id'=>'level2')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Brand', 'brand', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('brand', $brands, $selected_brand, array('class'=>'form-control')); ?>
			</div>
		</div>

		<!-- WHOLESALE PRICE COLUMN REMOVED FROM HERE -->

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_unit_price'), 'unit_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<div class="input-group input-group-sm">
					<?php if (!currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
					<?php echo form_input(array(
							'name'=>'unit_price',
							'id'=>'unit_price',
							'class'=>'form-control input-sm',
							'value'=>to_currency_no_money($item_info->unit_price))
							);?>
					<?php if (currency_side()): ?>
						<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_tax_1'), 'tax_percent_1', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'tax_names[]',
						'id'=>'tax_name_1',
						'class'=>'form-control input-sm',
						'readonly'=>'true',
						'value'=>$this->config->item('default_tax_1_name'))
						);?>
			</div>
			<div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php echo form_input(array(
							'name'=>'tax_percents[]',
							'id'=>'tax_percent_name_1',
							'class'=>'form-control input-sm',
							// 'value'=>to_tax_decimals($item_tax_info[0]['percent'])
							'value'=>$item_tax_info[0]['percent']
							));?>
					<span class="input-group-addon input-sm"><b>%</b></span>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_tax_2'), 'tax_percent_2', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'tax_names[]',
						'id'=>'tax_name_2',
						'class'=>'form-control input-sm',
						'readonly'=>'true',
						'value'=>$this->config->item('default_tax_2_name'))
						);?>
			</div>
			<div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php echo form_input(array(
							'name'=>'tax_percents[]',
							'class'=>'form-control input-sm',
							'id'=>'tax_percent_name_2',
							// 'value'=>to_tax_decimals($item_tax_info[1]['percent'])
							'value'=>$item_tax_info[1]['percent']
							));?>
					<span class="input-group-addon input-sm"><b>%</b></span>
				</div>
			</div>
		</div>

		<?php if($customer_sales_tax_enabled) { ?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('taxes_tax_category'), 'tax_category', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_dropdown('tax_category_id', $tax_categories, $selected_tax_category, array('class'=>'form-control')); ?>
				</div>
			</div>
		<?php } ?>

		<?php //echo json_encode($stock_locations); ?>

		<?php if($this->Item->is_both()) { ?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('items_quantity').' '.$stock_locations[4]['location_name'], 'quantity_4', array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'quantity_4',
							'id'=>'quantity_4',
							'class'=>'required quantity form-control',
							'value'=>isset($item_info->item_id) ? to_quantity_decimals($stock_locations[4]['quantity']) : to_quantity_decimals(0))
							);?>
				</div>
			</div>
		<?php } ?>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_receiving_quantity'), 'receiving_quantity', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'receiving_quantity',
						'id'=>'receiving_quantity',
						'class'=>'required form-control input-sm',
						'value'=>isset($item_info->item_id) ? to_quantity_decimals($item_info->receiving_quantity) : to_quantity_decimals(0))
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_reorder_level'), 'reorder_level', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'reorder_level',
						'id'=>'reorder_level',
						'class'=>'form-control input-sm',
						'value'=>isset($item_info->item_id) ? to_quantity_decimals($item_info->reorder_level) : to_quantity_decimals(0))
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_description'), 'description', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$item_info->description)
						);?>
			</div>
		</div>

	<!-- Allow alternate description and serialized checkbox code removed from here -->

	<?php if($this->Item->is_superadmin()) { ?>
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_is_deleted'), 'is_deleted', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox(array(
						'name'=>'is_deleted',
						'id'=>'is_deleted',
						'value'=>1,
						'checked'=>($item_info->deleted) ? 1 : 0)
						);?>
			</div>
		</div>
	<?php } ?>	
		
		<?php
		for ($i = 1; $i <= 10; ++$i)
		{
		?>
			<?php
			if($this->config->item('custom'.$i.'_name') != NULL)
			{
				$item_arr = (array)$item_info;
			?>
				<div class="form-group form-group-sm">
					<?php echo form_label($this->config->item('custom'.$i.'_name'), 'custom'.$i, array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'custom'.$i,
								'id'=>'custom'.$i,
								'class'=>'form-control input-sm',
								'value'=>$item_arr['custom'.$i])
								);?>
					</div>
				</div>
		<?php
			}
		}
		?>
		
		<hr>
		<p style="text-align:center; font-weight:bold; font-size: 1.2em; color:#9C27B0">Discounts</p>
		<?php foreach($custom_discounts as $row) : ?>
			<?php $name = $row['name']; ?>
			<div class="form-group form-group-sm">
				<?php echo form_label(strtoupper($row['name']), 'ds'.$row['id'], array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=> 'ds'.$row['id'],
							'id'=> 'ds'.$row['id'],
							'class'=>'form-control input-sm',
							'value' => json_decode($item_info->discounts)->$name
							)
					);?>
				</div>
			</div>
		<?php endforeach; ?>	
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
	$('#name').on('blur', function(){
		var name = $(this).val();
		$.ajax({
			type: 'GET',
			url: '<?php echo site_url('items/checkforitems'); ?>',
			data: {'name': name},
			success: function(data){
				$('#item-suggestions').html(data);
			}
		});
	});

	$('#item-suggestions').off().on('click', '.item-data', function(){
		var v = $(this).text();
		$('#name').val(v);
	});

	$('#level1').on('change',function(){
		var level1 = $(this).val();
		// console.log(val);
		if(level1){
				$.ajax({
						type:'GET',
						url:'<?php echo site_url()."/items/fetchsubs" ?>',
						data: {cat_id : level1},
						success:function(data){
								$('#level2').html(data);
						}
				}); 
		}else{
				$('#level2').html('<option value="">Select category first</option>');
		}
	});

	$("#new").click(function() {
		stay_open = true;
		$("#item_form").submit();
	});

	$("#submit").click(function() {
		stay_open = false;
	});

	var no_op = function(event, data, formatted){};
	$("#category").autocomplete({source: "<?php echo site_url('items/suggest_category');?>",delay:10,appendTo: '.modal-content'});

	<?php for ($i = 1; $i <= 10; ++$i)
	{
	?>
		$("#custom" + <?php echo $i; ?>).autocomplete({
			source:function (request, response) {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('items/suggest_custom');?>",
					dataType: "json",
					data: $.extend(request, $extend(csrf_form_base(), {field_no: <?php echo $i; ?>})),
					success: function(data) {
						response($.map(data, function(item) {
							return {
								value: item.label
							};
						}))
					}
				});
			},
			delay: 10,
			appendTo: '.modal-content'});
	<?php
	}
	?>

	$("a.fileinput-exists").click(function() {
		$.ajax({
			type: "GET",
			url: "<?php echo site_url("$controller_name/remove_logo/$item_info->item_id"); ?>",
			dataType: "json"
		})
	});

	$('#item_form').validate($.extend({
		submitHandler: function(form, event) {
			$(form).ajaxSubmit({
				success: function(response) {
					var stay_open = dialog_support.clicked_id() != 'submit';
					if (stay_open)
					{
						// set action of item_form to url without item id, so a new one can be created
						$("#item_form").attr("action", "<?php echo site_url("items/save/")?>");
						// use a whitelist of fields to minimize unintended side effects
						$(':text, :password, :file, #description, #item_form').not('.quantity, #reorder_level, #tax_name_1,' +
							'#tax_percent_name_1, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price').val('');
						// de-select any checkboxes, radios and drop-down menus
						$(':input', '#item_form').not('#item_category_id').removeAttr('checked').removeAttr('selected');
					}
					else
					{
						dialog_support.hide();
					}
					table_support.handle_submit('<?php echo site_url('items'); ?>', response, stay_open);
				},
				dataType: 'json'
			});
		},

		rules:
		{
			name: "required",
			category: "required",
			item_number:
			{
				required: false,
				remote:
				{
					url: "<?php echo site_url($controller_name . '/check_item_number')?>",
					type: "post",
					data: $.extend(csrf_form_base(),
					{
						"item_id": "<?php echo $item_info->item_id; ?>",
						"item_number": function()
						{
							return $("#item_number").val();
						},
					})
				}
			},
			cost_price:
			{
				required: true,
				remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
			},
			unit_price:
			{
				required: true,
				remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
			},
			<?php
			foreach($stock_locations as $key=>$location_detail)
			{
			?>
				<?php echo 'quantity_' . $key ?>:
				{
					required: true,
					remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
			<?php
			}
			?>
			receiving_quantity:
			{
				required: true,
				remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
			},
			reorder_level:
			{
				required: true,
				remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
			},
			tax_percent:
			{
				required: true,
				remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
			}
		},

		messages:
		{
			name: "<?php echo $this->lang->line('items_name_required'); ?>",
			item_number: "<?php echo $this->lang->line('items_item_number_duplicate'); ?>",
			category: "<?php echo $this->lang->line('items_category_required'); ?>",
			cost_price:
			{
				required: "<?php echo $this->lang->line('items_cost_price_required'); ?>",
				number: "<?php echo $this->lang->line('items_cost_price_number'); ?>"
			},
			unit_price:
			{
				required: "<?php echo $this->lang->line('items_unit_price_required'); ?>",
				number: "<?php echo $this->lang->line('items_unit_price_number'); ?>"
			},
			<?php
			foreach($stock_locations as $key=>$location_detail)
			{
			?>
				<?php echo 'quantity_' . $key ?>:
				{
					required: "<?php echo $this->lang->line('items_quantity_required'); ?>",
					number: "<?php echo $this->lang->line('items_quantity_number'); ?>"
				},
			<?php
			}
			?>
			receiving_quantity:
			{
				required: "<?php echo $this->lang->line('items_quantity_required'); ?>",
				number: "<?php echo $this->lang->line('items_quantity_number'); ?>"
			},
			reorder_level:
			{
				required: "<?php echo $this->lang->line('items_reorder_level_required'); ?>",
				number: "<?php echo $this->lang->line('items_reorder_level_number'); ?>"
			},
			tax_percent:
			{
				required: "<?php echo $this->lang->line('items_tax_percent_required'); ?>",
				number: "<?php echo $this->lang->line('items_tax_percent_number'); ?>"
			}
		}
	}, form_support.error));
});
</script>

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
						<!-- <div id="livesearch"></div> -->
			</div>

		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_category'), 'category', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('category', $categories, $selected_category, array('class'=>'form-control','id'=>'level1')); ?>
			</div>
		</div>


		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_subcategory'), 'subcategory', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('subcategory', $subcategories, $selected_subcategory, array('class'=>'form-control','id'=>'level2')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Brand', 'brand', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('brand', $brands, $selected_brand, array('class'=>'form-control', 'id'=>'brand')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Size', 'custom2', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('custom2', $sizes, $selected_size, array('class'=>'form-control', 'id'=>'custom2')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Color', 'custom3', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_dropdown('custom3', $colors, $selected_color, array('class'=>'form-control', 'id'=>'custom3')); ?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Model', 'custom4', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
						'name'=>'custom4',
						'id'=>'custom4',
						'class'=>'form-control input-sm',
						'value'=>$item_info->custom4)
						);?>
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
							'value'=>(empty($item_tax_info)) ? NULL : ($item_tax_info[0]['percent'])
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
							'value'=>(empty($item_tax_info)) ? NULL : ($item_tax_info[2]['percent'])
							));?>
					<span class="input-group-addon input-sm"><b>%</b></span>
				</div>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_tax_3'), 'tax_percent_3', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-4'>
				<?php echo form_input(array(
						'name'=>'tax_names[]',
						'id'=>'tax_name_3',
						'class'=>'form-control input-sm',
						'readonly'=>'true',
						'value'=>$this->config->item('default_tax_3_name'))
						);?>
			</div>
			<div class="col-xs-4">
				<div class="input-group input-group-sm">
					<?php echo form_input(array(
							'name'=>'tax_percents[]',
							'class'=>'form-control input-sm',
							'id'=>'tax_percent_name_3',
							// 'value'=>to_tax_decimals($item_tax_info[1]['percent'])
							'value'=>(empty($item_tax_info)) ? NULL : ($item_tax_info[1]['percent'])
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

		<!-- <?php //if($this->Item->is_superadmin()) { ?>
			<div class="form-group form-group-sm">
				<?php //echo form_label($this->lang->line('items_quantity').' '.$stock_locations[11]['location_name'], 'quantity_11', array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php //echo form_input(array(
							//'name'=>'quantity_11',
							//'id'=>'quantity_11',
							//'class'=>'required quantity form-control',
							//'value'=>isset($item_info->item_id) ? to_quantity_decimals($stock_locations[11]['quantity']) : to_quantity_decimals(0))
							//);?>
				</div>
			</div>
		<?php //} ?> -->

		<?php
		foreach($stock_locations as $key=>$location_detail)
		{
		?>
			<div class="form-group form-group-sm" style="display:none">
				<?php echo form_label($this->lang->line('items_quantity').' '.$location_detail['location_name'], 'quantity_' . $key, array('class'=>'required control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_input(array(
							'name'=>'quantity_' . $key,
							'id'=>'quantity_' . $key,
							'readonly'=>'true',
							'class'=>'required quantity form-control',
							'value'=>isset($item_info->item_id) ? to_quantity_decimals($location_detail['quantity']) : to_quantity_decimals(0))
							);?>
				</div>
			</div>
		<?php
		}
		?>

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
		//for ($i = 1; $i <= 10; ++$i)
		//{
		?>
			<?php
			//if($this->config->item('custom'.$i.'_name') != NULL)
			//{
				//$item_arr = (array)$item_info;
			?>
				<!-- <div class="form-group form-group-sm"> -->
					<?php //echo form_label($this->config->item('custom'.$i.'_name'), 'custom'.$i, array('class'=>'control-label col-xs-3')); ?>
					<!-- <div class='col-xs-8'> -->
						<?php //echo form_input(array(
								//'name'=>'custom'.$i,
								//'id'=>'custom'.$i,
								//'class'=>'form-control input-sm',
								//'value'=>$item_arr['custom'.$i])
								//);?>
					<!-- </div>
				</div> -->
		<?php
			//}
		//}
		?>

		<div class="form-group form-group-sm">
			<?php echo form_label('HSN Code', 'custom1', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
						'name'=>'custom1',
						'id'=>'custom1',
						'class'=>'form-control input-sm',
						'value'=>$item_info->custom1)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label('Pointer', 'custom5', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<?php echo form_input(array(
						'name'=>'custom5',
						'id'=>'custom5',
						'class'=>'form-control input-sm',
						'value'=>$item_info->custom5)
						);?>
			</div>
		</div>

		<hr>

		<?php if($item_info->unit_price == 0.00){ ?>
			<p name="custom_price_label" id="custom_price_label" value="fixed" style="text-align:center; font-weight:bold; font-size: 1.2em; color:#9C27B0">Fixed Prices</p>
		<?php }else{ ?>
			<p id="custom_price_label" value="discounted" style="text-align:center; font-weight:bold; font-size: 1.2em; color:#9C27B0">Discount Values</p>
		<?php } ?>

		<?php
			if(!empty($item_info->unit_price))
			{
				$billtype_retail = ($item_info->unit_price == 0.00) ? json_decode($item_info->cost_price)->retail : json_decode($item_info->discounts)->retail;
				$billtype_wholesale = ($item_info->unit_price == 0.00) ? json_decode($item_info->cost_price)->wholesale : json_decode($item_info->discounts)->wholesale;
				$billtype_franchise = ($item_info->unit_price == 0.00) ? json_decode($item_info->cost_price)->franchise : json_decode($item_info->discounts)->franchise;
				$billtype_ys = ($item_info->unit_price == 0.00) ? json_decode($item_info->cost_price)->ys : json_decode($item_info->discounts)->ys;
			}
		?>
			
		<?php if($this->Item->check_auth(array('superadmin', 'admin', 'apnagps'))){ ?>
			<div class="form-group form-group-sm">
				<?php echo form_label('RETAIL', 'retail', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=> 'retail',
							'id'=> 'retail',
							'class'=>'form-control input-sm',
							'value' => (empty($billtype_retail)) ? NULL : $billtype_retail
							)
					);?>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label('WHOLESALE', 'wholesale', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=> 'wholesale',
							'id'=> 'wholesale',
							'class'=>'form-control input-sm',
							'value' => (empty($billtype_wholesale)) ? NULL : $billtype_wholesale
							)
					);?>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label('FRANCHISE', 'franchise', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=> 'franchise',
							'id'=> 'franchise',
							'class'=>'form-control input-sm',
							'value' => (empty($billtype_franchise)) ? NULL : $billtype_franchise
							)
					);?>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label('SPECIAL APPROVAL', 'special', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_input(array(
							'name'=> 'special',
							'id'=> 'special',
							'class'=>'form-control input-sm',
							'value' => (empty($billtype_ys)) ? NULL : $billtype_ys
							)
					);?>
				</div>
			</div>
		<?php } ?>

	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{
// 	$('#name').on('keyup', function(){
// 		var name = $(this).val();
// 		$.post('<?php //echo site_url($controller_name."/items_livesearch");?>', {'keyword': name}, function(data) {
// 	        $('#livesearch').html(data);
//       });
// 	});

	// $('#livesearch').on('click', '.liveresults', function(){
	// 	var text = $(this).text();
	// 	$('#name').val(text);
	// 	$('#livesearch').html('');
	// });

	$('#unit_price').on('keyup', function(){
		var unit_price = $(this).val();
		if(unit_price == 0.00)
		{
			$('#custom_price_label').val('fixed');
			$('#custom_price_label').html('Fixed Prices');
		}
		else
		{
			$('#custom_price_label').val('discounted');
			$('#custom_price_label').html('Discount Values');
		}
	});

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

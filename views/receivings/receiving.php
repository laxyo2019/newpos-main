<?php $this->load->view("partial/header"); ?>

<?php
if (isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}

if (!empty($warning))
{
	echo "<div class='alert alert-dismissible alert-warning'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='alert alert-dismissible alert-success'>".$success."</div>";
}

if (isset($msg))
{
	echo "<div class='alert alert-dismissible alert-warning'>".$msg."</div>";
}
?>
<?php //echo json_encode($this->session->userdata('recv_cart')); ?>
<div id="register_wrapper">
<!-- Top register controls -->

		<button class='btn btn-info btn-sm pull-left modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_transfer"); ?>'
				title='Quick Excel Stock Transfer'>
			Quick Transfer
		</button>

		<button id="challan_list" class="btn btn-warning btn-sm pull-right modal-dlg-wide", data-href='<?php echo site_url($controller_name."/get_all_challans"); ?>'
		title='Challan List'>
			Print DC
		</button>
	<br><br>

	<?php echo form_open($controller_name."/change_mode", array('id'=>'mode_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label class="control-label"><?php echo $this->lang->line('receivings_mode'); ?></label>
				</li>
				<li class="pull-left">
					<?php echo form_dropdown('mode', $modes, $mode, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
					<?php //echo form_hidden('mode', 'requisition'); ?>
				</li>

				<?php 
				if ($show_stock_locations)
				{
				?>

					<?php
						echo form_hidden('stock_source', $this->Receiving->get_location_id_by_owner($this->session->userdata('person_id'))); //Stock source set by logged in user
					?>

					<?php
					if($mode=='requisition')
					{
					?>
					<li class="pull-left">
						<label class="control-label"><?php echo $this->lang->line('receivings_stock_destination'); ?></label>
					</li>
					<li class="pull-left">
						<?php 
							if($this->Item->is_dewasnaka())
							{
								echo form_hidden('stock_destination', '4'); //Dewasnaka fixed transfer to Laxyo Basement Only
								echo '<h5 style="color:#18bc9c;font-weight:bold">Laxyo Basement</h5>';
							}
							else
							{
								echo form_dropdown('stock_destination', $stock_locations, $stock_destination, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));
							}
						?>
					</li>
				<?php
					}
				}
				?>
				<li class="pull-right">
					<?php if($transfers > 0){ ?>
						<button class="btn btn-sm btn-danger modal-dlg-wide animated flash infinite" data-href='<?php echo site_url("receivings/st_view"); ?>'
								title='New Stock Transfer'>Items Received</button>
					<?php } ?>
				</li>			
			</ul>
		</div>
	<?php echo form_close(); ?>

	<?php echo form_open($controller_name."/add", array('id'=>'add_item_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label for="item", class='control-label'>
						<?php
						if($mode=='receive' or $mode=='requisition')
						{
						?>
							<?php echo $this->lang->line('receivings_find_or_scan_item'); ?>
						<?php
						}
						else
						{
						?>
							<?php echo $this->lang->line('receivings_find_or_scan_item_or_receipt'); ?>
						<?php
						}
						?>			
					</label>
				</li>
				<li class="pull-left">
				
				<?php
					//search item input box
					echo form_input(array('name'=>'item', 'id'=>'item', 'class'=>'form-control input-sm', 'size'=>'50', 'tabindex'=>'1'));
				?>
				</li>
				<!-- Add New Item Button Removed -->
				<li class="pull-right" style="font-weight:bold; font-size:1.2em">
					<?php
						$total_qty = 0;
						foreach($this->session->userdata('recv_cart') as $row)
						{
							$total_qty += $row['quantity'];
						}
						echo 'Total Qty: '.$total_qty; 
					?>
				</li>
			</ul>
		</div>
	<?php echo form_close(); ?>
	
	
<!-- Receiving Items List -->

	<table class="sales_table_100" id="register">
		<thead>
			<tr>
				<th style="width:5%;"><?php echo $this->lang->line('common_delete'); ?></th>
				<th style="width:45%;"><?php echo $this->lang->line('receivings_item_name'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_cost'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_quantity'); ?></th>
				<th style="width:5%;"></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_discount'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_total'); ?></th>
				<th style="width:5%;"><?php echo $this->lang->line('receivings_update'); ?></th>
			</tr>
		</thead>

		<tbody id="cart_contents">
			<?php
			if(count($cart) == 0)
			{
			?>
				<tr>
					<td colspan='8'>
						<div class='alert alert-dismissible alert-info'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
					</td>
				</tr>
			<?php
			}
			else
			{
				foreach(array_reverse($cart, TRUE) as $line=>$item)
				{
			?>
					<?php echo form_open($controller_name."/edit_item/$line", array('class'=>'form-horizontal', 'id'=>'cart_'.$line)); ?>
						<tr>
							<td><?php echo anchor($controller_name."/delete_item/$line", '<span class="glyphicon glyphicon-trash"></span>');?></td>
							<td style="align:center;">
								<?php echo $item['name']; ?><br /> <?php echo '[' . to_quantity_decimals($item['in_stock']) . ' in ' . $item['stock_name'] . ']'; ?>
								<?php echo form_hidden('in_stock', $item['in_stock']); ?>
								<?php echo form_hidden('location', $item['item_location']); ?>
							</td>

							<?php 
							if ($items_module_allowed && $mode !='requisition')
							{
							?>
								<td><?php echo form_input(array('name'=>'price', 'class'=>'form-control input-sm', 'value'=>to_currency_no_money($item['price'])));?></td>
							<?php
							}
							else
							{
							?>
								<td>
									<?php echo $item['price']; ?>
									<?php echo form_hidden('price', to_currency_no_money($item['price'])); ?>
								</td>
							<?php
							}
							?>
							
							<td><?php echo form_input(array('name'=>'quantity', 'class'=>'form-control input-sm', 'value'=>to_quantity_decimals($item['quantity']))); ?></td>
							<?php
							if ($item['receiving_quantity'] > 1) 
							{
							?>
								<td><?php echo 'x'.to_quantity_decimals($item['receiving_quantity']); ?></td>	
							<?php 
							}
							else
							{
							?>
								<td></td>
							<?php 
							}
							?>
						
							<?php       
							if ($items_module_allowed && $mode!='requisition')
							{
							?>
								<td><?php echo form_input(array('name'=>'discount', 'class'=>'form-control input-sm', 'value'=>$item['discount']));?></td>
							<?php
							}
							else
							{
							?>
								<td><?php echo $item['discount']; ?></td>
								<?php echo form_hidden('discount',$item['discount']); ?>
							<?php
							}
							?>
							<td><?php echo to_currency($item['price']*$item['quantity']*$item['receiving_quantity']-$item['price']*$item['quantity']*$item['receiving_quantity']*$item['discount']/100); ?></td> 
							<td><a href="javascript:$('#<?php echo 'cart_'.$line ?>').submit();" title=<?php echo $this->lang->line('receivings_update')?> ><span class="glyphicon glyphicon-refresh"></span></a></td>
						</tr>
						<tr>
							<?php 
							if($item['allow_alt_description']==1)
							{
							?>
								<td style="color: #2F4F4F;"><?php echo $this->lang->line('sales_description_abbrv').':';?></td>
							<?php 
							} 
							?>
							<td colspan='2' style="text-align: left;">
								<?php
								if($item['allow_alt_description']==1)
								{
									echo form_input(array('name'=>'description', 'class'=>'form-control input-sm', 'value'=>$item['description']));
								}
								else
								{
									if ($item['description']!='')
									{
										echo $item['description'];
	        							echo form_hidden('description',$item['description']);
									}
									else
									{
										echo $this->lang->line('sales_no_description');
										echo form_hidden('description','');
									}
								}
								?>
							</td>
							<td colspan='6'></td>
						</tr>
					<?php echo form_close(); ?>
			<?php
				}
			}
			?>
		</tbody>
	</table>
</div>

<!-- Overall Receiving -->

<div id="overall_sale" class="panel panel-default">
	<div class="panel-body">
		
		<table class="sales_table_100" id="sale_totals">
			<tr>
				<?php
				if($mode != 'requisition')
				{
				?>
					<th style="width: 55%;"><?php echo $this->lang->line('sales_total'); ?></th>
					<th style="width: 45%; text-align: right;"><?php echo to_currency($total); ?></th>
				<?php 
				}
				else
				{
				?>
					<th style="width: 55%;"></th>
					<th style="width: 45%; text-align: right;"></th>
				<?php 
				}
				?>
			</tr>
		</table>

		<?php
		if(count($cart) > 0)
		{
		?>
			<div id="finish_sale">
				<?php
				if($mode == 'requisition')
				{
				?>
					<?php echo form_open($controller_name."/requisition_complete", array('id'=>'finish_receiving_form', 'class'=>'form-horizontal')); ?>
						<div class="form-group form-group-sm">
							<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?></label>
							<?php echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'4')); ?>
							
							<div class="btn btn-sm btn-danger pull-left" id='cancel_receiving_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('receivings_cancel_receiving'); ?></div>
							
							<input type="hidden" name="location_owner" value="<?php echo $this->Receiving->get_owner_id_by_location($this->session->userdata('recv_stock_destination')); ?>">

							<div class="btn btn-sm btn-success pull-right" id='finish_receiving_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('receivings_complete_receiving'); ?></div>
						</div>
					<?php echo form_close(); ?>
				<?php
				}
				else
				{
				?>
					<?php echo form_open($controller_name."/complete", array('id'=>'finish_receiving_form', 'class'=>'form-horizontal')); ?>
						<div class="form-group form-group-sm">
							<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?></label>
							<?php echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'4'));?>

							<table class="sales_table_100" id="payment_details">
								<tr>
									<td><?php echo $this->lang->line('receivings_print_after_sale'); ?></td>
									<td>
										<?php echo form_checkbox(array('name'=>'recv_print_after_sale', 'id'=>'recv_print_after_sale', 'class'=>'checkbox', 'value'=>1, 'checked'=>$print_after_sale)); ?>
									</td>
								</tr>

								<?php
								if ($mode == "receive") 
								{
								?>
									<tr>
										<td><?php echo $this->lang->line('receivings_reference');?></td>
										<td>
											<?php echo form_input(array('name'=>'recv_reference', 'id'=>'recv_reference', 'class'=>'form-control input-sm', 'value'=>$reference, 'size'=>5));?>
										</td>
									</tr>
								<?php 
								}
								?>
								<tr>
									<td><?php echo $this->lang->line('sales_payment'); ?></td>
									<td>
										<?php echo form_dropdown('payment_type', $payment_options, array(), array('id'=>'payment_types', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'auto')); ?>
									</td>
								</tr>

								<tr>
									<td><?php echo $this->lang->line('sales_amount_tendered'); ?></td>
									<td>
										<?php echo form_input(array('name'=>'amount_tendered', 'value'=>'', 'class'=>'form-control input-sm', 'size'=>'5')); ?>
									</td>
								</tr>
							</table>

							<div class='btn btn-sm btn-danger pull-left' id='cancel_receiving_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('receivings_cancel_receiving') ?></div>
							
							<div class='btn btn-sm btn-success pull-right' id='finish_receiving_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('receivings_complete_receiving') ?></div>
						</div>
					<?php echo form_close(); ?>
				<?php
				}
				?>
			</div>
		<?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	$("#item").autocomplete(
	{
	source: '<?php echo site_url($controller_name."/stock_item_search"); ?>',
		minChars:0,
			delay:10,
			autoFocus: false,
	select:	function (a, ui) {
		$(this).val(ui.item.value);
		$("#add_item_form").submit();
		return false;
	}
	});

	$('#item').focus();

	$('#item').keypress(function (e) {
		if (e.which == 13) {
			$('#add_item_form').submit();
			return false;
		}
	});

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#comment').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_comment");?>', {comment: $('#comment').val()});
	});

	$('#recv_reference').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_reference");?>', {recv_reference: $('#recv_reference').val()});
	});

	$("#recv_print_after_sale").change(function()
	{
		$.post('<?php echo site_url($controller_name."/set_print_after_sale");?>', {recv_print_after_sale: $(this).is(":checked")});
	});

	$('#item').click(function()
    {
    	$(this).attr('value','');
    });

	// $('#item,#supplier').click(function()
    // {
    // 	$(this).attr('value','');
    // });

  //   $("#supplier").autocomplete(
  //   {
	// 	source: '<?php //echo site_url("suppliers/suggest"); ?>',
  //   	minChars:0,
  //   	delay:10,
	// 	select: function (a, ui) {
	// 		$(this).val(ui.item.value);
	// 		$("#select_supplier_form").submit();
	// 	}
  //   });

	dialog_support.init("button.modal-dlg, button.modal-dlg-wide");

		// $('#supplier').blur(function()
    // {
    // 	$(this).attr('value',"<?php //echo $this->lang->line('receivings_start_typing_supplier_name'); ?>");
    // });

    $("#finish_receiving_button").click(function()
    {
   		$('#finish_receiving_form').submit();
    });

    $("#cancel_receiving_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("receivings_confirm_cancel_receiving"); ?>'))
    	{
			$('#finish_receiving_form').attr('action', '<?php echo site_url($controller_name."/cancel_receiving"); ?>');
    		$('#finish_receiving_form').submit();
    	}
    });

	$("#cart_contents input").keypress(function(event)
	{
		if (event.which == 13)
		{
			$(this).parents("tr").prevAll("form:first").submit();
		}
	});

	table_support.handle_submit = function(resource, response, stay_open)
	{
		if(response.success)
		{
			if (resource.match(/suppliers$/))
			{
				$("#supplier").attr("value",response.id);
				$("#select_supplier_form").submit();
			}
			else
			{
				$("#item").attr("value",response.id);
				if (stay_open)
				{
					$("#add_item_form").ajaxSubmit();
				}
				else
				{
					$("#add_item_form").submit();
				}
			}
		}
	}

	$('[name="price"],[name="quantity"],[name="discount"],[name="description"],[name="serialnumber"]').change(function() {
		$(this).parents("tr").prevAll("form:first").submit()
	});

});

</script>

<?php $this->load->view("partial/footer"); ?>

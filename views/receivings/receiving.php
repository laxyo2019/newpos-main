<?php $this->load->view("partial/header"); ?>

<?php
$data_item = array();
	$error_count = 0;
	$total=0;
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
<?php //echo json_encode($this->session->userdata()); ?>
<div id="register_wrapper">
<!-- Top register controls -->

		<button class='btn btn-sm btn-primary pull-left modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/quick_transfer"); ?>'
				title='Quick Excel Stock Transfer'>
			Quick Transfer
		</button>
		
		<!----<button id="transfer_status" class="btn btn-sm btn-default pull-left modal-dlg-wide", data-href='<?php // echo site_url($controller_name."/get_transfer_status"); ?>'
		title='Pending Transfers'>
			Pending Transfers
		</button> -->

		<!----<button id="challan_list" class="btn btn-sm btn-danger pull-right modal-dlg-wide", data-href='<?php //echo site_url($controller_name."/get_all_challans"); ?>'
		title='Challan List'>
			Transfer Log
		</button> -->

		<a href='<?php echo site_url($controller_name."/view_transfer_manager");?>' id="transfer_manager" class="btn btn-sm btn-info pull-right" target="_blank"	title='Transfer Manager'>
			Manage Transfer</a>

		<button style="margin-right: 7px;" class="btn btn-sm btn-info pull-right hit_rmv_btn">Remove</button>	
		
		<a style="display: none; margin-right: 7px;" href='' id="rmv_out" class="btn btn-sm btn-info pull-right" title='Remove All Out Of Stocks'>
			Remove</a>
		
		<a style="display: none; margin-right: 7px;" href='<?php echo site_url($controller_name."/all_delete_item_view");?>' id="reload_btn" class="btn btn-sm btn-info pull-right" title='Remove All Out Of Stocks'>
		Remove</a>

		<?php
			// if(!empty($pending_transfers)){ 
			// 	if($pending_transfers){
			// 	echo anchor('receivings/stock_in', '<span class="btn btn-sm btn-info pull-right animated jello infinite">Stock In</span>',
			// 	array('class'=>'print_hide', 'data-btn-submit' => $this->lang->line('common_submit'), 'title' => 'Receive Items'));
			// 	}
			// } 
		?>	
	<br><br>

	<?php echo form_open($controller_name."/change_mode", array('id'=>'mode_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-right">
					<?php echo form_dropdown('dispatchers', $dispatchers, $dispatcher, array('class'=>'selectpicker show-menu-arrow text-success', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit', 'id'=>'selectDispatcher')); ?>
				</li>
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
						echo form_hidden('stock_source', $this->Stock_location->get_location_id_2($this->session->userdata('person_id'))); //Stock source set by logged in user
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
							echo form_dropdown('stock_destination', $stock_locations, $stock_destination, array('onchange'=>"$('#mode_form').submit();", 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));
						?>
					</li>
				<?php
					}
				}
				?>	
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
				<th style="width:15%;">Barcode</th>
				<th style="width:45%;"><?php echo $this->lang->line('receivings_item_name'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_cost'); ?></th>
				<th style="width:10%;"><?php echo $this->lang->line('receivings_quantity'); ?></th>
				<th style="width:5%;"></th>
				<!-- <th style="width:10%;"><?php //echo $this->lang->line('receivings_discount'); ?></th> -->
				<th style="width:10%;"><?php echo $this->lang->line('receivings_total'); ?></th>
				<!-- <th style="width:5%;"><?php //echo $this->lang->line('receivings_update'); ?></th> -->
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
						$total++;
						$text_danger = 	$item['in_stock']>0 ? '' : "text-danger";
						if($item['in_stock']<=0){
							$data_item[] = $item['line'];
							$error_count++;
						}
			?>
					<?php echo form_open($controller_name."/edit_item/$line", array('class'=>'form-horizontal', 'id'=>'cart_'.$line)); ?>
						<tr>
							<td><?php echo anchor($controller_name."/delete_item/$line", '<span class="glyphicon glyphicon-trash"></span>',array('class'=>$text_danger));?></td>
							<td class="<?php echo $text_danger; ?>"><?php echo $item['item_number'] ?></td>
							<td style="align:center;" class="<?php echo $text_danger; ?>">
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
								<td class="<?php echo $text_danger; ?>">
									<?php echo to_currency($item['price']); ?>
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
								<!-- <td><?php //echo $item['discount']; ?></td> -->
								<?php echo form_hidden('discount',$item['discount']); ?>
							<?php
							}
							?>
							<td class="<?php echo $text_danger; ?>"><?php echo to_currency($item['price']*$item['quantity']*$item['receiving_quantity']-$item['price']*$item['quantity']*$item['receiving_quantity']*$item['discount']/100); ?></td> 
							<!-- <td><a href="javascript:$('#<?php //echo 'cart_'.$line ?>').submit();" title=<?php //echo $this->lang->line('receivings_update')?> ><span class="glyphicon glyphicon-refresh"></span></a></td> -->
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
										//echo $item['description'];
										echo form_hidden('description',$item['description']);
									}
									else
									{
										//echo $this->lang->line('sales_no_description');
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
							
							<input type="hidden" name="location_owner" value="<?php echo $this->Stock_location->get_owner_id($this->session->userdata('recv_stock_destination')); ?>">

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
<input type="hidden" value="<?php echo implode(",",$data_item); ?>" name="rmv_id[]" id="rmv_id">

<script type="text/javascript">

$(document).ready(function(){
	$('.hit_rmv_btn').hover(function(){
		var ids =  $('#rmv_id').val();
	
		if(ids == ''){
			$('.hit_rmv_btn').attr("disabled", 'disabled');
		}
		else{
			$('.hit_rmv_btn').attr("disabled", false);	
		}
		$('#rmv_out').attr('href','<?php echo site_url($controller_name.'/all_delete_item/?id=');?>'+ids);
	});
})

$(document).on('click','.hit_rmv_btn',function(){
		$('#rmv_out')[0].click();
	setTimeout(function() {
	    $('#reload_btn')[0].click();
	}, 4e3);	
})

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

	$('#selectDispatcher').on('change', function(){
		var dispatcher_id = $(this).val();
		var webkey = prompt("Enter your secure webkey:");

		$.post('<?php echo site_url($controller_name."/dispatcher_auth");?>', {'dispatcher_id': dispatcher_id, 'webkey': webkey}, function(data) {
			if(data == "success")
			{
				$.post('<?php echo site_url($controller_name."/set_dispatcher");?>', {'dispatcher_id': dispatcher_id});
				window.location.href = "receivings";
			}
			else
			{
				alert("Incorrect Webkey");
			}
    });
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
			error = <?php echo $error_count;?>;
			total = <?php echo $total; ?>;
			if(error != 0){
					alert( error +' out of '+total+' Stock is not available.');
			}else{

					<?php if(!empty($this->session->userdata('dispatcher_id'))){ ?> 
					$('#finish_receiving_form').submit();
					<?php }else{ ?>	
						alert('Please select a Dispatcher');
					<?php } ?>	

			}	 
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

<?php $this->load->view("partial/header"); ?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<?php if(!empty($customer_email)): ?>
<script type="text/javascript">
$(document).ready(function()
{
	var send_email = function()
	{
		$.get('<?php echo site_url() . "/sales/send_pdf/" . $sale_id_num; ?>',
			function(response)
			{
				$.notify(response.message, { type: response.success ? 'success' : 'danger'} );
			}, 'json'
		);
	};

	$("#show_email_button").click(send_email);

	<?php if(!empty($email_receipt)): ?>
		send_email();
	<?php endif; ?>
});
</script>
<?php endif; ?>

<?php //echo json_encode($this->session->userdata('sales_cart')); ?>

<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>$print_after_sale, 'selected_printer'=>'invoice_printer')); ?>

<div class="print_hide" id="control_buttons" style="float:left">
		<button id="inv_cp_toggle" class="btn btn-warning btn-sm">Print Customer Copy</button>
</div>
<div class="print_hide" id="control_buttons" style="text-align:right">
	<a href="javascript:printdoc();"><div class="btn btn-info btn-sm", id="show_print_button"><?php echo '<span class="glyphicon glyphicon-print">&nbsp</span>' . $this->lang->line('common_print'); ?></div></a>
	<?php /* this line will allow to print and go back to sales automatically.... echo anchor("sales", '<span class="glyphicon glyphicon-print">&nbsp</span>' . $this->lang->line('common_print'), array('class'=>'btn btn-info btn-sm', 'id'=>'show_print_button', 'onclick'=>'window.print();')); */ ?>
	<?php if(isset($customer_email) && !empty($customer_email)): ?>
		<a href="javascript:void(0);"><div class="btn btn-info btn-sm", id="show_email_button"><?php echo '<span class="glyphicon glyphicon-envelope">&nbsp</span>' . $this->lang->line('sales_send_invoice'); ?></div></a>
	<?php endif; ?>
	<?php echo anchor("sales", '<span class="glyphicon glyphicon-shopping-cart">&nbsp</span>' . $this->lang->line('sales_register'), array('class'=>'btn btn-info btn-sm', 'id'=>'show_sales_button')); ?>
	<?php echo anchor("sales/manage", '<span class="glyphicon glyphicon-list-alt">&nbsp</span>' . $this->lang->line('sales_takings'), array('class'=>'btn btn-info btn-sm', 'id'=>'show_takings_button')); ?>
</div>

<?php
	// Temporarily loads the system language for _lang to print invoice in the system language rather than user defined.
	load_language(TRUE,array('sales','common'));
?>

	<div class="container" id="remove-border">
		<div class="a col-md-3"></div>
			<div class="b col-md-6" style="outline: 1px solid #cfcfcf;outline-offset: -15px;">
				<br>
				<section style="padding-left: 5px;">
					<div class="row">
						<h4 style="text-align:center">CREDIT CUM PURCHASE RETURN NOTE</h4>
						<div style="width:50%; float: left; padding-left: 20px ">
						<?php if(in_array($this->Sale->get_invoice_details($sale_id_num)['login_type'], array('dbf', 'hub'))){ ?>
							<img id="image" height="60" width="100" src="<?php echo base_url('uploads/' . $this->Appconfig->get('company_logo')); ?>" alt="company_logo" />
						<?php } ?>

							<h6 style="font-size: 8px;">
								<?php echo $this->Sale->get_invoice_details($sale_id_num)['shop_address']; ?>
							</h6>
						</div>
	
						<div style="width:45%; float: left;">
							<p id="inv_cp" style="text-align:center; font-size: 0.8em">Seller Copy</p>
							<table class="table table-bordered" style="margin: 0px; border-right: 0px; font-size: 10px">
								<tr>
									<td style="padding: 5px;" class="text-right">Ref Sale # <br> <b><?php echo $ref_invoice_number; ?></b></td>
									<td style="padding: 5px;" class="text-right">Credit Note #<br> <b><?php echo $credit_note_number; ?><b/></td>
									<td style="padding: 5px;" class="text-right"><?php echo $this->lang->line('common_date'); ?> <br> <b><?php echo $transaction_date; ?></b></td>
								</tr>
							</table>
							<div class="col-md-12 pull-right" style="padding: 0px;">
								<h5 style="font-size: 10px">
                  Shop: <?php echo $this->Sale->get_invoice_details($sale_id_num)['shop_name']; ?>
                  <br>
                  Cashier: <?php echo $cashier_name; ?>
                  <br>
									Sale Code: <?php echo $cashier_sale_code; ?>
									<!-- <br>
									Bill Type: <?php //echo $this->Sale->get_bill_type_on_invoice($sale_id_num); ?> -->
								</h5>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div style="height: 1px;background-color: #dddddd; width: 100%;"></div>
					<p style="font-size: 0.9em ;padding: 5px 10px;margin: 0px;">
						<?php echo $customer_info['name']; ?><br>
						Ph.: <?php echo $customer_info['phone']; ?><br>
						<?php if(!empty($customer_info['gstin'])){
							echo 'Cust. GSTIN: '.$customer_info['gstin'];
						}?>
					</p>
				</section>
				<section>
					<table class="table table-bordered table-condensed" style="font-size: 10px;">
						<thead>
							<tr>
								<td style="text-align:center; font-weight:bold;">Particulars</td>
								<td style="text-align:center; font-weight:bold;">HSN</td>
								<td style="text-align:center; font-weight:bold;">MRP</td>
								<td style="text-align:center; font-weight:bold;">Discount</td>
								<td style="text-align:center; font-weight:bold;">Discounted Price</td>
								<td style="text-align:center; font-weight:bold;">Qty</td>
								<td style="text-align:center; font-weight:bold;">Tax Rate</td>
								<td style="text-align:center; font-weight:bold;">Taxable Amt.</td>
							</tr>
            </thead>
            
            <tbody>
              <?php
              foreach($cart as $line=>$item)
              {
                if($item['print_option'] == PRINT_YES)
                {
              ?>
                  <tr class="item-row">
                    <td style="font-size:0.9em; text-align:center"><?php echo $item['name']; ?></td>
                    <td style="font-size:0.8em; text-align:center"><?php echo $item['custom1']; ?></td> 

                    <td style="font-size:0.8em; text-align:center"> 
											<?php // Item MRP 
												echo to_currency($item['price']);
											?>
                    </td>

										<td style="font-size:0.9em; text-align:center">
											<?php 
												echo to_quantity_decimals($item['discount']) . '%';
											?>
										</td>
                    
										<td style="font-size:0.9em; text-align:center"> 
											<?php // DISCOUNTED PRICE
												echo to_currency($item['price'] - ($item['price'] * ($item['discount'] / 100)) );
											?>
										</td>

                    <td style="font-size:0.9em; text-align:center"><?php echo to_quantity_decimals(-1 * $item['quantity']); ?></td>
                    
										<td style="font-size:0.8em; text-align:center">
											<?php
												echo $this->Item_taxes->get_item_invoice_tax_rate($item['item_id'], $item['price'], $item['discount']) . '%';
											?>
										</td>

                    <td style="font-size:0.9em; text-align:right">
											<?php // TAXABLE VALUE
												echo to_currency($item['taxable_total'] * -1); 
											?>
										</td>
                  </tr>
                  
              <?php
                }
              }
							?>

							<tr><td colspan="8" class="blank-bottom"></td></tr>

              <tr>
								<td colspan="6" class="blank-bottom"></td> <!-- KEYS -->
								<td style="font-size:0.9em">
									Subtotal<br>
									<?php foreach($taxes as $tax_group_index=>$sales_tax)
										{
											echo $sales_tax['tax_group'].'<br>';
										}
									?>
								</td>

								
                <td style="text-align:right; font-size:0.9em"> <!-- VALUES -->
									<?php echo to_currency($subtotal * -1).'<br>'; ?> <!-- SUBTOTAL -->
									<?php
										foreach($taxes as $tax_group_index=>$sales_tax)
										{
											echo to_currency($sales_tax['sale_tax_amount'] * -1).'<br>';
										}
									?>
								</td>
              </tr>

              <tr>
                <td style="text-align:right"><b>Total</b></td>
                <td colspan="4"></td>
                <td style="text-align:center"><?php echo $total_units * -1;?></td>
                <td colspan="2" style="text-align:right"><b><?php echo to_currency(-1 * ceil($total)); ?></b></td>
              </tr>
            </tbody>
					</table>
				</section>

				<!-- <section style="font-size: 10px;">
					<div class="text-center"
					 style="width:60%; float: left; padding-left: 20px; border: 1px solid #ddd;border-left: 0px;border-right: 0px; padding: 5px;">
						Company's PAN: <b>AABCL3031E</b><br>
						<p>Declaration: We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
					</div>
					<div style="width:40%; float: left; padding-left: 20px; border: 1px solid #ddd;border-right: 0px; padding: 5px;">
						Company's Bank Details<br>
            <p>Bank Name: <b>HDFC Bank A/c 04048470000079</b><br>
            Branch & IFSC: <b>VIJAY NAGAR & HDFC0000404</b></p>
					</div>
				</section> -->
				<div class="clearfix"></div>
				<section style="font-size: 10px;">
					<div class="text-center" style="width: 50%;float: left;border: 1px solid #ddd;border-left: 0px;border-right: 0px; padding: 5px;">
						Seller's Signature<br><br>
						<img id="image" style="position:absolute; transform: rotate(-18deg)" height="64" width="64" src="<?php echo base_url('images/lel_stamp.png'); ?>" alt="company_stamp" /><br>
						_______________
					</div>
					<div class="text-center" style="width: 50%;float: left;border: 1px solid #ddd;border-right: 0px; padding: 5px;">
						Customer's Signature<br><br><br>
						_______________
					</div>
				</section>
				<div class="clearfix"></div>
				
				<div class="clearfix"></div>
				<br>
				<section>
					<p style="font-size: 0.8em; padding: 0px 5px; margin: 0px;">TERMS AND CONDITIONS</p>
					<h6 style="font-size: 0.7em; padding: 5px; margin: 0px">
					1. The Goods sold are at Taxable value. GST, local taxes, if any, are charged in the bill as applicable, hence credit note, if issued at the discretion of management  will be issued only for the net value of product.<br />
					2. Goods once exchanged will not be returned back again.<br /> 
					3. Replacement (Only One Time) for manufacturing defect (if any) shall be allowed within Fifteen (15) days from date of billing subject to merchandise return in original packed saleable and sealed pack condition only with original bill.<br />
					4. No credit note/cash payment could be issued against return/exchange made through credit note.<br />
					5. Goods sold at discount do not carry any warranty or guarantee from the seller (LEL) and no claim/complain can be entertained about any defect or damage or on quality after completion of sale transaction.<br />
					6. We are operating on discounted and lower price basis which are non-negotiable.<br />
					7. While we would try and deal with any situation keeping our customers interest on priority: any dispute there under shall be subject to Indore jurisdiction only.<br />
					8. The discretion of management of Laxyo Energy Ltd. shall be final and binding on customer.<br /> 
					9. E. & O. E.
					</h6>
				</section>
				
				<div style="width: 200px;margin: auto;display: block">
					<center>
            <img width="150" src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
			      <?php echo $sale_id; ?>
					</center>
				<br>
				</div>
			</div>
		<div class="c col-md-3"></div>
	</div>

<script type="text/javascript">
$(window).on("load", function()
{
	// install firefox addon in order to use this plugin
	if(window.jsPrintSetup)
	{
		<?php if(!$this->Appconfig->get('print_header'))
		{
		?>
			// set page header
			jsPrintSetup.setOption('headerStrLeft', '');
			jsPrintSetup.setOption('headerStrCenter', '');
			jsPrintSetup.setOption('headerStrRight', '');
		<?php
		}

		if(!$this->Appconfig->get('print_footer'))
		{
		?>
			// set empty page footer
			jsPrintSetup.setOption('footerStrLeft', '');
			jsPrintSetup.setOption('footerStrCenter', '');
			jsPrintSetup.setOption('footerStrRight', '');
		<?php
		}
		?>
	}
});

$('#inv_cp_toggle').on('click',  function(){
	$(this).text($(this).text() == 'Print Seller Copy' ? 'Print Customer Copy' : 'Print Seller Copy');
	$('#inv_cp').text($('#inv_cp').text() == 'Seller Copy' ? 'Customer Copy' : 'Seller Copy');
});
</script>

<?php $this->load->view("partial/footer"); ?>

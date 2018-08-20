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
						<div style="width:55%; float: left; padding-left: 20px ">
						<img id="image" height="60" width="100" src="<?php echo base_url('uploads/' . $this->Appconfig->get('company_logo')); ?>" alt="company_logo" />
							<h6 style="font-size: 8px;">
								<b>Laxyo Energy Limited</b><br>
									Laxyo House Plot No. 2<br>
							    County Park, MR-5,<br>
							    Mahalaxmi Nagar, Indore M.P.<br>
							    PIN : 452010<br>
							    GSTIN/UIN:23AABCL3031E1Z9<br>
							    State Name : Madhya Pradesh, Code : 23<br>
							    Contact : 0731-6671721,+91-8815218210<br>
							    E-mail : sales@laxyo.com<br>
							</h6>
						</div>
	
						

						<div style="width:40%; float: left;">
							
							<table class="table table-bordered" style="margin: 0px; border-right: 0px; font-size: 10px">
								<tr>
									<td style="padding: 5px;"><?php echo $this->lang->line('sales_invoice_number');?><br> <b><?php echo $invoice_number; ?><b/></td>
									<td style="padding: 5px;" class="text-right"><?php echo $this->lang->line('common_date'); ?> <br> <b><?php echo $transaction_date; ?></b></td>
								</tr>
							</table>
							<div class="col-md-12 pull-right" style="padding: 0px;">
								<h5 style="font-size: 10px">
                  Shop: <?php $this->Sale->get_username($this->session->userdata('person_id')); ?>
                  <br>
                  Cashier: <?php echo $cashier_name; ?>
                  <br>
									Sale Code: <?php echo $cashier_sale_code; ?>
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
								<td style="text-align:center; font-weight:bold;">Base Price</td>
								<td style="text-align:center; font-weight:bold;">Tax Rate</td>
								<td style="text-align:center; font-weight:bold;">MRP</td>
								<td style="text-align:center; font-weight:bold;">Qty</td>
								<td style="text-align:center; font-weight:bold;">Disc. %</td>
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
                      <?php echo to_currency($item['taxable_total']); // BASE PRICE ?>
                    </td>

                    <td style="font-size:0.8em; text-align:center"><?php echo $this->Sale->get_item_tax_rate($item['item_id']) . '%';?></td>
                    <td style="font-size:0.9em; text-align:center"><?php echo to_currency($item['price']); ?></td>
                    <td style="font-size:0.9em; text-align:center"><?php echo to_quantity_decimals($item['quantity']); ?></td>
                    <td style="font-size:0.9em; text-align:center"><?php echo to_quantity_decimals($item['discount']) . '%'; ?></td>
                    <td style="font-size:0.9em; text-align:right"><?php echo $item['taxable_total'] * $item['quantity']; ?></td>
                  </tr>
                  
              <?php
                }
              }
							?>

							<tr><td colspan="8" class="blank-bottom"></td></tr>

              <tr>
								<td colspan="6" class="blank-bottom"></td>
								<td style="text-align:right">
									Subtotal<br>
									<?php foreach($taxes as $tax_group_index=>$sales_tax)
										{
											echo $sales_tax['tax_group'].'<br>';
										}
									?>
								</td>
                <td style="text-align:right">
									<?php echo $subtotal.'<br>'; ?>
									<?php
										foreach($taxes as $tax_group_index=>$sales_tax)
										{
											echo $sales_tax['sale_tax_amount'].'<br>';
										}
									?>
								</td>
              </tr>

              <tr>
                <td style="text-align:right"><b>Total</b></td>
                <td colspan="4"></td>
                <td style="text-align:center"><?php echo $total_units;?></td>
                <td colspan="2" style="text-align:right"><b><?php echo to_currency($total); ?></b></td>
              </tr>
            </tbody>
					</table>
				</section>

				<section style="font-size: 10px;">
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
				</section>
				<div class="clearfix"></div>
				<section style="font-size: 10px;">
					<div class="text-center" style="width: 50%;float: left;border: 1px solid #ddd;border-left: 0px;border-right: 0px; padding: 5px;">
						Seller's Signature<br><br><br>
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
					<p style="font-size: 10px; padding: 0px 5px; margin: 0px;">TERMS AND CONDITIONS</p>
					<h6 style="font-size: 8px; padding: 5px; margin: 0px">
					1. The Goods sold are at Taxable value which are exclusive of GST, local Lax etc. as applicable.<br> 
					2. No Credit. Any delayed payment will be charged 18 % interest for delayed period.<br>
					3. Goods once sold will not be returned or exchanged.<br>
					4. Replacement for manufacturing defect (if any) shall be allowed within 2 days from date of billing subject to merchandise return in original packed saleable and sealed pack condition only with original bill. Refund can be in form of credit note only and not in cash. No duplicate credit note could be issued in case of lost of credit note.<br>
					5. Goods sold at discount do not carry any warranty or guarantee from the seller and no claim/complain can be entertained about any defect or damage or on quality after completion of sale transaction.<br>
					6. Discount Brand Factory (DBF) operates on discounted and lower price basis which are non-negotiable.<br>
					7. While we would try and deal with any situation keeping our customers interest on priority: any dispute there under shall be subject to Indore jurisdiction only.<br>
					8. The discretion of Discount Brand Factory (DBF)/Laxyo Energy Ltd. shall be final and binding.<br> 
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

<?php $this->load->view("partial/footer"); ?>

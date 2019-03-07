
</style>
<?php $this->load->view("partial/header"); ?>
<div class="tab-content">
<div class="tab-pane fade in active" id="mh_count">
<div class="row">
	<div class="col-md-4">
		<div class="column">
			<center>
			<div class="card" style="background-color: #00cccc; min-height:200px;">
				<br>
				<h3>Dynamic Pricing</h3>
				<h1><a  href="<?php echo site_url('offers/view_dynamic_pricing'); ?>" target="_blank" title="Dynamic Pricing"><span class="fa fa-tags" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-md-4 min_height">
		<div class="column">
			<center>       
			<div class="card" style="background-color: #ffcc66;min-height:200px;">
				<br>
				<h3>Vouchers</h3>
				<h1><a  href="<?php echo site_url('offers/view_vouchers'); ?>" target="_blank" title="Vouchers"><span class="fa fa-briefcase" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-md-4 min_height">
		<div class="column">
			<center>       
			<div class="card" style="background-color: #f77b7b;min-height:200px;">
				<br>
				<h3>Purchase Limits</h3>
				<h1><a  href="<?php echo site_url('offers/view_purchase_limits'); ?>" target="_blank" title="Purchase Limits"><span class="fa fa-qrcode" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="clearfix" style="margin-top:20px;"></div>
	<div class="col-md-4" style="margin-top:20px;">
		<div class="column">
			<center>       
			<div class="card" style="background-color: #c7a9ef;min-height:200px;">
				<br>
				<h3>Control Limits</h3>
				<h1><a  href="<?php echo site_url('offers/view_control_panel'); ?>" target="_blank" title="Control Limits"><span class="fa fa-cog" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>	
</div>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
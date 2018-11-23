<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#basic_mod" title="Dynamic Pricing">Dynamic Pricing</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#voucher_mod" title="Gift Vouchers">Gift Vouchers</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#bogo_mod" title="BOGO Plans">Items Club</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#plimit_mod" title="Purchase Limiter">Purchase Limits</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="basic_mod">
		<?php $this->load->view("offers/submodules/basic"); ?>
	</div>
	<div class="tab-pane" id="voucher_mod">
		<?php $this->load->view("offers/submodules/voucher"); ?>
	</div>
	<div class="tab-pane" id="bogo_mod">
		<?php $this->load->view("offers/submodules/bogo"); ?>
	</div>
	<div class="tab-pane" id="plimit_mod">
		<?php $this->load->view("offers/submodules/plimit"); ?>
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>
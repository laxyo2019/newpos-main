<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#tab_dynamic_pricing" title="Dynamic Pricing">Dynamic Pricing</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#tab_reward_vc" title="Reward Vouchers">Reward Vouchers</a>
	</li>
	<!-- <li role="presentation">
		<a data-toggle="tab" href="#tab_gift_vc" title="Gift Vouchers">Gift Vouchers</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#bogo_mod" title="BOGO Plans">Items Club</a>
	</li> -->
	<li role="presentation">
		<a data-toggle="tab" href="#tab_purchase_limits" title="Purchase Limits">Set Purchase Limits</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="tab_dynamic_pricing">
		<?php $this->load->view("offers/submodules/dynamic_pricing"); ?>
	</div>
	<div class="tab-pane" id="tab_reward_vc">
		<?php $this->load->view("offers/submodules/reward_vc"); ?>
	</div>

	<!-- <div class="tab-pane" id="tab_gift_vc">
		<?php //$this->load->view("offers/submodules/gift_vc"); ?>
	</div>
	<div class="tab-pane" id="bogo_mod">
		<?php //$this->load->view("offers/submodules/bogo"); ?>
	</div> -->
	<div class="tab-pane" id="tab_purchase_limits">
		<?php $this->load->view("offers/submodules/purchase_limits"); ?>
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>
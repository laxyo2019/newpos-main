<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#count_mod" title="Count Items">Count Actions</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#list_mod" title="List Items">List Actions</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#mci_mod" title="Master Classification Index">MCI</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#bulk_mod" title="Bulk Actions">Bulk Actions</a>
	</li>
	<?php if($this->Item->is_superadmin()){ ?>
		<li role="presentation">
			<a data-toggle="tab" href="#cashier_mod" title="Cashiers">Cashiers</a>
		</li>
	<?php } ?>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="count_mod">
		<?php $this->load->view("manager/count_actions"); ?>
	</div>
	<div class="tab-pane" id="list_mod">
		<?php $this->load->view("manager/list_actions"); ?>
	</div>
	<div class="tab-pane" id="mci_mod">
		<?php $this->load->view("manager/mci"); ?>
	</div>
	<div class="tab-pane" id="bulk_mod">
		<?php $this->load->view("manager/bulk_actions"); ?>
	</div>
	<?php if($this->Item->is_superadmin()){ ?>
		<div class="tab-pane" id="cashier_mod">
			<?php $this->load->view("manager/cashiers"); ?>
		</div>
	<?php } ?>
</div>

<?php $this->load->view("partial/footer"); ?>
<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg-wide");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#transfer_list" title="Transfer List">Transfer List</a>
	</li>
	<!-- <li role="presentation">
		<a data-toggle="tab" href="#list_mod" title="List Items">List Actions</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#mci_mod" title="Master Classification Index">MCI</a>
	</li> -->

	<!-- <?php //if($this->Item->check_auth(array('superadmin', 'admin'))){ ?>
		<li role="presentation">
			<a data-toggle="tab" href="#bulk_mod" title="Bulk Actions">Bulk Actions</a>
		</li>
		<li role="presentation">
			<a data-toggle="tab" href="#cashier_mod" title="Cashiers">Cashiers</a>
		</li>
	<?php //} ?> -->

	<?php //if($this->Item->check_auth(array('superadmin', 'admin', 'accounts'))){ ?>
		<!-- <li role="presentation">
			<a data-toggle="tab" href="#report_mod" title="Custom Reports">Reports</a>
		</li> -->
	<?php //} ?>

	<!-- <li role="presentation">
		<a data-toggle="tab" href="#extras_mod" title="Extra Features">Extras</a>
	</li> -->

</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="transfer_list">
		<?php $this->load->view("receivings/tabs/transfer_list"); ?>
	</div>
	<!-- <div class="tab-pane" id="list_mod">
		<?php //$this->load->view("manager/tabs/list_actions"); ?>
	</div>
	<div class="tab-pane" id="mci_mod">
		<?php //$this->load->view("manager/tabs/mci"); ?>
	</div> -->

	<?php //if($this->Item->check_auth(array('superadmin', 'admin'))){ ?>
		<!-- <div class="tab-pane" id="bulk_mod">
			<?php //$this->load->view("manager/tabs/bulk_actions"); ?>
		</div>
		<div class="tab-pane" id="cashier_mod">
			<?php //$this->load->view("manager/tabs/cashiers"); ?>
		</div> -->
	<?php //} ?>
	
	<?php //if($this->Item->check_auth(array('superadmin', 'admin', 'accounts'))){ ?>
		<!-- <div class="tab-pane" id="report_mod">
			<?php //$this->load->view("manager/tabs/reports"); ?>
		</div> -->
	<?php //} ?>

	<!-- <div class="tab-pane" id="extras_mod">
		<?php //$this->load->view("manager/tabs/extras"); ?>
	</div> -->
	
</div>

<?php $this->load->view("partial/footer"); ?>
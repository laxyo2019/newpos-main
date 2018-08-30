<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#basic_mod" title="Basic Plans">Basic</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#" title="">TBD 2</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#" title="">TBD 3</a>
	</li>
	<li role="presentation">
		<a data-toggle="tab" href="#" title="">TBD 4</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="count_mod">
		<?php $this->load->view("special_pricing/basic"); ?>
	</div>
	<div class="tab-pane" id="">
		<?php //$this->load->view("special_pricing/list_actions"); ?>
	</div>
	<div class="tab-pane" id="">
		<?php //$this->load->view("special_pricing/mci"); ?>
	</div>
	<div class="tab-pane" id="">
		<?php //$this->load->view("special_pricing/bulk_actions"); ?>
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>
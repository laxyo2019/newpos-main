<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<ul class="nav nav-tabs" data-tabs="tabs">
	<li class="active" role="presentation">
		<a data-toggle="tab" href="#basic_mod" title="Basic Plans">Basic</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="count_mod">
		<?php $this->load->view("offers/basic"); ?>
	</div>
</div>

<?php $this->load->view("partial/footer"); ?>
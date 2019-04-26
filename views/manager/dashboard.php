<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>
<div class="tab-content">
<div class="tab-pane fade in active" id="mh_count">
<div class="row">
	<div class="col-sm-3">
		<div class="column">
			<center>
			<div class="card" style="background-image: linear-gradient(to bottom,#01e6e647, #00c3cc); min-height:200px;">
				<br>
				<h3>Count Actions</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/count_actions'); ?>"  title="Count Actions"><span class="fa fa-tags" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-sm-3 min_height">
		<div class="column">
			<center>       
			<div class="card" style="background: linear-gradient(to bottom,#ccb3006e, #ffcc66);min-height:200px;">
				<br>
				<h3>List Actions</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/list_actions'); ?>"  title="List Actions"><span class="fa fa-briefcase" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-sm-3 min_height">
		<div class="column">
			<center>       
			<div class="card" style="background-image: linear-gradient(to bottom,#efb1ab, #f77b7b);background-color: #f77b7b;min-height:200px;">
				<br>
				<h3>MCI</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/mci'); ?>"  title="MCI"><span class="fa fa-qrcode" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-sm-3" >
		<div class="column">
			<center>       
			<div class="card" style="background-image: linear-gradient(to bottom, #c7a9ef , #bf38d8);min-height:200px;">
				<br>
				<h3>Bulk Actions</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/bulk_actions'); ?>"  title="Bulk Actions"><span class="fa fa-tasks" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="clearfix" style="margin-top:20px;margin-bottom:30px;"></div>
	<div class="col-sm-3" >
		<div class="column">
			<center>       
			<div class="card" style="background-image: linear-gradient(to bottom, #c7a9ef , #bf38d8); min-height:200px;">
				<br>
				<h3>Reports</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/reports'); ?>"  title="Reports"><span class="fa fa-align-justify" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-sm-3" >
		<div class="column">
			<center>       
			<div class="card" style="background: linear-gradient(to bottom,#01e6e647, #00c3cc);min-height:200px;">
				<br>
				<h3>Extras</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/extras'); ?>"  title="Cashiers"><span class="fa fa-cog" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
	<div class="col-sm-3" >
		<div class="column">
			<center>       
			<div class="card" style="background-image: linear-gradient(to bottom,#fbefd6, #ffcc66);background-color: #f77b7b;min-height:200px;">
				<br>
				<h3>Inventory</h3>
				<h1><a  href="<?php echo site_url('manager/load_tab_view/inventory'); ?>"  title="Inventory"><span class="fa fa-folder-open" style="color: white;"></span></a></h1>
				<br>
			</div>
			</center>
		</div>
	</div>
</div>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
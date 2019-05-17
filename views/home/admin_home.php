<?php $this->load->view("partial/header"); ?>
<ul class="nav nav-tabs" data-tabs="tabs" id="shop_tab">

	<?php foreach($shops as $shop) {?>
	<li class="" role="presentation">
		
		<a data-toggle="tab" href="javascript:void(0)" onclick='count_data(<?php echo $shop->location_id.",".$shop->location_owner;?>)' title='<?php echo $shop->alias?>'><?php echo $shop->location_name;?></a>
	</li>
	<?php } ?>
</ul>
<br>
	<div class="tab-content">
    <div class="tab-pane fade in active" id="mh_count">
		<div class="row">
				<div class="column">
					<div class="col-md-4">
						<center>
						<div class="card"style="background-color: #00cccc;">
							<br>
							<h3>Current Stock</h3>
							<h1><span class="fa fa-tags" style="color: white;"></span></h1>
							<h1 id="itemcount" class="loader_wait"></h1>
						<br>
							</div>
					</center>
					</div>
					</div>
					<div class="col-md-4">
					<div class="column">
							<center>       
							<div class="card" style="background-color: #ffcc66;">
									<br>
									<h3>Today's Sales</h3>
									<h1><span class="fa fa-shopping-cart" style="color: white;"></span></h1>
									<h1 id="dailySales" class="loader_wait"></h1>
							<br>
							</div>
							</center>
					</div>
					</div>
					<div class="col-md-4">
					<div class="column">
						<center>
						<div class="card" style="background-color: #ff704d;">
							<br>
							<h3>Today's Earning</h3>
							<h1><span class="fa fa-inr" style="color: white;"></span></h1>
							<h1 id="totalSales" class="loader_wait"></h1>
					<br>
					</div>
					</center>
					</div>
			</div>
		</div>
		</div>
</div>
<?php $this->load->view("partial/footer");?>
<script>
$(document).ready(function(){
	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
	$("#shop_tab li:first-child").addClass('active');
	count_data(4,7);
})
function count_data(loc,per){
	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/admin_count') ?>', {per:per,loc:loc},function(data){
    resp = $.parseJSON(data);
      $('#itemcount').html(resp.itemcount);
      $('#dailySales').html(resp.dailySales);
      $('#totalSales').html(resp.totalSales);
    });
}
</script>
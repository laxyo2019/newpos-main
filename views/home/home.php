<?php $this->load->view("partial/header"); ?>
<div class="row">
  <div class="column">
    <div class="col-md-4">
      <center>
       <div class="card"style="background-color: #00cccc;">
         <br>
        <h1><span class="glyphicon glyphicon-tags" style="color: white;"></span></h1>
        <h3>Item Count</h3>
        <h4>All Item Count</h4>
        <h1 id="itemcount">item</h1>
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
       <h1><span class="glyphicon glyphicon-shopping-cart" style="color: white;"></span></h1>
       <h3>Daily Sales</h3>
       <h4>Daily Sales Reports</h4>
       <h1 id="dailySales"></h1>
      <br>
      </div>
     </center>
  </div>
  </div>

  <div class="column">
    <div class="col-md-4">
      <center>
      <div class="card" style="background-color: #ff704d;">
        <br>
      <h1><span class="glyphicon glyphicon-user" style="color: white;"></span></h1>
      <h3>Our Customers</h3>
      <h4>Our All Customers</h4>
      <h1 id="customerCount"></h1>
     <br>
    </div>
    </center>
  </div>
</div>
<!-- old pos code -->

<!-- <h3 class="text-center"><?php echo $this->lang->line('common_welcome_message'); ?></h3>

<div id="home_module_list">
	<?php
	foreach($allowed_modules as $module)
	{
	?>
		<div class="module_item" title="<?php echo $this->lang->line('module_'.$module->module_id.'_desc');?>">
			<a href="<?php echo site_url("$module->module_id");?>"><img src="<?php echo base_url().'images/menubar/'.$module->module_id.'.png';?>" border="0" alt="Menubar Image" /></a>
			<a href="<?php echo site_url("$module->module_id");?>"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
		</div>
	<?php
	}
	?>
</div>
 -->
 <script type="text/javascript">
	$(document).ready(function(){
    $('#dailySales').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/item_count') ?>', function(data){
      $('#itemcount').html(data);
    });
    $.get('<?php echo site_url('home/daily_sales') ?>', function(data){
      $('#dailySales').html(data);
    });
    $.get('<?php echo site_url('home/customer_count') ?>', function(data){
      $('#customerCount').html(data);
    });
  });
</script>

<?php $this->load->view("partial/footer"); ?>

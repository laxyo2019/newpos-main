<?php $this->load->view("partial/header"); ?>
<div class="row">
  <div class="column">
    <div class="col-md-4">
      <center>
       <div class="card"style="background-color: #00cccc;">
         <br>
        <h1><span class="glyphicon glyphicon-tags" style="color: white;"></span></h1>
        <h3>Item Count</h3>
        <h4>All Item Report</h4>
        <h1 id="itemcount"></h1>
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
       <h4>Daily Sales Report</h4>
       <h1 id="dailySales"></h1>
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
      <h1><span class="glyphicon glyphicon-usd" style="color: white;"></span></h1>
      <h3>Total Sales</h3>
      <h4>Total Sales Report</h4>
      <h1 id="totalSales"></h1>
     <br>
    </div>
    </center>
  </div>
</div>
 <script type="text/javascript">
	$(document).ready(function(){
    $('#itemcount').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/item_count') ?>', function(data){
      $('#itemcount').html(data);

    });
    $('#dailySales').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/sales_count') ?>', function(data){
      $('#dailySales').html(data);
    });

    $('#totalSales').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/total_sales') ?>', function(data){
      $('#totalSales').html(data);
    });
  });
</script>

<?php $this->load->view("partial/footer"); ?>

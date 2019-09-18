<?php $this->load->view("partial/header"); ?>
<div class="row">
  <div class="column">
    <div class="col-md-4">
      <center>
       <div class="card"style="background-color: #00cccc;">
         <br>
         <h3>Current Stock</h3>
         <h1><span class="fa fa-tags" style="color: white;"></span></h1>
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
        <h3>Today's Sales</h3>
        <h1><span class="fa fa-shopping-cart" style="color: white;"></span></h1>
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
        <h3>Today's Earning</h3>
        <h1><span class="fa fa-inr" style="color: white;"></span></h1>
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

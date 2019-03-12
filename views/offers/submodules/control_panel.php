<?php $this->load->view("partial/header"); ?>
<div class="row">

    <span class="col-md-4 col-md-offset-8">
      <div class="form-group">
        <select class="form-control" id="location_id" onchange="cashier(this);">
        <option value="" >Select Location</option>
          <?php foreach($locations as $key=>$value): ?>
            <option value="<?php echo $key; ?>" ><?php echo strtoupper($value); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </span>
    <div class="clearfix"></div>
    <hr>
  <div id="shop_cpanel"></div>  
</div>
<script>
  function cashier(e){
    x = $(e).val();
    $("#shop_id").val(x);
    console.log(x);
    if(x!=""){
      $.ajax({ 
            url: "<?php echo site_url('offers/get_cashiers')?>",
            data: {loc_owner:x},
            success: function (data) {
                $('#shop_cpanel').html(data); 
            },
            error: function (data) {
                console.log('An error occurred.');
            },
        });
    }else{
      $('#shop_cpanel').html(""); 
    }
  }
</script>
<?php $this->load->view("partial/footer"); ?>
<?php 
	$name   = !empty($loc_name)?$loc_name:'';
  $loc_id = !empty($loc_id)?$loc_id:0;
 	$data   = $this->Offers_manage->get_cashier();
?>
<form id="loc_Cashier">
	<div class="row">
	  <div class='col-xs-12 form-group'>
	    <div class="col-xs-12">
	      <input id='location_names' readonly = 'true' value="<?php echo $name ; ?>"class='form-control input-sm'required='required'>
	      <input type="hidden" id='location_ids' name="location_ids" value='<?php echo $loc_id ; ?>' >
	      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	    </div>
	  </div>
	</div>
	<hr>
	<div class="row">
	  <div class='col-xs-12 form-group'>
	      <label class='col-xs-5'>Name : </label>
	      <div class="col-xs-12">
	        <select id='cashiers_name[]' name="cashiers_name[]" class="form-control" multiple="">
	          <?php 
	            foreach ($data as $row) { 
	              $cash_id = $row->id;
	          ?>
	            <option <?php if(in_array($cash_id , $id)){ echo "selected"; } ?> value="<?php echo $row->id; ?>"><?php echo $row->name ; ?></option>
	          <?php } ?>
	        </select>
	      </div>
	  </div>
	</div>
		<div class="col-xs-2 pull-right">
	    <button class="btn btn-sm btn-success"  id="cashier_save">Submit</button>
	</div>
</form>
	

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
	$('#loc_Cashier').on('submit', function (e) {
      e.preventDefault();
      $.ajax({
        type: 'post',
        url: '<?php echo site_url(); ?>manager/addLocationCashier',
        data: $('#loc_Cashier').serialize(),
        success: function (data) {
        	alert("Cashier Updated");
          location.reload();
        }
      });
    });
</script>
<!-- <script>
$(document).ready(function(){
  $('#cashier_save').on('click', function(){		
		var name = $('#cashiers_name').val();
    var location_id = $('#location_ids').val();
   
    $.post('<?php echo site_url($controller_name."/add_cashier_loc");?>', 
      {'name': name,'location_id':location_id},function(data) {
      	$('#close').click();
        	load_cashier();
    })
  });
})
</script> -->


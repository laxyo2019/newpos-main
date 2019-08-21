<?php $name   = !empty($loc_name)?$loc_name:'';
      $loc_id = !empty($loc_id)?$loc_id:0;
      $data   = $this->Offers_manage->get_cashier();
    
 ?>
<div class="row">
  <div class="form-group col-xs-12">
    <div class='col-xs-12 form-group'>
        <div class="col-xs-12">
          <input id='location_names' readonly = 'true' value="<?php echo $name ; ?>"class='form-control input-sm'required='required'>
          <input type="hidden" id='location_ids' value='<?php echo $loc_id ; ?>' >
        </div> 
    </div>
    </div>
 </div>
<hr>
<div class="row">
  <div class='col-xs-12 form-group'>
      <label class='col-xs-5'>Name : </label>
      <div class="col-xs-7">
        
        <select id='cashiers_name' class="form-control">
          <?php 
              foreach ($data as $row) { 
                  $cash_id = $row->id;
                ?>
                <option <?php if(in_array($cash_id , $id)){ echo "disabled"; } ?> value="<?php echo $row->id; ?>"><?php echo $row->name ; ?></option>
          <?php  } ?>

        </select>
      </div>
  </div>
</div>
<div class="col-xs-2 pull-right">
    <button class="btn btn-sm btn-success" id="cashier_save">Submit</button>
</div>
<script>
  $(document).ready(function(){
    
    $('#cashier_save').on('click', function(){
			
			var name = $('#cashiers_name').val();
      var location_id = $('#location_ids').val();
     
      $.post('<?php echo site_url($controller_name."/add_cashier_loc");?>', 
         {'name': name,'location_id':location_id}, function(data) {
         $('#close').click();
            load_cashier();
        })
        });
      })
      

</script>


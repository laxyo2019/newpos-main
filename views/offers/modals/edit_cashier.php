<?php
   $this->db->select('GROUP_CONCAT(location_owner) as location_owner');
   $this->db->from('cashier_shops');
   $this->db->join('stock_locations','stock_locations.location_owner = cashier_shops.person_id');$this->db->where('cashier_id',$detail->id);
  $locations = $this->db->get()->row()->location_owner; 
 $locations_arr = explode(',',$locations);
?>
<div class="row">
<div class='col-xs-12 form-group'>
    <label class='col-xs-5'>Name : </label>
    <div class="col-xs-7">
        <?php echo form_input(array(
        'id'=>'cashier_name',
        'placeholder'=>'Name',
        'class'=>'form-control input-sm',
        'required'=>'required',
        'value'=>$detail->name
        ));
        ?>
    </div>
</div>
<div class='col-xs-12  form-group'>
    <label class='col-xs-5'>Webkey: </label>
    <div class="col-xs-7">
        <?php echo form_input(array(
        'id'=>'webkey',
        'placeholder'=>'Cashier Wekkey',
        'class'=>'form-control input-sm',
        'required'=>'required',
        'value'=>$detail->webkey
        ));
        ?>
    </div>
</div>
<div class='col-xs-12  form-group'>
    <label class='col-xs-5'>Contact Number: </label>
    <div class="col-xs-7">
        <?php echo form_input(array(
        'id'=>'contact',
        'placeholder'=>'Cashier Contact',
        'class'=>'form-control input-sm',
        'required'=>'required',
        'value'=>$detail->contact
        ));
        ?>
    </div>
</div>
<div class="col-xs-2 pull-right">
    <button class="btn btn-sm btn-success" id="cashier_save">Submit</button>
</div>
</div>
<hr>
<div class="row">
<div class='col-sm-12'>
<?php foreach($shops as $key=>$value):?>
    <div class='col-sm-6'>
        <input 
            type='checkbox' 
            class='location_checkbox' 
            value='<?php echo $key; ?>'
            <?php if(in_array($key,$locations_arr)){
                echo 'checked';
             }?>>
        <?php echo strtoupper($value); ?>
    </div>
 <?php endforeach; ?>
</div>
</div>
<script>
  $(document).ready(function(){
    $('#cashier_locations').select2();

    $('.location_checkbox').on('change',function(){
        action = $(this).prop("checked")==true ? 'insert' : 'delete';
        loc_id = $(this).val();
        cashier_id = <?php echo $detail->id;?>;
        $.post('<?php echo site_url();?>offers/edit_loc/'+action,{loc_id:loc_id,cashier_id:cashier_id},function(data){
            swal({
                    title: "",
                    text: data,
                    icon: "success",
            });
        });
    });
    $('#cashier_save').on('click', function(){
	  var shops = $('#cashier_locations').val();
      if(typeof(shops) != "undefined" && shops !== null) {
        shops.toString();
      }
	    var name = $('#cashier_name').val();
        var contact = $('#contact').val();
        var webkey = $('#webkey').val();
        var cashier_id = <?php echo $detail->id;?>;
      if(name==''||contact==''||webkey==''){
        swal({
                    title: "",
                    text: 'Fill all Details.',
                    icon: "warning",
            });
      }
      else if(webkey.length!=4){
        swal({
                    title: "",
                    text: 'Webkey must be of exact 4 digits.',
                    icon: "warning",
            });
      }else{
        console.log(shops);
            $.post('<?php echo site_url($controller_name."/cashier_edit");?>', {'cashier_id' : cashier_id, 'webkey':webkey,'shops': shops, 'name': name, 'contact': contact}, function(data) {
                alert(data);
                $('#close').click();
                load_cashier_details();
             });
      }
	});
  });
</script>

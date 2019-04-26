<div class="row">
  <div class="form-group col-xs-8">
    <select class="form-control" multiple="multiple" id="cashier_locations">
      <?php foreach($shops as $key=>$value):?>
        <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-xs-2 pull-right">
    <button class="btn btn-sm btn-success" id="cashier_save">Submit</button>
  </div>
</div>
<hr>
<div class="row">
<div class='col-xs-12 form-group'>
    <label class='col-xs-5'>Name : </label>
    <div class="col-xs-7">
        <?php echo form_input(array(
        'id'=>'cashier_name',
        'placeholder'=>'Name',
        'class'=>'form-control input-sm',
        'required'=>'required'
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
        'required'=>'required'
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
        'required'=>'required'
        ));
        ?>
    </div>
</div>

</div>

<script>
  $(document).ready(function(){
    $('#cashier_locations').select2();

    $('#cashier_save').on('click', function(){
			var shops = $('#cashier_locations').val();
      if(typeof(shops) != "undefined" && shops !== null) {
        shops.toString();
      }
			var name = $('#cashier_name').val();
      var contact = $('#contact').val();
      var webkey = $('#webkey').val();
      if(shops==''||name==''||contact==''||webkey==''){
        alert('Fill all details');
      }else if(webkey.length!=4){
        swal({
                    title: "",
                    text: 'Webkey must be of exact 4 digits',
                    icon: "warning",
            });
      }else{
        console.log(shops);
        $.post('<?php echo site_url($controller_name."/cashier_save");?>', {'webkey':webkey,'shops': shops, 'name': name, 'contact': contact}, function(data) {
          $('#close').click();
          if(data==1){
              swal({
                  title: "",
                  text: "Duplicate Entry",
                  icon: "error",
                });
          }else{
            alert("Created Successfully.");
                $('#close').click();
                load_cashier_details();
          }
        });
      }
      
		});
  });
</script>

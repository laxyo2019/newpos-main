<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <div class="form-group form-group-sm">
      <?php echo form_input(array(
      'name'=>'barcode',
      'id'=>'barcode',
      'class'=>'form-control input-sm',
      'placeholder'=>'Input Item Barcode')
      );?>
    </div>
    <div class="form-group form-group-sm">
        <select name="shop" id="shop" class="form-control">
          <option value="">Select shop</option>
          <?php foreach($shops as $row)
          {
            echo '<option value="'.$row['person_id'].'">'.$row['username'].'</option>';
          }
          ?>
        </select>
    </div>
    <div class="form-group form-group-sm">
      <?php echo form_input(array(
      'name'=>'price',
      'id'=>'price',
      'class'=>'form-control input-sm',
      'placeholder'=>'Input Item Price')
      );?>
    </div>
    <div class="form-group">
      <div class='input-group date' id='datetimepicker1'>
          <input type='text' class="form-control" id="validity" />
          <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
    </div>
    <button class="btn btn-success" id="submit">Submit</button>
  <input type="hidden" id="item_id" value>
</div>

<script>
  $(document).ready(function() {
    $('#datetimepicker1').datetimepicker();
    $('#submit').on("click",function(){
      var barcode = $('#barcode').val();
      var shop = $('#shop').val();
      var price = $('#price').val();
      var validity = $('#validity').val();
      // console.log(barcode,shop,price,validity);
      $.post('<?php echo site_url($controller_name."/save");?>', {'barcode': barcode, 'shop': shop,'price':price,'validity':validity}, function(data) {
                alert(data);
              });
      });
  });
</script> 
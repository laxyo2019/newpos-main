<style>
.select_bg{
    background: #8f9498;
    color: #fff;
    padding: 10px;
    margin-top: 10px;
}
</style>
<div clsss="row">
<div class="col-sm-12">
<?php echo form_open_multipart('offers/sub_gc_detail','target="_blank"'); ?>
   <div class="col-sm-5">
        <div class="form-group">
            <label>Voucher Value</label>
            <select class="form-control" id="vc_value" name="vc_value" required>
                <option>Select Value</option>
            </select>
        </div>
        <div class="form-group">
            <label>Total Vouchers</label>
            <input class="form-control" type="text" placeholder="Vouchers Count" name="vc_count" id="vc_count" required>
            <span class="text-danger" id="errmsg"></span>
        </div>
        <div class='form-group'>
            <label>Expiry date</label>
            <div class='input-group date datetimepicker' id='datetimepicker1'>
                <input type='text' class="form-control" id="vc_exp_date" placeholder="Start Time" name="vc_exp_date" required>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
      </div>
   </div>
   <div class="col-sm-7">
        <div class="form-group">
            <label>Select BackgroundColor</label>
            <div id="slider">
                <img class="" id="vc_bg_img" src="<?php echo base_url('images/vouchers/gift_vc_bg1.png');?>" style="max-width:100%;">
                <div class="clearfix"></div>
                <select name="vc_bg_img" class="select_bg" onchange="change_bg_image(this)">
                <?php for($i=1;$i<=6;$i++){?>
                    <option value="gift_vc_bg<?php echo $i; ?>.png">Background Image <?php echo $i;?></option>
                <?php } ?>
                </select>
                <button class="btn btn-info pull-right" type="submit" style="margin: 10px 0 0 15px ;">Submit</button>
                <input type="hidden" value="1" id="current_bg">
            </div>
        </div> 
   </div>
   <div class="clearfix"></div>
</form>
</div>
</div>
<script>
function change_bg_image(e){
    var x = $(e).val();
    var path = "<?php echo base_url('images/vouchers/" + x +"');?>";
    $("#vc_bg_img").attr('src',path);
}

$('#vc_value').on( 'mouseenter',function(){
    $.get('<?php echo site_url('offers/get_gift_vc_options')?>',
    function(data){
        $('#vc_value').html(data);
     }
   ); 
});

$('.datetimepicker').datetimepicker({
      // format: 'dd.mm.yyyy',
      // minView: 2,
      // maxView: 4,    
      autoclose: true
    });

$(document).ready(function () {
    
    $("#vc_count").on("blur",function(){
      var x =  $("#vc_count").val();
      if(x==0){
        $("#errmsg").html("Value must be more than 0").show().fadeOut("slow");
        $("#vc_count").val('');
      }
    }); 

    $("#vc_count").on("contextmenu",function(){
       return false;
    }); 
    $('#vc_count').on("paste",function(e) {
        return false;
   });
  //called when key is pressed in textbox
  $("#vc_count").keypress(function (e) {
      var digit  = $('#vc_count').val().length;
      if (digit==2) {
        //display error message
        $("#errmsg").html("Only two digits are allowed").show().fadeOut("slow");
        return false;
     }
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Only numbers are allowed").show().fadeOut("slow");
        return false;
    }
   });
});
</script>
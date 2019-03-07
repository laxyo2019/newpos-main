<div clsss="row">
<div class="col-md-12">
<form onsubmit="return false">
   <div class="col-md-3">
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
   <div class="col-md-5 col-md-offset-2">
        <div class="form-group">
            <label>Select BackgroundColor</label>
            <div id="slider">
                <img class="" id="vc_bg_img" src="<?php echo base_url('images/vouchers/gift_vc_bg1.png');?>" width="400px">
                <div class="clearfix"></div>
                <a href="javascript:void(0)" class="btn btn-sm btn-success pull-left" style="margin-top:10px;" onclick="change_bg_img();">New background</a>
                <input type="hidden" value="1" id="current_bg">
            </div>
        </div> 
   </div>
   <div class="clearfix"></div>
   <div class="col-sm-offset-1">
        <br>
        <button class="btn btn-info" type="submit" style="margin-left:15px;">Submit</button>
    </div>
</form>
</div>
</div>
<script>
$('#vc_value').on( 'mouseenter',function(){
    $.get('<?php echo site_url('offers/get_gift_vc_options')?>',
    function(data){
        $('#vc_value').html(data);
     }
   ); 
});
$("form").submit(function(e){
    e.preventDefault();
    var vc_value = $('#vc_value').val();
    var vc_count = $('#vc_count').val();
    var vc_exp_date = $('#vc_exp_date').val();
    var vc_bg_img = $('#vc_bg_img').attr('src').substring(($('#vc_bg_img').attr('src')).lastIndexOf('/')+1);
    $.post('<?php echo site_url('offers/sub_gc_detail') ?>',{vc_value:vc_value,vc_count:vc_count,vc_exp_date:vc_exp_date,vc_bg_img:vc_bg_img},
        function(data){ 
        alert(data);
    });
});

$('.datetimepicker').datetimepicker({
      // format: 'dd.mm.yyyy',
      // minView: 2,
      // maxView: 4,    
      autoclose: true
    });

function change_bg_img(e){
  
    var x = parseInt( $("#current_bg").val());
    x = x==6?1:x+1;
   $("#current_bg").val(x);
    var path = "<?php echo base_url('images/vouchers/gift_vc_bg" + x + ".png');?>";
    $("#vc_bg_img").attr('src',path);

}
$(document).ready(function () {
    $('#myTable').DataTable();
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
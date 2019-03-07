<style>
#gc_detail {
    margin-top:10px;
}
</style>
<div clsss="row">
<div class="col-md-12">
<table id="myTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Voucher Value</th>
        <th>Expiry Date</th>
        <th>Redeem At</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
       
    </tbody>
</table>
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
        $('#myTable').DataTable();
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
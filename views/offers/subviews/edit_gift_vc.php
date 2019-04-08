<style>
.select_bg{
    background: #8f9498;
    color: #fff;
    padding: 10px;
    margin-top: 10px;
}
.success{
    color: #fff;
    background: #18bc9c;
    display: block;
    padding: 10px;
    margin: 10px 0 32px;
    opacity: .7;
    font-size: 15px;
}
</style>

<?php 
$this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value');
$this->db->from('voucher_gifts');
$this->db->where('voucher_gifts.id',$id);
$this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
$voucher = $this->db->get()->row();
//echo "<pre>";
//print_r($voucher); die;
?>
<form id="create_vc" onsubmit="return false;" >
<input value="<?php echo $id ; ?>" type="hidden" id="id">
   <div class="col-sm-5">
        <div class="form-group">
            <label>Voucher Value</label>
            <input class="form-control"  value="<?php echo $voucher->vc_value;?>" readonly>
        </div>
        <div class="form-group">
            <label>Voucher Code</label>
            <input class="form-control"  value="<?php echo $voucher->code;?>" readonly>
        </div>
        <div class='form-group'>
            <label>Expiry date</label>
            <div class='input-group date datetimepicker' id='datetimepicker1'>
                <input type='text' class="form-control" value="<?php echo $voucher->exp_date?>" id="vc_exp_date" placeholder="Start Time" name="vc_exp_date" required>
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
                <img class="" id="vc_bg_img" src="<?php echo base_url()."images/vouchers/".$voucher->bg_img.".png";?>" style="max-width:100%;">
                <div class="clearfix"></div>
                <select name="vc_bg_img" class="select_bg" onchange="change_bg_image(this)">
                <?php for($i=1;$i<=6;$i++){?>
                    <option value="gift_vc_bg<?php echo $i; ?>" <?php if("gift_vc_bg".$i==$voucher->bg_img){ echo "selected";}?>>Background Image <?php echo $i;?></option>
                <?php } ?>
                </select>
                <button id="save" class="btn btn-info pull-right" type="submit" style="margin: 10px 0 0 15px ;">Save changes</button>
            </div>
        </div> 
   </div>
   <div class="clearfix"></div>
</form>
<script>
$('.datetimepicker').datetimepicker({
      // format: 'dd.mm.yyyy',
      // minView: 2,
      // maxView: 4,    
      autoclose: true
    });

function change_bg_image(e){
    var x = $(e).val();
    var path = "<?php echo base_url('images/vouchers/" + x +".png"+"');?>";
    $("#vc_bg_img").attr('src',path);
}

$('#create_vc').submit(function(e){
    e.preventDefault();
    vc_exp_date = $('#vc_exp_date').val();
    id = $('#id').val();
    vc_bg_img =  $("[name='vc_bg_img']").val();
    $.post("<?php echo site_url('offers/edit_vc');?>" , {vc_exp_date:vc_exp_date,vc_bg_img:vc_bg_img,id:id},
    function(){
        $('#create_vc').before("<strong class='success'>Updated Successfully !!</strong> ");
        $('.success').fadeOut(1000,function(){
            location.reload(); 
        });
         
    })
})
</script>
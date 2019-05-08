<style>
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
    $this->db->select('expiry_date');
    $this->db->from('voucher_gifts');
    $this->db->where('voucher_gifts.id',$id);
    $voucher = $this->db->get()->row();
?>
<form id="create_vc" onsubmit="return false;" >
<input value="<?php echo $id ; ?>" type="hidden" id="id">
   <div class="col-sm-12">
        <div class='form-group'>
            <label>Expiry date</label>
            <div class='input-group date datetimepicker' id='datetimepicker1'>
                <input type='text' class="form-control" value="<?php echo $voucher->expiry_date?>" id="vc_exp_date" placeholder="Start Time" name="vc_exp_date" required>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
   <button id="save" class="btn btn-info pull-right" type="submit" style="margin: 10px 0 0 15px ;">Save changes</button>
</form>
<script>
$('.datetimepicker').datetimepicker({
       format: 'yyyy-mm-dd',
      // minView: 2,
      // maxView: 4,    
      autoclose: true
    });


$('#create_vc').submit(function(e){
    e.preventDefault();
    vc_exp_date = $('#vc_exp_date').val();
    id = $('#id').val();
    $.post("<?php echo site_url('offers/edit_gift_vc');?>" , {vc_exp_date:vc_exp_date,id:id},
    function(){
        $('#create_vc').before("<strong class='success'>Updated Successfully !!</strong> ");
        $('.success').fadeOut(1000,function(){
            location.reload(); 
        });
         
    })
})
</script>

                
                

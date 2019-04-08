<script>
    dialog_support.init("a.modal-dlg, button.modal-dlg-wide");
</script>
<div clsss="row">
<div class="col-sm-12">
<?php // echo form_open_multipart('offers/sub_gc_detail'); ?>
<form id="form" onsubmit="return false;" >
   <div class="col-sm-7">
        <div class="form-group">
            <label>Voucher Value</label>
            <select class="form-control" id="vc_value" name="vc_value" required>
                <option>Select Value</option>
            </select>
        </div>
   </div>
   <div class="col-sm-5">
        <div class="form-group">
            <label></label>
            <button class="btn btn-info pull-right" type="submit" style="margin: 25px 0 0 15px ;">Submit</button>
            <input type="hidden" value="1" id="current_bg">
        </div> 
   </div>
   <div class="clearfix"></div>
</form>
</div>
</div>
<script>
$('#form').on('submit',function(e){
    e.preventDefault();
    var vc_value = $('#vc_value').val();
    $.post('<?php echo site_url('offers/sub_gc_detail');?>',{vc_value:vc_value},function(data){
        location.reload();
    });
});
$('#vc_value').on( 'mouseenter',function(){
    $.get('<?php echo site_url('offers/get_gift_vc_options')?>',
    function(data){
        $('#vc_value').html(data);
     }
   ); 
});
</script>
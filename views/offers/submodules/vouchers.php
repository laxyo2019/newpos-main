<?php $this->load->view("partial/header");?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="javascript:void(0)" onclick="load_view('fill_gc_detail');" title="Gift Vouchers">Gift Vouchers</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#" title="Reward Vouchers">Reward Vouchers</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#" title="Earned Vouchers">Earned Vouchers</a>
            </li>
        </ul>
    </div>
    <hr>
    <div class="clearfix"> </div>
    <div class="tab-content content" style="margin-top:30px;">
      
    </div>
</div>
</div>
<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    load_view('fill_gc_detail');
})
function load_view(view){
    $.post('<?php echo base_url();?>offers/load_subview/'+view,{},function(data){
        $('.content').html(data);
    });
    
}
</script>



















<?php $this->load->view("partial/header");?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="#gift_vc" title="Gift Vouchers">Gift Vouchers</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#reward_vc" title="Reward Vouchers">Reward Vouchers</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#earned_vc" title="Earned Vouchers">Earned Vouchers</a>
            </li>
        </ul>
    </div>
    <hr>
    <div class="clearfix"> </div>
    <div class="tab-content" style="margin-top:30px;">
        <div class="tab-pane fade in active" id="gift_vc">
            <?php $this->load->view("offers/subviews/fill_gc_detail"); ?> 
        </div>
        <div class="tab-pane" id="reward_vc">
            <?php echo "reawrd"; ?>
        </div>
        <div class="tab-pane" id="earned_vc">
            <?php echo "earn"; ?>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
})
</script>



















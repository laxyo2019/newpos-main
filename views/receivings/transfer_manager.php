<?php $this->load->view("partial/header");?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="java_script:void(0)" onclick="pendingTransfer()" title="Pending Transfers">Pending Transfers</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="java_script:void(0)" onclick="transferLog()"	title='Challan List'>Transfer Log</a>
            </li>
            <?php
			if(!empty($pending_transfers)){ 
				if($pending_transfers){
                echo '<li role="presentation">
                        <a data-toggle="tab" href="java_script:void(0)" onclick="stockIn()" title="Receive Items">Stock In</a>
                    </li>';
                } 
            }
		    ?>
        </ul>
    </div>
    <hr>
    <div class="clearfix"> </div>
    <div class="content" style="margin-top:30px;">
    
    </div>
</div>
</div>
<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    $('.content').html('<img src="<?php echo base_url('images/loadercyan.gif'); ?>" alt="loading" />');
    $.post('<?php echo site_url($controller_name."/get_transfer_status");?>',{},function(data){
        $('.content').html(data);
    });
})
function stockIn(){
    $('.content').html('<img src="<?php echo base_url('images/loadercyan.gif'); ?>" alt="loading" />');
    $.post('<?php echo site_url($controller_name."/stock_in");?>',{},function(data){
        $('.content').html(data);
    });
}
function pendingTransfer(){
    $('.content').html('<img src="<?php echo base_url('images/loadercyan.gif'); ?>" alt="loading" />');
    $.post('<?php echo site_url($controller_name."/get_transfer_status");?>',{},function(data){
        $('.content').html(data);
    });
}
function transferLog(){
    $('.content').html('<img src="<?php echo base_url('images/loadercyan.gif'); ?>" alt="loading" />');
    $.post('<?php echo site_url($controller_name."/get_all_challans");?>',{},function(data){
        $('.content').html(data);
    });
}
</script>



















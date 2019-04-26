<?php $this->load->view("partial/header"); ?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="javascript:void(0)" onclick="all_sheets('not_processed');" title="Pending Sheets">Sheet Uploads</a>
            </li>
            <li role="presentation" id="">
                <a data-toggle="tab"  href="javascript:void(0)" onclick="all_sheets('processed');" title="Discarded Sheets">Sheet Processed</a>
            </li>
        </ul>
    </div>
    <hr>
    <div class="clearfix"> </div>
    <div class="content" style="margin-top:30px;">
    </div>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
<script>

function all_sheets(status){
    $('.content').html("<img src='<?php echo base_url('images/loader_icon1.gif'); ?>' alt='Loading'>");
 $.post('<?php echo site_url();?>manager/items_upload/'+status,{},function(data){
    $('.content').html(data);
 });
}

function sheet_data(sheet_id,sheet_status){
    if(sheet_status=='not_processed'){
        $.post('<?php echo site_url();?>manager/items_upload_data/'+sheet_id,{},function(data){
            $('.content').html(data);
        });
    }else{
        $.post('<?php echo site_url();?>manager/items_processed_data/'+sheet_id,{},function(data){
            $('.content').html(data);
        });
    }
   
}

$(document).ready( function () {
    all_sheets('not_processed');
})
</script>
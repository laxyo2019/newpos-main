<style>
</style>
<div clsss="row">
<div class="col-md-12">
    <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url("offers/create_offer_bundle"); ?>' title='Create Offer Bundle'> Create Bundle </button>
    <div class="clearfix"></div>
    <hr>
   <div class="col-sm-12 offer_bundle_table">
   </div>
</div>
</div>

<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    insert_table();
})
function insert_table(){
    $.post('<?php echo site_url('offers/view_offer_bundle_table')?>',{},function(data){
        $('.offer_bundle_table').html(data);
    });
}
function delete_bundle_group(id){
var result = confirm("Are you sure to delete?");
    if (result) {
        $.post('<?php echo site_url('offers/delete_bundle_group')?>',{id:id},function(response){
            $.notify(response, { type: response ? 'success' : 'danger', delay: 1 });
            $('#offers').load('<?php echo site_url('offers/load_offer_bundle');?>',function(data){
                insert_table();
            }); 
        })
    }
}
</script>






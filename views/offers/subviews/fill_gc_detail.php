<style>
.delete_icon:hover{
    color:red!important;
}
</style>
<div clsss="row">
<div class="col-md-12">
    <div class="btn btn-danger pull-right" onclick="delete_all_gift_vc()" style="margin-left:10px;"> Delete All </div>
    <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url("offers/create_voucher"); ?>' title='Create New Vouchers'> Create </button>
    <div class="clearfix"></div>
    <hr>
    <table id="vc_table"></table>
</div>
</div>
<script>
$(document).ready(function () {
    dialog_support.init("a.modal-dlg, button.modal-dlg-wide");
    $.post('<?php echo base_url('offers/all_gc_views');?>',{},function(data){
        $('#vc_table').html(data); //inserting all rows in Table
    });
    $('#vc_table').DataTable({
        bSortable: true,
    });
})
function delete_gift_vc(id){
    var del = confirm("Are you sure to delete this?");
    if(del){
        $.post('<?php echo base_url();?>/offers/delete_gift_vc',{id:id},
        function(data){
            $.notify(data, { type: data ? 'success' : 'danger'} );
            setTimeout(function(){ window.location = '<?php echo base_url();?>offers/view_vouchers'; }, 400)
        })
    }
}
function delete_all_gift_vc(){
    var del = confirm("Are you sure to delete All vouchers?");
    if(del){
        $.post('<?php echo base_url();?>/offers/delete_all_gift_vc',{},
        function(data){
            $.notify(data, { type: data ? 'success' : 'danger'} );
            setTimeout(function(){ window.location = '<?php echo base_url();?>offers/view_vouchers'; }, 400)
        })
    }
}


</script>
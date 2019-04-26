<style>
.delete_icon:hover{
    color:red!important;
}
</style>
<?php
$vc_value = $this->db->select()->get('vc_gift_master')->result();
?>
<div clsss="row">
<div class="col-md-12">
    <div class="btn btn-danger pull-right" onclick="delete_all_gift_vc()" style="margin-left:10px;"> Delete All </div>
    <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url("offers/create_voucher"); ?>' title='Create New Vouchers'> Create </button>
    <div class="clearfix"></div>
    <hr>
    <div class='col-sm-3'>
        <select class='form-control' name=''>
            <option value=''>Select Voucher Value</option>
            <?php foreach($vc_value  as $row):?>
                <option value='<?php echo $row_id; ?>'><?php echo $row->vc_value;?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class='col-sm-2' style='position:relative'>
        <label class='form-control' style='margin:0'> Redeem </label>
        <input type='checkbox' name='redeemed' style="position: absolute;top: 12px;right: 32px;">
    </div>
    <div class='col-sm-2'>
         <input class='form-control'>
    </div>
    <div class='col-sm-2' style='position:relative'>
    <span class="pull-right">
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'')); ?>
  </span>
    </div>
    <div class='col-sm-2' style='position:relative'>
        <button class='col-sm-12 btn btn-warning'>Filter</button>
    </div>
    <div class='clearfix'></div>
    <hr>
    <div id="gc_tableArea" class='col-sm-12'></div>
</div>
</div>
<script>
$(document).ready(function () {
    dialog_support.init("a.modal-dlg, button.modal-dlg-wide");
    $.post('<?php echo base_url('offers/all_gc_views');?>',{},function(data){
        $('#gc_tableArea').html(data); //inserting all rows in Table
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
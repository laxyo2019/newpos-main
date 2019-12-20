<style>
.delete_icon:hover{
    color:red!important;
}
</style>
<?php

$vc_value = $this->db->select()->get('vc_gift_master')->result();
//print_r($vc_value); die;
?>
<div clsss="row">
<div class="col-md-12">
    <!-- <a class="btn btn-info pull-right" id="print" style="margin-left:10px;">Print</a> -->
    <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url("offers/create_voucher"); ?>' title='Create New Vouchers'> Create </button>
    <div class="clearfix"></div>
    <hr>
    <form>
        <div class='col-sm-4'>
            <select class='form-control' name='voucher' id="select_filter" data-attribute="age">
                <option value='0'>Select Voucher Value</option>
                <?php foreach($vc_value  as $row):?>
                    <option value='<?php echo $row->id; ?>'><?php echo $row->vc_value;?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class='col-sm-4' style='position:relative'>
            <label class='form-control' style='margin:0'> Redeem </label>
            <input type='checkbox' name='redeemed' id="chk_redeemed" style="position: absolute;top: 12px;right: 32px;">
        </div>
        <!-- <div class='col-sm-2'>
             <input class='form-control'>
        </div>
        <div class='col-sm-2' style='position:relative'>
            <span class="pull-right">
                <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'')); ?>
            </span>
        </div> -->
        <div class='col-sm-2' style='position:relative'>
            <button type="submit" class='col-sm-12 btn btn-warning' name="submit">Filter</button>
        </div>
    </form>
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

    
    
});

/*My Work*/
$(function () {
    $('form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '<?php echo base_url(); ?>Offers/all_gc_views',
            data: $('form').serialize(),
            success: function (data) {
                document.getElementById("gc_table").style.display = 'none';
                document.getElementById("gc_table_wrapper").style.display = 'none';
                document.getElementById("printbtn").style.display = 'none';
                document.getElementById("dialogDiv").innerHTML = data;

                $('#dialogDiv table').DataTable({
                    "pageLength": 18,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            }
        });
    });
});
/*My Work*/

// function delete_gift_vc(id){
//     var del = confirm("Are you sure to delete this?");
//     if(del){
//         $.post('<?php echo base_url();?>/offers/delete_gift_vc',{id:id},
//         function(data){
//             $.notify(data, { type: data ? 'success' : 'danger'} );
//             setTimeout(function(){ window.location = '<?php echo base_url();?>offers/view_vouchers'; }, 400)
//         })
//     }
// }
// function delete_all_gift_vc(){
//     var del = confirm("Are you sure to delete All vouchers?");
//     if(del){
//         $.post('<?php echo base_url();?>/offers/delete_all_gift_vc',{},
//         function(data){
//             $.notify(data, { type: data ? 'success' : 'danger'} );
//             setTimeout(function(){ window.location = '<?php echo base_url();?>offers/view_vouchers'; }, 400)
//         })
//     }
// }


</script>
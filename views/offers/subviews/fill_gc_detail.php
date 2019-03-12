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
    <table id="vc_table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Voucher Value</th>
            <th>Expiry Date</th>
            <th>Redeem At</th>
            <th>Created At</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value');
                $this->db->from('voucher_gifts');
                $this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
                $vc_info = $this->db->get()->result();
                foreach($vc_info as $row){
                echo '<tr>
                      <td>'.$row->id.'</td>
                      <td>'.$row->title.'</td>
                      <td>'.$row->vc_value.'</td>
                      <td>'.$row->exp_date.'</td>
                      <td>'.$row->redeem_at.'</td>
                      <td>'.$row->created_at.'</td>
                      <td><a href="javascript:void(0)" class="fa fa-pencil-square edit" title="Edit" style="font-size:20px;"></a></td>
                      <td><a href="javascript:void(0)" onclick = "delete_gift_vc('.$row->id.');" class="fa fa-trash text-danger delete_icon" title="Delete" style="font-size:20px;"></a></td>
                    </tr>';
            ?>
            <?php 
                }
            ?>
        </tbody>
    </table>
</div>
</div>
<script>
$(document).ready(function () {
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
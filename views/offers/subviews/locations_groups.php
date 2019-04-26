<style>
</style>
<div clsss="row">
<div class="col-md-12">
    <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url("offers/create_locations_group"); ?>' title='Create Locations group'> Create group </button>
    <div class="clearfix"></div>
    <hr>
    <div class='col-sm-12 table_div'>
    </div>
</div>
</div>

<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    insert_tbl();
})

function insert_tbl(){
    $.post('<?php echo base_url();?>/offers/view_location_group_table',{},function(data){
        $('.table_div').html(data);
    });
}
function delete_loc_group(id){
    var result = confirm("Are you sure to delete?");
        if (result) {
            $.post('<?php echo site_url('offers/delete_loc_group')?>',{id:id},function(response){
                $.notify(response, { type: response ? 'success' : 'danger', delay: 1 });
                $('#locations').load('<?php echo site_url('offers/load_locations');?>',function(data){}); 
            })
        }
    
}

</script>






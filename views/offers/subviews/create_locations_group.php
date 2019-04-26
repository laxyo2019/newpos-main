<div class="row">
<div class="col-sm-12">
    <form id="submit_loc_group" onsubmit="return false;">
    <div class="form-group">
        <label>Title  : </label>
        <input type="text" id="title" class="form-control" placeholder="Enter Title" required/>
    </div>
    <div class="form-group">
    <label>Select Locations : </label>
    <select class="form-control" multiple="multiple" id="location_group" required>
      <?php  foreach($rows as $row):?>
        <option value="<?php echo $row->person_id; ?>"><?php echo $row->username;?></option>
      <?php endforeach; ?>
    </select>
    </div>
    <div class="form-group col-sm-2 pull-right">  
        <input type="submit" id="" class="btn  btn-info form-control" value="Submit"/>
    </div>
    </form>
</div>
</div>
<script>
$(document).ready(function(){
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    $('#location_group').select2();
    $('#submit_loc_group').submit(function(e){
        e.preventDefault();
        var loc_group = $('#location_group').val();
        var title = $('#title').val();
        $.post('<?php echo site_url('offers/insert_loc_group');?>', {loc_group:loc_group,title:title}, function(data) {
            $('#close').click();
            $.notify("Created Succesfully", { type:'success' } );
            insert_tbl();     
        });
    })  
})
function insert_tbl(){
                $.post('<?php echo base_url();?>/offers/view_location_group_table',{},function(data){
                    $('.table_div').html(data);
                });
            }
</script>
<?php
    $employees = json_decode($row->locations);
?>
<div class="row">
<div class="col-sm-12">
    <form id="submit_form" onsubmit="return false;">
    <input type="hidden" id="id" value="<?php echo $id;?>">
    <div class="form-group">
        <label>Title  : </label>
        <input type="text" id="title" class="form-control" placeholder="Enter Title" value="<?php echo $row->title;?>" required/>
    </div>
    <div class="form-group">
    <label>Select Locations : </label>
    <select class="form-control" multiple="multiple" id="location_group" required>
      <?php  foreach($users as $user):?>
        <option value="<?php echo $user->person_id; ?>" <?php if(in_array($user->person_id,$employees)){echo "selected";}?>><?php echo $user->username;?></option>
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
    $('#location_group').select2();
    $('#submit_form').submit(function(e){
        e.preventDefault();
        var id = $('#id').val();
        var loc_group = $('#location_group').val();
        var title = $('#title').val();
        $.post('<?php echo site_url();?>/offers/update_loc_group/'+ id, {loc_group:loc_group,title:title}, function(data) {
            $('#close').click();
            $.notify("Edited Succesfully", { type:'success' } );
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
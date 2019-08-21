<style type="text/css">
  .input_tag{
    margin-top: 19px; 
    display:none;
  }  
</style>
<div class="row">
<div class="col-sm-12">
    <form id="submit_loc_group" onsubmit="return false;">
        <div class="form-group">
            <label>Title  : </label>
            <input type="text" id="title" class="form-control" placeholder="Enter Title" required/>
        </div>
        <div class="form-group">
            <label>Alias : </label>
             <input type="text" id="alias" class="form-control" placeholder="Enter Alias" />
        </div>
        <div class="form-group">
            <label>Tag Name : </label><br>
            <select style="margin-right: 14px;padding: 7px 26px;background-color: #fff;color: #000;border: 2px solid #dce4ec;border-radius: 4px;" id="slc_tg" class="show-menu-arrow slc_tg" name="custom_field" data-style="btn-default btn-sm" data-width="fit" tabindex="-98" required>
            <?php 
                 $data = $this->Offers_manage->get_custom_data();

                foreach($data as $row){ ?>
                     <option value="<?php echo $row->tag; ?>"><?php echo strtoupper($row->tag); ?></option>
            <?php   }
            ?>

        </select><a style="margin-bottom: 6px;" id="add_new" class='btn btn-info btn-sm'>Add New</a>
        <input style=" margin-top: 19px;" type="text" id="tag_name" class="form-control input_tag" placeholder="Enter Int Value"/>
        </div>
        <div class="form-group">
            <label>Int Value : </label>
             <input type="text" id="int_value" class="form-control" placeholder="Enter Int Value" />
        </div>
        <div class="form-group col-sm-2 pull-right">  
            <input style="padding: 6px;" type="submit" id="" class="btn  btn-info form-control" value="Submit">
        </div>
    </form>
</div>
</div>
<script>

$(document).on('click','#add_new',function(){

    if($("#tag_name").hasClass("input_tag")){
        $('#tag_name').removeClass("input_tag");
    }
    else{
        $('#tag_name').addClass("input_tag");
    }
    
    //$('#tag_name').toggle();
})

$(document).ready(function(){
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('#submit_loc_group').submit(function(e){
        e.preventDefault();
        var title        = $('#title').val();
        var alias        = $('#alias').val();
        var custom_field = $('#slc_tg').val();
        var tag_name     = $('#tag_name').val();
        var tag_value = '';
        if(tag_name == ""){
            tag_value = custom_field;
        }
        else{
            tag_value = tag_name;   
        }
        var int_value    = $('#int_value').val();
        $.post('<?php echo site_url('offers/add_tags');?>', {'title':title,'alias':alias,'tag_name':tag_value,'int_value':int_value}, function(data) {
            $('#close').click();
            $.notify("Created Succesfully", { type:'success' } );
         
        });
    })  
})
</script>
<?php
  $decoded_bundle = json_decode($row->bundle);
  $type = $decoded_bundle->type;
  $parent_id =  $decoded_bundle->parent_id;
  $entities =  $decoded_bundle->entities;
?>
<div class="row">
<div class="col-sm-12">
    <form id="submit_bundle" onsubmit="return false;">
    <input type="hidden" value="<?php echo $row->id;?>" id="id">
    <div class="form-group col-sm-12">
        <label>Title : </label>
        <input type="text" id="title" value="<?php echo $row->title;?>" class="form-control" placeholder="Enter Title" required/>
    </div>
    <div class="form-group col-sm-6">
        <label>Select Type : </label>
        <select name="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="categories" <?php if($type=='categories'){echo "selected";}?>>Category</option>
            <option value="subcategories" <?php if($type=='subcategories'){echo "selected";}?>>Sub Category</option>
            <option value="brands" <?php if($type=='brands'){echo "selected";}?>>Brand</option>
        </select>
    </div>
    <div class="form-group col-sm-6 <?php if($type!='subcategories'){echo "hide";}?>"  id="category_div">
        <label>Select Category : </label>
        <select name="category" class="form-control">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->id;?>" <?php if($category->id==$parent_id){echo "selected";}?>><?php echo $category->name;?></option>
            <?php endforeach; ?>
        </select>
    </div>  
    <div class="clearfix"></div>
    <div class="form-group col-sm-12" id="bundle_div">
    <label>Select : </label>
    <div class="clearfix"></div>
    <select name="bundle" class="form-control col-sm-12" id="insert_bun" style="width:100%!important" multiple required>
      <?php
        $entities_name = $this->Control_Panel->get_mci_info2($type);
        foreach($entities_name as $entity_name):?>
         <option value="<?php echo  $entity_name->id;?>" <?php if(in_array($entity_name->id,$entities)){echo "selected";}?>><?php echo  $entity_name->name;?></option>   
      <?php endforeach;?>
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
    $("[name='type']").on('change',function(){
        $("[name='category']").val('');
        $('#category_div').addClass('hide');
        $('[name="bundle"]').html('');
       var type = $(this).val();
       if(type=="subcategories"){
           $('#category_div').removeClass('hide');
       }
       if(type=="categories"){
           $.post('<?php echo base_url();?>/offers/fetch_categories',{},function(data){
                $('[name="bundle"]').html(data);
           });
       }
       if(type=="brands"){
           $.post('<?php echo base_url();?>/offers/fetch_brands',{},function(data){
                $('[name="bundle"]').html(data);
           });
       }
    });
    $("[name='category']").on('change',function(){
        $('[name="bundle"]').html('');
       var category = $("[name='category']").val();
       if(category!=""){
        $.post('<?php echo base_url();?>/offers/fetch_subcategory/'+category,{},function(data){
                $('[name="bundle"]').html(data);
           });
       }
    })
    $('[name="bundle"]').select2();
    $('#submit_bundle').submit(function(e){
        e.preventDefault();
        var title = $('#title').val();
        var id = $('#id').val();
        var type = $('[name="type"]').val();
        var bundle = $('[name="bundle"]').val();
        if(type=="subcategories"){
            var parent_id = $('[name="category"]').val();
        }else{
            var parent_id = 0;
        }
        $.post('<?php echo base_url();?>/offers/update_bundle_group/'+id,{parent_id:parent_id,title:title,type:type,bundle:bundle},function(data){
            $('#close').click();
            $.notify("Updated Succesfully", { type:'success' });
            $.post('<?php echo site_url('offers/view_offer_bundle_table')?>',{},function(data){
                $('.offer_bundle_table').html(data);
            });
         });
    })
})
</script>


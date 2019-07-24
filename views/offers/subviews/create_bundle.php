<div class="row">
<div class="col-sm-12">
    <form id="submit_bundle" onsubmit="return false;">
    <div class="form-group col-sm-12">
        <label>Title : </label>
        <input type="text" id="title" class="form-control" placeholder="Enter Title" required/>
    </div>
    <div class="form-group col-sm-12">
        <label>Barcode : </label>
        <input type="text" id="barcode" class="form-control" placeholder="Enter Barcode" required/>
    </div>
    <div class="form-group col-sm-6">
        <label>Select Type : </label>
        <select name="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="tags">Tag</option>
            <option value="categories">Category</option>
            <option value="subcategories">Sub Category</option>
            <option value="brands">Brand</option>
            <option value="barcode">Barcode</option>
        </select>
    </div>
    <div class="form-group col-sm-6 hide" id="category_div">
        <label>Select Category : </label>
        <select name="category" class="form-control">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-12" id="bundle_div">
    <label>Select : </label>
    <div class="clearfix"></div>
    <select name="bundle" class="form-control col-sm-12" id="insert_bun" style="width:100%!important" multiple>
        <!-- options will b inserted acc to type through ajax -->
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
       if(type=="tags"){
           $.post('<?php echo base_url();?>/offers/fetch_tags',{},function(data){
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
        var barcode = $('#barcode').val();
        var type = $('[name="type"]').val();
        if(type=="subcategories"){
            var parent_id = $('[name="category"]').val();
        }else{
            var parent_id = 0;
        }
        var bundle = $('[name="bundle"]').val();
        $.post('<?php echo base_url();?>/offers/insert_offer_bundle',{barcode:barcode,parent_id:parent_id,title:title,type:type,bundle:bundle},function(data){
            $('#close').click();
            $.notify("Created Succesfully", { type:'success' } );
            insert_table();     
         });
    })
})
function insert_table(){
    $.post('<?php echo site_url('offers/view_offer_bundle_table')?>',{},function(data){
        $('.offer_bundle_table').html(data);
    });
}
</script>


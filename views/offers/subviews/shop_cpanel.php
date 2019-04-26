<style>
.rotate_180{
    transform: rotate(180deg);
}
</style>
<div class="row">
<div class="col-sm-4">
         <div  class='list-group-item disabled' style='background-color: #132639;color:#fff;font-size:15px;'>
            <span class='glyphicon glyphicon-user' style='color: white;margin-right:10px;'></span>
            Cashiers 
         </div>
<?php if($cashiers){ ?>
       <?php foreach($cashiers as $row): ?>
        <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
            <h4 class="panel-title">
                <span id="cashier_title_<?php echo $row->id;?>" class="">
                     <?php echo $row->name ?>
                </span>
                <a data-toggle="collapse" href="#collapse_<?php echo $row->id ?>" class="fa fa-arrow-down pull-right arrow"></a> 
            </h4>
            </div>
         <div id="collapse_<?php echo $row->id ?>" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item col-sm-8">
                <div class="form-group">
                    <label for="name">Name : </label>
                    <!-- <input type="text" 
                        value="<?php //echo $row->name;?>"
                        class="" 
                        id="cashier_name_<?php //echo $row->id;?>" 
                        placeholder="Enter name"  
                        name="name" 
                        onblur="update_cashier_data(<?php //echo $row->id;?>, 'name');"
                    > -->
                    <?php echo $row->name;?>
                </div>
                </li>
                <div class="clearfix"></div>
                <li class="list-group-item">
                    <div class="form-group">
                    <label for="pwd">Password:</label>
                    <!-- <input type="text" 
                        value="<?php //echo $row->webkey;?>" 
                        class="" 
                        id="cashier_webkey_<?php //echo $row->id;?>" 
                        placeholder="Enter password" 
                        name="webkey" 
                        onblur="update_cashier_data(<?php //echo $row->id;?>,'webkey');"
                    > -->
                    <?php echo $row->webkey;?>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <!-- <input type="text" 
                        value="<?php //echo $row->contact;?>" 
                        class="" 
                        name="contact" 
                        id="cashier_contact_<?php //echo $row->id;?>" 
                        placeholder="Enter Contct number" 
                        name="contact" 
                        onblur="update_cashier_data(<?php //echo $row->id;?>,'contact');"> -->
                    <?php echo $row->contact;?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        </div>
        </div>
        <?php endforeach; ?>
    <?php } ?>
    </div>
    <div class="col-sm-7 pull-right">
       
        <?php
            foreach($shop_details as $shop_detail){
        ?>
            <div style="background: #2c3e50; padding: 10px 15px;" class="col-sm-12">
                <span style="color:#fff;font-size: 15px;padding-left:0" class="col-sm-3 ">Shop Incharge
                </span>
                <input 
                    class="col-sm-6" 
                    type="text" 
                    id="shop_incharge" 
                    name="shop_incharge" 
                    value="<?php echo $shop_detail->shop_incharge;?>"
                >
                <div class="btn btn-xs btn-info col-sm-2 col-sm-offset-1 pull-right" onclick="save_changes('shop_incharge');" >
                    Save Incharge
                </div>
            </div>  
            <div>
                <textarea name="address" id="address"> <?php echo $shop_detail->address; ?></textarea>
            </div>
            <div><br>
                <span class="btn btn-md btn-info col-sm-2 col-sm-offset-10" onclick="save_changes('address');">Save Address</span>
            </div>
            <br>
            <div class="clearfix"></div>
             <br>
            <div > 
                <textarea name="tnc" id="tnc"><?php echo $shop_detail->tnc; ?> 
                </textarea>
            </div>
            <div class="col-sm-12"> <br>
                <span class="btn btn-md btn-info col-sm-2 col-sm-offset-10" onclick="save_changes('tnc');">Save T&C</span>
            </div>
            <br>
        <?php
            }
        ?>
    </div>

</div>
<br><br>

<script>
   CKEDITOR.replace( 'tnc' );
    CKEDITOR.replace( 'address' );
</script>
<script>
function delete_cashier(cashier_id){
    var result = confirm("Are you sure to delete the Cashier details?");
      if (result) {
        var location_id = $('#location_id').val();
        $.post("<?php  echo site_url('offers/delete_cashier')?>",{cashier_id:cashier_id, location_id:location_id},function(data){
            $.ajax({ 
                url: "<?php echo site_url('offers/get_cashiers')?>",
                data: {loc_owner:location_id},
                success: function (data) {
                    $('#shop_cpanel').html(data); 
                },
                error: function (data) {
                    console.log('An error occurred.');
                },
            });
        });
      }
}
function save_changes(type){

    var location_id = $('#location_id').val();

    if(type=="shop_incharge"){
        var update_data = $("#"+type).val();
    }else if(type=="address"||type=="tnc"){
        console.log("add");
        var update_data = CKEDITOR.instances[type].getData();
    }
      $.ajax({ 
            url: "<?php  echo site_url('offers/save_cp_changes')?>",
            data: {loc_id:location_id,update_data:update_data,type:type},
            success: function (data) {
                alert(data);
            },
            error: function (data) {
                alert("Error");
            },
        });
    }
   
    function update_cashier_data(id,name){
        var data = $('#cashier_'+name+"_" + id).val();
        $.post('<?php echo site_url("offers/edit_cashier_data"); ?>', {'id': id, 'col': name,'data':data}, function(data) {
            $.notify({ message: data });
        });
    }
$(document).ready(function(){
    $('.arrow').on('click',function(){
       $(this).toggleClass('rotate_180');
    });
})
</script>


<?php
 $active_cashiers=0;
foreach($cashiers as $row){
   
    if($row->status == 'checked')
    {
        $active_cashiers++;
    }
    
}
?>
<style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<div class="row">

<?php if($cashiers){ ?>
    <div class="col-sm-4">
         <div  class='list-group-item disabled' style='background-color: #132639;color:#fff;font-size:15px;'>
            <span class='glyphicon glyphicon-user' style='color: white;margin-right:10px;'></span>
            Cashiers 
                <span class="pull-right"> 
                     <span id="active_cashiers_count">
                     <?php echo $active_cashiers;?></span>
                     <?php echo '/ '.count($cashiers);?>
                </span>
         </div>

       <?php foreach($cashiers as $row): ?>
        <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
            <h4 class="panel-title">
                <span id="cashier_title_<?php echo $row->id;?>" class="<?php
                    if($row->status != ""){
                        echo "text-success";
                    }?> ">
                     <?php echo $row->name ?>
                </span>
                <a data-toggle="collapse" href="#collapse_<?php echo $row->id ?>" class="glyphicon glyphicon-edit pull-right"></a> 
            </h4>
            </div>
         <div id="collapse_<?php echo $row->id ?>" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item col-sm-8">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" 
                        value="<?php echo $row->name;?>"
                        class="" 
                        id="cashier_name_<?php echo $row->id;?>" 
                        placeholder="Enter name"  
                        name="name" 
                        onblur="update_cashier_data(<?php echo $row->id;?>, 'name');"
                    >
                </div>
                </li>
                <li class="list-group-item col-sm-4">
                <img class="sw_loading" src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" style="display:none;" height="30" width="30"/> 
                 <div class="form-group cashier_switch">
                    <input value="<?php echo $row->id ?>" type="hidden" id="cashier_id">
                    <input type="checkbox" 
                        class="cashier_toggle" 
                        <?php echo $row->status ?> 
                        data-toggle="toggle" 
                        data-onstyle="success" 
                        data-offstyle="danger" 
                        data-style="ios" 
                        data-size="mini" />
                </div>
                </li>
                <div class="clearfix"></div>
                <li class="list-group-item">
                    <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="text" 
                        value="<?php echo $row->webkey;?>" 
                        class="" 
                        id="cashier_webkey_<?php echo $row->id;?>" 
                        placeholder="Enter password" 
                        name="webkey" 
                        onblur="update_cashier_data(<?php echo $row->id;?>,'webkey');"
                    >
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="text" 
                        value="<?php echo $row->contact;?>" 
                        class="" 
                        name="contact" 
                        id="cashier_contact_<?php echo $row->id;?>" 
                        placeholder="Enter Contct number" 
                        name="contact" 
                        onblur="update_cashier_data(<?php echo $row->id;?>,'contact');">
                    </div>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        </div>
        </div>
        <?php endforeach; ?>
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
<?php } ?>
</div>
<br><br>



<script>
   CKEDITOR.replace( 'tnc' );
    CKEDITOR.replace( 'address' );
</script>
<script>

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
    $('.cashier_toggle').bootstrapToggle();
    $('.cashier_toggle').on('change', function(){
      $('.cashier_switch').hide();
      $('.sw_loading').show();
      var id = $(this).parent().parent().find('#cashier_id').val();
      console.log(id);
     var status = $(this).prop('checked');
     var count= parseInt($('#active_cashiers_count').text());
      $.post('<?php echo site_url("offers/cashier_toggle"); ?>', {'id': id, 'status': status}, function(data) {
        console.log(data);
        $('.cashier_switch').show();
        $('.sw_loading').hide();
        if(status==""){
             $('#cashier_title_'+id).removeClass("text-success");
             $('#active_cashiers_count').text(count-1);
         } else {
            $('#cashier_title_'+id).addClass("text-success");
            $('#active_cashiers_count').text(count+1);
         }
      });
      
    });
   
    function update_cashier_data(id,name){
        var data = $('#cashier_'+name+"_" + id).val();
        $.post('<?php echo site_url("offers/edit_cashier_data"); ?>', {'id': id, 'col': name,'data':data}, function(data) {
            $.notify({ message: data });
        });
    }

</script>


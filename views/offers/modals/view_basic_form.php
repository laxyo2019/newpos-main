

<div class="row">
<div class="col-sm-12">
    <form id="submit_bundle" onsubmit="return false;">
    <div class="form-group col-sm-12">
        <label>Title : </label>
        <input type="text" id="title" class="form-control" placeholder="Enter Title" required/>
    </div>
    <div class="form-group col-sm-12" >
        <label>Locations Title : </label>
        <select name="locations" class="form-control"  style="width:100%!important" required>
        <option value=''>Select Location Group</option>
        <?php foreach($locations as $location):?>
            <option value="<?php echo $location->id;?>"><?php echo $location->title;?></option>
        <?php endforeach; ?>
        </select>
    </div>
    <div class=" col-sm-12" >
         <p id="locations_name" ></p>
    </div>
    <div class="form-group col-sm-12" >
        <label>Pointer Title : </label>
        <select name="pointers" class="form-control"  style="width:100%!important" required>
        <option value=''>Select Pointer Group</option>
        <?php foreach($pointers as $pointer):?>
            <option value="<?php echo $pointer->id;?>"><?php echo $pointer->title;?></option>
        <?php endforeach; ?>
        </select>
    </div>
    <div class=" col-sm-12" >
         <p id="offers_name" ></p>
    </div>
    <div class="form-group col-sm-6">
        <div class='input-group date datetimepicker' id='datetimepicker1'>
            <input type='text' class="form-control" autocomplete="off" id="start_time" placeholder="Start Time" required/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="form-group col-sm-6">
      <div class='input-group date datetimepicker' id='datetimepicker2'>
          <input type='text' class="form-control" autocomplete="off" id="end_time" placeholder="End Time" required/>
          <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
          </span>
      </div>
    </div>
    <div class="form-group col-sm-6">
        <label>Discount : </label>
      <input type="text" class="form-control" id="discount" placeholder="Temporary Discount Value" required/>
    </div>
   <!-- <div class="form-group col-sm-2 pull-right">  
        <input type="submit" id="submit" class="btn  btn-info form-control" value="Submit"/>
    </div> -->
    </form>
    
</div>
</div>
<script>
	$(document).ready( function () {
        //submit form to create offer
        $('#submit_bundle').on('submit',function(e){
           e.preventDefault();
            var title = $('#title').val();
            var locations = $('[name="locations"]').val();
            var pointers = $('[name="pointers"]').val();
            var start_time =  $('#start_time').val();
            var end_time = $('#end_time').val();
            var discount = $('#discount').val();
            if(locations=="" || pointers=="" || title=="" || start_time==""|| end_time=="" || discount==""){
                alert("Fill all details"); return false;
            }
           $.post('<?php echo site_url($controller_name."/save_basic"); ?>',{
               title : title,
               locations : locations,
               pointers : pointers,
               start_time : start_time,
               end_time : end_time,
               discount : discount
               },
               function(response){

                        setTimeout(function() {
                            location.reload();
                        }, 500);
                });
        });

        // $('.datetimepicker').datetimepicker({
        //     // format: 'dd.mm.yyyy',
        //     // minView: 2,
        //     // maxView: 4,    
        //     autoclose: true
        //     });


    // set default dates
    var start = new Date();
    // set end date to max one year period:
    var end = new Date(new Date().setYear(start.getFullYear()+1));

    $('#start_time').datetimepicker({
        startDate : start,
        endDate   : end,
        autoclose:true
    // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function(){
        // set the "toDate" start to not be later than "fromDate" ends:
        $('#end_time').datetimepicker('setStartDate', new Date($(this).val()));
    }); 

    $('#end_time').datetimepicker({
        startDate : start,
        endDate   : end,
        autoclose:true
    // update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function(){
        // set the "fromDate" end to not be later than "toDate" starts:
        $('#start_time').datetimepicker('setEndDate', new Date($(this).val()));
    });

    $('[name="pointers"]').on('change',function(){
        id = $('[name="pointers"]').val();
        if(id!=""){
            $.post('<?php echo site_url();?>offers/get_pointer_group/'+id,{},function(data){
                $('#offers_name').css({'border': 'solid 2px #dce4ec','overflow':'auto','padding': '10px','color': '#18bc9c'}).text(data);
             });
        }else{
            $('#offers_name').css({'border': '','padding': '','color': ''}).text('');
        }
    });

    $('[name="locations"]').on('change',function(){
        id = $('[name="locations"]').val();
        if(id!=""){
            $.post('<?php echo site_url();?>offers/get_loc_group/'+id,{},function(data){
                $('#locations_name').css({'border': 'solid 2px #dce4ec','overflow':'auto','padding': '10px','color': '#18bc9c'}).text(data);
             });
        }else{
            $('#locations_name').css({'border': '','padding': '','color': ''}).text('');
        }
    });
});
    
</script>
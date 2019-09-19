
<?php $this->load->view("partial/header"); ?>

<ul class="nav nav-tabs" data-tabs="tabs" id="shop_tab">

	<?php foreach($shops as $shop) { ?>
	<li class="" role="presentation">
		
		<a data-toggle="tab" href="javascript:void(0)" onclick='count_data(<?php echo $shop->location_id.",".$shop->location_owner;?>)' title='<?php echo $shop->alias?>'><?php echo $shop->location_name;?></a>
	</li>
	<?php } ?>
</ul>
<br>
	<div class="tab-content">
    <div class="tab-pane fade in active" id="mh_count">
		<div class="row">
				<div class="column">
					<div class="col-md-4">
						<center>
						<div class="card"style="background-color: #00cccc;">
							<br>
							<h3>Current Stock</h3>
							<h1><span class="fa fa-tags" style="color: white;"></span></h1>
							<h1 id="itemcount" class="loader_wait"></h1>
						<br>
							</div>
					</center>
					</div>
					</div>
					<div class="col-md-4">
						<div class="column">
							<center>       
							<div class="card" style="background-color: #ffcc66;">
								<br>
								<h3>Today's Sales</h3>
								<h1><span class="fa fa-shopping-cart" style="color: white;"></span></h1>
								<h1 id="dailySales" class="loader_wait"></h1><br>
							</div>
							</center>
						</div>
					</div>
					<div class="col-md-4">
					<div class="column">
						<center>
						<div class="card" style="background-color: #ff704d;">
							<br>
							<h3>Today's Earning</h3>
							<h1><span class="fa fa-inr" style="color: white;"></span></h1>
							<h1 id="totalSales" class="loader_wait"></h1>
					<br>
					</div>
					</center>
					</div>
			</div>
		</div>
		</div>
</div>
<div class="row">
	<div class="col-md-6">
		<h3>Points Table</h3>
		<table class="table table-hover table-bordered" >
			<thead >
				<tr>
					<th>Shop Name</th>
					<th>Total's Earning</th>
					<th>Total Items</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($rank as $row) :?>
					<tr>
						<td><?php echo $row->location_name;?></td>
						<td><i class="fa fa-inr">&nbsp;&nbsp;</i><?php echo $row->Total_earning;?></td><!--<i class="fa fa-inr">/-&nbsp;&nbsp;</i>-->
						<td><?php echo round($row->total_sale)?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h3>Login And Logout Details</h3>
		<div class="column">			
			<div class="card" >
				<div class="row" style="background-color: #ff704d;padding-top: 10px;">
					<div class="col-md-4">			
					<h5>Login Time:- <span id="logintime"></span></h5>
						
					</div>
					<div class="col-md-4">					
						<h5>Logout Time:- <span id="logouttime"></span></h5>
					</div>			
					<div class="col-md-2 ">					
						<button id="all_details" type="button" class="btn btn-success pull-right">All Details</button>
					</div>
					<div class="col-md-2 ">					
						<a href="<?php echo site_url('/home/shop_close/close') ;?>" class="btn btn-warning pull-right myLink">Shop Close</a>
					</div>
					<div class="col-md-12 " style=" height: 219px;overflow: scroll;margin-top: 23px;">					
						<div class="show_all_details" style=" display:none;">				
						</div>
						
					</div>	
				</div>
			</div>	
		</div>	
	</div>
</div>

<input type="hidden" id='login' value="<?php echo $time[0]->login ? $time[0]->login:'10:00'; ?> "/>
<input type="hidden" id='logout' value="<?php echo $time[0]->logout ? $time[0]->logout :'20:00'; ?> "/>
<input type="hidden" id='login_date' value="<?php echo $model[0]->date; ?> "/>
<input type="hidden" id='current_date' value="<?php echo date('Y-m-d'); ?> "/>
<input type="hidden" id='ip' value="<?php echo $_SERVER['REMOTE_ADDR']; ?> "/>
<input type="hidden" id='store_name' value="<?php echo $user_info->first_name . ' ' . $user_info->last_name; ?>" />

<!-- Modal Popup -->
<div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal">
                    &times;</button> -->
                <h4 class="modal-title">

                </h4>
            </div>
            <div class="modal-body" style="text-align: center;">
            	<span id="msg" style="color: #FF0000; font-size:12px;"></span><br>
            	<button id="start_shop" type="button" class="btn btn-lg btn-primary" >Start Shop Now</button>
            	
            	<div id='reason_field' class="row"  style="display: none ; padding-top: 19px;">
            		<div  class="col-sm-3 col-md-3">
            			<label><span style="color: #FF0000; font-size:11px;">*</span>Reason</label>
            		</div>
            		<div  class="col-sm-6 col-md-6" style="">
            			<span id="msg" style="color: #FF0000; font-size:11px;"></span>
            			<input class="form-control" name="reason" id='reason'/>
            			<span style="color: #FF0000; font-size:12px;" id="error"></span>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("partial/footer");?>
<script>
 $(document).ready(function(){
//  window.addEventListener("beforeunload", function (e) {

//   var confirmationMessage = "Are you sure you want to leave this page without placing the order ?";
//   (e || window.event).returnValue = confirmationMessage;
//   return confirmationMessage;
// })
 	// $(window).bind("beforeunload", function() { 
  //       return confirm("Do you really want to close?"); 
  //   });

	var d            = new Date();
	var time         = d.getHours() + ":" + d.getMinutes() ;
	var login        = $('#login').val();	
 	var logout       = $('#logout').val();	
    var title        = $('#store_name').val();
    var login_date   = $('#login_date').val();
    var current_date = $('#current_date').val();
    var ip           = $('#ip').val();
   
	var start = login;
    var end = time;

    s = start.split(':');
    e = end.split(':');

    min = e[1]-s[1];
    hour_carry = 0;
    if(min < 0){
        min += 60;
        hour_carry += 1;
    }
    hour = e[0]-s[0]-hour_carry;
    diff = hour + "	hr " + min+'min';
	if(time > login){
			$('#msg').text('You are '+diff+' late please enter reason')
			$('#reason_field').css('display','block');
		}
	$bfr = ''	
	if(time < login){
		$bfr = 'done';
	}	
	if(login_date !== current_date){	
	 	$("#MyPopup .modal-title").html(title);
	    //$("#MyPopup .modal-body").html(logout);
	    $("#MyPopup").modal({"backdrop": "static"});
	}
    $('#start_shop').on('click',function(){    
    	var reason  = $('#reason').val();
    	var res_len = $('#reason').val().length;
    	
	    if(reason !== " " && res_len > 10 || $bfr !== ''){
	    	$.post('<?php echo site_url("Home/reason_save");?>', {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','reason': reason}, function(data){
	    	   if(data=='true'){
					$("#MyPopup").modal("hide");
				}else{
					alert('Please submit again');
				}
			})
	    }
	    else{
	    	$('#error').text('Please Enter Your Reason Minimum 10 Characters');
	    }    
	})

	$('#all_details').on('click',function(){
		$('.show_all_details').toggle();
		})    
});

$(document).ready(function(){
	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
	$("#shop_tab li:first-child").addClass('active');
	count_data(4,7);
})
function count_data(loc,per){
	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/admin_count') ?>', {per:per,loc:loc},function(data){
       resp = $.parseJSON(data);
      $('#itemcount').html(resp.itemcount);
      $('#dailySales').html(resp.dailySales);
      $('#totalSales').html(resp.totalSales);
      $('#logintime').text(resp.logintime);
      $('#logouttime').text(resp.logouttime);
    });

    $.post('<?php echo site_url("Home/get_all_login");?>', {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','id': per}, function(data){
	    	  $('.show_all_details').html(data);
			})
}

$(document).ready(function(){
	$('#all_details').on('change',function(){
		$.post('<?php echo site_url("Home/reason_save");?>', {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','reason': reason}, function(data){
	    	  
			})
	})
})
</script>
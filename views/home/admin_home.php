
<?php $this->load->view("partial/header"); ?>
<?php  
$session = $this->session->all_userdata();
	
?>
<?php 
	$query = $this->db->select('*')->from('ospos_stock_locations')->where('location_owner',$session['person_id'])->get();
	$dataLogin = $query->result();

?>

<ul class="nav nav-tabs" data-tabs="tabs" id="shop_tab">

	<?php foreach($shops as $shop) { ?>
	<li <?php
			if($session['person_id'] == $shop->location_owner ){

				echo 'class="active"';
			}

			 if($session['person_id'] == '15' || $session['person_id'] == '16'){
			 	if($shop->location_owner == '7'){
			 		echo 'class="active"';
			 	}
			 }

		?>  role="presentation">
		
		<a data-toggle="tab"  href="javascript:void(0)" onclick='count_data(<?php echo $shop->location_id.",".$shop->location_owner;?>)' title='<?php echo $shop->alias?>'><?php echo $shop->location_name;?></a>
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
	<?php  if($session['person_id'] == $dataLogin[0]->location_owner || $session['person_id'] == '15' || $session['person_id'] == '16') {  ?> 

	<div class="col-md-6 show_all_details " style="border: 1px solid #ddd; margin-top: 54px;" >
		<?php  $this->load->view('home/login_details'); ?>
	</div>
<?php } ?>

</div>

<input type="hidden" id='login' value="<?php echo $time[0]->login ? $time[0]->login:'10:00'; ?> "/>
<input type="hidden" id='logout' value="<?php echo $time[0]->logout ? $time[0]->logout :'20:00'; ?> "/>
<input type="hidden" id='login_date' value="<?php echo $model[0]->date; ?> "/>
<input type="hidden" id='current_date' value="<?php echo date('Y-m-d'); ?> "/>
<input type="hidden" id='ip' value="<?php echo $_SERVER['REMOTE_ADDR']; ?> "/>
<input type="hidden" id='login_type' value="<?php echo $log_type[0]->login_type ; ?>"/>
<input type="hidden" id='store_name' value="<?php echo $user_info->first_name . ' ' . $user_info->last_name; ?>" />

<!-- Modal Popup -->
<div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header ">             
                <h4 class="modal-title text-center">
                	<b>Welcome To </b><b id="shopName"></b>
                </h4>
            </div>
            <div class="modal-body  text-center" >
            	<h5><b>Click on this button</b></h5>
            	</br>
            	<button id="start_shop" type="button" class="btn btn-md btn-primary" >Start Shop Now</button>
            	</br></br>
            
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("partial/footer");?>
<script>
 $(document).ready(function(){ 

	var d            = new Date();
	var time         = d.getHours() + ":" + d.getMinutes() ;
	var login        = $('#login').val();	
 	var logout       = $('#logout').val();	
    var title        = $('#store_name').val();
    var login_date   = $('#login_date').val();
    var current_date = $('#current_date').val();
    var ip           = $('#ip').val();
    var login_type   = $('#login_type').val();
      

	if(login_type === 'dbf' || login_type === 'shop'){	
		if(login_date !== current_date){	
		 	$("#shopName").html(title);		  
		    $("#MyPopup").modal({"backdrop": "static"});
		}
	}
    $('#start_shop').on('click',function(){    
    	$.post('<?php echo site_url("Home/reason_save");?>', {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'}, function(data){
	    	   if(data=='true'){
					$("#MyPopup").modal("hide");
					location.reload();
				}else{
					alert('Please submit again');
				}
			});
	    
	});


	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
	// $("#shop_tab li:first-child").addClass('active');
	
	
	var per = "<?php echo  $dataLogin[0]->location_owner ;  ?>" ;
	var loc = "<?php  echo $dataLogin[0]->location_id ; ?>";
	var count = "<?php echo count($dataLogin) ?>" ;
	if(count == 0){
		count_data(4,7);
	}
	else{
		count_data(loc,per);
	}
	
});

function count_data(loc,per){
	$('.loader_wait').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
    $.get('<?php echo site_url('home/admin_count') ?>', {per:per,loc:loc},function(data){
       resp = $.parseJSON(data);
      $('#itemcount').html(resp.itemcount);
      $('#dailySales').html(resp.dailySales);
      $('#totalSales').html(resp.totalSales);

    });

    var count = "<?php echo count($dataLogin) ?>" ;
    if(count == 0){
    	$.post('<?php echo site_url("Home/get_all_login");?>', {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','id': per}, function(data){
	    	  	$('.show_all_details').empty().html(data);
			});
	}

}


</script>
<?php $this->load->view("partial/header"); ?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs" id="rowTab">
            <li class="active" id="liactiv" role="presentation">
                <a data-toggle="tab" href="javascript:void(0)" id="load_cashier" onclick="load_cashier();" title="Cashiers">Cashiers</a>
            </li>
            <li class="" role="presentation">
                <a data-toggle="tab" href="javascript:void(0)" onclick="load_cashier_details();" title="Cashiers">Cashiers Detail</a>
            </li>
            <li role="presentation" id="offer_bundle_tab">
                <a data-toggle="tab"  href="javascript:void(0)" onclick="load_offer_bundle();" title="Offer Bundles">Offer Bundles</a>
            </li>
            <li role="presentation "  id="location_tab">
                <a data-toggle="tab" href="javascript:void(0)" onclick="load_loc_group()" title="Locations groups">Locations groups</a>
            </li>
            <li role="presentation "  id="custom_tab">
                <a data-toggle="tab" href="javascript:void(0)" onclick="load_custom_tab()" title="Locations groups">Custom Field</a>
            </li>
        </ul>
    </div>
    <hr>
    <div class="clearfix"> </div>
    <div class="content" style="margin-top:30px;">
    </div>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>

<script>
$(document).ready(function() {
  $("#load_cashier").click(function(e) {
  	$("#rowTab li").removeClass('active');
    $("#liactiv").addClass('active');
    $.ajax({ 
    	url: this.href, success: function(html) {
      	$(".content").empty().append(html);
      }
		});
    return false;
	});
  $(".content").load("<?php echo base_url();?>offers/load_cashier");
});

function load_cashier(){
	/*$.post('<?php echo base_url();?>offers/load_cashier',{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(data){
    $('.content').html(data);
  });*/
  $(".content").load("<?php echo base_url();?>offers/load_cashier");
}

function load_loc_group(){
  
  $.post('<?php echo base_url();?>offers/load_loc_group',{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(data){
  
    $('.content').html(data);
  });
}

function load_offer_bundle(){
  $.post('<?php echo base_url();?>offers/load_offer_bundle',{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(data){
    $('.content').html(data);
  });
}

function load_cashier_details(){
  $.post('<?php echo base_url();?>offers/load_cashier_details',{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(data){
    $('.content').html(data);
  });
}

function load_custom_tab(){
  $.post('<?php echo base_url();?>offers/load_custom_tab',{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},function(data){
    $('.content').html(data);
  });
}

// $(document).ready( function () {
//   load_cashier();
//    // $('#offers').load('<?php //echo site_url('offers/load_offer_bundle');?>',function(data){});   
//     dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
// })
</script>
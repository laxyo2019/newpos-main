<?php $this->load->view("partial/header"); ?>
<div class="row">
<div class="col-md-12">
    <div class="col-md-12">
        <ul class="nav nav-tabs" data-tabs="tabs">
            <li class="active" role="presentation">
                <a data-toggle="tab" href="javascript:void(0)" onclick="load_cashier();" title="Cashiers">Cashiers</a>
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

function load_cashier(){
  $.post('<?php echo base_url();?>offers/load_cashier',{},function(data){
    $('.content').html(data);
  });
}

function load_loc_group(){
  $.post('<?php echo base_url();?>offers/load_loc_group',{},function(data){
    $('.content').html(data);
  });
}

function load_offer_bundle(){
  $.post('<?php echo base_url();?>offers/load_offer_bundle',{},function(data){
    $('.content').html(data);
  });
}

function load_cashier_details(){
  $.post('<?php echo base_url();?>offers/load_cashier_details',{},function(data){
    $('.content').html(data);
  });
}

$(document).ready( function () {
  load_cashier();
   // $('#offers').load('<?php //echo site_url('offers/load_offer_bundle');?>',function(data){});   
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
})
</script>
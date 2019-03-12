<style>
.border_none{
  border:none!important;
}
span.title{
    display: inline-block;
    width: 30%;
    padding: 10px;
    background: #eee;
    text-align: center;
    border: solid #bf6026 1px;
}
#accordion6 .panel{
    nnborder: 1px solid #bf6026;
    border-radius: 0;
    box-shadow: none;
    margin-left: 52px;
    margin-bottom: 12px;
}
 
#accordion6 .panel-heading{
    padding: 0;
    background: #fff;
    position: relative;
}
#accordion6 .panel-heading:before,
#accordion6 .panel-heading:after{
    content: "";
    border-right: 8px solid #bf6026;
    border-bottom: 8px solid transparent;
    border-top: 8px solid transparent;
    position: absolute;
    top: 12px;
    left: -9px;
}
#accordion6 .panel-heading:after{
    border-right: 7px solid #fff;
    border-bottom: 7px solid transparent;
    border-top: 7px solid transparent;
    position: absolute;
    top: 13px;
    left: -6px;
}
#accordion6 .panel-title a{
    display: block;
    ppppadding: 10px 20px 11px 20px;
    border: none;
    font-size: 20px;
    font-weight: 400;
    color: #bf6026;
    position: relative;
    pppbackground: #f1f1f2;
    text-align:left;
}
#accordion6 .panel-title a:before{

}
#accordion6 .panel-title a:before{
    content: "\002D";
    font-weight: 900;
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 15px;
    color: #bf6026;
    text-align: center;
    border: 1px solid #bf6026;
    position: absolute;
    top: 0;
    left: -50px;
    transition: all 0.5s ease 0s;
}
#accordion6 .panel-title a.collapsed:before{ 
    content: "\002B"; 
    font-weight: 900;
    width: 35px;
    height: 35px;
    line-height: 35px;
    font-size: 15px;
    color: #bf6026;
    text-align: center;
    border: 1px solid #bf6026;
    position: absolute;
    top: 0;
    left: -50px;
    transition: all 0.5s ease 0s;
}
#accordion6 .panel-body{
    padding: 0 15px 15px;
    border: none;
    font-size: 14px;
    color: #807e7e;
    line-height: 28px;
}
#accordion6 .panel-body p{ margin-bottom: 0; }



#accordion6 .panel-body{
    display:block;
}

</style>
<?php $this->load->view("partial/header");?>
<div class="row">
  <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url($controller_name."/view_basic"); ?>'
            title='Create New Offer'>
      Create
  </button>
  <div class="col-sm-12">
  <center>
          <div class="col-sm-12">
              <div class="panel-group" id="accordion6" role="tablist" aria-multiselectable="true">
                  <?php for($i=1;$i<=5;$i++){ ?>
                  <div class="panel panel-default border_none">
                      <div class="panel-heading" role="tab" id="heading_<?php echo $i;?>">
                          <h4 class="panel-title">
                              <a role="button" class="collapsed " data-toggle="collapse" data-parent="#accordion6" href="#collapse_<?php echo $i;?>" aria-expanded="true" aria-controls="collapseOne6">
                                 <span class="title"> Section 1 </span>
                              </a>
                          </h4>
                      </div>
                      <div id="collapse_<?php echo $i ; ?>" class="panel-collapse collapse" role="tabpanel"     aria-labelledby="headingOne6">
                         <div>hi</div>
                      </div>
                  </div>

                <?php   } ?>
              </div>
          </div>
      </center>
  </div>
</div>
<hr>
<div id="dynamic_prices_table_area"></div>

<script>
	$(document).ready( function () {
  
			$.post('<?php echo site_url($controller_name."/get_dynamic_prices"); ?>', {}, function(data) {
				$('#dynamic_prices_table_area').html(data);
				$('#dynamic_prices').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

	
</script>
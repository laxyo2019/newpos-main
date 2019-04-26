<?php $this->load->view("partial/header"); ?>
<div class="row">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4> </a>>> Inventory 
  </div>
  <div id="office_module_list">
		<div class="module_item col-sm-2" title="Uploaded Sheetsfor Generating Barcodes">
			<a target='_blank' href="<?php echo site_url()."manager/load_tab_view/items_upload/inventory";?>">
                <div style='font-size: 34px;padding: 20px;background: #18bc9c;color: #fff;margin-bottom: 5px;' class='fa fa-file-excel-o'></div>
                <h5>Items Upload</h5>
            </a>
		</div>
	</div>
</div>

<script>
  $(document).ready(function(){
    dialog_support.init("button.modal-dlg");
    
  });
</script>
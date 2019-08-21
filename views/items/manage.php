<style>
td, th {
  margin: 0;
  border: 1px solid grey;
  white-space: nowrap;
  border-top-width: 0px;
  font-size: 13px;
}

.headcol {
  position: absolute;
  left: 1110px;
  top: auto;
  border-top-width: 1px;
}
tr:nth-child(odd) .headcol{
	background: #f9f9f9
}
tr:nth-child(even) .headcol{
	background: #fff
}


</style>

<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
    $('#generate_barcodes').click(function()
    {
        window.open(
            'index.php/items/generate_barcodes/'+table_support.selected_ids().join(':'),
            '_blank' // <- This is what makes it open in a new window.
        );
    });

    dialog_support.init("a.modal-dlg, button.modal-dlg-wide");
	
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });


   $(document).ready( function () {
    	$('#table1').DataTable({
    		 "lengthMenu": [[25, 50, -1], [10, 25, 50, "All"]],
    		'bJQueryUI': true,
    		'bAutoWidth': false,
    		'bSortClasses': false,
    		"bPaginate": true,
        	"bFilter": false,
        	dom: 'Bfrtip',
	        order: [[0, 'desc']],
	        buttons: [
	          'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
        	<?php if($this->Item->is_both()) {
			if($this->Item->is_superadmin()) { ?>
        	,"columnDefs": [ {
				"targets": 0,
				"orderable": false
			} ]
		<?php }
			} ?>
        });
	});
});
</script>
</div>
<div class="container-fluid">
	<div id="title_bar" class="btn-toolbar print_hide">

	<?php if($this->Item->is_both()) { ?>

		<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_stock_up"); ?>'
						title='<?php echo $this->lang->line('items_update_items_excel'); ?>'>
				<span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_update_excel'); ?>
		</button>

		<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
						title='<?php echo $this->lang->line('items_import_items_excel'); ?>'>
				<span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_import_excel'); ?>
		</button>

		<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('common_new') ?>' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/view"); ?>'
						title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
				<span class="glyphicon glyphicon-tag">&nbsp</span><?php echo $this->lang->line($controller_name. '_new'); ?>
		</button>
		<button class="btn btn-info btn-sm pull-right" id="filter_data_btn">	
				<span class="glyphicon glyphicon-filter">&nbsp</span>Filter
		</button>
	    
	<?php } ?>
		<?php if($this->Item->is_both()) { ?>
		<?php if($this->Item->is_superadmin()) { ?>
			<button disabled="true" id="delete" class="btn btn-default btn-sm print_hide">
					<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete"); ?>
			</button>
		<?php } ?>   
		<button disabled="true" id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide", data-btn-submit='<?php echo $this->lang->line('common_submit') ?>', data-href='<?php echo site_url($controller_name."/bulk_edit"); ?>'
		title='<?php echo $this->lang->line('items_edit_multiple_items'); ?>'>
				<span class="glyphicon glyphicon-edit">&nbsp</span><?php echo $this->lang->line("items_bulk_edit"); ?>
		</button>
	<?php } ?>
	

	</div>

	<div id="toolbar">
	    <div class="form-inline" role="toolbar">


			<?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
			<?php
			if (count($stock_locations) > 1)
			{
				echo form_dropdown('stock_location', $stock_locations, $stock_location, array('id'=>'stock_location', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));
			}
			?>
			<?php 
		
			 ?>
			<select style="max-width: 134px;" data-width="12%" id ="cat_id" class="selectpicker show-menu-arrow" data-style='btn-default btn-sm'>
				<option>Category..</option>
				<?php
					$cate = $this->Item->get_cate();
					foreach ($cate as $cat) { ?>
						<option value = "<?php echo $cat->id ; ?>"><?php echo $cat->name ; ?></option>
			<?php	}
				 ?>
			</select>

			<select data-width="12%" id="sub_cat"  class="show-menu-arrow " data-style='btn-default btn-sm' class="" style="background-color: #fff; color: #000;border: 2px solid #dce4ec;padding-bottom: 8px;padding-top: 5px;border-radius: 3px; max-width: 140px;" data-style='btn-default btn-sm'>
				<option>Subcategorie..</option>
			</select>

			<select style="max-width: 145px!imortant;" id ="brand_id" class=" selectpicker show-menu-arrow" data-width="12%"  data-style='btn-default btn-sm'>
				<option>Brand..</option>
				<?php
					$brands = $this->Item->get_brand();
					foreach ($brands as $list) { ?>
						<option value = "<?php echo $list->name ; ?>"><?php echo $list->name ; ?></option>
			<?php	}
				 ?>
			</select>

			<select class="hidden" style="max-width: 145px;" id ="edition_id" class=" selectpicker show-menu-arrow" data-width="12%"  data-style='btn-default btn-sm'>
				<option>Stock Edition...</option>
				<?php
					//$edition = $this->Item->get_stock_edition();
					$edition = array();
					foreach ($edition as $edit) { ?>
						<option value = "<?php echo $edit->id ; ?>"><?php echo $edit->name ; ?></option>
			<?php	}
				 ?>
			</select>


			<input style="width: 25%;" placeholder="Search" id="serch_item" class="pull-right form-control input-sm">

	    </div>
	</div>

	<div id="table_holder" style="margin-top:16px;overflow-x:auto">
	    <table id="table1" class="table table-responsive table-striped" style="width: 100%; display:table-caption;">
	    	<thead>
		<tr>
			<?php if($this->Item->is_both()) { 
			    if($this->Item->is_superadmin()) { ?>
					<th style="background-image: none;"><input style="margin-left: -8px;" type="checkbox" id="master"></th>
		 <?php  }
		 	} ?>			
			<th>id</th>
			<th>Barcode</th>
			<th>HSN Code</th>
			<th>Item Name</th>
			<th>Category</th>
			<th>Subcategroy</th>
			<th>Brand</th>
			<th>Size</th>
			<th>Color</th>
			<th>Expiry Date</th>
			<th>Stock Edition</th>
			<th>Retail Price</th>
			<th>Quantity</th>
			<th class="" style="background-image:none; padding-right: 50px; padding-left: 24px;">Action</th>
		</tr>
	</thead>	
	<tbody>
	<?php

	foreach($data as $row){ 
	$item_id     = !empty($row['item_id'])?$row['item_id']:'';
	$location_id = !empty($row['location_id'])?$row['location_id']:'';

	$data = $this->Item->get_multiple_info($item_id,$location_id)->result_array();
	$quantities = !empty($data[0]['quantity'])?$data[0]['quantity']:'';
	//$stock       = $this->Item->get_stock_edition($row['custom6']);


	?>		
		<tr>
			<?php if($this->Item->is_both()) { 
				 if($this->Item->is_superadmin()) { ?>
					<td><input type="checkbox" class="sub_chk" data-id="<?php echo $row['item_id']; ?>"></td>
		<?php 	}
			}	?>		
			<td><?php echo $row['item_id']; ?></td>
			<td><?php echo $row['item_number']; ?></td>
			<td><?php echo $row['custom1']; ?></td>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['category']; ?></td>
			<td><?php echo $row['subcategory']; ?></td>
			<td><?php echo $row['brand']; ?></td>
			<td><?php echo $row['custom2']; ?></td>
			<td><?php echo $row['custom3']; ?></td>
			<td><?php echo $row['custom5']; ?></td>
			<td><?php echo $row['custom6']; ?></td>
			<td><?php echo $row['unit_price']; ?></td>
			<td><?php echo $quantities; ?></td>
			<td class="" style=""><a  href="<?php echo site_url($controller_name.'/inventory/').$row['item_id'] ; ?>" class="modal-dlg" data-btn-submit="Submit" title="Update Inventory"><span style="padding-right: 10px;" class="glyphicon glyphicon-pushpin"></span></a>

			<a href="<?php echo site_url($controller_name.'/count_details/').$row['item_id'] ;?>" class="modal-dlg" title="Inventory Count Details"><span style="padding-right: 10px;" class="glyphicon glyphicon-list-alt"></span></a>

			<a href="<?php echo site_url($controller_name.'/view/') .$row['item_id']; ?>" class="modal-dlg" data-btn-submit="Submit" title="Update Item"><span class="glyphicon glyphicon-edit"></span></a></td>
		</tr>

	<?php }
	?>	</tbody> 
	    </table>
	</div>
</div>
<script>
	dialog_support.init("button.modal-dlg, button.modal-dlg-wide");

	$(document).ready(function(){
		$("#table").on('click', '.qty_update', function(event){
			var item_id = $(this).parent().parent().attr('data-uniqueid');
			var item_qty = this.id;
			console.log('item_id: '+item_id);
			console.log('item_quantity: '+item_qty);
			var new_qty = prompt("Please enter value", item_qty);
			if(new_qty)
			{
				new_qty = new_qty.trim();
				new_qty = parseInt(new_qty, 10);
				if(Number.isInteger(new_qty))
				{
						new_qty = Math.abs(new_qty);
						console.log(new_qty);
						$.post('<?php echo site_url($controller_name."/quick_item_quantity_update"); ?>', {'item_id': item_id, 'new_qty': new_qty}, function(data) {
        	    alert(data);
								// location.reload();
      	        });
				}
				else
				{
					console.log('invalid');
				} 
			}
			else
			{
				console.log("empty");
			}
		});

		$("#table").on('click', '.request_item', function(event){
			var item_id = $(this).parent().parent().attr('data-uniqueid');
			var req_qty = prompt("Please enter value");

			if(req_qty)
			{
				$.post('<?php echo site_url($controller_name."/request_item_add"); ?>', {'item_id': item_id, 'request_qty': req_qty}, function(data) {
	        	alert(data);
	      });
			}
			else
			{
				console.log("empty");
			}
		});
	});
    

    $(document).on('change','#cat_id',function(){
		var id = $('#cat_id').val();
		$.post('<?php echo site_url($controller_name."/get_subcate"); ?>',{'id':id},function(data){
			$('#sub_cat').html(data);
		})
	})

	$(document).ready(function(){
		$('#filter_data_btn').on('click',function(){
			var filters        = $('#filters').val();
			var serch_item     = $('#serch_item').val();
			var stock_location = $('#stock_location').val();
			var cat_id         = $('#cat_id').val();
			var sub_cat        = $('#sub_cat').val();
			var brand          = $('#brand_id').val();
			var edition_id     = $('#edition_id').val();

			$.post('<?php echo site_url($controller_name."/get_suggestion");?>',{'search':serch_item,'location_id':stock_location,'slc_subcate':sub_cat,'slc_cate':cat_id,'slc_brnd':brand,'filters[]':filters,'edition_id':edition_id},function(data){
					$('#table_holder').html(data);
			})
		})
	})

$(document).ready(function () {
	
	  // $('#table1').DataTable({
   //      "scrollX": true,
   //      dom: 'Bfrtip',
   //      order: [[0, 'desc']],
   //      buttons: [
   //        'copy', 'csv', 'excel', 'pdf', 'print'
   //      ]
   //    });

	$('.sub_chk').on('click', function(e) {
		$('#delete').attr("disabled", false);
		$('#bulk_edit').attr("disabled", false);
		
	});

	$(document).on('click','.sub_chk , #master',function(e) {
		if ($(".sub_chk").is(":checked")==false) {
			$('#delete').attr("disabled", true)	
			$('#bulk_edit').attr("disabled", true);
		}
		else{
			$('#bulk_edit').attr("disabled", false);
		}
	});	


    $('#master').on('click', function(e) {
       if($(this).is(':checked',true))  
        {
        	$('#delete').attr("disabled", false);
           $(".sub_chk").prop('checked', true);  
           $('#bulk_edit').attr("disabled", false);
         }
         else {  
            $(".sub_chk").prop('checked',false);
            $('#bulk_edit').attr("disabled", true);  
         }  
    });
     $('#delete').on('click', function(e) {

         var allVals = [];  
        $(".sub_chk:checked").each(function() {  
             allVals.push($(this).attr('data-id'));
         });  
        
      	  var ids = allVals.join(","); 
      	     
          $.post("<?php echo site_url($controller_name).'/delete_item'?>",{'id':ids},function (data) {  
                $('#table_holder').html(data);
                 $('#delete').attr("disabled", true);  
                  $('#bulk_edit').attr("disabled", true);  
                 	//refresh_data_table('table12');
               });
         });
  });
  $(document).ready(function(){
    $('#bulk_edit').hover(function(){
     	   var allVals = [];  
        $(".sub_chk:checked").each(function() {  
             allVals.push($(this).attr('data-id'));
         });  
        
      	  var ids = allVals.join(","); 
      	  $('#bulk_edit').attr('data-href','<?php echo site_url($controller_name."/bulk_edit/?id="); ?>'+ids)
     }); 
    })
</script>


<?php $this->load->view("partial/footer"); ?>
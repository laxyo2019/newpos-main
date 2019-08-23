
<script type="text/javascript">

	$(document).ready(function(){
     	 //for table row
  		$("tr:odd").css("background-color", "#f9f9f9");
  		$("#table123").css("width","100%");
  		$("#table123").css("display"," table-caption");

		});

	$(document).ready( function () {
    	$('#table123').DataTable({
    		 "lengthMenu": [[25, 50, -1], [10, 25, 50, "All"]],
    		'bJQueryUI': true,
    		'bAutoWidth': false,
    		'bSortClasses': false,
    		"bPaginate": true,
        	"bFilter": false,
        	"pagingType": "full_numbers",
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
	dialog_support.init("a.modal-dlg, button.modal-dlg-wide");


$(document).ready(function () {
	
	$('.sub_chk').on('click', function(e) {
		$('#delete').attr("disabled", false);
		$('#bulk_edit').attr("disabled", false);
		
	});

	$(document).on('click','.sub_chk , #master',function(e) {
		if ($(".sub_chk").is(":checked")==false) {
			$('#delete').attr("disabled", true)	
			$('#bulk_edit').attr("disabled", false);
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
        
      	  var join_selected_values = allVals.join(","); 
      	     
          $.ajax({
                url: "<?php echo site_url($controller_name).'/delete_item/'?>"+join_selected_values,
                type: 'get',
                /*data: 'ids=',*/
               success: function (data) {  
                $('#table_holder').html(data);
                 	//refresh_data_table('table12');
               }
           });
         
       });
  }); 
   
</script>
<div class="clearfix"></div>
<table id="table123" class="table table-responsive table-striped ">
<thead>
	<tr>
		<?php if($this->Item->is_both()) { 
		    if($this->Item->is_superadmin()) { ?>
				<th style="background-image: none;"><input style="margin-left: -8px;" type="checkbox" id="master"></th>
	 <?php  }
	 	} ?>			
		<th>id</td>
		<th>Barcode</td>
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
		<th style="background-image:none; padding-right: 50px; padding-left: 24px;">Action</th>
	</tr>
</thead>	
<tbody>
<?php
foreach($data as $row){ 

$item_id     = !empty($row['item_id'])?$row['item_id']:'';
$location_id = !empty($row['location_id'])?$row['location_id']:'';

$data  = $this->Item->get_multiple_info($item_id,$location_id)->result_array();

?>		
	<tr data-uniqueid="<?php echo $row['item_id'];?>">
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
		<td class="qty_td"><?php echo  $row['quantity']; ?></td>
		<td class="print_hide">
<?php if($this->Item->check_auth(array('superadmin','admin'))) {?>

	<a href="JavaScript:void(0)" class="qty_update" id="<?php echo $row['quantity'] ?>" data-btn-submit="Submit" title="Quick Quantity Update" style="margin-right: 9px;color: #000022d6;"><span class="glyphicon glyphicon-erase"></span></a>

<?php } ?>
			<a href="<?php echo site_url($controller_name.'/inventory/').$row['item_id'] ; ?>" class="modal-dlg" data-btn-submit="Submit" title="Update Inventory"><span style="padding-right: 10px;" class="glyphicon glyphicon-pushpin"></span></a>

		<a href="<?php echo site_url($controller_name.'/count_details/').$row['item_id'] ;?>" class="modal-dlg" title="Inventory Count Details"><span style="padding-right: 10px;" class="glyphicon glyphicon-list-alt"></span></a>

		<?php if($this->Item->check_auth(array('superadmin','admin'))) {?>

		<a href="<?php echo site_url($controller_name.'/view/') .$row['item_id']; ?>" class="modal-dlg" data-btn-submit="Submit" title="Update Item"><span class="glyphicon glyphicon-edit"></span></a>

		<?php } ?>
	</td>
	</tr>

<?php }
?>	</tbody> 
</table>

<script>

	$(document).ready(function(){
			$(".qty_update").on('click', function(event){
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
							if(data=='Successfully Updated'){
								$.notify(data);
								$("#"+item_qty).parent().parent().find('.qty_td').text(new_qty);
							}else{
								$.notify(data);
							}
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

		})
	</script>
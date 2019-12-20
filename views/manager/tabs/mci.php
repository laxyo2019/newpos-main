<?php $this->load->view("partial/header"); ?>

<div class="row">
	<div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> MCI 
  </div>
	<span class="col-md-2 pull-right">
		<select id="select_mci" class="form-control">
			<option value="">Select MCI</option>
			<option value="categories">Category</option>
			<option value="subcategories">Subcategory</option>
			<option value="brands">Brand</option>
			<option value="sizes">Size</option>
			<option value="colors">Color</option>
		</select>
	</span>
	
	<span class="col-md-6">
		<input type="text" id="create_mci" class="form-control input-sm" placeholder="Create MCI">
		<span id="liveresults_area"></span>
	</span>
	<span class="col-md-1">
		<input type="text" id="index" class="form-control input-sm" placeholder="ID" style="display:none">
	</span>
	<span class="col-md-2">
		<button id="save" class="btn btn-sm btn-success">Create</button>
	</span>
</div>
<hr>
<div class="row">
	<div id="suggestion" class="text-center"></div>
	<div class="col-md-6 col-md-offset-3" id="mci_sublist">
		
	</div>
	<div class="col-md-3">
		<select id="cSwitch" class="form-control" style="display:none">
			<?php foreach($this->Item->get_mci_data('categories') as $row): ?>
				<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<style type="text/css">
	.myImp{
		width: 100% !important;
    border: none !important;
    background: none !important;
  }
</style>
<script>
	$(document).ready(function() 
	{
		$('#create_mci').on('keyup', function(){ //livesearch
			var selected_mci = $('#select_mci').val();
			console.log(selected_mci);
			if(selected_mci != "")
			{
				var mci = $('#create_mci').val();
				if(mci.length > 0)
				{
					$.post('<?php echo site_url($controller_name."/mci_livesearch");?>', {'keyword': mci, 'type': selected_mci}, function(data) {
						$('#liveresults_area').html(data);
					});
				}
				else
				{
					$('#liveresults_area').html('');
				}
				
			}
			else
			{
				alert("Please select the MCI");
			}
		});

		$('#select_mci').on('change', function(){
			var selected_mci = $(this).val();
			var main = ["categories", "subcategories"];
			var main2 = ["subcategories"];
			$('#index').toggle(main.includes(selected_mci));
			$('#cSwitch').toggle(main2.includes(selected_mci));
			$.post('<?php echo site_url($controller_name."/get_mci_list"); ?>', {'type': selected_mci}, function(data) {
				$('#mci_sublist').html(data);
				$('#list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

		$('#cSwitch').on('change', function(){
			var parent_id = $(this).val();
			// console.log(parent_id);
			$.post('<?php echo site_url($controller_name."/get_mci_sublist");?>', {'parent_id': parent_id}, function(data) {
				$('#mci_sublist').html(data);
				$('#list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });
			});
		});

		$('#save').on('click', function(){
			<?php if($this->Item->is_both()){ ?>
				var id = "";
				var type = $('#select_mci').val();
				var name = $('#create_mci').val();
				var parent_id = "";
				if(type == 'categories')
				{
					id = $('#index').val();
				}
				if(type == 'subcategories')
				{
					id = $('#index').val();
					parent_id = $('#cSwitch').val();
				}
				$.post('<?php echo site_url($controller_name."/mci_save");?>', {'type': type, 'id': id, 'name': name, 'parent_id': parent_id}, function(data) {
					if(data == 1){
	        	alert("Duplicate Entry Not Saved!");
	        }else if(data == 2){
	        	alert('Successfully Created');
	        }else if(data == 3){
	        	alert('Fields are overloaded');
	        }else{
	        	var txt = "";
	        	var myObj = JSON.parse(data);
	        	txt += "<div class='row'><div class='col-md-3'></div><div class='col-md-6'><table class='table table-striped'>"
				    for (x in myObj) {
				      txt += "<tr><td><input class='myImp' type='text' value='" + myObj[x].name + "' id='myInput"+x+"' readonly></td><td><i class='fa fa-files-o' aria-hidden='true' onclick='myFunction("+ x +")'></i></td></tr>";
				    }
				    txt += "</table></div></div>" 
	        	$("#suggestion").append(txt);
	        }
					// location.reload();
				});

			<?php }else{ ?>
				alert('Access Denied!');
			<?php } ?>
		});

	});
</script>
<script type="text/javascript">
function myFunction(id) {
  var copyText = document.getElementById("myInput"+id);
  console.log(copyText);
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}
// function myFunction(id) {
//   var copyText = document.getElementsByClassName("myInput"+id)[0].getAttribute("data-value");
//   console.log(copyText);
//   copyText.select();
//   copyText.setSelectionRange(0, 99999)
//   document.execCommand("copy");
//   alert("Copied the text: " + copyText);
// }
	/*$(document).ready(function(){
    $('.myInput0').on("click", function(){
        value = $(this).data('value'); 
        console.log(value);
        var $temp = $("<input>");
          $("body").append($temp);
          $temp.val(value).select();
          document.execCommand("copy");
          $temp.remove();
    })
	})*/
</script>
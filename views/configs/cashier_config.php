<br><br>

<div id="toolbar">
  <div class="pull-left form-inline" role="toolbar">
	<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Create New</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Cashier</h4>
      </div>
      <div class="modal-body">
			<div class="form-group form-group-sm">
				<?php 
					echo '<span class="col-xs-8"> <label>Operating Shop</label>';
					echo form_dropdown('shop_id', $operating_shops, 'test', array('class' => 'form-control','id' => 'shop_id'));
				?>
				<!-- <input type="hidden" id="shop_id" value="7">  -->
				<div class='col-xs-8'>
					<?php echo form_input(array(
						'id'=>'name',
						'placeholder'=>'Name',
						'class'=>'form-control input-sm'
					));
					?>

					<?php echo form_input(array(
						'id'=>'sale_code',
						'placeholder'=>'Sale Code',
						'class'=>'form-control input-sm'
					));
					?>
				</div>
				<button class="btn btn-sm btn-success" id="save">Save</button>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  </div>
</div>

<style>
	table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
	}

	td, th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
	}

	tr:nth-child(even) {
			background-color: #dddddd;
	}
</style>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Sale Code</th>
			<th>Shop</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cashiers as $row): ?>
			<tr>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['sale_code']; ?></td>
				<td><span><?php echo $this->Employee->get_info($row['shop_id'])->first_name; ?></span></td>
				<!-- <span class="glyphicon glyphicon-edit"></span> -->
				<td id="<?php echo $row['id']; ?>"><span style="padding-left: 20px" class="glyphicon glyphicon-trash"></span></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function(){
		// $('#list').DataTable();

		$('#save').on('click', function(){
			var shop_id = $('#shop_id').val();
			var name = $('#name').val();
			var sale_code = $('#sale_code').val();
			// console.log(shop_id+' '+name+' '+sale_code);
			$.post('<?php echo site_url($controller_name."/cashier_save");?>', {'shop_id': shop_id, 'name': name, 'sale_code': sale_code}, function(data) {
				alert(data);
				location.reload();
      });
		});

		// $('.glyphicon-edit').on('click', function(){
		// 	var id = $(this).closest('td').attr('id');

		// });

		$('.glyphicon-trash').on('click', function(){
			var id = $(this).closest('td').attr('id');
			console.log(id);
			$.post('<?php echo site_url($controller_name."/cashier_delete");?>', {'id': id}, function(data) {
				alert(data);
				location.reload();
      });
		});
	
	});
</script>
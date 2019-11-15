<?php $this->load->view("partial/header"); ?>


	<div class="container">
		<div class="row ">
			<div class="col-md-12">
				<h2><?php echo $owner['location_name'] ;?> Login and Logout Details</h2>
				<br>
			</div>
			<div class="col-md-12">
				<table class="table" id="tableLogins">
					<thead>
						<tr>
							<td>Login Time</td>
							<td>Logout Time</td>
							<td>Date</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach($logins as $login) : ?>
						<tr>
							<td><?php echo $login->logintime;?></td>
							<td><?php echo $login->logouttime;?></td>
							<td><?php echo date('d-m-Y',strtotime($login->date)) ; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>		
	</div>

<script type="text/javascript">
	$(document).ready(function(){
$('#tableLogins').DataTable({
			dom: 'Bfrtip',
	        order: [[2, 'desc']],
	        buttons: [
	          'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
		});
	});
</script>


<?php $this->load->view("partial/footer");?>
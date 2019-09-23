	<h3 class=""><b>Login And Logout Details</b></h3>
		<div class="row">
			<div class="col-md-4">			
			<h5>Login Time:- <span id="logintime"><?php echo $time[0]->logintime ;?></span></h5>

			</div>
			<div class="col-md-4">					
			<h5>Logout Time:- <span id="logouttime"><?php echo $time[0]->logouttime ;?></span></h5>
			</div>				
			<div class="col-md-2 pull-right">					
				<a href="<?php echo base_url() ?>home/view_all/<?php echo $person_id; ?>" id="all_details" class="btn btn-success pull-right">View All</a>
			</div>
		
		</div>
		<div class="row">
			<br>
			<div class="col-md-12 show_all_details" >

				<table class="table table-hover table-bordered" >
					<thead >
						<tr>
							<th>Login Time</th>
							<th>Logout Time</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>

						<?php 

						if(count($login) != 0){ 
						foreach($login as $row) :

							?>
							<tr>
								<td><?php echo $row->logintime;?></td>
								<td><?php echo $row->logouttime;?></td>
								<td><?php echo date('d-m-Y',strtotime($row->date)) ; ?></td>
							</tr>

						<?php endforeach;
							}
							else { ?>
								<tr class="text-center"><td colspan="3">No matching records found</td></tr>
							<?php }
						?>

					</tbody>
				</table>
			</div>
		</div>
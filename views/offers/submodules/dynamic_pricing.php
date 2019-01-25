<div class="row">
  <span class="col-md-2 pull-right">
		<select id="select_plan" class="form-control">
			<option value="">Select Plan</option>
      <?php
        foreach($plans as $row)
				{
					echo '<option value="'.$row['varchar_value'].'">'.$row['title'].'</option>';
        }
      ?>
		</select>
	</span>
  <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php echo site_url($controller_name."/view_basic"); ?>'
            title='Create New Offer'>
      Create
  </button>

</div>
<hr>
<div id="dynamic_prices_table_area"></div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('#select_plan').on('change', function(){
			var selected_plan = $(this).val();
			$.post('<?php echo site_url($controller_name."/get_dynamic_prices"); ?>', {'plan': selected_plan}, function(data) {
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

	});
</script>


  <!-- <div class="col-md-6 col-md-offset-3">
    <ul class="nav nav-tabs">
    <?php //foreach($plans as $row): ?>
        <li><a href="#" onclick="event.preventDefault();" id="<?php //echo $row['varchar_value']; ?>" class="text-warning"><?php //echo $row['title']; ?></a></li>
      <?php //endforeach; ?>
    </ul>
  </div>

  <a class="modal-dlg-wide" href="<?php //echo site_url($controller_name."/view_basic"); ?>">
    <span class="glyphicon glyphicon-plus"></span>
  </a> -->
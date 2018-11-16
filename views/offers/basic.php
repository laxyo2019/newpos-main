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
<div id="offers_table_area"></div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('#select_plan').on('change', function(){
			var selected_plan = $(this).val();
			$.post('<?php echo site_url($controller_name."/get_offers_sublist"); ?>', {'plan': selected_plan}, function(data) {
				$('#offers_table_area').html(data);
				$('#offer_list').DataTable({
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
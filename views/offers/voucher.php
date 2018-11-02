<div class="row">
  <span class="col-md-2 pull-right">
		<select id="select_voucher" class="form-control">
			<option value="">Select Voucher</option>
      <?php
        foreach($special_vouchers as $row)
				{
					echo '<option value="'.$row['varchar_value'].'">'.$row['title'].'</option>';
        }
      ?>
		</select>
  </span>

  <!-- <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php //echo site_url($controller_name."/generate_voucher"); ?>'
            title='Generate New Vouchers'>
      Generate
  </button> -->
  <!-- <button class='btn btn-info pull-right modal-dlg-wide' data-href='<?php //echo site_url($controller_name."/view_voucher"); ?>'
            title='Add New Special Voucher'>
      Add New
  </button> -->

</div>
  
<hr>

<div id="table_area"></div>

<script>
	$(document).ready( function () {
    // dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");

    $('#select_voucher').on('change', function(){
			var selected_voucher = $(this).val();
			$.post('<?php echo site_url($controller_name."/get_vouchers_sublist"); ?>', {'voucher': selected_voucher}, function(data) {
				$('#table_area').html(data);
				$('#list').DataTable({
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
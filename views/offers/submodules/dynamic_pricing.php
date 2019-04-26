
<?php $this->load->view("partial/header");?>
<div class="row">
  <button class='btn btn-info pull-right modal-dlg-wide' data-btn-submit="Save" data-href='<?php echo site_url($controller_name."/view_basic"); ?>'
            title='Create New Offer'>
      Create
  </button>

</div>
<hr>
<div id="dynamic_prices_table_area"></div>

<script>
	$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
    
    $.post('<?php echo site_url($controller_name."/get_dynamic_prices"); ?>', {}, function(data) {
      $('#dynamic_prices_table_area').html(data);
      $('#dynamic_prices').DataTable({
          "scrollX": true,
          "ordering": false,
          dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
          ]
        });
    });
	});
</script>

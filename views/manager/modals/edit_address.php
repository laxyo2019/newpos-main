<div class="row"><span class="col-md-6">
    <select id="edit_stock_location" class="form-control">
      <option value="">-- Select Stock Location --</option>
      <option value="laxyo">LaxyoHouse</option>
      <option value="dn">Dewasnaka</option>
      <option value="ip">Indraprastha</option>
      <option value="bk">Bhanvarkuan</option>
      <option value="ap">Annapurna</option>
      <option value="amazon">Amazon</option>
      <option value="ml">Mahalaxmi</option>
      <option value="rl">Ratlam</option>
      <option value="sp">Shivpuri</option>
      <option value="bp">Bapat</option>
      <option value="ag">Apnagps</option>
    </select>
  </span>
</div>
<hr>
<div id="edit_table_area"></div>
<script>
  $(document).ready(function(){

    $('#edit_stock_location').on('change', function(){
      var report_edit = $('#edit_stock_location').val();
      $('#bulk_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/edit_stock_location") ?>', {'report_edit': report_edit}, function(data) {
          $('#edit_table_area').html(data);
          $('#edit_action_list').DataTable({
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
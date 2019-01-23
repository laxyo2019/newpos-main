<?php $this->load->view("partial/header"); ?>
<h4>Select Any Location</h4>
<div class="row"><span class="col-md-6">
    <select id="edit_stock_location" class="form-control">
      <option value="">-- Select Stock Location --</option>
      <option value="laxyohouse">LaxyoHouse</option>
      <option value="dewasnaka">Dewasnaka</option>
      <option value="indraprastha">Indraprastha</option>
      <option value="bhanvarkuan">Bhanvarkuan</option>
      <option value="annapurna">Annapurna</option>
      <option value="amazon">Amazon</option>
      <option value="mahalaxmi">Mahalaxmi</option>
      <option value="ratlam">Ratlam</option>
      <option value="shivpuri">Shivpuri</option>
      <option value="bapat">Bapat</option>
      <option value="apnagps">Apnagps</option>
    </select>
  </span>
</div>
<br>
<div id="edit_table_area"></div>
<?php $this->load->view("partial/footer"); ?>
<script>
  $(document).ready(function(){

    $('#edit_stock_location').on('change', function(){
      var report_edit = $('#edit_stock_location').val();
      $('#bulk_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');
      $.post('<?php echo site_url($controller_name."/detail_stocklocation") ?>', {'report_edit': report_edit}, function(data) {
          $('#edit_table_area').html(data);
          /*$('#edit_action_list').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
            ]
          });*/
        });
      });
  });
</script>
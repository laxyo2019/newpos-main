<div class="row">
  <div class="col-md-6 col-md-offset-3">
    <select class="form-control" id="deck_switch">
      <option value="">Select Location</option>
      <?php
      foreach($this->Pricing->get_active_shops(array('dbf', 'shop')) as $row)
      {
        echo '<option value="'.$row['person_id'].'">'.strtoupper($row['username']).'</option>';
      }
      ?>
    </select>
  </div>
</div>

<hr>

<div id="table_area"></div>

<script>
  $(document).ready( function () {

    $('#deck_switch').on('change', function(){
      var shop_id = $(this).val();
      $.post('<?php echo site_url($controller_name."/switch_deck"); ?>', {'shop_id': shop_id}, function(data) {
        $('#table_area').html(data);
        $('#deck_sublist').DataTable({
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
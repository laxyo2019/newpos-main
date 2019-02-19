<?php $this->load->view("partial/header"); ?>
<h5 style="text-align: center; text-decoration-line: underline;">Sales Report - Custom Format</h5>
<br>
<div class="row">
  <span class="col-md-4">
     <div class="form-group">
      <select class="form-control" id="location_id">
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    </span>
    <span class="col-md-3">
     <div class="form-group">
      <select class="form-control" id="sale_type">
       <option value="all">All</option>
       <option value="1" style="margin-bottom: 50px;">Credit Note</option>
       <option value="0" style="margin-bottom: 50px;">Invoice</option>
       </select> 
    </div>
    </span>
    <span class="col-md-3">
    <div class="form-group">
      <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input', 'id'=>'daterangepicker')); ?> 
    </div>
    </span>

    <span class="col-md-4">
    <div class="form-group">
         <button class="btn btn-sm btn-primary" id="monthlyFormat">Get Sales</button>
    </div>
    </span>
</div>
<hr>
<div id="report_table_area"></div>
<?php $this->load->view("partial/footer"); ?>
 
<script>
  $(document).ready( function () {
    <?php $this->load->view('partial/daterangepicker'); ?>

    $('#monthlyFormat').on('click', function(){
       var location_id = $('#location_id').val();
       console.log(location_id);
       if(location_id != null)
      {
      $('#report_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');

      $.post('<?php echo site_url($controller_name."/custom_sales_format");?>', 
        {'start_date': start_date, 'end_date': end_date, 'location_id': location_id}, function(data) {
          $('#report_table_area').html(data);
          $('#report_list').DataTable({
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                  'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
          });
    }
     else
      {
        alert('Please select a location');
      }
    });

  });
</script>
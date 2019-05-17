<style>
.label_text {
    font-weight: bold;
    font-size: 15px;
    margin-top: 10px;
    float: right;
}
</style>
<?php $this->load->view("partial/header"); ?>
<h5 style="text-align: center; text-decoration-line: underline;">Sales Report - Monthly Format</h5>
<br>
<div class="row">

  <span class="col-md-6">
  <div class="col-sm-4">
    <label class='label_text' for="">Location : </label>
  </div>
  <div class="col-sm-8">
  <div class="form-group">
      <select class="form-control" id="location_id">
      <!-- <option value="">Select Location</option> -->
        <?php foreach($stock_locations as $key=>$value): ?>
          <option value="<?php echo $key; ?>"><?php echo strtoupper($value); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
   
    </span>
     <span class="col-md-6">
     <div class="col-sm-4">

     <label class='label_text' for="">Bill Type : </label>
     </div>
     <div class="col-sm-8">
     <div class="form-group">
      <select class="form-control" id="sale_type">
      <!-- <option value="">Select Bill Type</option> -->
       <option value="all">All</option>
       <option value="4">Credit Note</option>
       <option value="1">Invoice</option>
       </select> 
    </div>
     </div>
    
    </span>

    </div>
    <div class="row">
    <span class="col-md-6">
    <div class="col-sm-4">

    <label class='label_text' for="">Month : </label>
    </div>
    <div class="col-sm-8">
    <div class="form-group">
       <select class="form-control" id="select_month">
       <!-- <option value=""> Select Month</option> -->
       <option value="01">JANUARY</option>
       <option value="02">FEBRUARY</option>
       <option value="03">MARCH</option>
       <option value="04">APRIL</option>
       <option value="05">MAY</option>
       <option value="06">JUNE</option>
       <option value="07">JULY</option>
       <option value="08">AUGUST</option>
       <option value="09">SEPTEMBER</option>
       <option value="10">OCTOBER</option>
       <option value="11">NOVEMBER</option>
       <option value="12">DECEMBER</option>
       </select> 
    </div>
    </div>
   
    </span>

    

  <span class="col-md-6">
  <div class="col-sm-4">
  <label class='label_text' for="">Year : </label>
  </div>
  <div class="col-sm-8">
  <div class="form-group">
      <select class="form-control" id="select_year">
      <!-- <option value="">Select Year</option> -->
       <option value="2018">2018</option>
       <option value="2019">2019</option>
       <option value="2020">2020</option>
       </select>    
    </div>
  </div>
   
  </span>
  
  <span class="col-md-3 col-md-offset-9">
    <div class="form-group">
         <button class="btn btn-sm btn-primary pull-right" id="monthlyFormat">Get Sales</button>
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
       
       var sale_type = $('#sale_type').val();
       var select_month = $('#select_month').val();
       var select_year = $('#select_year').val();


       var start_date =  select_year + '-' + select_month + '-' + '01';
       var lastday = function(select_year,select_month){
        return  new Date(select_year, select_month, 0).getDate();
       }
       
       var end_date = select_year + '-' + select_month + '-' + lastday(select_year,select_month);
       console.log(location_id);
       console.log(lastday(select_year,select_month));
     // if(location_id != "" && sale_type!="" && select_month!="" && select_year!="" )
     if(location_id != null )
      {
      $('#report_table_area').html('<img src="<?php echo base_url('images/loader_icon1.gif'); ?>" alt="loading" />');

      $.post('<?php echo site_url($controller_name."/monthly_sales_format");?>', 
        {'start_date': start_date, 'end_date':end_date, 'sale_type':sale_type, 'location_id': location_id}, function(data) {
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
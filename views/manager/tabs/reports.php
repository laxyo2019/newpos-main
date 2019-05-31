<?php $this->load->view("partial/header"); ?>
<?php if($this->Item->check_auth(array('superadmin', 'admin'))){ ?>
<div class="row">
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'><h4 style='display:inline'>Manager</h4>  </a>>> Reports 
  </div>
  <div class="column">
    <div class="col-md-4">
    <div class="list-group">
    <h4  class="list-group-item disabled" style="background-color: #132639;"><span class="glyphicon glyphicon-stats" style="color: white;">
    Sales Report</span></h4>
  <a href="<?php echo site_url('manager/tally_report'); ?>" class="list-group-item">Tally Format</a>
  <a href="<?php echo site_url('manager/monthly_report'); ?>" class="list-group-item">Monthly Format</a>
  <a href="<?php echo site_url('manager/custom_report'); ?>" class="list-group-item">Custom Format</a>

  <a href="<?php echo site_url('manager/send_email'); ?>" class="list-group-item">Send Email</a>
</div>
</div>
</div>
</div>
<?php } else{ ?>
  <div class='bg-info' style='color:#fff;padding:10px;margin-bottom:20px;'>
      <a style='color:#fff' href='<?php echo site_url('manager');?>'>Manager </a>>> Reports 
  </div>
<div class='text-danger'><h3 class='text-center'> Persmission Denied</h3></div>
<?php } ?>


   
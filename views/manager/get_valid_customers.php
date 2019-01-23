<?php $this->load->view("partial/header"); ?>
<h3 class="text-center">Get Valid Customers</h3>
<div class="row">
  <div class="form-group">
    <textarea id="list" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <input id="submit" type="submit" class="btn btn-success form-control">
  </div>
</div>

<textarea class="form-control" id="mList"></textarea>

<script>
  $(document).ready(function(){
    $('#submit').on('click', function(){
      var customers = $('#list').val();
      $.post('<?php echo site_url($controller_name."/fetch_valid_customers");?>', {'customers': customers}, function(data) {
				$('#mList').html(data);
      });
    });
  });
</script>
  
<div class="row">
<div class="col-xs-2 pull-right">
<button id="create_pointer" type="submit" class="btn btn-sm btn-success">Submit</button>
  </div>


  <div class="col-xs-6 col-xs-offset-3">
  <input type ="test" id ="pointer" name ="pointer" placeholder ="Add Pointer" class="form-control input-sm">
   <!-- <?php echo form_input(array(
      'id'=>'pointer',
      'placeholder'=>'Add Pointer',
      'class'=>'form-control input-sm'
    ));
    ?>-->
</div>
</div>
<!--
<script>
  $(document).ready(function(){
    dialog_support.init("button.modal-dlg, a.modal-dlg");
    $('#save').on('click', function(){
			var pointer = $('#pointer').val();
			$.post('<?php echo site_url($controller_name."/pointer_save");?>', {'id': id, 'pointer': pointer}, function(data) {
				alert(data);
      });
		});
  });
</script>-->
<script>
	$(document).ready( function () {
    $('#create_pointer').on('click', function(){
      var pointer = $('#pointer').val();
      $.post('<?php echo site_url($controller_name."/pointer_save"); ?>', 
      {
        'pointer': pointer
      }, 
      function(data) {
      alert(data);
			  });
        
			});
		});

</script>

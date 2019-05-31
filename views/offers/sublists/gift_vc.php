<script type="text/javascript">
	dialog_support.init(".modal-dlg");
</script>
<?php echo form_open('offers/all_vouchers/', array('id'=>'myform')); ?>
<p class="text-right"><input type="submit" name="submit" Value="Print" class="btn  btn-info" ></p>
<!-- <p id="demo"></p> -->
<table class="table table-striped table-bordered table-hover"  id="gc_table"   >
  <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Voucher Value</th>
        <th>Code</th>
        <th>Expiry Date</th>
        <th>Redeem At</th>
        <th>Created At</th>
        <th>Edit Expiry Date</th>
        <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($vc_info as $row): ?>
      <tr>
        <td><?php echo $row->id; ?></td>
        <td><?php echo $row->title; ?></td>
        <td><?php echo $row->vc_value; ?></td>
        <td><?php echo $row->voucher_code; ?></td>
        <td><?php echo $row->expiry_date; ?></td>
        <td><?php echo $row->redeem_at; ?></td>
        <td><?php echo $row->created_at; ?></td>
        <td>
        <a href="<?php echo base_url();?>offers/edit_gift_vc/<?php echo $row->id; ?>" class="modal-dlg fa fa-pencil-square edit" title="Edit" style="font-size:20px;"></a>
        </td>
        <td><input class="cb" type="checkbox" name="voucher_ids[]" value="<?php echo $row->id; ?>"> </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script>

$(document).ready(function () { 

    $('#gc_table').DataTable({
       
         
       dom: 'Bfrtip',
           buttons: [
               'copy', 'csv', 'excel', 'pdf', 'print'
           ]
    });
    

    // $("#print").click(function(){  
    //     var cb = [];
    //     $.each($('.cb:checked'), function() {
    //     cb.push($(this).val());
    //     });

    //     var count = cb.length; 
    //     console.log(count);
    //     if(count == 0){
    //         swal({
    //                 text: "Selected at least one voucher",
    //                 icon: "warning",
    //             });
    //     }
    //     else if(count > 8){
    //         swal({
    //                 text: "You can not select more than 8 vouchers!",
    //                 icon: "warning",
    //             });
    //     }
    //     else{
    //         $.post('<?php //echo site_url($controller_name."/voucher_try/");?>', {'myData' : cb}, function() {
    //             $('#demo').html();
                
    //           //  window.open(this.href, '_blank');
    //          });
    //     }
    // });


   
    
});
</script>    


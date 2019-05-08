
<script type="text/javascript">
	dialog_support.init(".modal-dlg");
</script>
<table class="table table-striped"  id="gc_table">
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
            <th>Print</th>
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
                
                <td><a href="<?php echo base_url(); ?>offers/view_gift_vc/<?php echo $row->id; ?>" target="_blank" class="fa fa-eye edit" title="View" style="font-size:20px; color: #3498db;"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#gc_table').DataTable({
        //"scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});   
</script>    
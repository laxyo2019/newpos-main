<table id="loc_group_table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Locations</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($loc_group as $row):
        ?>    
        <tr> 
            <td><?php echo $row->id?></td>
            <td><?php echo $row->title?></td>
            <td>
            <?php   
                echo substr($this->Control_Panel->fetch_username($row->locations,'employees'),0,-2);
                ?>
            </td>
            <td>
            
            <!-- <a title="Delete" style="font-size:17px;margin-left:10px;" class='text-danger fa fa-trash  modal-dlg-wide' data-href='<?php //echo site_url(); ?>/offers/delete_row/offer_location_groups/location_group_id/<?php // echo $row->id; ?>'></a> -->
            
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
})
$('#loc_group_table').DataTable({
    order:[[0,'desc']],
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ]
});
</script>
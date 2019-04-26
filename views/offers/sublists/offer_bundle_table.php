 <table id="offer_bundle_tbl">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Type</th>
            <th>Bundle</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bundles as $bundle):?>
            <tr>
                <td><?php echo $bundle->id;?></td>
                <td><?php echo $bundle->title;?></td>
                <td><?php   
                $decoded_bundle = json_decode($bundle->bundle);
               echo $decoded_bundle->type;
                ?></td>
                <td><?php 
                $bundle_arr = $decoded_bundle->entities;
                $bundle_string="";
                   foreach( $bundle_arr as $row){
                    $bundle_string.= $this->Control_Panel->get_mci_info($row,$decoded_bundle->type);
                   }
                   echo substr($bundle_string,0,-1);
                ?></td>
                <td>
                
                <!-- <a title="Delete" style="font-size:17px;margin-left:10px;" class='text-danger fa fa-trash  modal-dlg-wide' data-href='<?php //echo site_url(); ?>/offers/delete_row/offer_pointer_groups/pointer_group_id/<?php //echo $bundle->id; ?>'></a> -->
                
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<script>
$(document).ready( function () {
    dialog_support.init("button.modal-dlg-wide, a.modal-dlg-wide");
})
$('#offer_bundle_tbl').DataTable({
    order:[[0,'desc']]
});
</script>
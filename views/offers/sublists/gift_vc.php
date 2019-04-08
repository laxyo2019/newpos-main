<thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Voucher Value</th>
            <th>Code</th>
            <th>Expiry Date</th>
            <th>Redeem At</th>
            <th>Created At</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Print</th>
        </tr>
        </thead>
        <tbody>
            <?php
                foreach($vc_info as $row){
                echo '<tr>
                      <td>'.$row->id.'</td>
                      <td>'.$row->title.'</td>
                      <td>'.$row->vc_value.'</td>
                      <td>'.$row->voucher_code.'</td>
                      <td>'.$row->exp_date.'</td>
                      <td>'.$row->redeem_at.'</td>
                      <td>'.$row->created_at.'</td>
                      <td><a href="'.base_url().'/offers/edit_gift_vc/'.$row->id.'" class="modal-dlg-wide fa fa-pencil-square edit" title="Edit" style="font-size:20px;"></a></td>
                      <td><a href="javascript:void(0)" onclick = "delete_gift_vc('.$row->id.');" class="fa fa-trash text-danger delete_icon" title="Delete" style="font-size:20px;"></a></td>
                      <td><a href="'.base_url().'/offers/view_gift_vc/'.$row->id.'" target="_blank" class="fa fa-eye edit" title="View" style="font-size:20px; color: #3498db;"></a></td>
                    </tr>';
            ?>
            <?php 
                }
            ?>
        </tbody>
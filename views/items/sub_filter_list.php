<table id="list" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
    <?php foreach ($subcategories as $key => $value): ?>
    	<tr>
            <td><?php echo $key; ?></td>
            <td class="v-data" id="<?php echo $key; ?>"><span><?php echo $value; ?></span></td>
            <!-- <td class="v-trash" id="<?php //echo $key; ?>"><span class="glyphicon glyphicon-trash"></span></td> -->
        </tr>
     <?php endforeach; ?>
    </tbody>
</table>
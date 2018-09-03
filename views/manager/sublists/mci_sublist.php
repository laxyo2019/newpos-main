<table id="list" class="display nowrap" style="width:100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($mci_data as $row): ?>
    <tr style="text-align: center;">
      <td><?php echo $row['id']; ?></td>
      <td class="v-data" id="<?php echo $row['id']; ?>"><span><?php echo $row['name']; ?></span></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
  $(".v-data").on('click', function(){
    var type = $('#select_mci').val();
    var id = this.id;
    var text = $(this).find("span").text();
    if($('#cSwitch').val() != null)
    {
      var parent_id = $('#cSwitch').val();
    }
    else
    {
      var parent_id = '';
    }

    var new_val = prompt("Please enter value", text);
    if (new_val != null)
    {   
      $.post('<?php echo site_url($controller_name."/mci_update");?>', {'name': new_val, 'id': id, 'type': type, 'parent_id': parent_id}, function(data) {
        alert(data);
        location.reload();
      	});
    }
  });
</script>
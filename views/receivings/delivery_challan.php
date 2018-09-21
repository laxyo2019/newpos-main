<?php $this->load->view("partial/header"); ?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<?php
if(isset($error_message))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
	exit;
}
?>

<div class="print_hide pull-right">
  <a href="javascript:void(0);" onclick="window.print()"><div class="btn btn-info btn-sm", id="show_print_button"><?php echo '<span class="glyphicon glyphicon-print">&nbsp</span>' . $this->lang->line('common_print'); ?></div></a>
</div>

<div class="container">
  <div class="row">
    <span class="col-md-4">
      <img id="image" height="60" width="150" src="<?php echo base_url('images/laxyo.png'); ?>" alt="company_logo" />
    </span>
    <span class="col-md-8 pull-right">
      <p>Laxyo House, Plot No. 2, County park, MR-5, Mahalaxmi Nagar, Indore-10 (MP)</p>
      <p>Phone: +91-731-4043798, Mobile: 8815218210</p>
    </span>
  </div>
  <div class="row">
    <div class="col-md-12" style="font-size:1.5em; text-align:center">
      <p>CHALLAN  (NOT FOR SALE)</p>
      <p>LAXYO ENERGY LTD. - GSTIN - 23AABCL3031E1Z9</p>
    </div>
  
    <div class="col-md-12">
      <table class="table table-bordered table-condensed small">
        <thead>
          <tr>
            <th>CHALLAN NO.</th>
            <th>STOCK TRANSFER ORDER NO.</th>
            <th>CONSIGNOR (from)</th>
            <th>CONSIGNEE (to)</th>
            <th>PICKED BY</th>
            <th>DISPATCH THROUGH</th>
            <th>DATE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $challan_id; ?></td>
            <td><?php echo $stock_transfer_id; ?></td>
            <td><input style="border:none;" value="LEL INDORE" type="text"></td>
            <td><select>
              <option value=""></option>
              <option value="">LAXYOHOUSE</option>
              <option value="">DBF MAHALAXMI</option>
              <option value="">DBF BHANVARKUAN</option>
              <option value="">DBF INDRAPRASTHA</option>
              <option value="">LAXYO ANNAPURNA</option>
            </select></td>
            <td><input style="border:none;" value="" type="text"></td>
            <td><input style="border:none;" value="MP.43.C.1596" type="text"></td>
            <td><?php echo date('d-m-Y H:i'); ?></td>
          </tr>
        </tbody>
      </table>  

      <table class="table table-bordered table-condensed small">
        <thead>
          <tr>
            <th>Sn.</th>
            <th>Barcode</th>
            <th>Name of goods</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Quantity</th>
            <th>Remark</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach($items as $row): ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $row->item_number; ?></td>
              <td><?php echo $row->name; ?></td>
              <td><?php echo $row->category?></td>
              <td><?php echo $row->subcategory?></td>
              <td><?php echo $row->quantity?></td>
              <td></td>
            </tr>
            <?php $i++; ?>
          <?php endforeach; ?>
            <tr>
              <td colspan="5"><b>Total Quantity</b></td>
              <td><?php echo $total_quantity; ?></td>
              <td></td>
            </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-12">
      <p class="text-center small">(BEING THE GOODS TRANSFER FROM WAREHOUSE TO SHOP/SHOP TO WAREHOUSE HENCE NO COMMERCIAL VALUE)</p><br><br><br>
      <img id="image" style="position:absolute; bottom:10px; transform: rotate(-18deg)" height="90" width="90" src="<?php echo base_url('images/lel_stamp.png'); ?>" alt="company_stamp" />
        <p>FOR LAXYO ENERGY LTD.</p>
        <p>AUTHORISED SIGNATORY</p>
      </span>
    </div>

  </div>
</div>

<?php $this->load->view("partial/footer"); ?>
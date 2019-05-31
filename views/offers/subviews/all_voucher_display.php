
<?php $this->load->view("partial/header"); ?>
<?php
    $vouchers_arr  = array();
    foreach( $vou_id as $vou_ids){
        $vouchers_arr [] = $vou_ids;
    }
    $this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value , vc_gift_master.bg_img as bg_img');
    $this->db->from('voucher_gifts');
    $this->db->where_in('voucher_gifts.id',$vouchers_arr);
    $this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
    $all_vouchers = $this->db->get()->result();
?>
<div class="row">
   <?php foreach($all_vouchers as $row) :?>
        <div class='pagewidth' style='' >
            <table>
                <tr>
                <td class='bg_img_div' style='position:absolute;'>
                    <img src='<?php echo base_url()."images/vouchers/".$row->bg_img?>.png' style='width:425px; height:300px;'> 
                </td>
                <td style='text-align:center; position:relative; width:425px; height:300px;'>
                    <img class='dbf_logo' src="<?php echo base_url('images/dbf_mini_logo.png'); ?>" />
                    <p class='gift_voucher'>GIFT VOUCHER</p>
                    <p class='vc_value'><?php echo to_currency($row->vc_value) ?></p>
                    <p class='code'>CODE : <?php echo $row->voucher_code ?></p>
                    <p class='exp_date'>Valid till : <?php echo date("d M Y", strtotime($row->expiry_date))?> </p>
                    <p class='tnc'>T&C Apply</p>       
                </td>
                </tr>
            </table>
        </div>
<?php endforeach; ?>                      
</div>

<style>
.pagewidth {            
            margin-left:30px;
            width :45%;
            float:left;
            margin-top:50px;
         }

    .dbf_logo{
        margin-top:-57px;
        width:73px;
        height:43px;

    }

    .gift_voucher, .vc_value, .code{
        text-align: center;
        color: #fff;
        font-size: 15px;
        margin-top: 10.5px;
        font-family: sans-serif;
    }
    .vc_value{
        font-size:40px;
        font-weight: 700;
        margin-bottom:0px;
        
    }
    .tnc {
        position: absolute;
        bottom: 0;
        right: 8px;
        color: #fff;
    }
    .exp_date {
        position: absolute;
        bottom: 0;
        font-family: sans-serif;
        left: 10px;
        font-size:14px ;
        color: #fff;
    }
    .code{
        font-size: 11px;
        margin-top: 4px;
    }

@media print {
    * {
        -webkit-print-color-adjust:exact!important;
    }
    p {
        color: white !important;
    }
}

</style>
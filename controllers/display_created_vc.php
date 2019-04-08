<?php $this->load->view("partial/header");
$this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value');
$this->db->from('voucher_gifts');
$this->db->where('voucher_gifts.id',$id);
$this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
$voucher = $this->db->get()->result();
?>
<div class="row">
<div class="col-md-10 col-md-offest-1">
   <?php  
        foreach($voucher as $row){
            echo "
            <div class='main_div' style='margin-top:100px;'>
            <div style='position:relative;margin:auto;width:576px'>
                <div class='bg_img_div' style='position:absolute;margin:auto;width:576px;height:384px;'>
                    <img src='".base_url()."images/vouchers/".$row->bg_img.".png'>
                </div>
                <div class='slider_img' style='position:relative;margin:auto;width:576px;height:384px;'>
                    <div style='text-align:center'><img class='dbf_logo' src='".base_url()."images/dbf_mini_logo.png'></div>
                    <p class='gift_voucher'>GIFT VOUCHER</p>
                    <p class='vc_value'>".to_currency($row->vc_value)."</p>
                    <p class='code'>CODE : ".$row->code."</p>
                    <p class='tnc'>T&C Apply</p>
                </div>
            </div>
            </div>";
        }   
   ?>
</div>
</div>

<style>

.dbf_logo{
    margin-top:30px;
}

.gift_voucher, .vc_value, .code {
    text-align: center;
    color: #fff;
    font-size: 20px;
    margin-top: 27px;
    font-family: sans-serif;
}
.vc_value{
    font-size:60px;
    font-weight: 700;
}
.tnc {
    position: absolute;
    bottom: 0;
    right: 8px;
    color: #fff;
}
.code{
    font-size: 25px;
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

<?php $this->load->view("partial/header");
$this->db->select('voucher_gifts.* , vc_gift_master.title as title, vc_gift_master.vc_value as vc_value');
$this->db->from('voucher_gifts');
$this->db->where_in('voucher_gifts.id',$ids);
$this->db->join('vc_gift_master','voucher_gifts.voucher_id=vc_gift_master.id','inner');
$vouchers = $this->db->get()->result();

?>
<div class="row">
<div class="col-md-9">
   <?php
        $i=0;    
        foreach($vouchers as $row){
            $i++;
            echo "
            <div class='main_div' id='main_div_".$i."' style='margin-top:100px;'>
            <div style='position:relative;margin:auto;width:576px'>
                <div class='bg_img_div' id='".$i."_bg' style='position:absolute;margin:auto;width:576px;height:384px;'>
                    <img src='".base_url()."images/vouchers/".$bg_img."'>
                </div>
                <div class='slider_img' id='".$i."' style='position:relative;margin:auto;width:576px;height:384px;'>
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
<button class="btn btn-info btn-sm col-sm-1 click_button" onclick="previous();"> Previous</button>
<button style="margin-left:10px;" class="btn btn-info btn-sm col-sm-1 click_button" onclick="next();"> Next</button><br>
<h6 class="page_count"><span id="page_no">1</span> out of <span><?php echo ceil($i/2)?></span></h6>
<input id="current_page" value="2" type="hidden">
<input id="total" value="<?php echo $i;?>" type="hidden">
</div>


<script> 
$('document').ready(function(){
   $('.main_div').addClass('hide');
   $('#main_div_1').removeClass('hide');
   if($("#main_div_2").length != 0) {
        $('#main_div_2').removeClass('hide');
    }
})
function previous(){
    var page = parseInt($('#current_page').val());
    if(page==2){
      return false;
    }else{
       page = page-2;
        $('.main_div').addClass('hide');
        $('#main_div_'+page).removeClass('hide');
        $('#current_page').val(page);
       page--;
        $('#main_div_'+page).removeClass('hide');
        var page_no = parseInt($('#page_no').text());
        page_no-- ;
        $('#page_no').text(page_no);
    }
}
function next(){
    var page = parseInt($('#current_page').val());
    var total = parseInt($('#total').val());
    if(page == total || page==(total+1)){
      return false;
    }else{
        var page_no = parseInt($('#page_no').text());
        page_no++ ;
        $('#page_no').text(page_no);
       page++;
      $('.main_div').addClass('hide');
      $('#main_div_'+page).removeClass('hide');
        page++;
      if($('#main_div_'+page).length==0){
        $('#current_page').val(page);
      }else{
        $('#main_div_'+page).removeClass('hide');
        $('#current_page').val(page);
      }
    }
}

</script>
<style>
.page_count{
    float: left;
    font-size: 15px;
    margin-left: 15px;
}
.click_button{
    margin:10px;
}
.dbf_logo{
    margin-top:30px;
}
#current_img{
    border: 0;
    display: inline!important;
    text-align: right;
    font-weight: 700;
    margin-top: 10px;
    color: #18bc43;
    margin-right: 2px;
    font-size: 15px;
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
    .click_button, .count_msg ,.page_count{
    display: none;
  }
}

</style>

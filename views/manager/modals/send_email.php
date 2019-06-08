<?php $this->load->view("partial/header"); ?>
<div class="container">
   
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="inputEmail" placeholder="Enter Email Address">
        </div> 
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Report Type</label>
            <select name="category" id="category1" class="form-control pull-left">
               <option value="">Select Reports Type</option>
               <option value="monthly_sales">Monthly Sales Report</option>
               <option value="delete_bill">Monthly Delete Report</option>
           </select>
        </div> 
    </div>
    <div class= "col-md-12 ">
        <div class="form-group">
            <input type="submit" class="btn btn-info" id="emailReport" value="Send Email"> 
        </div> 
    </div>  
   
</div>
<?php $this->load->view("partial/footer"); ?>

<script>
$(document).ready(function(e){


    $('#emailReport').on('click', function(){
        var url1 = '<?php echo site_url().$controller_name."/monthly_report_csv/?";?>';
        var url2=  '<?php echo site_url("Sales/bill_delete_csv");?>';
        var category=$('#category1').val();

        var sEmail = $('#inputEmail').val();
       
        if (validateEmail(sEmail)) {
           var url3 = '<?php echo site_url("login/send_Email_report/?");?>'+"&email=" + sEmail +"&category=" +category;
            //alert('Nice!! your Email is valid, now you can continue..');
            if (category !=="") {
                if(category=="monthly_sales"){
                    $.ajax({
                        url : url1,
                        async: true,
                        success: function(html){
                            swal("Monthly Report file successfully created");
                        },
                        error: function(request, error){
                            swal("Monthly Report file creation failed");  
                        },       
                    });

                }
                else{
                    $.ajax({
                        url : url2,
                        async: true,
                        success: function(html){
                            swal("Monthly Bill Deleted file successfully created");
                        },
                        error: function(request, error){
                            swal("Monthly Bill Deleted file creation failed");  
                        },       
                    });
                }

                $.ajax({
                url : url3,
                async: true,
                success: function(html){                   
                    swal("Email successfully Send");
                    $('#inputEmail').val("");
                },
                error: function(request, error){
                    swal("Email send failed");  
                },   
            });
                location.reload();
            }
           
            else{
                //swal("", "Select Any One Report", "success");
              swal({
                   title: "",
                   text: 'Select Report Type',
                   icon: "error",
               });      
            } 

        }
        else {
            swal({
                title: "",
                text: 'Incorrect Email Address',
                icon: "error",
            });
        }
});

});
function validateEmail(sEmail) {
            var filter = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
            if (filter.test(sEmail)) {
            return true;
            }
            else {
            return false;
            }   
        }
</script>
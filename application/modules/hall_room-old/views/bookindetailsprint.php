<link type="text/css" href="<?php echo MOD_URL;?>room_reservation/assets/css/table.css">
<div class="card">
   <div class="card-body" id="printArea">
       <style>
   @media print {
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #fff; /* Adjust as per your preference */
            border-top: 1px solid #ccc; /* Adjust as per your preference */
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
    }
</style>
   <div class="row">
       <?php $total_price = number_format($bookingdata->totalamount, 0, '.', '');
       $paid_amount = number_format($bookingdata->paid_amount, 0, '.', '');
     //  echo  $total_price."-".$paid_amount;die();
       ?>
            <div style="position: absolute;top: 20px;right: 20px;color: #28a745; font-size:30px;"><span id="ipaid" style="font-size:30px;" class="color-red"><?php if(isset($total_price)){?>
                <?php if($paid_amount >= $total_price){  echo "<span style='color: green;font-weight:bolder;'>Paid</span>";}else{  echo "<span style='color: red;font-weight:bolder;'>Unpaid</span>";}?>
                <?php } else{echo "<span style='color: red;font-weight:bolder;'>Unpaid</span>";}?></span></div>

            <div class="col-md-12" style="text-align: center;">
                <img src="<?php echo base_url();?><?php echo html_escape(!empty($commominfo->invoice_logo)?$commominfo->invoice_logo: 'assets/img/header-logo.png')?>" style="width:150px;height:150px;" alt="">
                <h5 style="font-weight:bold; margin: 0; font-size: 30px; color: #5b5b5b;"><?php echo html_escape($storeinfo->storename); ?> </h5>
                <p style="color: #9a9a9a; margin: 0; font-size: 20px;"><?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?></p>
                <p style="color: #9a9a9a; margin: 0; font-size: 20px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;.<strong style="color: #5b5b5b;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></p>
                <p style="margin: 0; font-size: 20px;"><strong style="color: #5b5b5b;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></p>
                <h5 style="font-weight:bolder; margin: 0; color: #5b5b5b; font-size: 30px;"><?php echo "INVOICE"; ?></h5>
            </div>
        </div>


        <br>
        <div class="row">
            <div class="col-sm-6">
              <p style="color:#5b5b5b;text-transform:uppercase;font-weight:600;font-size:20px;margin-bottom:10px;"><?php echo ("GUEST INFO") ?></p>
                <address>
                    <p style="color: #000; font-weight: bold; font-size: 20px; margin: 0; color: #5b5b5b;"><?php echo html_escape((!empty($customerinfo->firstname)?$customerinfo->title.' '.$customerinfo->firstname.' '.$customerinfo->lastname:'User Deleted'));?></p>
          
                 <p style="margin: 0; font-size: 20px; color: #5b5b5b;">
                           <?php if (!empty($customerinfo->gst_no) && $customerinfo->gst_no !== null && $customerinfo->gst_no != '') { ?>
                        
                          
                           <?php echo "<span style='font-weight:bold;'>GST NO : </span>".$customerinfo->gst_no ?>
                           </p>
                          
                           <?php } ?>
                    <p style="margin: 0; font-size: 20px; color: #5b5b5b;"><?php echo html_escape(!empty($customerinfo->cust_phone)?$customerinfo->cust_phone:null);?></p>
                    <p style="margin: 0; font-size: 20px; color: #5b5b5b;"><?php echo html_escape(!empty($customerinfo->email)?$customerinfo->email:null);?></p>
                </address>
            </div>
            
            
            <div class="col-sm-6" style="text-align: right; font-size: 20px; color: #5b5b5b;">
               <strong>Bill Date : </strong> <?php echo html_escape($bookingdata->date_time);?><br/>
              
                <strong>Bill No :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo $bookingdata->invoice_no ; ?>
            </div>
          </div>
          <br>







          
            <div class="row">
              <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                    <thead>
    <tr>
        <th class="invp-14">HALL TYPE</th>
        <th class="invp-14"><?php echo display("date"); ?></th>
        <th class="invp-15"><?php echo display("room_rent_details"); ?></th>
    </tr>
</thead>
<tbody>
 
 
                        
                        
                        <tr>
                                <!-- <?php
                                    // $allroomtype="";
                                    // $roomid = explode(",",$bookingdata->roomid);
                                    // $exbed = explode(",", $bookingdata->extrabed);
                                    // $experson = explode(",", $bookingdata->extraperson);
                                    // $exchild = explode(",", $bookingdata->extrachild);

                                    // for ($m = 0; $m < count($exbed); $m++) {
                                    //     $bedcharge += ($bookingdata->bed_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    // }

                                    // for ($m = 0; $m < count($exbed); $m++) {
                                    //     $personcharge += ($bookingdata->person_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    // }

                                    // for ($m = 0; $m < count($exbed); $m++) {
                                    //     $childcharge += ($bookingdata->child_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    // }
                                     


                                    // for($i=0;$i<count($roomid); $i++){
                                    //     $roomtype = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$roomid[$i])->get()->row();
                                    //     $allroomtype .= $roomtype->roomtype.",";
                                    // }
                                ?> -->

                                

                                <td style="width: 180px; color: #5b5b5b; font-size: 20px;">
                                <div> <?php echo html_escape($bookingdata->hall_type) ?></div>
                                </td>
 
                                 <td>
                                <div> <?php echo html_escape($bookingdata->start_date) ?><br>
                                      <?php echo html_escape($bookingdata->end_date) ?>
                                </div>
                                </td>

 


                                <td style="border:1px solid black;">
                                    <table class="table table-bordered table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th class="1nvp-18"><?php echo display("nod") ?></th>
                                        <th class="1nvp-18"><?php echo display("rent") ?></th>
                                        <!-- <th class="1nvp-18"><?php //echo display("tot_amt") ?></th> -->
                                    </tr>
                                </thead>
                                
                                        <tbody>
                                            <?php 
                                                $hallcount = explode(",", $bookingdata->total_room);
                                                for($n=0;$n<count($hallcount);$n++){
                                            ?>
                                                <tr>
                                                    <td style="font-size: 20px; color: #5b5b5b;"><?php echo $n+1; ?></td>
                                                    <td style="font-size: 20px; color: #5b5b5b;"><?php echo html_escape($bookingdata->rent); ?></td>
                                                    <!-- <td style="font-size: 20px; color: #5b5b5b;"><?php //echo html_escape($bookingdata->totalamount); ?></td> -->
                                                     </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>





 






                    <div style="float: left; width: 50%;">
                         
                       
                    <div style='float:left; float: left; width: 70%;'>
                 <?php    if($bookingdata->additional_reason || $bookingdata->additional_remarks){  ?>
           
                             <table border="1" class="additional_table" style="table-layout: fixed; width: 100%; ">
                <thead>
                  <tr style="font-size:18px;">
                      
                       
                        <th style="font-weight:bold;width:300px;" class="res-padding"  ><?php echo ("Additional Charge Reason") ?></th>
                        <th style="font-weight:bold;width:100px;" class="res-allign-padding"  ><?php echo ("Remarks") ?></th>
                    </tr>  
                </thead>
                <tbody>
                    <tr style="font-size:18px;">
                      
                       
                        <td  class="res-padding" id="ad_rsn"><?php  echo $bookingdata->additional_reason; ?></td>
                        <td class="res-allign-padding" id="ad_rmk"><?php  echo $bookingdata->additional_remarks; ?></td>
                    </tr>
                </tbody>
            </table><br/>
            <?php }  ?>
            
                            <table style="table-layout: fixed; width: 100%; border: none;">
                                <tbody>
                                    <tr id="paymentmethod_0">
                                     <?php    if (strpos($paymentdetails->paymenttype, "Payment") !== false) {    $paymentdetails->paymenttype = str_replace("Payment", "", $paymentdetails->paymenttype);  }  ?>
                                        <th class="res-padding" id="pmode_0" style="font-size: 20px; font-weight: bold; color: #5b5b5b;"><strong><?php echo display("payment_mode") ?></strong> <br><span><?php echo $paymentdetails->paymenttype; ?></span></th>
                                        <th class="res-allign-padding" id="pamount_0" style="font-size: 20px; font-weight: bold; color: #5b5b5b;"><strong><?php echo display("amnt") ?></strong> <br> <span><?php echo $paymentdetails->paymentamount; ?></span></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <div style="float: left; width: 50%;">
                        <table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%;font-size: 14px;">
                            <tbody>
                                 <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px;"><?php echo display("sub_total") ?></td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;" width="80">
                                       <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($bookingdata->rent); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px;">CGST </td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata->cgst; ?></td>
                                </tr>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px;">SGST</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata->sgst; ?></td>
                                </tr>
                                <?php if($bookingdata->advance_amount > 0){ ?>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px;">Advance Amount</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;">
                                          <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php  echo $bookingdata->advance_amount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                  
                                       </td>
                                </tr>
                                <?php } ?>
                                <?php if($bookingdata->discountamount > 0){ ?>
                                <tr>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; font-size: 20px; padding-right: 10px;">Discount Amount</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;">
                                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php  echo $bookingdata->discountamount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                  
                                        
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    
                              


 
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px;">Paid Amount </td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;">
                                        
                                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php  echo $bookingdata->paid_amount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                        
                                       </td>
                                </tr>
 
                            <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 20px; padding-right: 10px; font-weight: bold;"><?php echo display("grand_total_inctax") ?></td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 20px;">
                                        
                                    <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php  echo $bookingdata->totalamount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                </td>

 

                                </tr>
                            </tbody>
                        </table>
                    </div>






                    

                  
                  </div>
              </div>
          </div>
          
          
                   <div class="print-footer">
   <p style="text-align:center; font-size: 18px; margin: 3px auto; padding: 3px 0;  border-bottom: 1px solid #c7bcbc;">
        PLEASE RETURN YOUR KEY CARD ON DEPARTURE
    </p>
   <p style="text-align:left; height: 0px; font-size: 18px; margin-top: 10px;">
        I agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or Person indicated Billing Instructions: <strong>DIRECT</strong>
    </p>
    <div style="margin-top: 100px;display: flex; justify-content: space-between;">
        <div> 
            <hr style="font-size: 28px;border-top: 1px solid #000;">
            <span style='font-size: 18px;'><?php echo display("guest_signature") ?></span>
        </div>
        <div>
            <hr style="border-top: 1px solid #000;">
            <span style='font-size: 18px;'><?php echo display("authorized_signature") ?></span>
        </div>
    </div>
</div>

        </div>
   </div>
   <div class="card-footer">
      <button type="button" class="btn btn-info mr-2 print-list"onclick="printContent('printArea')"><span
         class="fa fa-print"></span></button>
   </div>
</div>
<script src="<?php echo MOD_URL;?>room_reservation/assets/js/print.js"></script>

<style type="text/css">
    .SumoSelect {
    width: 100%;
}

.SumoSelect>.CaptionCont>span.placeholder {
    font-style: normal;
}

.color-red {
    color: red;
}

.rtype {
    width: 10%;
}
.tr-background{
    background-color: #f7f7f7;
}
.invp-1{
    max-width: 791px;background: #fff;padding: 48px;margin-right: auto;margin-left: auto;font-size: 14px;color: #5b5b5b;position: relative;font-family: 'Lato', sans-serif;border-image: linear-gradient(90deg,#575a7b,#575a7b 20%,#f9655b 0,#f9655b 40%,#f5c070 0,#f5c070 60%,#6658ea 0,#6658ea 80%,#fcc 0) 1;border-top: 4px solid;
}
.invp-2{
    position: absolute;top: 20px;right: 20px;color: #28a745;font-size:20px;
}
/*.invp-3{
    text-align: center; margin-bottom: 50px;
}*/
.invp-img{
    height: 300px;
}
.invp-4{
    margin:10px 0;color: #000;font-weight:500;font-size: 1.5rem;
}
.invp-5{
    color: #9a9a9a; margin: 0;
}
.invp-6{
    margin: 0;
}
.invp-7{
    display: flex; justify-content: space-between;margin-bottom: 1rem;
}
.invp-8{
    color:#5b5b5b;text-transform:uppercase;font-weight:600;font-size:12px;margin-bottom:10px;
}
.invp-9{
    color: #000;
}
.invp-10{
    color: #000; font-weight: 700;
}
.invp-11{
    text-align: right;
}
.invp-12{
    font-style: normal;
}
.invp-13{
    font-size: 0.781rem;border: 1px solid #000;border-collapse: collapse;color: #212529;width: 100%;margin-bottom: 1rem;
}
.invp-14{
    padding: .3rem;border: 1px solid #000;
}
.invp-15{
    padding: .3rem;border: 1px solid #000;text-align: center
}
.invp-16{
    padding: 0;border: 1px solid #000;
}
.invp-17{
    font-size: 0.781rem;border-collapse: collapse;color: #212529;width: 100%;
}
.invp-18{
    padding: .3rem;
}
.invp-19{
    padding: .3rem;border: 1px solid #000;border-bottom: 0;border-left: 0;
}
.invp-20{
    padding: .3rem;border: 1px solid #000;border-bottom: 0;
}
.invp-21{
    padding: .3rem;border: 1px solid #000;border-bottom: 0;border-right: 0;
}
.invp-22{
    float: left; width: 50%;
}
.invp-23{
    table-layout: fixed; width: 80%;
}
.invp-24{
    width: 100%;font-size: 14px;
}
.invp-25{
    color: #646a6e; line-height: 22px; vertical-align: top; text-align:right;
}
.invp-26{
    color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;
}
.invp-27{
    color: #b0b0b0; line-height: 22px; vertical-align: top; text-align:right;
}
.invp-28{
    color: #000; line-height: 22px; vertical-align: top; text-align:right;
}
.invp-29{
    text-align: center;font-size: large; font-weight: 500; color: #28a745;
}
.invp-30{
    margin-top: 30px;
}
.invp-31{
    color:#000000;text-transform:uppercase;font-weight:600;font-size:12px;margin-bottom:10px;
}
.invp-32{
    padding: 0 0 0 12px;list-style: decimal;margin: invp-6 #5b5b5b;
}
.invp-33{
    margin-bottom: 10px;
}
.invp-34{
    display: flex;justify-content: space-between;margin-top: 5rem;
}
.invp-35{
    color:#000; border-top: 1px solid #000;
}
</style>
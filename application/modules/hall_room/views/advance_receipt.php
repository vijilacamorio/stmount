<link type="text/css" href="<?php echo MOD_URL; ?>room_reservation/assets/css/table.css">
<div class="card">
   <div class="card-body" id="printArea">
      <div class="row">
         <div class="col-md-12" style="text-align: center;">
            <img src="<?php echo base_url(); ?><?php echo html_escape(!empty($commominfo->invoice_logo) ? $commominfo->invoice_logo : 'assets/img/header-logo.png') ?>" style="width:100px;height:100px;" alt="">
            <h5 style="font-weight:bold; margin: 0; font-size: 20px; color: #5b5b5b;"><?php echo html_escape($storeinfo->storename); ?> </h5>
            <p style="color: #9a9a9a; margin: 0; font-size: 14px;"><?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?></p>
            <p style="color: #9a9a9a; margin: 0; font-size: 14px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;<strong style="color: #5b5b5b;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></p>
            <p style="margin: 0; font-size: 14px;"><strong style="color: #5b5b5b;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></p>
            <h5 class="invp-10 mt-4" style="font-weight:bolder; margin: 0; color: #5b5b5b; font-size: 20px; "><u><?php echo "Advance Receipt"; ?></u></h5>
         </div>
      </div>
      <br>
      <div class="row">
         <div class="col-sm-6">
            <address>
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Guest Name : <?php echo html_escape((!empty($customerinfo->firstname) ? $customerinfo->firstname . ' ' . $customerinfo->lastname : 'User Deleted')); ?></p>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Address<span style="padding-left:30px;"> :<span style="font-weight: normal;">  <?php echo html_escape((!empty($customerinfo->address) ? $customerinfo->address  : '')); ?></span></p>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">City<span style="padding-left:65px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->city) ? $customerinfo->city  : '')); ?></span></p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">State<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->state) ? $customerinfo->state  : '')); ?></span></p>
               </div>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Country<span style="padding-left:33px;">:</span><span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->country) ? $customerinfo->country  : '')); ?></span>
                  </p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Zip<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->zipcode) ? $customerinfo->zipcode  : '')); ?></span></p>
               </div>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Company<span style="padding-left:22px;">: </span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->firstname) ? $customerinfo->title . '.' . $customerinfo->firstname . ' ' . $customerinfo->lastname : 'User Deleted')); ?></span></p>
            </address>
         </div>
         <div class="col-sm-2"></div>
         <div class="col-sm-4" style="text-align: left; font-size: 20px; color: #5b5b5b; font-weight:800;">
         <?php
date_default_timezone_set('Asia/Kolkata');
$currentTime = date( 'h:i:s A', time () );
//echo $currentTime;
?>
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Date<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"> <?php echo date('d-m-Y'); ?></span></p>
         <hr style="margin-top:0;" style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Time<span style="padding-left:40px;">:</span>  <span style="font-weight: normal;"><?php  echo $currentTime;  ?></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">Hall Type<span style="padding-left:9px;">:</span>  <span style="font-weight: normal;"><?php echo html_escape((!empty($bookinfo->hall_type) ? $bookinfo->hall_type  : '')); ?></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Recipt No : <span style="font-weight: normal;"><?php echo html_escape((!empty($bookinfo->advance_id) ? $bookinfo->advance_id  : '')); ?></span></p>
         <hr style="margin-top:0;">
        <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Pay Mode :<span style="font-weight: normal;"> <?php  
         $pay_info=str_replace("Advance in ","", $paymentdetails->details);
         echo $pay_info; ?></span> </p>
         </div>
      </div>
      <div class="container">
      <div class="row">
      <div class="col-sm-8">
      <div class="d-flex" style="justify-content: space-between;">
      <h2 style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800"> Advanced Receipt Details</span></h2>
    <?php if(!empty($customerinfo->gst_no)) { ?>  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">GSTIN: <span style="font-weight: normal;"> <?php  echo $customerinfo->gst_no;  ?></span></p>   <?php }  ?>
      </div>
      </div>
      </div>
      </div>
      <hr style="margin-top:0;">
      <div class="row mt-2">
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Date</p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Description</p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">HSN/SAC</p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Amount</p>
         </div>
      </div>
      <div class="row mt-3">
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400 ; "><?php 
            $split=explode(" ",$bookinfo->paydate);

            echo html_escape((!empty($split[0]) ? $split[0]  : '')); ?></p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"><?php echo html_escape((!empty($bookinfo->advance_remarks) ? $bookinfo->advance_remarks  : '')); ?></p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">997212</p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"><?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></p>
         </div>
      </div>
      <div class="" style="justify-content:right; text-align:right;margin-right:10%">
         <!-- <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Sub Total : <span style="font-weight: normal;"> <?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></span></p>
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Net Total : <span style="font-weight: normal;"> <?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></span></p> -->
      </div>
      <br/>
      <div class="row">
         <div style="margin-left:20px;">

         <?php 
$number = (!empty($paymentdetails->paymentamount) ? $paymentdetails->paymentamount  : 0);
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  



?> <br/>
            <h3 style="font-size: 16px;"><?php  if($points){ echo strtoupper($result . "Rupees  " . $points . " Paise"); }else{ echo strtoupper($result . "Rupees"); } ?></h3>
         </div>
      </div>
      <br/>
      <div class="row">
         <div class="col-sm-9">
            <hr>
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Guest Signature</p>
         </div>
         <div class="col-sm-3">
            <hr>
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Cashier/supervisor</p>
         </div>
      </div>
      
      <br>
      <!-- Duplicate Copy -->
    <hr style="border-top: 5px dotted #000;">
     <br>
     <div class="col-md-12" style="text-align: center;">
            <img src="<?php echo base_url(); ?><?php echo html_escape(!empty($commominfo->invoice_logo) ? $commominfo->invoice_logo : 'assets/img/header-logo.png') ?>" style="width:100px;height:100px;" alt="">
            <h5 style="font-weight:bold; margin: 0; font-size: 20px; color: #5b5b5b;"><?php echo html_escape($storeinfo->storename); ?> </h5>
            <p style="color: #9a9a9a; margin: 0; font-size: 14px;"><?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?></p>
            <p style="color: #9a9a9a; margin: 0; font-size: 14px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;<strong style="color: #5b5b5b;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></p>
            <p style="margin: 0; font-size: 14px;"><strong style="color: #5b5b5b;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></p>
            <h5 class="invp-10 mt-4" style="font-weight:bolder; margin: 0; color: #5b5b5b; font-size: 20px; "><u><?php echo "Advance Receipt"; ?></u></h5>
         </div>
     <div class="row">
         <div class="col-sm-6">
            <address>
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Guest Name : <?php echo html_escape((!empty($customerinfo->firstname) ? $customerinfo->title . '.' . $customerinfo->firstname . ' ' . $customerinfo->lastname : 'User Deleted')); ?></p>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Address<span style="padding-left:30px;"> :<span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->address) ? $customerinfo->address  : '')); ?></span></p>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">City<span style="padding-left:65px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->city) ? $customerinfo->city  : '')); ?></span></p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">State<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->state) ? $customerinfo->state  : '')); ?></span></p>
               </div>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">
                     Country
                     <span style="padding-left:33px;">
                        :<span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->country) ? $customerinfo->country  : '')); ?></span>
                  </p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Zip<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->zipcode) ? $customerinfo->zipcode  : '')); ?></span></p>
               </div>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Company<span style="padding-left:25px;">: </span> <span style="font-weight: normal;"> <?php echo html_escape((!empty($customerinfo->firstname) ? $customerinfo->title . '.' . $customerinfo->firstname . ' ' . $customerinfo->lastname : 'User Deleted')); ?></span></p>
            </address>
         </div>
         <div class="col-sm-2"></div>
         <div class="col-sm-4" style="text-align: left; font-size: 20px; color: #5b5b5b; font-weight:800;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Date<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"><?php echo date('d-m-Y'); ?></span></p>
         <hr style="margin-top:0;" style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Time<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"><?php  echo $currentTime;  ?></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">Hall type<span style="padding-left:8px;">:</span> <span style="font-weight: normal;"><?php echo html_escape((!empty($bookinfo->hall_type) ? $bookinfo->hall_type  : '')); ?></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Recipt No  : <span style="font-weight: normal;"><?php echo html_escape((!empty($bookinfo->advance_id) ? $bookinfo->advance_id  : '')); ?></span></p>
         <hr style="margin-top:0;">
      <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Pay Mode :<span style="font-weight: normal;"> <?php  
         $pay_info=str_replace("Advance in ","", $paymentdetails->details);
         echo $pay_info; ?></span> </p>
         </div>
      </div>
      <div class="container">
      <div class="row">
      <div class="col-sm-8">
      <div class="d-flex" style="justify-content: space-between;">
      <h2 style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800"> Advanced Receipt Details</span></h2>
        <?php if(!empty($customerinfo->gst_no)) { ?>  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">GSTIN: <span style="font-weight: normal;"> <?php  echo $customerinfo->gst_no;  ?></span></p>   <?php }  ?>
      </div>
      </div>
      </div>
      </div>
      <hr style="margin-top:0;">
      <div class="row mt-2">
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Date</p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Description</p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">HSN/SAC</p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Amount</p>
         </div>
      </div>
      <div class="row mt-3">
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400 ; "><?php 
            $split=explode(" ",$bookinfo->paydate);

            echo html_escape((!empty($split[0]) ? $split[0]  : '')); ?></p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"><?php echo html_escape((!empty($bookinfo->advance_remarks) ? $bookinfo->advance_remarks  : '')); ?></p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">997212</p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"><?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></p>
         </div>
      </div>   
      <div class="" style="justify-content:right; text-align:right;margin-right:10%">
         <!-- <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Sub Total : <span style="font-weight: normal;"> <?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></span></p>
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Net Total : <span style="font-weight: normal;"> <?php echo html_escape((!empty($paymentdetails->paymentamount) ? '₹'.$paymentdetails->paymentamount  : '')); ?></span></p> -->
      </div>
        <br/>
      <div class="row">
         <div style="margin-left:20px;">
            <?php 
$number = (!empty($paymentdetails->paymentamount) ? $paymentdetails->paymentamount  : 0);
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  



?> <br/>
            <h3 style="font-size: 16px;"><?php  if($points){ echo strtoupper($result . "Rupees  " . $points . " Paise"); }else{ echo strtoupper($result . "Rupees"); } ?></h3>
        
      </div>
      </div>
      <br/>
      <div class="row">
         <div class="col-sm-9">
            <hr>
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Guest Signature</p>
         </div>
         <div class="col-sm-3">
            <hr>
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Cashier/supervisor</p>
         </div>
      </div>
      
      
   </div>
</div>
</div>
<br>
<div class="card-footer">
   <button type="button" class="btn btn-info mr-2 print-list" onclick="printContent('printArea')"><span class="fa fa-print"></span></button>
</div>
</div>


<script src="<?php echo MOD_URL; ?>room_reservation/assets/js/print.js"></script>
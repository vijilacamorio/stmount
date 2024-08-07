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
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Guest Name : <?php echo html_escape((!empty($customerinfo->firstname) ? $customerinfo->title . '.' . $customerinfo->firstname . ' ' . $customerinfo->lastname : 'User Deleted')); ?></p>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Address<span style="padding-left:30px;"> :<span style="font-weight: normal;"> xxx 5th street chennai</span></p>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">City<span style="padding-left:65px;">:</span> <span style="font-weight: normal;"> chennai</span></p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">State<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> TamilNadu</span></p>
               </div>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Country<span style="padding-left:33px;">:</span><span style="font-weight: normal;"> India</span>
                  </p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Zip<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> 600 555</span></p>
               </div>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Company<span style="padding-left:22px;">: </span> <span style="font-weight: normal;"> aaa company arcot chennai</span></p>
            </address>
         </div>
         <div class="col-sm-2"></div>
         <div class="col-sm-4" style="text-align: left; font-size: 20px; color: #5b5b5b; font-weight:800;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Date<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;" style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Time<span style="padding-left:40px;">:</span>  <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">Room No<span style="padding-left:9px;">:</span>  <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Recipt No : <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Pay Mode :<span style="font-weight: normal;"> Credit Card:</span> <span style="font-weight: normal;">7999</span> </p>
         </div>
      </div>
      <div class="container">
      <div class="row">
      <div class="col-sm-8">
      <div class="d-flex" style="justify-content: space-between;">
      <h2 style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800"> Advanced Receipt Details</span></h2>
      <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">GSTIN: <span style="font-weight: normal;"> 55555555555</span></p>
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
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400 ; ">05.06.2024</p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">adv</p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"></p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">72000</p>
         </div>
      </div>
      <div class="" style="justify-content:right; text-align:right;margin-right:10%">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Sub Total : <span style="font-weight: normal;"> 72000</span></p>
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Net Total : <span style="font-weight: normal;"> 72000</span></p>
      </div>
      <div class="row">
         <div style="margin-left:20px;">
            <h3 style="font-size: 16px;">(Ruppes<span>Seventy two thousand</span>)</h3>
         </div>
      </div>
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
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Address<span style="padding-left:30px;"> :<span style="font-weight: normal;"> xxx 5th street chennai</span></p>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">City<span style="padding-left:65px;">:</span> <span style="font-weight: normal;"> chennai</span></p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">State<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> TamilNadu</span></p>
               </div>
               <hr style="margin-top:0;">
               <div class="d-flex" style="justify-content: space-between;">
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">
                     Country
                     <span style="padding-left:33px;">
                        :<span style="font-weight: normal;"> India</span>
                  </p>
                  <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Zip<span style="padding-left:0px;">:</span> <span style="font-weight: normal;"> 600 555</span></p>
               </div>
               <hr style="margin-top:0;">
               <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Company<span style="padding-left:25px;">: </span> <span style="font-weight: normal;"> aaa company arcot chennai</span></p>
            </address>
         </div>
         <div class="col-sm-2"></div>
         <div class="col-sm-4" style="text-align: left; font-size: 20px; color: #5b5b5b; font-weight:800;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Date<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;" style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Time<span style="padding-left:40px;">:</span> <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800; ">Room No<span style="padding-left:8px;">:</span> <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Recipt No  : <span style="font-weight: normal;"></span></p>
         <hr style="margin-top:0;">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Pay Mode :<span style="font-weight: normal;"> Credit Card:</span> <span style="font-weight: normal;">7999</span> </p>
         </div>
      </div>
      <div class="container">
      <div class="row">
      <div class="col-sm-8">
      <div class="d-flex" style="justify-content: space-between;">
      <h2 style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800"> Advanced Receipt Details</span></h2>
      <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">GSTIN: <span style="font-weight: normal;"> 55555555555</span></p>
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
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400 ; ">05.06.2024</p>
         </div>
         <div class="col-sm-3">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">adv</p>
         </div>
         <div class="col-sm-5">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400"></p>
         </div>
         <div class="col-sm-2">
            <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:400">72000</p>
         </div>
      </div>   
      <div class="" style="justify-content:right; text-align:right;margin-right:10%">
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800">Sub Total : <span style="font-weight: normal;"> 72000</span></p>
         <p style="margin: 0; font-size: 16px; color: #5b5b5b; font-weight:800;">Net Total : <span style="font-weight: normal;"> 72000</span></p>
      </div>
      <div class="row">
         <div style="margin-left:20px;">
            <h3 style="font-size: 16px;">(Ruppes<span>Seventy two thousand</span>)</h3>
         </div>
      </div>
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
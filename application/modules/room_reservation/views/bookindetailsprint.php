<link type="text/css" href="<?php echo MOD_URL;?>room_reservation/assets/css/table.css">
<style>
  /*.footer-content {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        border-top: 1px solid #c7bcbc;
        border-bottom: 1px solid #c7bcbc;
    }
    @page {
        margin-bottom: 150px; 
    }*/

    @media print {
    .print-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        font-size: 12px;
    }

    @page {
        margin-bottom: 60px; /* Adjust this value based on your footer height */
    }

    .print-footer-container {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        page-break-before: always;
    }
}

</style>


<script type="text/javascript">

function printDiv(divName) {
    var table = document.getElementById('CountTableRows');
    var tableroom = document.getElementById('roomNODetails');
    var rowCountroomno = tableroom.rows.length;
    console.log(rowCountroomno);
    var rowCount = table.rows.length;
    console.log(rowCount);
    var firstPageRowCount = 15;

    // Hide all rows
    for (var i = 0; i < rowCount; i++) {
        table.rows[i].style.display = 'none';
    }

    // Show the first page rows
    for (var i = 0; i < firstPageRowCount && i < rowCount; i++) {
        table.rows[i].style.display = 'table-row';
    }

    if (rowCount > firstPageRowCount) {
        // Create a new table for the second page
        var secondPageTable = document.createElement('table');
        secondPageTable.className = 'table table-bordered table-striped table-hover';
        
        var headerRow = table.rows[0].cloneNode(true);
        var printableContainer = document.getElementById(divName);
        printableContainer.appendChild(secondPageTable);

        // Clone the header div and append it to the second page
        var headerDiv = document.getElementById('Headerpart');
        if (headerDiv) {
            var clonedHeaderDiv = headerDiv.cloneNode(true);
            secondPageTable.appendChild(clonedHeaderDiv);

            var helloHeading = document.createElement('p');
            helloHeading.innerHTML = '<strong>Bill Date:</strong> <?php echo html_escape($bookinfo->checkoutdate); ?><br><strong>Bill No:</strong> <?php echo $bookinfo->booking_number; ?>';
            helloHeading.style.float = 'right'; 
            secondPageTable.appendChild(helloHeading);

            // Hide the header if the first page has less than 12 rows
            if (rowCount < firstPageRowCount) {
                clonedHeaderDiv.style.display = 'none';
            }
        } else {
            // If headerDiv is not found, create an empty header row
            var emptyHeaderRow = document.createElement('tr');
            secondPageTable.appendChild(emptyHeaderRow);
        }

        secondPageTable.appendChild(headerRow);

        for (var i = firstPageRowCount; i < rowCount; i++) {
            var newRow = secondPageTable.insertRow();
            if (i >= rowCount - 11) {
                for (var j = 0; j < 2; j++) { 
                    var newCell = newRow.insertCell();
                    if (table.rows[i] && table.rows[i].cells[j]) {
                        if (j === 0) {
                            newCell.colSpan = 3;
                            newCell.style.textAlign = 'right';
                        } else if(j === 1){
                            newCell.style.textAlign = 'right';
                        }
                        else if (j === 2) {
                            newCell.style.display = 'none';
                        }
                        newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                    } else {
                        newCell.colSpan = 3;
                    }
                }
            } else {
                for (var j = 0; j < table.rows[i].cells.length; j++) {
                    var newCell = newRow.insertCell();
                    if (table.rows[i] && table.rows[i].cells[j]) {
                        newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                    }
                }
            }
        }
        
        removeEmptyColumns(secondPageTable);
        var footerTable = document.querySelector('.my_table');
        if (footerTable) {
            var clonedFooterTable = footerTable.cloneNode(true);
            printableContainer.appendChild(clonedFooterTable);
        }
        
        
    }

    $('.hide').hide();
    var printContents = document.getElementById(divName).innerHTML;
    $.print(printContents);
}

function removeEmptyColumns(table) {
    var columnCount = table.rows[0].cells.length;
    for (var j = columnCount - 1; j >= 0; j--) {
        var isEmpty = true;
        for (var i = 0; i < table.rows.length; i++) {
            if (table.rows[i].cells[j].innerHTML.trim() !== '') {
                isEmpty = false;
                break;
            }
        }
        if (isEmpty) {
            for (var i = 0; i < table.rows.length; i++) {
                table.rows[i].deleteCell(j);
            }
        }
    }
}
</script>

<div class="card">
   <div class="card-body" id="printArea">
        <div class="row">
            <?php $total_price = number_format($bookinfo->total_price, 0, '.', ''); ?>
            <div style="position: absolute;top: 18px;right: 18px;color: #28a745; font-size:30px;"><span id="ipaid" style="font-size:30px;font-weight:bolder;" class="color-red"><?php if(isset($total_price)){?>
                <?php if($paymentinfo_withadv->paid_amount >= $total_price){  echo "<span style='font-size:30px;font-weight:bolder;color: red;'>Unpaid</span>";}else{  echo "<span style='font-size:30px;font-weight:bolder;color: green;'>Paid</span>";}?>
                <?php } else{echo "<span style='color: red;'>Unpaid</span>";}?></span></div>
            
            <div class="col-md-12" style="text-align: center;" id="Headerpart">
                <img src="<?php echo base_url();?><?php echo html_escape(!empty($commominfo->invoice_logo)?$commominfo->invoice_logo: 'assets/img/header-logo.png')?>" style="width:150px;height:150px;" alt="">
                <h5 style="font-weight:bold; margin: 0; font-size: 22px; color: #5b5b5b;"><?php echo html_escape($storeinfo->storename); ?> </h5>
                <p style="color: #9a9a9a; margin: 0; font-size: 22px;"><?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?></p>
                <p style="color: #9a9a9a; margin: 0; font-size: 22px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;<strong style="color: #5b5b5b;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></p>
                <p style="margin: 0; font-size: 22px;"><strong style="color: #5b5b5b;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></p>
                <h5 class="invp-10"  style="font-weight:bolder; margin: 0; color: #5b5b5b; font-size: 22px;"><?php echo "INVOICE"; ?></h5>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-6">
              <p style="color:#5b5b5b;text-transform:uppercase;font-weight:600;font-size:18px;margin-bottom:10px;"><?php echo ("Guest Name") ?></p>
                <address>
                    <p style="color: #000; font-weight: bold; font-size: 18px; margin: 0; color: #5b5b5b;"><?php echo html_escape((!empty($customerinfo->firstname)?$customerinfo->title.'.'.$customerinfo->firstname.' '.$customerinfo->lastname:'User Deleted'));?></p>
                   <p style="margin: 0; font-size: 18px; color: #5b5b5b;"><?php echo html_escape(!empty($customerinfo->address)?$customerinfo->address:null);?></p>
                    <p style="margin: 0; font-size: 18px; color: #5b5b5b;"><?php echo html_escape(!empty($customerinfo->cust_phone)?$customerinfo->cust_phone:null);?></p>
                    <p style="margin: 0; font-size: 18px; color: #5b5b5b;"><?php echo html_escape(!empty($customerinfo->email)?$customerinfo->email:null);?></p>
                       <p style="margin: 0; font-size: 18px; color: #5b5b5b;font-weight:bold;"><?php echo html_escape(!empty($customerinfo->gst_no)?"GST No : ".$customerinfo->gst_no:null);?></p>
                </address>
            </div>
            <div class="col-sm-2"></div>
          <div class="col-sm-4" style="text-align: left; font-size: 18px; color: #5b5b5b;">
    <strong>Bill Date :</strong> <?php echo html_escape($bookinfo->checkoutdate); ?><br/>
    <strong>Bill No &nbsp;&nbsp;&nbsp;  :</strong> <?php echo $bookinfo->booking_number; ?>
</div>

        </div>
          
            <div class="row">
              <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="checkoutTable">
                        <tr style="font-size: 18px; color: #5b5b5b;">
                            <th><?php echo display("room_no") ?></th>
                            <th style="text-align: center;"><?php echo display("room_rent_details") ?></th>
                        </tr>
                        <tbody>
                            <tr>
                                <?php
                                    $allroomtype="";
                                    $roomid = explode(",",$bookinfo->roomid);
                                    $exbed = explode(",", $bookinfo->extrabed);
                                    $experson = explode(",", $bookinfo->extraperson);
                                    $exchild = explode(",", $bookinfo->extrachild);

                                    for ($m = 0; $m < count($exbed); $m++) {
                                        $bedcharge += ($bookinfo->bed_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    }

                                    for ($m = 0; $m < count($exbed); $m++) {
                                        $personcharge += ($bookinfo->person_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    }

                                    for ($m = 0; $m < count($exbed); $m++) {
                                        $childcharge += ($bookinfo->child_amount * $extra_facility_days[$m]) * $exbed[$m];
                                    }
                                     


                                    for($i=0;$i<count($roomid); $i++){
                                        $roomtype = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$roomid[$i])->get()->row();
                                        $allroomtype .= $roomtype->roomtype.",";
                                    }
                                ?>
                                <td id="roomNODetails" style="width: 180px; color: #5b5b5b; font-size: 18px;"><strong><?php echo html_escape(!empty($bookinfo->room_no)?$bookinfo->room_no:null);?></strong><br><?php echo trim($allroomtype,",");?>
                                    <hr class="my-1">
                                    <div><?php echo display("adults") ?> : <?php echo html_escape($bookinfo->nuofpeople) ?></div>
                                    <div><?php echo "NOD" ?> : <?php 
                                        $start = strtotime($bookinfo->checkindate);
                                        $end = strtotime($bookinfo->checkoutdate);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));
                                        ?>
                                    </div>
                                  
                            
<div class="text-muted"><?php echo "Extra Bed" ?> :  <?php echo array_sum($exbed);  ?></div>
<div class="text-muted"><?php echo "Extra Person" ?> :  <?php echo array_sum($experson);  ?></div>
<div class="text-muted"><?php echo "Extra Child" ?> :  <?php  echo array_sum($exchild); ?></div>
                                </td>
                                <td style="border:1px solid black;">
                                    <table class="table table-bordered table-sm mb-0" id="CountTableRows">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 18px; color: #5b5b5b;"><?php echo $l+1; ?> #</th>
                                                <th style="font-size: 18px; color: #5b5b5b;"><?php echo display("from_date") ?></th>
                                                <th style="font-size: 18px; color: #5b5b5b;"><?php echo display("to_date") ?></th>
                                                <th style="font-size: 18px; color: #5b5b5b;"><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent"); ?><?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $roomcount = explode(",", $bookinfo->room_no);
                                                for($n=0;$n<count($roomcount);$n++){
                                            ?>
                                                <tr>
                                                    <td style="font-size: 18px; color: #5b5b5b;"><?php echo $n+1; ?></td>
                                                    <td style="font-size: 18px; color: #5b5b5b;"><?php echo html_escape($bookinfo->checkindate); ?></td>
                                                    <td style="font-size: 18px; color: #5b5b5b;"><?php echo html_escape($bookinfo->checkoutdate); ?></td>
                                                    <td style="font-size: 18px; color: #5b5b5b;"><?php $ratecount = explode(",", $bookinfo->roomrate);  for($m=0; $m<$ratecount[$n];$m++){echo ($ratecount[$n]); break;} ?>
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
        <div style='float:left; float: left; width: 100%;'>
                <?php if(!empty($bookinfo->additional_reason) || !empty($bookinfo->additional_remarks)){  ?>
           
            <table border="1" class="additional_table" style="table-layout: fixed; width: 100%; ">
                <thead>
                  <tr style="font-size:18px;">
                      
                       
                        <th style="font-weight:bold;width:200px;" class="res-padding"  ><?php echo ("Additional Charge Reason") ?></th>
                        <th style="font-weight:bold;width:100px;" class="res-allign-padding"  ><?php echo ("Remarks") ?></th>
                    </tr>  
                </thead>
                <tbody>
                    <tr style="font-size:18px;">
                      
                       
                        <td  class="res-padding" id="ad_rsn"><?php  echo $bookinfo->additional_reason; ?></td>
                        <td class="res-allign-padding" id="ad_rmk"><?php  echo $bookinfo->additional_remarks; ?></td>
                    </tr>
                </tbody>
            </table><br/>
            <?php }  ?>
            <table style="table-layout: fixed;  border: none;" class="my_table">
<tbody>
    
        <tr id="paymentmethod_<?php echo $key; ?>">
            <td class="res-padding" id="pmode_<?php echo $key; ?>" style="font-size: 18px; font-weight: bold; color: #5b5b5b;">
                <strong><?php echo display("payment_mode") ?></strong> <br>
                <span>
                
                    <?php foreach ($paymentinfo as $key => $payment): 
                    // Replace "payment" with an empty string in $payment->paymenttype
                    $paymentType = str_replace('Payment', '', $payment->paymenttype);
                    echo $paymentType;
                    echo "<br/>";
                    endforeach;
                    ?>
                </span>
            </td>
            <td class="res-allign-padding" id="pamount_<?php echo $key; ?>" style="font-size: 18px; font-weight: bold; color: #5b5b5b;">
                <strong><?php echo display("amnt") ?></strong> <br>
                <span><?php 
                foreach ($paymentinfo as $key => $payment):
                echo $payment->paymentamount; echo "<br/>";
                 endforeach;
                 
                 $bed_sum = 0;
$person_sum = 0;
$child_sum = 0;
foreach ($bookinfo as $key => $value) {

    if (!empty($value) && is_string($value)) {
     
        $sum = array_sum(array_filter(explode(',', $value)));
       
        if ($key === 'bc') {
            $bed_sum += $sum;
        } elseif ($key === 'pc') {
            $person_sum += $sum;
        } elseif ($key === 'cc') {
            $child_sum += $sum;
        }
    }
}
$total_extra=$bed_sum+$person_sum+$child_sum;
                 
                 ?></span>
            </td>
        </tr>

</tbody>
            </table>
        </div>
    </div>
<div style="float: left; width: 50%;">
                    
                        <table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 100%;font-size: 18px;">
                            <tbody>
                                <?php if($bookinfo->discountamount > 0){ ?>
                                <tr>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; font-size: 18px; padding-right: 10px;">Discount Amount</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->discountamount;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                     </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;"><?php echo display("sub_total") ?></td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;" width="80">
                                       <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php $amt_ttl=array_sum($ratecount); echo number_format($amt_ttl- $bookinfo->discountamount, 2, '.', ''); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">CGST Tax 6.00%</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->cgst;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                   </td>
                                </tr>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">SGST Tax 6.00%</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                        <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->sgst;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                       </td>
                                </tr>
                                <?php if($btaxinfo->additional_charges > 0){ ?>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">Additional Charges 18%</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                         <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $btaxinfo->additional_charges+ $bookinfo->additional_cgst+ $bookinfo->additional_sgst;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                       </td>
                                </tr>
                                <?php } ?>
                                     <?php if($total_extra > 0){ ?>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">Extra bed/Person/Child</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                         <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $total_extra;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                       </td>
                                </tr>
                                <?php } ?>
                                    <?php if($bookinfo->advance_amount > 0){ ?>
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">Advance Amount</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                         <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->advance_amount;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                       </td>
                                </tr>
                                <?php } ?>
                                
                                
                            <?php if($bookinfo->paid_amount > 0){ ?>       
                                <tr>
                                     <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px;">Paid Amount</td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                          <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->paid_amount;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>  
                                      </td>
                                </tr>
                                 <?php } ?>
                                 <?php if($bookinfo->paid_amount > 0){ ?>       
                                <tr>
                                    <td style="color: #000; line-height: 22px; vertical-align: top; text-align:right; font-size: 18px; padding-right: 10px; font-weight: bold;"><?php echo ("Total Amount Received") ?></td>
                                    <td style="color: #646a6e; line-height: 30px; vertical-align: top; text-align: right; white-space: nowrap; font-size: 18px;">
                                        <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php $allbpccharge = ($bedcharge+$personcharge+$childcharge); 
                                        $singletax += ($bookinfo->cgst + $bookinfo->sgst);
                                      echo $bookinfo->paid_amount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                    </td>
                                </tr>
                                  <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center;font-size: large; font-weight: 500; color: #28a745;">&nbsp;</div>
                       
                  </div>
              </div>
          </div>
        <div class="footer-content print-footer-container">
            <div class="print-footer">
                <p style="text-align:center; font-size: 18px; margin: 8px auto; padding: 8px 0; border-top: 1px solid #c7bcbc; border-bottom: 1px solid #c7bcbc;">
                    PLEASE RETURN YOUR KEY CARD ON DEPARTURE
                </p>

                <p style="text-align:left; height: 0px; font-size: 18px; margin-top: 10px;">
                    I Agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or Person indicated Billing Instructions: <strong>DIRECT</strong>
                </p>
                <div style="display: flex; justify-content: space-between; margin-top: 8rem;">
                    <div> 
                        <span style="display: block; width: 100%; border-top: 1px solid #000;"></span>
                        <span style="font-size: 18px;"><?php echo display("guest_signature") ?></span>
                    </div>
                    <div>
                        <span style="display: block; width: 100%; border-top: 1px solid #000;"></span>
                        <span style="font-size: 18px;"><?php echo display("authorized_signature") ?></span>
                    </div>
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
<!-- <script src="<?php echo MOD_URL;?>room_reservation/assets/js/print.js"></script> -->

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
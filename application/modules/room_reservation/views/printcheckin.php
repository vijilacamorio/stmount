<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px;  height: 60px; text-align: center; line-height: 20px;font-size: 20px;}
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 90px;text-align: center; line-height: 20px; font-size: 12px;}
    .pagebreak { page-break-after: always; }
    .pagebreak:last-child { page-break-after: never; }
    footer .left {
      text-align: left;
      position: relative;
      top: 20px;
    }

    footer .right {
      text-align: right;
    }
    .header-table{
        margin-top: 80px;
        font-size: 10px !important;
    }
    table {
        font-size: 10px !important;
    }

   .mainTable {
    border-collapse: collapse;
    width: 100%;
}

    .mainTable th, .mainTable td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    .subTable td, th {
      
      font-size: 12px;
    }

</style>
</head>
<body>
<header>
    <?php 
        $logo = base64_encode(file_get_contents(!empty($commominfo->invoice_logo) ? $commominfo->invoice_logo : 'assets/img/header-logo.png'));
        $total_price = number_format($bookinfo->total_price, 0, '.', '');
    ?>
    <div style="position: absolute; top: 18px; right: 18px; color: #28a745; font-size: 30px; text-align: center;">
        <span id="ipaid" style="font-size: 30px; font-weight: bolder;" class="color-red">
            <?php 
            if (isset($total_price)) {
                if ($paymentinfo_withadv->paid_amount >= $total_price) {
                    echo "<span style='font-size: 20px; font-weight: bolder; color: red;'>Unpaid</span>";
                } else {
                    echo "<span style='font-size: 20px; font-weight: bolder; color: green;'>Paid</span>";
                }
            } else {
                echo "<span style='color: red;'>Unpaid</span>";
            }
            ?>
        </span>
    </div>

    <div style="text-align: center;">
        <img src="data:image/png;base64,<?php echo $logo; ?>" alt="Logo" style="width:60px;height:60px;">
    </div>
    <div class="top-heading">
        <h5 style="font-weight:bold; margin: 0; font-size: 14px; color: #000;"><?php echo html_escape($storeinfo->storename); ?> </h5>
        <div style="color: #000; margin: 0; font-size: 14px;">
            <?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?>
            
        </div>
    <div style="color: #000; margin: 0; font-size: 14px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;<strong style="color: #000;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></div>
    <div style="margin: 0; font-size: 14px;"><strong style="color: #000;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></div>
    <h5 class="invp-10"  style="font-weight:bolder; margin: 0; color: #000; font-size: 14px;"><?php echo "INVOICE"; ?></h5>
    </div>
</header>
  <footer>
    <hr />PLEASE RETURN YOUR KEY CARD ON DEPARTURE <br><hr />
    I Agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or
    Person indicated Billing Instructions: DIRECT
    <div class="left">
        Guest Signature
      </div>
      <div class="right">
        Authorized Signature
    </div>
</footer>
<div class="pagebreak header-table">
    <table style="width: 100%;">
      <tr style="font-size: 12px !important;">
         <td>Guest Name</td>
         <td>Bill Date:</td>
         <td><?php echo html_escape($bookinfo->checkoutdate); ?></td>
      </tr>
       <tr style="font-size: 12px !important;">
         <td>
            <?php echo html_escape((!empty($customerinfo->firstname)?$customerinfo->title.'.'.$customerinfo->firstname.' '.$customerinfo->lastname:'User Deleted'));?><br>
         </td>  
         <td>Bill NO:</td>
         <td><?php echo $bookinfo->booking_number; ?></td>
       </tr>
        <tr style="font-size: 12px !important;">
          <td style="min-width: 40px !important; max-width: 100px !important; word-wrap: break-word; word-break: break-all;">
            <?php echo html_escape(!empty($customerinfo->address)?$customerinfo->address:null);?><br>
            <?php echo html_escape(!empty($customerinfo->cust_phone)?$customerinfo->cust_phone:null);?><br>
            <?php echo html_escape(!empty($customerinfo->email)?$customerinfo->email:null);?><br>
            <?php echo html_escape(!empty($customerinfo->gst_no)?"GST No : ".$customerinfo->gst_no:null);?>
          </td>
        </tr>
    </table>
    <?php
        $allroomtype = rtrim($allroomtype, ',');
        $allroomtypeArray = explode(",", $allroomtype);

        $roomcount = explode(",", $bookinfo->room_no);
        $row_count = 0;
        $total_rows = count($roomcount);
        ?>
        <table width="100%" class="mainTable">
            <thead>
              <tr>
                <th><?php echo "S.No" ?></th>
                <th><?php echo "Room No"; ?></th>
                <th><?php echo "Room Type"; ?></th>
                <th><?php echo display("from_date") ?></th>
                <th><?php echo display("to_date") ?></th>
                <th><?php echo display("nod") ?></th>
                <th style='display:none;'><?php echo ("Per Day Rent") ?></th>
                <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent"); ?>
                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                </th>
                <th  style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent")." ".display("discount"); ?>
                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                </th>
                <th style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tax") ?>
                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                </th>
               </tr>
            </thead>
            <tbody>
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

                $allroomtype = rtrim($allroomtype, ',');
                $allroomtypeArray = explode(",", $allroomtype);

                $roomcount = explode(",", $bookinfo->room_no);
           
                foreach ($roomcount as $krow =>$kval) {
                    $row_count++;

                    $tblsta         = 'unclosed';
                    if ($row_count > 1 && ($row_count - 1) % 15 == 0) {
                        $tblsta         = 'closed';
                        echo '</tbody></table></div>'; 
                        echo '<div style="page-break-before: always;">';
                        echo '<table width="100%" class="mainTable" style="margin-top: 100px;">'; 
                        ?>
                        <thead>
                          <tr>
                            <th><?php echo "S.No" ?></th>
                            <th><?php echo "Room No"; ?></th>
                            <th><?php echo "Room Type"; ?></th>
                            <th><?php echo display("from_date") ?></th>
                            <th><?php echo display("to_date") ?></th>
                            <th><?php echo display("nod") ?></th>
                            <th style='display:none;'><?php echo ("Per Day Rent") ?></th>
                            <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent"); ?>
                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                            </th>
                            <th  style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent")." ".display("discount"); ?>
                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                            </th>
                            <th style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tax") ?>
                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                            </th>
                           </tr>
                        </thead>
                        <tbody>
                    <?php }
        ?>
        <tr>
            <td><?php echo $row_count; ?></td>
            <td><?php echo html_escape(!empty($roomcount[$row_count]) ? $roomcount[$row_count] : null); ?></td>
            <td><?php echo html_escape(!empty($allroomtypeArray[$row_count]) ? $allroomtypeArray[$row_count] : null); ?></td>
            <td><?php echo html_escape($bookinfo->checkindate); ?></td>
            <td><?php echo html_escape($bookinfo->checkoutdate); ?></td>
            <td>
                <?php 
                $start = strtotime($bookinfo->checkindate);
                $end = strtotime($bookinfo->checkoutdate);
                $datediff = $end - $start;
                echo $days = ceil($datediff / (60 * 60 * 24)); ?>
            </td>
            <td><?php $ratecount = explode(",", $bookinfo->roomrate);  for($m=0; $m<$ratecount[$row_count];$m++){echo ($ratecount[$row_count]); break;} ?></td>
        </tr>
        <?php } 
          if ($row_count == $total_rows) { ?>
           <tr>
            <td colspan="7" style="text-align: left;"> 
                <?php
                    $adultsArray = explode(",", $bookinfo->nuofpeople);
                    $adultsCount = count($adultsArray);
                ?>
                <?php echo display("adults") ?> : <?php echo html_escape($adultsCount) ?>
                <br>
                <?php if (empty($exbed) && empty($experson) && empty($exchild)) { ?>
                    <p> - </p>
                <?php } else { ?>         
                    <?php if(array_sum($exbed) > 0) { ?>                   
                        <p><?php echo "Ex Bed" ?> :  <?php echo array_sum($exbed); ?></p>
                    <?php } ?>
                    <?php if(array_sum($experson) > 0) { ?>
                        <p><?php echo "Ex Person" ?> :  <?php echo array_sum($experson); ?></p>
                    <?php } ?>
                    <?php if(array_sum($exchild) > 0) { ?>
                        <p><?php echo "Ex Child" ?> :  <?php echo array_sum($exchild); ?></p>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
        <?php }
        ?>
        
        </tbody>
    </table>
    <?php if($tblsta =='closed'){
        echo '</div>';
    } ?>
<table class="subTable" style="margin-right: 10px; width: 30% !important; margin-top: 10px;">
    <tbody>
        <?php if(!empty($bookinfo->additional_reason) || !empty($bookinfo->additional_remarks)){  ?>
        <tr>
            <th style="font-weight:bold;border: none !important;">Additional Charge Reason></th>
            <th style="font-weight:bold;border: none !important;"><?php echo ("Remarks") ?></th>
        </tr>
        <tr style="font-size:18px;">
            <td style="border: none !important; background-color: #fff !important;" id="ad_rsn"><?php  echo $bookinfo->additional_reason; ?></td>
            <td style="border: none !important; background-color: #fff !important;" id="ad_rmk"><?php  echo $bookinfo->additional_remarks; ?></td>
        </tr>
        <?php } ?>

        <tr id="paymentmethod_<?php echo $key; ?>">
            <td style="font-weight:bold; border: none !important;" colspan="1"><?php echo display("payment_mode") ?></td>
            <td style="font-weight:bold; border: none !important;" colspan="1"><?php echo display("amnt") ?></td>
        </tr>
        <tr style="font-size:18px;">
            <td style="border: none !important; background-color: #fff !important;" id="pmode_<?php echo $key; ?>">
                <?php foreach ($paymentinfo as $key => $payment): 
                    $paymentType = str_replace('Payment', '', $payment->paymenttype);
                    echo $paymentType;
                    echo "<br/>";
                    endforeach;
                ?>
            </td>
            <td style="border: none !important; background-color: #fff !important;" id="pamount_<?php echo $key; ?>">
                <?php 
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
                
                ?>
            </td>
        </tr>
    </tbody>
</table>
<table class="subTable" style="width: 70% !important; float: right; position: relative; left: 60; bottom: 45; margin-top:25px;">
<?php 

for($n=0;$n<count($roomcount);$n++){ 
    $alltotal += $total;
    $alltax += $allsingle;
    $allsubtotal +=$subtotal;
    $complementarycharge += $complementaryprice[$n];
    for($m=0; $m<$ratecount[$n];$m++){
        $room_reat += ($extra_facility_days[$m])*($ratecount[$n]);
    }
}

for ($m = 0; $m < count($exbed); $m++) {
    $bedcharge += ($bookingdata[$l]->bedcharge * $extra_facility_days[$m]) * $exbed[$m];
}

for ($m1 = 0; $m1 < count($experson); $m1++) {
    $personcharge += ($bookingdata[$l]->personcharge * $extra_facility_days[$m1]) * $experson[$m1];
}

for ($m2 = 0; $m2 < count($exchild); $m2++) {
    $childcharge += ($bookingdata[$l]->childcharge * $extra_facility_days[$m2]) * $exchild[$m2];
}
if(!empty($bookingdata[$l]->promocode)){
    $pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $bookingdata[$l]->promocode)->get()->row();
    $promocode += $pdiscount->discount;
} 
$allroomrent += $alltotal;
$allroomrentandtax += $allsubtotal;
$allcomplementarycharge += $complementarycharge;
$allbpccharge += ($bedcharge+$personcharge+$childcharge);
$alladvanceamount +=$bookingdata[$l]->advance_amount;
if(!empty($poolbill)){
    for($pc=0; $pc<count($poolbill[$l]); $pc++){
        if($poolbill[$l][$pc]->status==1){$poolbillpaidamt += $poolbill[$l][$pc]->total_amount;}
        else{$poolbillamt += $poolbill[$l][$pc]->total_amount;$singlepid .= $poolbill[$l][$pc]->pbookingid.",";}
    }
}
if(!empty($restaurantbill)){
    for($rb=0; $rb<count($restaurantbill[$l]); $rb++){
        $restbill += $restaurantbill[$l][$rb]->bill_amount;
        $singleorder .= $restaurantbill[$l][$rb]->order_id.",";
    }
}
if(!empty($hallroombill)){
    for($hb=0; $hb<count($hallroombill[$l]); $hb++){
        $hallbill += $hallroombill[$l][$hb]->totalamount;
        $singlehall .= $hallroombill[$l][$hb]->hbid.",";
    }
}
if(!empty($carParkingBill)){
    for($cb=0; $cb<count($carParkingBill[$l]); $cb++){
        $parkingbill += $carParkingBill[$l][$cb]->total_price;
        $singleparking .= $carParkingBill[$l][$cb]->bookParking_id.",";
    }
}
$total_amount_with_extras=$bookingdata[$l]->roomrate+$allbpccharge;
$amt_ttl=array_sum($ratecount);

?>

    <tbody>
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;"><?php echo display("sub_total"); ?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php $amt_ttl=array_sum($ratecount); echo $amt_ttl- $bookinfo->discountamount; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></td>
        </tr>

        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">CGST Tax 6.00%</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->cgst;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">SGST Tax 6.00%</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookinfo->sgst;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></td>
        </tr>

        <tr id="invadc1" hidden>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">Additional CGST (9%)</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;" id="inadcamtcgst"></td>
        </tr>

         <tr id="invadc2" hidden>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">Additional SGST (9%)</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;" id="inadcamtsgst"></td>
        </tr>

        
        <?php if($allbpccharge > 0){ ?>
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;"><?php echo display("complementary_amt") ?>.</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($extrabpc); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></td>
        </tr>
        <?php } ?>

        <?php if($total_extra > 0){ ?>
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">Extra bed/Person/Child</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $total_extra;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></td>
        </tr>
        <?php } ?>

        <?php if($alladvanceamount > 0){ ?>
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">Advance Amount</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;">
               <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($alladvanceamount); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
            </td>
        </tr>
        <?php } ?>
       
        <tr id="paidamounttitle" hidden>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;">Paid Amount</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;" id="paidamount"></td>
        </tr>
        
        <tr>
            <td colspan="5" style="text-align: right; border: none !important; font-weight: bold;"><?php echo ("Total Amount Received") ?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;" id="inpayableamt">
                <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo ($amt_ttl+$allbpccharge+$singletax)-$alladvanceamount-$disamt;   ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
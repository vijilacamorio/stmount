<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/poolprint.css">
<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/checkoutview.css">

<div class="form-group row">
    <style>
 @media print {
            /* Define styles for the print layout */
            .print-footer {
                /*position: fixed;
                bottom: 0;
                width: 680px;
                background-color: #f2f2f2;
                padding: 10px;
                text-align: center;*/

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
                margin-bottom: 60px; 
            }
            .print-footer-container {
                page-break-before: always;
            }

        }
        .invp-7{
            margin-bottom: 0px !important;
        }
    </style>
    <div class="invalid-feedback" id="cmsg" hidden></div>
    <label
        class="col-3 col-sm-3 col-md-3 col-lg-2 col-xl-2 col-form-label text-right font-weight-600"><?php echo display("room_no") ?>
        :</label>
    <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-4">
        <div class="SumoSelect" tabindex="0" role="button" aria-expanded="false">
            <select multiple="multiple" class="testselect2 SumoUnder" tabindex="-1" id="chroomno">
                <?php foreach($checkinrooms as $rooms){ ?>
                <option value="<?php echo html_escape($rooms->bookedid); ?>"
                    <?php if($bookingdata[0]->bookedid==$rooms->bookedid){ echo "Selected";} ?>>
                    <?php echo html_escape($rooms->room_no)." - "; ?> <?php echo html_escape($rooms->firstname); ?>
                </option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-3 col-sm-3 col-sm-3 col-md-3 col-lg-1 col-xl-1">
        <button type="button" disabled onclick="checkoutinfo()" class="btn btn-success"
            id="go"><?php echo display("go") ?></button>
    </div>
</div>
<div id="checkoutdetail">
    <div class="row">
        <div class="col-lg-12 col-xl-6 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("customer_details") ?></h6>
                </div>
                <input type="hidden" id="inv_no" value="<?php  echo $bookingdata[0]->booking_number; ?>"/>
                <div class="card-body" id="custdetails">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="150"><?php echo display("name") ?></th>
                                <td><strong id="inname"><?php echo html_escape($bookingdata[0]->firstname) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("room_no") ?></th>
                                <td><strong><?php echo html_escape($bookingdata[0]->room_no) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("booking_no") ?>.</th>
                                <td><strong
                                        id="inbknumber"><?php echo html_escape($bookingdata[0]->booking_number) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("email_id") ?></th>
                                <td><strong id="inemail"><?php echo html_escape($bookingdata[0]->email) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("mobile_no") ?></th>
                                <td><strong
                                        id="inmobile"><?php echo html_escape($bookingdata[0]->cust_phone) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("address") ?></th>
                                <td><strong><?php echo html_escape($bookingdata[0]->address) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("time_format") ?></th>
                                <td><strong>24hrs</strong></td>
                            </tr>
                            <?php $bookingts = $this->db->select("booking_type,booking_sourse")->from("tbl_booking_type_info")->where("btypeinfoid",$bookingdata[0]->booking_source)->get()->row(); ?>
                            <?php if(!empty($bookingts)){ ?>
                            <tr>
                                <th class="pl-0"><?php echo display("booking_type") ?></th>
                                <td>
                                    <div class="form-floating with-icon">
                                        <input type="text" class="form-control" readonly
                                            placeholder="<?php echo display("bopkig_type") ?>"
                                            value="<?php echo html_escape($bookingts->booking_type) ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("booking_source") ?></th>
                                <td>
                                    <div class="form-floating with-icon">
                                        <input type="text" class="form-control" readonly
                                            placeholder="<?php echo display("booking_source") ?>"
                                            value="<?php echo html_escape($bookingts->booking_sourse) ?>">
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="additional_cgst" id="additional_cgst">
                        <input type="hidden" name="additional_sgst" id="additional_sgst">
        <div class="col-lg-12 col-xl-6 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("set_default_customer") ?></h6>
                </div>
                <div class="card-body">
                    <?php 
                for($j=0; $j<count($bookingdata); $j++){
                ?>
                    <div class="customer-radio custom-control custom-radio pl-0 rounded bg-white border mb-2">
                        <input type="radio" id="custom_radio_<?php echo $j;?>"
                            <?php if(count($bookingdata)>1){ ?>onclick="userselect(<?php echo $j;?>)" <?php } ?>
                            name="customer-radio" class="custom-control-input" <?php if($j==0){echo "checked";} ?>
                            value="<?php echo html_escape($bookingdata[$j]->bookedid)?>">
                        <label class="custom-control-label d-flex align-items-center justify-content-between py-2 pr-3"
                            for="custom_radio_<?php echo $j;?>">
                            <span>
                                <span
                                    class="text-muted fs-12"><?php echo html_escape($bookingdata[$j]->room_no)." - "; ?>
                                    <?php echo html_escape($bookingdata[$j]->booking_number)?></span>
                                <span
                                    class="d-block"><?php echo html_escape($bookingdata[$j]->checkindate)." - "; ?><?php echo html_escape($bookingdata[$j]->checkoutdate)?></span>
                            </span>
                        </label>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-4 d-flex">
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr class="tr-background">
                        <th><?php echo display("room_no") ?></th>
                        <th><?php echo display("date") ?></th>
                        <th class="text-center"><?php echo display("room_rent_details") ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $allroomrent = 0; $allroomrentandtax = 0;
                    $bedcharge = 0; $personcharge = 0; $childcharge = 0; $allbpccharge = 0; $advanceamount = 0;
                    $room_rate_for_all_room=array();
                    $alladvanceamount = 0; $bookedid=""; $poolbillamt = 0; $poolbillpaidamt = 0;
                    $allcomplementarycharge = 0; $restbill = 0; $hallbill=0; $poolid=""; $orderid = "";$hallid ="";$parkingbill=0;$parkingid="";$promocode=0;
                    $total_amount_with_extras=array();
                    for($l=0; $l<count($bookingdata);$l++){

                    ?>
                    <tr>
                        <td style='width:150px;' class="rtype">
                            <strong class="d-block"><?php echo html_escape($bookingdata[$l]->room_no) ?></strong>
                            <?php 
                            $rtype=""; $alltotal = 0; $alltax = 0; $allsubtotal = 0; $singlepid=""; $singleorder="";$singlehall="";$singleparking="";
                            $roomtype = explode(",",$bookingdata[$l]->roomid);
                            for($s=0;$s<count($roomtype);$s++){
                                $sroomtype = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$roomtype[$s])->get()->row();
                                $rtype .= $sroomtype->roomtype.",";
                            }
                            ?>
                            <span><?php echo trim($rtype,","); ?></span>


                            <div class="text-muted"><?php echo display("adults") ?> :
                                <?php echo html_escape($bookingdata[$l]->nuofpeople) ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <?php $indate = html_escape($bookingdata[$l]->checkindate);
                             $cindate = new DateTime($indate);
                             echo $cindate->format('jS M h:i')." to"; ?><br>
                            <?php $outdate = html_escape($bookingdata[$l]->checkoutdate);
                             $coutdate = new DateTime($outdate);
                             echo $coutdate->format('jS M h:i');
                             $issue = html_escape($bookingdata[0]->checkindate);
                             $issuedate = new DateTime($issue);
                             $invissue = $outdate;
                              $bookedid .= $bookingdata[$l]->bookedid.",";
                                    $roomcount = explode(",", $bookingdata[$l]->room_no);
                                    $ratecount = explode(",", $bookingdata[$l]->roomrate);
                                    
                                    $extracheckin = explode(",", $bookingdata[$l]->extracheckin);
                                    $extracheckout = explode(",", $bookingdata[$l]->extracheckout);
                                    
                                    $mergedArray = [];

                                    foreach ($extracheckin as $key => $value) {
                                        $mergedArray[$key] = $value;
                                    }
                                    
                                    foreach ($extracheckout as $key => $value) {
                                        if (isset($mergedArray[$key])) {
                                            $mergedArray[$key] = $value;
                                        } else {
                                            $mergedArray[$key] = $value;
                                        }
                                    }
                                    
                                    
                                    
                                    $complementaryprice = explode(",", $bookingdata[$l]->complementaryprice);
                                    $extra_facility_days = explode(",", $bookingdata[$l]->extra_facility_days);
                                    $exbed = explode(",", $bookingdata[$l]->extrabed);
                                    $room_reat=0;
                                    $experson = explode(",", $bookingdata[$l]->extraperson);
                                    $exchild = explode(",", $bookingdata[$l]->extrachild);
                                    $bedcharge1=explode(",",$bookingdata[$l]->bed_amount);
                                 
                                    $personcharge1=explode(",",$bookingdata[$l]->person_amount);
                                    $childcharge1=explode(",",$bookingdata[$l]->child_amount);
                         

$total_bed_charge = 0;
$total_person_charge = 0;
$total_child_charge = 0;

// Loop through each array and sum up the charges
for ($i = 0; $i < count($bedcharge1); $i++) {
    $total_bed_charge += (int)$bedcharge1[$i];
}

for ($i = 0; $i < count($personcharge1); $i++) {
    $total_person_charge += (int)$personcharge1[$i];
}

for ($i = 0; $i < count($childcharge1); $i++) {
    $total_child_charge += (int)$childcharge1[$i];
}
$extrabpc=$total_bed_charge+$total_person_charge+$total_child_charge;



                            ?>
                            </div>
                            <hr class="my-1">
                            
                           
                            <div class="text-muted"><?php echo "NOD" ?> :
                                <?php 
                                    // $start = strtotime($mergedArray[1]);
                                    // $end = strtotime($mergedArray[0]);
                                    // $datediff = $end - $start;
                                    // echo $days = ceil($datediff / (60 * 60 * 24));
                                    
                                    $start = strtotime($bookingdata[$l]->checkindate);
                                    $end = strtotime($bookingdata[$l]->checkoutdate);
                                    $datediff = $end - $start;
                                    echo $days = ceil($datediff / (60 * 60 * 24));
                                ?>
                            </div>
<div class="text-muted"><?php echo "Extra Bed" ?> :  <?php echo array_sum($exbed);  ?></div>
<div class="text-muted"><?php echo "Extra Person" ?> :  <?php echo array_sum($experson);  ?></div>
<div class="text-muted"><?php echo "Extra Child" ?> :  <?php  echo array_sum($exchild); ?></div>
                            
                        </td>
                        <td>
                            <table class="table table-bordered table-sm mb-0 tr-background">
                                <thead>
                                    <tr>
                                        <th><?php echo $l+1; ?> #</th>
                                        <th><?php echo display("from_date") ?></th>
                                        <th><?php echo display("to_date") ?></th>
                                        <th style='display:none;'><?php echo display("nod") ?></th>
                                        <th  style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent")." ".display("discount"); ?>
                                            <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                        </th>
                                      
                                        <!--<th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("amt_aft_dis") ?>-->
                                        <!--    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>-->
                                        <!--</th>-->
                                        <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tot_rent") ?>
                                            <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                        </th>
                                        <th style='display:none;'><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tax") ?>
                                            <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                        </th>
                                        <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tot_amt") ?>.
                                            <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // print_r($bookingdata); die();
                                   $room_rent_array=array();
                                   $extracheckin_array=array();
                                   $extracheckout_array=array();
                                    // $offer = explode(",", $bookingdata[$l]->discountamount);
                                    $complementarycharge = 0;
                                    for($n=0;$n<count($roomcount);$n++){
                                    ?>
                                    <tr>
                                        <td><?php echo $n+1; ?></td>
                                        <td><?php for($m=0; $m<$extracheckin[$n];$m++){
                                            $extracheckin_array = ($extracheckin[$n]);
                                            echo ($extracheckin[$n]);
                                            break;
                                            } 
                                        ?></td>
                                        <td><?php for($m=0; $m<$extracheckout[$n];$m++){
                                            $extracheckout_array = ($extracheckout[$n]);
                                            echo ($extracheckout[$n]);
                                            break;
                                            } 
                                        ?></td>
                                        <td style='display:none' id="nod"><?php 
                                        $start = strtotime($mergedArray[1]);
                                        $end = strtotime($mergedArray[0]);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));
                                        ?></td>
                                        <td style='display:none;'><?php echo html_escape($bookingdata[$l]->discountamount); ?></td>
                                        <td><?php 

                                         for($m=0; $m<$ratecount[$n];$m++){
                                             $room_rent_array = ($extra_facility_days[$m])*($ratecount[$n]);
                                                echo ($ratecount[$n]);
                                                break;
                                                    
                                            } 
                                            ?>
                                        </td>
                                        <?php $allsingle=0; ?>
                                         <?php
                                             $alltotal += $total;
                                             $alltax += $allsingle;
                                             $allsubtotal +=$subtotal;
                                             $complementarycharge += $complementaryprice[$n];
                                              for($m=0; $m<$ratecount[$n];$m++){
                                                $room_reat += ($extra_facility_days[$m])*($ratecount[$n]);
                                              //  echo $bookingdata[$l]->bedcharge."#".$extra_facility_days[$m]."#".$exbed[$n];
                                                    
                                            }//echo $bedcharge."-";//.$personcharge."-".$childcharge;
                             
                                            }
                                            $bcharge=0;  $pcharge=0;  $ccharge=0;
                                            for ($m = 0; $m < count($exbed); $m++) {
    $bedcharge += ($bookingdata[$l]->bedcharge * $extra_facility_days[$m]) * $exbed[$m];
$bcharge += $bc[$m];
  //  echo $bookingdata[$l]->bedcharge . "#" . $extra_facility_days[$m] . "#" . $exbed[$m] . "<br/>";
}

for ($m1 = 0; $m1 < count($experson); $m1++) {
    $personcharge += ($bookingdata[$l]->personcharge * $extra_facility_days[$m1]) * $experson[$m1];
    $pcharge += $pc[$m];
  //  echo $bookingdata[$l]->personcharge . "#" . $extra_facility_days[$m1] . "#" . $experson[$m1] . "<br/>";
}

for ($m2 = 0; $m2 < count($exchild); $m2++) {
    $childcharge += ($bookingdata[$l]->childcharge * $extra_facility_days[$m2]) * $exchild[$m2];
    $ccharge += $cc[$m];
 //   echo $bookingdata[$l]->childcharge . "#" . $extra_facility_days[$m2] . "#" . $exchild[$m2] . "<br/>";
}
     
                                            if(!empty($bookingdata[$l]->promocode)){
                                                $pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $bookingdata[$l]->promocode)->get()->row();
                                                $promocode += $pdiscount->discount;
                                            } 
                                            $allroomrent += $alltotal;
                                            $allroomrentandtax += $allsubtotal;
                                            $allcomplementarycharge += $complementarycharge;
                                            $allbpccharge = ($extrabpc);
                                        // echo ($bedcharge+$personcharge+$childcharge);
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
                                        <td style="display:none;" id='tax_with_amount'>
                                            <?php 
                                            $singletax=0;
                                            foreach($taxsetting as $tax){ ?>
                                                <?php echo "($tax->taxname".html_escape($tax->rate)."% )"; 
                                                // echo $bookingdata[$l]->roomrate;
                                                // echo "<BR/>";
                                                // echo $allbpccharge;
                                                // echo "(".$tax->rate ."*(".$amt_ttl."+".$allbpccharge."))/100";
                                                 $singletax += ($tax->rate*(($amt_ttl)+$allbpccharge))/100; 
                                                 echo  ($tax->rate*(($amt_ttl)+$allbpccharge))/100;
                                                 ?><br>
                                            <?php } ?>
                                        </td>
                                        <td>
                                         
                                            <?php //echo ($room_reat)+$allbpccharge+$singletax;  ?>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="3"></td>
                                        <td> <?php 
                                          
                                            foreach($taxsetting as $tax){ ?>
                                                <?php echo "($tax->taxname".html_escape($tax->rate)."% )"; 
                                               echo  ($tax->rate*(($amt_ttl)+$allbpccharge))/100;
                                                 ?><br>
                                            <?php } ?>   </td>
                                       <!-- <td id="total_tax"><?php $alltax +=$singletax;  echo html_escape($alltax); ?></td> -->
                                        <td> <?php echo ($amt_ttl)+$allbpccharge+$singletax;  ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php $orderid .= $singleorder.","; $poolid .= $singlepid.","; $hallid .= $singlehall.",";$parkingid .= $singleparking.","; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xl-6 d-flex">
        <div class="card flex-fill w-100 mb-4">
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("billing_details") ?></h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="pl-0" width="180"><?php echo display("room_rent_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="allroomrent"><?php echo $amt_ttl; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0">
                                <?php echo display("discount") ?>
                                <!--<div class="d-flex align-items-center white-space-nowrap mb-1">-->
                                <!--    <input type="number" min="0" disabled id="percent"-->
                                <!--        class="form-control form-control-xs" autocomplete="off">-->
                                <!--    <div class="ml-1 mr-3">(%) (<?php echo display("or") ?>)</div>-->
                                <!--</div>-->
                                <div class="d-flex align-items-center white-space-nowrap">
                                    <input type="number" min="0" disabled id="amount"
                                        class="form-control form-control-xs" autocomplete="off">
                                    <div class="ml-1 mr-3">(<?php echo display("amnt") ?>)</div>
                                </div>
                            </th>
                            <td>
                                <div class="form-floating d-inline-block">
                                    <select class="form-select" id="disreason">
                                        <option value="" selected="">No Discount</option>
                                        <option value="1">Offer</option>
                                        <option value="2">Reduction</option>
                                        <option value="3">MD Guest</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("discount_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="disamount"><?php echo $bookingdata[0]->discountamount ? $bookingdata[0]->discountamount : 0; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <?php if($promocode>0){ ?>
                        <tr>
                            <th class="pl-0"><?php echo display("promocode")." "; ?><?php echo display("discount") ?>.
                            </th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="disamount"><?php echo html_escape($promocode); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <?php } ?>
                       
                        <tr>
                            <th class="pl-0"><?php echo display("total_room_rent_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="totalroomrentamount"><?php
                                    //    print_r($ratecount);
                                       
                                       
                                        
                                       echo $amt_ttl; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <input type='hidden'  name='rentafterdiscount' id="rentafterdiscount"/>
                            <input type="hidden" name="discount_cgst" id="discount_cgst">
    <input type="hidden" name="discount_sgst" id="discount_sgst">
                        <tr>
                            <th style='display:none;' class="pl-0"><?php echo display("total_room_rent_amt_with_tax") ?></th>
                            <td hidden>
                                <strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="oldallroomrentandtax"><?php echo $amt_ttl+$singletax;  ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                            <td style='display:none;'><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="allroomrentandtax"><?php echo $amt_ttl+$singletax;  ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                      
                        <tr>
                            <th class="pl-0"><?php echo display("extra_bpc_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="allbpccharge"><?php echo html_escape($extrabpc); 
                                        
                                        ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                                        
                                   <span style="display:none;" name="bedcharge_rate" id="bedcharge_rate"><?php echo $bedcharge; ?></span> 
                                   <span style="display:none;" name="personcharge_rate" id="personcharge_rate"><?php echo $personcharge; ?></span> 
                                   <span style="display:none;" name="childcharge_rate" id="childcharge_rate"><?php echo $childcharge; ?></span> 
                                    </strong>
                                    
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("advance_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="alladvanceamount"><?php echo html_escape($alladvanceamount); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("payable_rent_amt") ?>.</th>
                            <td><strong>
                                    <?php 
                                    $disamt=$bookingdata[0]->discountamount ? $bookingdata[0]->discountamount : 0;
                                    
                                    if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="payableamount"><?php echo ($amt_ttl+$allbpccharge+$singletax)-$alladvanceamount-$disamt;  ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
          <div class="col-lg-12 col-xl-6 d-flex">
        <div class="card flex-fill w-100 mb-4">
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("additional_charges") ?></h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="pl-0" width="180"><?php echo "Reason"; ?></th>
                             <td>
                                <select class="form-control form-control-sm" id="additional_reason">
                                    <option selected disabled>Select your Reason</option>
                                    <option value="Breakage">Breakage</option>
                                    <option value="Laundry">Laundry</option>
                                    <option value="Transport">Transport</option>
                                </select>
                            </td>
                          
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("additional_charge_comments") ?></th>
                            <td>
                                <textarea class="form-control"
                                    name="additional_remarks" id="additional_remarks" placeholder="<?php echo display("additional_charge_comments") ?>"></textarea>
                            </td>
                        </tr>
                        <tr>
                              <th class="pl-0" width="180"><?php echo display("additional_charges") ?></th>
                            <td>
                                <input type="number" min="0" id="additionalcharge" class="form-control form-control-sm"
                                    value="0" autocomplete="off">
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("payment_details") ?></h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="pl-0" width="180"><?php echo display("net_payable_amt") ?></th>
                            <td><strong>
                                    <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="netpayableamount"><?php echo html_escape(($amt_ttl+$allbpccharge+$singletax)-$alladvanceamount-$disamt) ; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("credit_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="creditamt">0.00</span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("special_discount_amt") ?></th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="complemetaryamt">0.00</span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0"><?php echo display("payable_amt") ?>.</th>
                            <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                        id="payableamt"><?php echo ($amt_ttl+$allbpccharge+$singletax)-$alladvanceamount-$disamt;   ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <button hidden type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#currencyConverterModal">
                        <i class="fas fa-euro-sign"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
        <div class="col-lg-12 col-xl-6 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header py-3" style="display: none;">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("credit") ?></h6>
                </div>
                <div class="card-body" style="display: none;">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="180"><?php echo display("type") ?></th>
                                <td>
                                    <div class="form-floating">
                                        <select class="form-select" id="credit">
                                            <option selected value="">No Credit</option>
                                            <option value="admin">Admin</option>
                                            <option value="regular">Regular Customer</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                    <?php echo display("credit_amt") ?>.<?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td>
                                    <input type="number" min="0" id="creditamount" disabled
                                        class="form-control form-control-sm">
                                    <div class="invalid-feedback" id="crmsg" hidden></div>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("remarks") ?></th>
                                <td>
                                    <textarea class="form-control" placeholder="<?php echo display("remarks") ?>"
                                        id="floatingTextarea"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("bill_settlement") ?></h6>
                </div>
                <div class="card-body" hidden>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="180">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                    <?php echo display("cash") ?>
                                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td><input type="number" id="old_cash" min="0" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                    <?php echo display("card") ?>
                                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td><input type="number" id="card" min="0" class="form-control form-control-sm"></td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                    <?php echo display("cheque") ?>
                                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td><input type="number" id="cheque" min="0" class="form-control form-control-sm"></td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                    <?php echo display("online") ?>
                                    <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td><input type="number" id="online" min="0" class="form-control form-control-sm"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <table class="table table-borderless payment mb-0 ">
                        <thead>
                            <tr>
                                <th class="pl-0"><?php echo display("payment_mode") ?></th>
                                <th><?php echo display("amnt") ?></th>
                                <th class="text-right pr-0"><?php echo display("action") ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border-0 pl-0">
                                    <div class="form-floating with-icon">
                                        <select class="selectpicker form-select" data-live-search="true"
                                            data-width="100%" onchange="paymode(0)" id="paymentmode_0">
                                            <option value="" selected>Choose <?php echo display("payment_mode") ?>
                                            </option>
                                            <?php foreach($paymentdetails as $ptype){ ?>
                                            <option value="<?php echo html_escape($ptype->payment_method) ?>">
                                                <?php echo html_escape($ptype->payment_method) ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="paymentmode"><?php echo display("payment_mode") ?></label>
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="row mt-2" id="modedetails_0" hidden>
                                             <div class="col-md-8 mb-3" id="chequenodiv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Cheque Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="number" disabled class="form-control" id="cheque_no"
                           placeholder="Cheque Number" maxlength="6">
                     </div>
                  </div>
               </div>

               <div class="col-md-4 mb-3" id="chequedatediv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Date" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="date" disabled class="form-control" id="cheque_date"
                           placeholder="dd/mm/yyyy">
                     </div>
                  </div>
               </div>

              <div class="col-md-12 mb-3" id="banknamediv" hidden>
               
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Bank Name" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="bankname"
                           placeholder="Bank Name" maxlength="19"> 
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
               </div>
            
               <div class="col-md-12 mb-3" id="accountnumberdiv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Account Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="accountnumber"
                           placeholder="Account number" maxlength="19"> 
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 mb-3" id="upireferencediv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "UPI Reference Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="upi_referenceno"
                           placeholder="Reference Number">
                     </div>
                  </div>
               </div>
               <div class="col-md-6 mb-3" id="carddiv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Card Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="cardno"
                           placeholder="Card number" maxlength="19"> 
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
               </div>
               <div class="col-md-3 mb-3" id="cardexpirediv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Expiry Date" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="expirydate"
                           placeholder="mm/yyyy">
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
               </div>
               <div class="col-md-3 mb-3" id="cardcvvdiv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "CCV" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="password" disabled class="form-control" id="ccv" placeholder="***" maxlength="3">
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
               </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    
                                </td>
                                <th class="border-0"><input type="number" id="cash_0" disabled class="form-control"
                                        placeholder="Amount" value=""></th>
                                <td class="border-0 pr-0 text-right">
                                    <button type="button" onclick="delrow(0)" id="del0"
                                        class="btn btn-danger-soft del0"><i class="far fa-times-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right py-2">
                        <button type="button" class="btn btn-warning btn-sm" id="multipayment"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-6 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header py-3" style="display: none;">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("special_discount") ?></h6>
                </div>
                <div class="card-body" style="display: none;">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="180"><?php echo display("type") ?></th>
                                <td>
                                    <div class="form-floating">
                                        <select class="form-select" id="complementary">
                                            <option selected value="">No Discount</option>
                                            <option value="mdsfriend">MD's Friend</option>
                                            <option value="friend">Friend</option>
                                            <option value="regular">Regular Customer</option>
                                            <option value="ncorder">NC Order</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?><?php echo display("discount_amt") ?>.<?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                </th>
                                <td><input type="number" min="0" id="complementaryamount" disabled
                                        class="form-control form-control-sm"></td>
                                <div class="invalid-feedback" id="crmsg1" hidden></div>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("remarks") ?></th>
                                <td>
                                    <textarea class="form-control" placeholder="<?php echo display("remarks") ?>"
                                        id="floatingTextarea"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("balance_details") ?></h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="180"><?php echo display("remain_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="balance">0.00</span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("collected_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="collectedamt">0.00</span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("change_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="refunddamt">0.00</span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="d-lg-flex align-items-center justify-content-between">
        <div class="">
            <div class="custom-control custom-checkbox">
                <label class="custom-control-label" for="roomRent" hidden></label>
            </div>
            <small></small>
        </div>
        <div class="mt-3 mt-lg-0">
            <button type="button" onclick="printDiv('printArea')"
                class="btn btn-success btn-lg print-btn"><?php echo display("print") ?></button>

             <!-- <button type="button" id="print_invoice" class="btn btn-success btn-lg print-btn"><?php echo display("print") ?></button> -->
            <input type="hidden" id="bookedid" value="<?php echo trim($bookedid,","); ?>">
            <button type="button" disabled id="checkout" onclick="checkout()"
                class="btn btn-info btn-lg"><?php echo display("checkout") ?></button>
        </div>
    </div>
    <div id="smpooldetails">


    </div>
    <div id="restdetails">


    </div>
    <div id="halldetails">


    </div>
    <div id="parkingdetails">
    </div>
    
    <?php 
     // Bed Amount
     $bedamounts = explode(",", $bookingdata[0]->bed_amount);
     $bedtotal = array_sum($bedamounts);
     
      // Person Amount
     $personamounts = explode(",", $bookingdata[0]->person_amount);
     $persontotal = array_sum($personamounts);
     
      // Child Amount
     $childamounts = explode(",", $bookingdata[0]->child_amount);
     $childtotal = array_sum($childamounts);
    ?>
    
    <input type="hidden" id="orderid" value="<?php echo html_escape(trim($orderid,",,")); ?>">
    <input type="hidden" id="poolid" value="<?php echo html_escape(trim($poolid,",,")); ?>">
    <input type="hidden" id="hallid" value="<?php echo html_escape(trim($hallid,",,")); ?>">
    <input type="hidden" id="parkingid" value="<?php echo html_escape(trim($parkingid,",,")); ?>">

    
    <input type="hidden" id="bed_amount" value="<?php echo isset($bedtotal) ? $bedtotal : 0; ?>">
    <input type="hidden" id="person_amount" value="<?php echo isset($persontotal) ? $persontotal : 0; ?>">
    <input type="hidden" id="child_amount" value="<?php echo isset($childtotal) ? $childtotal : 0; ?>">

    <div id="printArea" hidden>

    
   
    <!--Print button-->
    <div class="invoice-wrap print-content invp-1">
        <div class="invp-2"><span id="ipaid" style='font-weight:bolder;' class="color-red"><?php echo display("unpaid") ?></span></div>
        <div id="Headerpart">
        <div class="invp-3"  style="margin-top:-4px;margin-bottom:40px;">
           <img src="<?php echo base_url($invoicelogo->invoice_logo) ?>" style="width:80px;height:80px;" alt="..." class="invp-img">
            <h5 class="invp-6" style="font-weight:bold;"><?php echo html_escape($setting->title); ?></h5>
            <p class="invp-5"><?php echo display("address") ?>: <?php echo html_escape($setting->address); ?></p>
           <p  class="invp-5"> <strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($setting->phone); ?>.<strong><?php echo "   Email : " ?></strong>  <?php echo html_escape($setting->email); ?><br></p>
            <p  class="invp-6"><strong><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></p>
            <h5 class="invp-10" style="font-weight:bolder;"><?php echo "INVOICE"; ?></h5> 
        </div>

        <div class="invp-7">
            <div class="invp-11" style="text-align:left;">
             
                <p class="invp-8">
                    <?php echo ("Guest Info") ?></p>
                <div>
                    <address class="invp-12">
                        <!-- <strong class="invp-9"><?php //echo display("details_of_the_guest") ?> </strong><br> -->
                      <span class="invp-10"> <?php if($bookingdata[0]->title){ echo html_escape($bookingdata[0]->title)."." ;} ?></span><span class="invp-10"
                            id="invname"></span><span class="invp-10" ><?php  echo " ".$bookingdata[0]->lastname  ; ?></span><br>
                        <span
                            id="invmobile"></span>
                        <br>
                       <span
                            id="invemail"></span>
                    </address>
                </div>
            </div>

                <div>
               <strong>  Bill Date :  </strong> <?php echo html_escape($invissue); ?><br/>
               <strong>  Bill No  &nbsp;&nbsp;  :</strong>  <?php  echo $bookingdata[0]->booking_number  ; ?>
               
            </div>
        </div>
    </div>
        <!-- Order Details -->

       <table class="invp-13 table table-bordered table-sm mb-0" id="checkouttable">
        <thead>
        <?php
        $allroomrent = 0; $allroomrentandtax = 0;
        $bedcharge = 0; $personcharge = 0; $childcharge = 0; $allbpccharge = 0; $advanceamount = 0;
        $room_rate_for_all_room=array();
        $alladvanceamount = 0; $bookedid=""; $poolbillamt = 0; $poolbillpaidamt = 0;
        $allcomplementarycharge = 0; $restbill = 0; $hallbill=0; $poolid=""; $orderid = "";$hallid ="";$parkingbill=0;$parkingid="";$promocode=0;
        $total_amount_with_extras=array();
        for($l=0; $l<count($bookingdata);$l++){
            ?>
            <tr>
                                        <th><?php echo "S.No" ?></th>
                                        <th><?php echo "Room No"; ?></th>
                                        <th><?php echo "Room Type"; ?></th>
                                        <th><?php echo display("from_date") ?></th>
                                        <th><?php echo display("to_date") ?></th>
                                        <th><?php echo display("nod") ?></th>
                                        <th style='display:none;'><?php echo ("Per Day Rent") ?></th>
                                        <th  ><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("rent"); ?>
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
                                    $complementarycharge = 0;
                                    $room_no_string = $bookingdata[$l]->room_no;
                                    $room_no_array = explode(',', $room_no_string);

                                    $rtype=""; $alltotal = 0; $alltax = 0; $allsubtotal = 0; $singlepid=""; $singleorder="";$singlehall="";$singleparking="";
                                    $roomtype = explode(",",$bookingdata[$l]->roomid);
                    
                                    $room_type=array();

                                    for($s=0;$s<count($roomtype);$s++){
                                        $sroomtype = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$roomtype[$s])->get()->row();
                                       $room_type[]=$sroomtype->roomtype;
                                    }
                   
                                for($n=0;$n<count($roomcount);$n++){
                                    ?>
                                    <tr>
                                        <td><?php echo $n+1; ?></td>
                                        <td>
                                            <?php echo $room_no_array[$n]; ?>
                                            
                                        </td>
                                        <td>
                                         <?php echo $room_type[$n]; ?>
                                        </td>
                                        <td><?php echo html_escape($bookingdata[$l]->checkindate); ?></td>
                                       <td><?php echo html_escape($bookingdata[$l]->checkoutdate); ?></td>
                                        <td  id="nod"><?php
                                        $start = strtotime($bookingdata[$l]->checkindate);
                                        $end = strtotime($bookingdata[$l]->checkoutdate);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));
                                        ?></td>
                                    
                                       <td><?php
                                             for($m=0; $m<$ratecount[$n];$m++){
                                             $room_rent_array = ($extra_facility_days[$m])*($ratecount[$n]);
                                                echo ($ratecount[$n]);
                                                break;
                                            }
                                         ?>
                                        </td>
                                        <?php $allsingle=0; ?>
                                        </tr>
                                        <?php
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
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="addcolspan" colspan="7">
                                            <?php echo display("adults") ?> : <?php 
                                            $nuofpeople = 0;
                                                foreach ($bookingdata as $booking) {
                                                    $nuofpeople += $booking->nuofpeople;
                                                }
                                            echo html_escape($nuofpeople);
                                             ?>
                                            <br>
                                          
                                            <?php 

                                                $exbed = explode(",", $bookingdata[$l]->extrabed);
                                                $room_reat=0;
                                                $experson = explode(",", $bookingdata[$l]->extraperson);
                                                $exchild = explode(",", $bookingdata[$l]->extrachild);
                                                $bedcharge1=explode(",",$bookingdata[$l]->bedcharge);
                                                $personcharge1=explode(",",$bookingdata[$l]->personcharge);
                                                $childcharge1=explode(",",$bookingdata[$l]->childcharge);
                                                ?>
                                                <?php if (empty($exbed) && empty($experson) && empty($exchild)){ ?>
                                                    <div> - </div>
                                                <?php } else { ?>         
                                                    <?php if(array_sum($exbed) >0){ ?>                   
                                                        <div ><?php echo "Ex Bed" ?> :  <?php echo array_sum($exbed);  ?></div>
                                                    <?php } ?>
                                                    <?php if(array_sum($experson) >0){ ?>
                                                        <div ><?php echo "Ex Person" ?> :  <?php echo array_sum($experson);  ?></div>
                                                    <?php } ?>
                                                    <?php if(array_sum($exchild) >0){ ?>
                                                        <div ><?php echo "Ex Child" ?> :  <?php  echo array_sum($exchild); ?></div>
                                                    <?php } }?>
                                        </td>
                                    </tr>
                                </tfoot>
                    <?php $orderid .= $singleorder.","; $poolid .= $singlepid.","; $hallid .= $singlehall.",";$parkingid .= $singleparking.","; ?>
                <?php } ?>
            </tbody>
        </table>
<br/>
      <div class="invp-22">
    
            <table border="1" class="additional_table" style="padding:10px;">
                <thead>
                  <tr class="tr-background" style="padding:10px;font-size:15px;">
                      
                       
                        <th style="width:200px;" class="res-padding"  ><?php echo ("Additional Charge Reason") ?></th>
                        <th class="res-allign-padding"  ><?php echo ("Remarks") ?></th>
                    </tr>  
                </thead>
                <tbody>
                    <tr style="padding:10px;font-size:15px;">
                      
                       
                        <th  class="res-padding" id="ad_rsn"></th>
                        <th class="res-allign-padding" id="ad_rmk"></th>
                    </tr>
                </tbody>
            </table>
            
            <table border="1" class="paymentdetails" id="paymentdetails">
                <tbody style="border-color: white;padding-right: 80px;text-align: left;">
                    <tr id="paymentmethod_0" style="border: none;border-color: white;" >
                        <th class="res-padding" id="pmode_0"><?php echo display("payment_mode") ?></th>
                        <th   style="text-align:left;" id="pamount_0"><?php echo display("amnt") ?></th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invp-22" id="checkoutsubtotal">
            <table style='padding:2px;border:none' border="0" cellpadding="0" cellspacing="0" align="center" class="invp-24">
                <tbody>
                                      <tr id="invdis" hidden>
                        <td class=" invp-27">
                            <small id="indistitle"></small>
                        </td>
                        <td class=" invp-27">
                            <small id="indisamt"></small>
                        </td>
                    </tr>
                    <tr style='border:none'>
                       <td style='border:none' class="invp-25">
                           <?php echo display("sub_total") ?>
                       </td>
                       
                       <td style='border:none' class="invp-26" id="sub" width="80">
                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $amt_ttl;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                       </td>
                    </tr>
                      
                  <tr>
                        <td class="invp-25">
                           <?php echo ("CGST");
                                foreach($taxsetting as $tax){ 
                                                if($tax->taxname == 'CGST'){
                                                echo "(".html_escape($tax->rate)."% )"; 
                                             //  echo  ($tax->rate*($amt_ttl+$allbpccharge))/100 ;
                                                break;
                                                }
                                           } 
                            ?>
                        </td>
                    <td class=" invp-26">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class="discount_cgst"><?php echo $bookingdata[0]->cgst; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                    </tr>
                     <tr>
                        <td class="invp-25">
                            <?php echo ("SGST");
                                foreach($taxsetting as $tax){ 
                                                if($tax->taxname == 'SGST'){
                                                echo "(".html_escape($tax->rate)."% )"; 
                                             //  echo  ($tax->rate*($amt_ttl*$days+$allbpccharge))/100 ;
                                              break;   
                                                }
                                           } 
                            ?>
                        </td>
                        <td class=" invp-26">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class="discount_sgst"><?php echo $bookingdata[0]->sgst; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>

                    <?php if($allcomplementarycharge>0) { ?>
                    <tr>
                        <td class=" invp-27">
                            <small><?php echo display("complementary_amt") ?>.</small>
                        </td>
                        <td class=" invp-27">
                            <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($allcomplementarycharge); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($allbpccharge>0) { ?>
                    <tr>
                        <td class=" invp-25">
                           <?php echo display("extra_bpc_amt") ?>.
                        </td>
                        <td class=" invp-26">
                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($extrabpc); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr id="poolamttitle" hidden>
                        <td class=" invp-27">
                            <small><?php echo display("swimming_pool") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="poolamt"></small>
                        </td>
                    </tr>
                    <tr id="restbillamttitle" hidden>
                        <td class=" invp-27">
                            <small><?php echo display("restaurant") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="restbillamt"></small>
                        </td>
                    </tr>
                    <tr id="hallbillamttitle" hidden>
                        <td class=" invp-27">
                            <small><?php echo display("hall_room") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="hallbillamt"></small>
                        </td>
                    </tr>
                    <tr id="parkingbillamttitle" hidden>
                        <td class=" invp-27">
                            <small><?php echo display("car_parking") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="parkingbillamt"></small>
                        </td>
                    </tr>
                 <tr id="invadc" hidden>
                       <td class=" invp-27">
                                <small><?php echo ("Additional Charges 18%"); ?></small>
                            </td>
                            <td class=" invp-25" id="adv_amt">
                             
                            </td>
                    </tr>
                    <tr id="invadc1" hidden>
                        <td class=" invp-27">
                          <?php echo "Additional CGST (9%)" ?>
                        </td>
                        <td class=" invp-27">
                            <small id="inadcamtcgst"></small>
                        </td>
                    </tr>
                    <tr id="invadc2" hidden>
                        <td class=" invp-27">
                          <?php echo "Additional SGST (9%)" ?>
                        </td>
                        <td class=" invp-27">
                            <small id="inadcamtsgst"></small>
                        </td>
                    </tr>

                    <tr id="invcredit" hidden>
                        <td class=" invp-27">
                            <small id="creditreason"></small>
                        </td>
                        <td class=" invp-27">
                            <small id="increditamt"></small>
                        </td>
                    </tr>
                    <tr id="invsdis" hidden>
                        <td class=" invp-27">
                            <small id="invsdistitle"><?php echo display("special_discount") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="insdis"></small>
                        </td>
                    </tr>
                    <?php if($alladvanceamount>0) { ?>
                    <tr>
                        <td class=" invp-25">
                           <?php echo display("advance_amount") ?>
                        </td>
                        <td class=" invp-26">
                           <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($alladvanceamount); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr id="paidamounttitle" hidden>
                        <td class=" invp-27">
                         <?php echo display("paid_amount") ?>
                        </td>
                        <td class=" invp-27">
                            <small id="paidamount"></small>
                        </td>
                    </tr>
                    <tr id="changeamounttitle" hidden>
                        <td class=" invp-27">
                            <small><?php echo display("cng_amount") ?></small>
                        </td>
                        <td class=" invp-27">
                            <small id="changeamount"></small>
                        </td>
                    </tr>
                    <tr>
                        <td class="invp-28">
                            <strong><?php echo ("Total Amount Received") ?></strong>
                        </td>
                        <td class="invp-28">
                            <strong
                                id="inpayableamt"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo ($amt_ttl+$allbpccharge+$singletax)-$alladvanceamount-$disamt;   ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        
            <div class="invp-29">&nbsp;</div>

   <div class="print-footer-container">
        <div class="print-footer">
       
            <p style="text-align:center;height: 0px; font-size: 12px; margin-top: 10px; border-top: 1px solid #c7bcbc;; border-bottom: 1px solid #c7bcbc;;">
                PLEASE RETURN YOUR KEY CARD ON DEPARTURE
            </p>
            <p style="text-align:left;height: 0px; font-size: 12px; margin-top: 10px; border-top: 1px solid #c7bcbc;; border-bottom: 1px solid #c7bcbc;;">
                    I Agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or Person
                    indicated Billing Instructions: <strong>DIRECT</strong>
                                    </p>
                              
                        <div class="invp-34">
                            <div class="invp-35"><?php echo display("guest_signature") ?></div>
                            <div class="invp-35"><?php echo display("authorized_signature") ?></div>
                        </div>
        </div>
    </div>

    </div>        </div>
    <div id="paymentinfo" hidden>
        <option value="" selected>Choose <?php echo display("payment_mode") ?></option>
        <?php foreach($paymentdetails as $ptype){ ?>
        <option value="<?php echo html_escape($ptype->payment_method) ?>">
            <?php echo html_escape($ptype->payment_method) ?></option>
        <?php } ?>
    </div>
    <div id="bankinfo" hidden>
        <option value="" selected>Choose <?php echo display("bank_name") ?></option>
        <?php foreach($banklist as $list){ ?>
        <option value="<?php echo html_escape($list->HeadName); ?>">
            <?php echo html_escape($list->HeadName);?></option>
        <?php } ?>
    </div>
    <input type="hidden" id="finyear" value="<?php echo financial_year(); ?>">
    <script src="<?php echo MOD_URL.$module;?>/assets/js/custom.js"></script>
    <script src="<?php echo MOD_URL.$module;?>/assets/js/checkoutreservation.js"></script>
    
    
    
    
    
    <script>
$("#paymentmode_0").on('change', function(){

var paymentmode = $("#paymentmode_0").find(":selected").val();
// Card Payment
if(paymentmode=="Card Payment"){
 $("#cardno").attr("disabled", false);
 $("#expirydate").attr("disabled", false);
 $("#ccv").attr("disabled", false);
 $("#cardexpirediv").attr("hidden", false);
 $("#cardcvvdiv").attr("hidden", false);
 $("#carddiv").attr("hidden", false);
}else{
 $("#cardno").attr("disabled", true);
 $("#expirydate").attr("disabled", true);
 $("#ccv").attr("disabled", true);
 $("#cardexpirediv").attr("hidden", true);
 $("#cardcvvdiv").attr("hidden", true);
 $("#carddiv").attr("hidden", true);
}

// UPI 
if(paymentmode=="UPI"){
 $("#upi_referenceno").attr("disabled", false);
 $("#upireferencediv").attr("hidden", false);
}else{
 $("#upi_referenceno").attr("disabled", true);
 $("#upireferencediv").attr("hidden", true);
}


// Cheque
if(paymentmode=="Cheque"){
 $("#cheque_no").attr("disabled", false);
 $("#cheque_date").attr("disabled", false);
 $("#chequenodiv").attr("hidden", false);
 $("#chequedatediv").attr("hidden", false);
}else{
 $("#cheque_no").attr("disabled", true);
 $("#cheque_date").attr("disabled", true);
 $("#chequenodiv").attr("hidden", true);
 $("#chequedatediv").attr("hidden", true);
}

    // Online Payment
 if (paymentmode == "Bank Payment") {
    $("#bankname, #accountnumber").prop("disabled", false);
    $("#banknamediv, #accountnumberdiv").show(); // Show bank name and account number divs
} else {
    $("#bankname, #accountnumber").prop("disabled", true);
    $("#banknamediv, #accountnumberdiv").hide(); // Hide bank name and account number divs
}

if(paymentmode){
 $("#advanceamount").attr("disabled", false);
 $("#advanceremarks").attr("disabled", false);
}else{
 $("#advanceamount").attr("disabled", true);
 $("#advanceremarks").attr("disabled", true);
}
});
$(document).ready(function(){
   $('.additional_table').hide(); 
});


// Print Pdf Align Function
function printDiv(divName) {
    var table = document.getElementById('checkouttable');
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
    

    // Create a new table for the second page
    var secondPageTable = document.createElement('table');
    secondPageTable.className = 'table table-bordered table-sm mb-0';
    
    // Clone the header row from the original table
    var headerRow = table.rows[0].cloneNode(true);
    
    // Get the container for the printable content
    var printableContainer = document.getElementById(divName);
    if (printableContainer) {
        printableContainer.appendChild(secondPageTable);
        // Clone the header div and append it to the second page
        var headerDiv = document.getElementById('Headerpart');
        if (headerDiv) {
            var clonedHeaderDiv = headerDiv.cloneNode(true);
            secondPageTable.appendChild(clonedHeaderDiv);

            if (rowCount < firstPageRowCount) {
                clonedHeaderDiv.style.display = 'none';
            }
        } else {
            // If headerDiv is not found, create an empty header row
            var emptyHeaderRow = document.createElement('tr');
            secondPageTable.appendChild(emptyHeaderRow);
        }

        // Append the cloned header row to the second page table
        if (rowCount > firstPageRowCount){
            secondPageTable.appendChild(headerRow);

            // Add rows to the second page table
            for (var i = firstPageRowCount; i < rowCount; i++) {
                var newRow = secondPageTable.insertRow();

                if (i >= rowCount - 7) {
                    for (var j = 0; j < 7; j++) {
                        var newCell = newRow.insertCell();
                        if (table.rows[i] && table.rows[i].cells[j]) {
                            newCell.style.textAlign = 'left';
                            newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                        } else {
                            newCell.innerHTML = ''; // Ensure the cell is not empty
                        }
                    }
                } else {
                    // Copy all cells from the original table row
                    for (var j = 0; j < table.rows[i].cells.length; j++) {
                        var newCell = newRow.insertCell();
                        if (table.rows[i] && table.rows[i].cells[j]) {
                            newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                        } else {
                            newCell.innerHTML = ''; // Ensure the cell is not empty
                        }
                    }
                }
            }
        }

        // Remove empty columns from the second page table
        removeEmptyColumns(secondPageTable);

        // Clone the footer table and append it to the printable container
        var footerTable = document.querySelector('.print-footer');
        if (footerTable) {
            var clonedFooterTable = footerTable.cloneNode(true);
            printableContainer.appendChild(clonedFooterTable);
        }

        // Append checkoutsubtotal table after checkouttable content

        if (rowCount > firstPageRowCount){
            var paymentdetailstable = document.getElementById('paymentdetails');
            var paymentdetailrowCount = paymentdetailstable.rows.length;
            var clonedHeaderDiv = headerDiv.cloneNode(true);
            if (paymentdetailstable) {
                paymentdetailstable.style.fontSize = '14px';
                printableContainer.appendChild(paymentdetailstable);
            }

            var checkoutsubtotal = document.getElementById('checkoutsubtotal');
            if (checkoutsubtotal) {
                checkoutsubtotal.style.float = 'right'; 
                printableContainer.appendChild(checkoutsubtotal);
            }
        }

        } else {
            console.error(`Element with ID "${divName}" not found.`);
        }
var tdElement = document.querySelector('tfoot .addcolspan');

// Check if the element exists
if (tdElement) {
    // Set the colspan attribute
    tdElement.colSpan = 7; // Change 8 to the desired colspan value
}

    // Hide unnecessary elements
    $('.hide').hide();
    
    // Print the content
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
    
    
    
    
    
    
    
    
    
    
    
    
    
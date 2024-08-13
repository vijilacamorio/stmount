<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/poolprint.css">
<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/checkoutview.css">
<style>
 @media print {
            /* Define styles for the print layout */
            .print-footer {
                position: fixed;
                bottom: 0;
                width: 680px;
                background-color: #f2f2f2;
                padding: 10px;
                text-align: center;
            }
        }
    </style>
<div class="form-group row">
    <div class="invalid-feedback" id="cmsg" hidden></div>
    <label
        class="col-3 col-sm-3 col-md-3 col-lg-2 col-xl-2 col-form-label text-right font-weight-600"><?php echo display("room_no") ?>
        :</label>
    <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-4">
        <div class="SumoSelect" tabindex="0" role="button" aria-expanded="false">
            <select multiple="multiple" class="testselect2 SumoUnder" tabindex="-1" id="chroomno">
                <?php foreach($checkinrooms as $room){  ?>
                <option value="<?php echo html_escape($room->hbid); ?>"
                    <?php if($bookingdata[0]->hbid==$room->hbid){ echo "Selected";} ?>>
                    <?php echo html_escape($room->hall_type)." - "; ?> <?php echo html_escape($room->firstname); ?>
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
                <div class="card-body" id="custdetails">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="pl-0" width="150"><?php echo display("name") ?></th>
                                <td><strong id="inname"><?php echo html_escape($bookingdata[0]->firstname) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo "Hall type" ?></th>
                                <td><strong><?php echo html_escape($bookingdata[0]->hall_type) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("booking_no") ?>.</th>
                                <td><strong
                                        id="inbknumber"><?php echo html_escape($bookingdata[0]->invoice_no) ?></strong>
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
        <?php
          function customRound($amount) {
   $amount = str_replace(',', '', $amount);
 $amount = (float) $amount;
 $integerPart = floor($amount);
  $fractionalPart = $amount - $integerPart;
    if ($fractionalPart <= 0.49) {
        $roundedAmount = $integerPart;
    } else {
        $roundedAmount = $integerPart + 1;
    }
 return number_format($roundedAmount, 2, '.', ',');
}
?>
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
                            value="<?php echo html_escape($bookingdata[$j]->hbid)?>">
                        <label class="custom-control-label d-flex align-items-center justify-content-between py-2 pr-3"
                            for="custom_radio_<?php echo $j;?>">
                            <span>
                                <span
                                    class="text-muted fs-12"><?php echo html_escape($bookingdata[$j]->hall_type)." - "; ?>
                                    </span>
                                <span
                                    class="d-block"><?php echo html_escape($bookingdata[$j]->start_date)." - "; ?><?php echo html_escape($bookingdata[$j]->end_date)?></span>
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
                            <th><?php echo "Hall Type" ?></th>
                            <th><?php echo display("date") ?></th>
                            <th class="text-center"><?php echo "Hall Rent Details" ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $allroomrent = 0; $allroomrentandtax = 0;
                    $bedcharge = 0; $personcharge = 0; $childcharge = 0; $allbpccharge = 0; $advanceamount = 0;
                    $alladvanceamount = 0; $bookedid=""; $poolbillamt = 0; $poolbillpaidamt = 0;
                    $allcomplementarycharge = 0; $restbill = 0; $hallbill=0; $poolid=""; $orderid = "";$hallid ="";$parkingbill=0;$parkingid=""; $promocode=0;
                    for($l=0; $l<count($bookingdata);$l++){
                

                    ?>
                        <tr>
                            <td class="rtype">
                                <strong class="d-block"><?php echo html_escape($bookingdata[$l]->hall_type) ?></strong>
                            </td>
                            <td>
                                <div>
                                    <?php $indate = html_escape($bookingdata[$l]->start_date);
                                         $cindate = new DateTime($indate);
                                         echo $cindate->format('jS M h:i')." to"; ?><br>
                                         <?php $outdate = html_escape($bookingdata[$l]->end_date);
                                         $coutdate = new DateTime($outdate);
                                         echo $coutdate->format('jS M h:i');
                                         $issue = html_escape($bookingdata[0]->start_date);
                                         $issuedate = new DateTime($issue);
                                         $invissue = $issuedate->format('l, F j, Y');
                                    ?>
                                </div>
                                
                            </td>
                            <td>
                                <table class="table table-bordered table-sm mb-0 tr-background">
                                    <thead>
                                        <tr>
                                            <th><?php echo display("from_date") ?></th>
                                            <th><?php echo display("to_date") ?></th>
                                            <th><?php echo display("nod") ?></th>
                                            
                                           
                                            <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tot_rent") ?>
                                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                            </th>
                                            <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tax") ?>
                                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                            </th>
                                            <th><?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).") "; } ?><?php echo display("tot_amt") ?>.
                                                <?php if($currency->position==2){ echo "(".html_escape($currency->curr_icon).")"; } ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?php echo html_escape($bookingdata[0]->start_date); ?> <input type="hidden" class="start_date" value="<?php echo html_escape($bookingdata[0]->start_date); ?>"></td>
                                        <td><?php echo html_escape($bookingdata[0]->end_date); ?> <input type="hidden" class="end_date" value="<?php echo html_escape($bookingdata[0]->end_date); ?>"></td>
                                        <td id="nod"><?php 
                                        $start = strtotime($bookingdata[0]->start_date);
                                        $end = strtotime($bookingdata[0]->end_date);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));
                                        ?>  <input type="hidden" class="nod" value="<?php echo $days = ceil($datediff / (60 * 60 * 24)); ?>"></td>
                                        <td id='rnt'><?php echo $bookingdata[0]->rent; ?>
                                        </td>
                                        <?php $allsingle=0; ?>
                                        <td id='tax_with_amount'>
                                            <?php foreach($taxsetting as $tax){ ?>
                                                <?php echo "($tax->taxname".html_escape($tax->rate)."% )"; echo $singletax = $bookingdata[0]->cgst; ?><br>
                                            <?php } ?>
                                            <input type="hidden" class="tax_name" value="<?php echo $bookingdata[0]->cgst + $bookingdata[0]->sgst; ?>">
                                        </td>
                                        <td id="TotalAmount">
                                            <!--<?php echo $subtotal = $bookingdata[$l]->total_price+$allsingle; ?>-->
                                            <?php echo $bookingdata[0]->totalamount;  ?>
                                            <input type="hidden" class="TotalAmount" value="<?php echo $bookingdata[0]->totalamount; ?>">
                                        </td>
                                    </tr> 
                                        <?php
                                             $alltotal += $total;
                                             $alltax += $allsingle;
                                             $allsubtotal +=$subtotal;
                                             $complementarycharge += $complementaryprice[$n];
                                            for($m=0; $m<$exbed[$n];$m++){
                                                $bedcharge += $bookingdata[$l]->bedcharge*$extra_facility_days[$m];
                                            }
                                            for($m1=0; $m1<$experson[$n];$m1++){
                                                $personcharge += $bookingdata[$l]->personcharge*$extra_facility_days[$m1];
                                            }
                                            for($m2=0; $m2<$exchild[$n];$m2++){
                                                $childcharge += ($bookingdata[$l]->personcharge/2)*$extra_facility_days[$m2];
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
                                            echo $alladvanceamount;
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
                                            ?>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td id="total_tax" class="text-right"><?php echo $bookingdata[0]->cgst + $bookingdata[0]->sgst; ?></td>
                                            <td><?php echo $bookingdata[0]->totalamount;  ?></td>
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
                            <!--<tr>-->
                            <!--    <th class="pl-0" width="180"><?php echo display("room_rent_amt") ?>.</th>-->
                            <!--    <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span-->
                            <!--                id="allroomrent"><?php echo $bookingdata[0]->rent; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>-->
                            <!--    </td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                            <!--    <th class="pl-0">-->
                            <!--        <?php echo display("discount_max") ?>-->
                            <!--        <div class="d-flex align-items-center white-space-nowrap mb-1">-->
                            <!--            <input type="number" min="0" disabled id="percent"-->
                            <!--                class="form-control form-control-xs" autocomplete="off">-->
                            <!--            <div class="ml-1 mr-3">(%) (<?php echo display("or") ?>)</div>-->
                            <!--        </div>-->
                            <!--        <div class="d-flex align-items-center white-space-nowrap">-->
                            <!--            <input type="number" min="0" disabled id="amount"-->
                            <!--                class="form-control form-control-xs" autocomplete="off">-->
                            <!--            <div class="ml-1 mr-3">(<?php echo display("amnt") ?>)</div>-->
                            <!--        </div>-->
                            <!--    </th>-->
                            <!--    <td>-->
                            <!--        <div class="form-floating d-inline-block">-->
                            <!--            <select class="form-select" id="disreason">-->
                            <!--                <option value="" selected="">No Discount</option>-->
                            <!--                <option value="1">Offer</option>-->
                            <!--                <option value="2">Reduction</option>-->
                            <!--                <option value="3">MD Guest</option>-->
                            <!--            </select>-->
                            <!--        </div>-->
                            <!--    </td>-->
                            <!--</tr>-->
                            <!--<tr>-->
                            <!--    <th class="pl-0"><?php echo display("discount_amt") ?>.</th>-->
                            <!--    <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span-->
                            <!--                id="disamount"><?php echo $bookingdata[0]->discountamount ? $bookingdata[0]->discountamount : 0; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>-->
                            <!--    </td>-->
                            <!--</tr>-->
                            <?php if($promocode>0){ ?>
                            <tr>
                                <th class="pl-0">
                                    <?php echo display("promocode")." "; ?><?php echo display("discount") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="disamount"><?php echo html_escape($promocode); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            <?php } ?>
                           
                            <tr>
                     <input type='hidden' value="" name='rentafterdiscount' id="rentafterdiscount"/>
                   <input type='hidden' value="" name='discount_cgst' class="discount_cgst"/>
                    <input type='hidden' value="" name='discount_sgst' class="discount_sgst"/>
                                  <th class="pl-0"><?php echo display("total_room_rent_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="totalroomrentamount"><?php echo $bookingdata[0]->rent; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            
                        
                            <tr>
                                <th class="pl-0"><?php echo display("advance_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="alladvanceamount"><?php 
                                            //print_r($advance); die();
                                            if($bookingdata[0]->adv_amt){echo $bookingdata[0]->adv_amt; }else{ echo 0;}?></span></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("payable_rent_amt") ?>.</th>
                                <td><strong>
                                        <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="payableamount"><?php echo ($bookingdata[0]->totalamount) - ($bookingdata[0]->adv_amt); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                                <input type="text" class="form-control"
                                    name="additional_remarks" id="additional_remarks" placeholder="<?php echo display("additional_charge_comments") ?>">
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
                                            id="netpayableamount"><?php echo ($bookingdata[0]->totalamount) - ($bookingdata[0]->adv_amt); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                                            id="payableamt"><?php echo ($bookingdata[0]->totalamount) - ($bookingdata[0]->adv_amt); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                <div class="card-header py-3" style='display:none;'>
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("credit") ?></h6>
                </div>
                <div class="card-body" style='display:none;'>
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
                           placeholder="Bank Name">
                     </div>
                  </div>
               </div>
               <div class="col-md-12 mb-3" id="accountnumberdiv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Account Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="accountnumber"
                           placeholder="Account Number">
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
                <div class="card-header py-3" >
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("special_discount") ?></h6>
                </div>
                <div class="card-body">
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
            <button type="button" id="print_invoice_all"
                class="btn btn-success btn-lg print-btn"><?php echo display("print") ?></button>
            <input type="hidden" id="hbid" value="<?php echo $bookingdata[0]->hbid; ?>">
            <input type="hidden" id="h_type" value="<?php echo $bookingdata[0]->h_type; ?>">
            <input type="hidden" id="BOOKING_ID" value="<?php echo $bookingdata[0]->invoice_no; ?>">
            <button type="button" disabled id="checkout_hall" onclick="checkout_hall()"
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

    <div id="printArea" hidden>
        <!--Print button-->
        <div class="invoice-wrap print-content invp-1">
            <div class="invp-3">
                <img src="<?php echo base_url($invoicelogo->invoice_logo) ?>" style="width:80px;height:80px;" alt="..." class="invp-img">
               <h5 class="invp-6" style="font-weight:bold;">
                <?php echo html_escape($setting->title); ?></h5>
            <p class="invp-5"><?php echo display("address") ?>: <?php echo html_escape($setting->address); ?></p>
          <p  class="invp-5"> <strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($setting->phone); ?><strong><?php echo "   Email : " ?></strong>  <?php echo html_escape($setting->email); ?><br></p>
            <p  class="invp-6"><strong><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></p></strong>
             <h5 class="invp-6" style="font-weight:bold;">
                <?php echo "INVOICE"; ?></h5>
        </div>

        <div class="invp-7">
            
          
        
            <div class="invp-11" style="text-align:left;">
             
                <p class="invp-8">
                      <?php echo ("Guest Name") ?></p>
                <div>
                    <address class="invp-12">
                        <!-- <strong class="invp-9"><?php //echo display("details_of_the_guest") ?> </strong><br> -->
                        <span class="invp-10"
                            id="invname"></span><br>
                        <span
                            id="invmobile"></span>
                        <br>
                       <span
                            id="invemail"></span>
                    </address>
                </div>
            </div>

                <div>
                     <strong>   Bill Date :  </strong> <?php echo html_escape($invissue); ?><br/>
               <strong>   Bill No :</strong>  <?php  echo $bookingdata[0]->invoice_no ; ?>
               
            </div>
        </div>
        <!-- Order Details -->
            <table class="invp-13">
                <thead>
                    <tr>
                        <th class="invp-14"><?php echo ("HALL") ?></th>
                        <th class="invp-14"><?php echo display("date") ?></th>
                        <th class="invp-15"><?php echo display("room_rent_details") ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="invp-14">
                            <strong class="d-block"><?php echo $bookingdata[0]->hall_type; ?></strong>
                        </td>
                      
                         <td class="invp-14">
                                <div>
                                    <?php $indate = html_escape($bookingdata[0]->start_date);
                                         $cindate = new DateTime($indate);
                                         echo $cindate->format('jS M h:i')." to"; ?><br>
                                         <?php $outdate = html_escape($bookingdata[0]->end_date);
                                         $coutdate = new DateTime($outdate);
                                         echo $coutdate->format('jS M h:i');
                                         $issue = html_escape($bookingdata[0]->start_date);
                                         $issuedate = new DateTime($issue);
                                         $invissue = $issuedate->format('l, F j, Y');
                                    ?>
                                </div>
                                
                            </td>
                        <td class="invp-16">
                            <table class="invp-17">
                                <thead>
                                    <tr>
                                        <th class="1nvp-18"><?php echo display("nod") ?></th>
                                        <th class="1nvp-18"><?php echo display("rent") ?></th>
                                      
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!--<td class="invp-19">-->
                                        <!--    -->
                                        <!--    $instart = strtotime($bookingdata[0]->start_date);-->
                                        <!--    $inend = strtotime($bookingdata[0]->end_date);-->
                                        <!--    $indatediff = $inend - $instart;-->
                                        <!--    echo $indays = ceil($indatediff / (60 * 60 * 24));-->
                                        <!--    ?>-->
                                        <!--</td>-->
                                          <td class="invp-19" id="nod"><?php 
                                        $start = strtotime($bookingdata[0]->start_date);
                                        $end = strtotime($bookingdata[0]->end_date);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));
                                        ?>  <input type="hidden" class="nod" value="<?php echo $days = ceil($datediff / (60 * 60 * 24)); ?>"></td>
                                         <td id="t_rent" class="invp-19">
                                            <?php echo $bookingdata[0]->rent; ?>
                                        </td>
                                       
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- /Order Details -->
            <!-- Table Total -->
            <div class="invp-22">
                <table border="1" class="additional_table" style="padding:5px;">
                <thead>
                  <tr style="font-size:13px;">
                      
                       
                        <th style="width:180px;" class="res-padding"  ><?php echo ("Additional Charge Reason") ?></th>
                        <th class="res-allign-padding"  ><?php echo ("Remarks") ?></th>
                    </tr>  
                </thead>
                <tbody>
                    <tr style="font-size:13px;">
                      
                       
                        <td  class="res-padding" id="ad_rsn"></td>
                        <td class="res-allign-padding" id="ad_rmk"></td>
                    </tr>
                </tbody>
            </table>
            <br/>    
    <table border="1px" class="paymentdetails invp-23">
        <tbody>
            <tr id="paymentmethod_0">
                <th class="res-padding" id="pmode_0"><?php echo display("payment_mode") ?></th>
                <th class="res-allign-padding" id="pamount_0"><?php echo display("amnt") ?></th>
            </tr>
        </tbody>
    </table>

            </div>
            <div class="invp-22">
                <table border="0" cellpadding="0" cellspacing="0" align="center" class="invp-24">
                    <tbody>
                       
                        <tr>
                            <td class="invp-25">
                                <small><?php echo "CGST" ?></small>
                            </td>
                            <td class=" invp-25">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class="discount_cgst"><?php echo number_format($bookingdata[0]->cgst, 2, '.', ','); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="invp-25">
                                <small><?php echo "SGST" ?></small>
                            </td>
                            <td class=" invp-25">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class="discount_sgst"><?php echo number_format($bookingdata[0]->sgst, 2, '.', ','); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                         <tr>
                            <td class="invp-25">
                                <?php echo display("sub_total") ?>
                            </td>
                            <td class="invp-26" width="80">
                                <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class="sub"> <?php echo number_format($bookingdata[0]->rent+$bookingdata[0]->sgst+$bookingdata[0]->cgst, 2, '.', ','); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                            </td>
                        </tr>
                        <!--<tr>-->
                        <!--    <td class="invp-27">-->
                        <!--        <small><?php echo "Total Tax" ?></small>-->
                        <!--    </td>-->
                        <!--    <td class=" invp-27">-->
                        <!--        <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata[0]->cgst + $bookingdata[0]->sgst; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>-->
                        <!--    </td>-->
                        <!--</tr>-->
                        
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
                            <td class=" invp-27">
                                <small><?php echo display("extra_bpc_amt") ?>.</small>
                            </td>
                            <td class=" invp-27">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($allbpccharge); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
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
                            <td class=" invp-25">
                                <small><?php echo ("Additional Charges 18%"); ?></small>
                            </td>
                            <td class=" invp-25" id="adv_amt">
                             
                            </td>
                        </tr>
                        <tr id="invdis" hidden>
                            <td class=" invp-27">
                                <small id="indistitle"></small>
                            </td>
                            <td class=" invp-27">
                                <small id="indisamt"></small>
                            </td>
                        </tr>
                        <tr id="invcredit">
                            <td class=" invp-25">
                                <small id="creditreason"></small>
                            </td>
                            <td class=" invp-25">
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
                        <?php if($bookingdata[0]->adv_amt > 0) { ?>
                        <tr>
                            <td class=" invp-25">
                                <small><?php echo display("advance_amount") ?></small>
                            </td>
                            <td class=" invp-25">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span class='adv'><?php echo number_format($bookingdata[0]->adv_amt, 2, '.', ''); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr id="paidamounttitle">
                            <td class=" invp-25">
                                <small><?php echo display("paid_amount") ?></small>
                            </td>
                            <td class=" invp-25">
                                <small id="paidamount"></small>
                            </td>
                        </tr>
                        <tr id="changeamounttitle">
                            <td class=" invp-25">
                                <small><?php echo display("cng_amount") ?></small>
                            </td>
                            <td class=" invp-25">
                                <small id="changeamount"></small>
                            </td>
                        </tr>
                        <tr>
                            <td class="invp-28">
                                <strong><?php echo ("Total Amount Received") ?></strong>
                            </td>
                            <td class="invp-28">
                                <strong
                                    id="inpayableamt"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php   echo customRound($bookingdata[0]->rent+$bookingdata[0]->sgst+$bookingdata[0]->cgst); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="invp-29">&nbsp;</div>
   
     <div class="print-footer">
    
<p style="text-align:center;height: 0px; font-size: 12px; margin-top: 10px; border-top: 1px solid #c7bcbc;; border-bottom: 1px solid #c7bcbc;;">
    PLEASE RETURN YOUR KEY CARD ON DEPARTURE
</p>
<p style="text-align:left;height: 0px; font-size: 12px; margin-top: 10px; border-top: 1px solid #c7bcbc;; border-bottom: 1px solid #c7bcbc;;">
        I Agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or Person indicated Billing Instructions: <strong>DIRECT</strong>
                        </p>
                 <br/>
            <div class="invp-34">
                <div class="invp-35"><?php echo display("guest_signature") ?></div>
                <div class="invp-35"><?php echo display("authorized_signature") ?></div>
            </div>
            <!--/Signatory-->
        </div>
    </div>  </div> 
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

    if(paymentmode=="Bank Payment"){
            $("#bankname").attr("disabled", false);
            $("#accountnumber").attr("disabled", false);
             $("#accountnumberdiv").attr("hidden", false);
            $("#banknamediv").attr("hidden", false);
        }else{
        $("#bankname").attr("disabled", true);
            $("#accountnumber").attr("disabled", true);
             $("#accountnumberdiv").attr("hidden", true);
            $("#banknamediv").attr("hidden", true);
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
//if (amount > 0 && amount <= roomrent) {
      var roomrent = parseFloat($("#t_rent").text());
      var allbpccharge = parseFloat($("#allbpccharge").text());
    var amount = parseFloat($("#complementaryamount").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
    var additionalcharge = parseFloat($("#additionalcharge").val());
    // var cg=<?php echo  $bookingdata[0]->cgst; ?>;
    // var sg=<?php echo  $bookingdata[0]->cgst; ?>;
    if (isNaN(amount)) {
        amount = 0;
    }
    if (isNaN(additionalcharge)) {
        additionalcharge = 0;
    }
   var rentafterdiscount1 = (roomrent - amount);

if (!isNaN(rentafterdiscount1)) {
    $('#rentafterdiscount').val(rentafterdiscount1);
}
debugger;
 $("#totalroomrentamount").text(parseFloat(rentafterdiscount1));
    var totalrentandtax = (rentafterdiscount1);
    var tax12percent = (totalrentandtax * 18) / 100;
    var cgst = (totalrentandtax * 9) / 100;
     var sgst = (totalrentandtax * 9) / 100;
   
     $('.discount_cgst').text(cgst);
        $('.discount_sgst').text(sgst);
      $('.discount_cgst').val(cgst);
        $('.discount_sgst').val(sgst);
    var payableamount = totalrentandtax - alladvanceamount ;
    // var payableamt = allbpccharge + poolbill + restbill + hallbill + parkingbill - alladvanceamount + roomrentandtax - amount +
    //     additionalcharge -
    //     creditamount;
         var payableamt = payableamount +  additionalcharge;
       var rentandextras=(roomrent);
    if (amount > 0 && amount <= roomrent) {
       //   $("#percent").val((amount * 100) / roomrent);
        $("#disamount").text(amount);
       // $("#amount").val((roomrent * percent) / 100);
        //$("#disamount").text((roomrent * percent) / 100);
       var val=parseFloat(rentafterdiscount1)+additionalcharge+parseFloat(tax12percent);
        $("#allroomrentandtax").text(val.toFixed(2));
        $("#payableamount").text(val.toFixed(2));
        $("#netpayableamount").text(val.toFixed(2));
        $("#payableamt").text(val.toFixed(2));

var rentafterdiscount1 = roomrent - amount;

if (!isNaN(rentafterdiscount1)) {
    $('#rentafterdiscount').val(rentafterdiscount1);
}
 $("#totalroomrentamount").text(parseFloat(rentafterdiscount1));
      //  $("#payableamt").text(val.toFixed(2));
        $("#balance").text(val.toFixed(2));
        $("#cash_0").trigger('change');
           $('.sub').text((rentafterdiscount1+cgst+sgst).toFixed(2));
            var advValue = parseFloat($('.adv').text());
            
    }else{
  $('.sub').text((parseFloat($('#rnt').text())+cgst+sgst).toFixed(2));

    }

//}

});
$(document).ready(function(){
   $('.additional_table').hide(); 
});
</script>
    
    <input type="hidden" id="finyear" value="<?php echo financial_year(); ?>">
    <script src="<?php echo MOD_URL.$module;?>/assets/js/custom.js"></script>
    <script src="<?php echo MOD_URL.$module;?>/assets/js/checkoutreservation.js"></script>
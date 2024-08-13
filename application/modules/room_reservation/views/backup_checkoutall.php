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
                                <td><strong id="inname"><?php echo html_escape($bookingdata->firstname) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo "Hall type" ?></th>
                                <td><strong><?php echo html_escape($bookingdata->hall_type) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("booking_no") ?>.</th>
                                <td><strong
                                        id="inbknumber"><?php echo html_escape($bookingdata->invoice_no) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("email_id") ?></th>
                                <td><strong id="inemail"><?php echo html_escape($bookingdata->email) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("mobile_no") ?></th>
                                <td><strong
                                        id="inmobile"><?php echo html_escape($bookingdata->cust_phone) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("address") ?></th>
                                <td><strong><?php echo html_escape($bookingdata->address) ?></strong></td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("time_format") ?></th>
                                <td><strong>24hrs</strong></td>
                            </tr>
                            <?php $bookingts = $this->db->select("booking_type,booking_sourse")->from("tbl_booking_type_info")->where("btypeinfoid",$bookingdata->booking_source)->get()->row(); ?>
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
        <div class="col-lg-12 col-xl-6 d-flex">
            <div class="card flex-fill w-100 mb-4">
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("") ?></h6>
                </div>
                <div class="card-body">
                    <div class="customer-radio custom-control custom-radio pl-0 rounded bg-white border mb-2">
                        <input type="radio" id="custom_radio"
                            <?php if(count($bookingdata)>1){ ?>onclick="userselect()" <?php } ?>
                            name="customer-radio" class="custom-control-input" <?php if($j==0){echo "checked";} ?>
                            value="<?php echo html_escape($bookingdata->hbid)?>">
                        <label class="custom-control-label d-flex align-items-center justify-content-between py-2 pr-3"
                            for="custom_radio_<?php echo $j;?>">
                            <span>
                                <span
                                    class="text-muted fs-12"><?php echo html_escape($bookingdata->hall_type)." - "; ?>
                                    </span>
                                <span
                                    class="d-block"><?php echo html_escape($bookingdata->start_date)." - "; ?><?php echo html_escape($bookingdata->end_date)?></span>
                            </span>
                        </label>
                    </div>
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
                    <tr>
                        <td class="rtype">
                            <strong class="d-block"><?php echo html_escape($bookingdata->hall_type) ?></strong>
                        </td>
                        <td>
                            <div>
                                <?php $indate = html_escape($bookingdata->start_date);
                             $cindate = new DateTime($indate);
                             echo $cindate->format('jS M h:i')." to"; ?><br>
                                <?php $outdate = html_escape($bookingdata->end_date);
                             $coutdate = new DateTime($outdate);
                             echo $coutdate->format('jS M h:i');
                             $issue = html_escape($bookingdata->start_date);
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
                                        <td><?php echo html_escape($bookingdata->start_date); ?></td>
                                        <td><?php echo html_escape($bookingdata->end_date); ?></td>
                                        <td id="nod"><?php 
                                        $start = strtotime($bookingdata->start_date);
                                        $end = strtotime($bookingdata->end_date);
                                        $datediff = $end - $start;
                                        echo $days = ceil($datediff / (60 * 60 * 24));

                                        ?></td>
                                        <td><?php echo ($bookingdata->rent); ?>
                                        </td>
                                        <?php $allsingle=0; ?>
                                        <td id='tax_with_amount'>
                                            <?php echo $bookingdata->cgst + $bookingdata->sgst; ?>
                                        </td>
                                        <td>
                                           <?php echo ($bookingdata->rent)+$bookingdata->cgst + $bookingdata->sgst;  ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td id="total_tax" class="text-right"><?php echo $bookingdata->cgst + $bookingdata->sgst; ?></td>
                                            <td><?php echo ($bookingdata->rent)+$bookingdata->cgst + $bookingdata->sgst;  ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
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
                                            id="allroomrent"><?php echo ($bookingdata->rent); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0">
                                    <?php echo display("discount_max") ?>
                                    <div class="d-flex align-items-center white-space-nowrap mb-1">
                                        <input type="number" min="0" disabled id="percent"
                                            class="form-control form-control-xs" autocomplete="off">
                                        <div class="ml-1 mr-3">(%) (<?php echo display("or") ?>)</div>
                                    </div>
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
                                            id="disamount"><?php echo $bookingdata->discountamount ? $bookingdata->discountamount : 0; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
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
                                <th class="pl-0"><?php echo display("total_room_rent_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="totalroomrentamount"><?php echo $allroomrent += ($bookingdata->rent); ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            
                        
                            <tr>
                                <th class="pl-0"><?php echo display("advance_amt") ?>.</th>
                                <td><strong><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="alladvanceamount"><?php echo $bookingdata->paid_amount; ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("payable_rent_amt") ?>.</th>
                                <td><strong>
                                        <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><span
                                            id="payableamount"><?php echo ($bookingdata->rent)+($bookingdata->cgst + $bookingdata->sgst)-$bookingdata->paid_amount;  ?>  </span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                                <th class="pl-0" width="180"><?php echo display("additional_charges") ?></th>
                                <td>
                                    <input type="number" min="0" id="additionalcharge"
                                        class="form-control form-control-sm" value="0" autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <th class="pl-0"><?php echo display("additional_charge_comments") ?></th>
                                <td>
                                    <textarea class="form-control"
                                        placeholder="<?php echo display("additional_charge_comments") ?>"></textarea>
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
                                            id="netpayableamount"><?php echo ($bookingdata->rent)+($bookingdata->cgst + $bookingdata->sgst)-$bookingdata->paid_amount;  ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                                            id="payableamt"><?php echo ($bookingdata->rent)+($bookingdata->cgst + $bookingdata->sgst)-$bookingdata->paid_amount;  ?></span><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                <div class="card-header py-3">
                    <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("credit") ?></h6>
                </div>
                <div class="card-body">
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
                                        <!-- <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="cardno_0"
                                                placeholder="Card Number" required>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="recdate_0"
                                                placeholder="Date">
                                            <select class="selectpicker form-select" data-live-search="true"
                                                data-width="100%" id="bankname_0">
                                                <option value="" selected>Choose <?php echo display("bank_name") ?>
                                                </option>
                                                <?php foreach($banklist as $list){ ?>
                                                <option value="<?php echo html_escape($list->HeadName); ?>">
                                                    <?php echo html_escape($list->HeadName);?></option>
                                                <?php } ?>
                                            </select>
                                        </div> -->

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

               <div class="col-md-12 mb-3" id="onlinereferencediv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Reference Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="online_referenceno"
                             placeholder="Reference Number">
                     </div>
                  </div>
               </div>

               <div class="col-md-12 mb-3" id="rtgsreferencediv" hidden>
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "RTGS Reference Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="rtgs_referenceno"
                           placeholder="Reference Number">
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
                <div class="card-header py-3">
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
                
            <input type="hidden" id="hbid" value="<?php echo $bookingdata->hbid; ?>">
            <input type="hidden" id="h_type" value="<?php echo $bookingdata->h_type; ?>">
            <input type="hidden" id="BOOKING_ID" value="<?php echo $bookingdata->invoice_no; ?>">
            <button type="button" disabled id="checkout_hall" onclick="checkout_hall()"
                class="btn btn-info btn-lg"><?php echo display("checkout") ?></button>
        </div>
       
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
            </div>
            <div class="invp-7">

        
               <div class="invp-11" style="text-align:left;">
                  
                    <p class="invp-8">
                        <?php echo display("invoiced_to") ?></p>
                    <div>
                        <address class="invp-12">
                           
                       <strong><span
                                id="invname"></span></strong><br>
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
               <strong>   Bill No :</strong>  <?php  echo $bookingdata->invoice_no  ; ?>
               
            </div>
        </div>
        <!-- Order Details -->
<table class="invp-13 table table-bordered table-sm mb-0">
    <thead>
        <tr style="border: 1px solid black;">
            <th style="border: 1px solid black;" class="invp-14"><?php echo ("HALL") ?></th>
            <th style="border: 1px solid black;" class="invp-14"><?php echo display("date") ?></th>
            
            <th style="border: 1px solid black;" class="invp-18"><?php echo display("nod") ?></th>
            <th style="border: 1px solid black;" class="invp-18"><?php echo display("rent") ?></th>
            <!-- <th style="border: 1px solid black;" class="invp-18"><?php echo display("tax") ?></th>
            <th style="border: 1px solid black;" class="invp-18"><?php echo display("advance_amt") ?></th> -->
            <th style="border: 1px solid black;" class="invp-18"><?php echo display("tot_amt") ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="invp-14" style="border: 1px solid black;">
                <strong class="d-block"><?php echo $bookingdata->hall_type; ?></strong>
            </td>
            <td class="invp-14" style="border: 1px solid black;">
                <div>
                    <?php 
                        $indate = html_escape($bookingdata->start_date);
                        $cindate = new DateTime($indate);
                        echo $cindate->format('jS M h:i')." to"; ?><br>
                    <?php 
                        $outdate = html_escape($bookingdata->end_date);
                        $coutdate = new DateTime($outdate);
                        echo $coutdate->format('jS M h:i');
                        $issue = html_escape($bookingdata->start_date);
                        $issuedate = new DateTime($issue);
                        $invissue = $issuedate->format('l, F j, Y');
                    ?>
                </div>
            </td>
            <td class="invp-16" style="border: 1px solid black;">
                <div>
                    <?php 
                        $start = strtotime($bookingdata->start_date);
                        $end = strtotime($bookingdata->end_date);
                        $datediff = $end - $start;
                        echo $days = ceil($datediff / (60 * 60 * 24));
                    ?> 
                    <input type="hidden" class="nod" value="<?php echo $days; ?>">
                </div>
            </td>
            <!-- <td class="invp-19" style="border: 1px solid black;">
                <?php //echo $bookingdata->rent; ?>
            </td>
            <td id="invp-19" style="border: 1px solid black;">
                <?php //echo $bookingdata->cgst + $bookingdata->sgst; ?>
            </td> -->
            <td class="invp-19" style="border: 1px solid black;">
                <?php echo $bookingdata->paid_amount; ?>
            </td>
            <td class="invp-19" style="border: 1px solid black;">
                <?php echo $bookingdata->totalamount; ?>
            </td>
        </tr>
    </tbody>
</table>
<br/>
           
           
            <!-- /Order Details -->
            <!-- Table Total -->
            <div class="invp-22">
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
                                <?php echo display("sub_total") ?>
                            </td>
                            <td class="invp-26" width="80">
                                <?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo ($bookingdata->rent); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="invp-27">
                                <small><?php echo "CGST" ?></small>
                            </td>
                            <td class=" invp-27">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata->cgst; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="invp-27">
                                <small><?php echo "SGST" ?></small>
                            </td>
                            <td class=" invp-27">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata->sgst; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                        
                        <!--<tr>-->
                        <!--    <td class="invp-27">-->
                        <!--        <small><?php echo "Total Tax" ?></small>-->
                        <!--    </td>-->
                        <!--    <td class=" invp-27">-->
                        <!--        <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo $bookingdata->cgst + $bookingdata->sgst; ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>-->
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
                            <td class=" invp-27">
                                <small><?php echo display("additional_charges") ?></small>
                            </td>
                            <td class=" invp-27">
                                <small id="inadcamt"></small>
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
                        <?php if($bookingdata->paid_amount>0) { ?>
                        <tr>
                            <td class=" invp-27">
                                <small><?php echo display("advance_amount") ?></small>
                            </td>
                            <td class=" invp-27">
                                <small><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo html_escape($bookingdata->paid_amount); ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></small>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr id="paidamounttitle" hidden>
                            <td class=" invp-27">
                                <small><?php echo display("paid_amount") ?></small>
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
                                <strong><?php echo display("grand_total_inctax") ?></strong>
                            </td>
                            <td class="invp-28">
                                <strong
                                    id="inpayableamt"><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?><?php echo ($bookingdata->rent)+($bookingdata->cgst + $bookingdata->sgst)-$bookingdata->paid_amount;  ?><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></strong>
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
                  <div class="invp-34" >
                <div class="invp-35"><?php echo display("guest_signature") ?></div>
                <div class="invp-35"><?php echo display("authorized_signature") ?></div>
            </div>
            <!--/Signatory-->
        </div>
    </div></div>
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

<script src="<?php echo MOD_URL.$module;?>/assets/js/checkoutall.js"></script>
<script src="<?php echo MOD_URL.$module;?>/assets/js/custom.js"></script>



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

 // RTGS
 if(paymentmode=="RTGS"){
     $("#rtgs_referenceno").attr("disabled", false);
     $("#rtgsreferencediv").attr("hidden", false);
 }else{
     $("#rtgs_referenceno").attr("disabled", true);
     $("#rtgsreferencediv").attr("hidden", true);
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
 if(paymentmode=="Online Payment"){
     $("#online_referenceno").attr("disabled", false);
     $("#onlinereferencediv").attr("hidden", false);
 }else{
     $("#online_referenceno").attr("disabled", true);
     $("#onlinereferencediv").attr("hidden", true);
 }

 if(paymentmode){
     $("#advanceamount").attr("disabled", false);
     $("#advanceremarks").attr("disabled", false);
 }else{
     $("#advanceamount").attr("disabled", true);
     $("#advanceremarks").attr("disabled", true);
 }
});




</script>

<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<div id="reservation">
    <div class="card mb-4">
        <div class="card-header py-2 ">
            <h6 class="fs-17 font-weight-600 mb-0"><?php echo display('check_in_details') ?><span id="msg"
                    class="red-message"></span><small class="float-right"><a href="#" id="view_checin"
                        class="btn btn-primary btn-sm"><i class="ti-plus" aria-hidden="true"></i>
                        <?php echo display('booking_list')?></a></small></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1"><?php echo display('checkin') ?><span
                                class="text-danger">*</span></label>
                        <div class="icon-addon addon-md">
                            <input type="text" name="datefilter" class="form-control datefilter" id="datefilter1"
                                placeholder="dd/mm/yyyy --:-- --"
                                value="<?php echo html_escape($bookingdata->checkindate); ?>" />
                            <label class="fas fa-calendar-alt"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1"><?php echo display('checkout') ?> <span
                                class="text-danger">*</span></label>
                        <div class="icon-addon addon-md">
                            <input type="text" name="datefilter" class="form-control datefilter" id="datefilter2"
                                placeholder="dd/mm/yyyy --:-- --"
                                value="<?php echo html_escape($bookingdata->checkoutdate); ?>" />
                            <label class="fas fa-calendar-alt"></label>
                        </div>
                    </div>
                </div>
                <div style='display:none;' class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1"><?php echo display('arrival_from') ?></label>
                        <div class="icon-addon addon-md">
                            <input type="text" class="form-control" id="arrival_from"
                                placeholder="<?php echo display('arrival_from') ?>"
                                value="<?php echo html_escape($bookingdata->arival_from); ?>" readonly>
                            <label class="fas fa-plane-arrival"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1"><?php echo display('purpose_of_visit') ?></label>
                        <div class="icon-addon addon-md">
                            <select class="form-control" id="pof_visit">
                                <?php if($bookingdata->purpose){ ?>
                                <option><?php echo $bookingdata->purpose; ?></option>
                                <?php } ?>
                                <option value="Official meeting">Official meeting</option>
                                <option value="Official gathering">Official gathering</option>  
                                <option value="Retreat">Retreat</option>
                                <option value="Family outing">Family outing</option>
                                <option value="Picnic">Picnic</option>
                                <option value="Airport stay">Airport stay</option>
                            </select>
                            <!--<input type="text" class="form-control" id="pof_visit"-->
                            <!--    placeholder="<?php echo display('purpose_of_visit') ?>"-->
                            <!--    value="<?php echo html_escape($bookingdata->purpose); ?>">-->
                            <label class="fas fa-eye"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1"><?php echo display('remarks') ?></label>
                        <div class="icon-addon addon-md">
                            <input type="text" class="form-control" id="booking_remarks"
                                placeholder="<?php echo display('remarks') ?>"
                                value="<?php echo html_escape($bookingdata->remarks); ?>">
                            <label class="fas fa-comment-dots"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="fs-17 font-weight-600 mb-0"><?php echo display('room_detail') ?></h6>
        </div>
        <input type="hidden" id="bookingid" value="<?php echo html_escape($bookingdata->bookedid); ?>">
        <?php $roomtype = explode(",",$bookingdata->roomid);
        $roomno = explode(",",$bookingdata->room_no);
        $nofpeople = explode(",",$bookingdata->nuofpeople);
        $children = explode(",",$bookingdata->children);
        $extracheckin = explode(",",$bookingdata->extracheckin);
        $extracheckout = explode(",",$bookingdata->extracheckout);
        $roomrate = explode(",",$bookingdata->roomrate);
        $totalamount = 0;
        $taxPercent = 0;
        $scharge = 0;
        for($tm=0; $tm<count($roomrate); $tm++){
            $totalamount+=$roomrate[$tm];
        }
        $taxPercent = 0;
        if(!empty($taxsetting)){
            foreach($taxsetting as $tax){
                $taxPercent += $tax->rate;
            }
        }
        if($taxPercent>0){
            $taxPercent = ($totalamount*$taxPercent)/100;
        }
        
        $totalamount = $totalamount+$taxPercent;
        
        $totaldatediff = strtotime($extracheckin) - strtotime($extracheckout);
        $totaldays = ceil($totaldatediff / (60 * 60 * 24));
        
        $room_rent = explode(",",$bookingdata->roomrate);
        $bcharge = $bookingdata->roomrate * $totaldays;
        $extrabed = explode(",",$bookingdata->extrabed);
        $extraperson = explode(",",$bookingdata->extraperson);
        $extrachild = explode(",",$bookingdata->extrachild);
        
        $extrabedamount = explode(",",$bookingdata->bed_amount);
        $extrapersonamount = explode(",",$bookingdata->person_amount);
        $extrachildamount = explode(",",$bookingdata->child_amount);
        ?>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered white-space-nowrap mb-0 room-list">
                    <?php for($r=-1; $r<count($roomtype)-1; $r++) { ?>
                    <tbody>
                        <tr>
                            <th colspan="2"><?php echo display('room_info') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="border-0">
                                                <div class="form-group mb-0">
                                                    <label
                                                        class="font-weight-600 mb-1"><?php echo display('roomtype') ?>
                                                        <span class="text-danger">*</span></label>
                                                    <div class="icon-addon addon-md input-left-icon">
                                                        <select class="selectpicker form-select" data-live-search="true"
                                                            data-width="100%" onchange="getroomnos(<?php echo $r; ?>)"
                                                            id="room_type<?php echo $r;?>">
                                                            <option value="" selected>Choose
                                                                <?php echo display('roomtype') ?></option>
                                                            <?php foreach($roomdetails as $btype){ ?>
                                                            <option
                                                                <?php if($btype->roomid==$roomtype[$r+1]){echo 'selected';} ?>
                                                                value="<?php echo html_escape($btype->roomid); ?>">
                                                                <?php echo html_escape($btype->roomtype);?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <label class="fas fa-sort-amount-down"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <div class="form-group mb-0">
                                                    <label class="font-weight-600 mb-1"><?php echo display('room_no') ?>
                                                        <span class="text-danger">*</span></label>
                                                    <div class="icon-addon addon-md input-left-icon">
                                                        <select name=roomno[] class="selectpicker form-select"
                                                            data-live-search="true" data-width="100%"
                                                            onchange="getcapcitys(<?php echo $r; ?>)" disabled
                                                            id="roomno<?php echo $r; ?>">
                                                            <option value="" selected>Choose
                                                                <?php echo display('room_no') ?></option>
                                                            <option value="<?php echo html_escape($roomno[$r+1]);?>"
                                                                selected><?php echo html_escape($roomno[$r+1]);?>
                                                            </option>
                                                            <input type="hidden" id="room_rent" class="room_rent">
                                                        </select>
                                                        <label class="fas fa-sort-numeric-down"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <div class="form-group mb-0">
                                                    <label
                                                        class="font-weight-600 mb-1">No of persons</label>
                                                    <div class="icon-addon addon-md input-left-icon">
                                                        <input type="number" min="1" disabled class="form-control"
                                                            id="adults<?php echo $r; ?>"
                                                            value="<?php echo html_escape($nofpeople[$r+1]);?>"
                                                            placeholder="<?php echo display('adults') ?>">
                                                        <label class="fas fa-restroom"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <!--<td class="border-0">-->
                                            <!--    <div class="form-group mb-0">-->
                                            <!--        <label-->
                                            <!--            class="font-weight-600 mb-1">#<?php echo display('children') ?></label>-->
                                            <!--        <div class="icon-addon addon-md input-left-icon">-->
                                            <!--            <input type="number" disabled min="0" class="form-control"-->
                                            <!--                id="children<?php echo $r; ?>"-->
                                            <!--                placeholder="<?php echo display('children') ?>"-->
                                            <!--                value="<?php echo html_escape($children[$r+1]);?>">-->
                                            <!--            <label class="fas fa-child"></label>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</td>-->
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td rowspan="3" class="text-center">
                                <button type="button" onclick="rdel(<?php echo $r; ?>)"
                                    class="btn btn-danger btn-sm rdel<?php echo $r; ?>"><i
                                        class="far fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><?php echo display('occupant_info') ?></span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle no-caret" type="button"
                                            <?php if($r!=-1){echo 'hidden ';} ?>id="custdetailbtn<?php echo $r; ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"
                                                data-target="#exampleModal"><?php echo display('new_customer') ?></a>
                                            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"
                                                data-target="#exampleModal2"><?php echo display('old_customer') ?></a>
                                        </div>
                                    </div>
                                </div>

            </div>
            </th>
            <th><?php echo display('rent_info') ?></th>
            </tr>
            <tr>
                <td>
                    <table class="table table-borderless customerdetail<?php echo $r; ?>">
                        <thead>
                            <tr>
                                <th class="pl-0" width="20"><?php echo display('sl') ?></th>
                                <th><?php echo display('name') ?></th>
                                <th><?php echo display('mobile_no') ?>.</th>
                                <th class="text-right pr-0"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="border-0 pl-0">
                                    <div class="custom-control custom-radio"><input type="radio"
                                            <?php if($r==-1) echo 'checked'; ?> onclick="getradio(<?php echo 0; ?>)"
                                            id="pri<?php echo 0; ?>" name="customRadio"
                                            class="custom-control-input"><label class="custom-control-label"
                                            for="pri<?php echo 0; ?>"></label>
                                    </div>
                                </th>
                                <td class="border-0" id="userid<?php echo 0; ?>" hidden>
                                    <?php echo html_escape($custdata[0]->customerid); ?></td>
                                <td class="border-0" id="username<?php echo 0; ?>">
                                    <?php echo html_escape($custdata[0]->firstname); ?></td>
                                <td class="border-0" id="usermobile<?php echo 0; ?>">
                                    <?php echo html_escape($custdata[0]->cust_phone); ?></td>
                                <td class="border-0 pr-0 text-right">
                                    <button type="button" onclick="custdel(<?php echo 0; ?>)"
                                        class="btn btn-danger-soft btn-xs custdelete<?php echo 0; ?>"
                                        id="custdel<?php echo 0; ?>"><i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                                        $customer = explode(",",$bookingdata->full_guest_name);
                                        for($c=0;$c<count($guestdata);$c++){
                                        ?>
                            <tr>
                                <?php if(empty($guestdata[$c]->customerid)){ ?>
                                <th class="border-0 pl-0">
                                    <div class="custom-control custom-radio"><input type="radio"
                                            onclick="getradio(<?php echo $c+1; ?>)" id="pri<?php echo $c+1; ?>"
                                            name="customRadio" class="custom-control-input"><label
                                            class="custom-control-label" for="pri<?php echo $c+1; ?>"></label>
                                    </div>
                                </th>
                                <td class="border-0" id="username<?php echo $c+1; ?>">
                                    <?php echo html_escape($guestdata[$c]->guestname); ?></td>
                                <td class="border-0" id="usermobile<?php echo $c+1; ?>">
                                    <?php echo html_escape($guestdata[$c]->mobile); ?></td>
                                <td class="border-0 pr-0 text-right">
                                    <button type="button" onclick="custdel(<?php echo $c+1; ?>)"
                                        class="btn btn-danger-soft btn-xs custdelete<?php echo $c+1; ?>"
                                        id="custdel<?php echo $c+1; ?>"><i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                                <?php }else{ ?>
                                <?php $oldcustomer = $this->db->select("customerid,firstname,cust_phone")->from("customerinfo")->where("customerid",$guestdata[$c]->customerid)->get()->row(); ?>
                                <th class="border-0 pl-0">
                                    <div class="custom-control custom-radio"><input type="radio"
                                            onclick="getradio(<?php echo $c+1; ?>)" id="pri<?php echo $c+1; ?>"
                                            name="customRadio" class="custom-control-input"><label
                                            class="custom-control-label" for="pri<?php echo $c+1; ?>"></label>
                                    </div>
                                </th>
                                <td class="border-0" id="userid<?php echo $c+1; ?>" hidden>
                                    <?php echo html_escape($oldcustomer->customerid); ?></td>
                                <td class="border-0" id="username<?php echo $c+1; ?>">
                                    <?php echo html_escape($oldcustomer->firstname); ?></td>
                                <td class="border-0" id="usermobile<?php echo $c+1; ?>">
                                    <?php echo html_escape($oldcustomer->cust_phone); ?></td>
                                <td class="border-0 pr-0 text-right">
                                    <button type="button" onclick="custdel(<?php echo $c+1; ?>)"
                                        class="btn btn-danger-soft btn-xs custdelete<?php echo $c+1; ?>"
                                        id="custdel<?php echo $c+1; ?>"><i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table table-borderless mb-0 order-list">
                        <tbody>
                            <tr>
                                <td class="border-0">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-600 mb-1"><?php echo display('checkin') ?></label>
                                        <div class="icon-addon addon-md">
                                            <input type="text" disabled class="form-control form-control datefilter3"
                                                id="from_date1<?php echo $r; ?>" placeholder="dd/mm/yyyy"
                                                value="<?php echo html_escape($extracheckin[$r+1]);?>">
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-600 mb-1"><?php echo display('checkout') ?></label>
                                        <div class="icon-addon addon-md">
                                            <input type="text" disabled class="form-control form-control datefilter4"
                                                id="to_date1<?php echo $r; ?>" placeholder="dd/mm/yyyy"
                                                value="<?php echo html_escape($extracheckout[$r+1]);?>">
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-600 mb-1"><?php echo display('rent') ?>
                                        </label>
                                        <div class="icon-addon addon-md">
                                            <input type="number"  class="form-control form-control"
                                                id="rent<?php echo $r; ?>"
                                                value="<?php echo html_escape($room_rent[$r+1]);?>">
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-600 mb-1">
                                        </label>
                                        <div class="d-flex"><span class="p-2"><del class="text-danger"
                                                    id="offer_price<?php echo $r; ?>"><?php echo (!empty($offer[$r+1])?html_escape($offer[$r+1]):"" )?></del></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="p-0">
                    <table class="table table-borderless mb-0 bg-light">
                        <tbody>
                            <tr>
                                <td style="display:none;" class="border-0">
                                    <input type="text" class="form-control ex-room datefilter3"
                                        id="from_date2<?php echo $r; ?>" placeholder="dd/mm/yyyy"
                                        value="<?php echo html_escape($extracheckin[$r+1]);?>">
                                </td>
                                <td style="display:none;" class="border-0">
                                    <input type="text" class="form-control ex-room datefilter4"
                                        id="to_date2<?php echo $r; ?>" placeholder="dd/mm/yyyy"
                                        value="<?php echo html_escape($extracheckout[$r+1]);?>">
                                </td>
                                <?php 
                                                $price = $this->db->select("childcharge,bedcharge,personcharge")->from("roomdetails")->where("roomid",$roomtype[$r+1])->get()->row();

                                              // print_r($extrabed[$r+1]); die();
                                            ?>
                                <td class="border-0">
                                    <input type="text"  onchange="bedprices(<?php echo $r; ?>)"
                                        class="form-control ex-room" id="bed<?php echo $r; ?>"
                                        value="<?php echo (!empty($extrabed[$r+1])?html_escape($extrabed[$r+1]):"");?>"
                                        placeholder="Extra bed">
                                </td>
                                <td class="border-0">
                                    <input type="text" disabled class="form-control ex-room" id="amount1<?php echo $r; ?>" value="<?php echo !empty($extrabedamount[$r+1]) ? $extrabedamount[$r+1] : ''; ?>" placeholder="<?php echo display('amnt'); ?>">
                                <td class="border-0">
                                    <input type="text" min="0" onchange="personprices(<?php echo $r; ?>)"
                                        class="form-control ex-room" id="person<?php echo $r; ?>"
                                        value="<?php echo (!empty($extraperson[$r+1])?html_escape($extraperson[$r+1]):"");?>"
                                        placeholder="Extra person">
                                </td>
                                <td class="border-0">
                                    <input type="text" disabled class="form-control ex-room" id="amount2<?php echo $r; ?>" value="<?php echo !empty($extrapersonamount[$r+1]) ? $extrapersonamount[$r+1] : ''; ?>" placeholder="<?php echo display('amnt') ?>">
                                </td>
                                <td class="border-0">
                                    <input type="text" min="0" onchange="childprices(<?php echo $r; ?>)"
                                        class="form-control ex-room" id="child1<?php echo $r; ?>"
                                        value="<?php echo (!empty($extrachild[$r+1])?html_escape($extrachild[$r+1]):"");?>"
                                        placeholder="Extra child">
                                </td>
                                <td class="border-0">
                                    <input type="text" disabled class="form-control ex-room" id="amount3<?php echo $r; ?>" value="<?php echo !empty($extrachildamount[$r+1]) ? $extrachildamount[$r+1] : ''; ?>" placeholder="<?php echo display('amnt') ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="p-0">
                    <?php
                                $rtype = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$roomtype[$r+1])->get()->row();
                                $complementarylist = $this->db->select("*")->from("tbl_complementary")->where("roomtype",$rtype->roomtype)->get()->result();
                                ?>
                    <table class="table table-borderless mb-0 bg-light" style="display: none;">
                        <tbody>
                            <tr>
                                <td class="border-0">
                                    <select onmouseenter="getcomplementprice(<?php echo $r; ?>)" disabled
                                        name="complementary" class="selectpicker form-select" data-live-search="true"
                                       data-width="100%" id="complementary<?php echo $r; ?>">
                                        <option value="0" selected>Choose <?php echo display('complementary') ?>
                                        </option>
                                        <?php foreach($complementarylist as $rtype){ ?>

                                        <option
                                          <?php if(preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "\n", $rtype->complementaryname))==preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "\n", $compname[$r+1])) && preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "\n", $rtype->rate))==preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "\n", $compprice[$r+1]))){echo 'selected';} ?>
                                            value="<?php echo html_escape($rtype->rate); ?>">
                                            <?php echo html_escape($rtype->complementaryname);?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="row">
                                        <span class="ml-4"
                                          id="compamount<?php echo $r; ?>"><?php if($compprice[$r+1]!=0){ echo html_escape($compprice[$r+1]);} ?></span>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <input type="number" disabled class="form-control form-control"
                                       id="nofroom<?php echo $r; ?>"
                                       value="<?php if($r!=-1){echo '';}else{echo html_escape(count($roomtype));} ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="text-center res-v-allign"><button type="button"
                        <?php if($r!=count($roomtype)-2){echo 'hidden';} ?>
                        class="btn btn-primary btn-sm newroom<?php echo $r; ?>" onclick="room(<?php echo $r; ?>)"
                        id="newroom<?php echo $r; ?>"><i class="fas fa-plus"></i></button></td>
            </tr>
            </tbody>
            <?php } ?>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div style="display:none;">
        <div style="display:none;" class="card flex-fill w-100">
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display('payment_details') ?> <span id="msg2"
                        class="red-message"></span>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('discount_reason') ?></label>
                            <div class="icon-addon addon-md">
                                <input type="text" class="form-control" id="discountreason"
                                    value="<?php echo html_escape($bookingdata->discountreason); ?>"
                                    placeholder="<?php echo display('discount_reason') ?>">
                                <label class="fas fa-tags"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="row align-items-end">
                            <!--<div class="col-8">-->
                                <div class="form-group mb-0">
                                    <label class="font-weight-600 mb-1">Discount Amount</label>
                                    <div class="icon-addon addon-md">
                                        <input type="number" disabled
                                            value="<?php echo $bookingdata->discountamount; ?>"
                                            class="form-control" id="discountrate"
                                            placeholder="Discount Amount">
                                        <label class="fas fa-tags"></label>
                                    </div>
                                </div>
                            <!--</div>-->
                            <!--<div class="col-4">-->
                            <!--    <div class="d-flex align-items-center">-->
                            <!--        <div class="ml-1">-->
                            <!--            <?php if($currency->position==1){ echo "(".html_escape($currency->curr_icon).")"; } ?>-->
                            <!--        </div>-->
                            <!--        <input type="number" disabled class="form-control form-control" id="discountamount"-->
                            <!--            value="<?php echo ($bookingdata->discountamount) ? html_escape($bookingdata->discountamount) : ''; ?>">-->
                            <!--        <div class="ml-1">-->
                            <!--            <?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
                    <!-- <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('commission') ?></label>
                            <div class="icon-addon addon-md">
                                <input type="text" disabled class="form-control" id="commissionrate"
                                    value="<?php echo html_escape($bookingdata->commissionpersent); ?>"
                                    placeholder="Commission rate">
                                <label class="fas fa-percent"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('commission_amt') ?>.</label>
                            <div class="icon-addon addon-md">
                                <i
                                    class=""><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?></i>
                                <input type="text" disabled class="form-control" id="commissionamount"
                                    value="<?php echo html_escape($bookingdata->commissionamount); ?>"
                                    placeholder="<?php echo display('commission_amt') ?>">
                                <i
                                    class=""><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></i>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo "Billing Details"; ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0"><?php echo "Rent" ?></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0" id="booking_charge"><?php echo html_escape($bcharge);?></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0"><?php echo "CGST(6%)" ?></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0" id="cgst_charge"><?php echo $bookingdata->cgst; ?></label>
                            <input type="hidden" id="cgst_charge" class="cgst_charge" value="<?php echo $bookingdata->cgst; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0"><?php echo "SGST(6%)" ?></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0" id="sgst_charge"><?php echo $bookingdata->sgst; ?></label>
                            <input type="hidden" id="sgst_charge" class="sgst_charge" value="<?php echo $bookingdata->sgst; ?>">
                        </div>
                    </div>
                    <div  class="col-md-6 mb-3">
                        <div style="display:none;" class="form-group mb-0">
                            <label class="font-weight-600 mb-0"><?php echo "Discount" ?><span style="display:none;" id="d_rate"></span></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div style="display:none;" class="form-group mb-0">
                            <label class="font-weight-600 mb-0" id="discountAmount">-<?php echo html_escape($bookingdata->discountamount); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-0"><b><?php echo "Total" ?></b></label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                           <!--  <label class="font-weight-600 mb-0"
                                id="total_charge"><?php echo $totalamount*$totaldays ?></label> -->
                            <label class="font-weight-600 mb-0"
                                id="total_charge"><?php echo $bookingdata->total_price; ?></label>
                            <input type="hidden" class="get_total" id="get_total" value="<?php echo $bookingdata->total_price; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header py-3">
                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display('advance_details') ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('payment_mode') ?></label>
                            <div class="icon-addon addon-md input-left-icon">
                                <?php
                               
                // echo $payment_method->details;
                  $ref_no = $payment_method->details;
                  $ref=explode(':', $ref_no);
                  $ref=$ref[1];
                // echo  $payment_method->paymenttype;die();
                  //echo $ref;
            
                                ?>
                                <select class="selectpicker form-select" <?php if ($payment_method->paymenttype) { echo "disabled"; } ?> data-live-search="true" data-width="100%"
                                    id="paymentmode">
                                    <option value="" selected>Choose <?php echo display('payment_mode') ?></option>
                                    <?php foreach($paymentdetails as $ptype){ ?>
                                    <option
                                        <?php if($ptype->payment_method==$bookingdata->payment_method){echo 'selected';} ?>
                                        value="<?php echo html_escape($ptype->payment_method) ?>">
                                        <?php echo html_escape($ptype->payment_method) ?></option>
                                    <?php } ?>
                                </select>
                                <label class="fas fa-credit-card"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('total_amount') ?></label>
                            <div class="icon-addon addon-md">
                                <i
                                    class=""><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?></i>
                                <!-- <input type="text" disabled class="form-control" id="totalamount"
                                    value="<?php echo $totalamount*$totaldays; ?>" placeholder="Total amount"> -->

                                <input type="text" disabled class="form-control" id="totalamount"
                                    value="<?php echo $bookingdata->total_price; ?>" placeholder="Total amount">
                                <i
                                    class=""><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></i>
                            </div>
                        </div>
                    </div>
                    
                                
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

           <div class="col-md-12 mb-3" id="banknamediv" <?php if ($payment_method->paymenttype != 'Bank Payment') { echo "hidden"; } ?> >
    <div class="form-group mb-0">
        <label class="font-weight-600 mb-1"><?php echo "Bank Name" ?></label>
        <div class="icon-addon addon-md">
            <input type="text" <?php if ($payment_method->paymenttype == 'Bank Payment') { echo "readonly"; } ?> value='<?php echo $ref ?>' class="form-control" id="bankname" style="width: 858px;" placeholder="Bank Name">
        </div>
    </div>
</div>
<div class="col-md-12 mb-3" id="accountnumberdiv"  hidden >
   <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Account Number" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled class="form-control" id="accountnumber"
                           placeholder="mm/yyyy">
                        <label class="fas fa-credit-card"></label>
                     </div>
                  </div>
</div>


              <div class="col-md-12 mb-3" id="upireferencediv" <?php if ($payment_method->paymenttype != 'UPI') { echo "hidden"; } ?>>
    <div class="form-group mb-0">
        <label class="font-weight-600 mb-1"><?php echo "UPI Reference Number" ?></label>
        <div class="icon-addon addon-md">
            <input type="text" <?php if ($bookingpaymentdata->paymenttype == 'UPI') { echo "readonly"; } ?> value='<?php echo $ref ?>' class="form-control" id="upi_referenceno" style="width: 858px;" placeholder="Reference Number">
        </div>
    </div>
</div>
               <?php   if($payment_method->paymenttype == 'Card Payment') { ?>
               <div class="col-md-12 mb-3" >
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Card Details" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled  value='<?php  echo $ref  ?>'  class="form-control" id=""
                          style="width: 858px;"  placeholder="Reference Number">
                     </div>
                  </div>
               </div>
               <?php  } ?>
                       <?php   if($payment_method->paymenttype == 'Cheque') { ?>
               <div class="col-md-12 mb-3" >
                  <div class="form-group mb-0">
                     <label class="font-weight-600 mb-1"><?php echo "Cheque Details" ?></label>
                     <div class="icon-addon addon-md">
                        <input type="text" disabled  value='<?php  echo $ref  ?>'  class="form-control" id=""
                          style="width: 858px;"  placeholder="Reference Number">
                     </div>
                  </div>
               </div>
               <?php  } ?>
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
              
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('advance_remarks') ?></label>
                            <div class="icon-addon addon-md">
                                <input type="text" class="form-control" id="advanceremarks" disabled
                                    value="<?php echo html_escape($bookingdata->advance_remarks); ?>"
                                    placeholder="<?php echo display('advance_remarks') ?>">
                                <label class="fas fa-comments"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-600 mb-1"><?php echo display('advance_amount') ?></label>
                            <div class="icon-addon addon-md">
                                <i
                                    class=""><?php if($currency->position==1){ echo html_escape($currency->curr_icon); } ?></i>
                                <input type="number" disabled <?php if ($payment_method->paymenttype) { echo "readonly"; } ?> class="form-control" id="advanceamount"
                                    value="<?php echo html_escape($bookingdata->advance_amount); ?>"
                                    placeholder="<?php echo display('advance_amount') ?>">
                                <i
                                    class=""><?php if($currency->position==2){ echo html_escape($currency->curr_icon); } ?></i>
                          <input type="hidden"  name="advanceid" value="<?php echo $bookingdata->advance_id; ?>"  id="advanceid"  >
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <div class="col-lg-1 d-flex"></div>
</div>
<div class="text-right mt-3">
    <button type="button" class="btn btn-primary w-100p" onclick="newBooking()"
        id="bookingsave"><?php echo display("update") ?></button>
</div>
<!-- Occupant Details Modal -->
<style>
   .custom-modal-xl {
   max-width: 70%; /* Adjust the percentage as needed */
   }
</style>



<!-- Occupant Details Modal -->
<div class="modal custom-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog custom-modal-xl">
      <div class="modal-content border-0">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo display("occupant_details") ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6 d-flex">
                  <div class="card flex-fill w-100 border mb-4">
                     <div class="card-header py-3">
                        <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("guest_details") ?></h6>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-3 mb-1">
                              <div class="form-group mb-0">
                                 <label><?php echo display("title") ?></label>
                                 <div class="icon-addon addon-md">
                                    <select class="form-select"  name="title"  id="title"      >
                                       <option selected value="Mr">Mr</option>
                                       <option value="Ms">Ms</option>
                                       <option value="Mrs">Mrs</option>
                                       <option value="M/s">M/s</option>
                                       <option value="Dr">Dr</option>
                                       <option value="Sister">Sister</option>
                                       <option value="Father">Father</option>
                                    </select>
                                    <label class="fas fa-meh"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 mb-4">
                              <div class="form-group mb-0">
                                 <label for="firstname"><?php echo display("first_name") ?> <span
                                    class="text-danger">*</span></label>
                                 <div class="icon-addon addon-md">
                                    <input type="text" required name="firstname"   class="form-control" id="firstname"
                                       placeholder="<?php echo display("first_name") ?>  "   >
                                    <label class="fas fa-user-circle"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 mb-4">
                              <div class="form-group mb-0">
                                 <label for="lastname"><?php echo display("last_name") ?> <span
                                    class="text-danger">*</span></label>
                                 <div class="icon-addon addon-md">
                                    <input type="text" required name="lastname"  class="form-control" id="lastname" style="width:192px;"                                                    placeholder="<?php echo display("last_name") ?>">
                                    <label class="fas fa-user-circle"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('firstname').addEventListener('keyup', updateFullName);
                              document.getElementById('lastname').addEventListener('keyup', updateFullName);
                              document.getElementById('title').addEventListener('keyup', updateFullName);
                              
                              function updateFullName() {
                                  var titleValue = document.getElementById('title').value;
                                  var firstNameValue = document.getElementById('firstname').value;
                                  var lastNameValue = document.getElementById('lastname').value;
                                  
                                  var fullName = titleValue + ' ' + firstNameValue + ' ' + lastNameValue;
                                  document.getElementById('c_full_name').value = fullName;
                              }
                           </script>
                           <div class="col-md-6 align-self-center mb-3">
                              <div class="form-group mb-0">
                                 <label><?php echo  ("Gender") ?></label>
                                 <div class="icon-addon addon-md">
                                    <select class="form-select"  name="gender"   style="width: 235px;"  >
                                       <option selected value="male">Male</option>
                                       <option value="female">Female</option>
                                       <option value="Others">Others</option>
                                    </select>
                                    <label class="fas fa-meh"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="occupation"><?php echo display("occupation") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="text" class="form-control" name="profession"  id="occupation"  
                                       placeholder="<?php echo display("occupation") ?>">
                                    <label class="fas fa-anchor"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="dob"><?php echo display("dob") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="date" autocomplete="off" name="dob"  
                                       class="form-control datefilter2" id="dob" placeholder="dd/mm/yyyy"
                                       value="" />
                                    <label class="fas fa-calendar-alt"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('dob').addEventListener('change', function() {
                                  var dobValue = this.value;
                                  document.getElementById('c_dob').value = dobValue;
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="anniversary"><?php echo display("anniversary") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="date" name="adate" autocomplete="off"  
                                       class="form-control datefilter2" id="anniversary"
                                       placeholder="dd/mm/yyyy" value="" />
                                    <label class="fas fa-calendar-alt"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="nationality"><?php echo display("nationality") ?><span  class="text-danger">*</span></label>
                                 <div class="icon-addon addon-md">
                                    <input type="text" name="nationaliti" class="form-control"
                                       id="nationality" require  placeholder="<?php echo display("nationality") ?>"
                                       value="" />
                                    <label class="fas fa-flag-usa"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('nationality').addEventListener('input', function() {
                                  var inputValue = this.value;
                                  var regex = /^[a-zA-Z]*$/;
                                  if (!regex.test(inputValue)) {
                                      this.value = inputValue.slice(0, -1);
                                  }
                              });
                           </script>
                           <script>
                              document.getElementById('nationality').addEventListener('keyup', function() {
                                  var firstNatValue = this.value;
                                  document.getElementById('c_nationality').value = firstNatValue;
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="gst"><?php echo ("GSTIN") ?></label>
                                 <br>
                                 <div class="icon-addon addon-md" style="display: flex; align-items: center;">
                                    <input type="text" name="gst_no" class="form-control hidden"
                                       id="gst_no" placeholder="<?php echo ("GSTIN") ?>" value="" />
                                    <label class="fas fa-info-circle" style="margin-left: 4px;"></label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <script>
document.getElementById('gst_no').addEventListener('input', function (e) {
    // Convert to uppercase
    e.target.value = e.target.value.toUpperCase();
    // Limit to 15 characters
    if (e.target.value.length > 15) {
        e.target.value = e.target.value.slice(0, 15);
    }
});
</script>   
               <div class="col-md-6 d-flex">
                  <div class="card flex-fill w-100 border mb-4">
                     <div class="card-header py-3">
                        <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("contact_details") ?></h6>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="contacttype"><?php echo  ("Booking Type") ?></label>
                                 <div class="icon-addon addon-md">
                                    <select class="form-select" name="contacttype"   id="contacttype" style="width:235px;" >
                                       <option selected value="">Choose
                                          <?php echo display("contact_type") ?>
                                       </option>
                                       <option value="walk-in">walk-in</option>
                                       <option value="Online">Online</option>
                                       <option value="House Guest">House Guest</option>
                                       <option value="Referred / Management Guest">Referred / Management Guest</option>
                                    </select>
                                    <label class="fas fa-address-book"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="mobileNo"><?php echo display("mobile_no") ?> <span  class="text-danger">*</span> </label>
                                 <div class="icon-addon addon-md">
                                    <input type="number"    name="phone"   required     class="form-control" id="mobileNo"
                                       placeholder="<?php echo display("mobile_no") ?>.">
                                    <label class="fas fa-mobile"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('mobileNo').addEventListener('keyup', function() {
                                  var firstmobValue = this.value;
                                  document.getElementById('c_moblie').value = firstmobValue;
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="email"><?php echo display("email") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="email" class="form-control" name="email" id="email"
                                       placeholder="example@email.com"     >
                                    <label class="fas fa-envelope"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('email').addEventListener('keyup', function() {
                                  var firstEmailValue = this.value;
                                  document.getElementById('c_email').value = firstEmailValue;
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="floatingSelect"><?php echo display("city") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="city"  name="city"  class="form-control" id="city"
                                       placeholder="<?php echo display("city") ?>">
                                    <label class="fas fa-globe-americas"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('city').addEventListener('input', function() {
                                  var inputValue = this.value;
                                  var regex = /^[a-zA-Z]*$/;
                                  if (!regex.test(inputValue)) {
                                      this.value = inputValue.slice(0, -1);
                                  }
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="floatingSelect"><?php echo display("state") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="state"  name="state"  class="form-control" id="state"
                                       placeholder="<?php echo display("state") ?>">
                                    <label class="fas fa-globe-americas"></label>
                                 </div>
                              </div>
                           </div>
                             <script>
                                    document.getElementById('state').addEventListener('input', function() {
                                        var inputValue = this.value;
                                         var regex = /^[a-zA-Z\s]*$/; // Modified regex to include space (\s)
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="zipcode"><?php echo display("zipcode") ?></label>
                                 <div class="icon-addon addon-md">
                                    <input type="number" name="zipcode"   class="form-control" id="zipcode"
                                       placeholder="<?php echo display("zipcode") ?>">
                                    <label class="fas fa-code-branch"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('zipcode').addEventListener('keyup', function() {
                                  var firstpincodeValue = this.value;
                                  document.getElementById('c_pincode').value = firstpincodeValue;
                              });
                           </script>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="floatingSelect"><?php echo display("country") ?></label>
                                 <div class="icon-addon addon-md">
                                    <select name="country" class="form-control countries"  id="country"  >
                                       <option value="Afghanistan">Afghanistan</option>
                                       <option value="Albania">Albania</option>
                                       <option value="Algeria">Algeria</option>
                                       <option value="American Samoa">American Samoa</option>
                                       <option value="Andorra">Andorra</option>
                                       <option value="Angola">Angola</option>
                                       <option value="Anguilla">Anguilla</option>
                                       <option value="Antartica">Antarctica</option>
                                       <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                       <option value="Argentina">Argentina</option>
                                       <option value="Armenia">Armenia</option>
                                       <option value="Aruba">Aruba</option>
                                       <option value="Australia">Australia</option>
                                       <option value="Austria">Austria</option>
                                       <option value="Azerbaijan">Azerbaijan</option>
                                       <option value="Bahamas">Bahamas</option>
                                       <option value="Bahrain">Bahrain</option>
                                       <option value="Bangladesh">Bangladesh</option>
                                       <option value="Barbados">Barbados</option>
                                       <option value="Belarus">Belarus</option>
                                       <option value="Belgium">Belgium</option>
                                       <option value="Belize">Belize</option>
                                       <option value="Benin">Benin</option>
                                       <option value="Bermuda">Bermuda</option>
                                       <option value="Bhutan">Bhutan</option>
                                       <option value="Bolivia">Bolivia</option>
                                       <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                                       <option value="Botswana">Botswana</option>
                                       <option value="Bouvet Island">Bouvet Island</option>
                                       <option value="Brazil">Brazil</option>
                                       <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                       <option value="Brunei Darussalam">Brunei Darussalam</option>
                                       <option value="Bulgaria">Bulgaria</option>
                                       <option value="Burkina Faso">Burkina Faso</option>
                                       <option value="Burundi">Burundi</option>
                                       <option value="Cambodia">Cambodia</option>
                                       <option value="Cameroon">Cameroon</option>
                                       <option value="Canada">Canada</option>
                                       <option value="Cape Verde">Cape Verde</option>
                                       <option value="Cayman Islands">Cayman Islands</option>
                                       <option value="Central African Republic">Central African Republic</option>
                                       <option value="Chad">Chad</option>
                                       <option value="Chile">Chile</option>
                                       <option value="China">China</option>
                                       <option value="Christmas Island">Christmas Island</option>
                                       <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                                       <option value="Colombia">Colombia</option>
                                       <option value="Comoros">Comoros</option>
                                       <option value="Congo">Congo</option>
                                       <option value="Congo">Congo, the Democratic Republic of the</option>
                                       <option value="Cook Islands">Cook Islands</option>
                                       <option value="Costa Rica">Costa Rica</option>
                                       <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                                       <option value="Croatia">Croatia (Hrvatska)</option>
                                       <option value="Cuba">Cuba</option>
                                       <option value="Cyprus">Cyprus</option>
                                       <option value="Czech Republic">Czech Republic</option>
                                       <option value="Denmark">Denmark</option>
                                       <option value="Djibouti">Djibouti</option>
                                       <option value="Dominica">Dominica</option>
                                       <option value="Dominican Republic">Dominican Republic</option>
                                       <option value="East Timor">East Timor</option>
                                       <option value="Ecuador">Ecuador</option>
                                       <option value="Egypt">Egypt</option>
                                       <option value="El Salvador">El Salvador</option>
                                       <option value="Equatorial Guinea">Equatorial Guinea</option>
                                       <option value="Eritrea">Eritrea</option>
                                       <option value="Estonia">Estonia</option>
                                       <option value="Ethiopia">Ethiopia</option>
                                       <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                                       <option value="Faroe Islands">Faroe Islands</option>
                                       <option value="Fiji">Fiji</option>
                                       <option value="Finland">Finland</option>
                                       <option value="France">France</option>
                                       <option value="France Metropolitan">France, Metropolitan</option>
                                       <option value="French Guiana">French Guiana</option>
                                       <option value="French Polynesia">French Polynesia</option>
                                       <option value="French Southern Territories">French Southern Territories</option>
                                       <option value="Gabon">Gabon</option>
                                       <option value="Gambia">Gambia</option>
                                       <option value="Georgia">Georgia</option>
                                       <option value="Germany">Germany</option>
                                       <option value="Ghana">Ghana</option>
                                       <option value="Gibraltar">Gibraltar</option>
                                       <option value="Greece">Greece</option>
                                       <option value="Greenland">Greenland</option>
                                       <option value="Grenada">Grenada</option>
                                       <option value="Guadeloupe">Guadeloupe</option>
                                       <option value="Guam">Guam</option>
                                       <option value="Guatemala">Guatemala</option>
                                       <option value="Guinea">Guinea</option>
                                       <option value="Guinea-Bissau">Guinea-Bissau</option>
                                       <option value="Guyana">Guyana</option>
                                       <option value="Haiti">Haiti</option>
                                       <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                                       <option value="Holy See">Holy See (Vatican City State)</option>
                                       <option value="Honduras">Honduras</option>
                                       <option value="Hong Kong">Hong Kong</option>
                                       <option value="Hungary">Hungary</option>
                                       <option value="Iceland">Iceland</option>
                                       <option value="India" selected >India</option>
                                       <option value="Indonesia">Indonesia</option>
                                       <option value="Iran">Iran (Islamic Republic of)</option>
                                       <option value="Iraq">Iraq</option>
                                       <option value="Ireland">Ireland</option>
                                       <option value="Israel">Israel</option>
                                       <option value="Italy">Italy</option>
                                       <option value="Jamaica">Jamaica</option>
                                       <option value="Japan">Japan</option>
                                       <option value="Jordan">Jordan</option>
                                       <option value="Kazakhstan">Kazakhstan</option>
                                       <option value="Kenya">Kenya</option>
                                       <option value="Kiribati">Kiribati</option>
                                       <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
                                       <option value="Korea">Korea, Republic of</option>
                                       <option value="Kuwait">Kuwait</option>
                                       <option value="Kyrgyzstan">Kyrgyzstan</option>
                                       <option value="Lao">Lao People's Democratic Republic</option>
                                       <option value="Latvia">Latvia</option>
                                       <option value="Lebanon" >Lebanon</option>
                                       <option value="Lesotho">Lesotho</option>
                                       <option value="Liberia">Liberia</option>
                                       <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                       <option value="Liechtenstein">Liechtenstein</option>
                                       <option value="Lithuania">Lithuania</option>
                                       <option value="Luxembourg">Luxembourg</option>
                                       <option value="Macau">Macau</option>
                                       <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                                       <option value="Madagascar">Madagascar</option>
                                       <option value="Malawi">Malawi</option>
                                       <option value="Malaysia">Malaysia</option>
                                       <option value="Maldives">Maldives</option>
                                       <option value="Mali">Mali</option>
                                       <option value="Malta">Malta</option>
                                       <option value="Marshall Islands">Marshall Islands</option>
                                       <option value="Martinique">Martinique</option>
                                       <option value="Mauritania">Mauritania</option>
                                       <option value="Mauritius">Mauritius</option>
                                       <option value="Mayotte">Mayotte</option>
                                       <option value="Mexico">Mexico</option>
                                       <option value="Micronesia">Micronesia, Federated States of</option>
                                       <option value="Moldova">Moldova, Republic of</option>
                                       <option value="Monaco">Monaco</option>
                                       <option value="Mongolia">Mongolia</option>
                                       <option value="Montserrat">Montserrat</option>
                                       <option value="Morocco">Morocco</option>
                                       <option value="Mozambique">Mozambique</option>
                                       <option value="Myanmar">Myanmar</option>
                                       <option value="Namibia">Namibia</option>
                                       <option value="Nauru">Nauru</option>
                                       <option value="Nepal">Nepal</option>
                                       <option value="Netherlands">Netherlands</option>
                                       <option value="Netherlands Antilles">Netherlands Antilles</option>
                                       <option value="New Caledonia">New Caledonia</option>
                                       <option value="New Zealand">New Zealand</option>
                                       <option value="Nicaragua">Nicaragua</option>
                                       <option value="Niger">Niger</option>
                                       <option value="Nigeria">Nigeria</option>
                                       <option value="Niue">Niue</option>
                                       <option value="Norfolk Island">Norfolk Island</option>
                                       <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                       <option value="Norway">Norway</option>
                                       <option value="Oman">Oman</option>
                                       <option value="Pakistan">Pakistan</option>
                                       <option value="Palau">Palau</option>
                                       <option value="Panama">Panama</option>
                                       <option value="Papua New Guinea">Papua New Guinea</option>
                                       <option value="Paraguay">Paraguay</option>
                                       <option value="Peru">Peru</option>
                                       <option value="Philippines">Philippines</option>
                                       <option value="Pitcairn">Pitcairn</option>
                                       <option value="Poland">Poland</option>
                                       <option value="Portugal">Portugal</option>
                                       <option value="Puerto Rico">Puerto Rico</option>
                                       <option value="Qatar">Qatar</option>
                                       <option value="Reunion">Reunion</option>
                                       <option value="Romania">Romania</option>
                                       <option value="Russia">Russian Federation</option>
                                       <option value="Rwanda">Rwanda</option>
                                       <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                       <option value="Saint LUCIA">Saint LUCIA</option>
                                       <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                                       <option value="Samoa">Samoa</option>
                                       <option value="San Marino">San Marino</option>
                                       <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                       <option value="Saudi Arabia">Saudi Arabia</option>
                                       <option value="Senegal">Senegal</option>
                                       <option value="Seychelles">Seychelles</option>
                                       <option value="Sierra">Sierra Leone</option>
                                       <option value="Singapore">Singapore</option>
                                       <option value="Slovakia">Slovakia (Slovak Republic)</option>
                                       <option value="Slovenia">Slovenia</option>
                                       <option value="Solomon Islands">Solomon Islands</option>
                                       <option value="Somalia">Somalia</option>
                                       <option value="South Africa">South Africa</option>
                                       <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                                       <option value="Span">Spain</option>
                                       <option value="SriLanka">Sri Lanka</option>
                                       <option value="St. Helena">St. Helena</option>
                                       <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                                       <option value="Sudan">Sudan</option>
                                       <option value="Suriname">Suriname</option>
                                       <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                                       <option value="Swaziland">Swaziland</option>
                                       <option value="Sweden">Sweden</option>
                                       <option value="Switzerland">Switzerland</option>
                                       <option value="Syria">Syrian Arab Republic</option>
                                       <option value="Taiwan">Taiwan, Province of China</option>
                                       <option value="Tajikistan">Tajikistan</option>
                                       <option value="Tanzania">Tanzania, United Republic of</option>
                                       <option value="Thailand">Thailand</option>
                                       <option value="Togo">Togo</option>
                                       <option value="Tokelau">Tokelau</option>
                                       <option value="Tonga">Tonga</option>
                                       <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                       <option value="Tunisia">Tunisia</option>
                                       <option value="Turkey">Turkey</option>
                                       <option value="Turkmenistan">Turkmenistan</option>
                                       <option value="Turks and Caicos">Turks and Caicos Islands</option>
                                       <option value="Tuvalu">Tuvalu</option>
                                       <option value="Uganda">Uganda</option>
                                       <option value="Ukraine">Ukraine</option>
                                       <option value="United Arab Emirates">United Arab Emirates</option>
                                       <option value="United Kingdom">United Kingdom</option>
                                       <option value="United States">United States</option>
                                       <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                       <option value="Uruguay">Uruguay</option>
                                       <option value="Uzbekistan">Uzbekistan</option>
                                       <option value="Vanuatu">Vanuatu</option>
                                       <option value="Venezuela">Venezuela</option>
                                       <option value="Vietnam">Viet Nam</option>
                                       <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                                       <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                                       <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                                       <option value="Western Sahara">Western Sahara</option>
                                       <option value="Yemen">Yemen</option>
                                       <option value="Serbia">Serbia</option>
                                       <option value="Zambia">Zambia</option>
                                       <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                    <label class="fas fa-globe-americas"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
                              document.getElementById('country').addEventListener('change', function() {
                                  var countryValue = this.value;
                                  document.getElementById('c_country').value = countryValue;
                              });
                           </script>
                           <div class="col-md-12 mb-3">
                              <div class="form-floating with-icon">
                                 <textarea class="form-control"
                                    name="address"     placeholder="<?php echo display("address") ?>" id="address"></textarea>
                                 <label for="address"><?php echo display("address") ?></label>
                                 <i class="fas fa-map-pin"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <script>
                  document.getElementById('address').addEventListener('keyup', function() {
                      var firstaddValue = this.value;
                      document.getElementById('c_address').value = firstaddValue;
                  });
               </script>
               <div class="col-md-6 d-flex">
                  <div class="card flex-fill w-100 border mb-4 mb-md-0">
                     <div class="card-header py-3">
                        <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("photo_id_details") ?></h6>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="pitype"><?php echo display("photo_id_type") ?><span  class="text-danger">*</span></label>
                                 <div class="icon-addon addon-md">
                                    <select name="id_type" id="pitype "   required   class="form-control" >
                                       <option value="Driving Licence">Driving Licence </option>
                                       <option value="Passport">Passport</option>
                                       <option value="Aadhar Card">Aadhar Card</option>
                                       <option value="PAN Card">PAN Card</option>
                                       <!-- Add more options as needed -->
                                    </select>
                                    <label class="fas fa-images"></label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="form-group mb-0">
                                 <label for="pid"><?php echo display("photo_id") ?> # <span
                                    class="text-danger">*</span></label>
                                 <div class="icon-addon addon-md">
                                    <input type="text"  name="national_id" class="form-control" id="pid"
                                       placeholder="<?php echo display("photo_id") ?>"  required >
                                    <label class="fas fa-images"></label>
                                 </div>
                              </div>
                           </div>
                           <script>
document.getElementById('pid').addEventListener('input', function (e) {
    e.target.value = e.target.value.toUpperCase();
     if (e.target.value.length > 15) {
        e.target.value = e.target.value.slice(0, 15);
    }
});
</script>
                           <div class="col-md-12">
                              <label><?php echo display("photo_id_upload") ?></label>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="image-upload position-relative overflow-hidden m-auto">
                                 <input type="file" id="imgfront" onchange="fileValueOne(this)">
                                 <input type="hidden" id="imgffront">
                                 <label for="imgfront" class="upload-field mb-0" id="file-label">
                                 <span class="file-thumbnail">
                                 <span
                                    class="d-block text-center mb-2"><?php echo display("front_side") ?></span>
                                 <img id="image-preview"
                                    src="<?php echo base_url()?>/assets/img/proof_icon.png" alt="">
                                 <span id="filename"
                                    class="d-block mt-2"><?php echo display("drag_and_drop") ?></span>
                                 <span class="format"><?php echo display("supports_image") ?></span>
                                 </span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-6 mb-3">
                              <div class="image-upload position-relative overflow-hidden m-auto">
                                 <input type="file" id="imgback" onchange="fileValuesTwo(this)">
                                 <input type="hidden" id="imgbback">
                                 <label for="imgback" class="upload-field mb-0" id="file-label">
                                 <span class="file-thumbnail">
                                 <span
                                    class="d-block text-center mb-2"><?php echo display("back_side") ?></span>
                                 <img id="image-preview2"
                                    src="<?php echo base_url()?>assets/img/proof_icon.png" alt="">
                                 <span id="filename2"
                                    class="d-block mt-2"><?php echo display("drag_and_drop") ?></span>
                                 <span class="format"><?php echo display("supports_image") ?></span>
                                 </span>
                                 </label>
                              </div>
                           </div>
                           <div class="col-md-12 mb-3">
                              <div class="form-floating with-icon">
                                 <textarea class="form-control" placeholder="Remarks"
                                    id="comments"></textarea>
                                 <i class="far fa-comment-dots"></i>
                                 <label for="comments"><?php echo display("comments") ?></label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6 d-flex">
                  <div class="card flex-fill w-100 border">
                     <div class="card-header py-3">
                        <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("guest_image") ?></h6>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-8 col-lg-6 mb-3">
                              <div class="image-upload position-relative overflow-hidden m-auto">
                                 <input type="file" id="imgguest" onchange="fileValuesThree(this)">
                                 <input type="hidden" id="imggguest">
                                 <label for="imgguest" class="upload-field mb-0" id="file-label">
                                 <span class="file-thumbnail">
                                 <span
                                    class="d-block text-center mb-2"><?php echo display("occupant_image") ?></span>
                                 <img id="image-preview3"
                                    src="<?php echo base_url()?>/assets/img/user.png" alt="">
                                 <span id="filename3"
                                    class="d-block mt-2"><?php echo display("drag_and_drop") ?></span>
                                 <span class="format"><?php echo display("supports_image") ?></span>
                                 </span>
                                 </label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <script>
            // Function to hide content
            function hideContent() {
                var contentToToggle = document.getElementById('contentToToggle');
                if (contentToToggle) {
                    contentToToggle.style.display = 'none';
                }
            }
            
            // Add click event listener to the button
            document.getElementById('custdetailbtn-1').addEventListener('click', hideContent);
            
            // Ensure that nationality and contentToToggle elements exist
            var nationality = document.getElementById('nationality');
            var contentToToggle = document.getElementById('contentToToggle');
            if (!nationality || !contentToToggle) {
                console.error("Elements 'nationality' or 'contentToToggle' not found.");
            }
            
            // Add change event listener to nationality select
            nationality.addEventListener('change', function() {
                // Ensure contentToToggle element exists
                if (!contentToToggle) {
                    console.error("Element 'contentToToggle' not found.");
                    return;
                }
            
                // Toggle content based on nationality value
                if (this.value.toLowerCase() === 'ind' || this.value.toLowerCase() === 'india') {
                    contentToToggle.style.display = 'none';
                    var cFullNameInput = document.getElementById('c_full_name');
                    if (cFullNameInput) {
                        cFullNameInput.removeAttribute('required');
                    }
                } else {
                    contentToToggle.style.display = 'block';
                    var cFullNameInput = document.getElementById('c_full_name');
                    if (cFullNameInput) {
                        cFullNameInput.setAttribute('required', 'required');
                    }
                }
            });
         </script>
         <!-- Start -->
         <div  id="contentToToggle"  style="border:1px solid;margin-left: 16px;margin-top: 13px;    width: 1177px;" >
            <div class="card-header py-3">
               <h6 class="fs-17 font-weight-600 mb-0" style="text-align:center;" >GUEST REGISTRATION FORM</h6>
            </div>
            <div style="margin-left: 35px;">
               <img style='margin-left:10px;width:80px;'src="<?php echo base_url();?><?php echo (file_exists($comsettinginfo->invoice_logo)?$comsettinginfo->invoice_logo:'application/modules/ordermanage/assets/images/dmi_logo.jpeg')?>"
                  class="img img-responsive height-mb" alt=""   >
               <address class="mt-10"   style="margin-left:104px;margin-top: -76px  !important;font-size: smaller;">
                  <strong>St. Thomas Mount International Piligrime Centre <strong style="margin-left:516px;">
                  <th>Reservation No : 
                     <input type="text"  name="c_reservation"  id="c_reservation"   style="width:141px; margin-bottom: 7px;margin-left: 5px;" >
                  </th>
                  <br>
                  <abbr>
                     <strong><?php echo ('Hill Top , St.Thomas Mount , Chennai - 600016') ?></strong style="margin-left:132PX;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                     <th>Registration No : <input type="text" name="c_reg"  id="c_reg"   style="width:140px;margin-left:2px; margin-bottom: 7px;"></th>
                  </abbr>
                  <br>
                  <abbr>
                     <strong><?php echo ('Tamilnadu , India.') ?></strong style="margin-left:160PX;"> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;  
                     <th>Room  No : <input type="text"  name="c_roomno" id="c_roomno"  style="width:141px;    margin-left: 39px; margin-bottom: 7px;" ></th>
                  </abbr>
                  <br>
               </address>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <table class="c-table" role="table" aria-label="Students" style="margin-top:-60px;margin-left: 31px;" >
                        <thead>
                           <tr>
                              <td>FullName :</td>
                              <td colspan="9" style="border-left: none;"><input type="text" name="c_full_name"   id="c_full_name" style="width:1012px;  margin-left:-15px;border: none;background-color: aliceblue;" >  </td>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td colspan="10">Company / Travel Agent Name :<input type="text" name="c_company_taname"  id="c_company_taname"   style="border: none;margin-left: 10px;width: 79%;background-color: aliceblue;" ></td>
                           </tr>
                           <tr>
                              <td> Address :</td>
                              <td colspan="9" style="border-left: none;"><input type="text" name="c_address"  id="c_address"    style="width:101%;border: none;background-color: aliceblue;margin-left: -3%;" >  </td>
                           </tr>
                           <tr>
                              <td colspan="6"> </td>
                              <td> Pincode</td>
                              <td colspan="3"><input type="text"  name="c_pincode"  id="c_pincode"  style="border: none;background-color: aliceblue;width: 169px;" ></td>
                           </tr>
                           <tr>
                              <td>Res /Off :</td>
                              <td style="border-left: none" > <input type="text" name="c_res_off"  id="c_res_off" style="border: none; margin-left: -28px;background-color: aliceblue;" ></td>
                              <td>Mobile :</td>
                              <td style="border-left: none" > <input type="number" name="c_moblie"   id="c_moblie"   style="margin-left: -8%;border: none;background-color: aliceblue;" ></td>
                              <td>Res :</td>
                              <td colspan="2"style="border-left: none" > <input type="text" name="c_res"  id="c_res"  style="margin-left: -8%;border: none;background-color: aliceblue;width: 106%; " ></td>
                              <td  >DOB :</td>
                              <td colspan="3" style="border-left: none"><input type="text" name="c_dob" id="c_dob"  style="margin-left: -25%;border: none;    width: 128%;background-color: aliceblue;" > </td>
                           </tr>
                           <tr>
                              <td colspan="10"> Mail ID :<input type="email" name="c_email"  id="c_email" oninput="validateEmail(this)"  style="width:360px;border: none;background-color: aliceblue;" > Vehicle No :<input type="text"  name="c_vehicleno"  id="c_vehicleno" style="width:592px;border: none;background-color: aliceblue;" ><span id="validateemails" style="margin-top: 10px;"></span>
                              </td>
                           </tr>
                           <tr>
                              <td>Nationality:</td>
                              <td style="border-left: none;" ><input type="text" name="c_nationality"  id="c_nationality"  style=" border: none;background-color: aliceblue;    width: 146px;    margin-left: -14px;" > </td>
                              <td   colspan="2" >No of Person :</td>
                              <td style="border-left: none;" ><input type="text" name="c_noofperson"  id="c_noofperson" style= "background-color: aliceblue;    border: none;width: 350%;margin-left: -159px;" > </td>
                              <td>Adults :</td>
                              <td style="border-left: none;" > <input type="text" name="c_adults"  id="c_adults"   style="width: 100%;border: none; margin-left: -15%;background-color: aliceblue;"> </td>
                              <td>Children :</td>
                              <td  colspan="2" style="border-left: none;" ><input type="text"  name="c_children" id="c_children"   style="width: 125px;border: none; margin-left: -15px;background-color: aliceblue;"> </td>
                           </tr>
                           <tr>
                              <td  colspan="2" >Arrival Date : <input type="date" name="c_arrival"  id="c_arrival"  style="width:132px;border: none;background-color: aliceblue;" ></td>
                              <td colspan="2"> Time : <input type="text" name="c_atime"  id="c_atime"  placeholder="HH:MM AM/PM"  style="width:170px;border: none;background-color: aliceblue;" ></td>
                              <td colspan="3">Departure Date : <input type="date" name="c_departutedate"  id="c_departutedate"   style="width:165px;border: none;background-color: aliceblue;" ></td>
                              <td  colspan="3" >Time : <input type="text" name="c_dtime"   id="c_dtime"  placeholder="HH:MM AM/PM"  style="width:155px;border: none;background-color: aliceblue;" ></td>
                           </tr>
                           <tr>
                              <td colspan="3" >Arrived Form : <input type="text" name="c_aform" id="c_aform"  style="width:66%;border: none;background-color: aliceblue;" ></td>
                              <td colspan="3" style="border-left: none;" > Purpose of Visit : <input type="text" id="c_pov" name="c_pov"  style="width:59%;border: none;background-color: aliceblue;" ></td>
                              <td  colspan="4"  style="border-left: none;" >Proceeding To : <input type="text" id="c_proceedingto"  name="c_proceedingto"  style="width:234px;border: none;background-color: aliceblue;" > </td>
                           </tr>
                           <tr>
                              <td style="border-right: none;">Amount :</td>
                              <td style="border-left: none;"> <input type="text"  name="c_amount"  id="c_amount"  style="width:160px;border: none;background-color: aliceblue;    margin-left: -25px;" > </td>
                              <td colspan="2" style="border-left: none;">Mode of Payment : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cash <input type="checkbox" class="mode-of-payment" id="modeofpayment"   name="c_cash"  style="width: 40px;height: 20px;" > </td>
                              <td colspan="2" style="border-left: none;">Credit Card <input type="checkbox" class="mode-of-payment" name="c_credit" id="modeofpayment"  style="width: 40px;height: 20px;" >  </td>
                              <td colspan="2"  style="border-left: none;">Company <input type="checkbox" class="mode-of-payment" name="c_company"  id="modeofpayment"   style="width: 40px;height: 20px;"  >  </td>
                              <td colspan="2"  style="border-left: none;">  Travel Agent <input type="checkbox" class="mode-of-payment" name="c_travel" id="modeofpayment"    style="width: 40px;height: 20px;" ></td>
                           </tr>
                           <script>
                              // Add an event listener to each checkbox
                              document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                                  checkbox.addEventListener('change', function() {
                                      // Set the value of a hidden input based on the checked state of the checkboxes
                                      document.querySelector('input[name="mode_of_payment"]').value = this.checked ? this.value : '';
                                  });
                              });
                           </script>
                           <tr >
                              <td colspan="10"   >
                                 <span style="margin-left: 470px;">  FOR FOREIGNERS ONLY  </span><br> <span style="margin-left: 500px;" >Form 'c' Rule 14</span>
                                 <br>
                                 <!-- <span>Passport No : </span> --> 
                                 <span>Passport No : </span> <input name="c_passport" id="c_passport"  type="text">  &nbsp; <span  style="margin-left: 501px;" >Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <input type="text" name="c_country" id="c_country"   style="margin-left:3px;width:166px;" > </span>
                                 <br>
                                 <br>
                                 <span>Issued No  &nbsp;&nbsp;&nbsp;&nbsp;: </span> &nbsp;<input type="text" name="c_issued"  id="c_issued"   style="margin-left:-2px;" >  &nbsp; <span style="margin-left: 501px;">Place of Issues&nbsp;&nbsp;:<input type="text"  name="c_poi"  id="c_poi"  style="margin-left: 9px;">  </span>
                                 <br>
                                 <br>
                                 <span>Vaild Till  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span>&nbsp;<input type="text" name="c_vaild" id="c_vaild"  style="margin-left:-1px;">  &nbsp; <span style="margin-left: 501px;">Visa Valid Till&nbsp;&nbsp;&nbsp;&nbsp;: <input  name="visa_validtill" id="visa_validtill"  style="margin-left: 3px;
                                    " type="text"> </span>
                                 <br>
                                 <br>
                                 <span>Proposed Departure from India  : </span> <input type="text" name="c_pdfi" id="c_pdfi"  >  &nbsp;
                                 <br>
                                 <br>
                                 <!-- <span>Whether Empolyed in India  : </span><input type="text" name="c_weii" >   &nbsp;   -->
                                 <span>Whether Employed in India:</span>
                                 <label><input type="radio" name="c_weii" id="c_weii"  value="Yes"> Yes</label>
                                 <label><input type="radio" name="c_weii"  id="c_weii"  value="No"> No</label>
                                 &nbsp;
                                 <br>
                                 <br>
                                 <span style="margin-left: 25px;" > Signature of the Office      </span> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; <span style="margin-left:556px;"> i agree to abide by the rules of the Hotel <br><span style="margin-left: 787px;"> and Promise vacate as mentioned above   </span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     <style>
                        .c-table {
                        width: 100%;
                        border-collapse: collapse;
                        border-bottom: 2px solid #333;
                        border-right: 2px solid #333;
                        caption {}
                        th,
                        td {
                        vertical-align: top;
                        border-top: 2px solid #333;
                        border-left: 2px solid #333;
                        padding: 8px;
                        }
                        thead {
                        th {
                        /* background: #000; */
                        /* color: #fff; */
                        }
                        }
                        tbody {
                        th,
                        td {}
                        th {}
                        td {}
                        }
                        }
                     </style>
                     <script>
                        function addInputListenerToElement(elementId) {
                            document.getElementById(elementId).addEventListener('input', function() {
                                var inputValue = this.value;
                                var regex = /^[a-zA-Z]*$/;
                                if (!regex.test(inputValue)) {
                                    this.value = inputValue.slice(0, -1);
                                }
                            });
                        }
                        // Add input listeners to multiple elements
                        addInputListenerToElement('c_full_name');
                        addInputListenerToElement('c_company_taname');
                        addInputListenerToElement('c_nationality');
                        addInputListenerToElement('c_proceedingto');
                        addInputListenerToElement('c_pov');
                        addInputListenerToElement('c_aform');
                        addInputListenerToElement('c_poi');
                        addInputListenerToElement('c_country');    
                        addInputListenerToElement('c_pdfi');    
                        
                     </script>
                     <script>
                        function addInputListenerToElement(elementId) {
                                document.getElementById(elementId).addEventListener('input', function() {
                                    var inputValue = this.value;
                                    // var regex = /^[0-9]*$/; // Only allow numeric characters
                                    var regex = /^[0-9\/\-\.]*$/; // Allow numeric characters, /, -, and .
                                    if (!regex.test(inputValue)) {
                                        this.value = inputValue.slice(0, -1);
                                    }
                                });
                            }
                        
                            // Add input listener to the 'pincode' element
                            addInputListenerToElement('c_pincode');
                            addInputListenerToElement('c_moblie');
                            addInputListenerToElement('c_dob');
                            addInputListenerToElement('c_noofperson');
                            addInputListenerToElement('c_adults');
                            addInputListenerToElement('c_children');
                            addInputListenerToElement('c_arrival');
                            addInputListenerToElement('c_atime');
                            addInputListenerToElement('c_departutedate');
                            addInputListenerToElement('c_dtime');
                            addInputListenerToElement('c_amount');
                            addInputListenerToElement('c_reservation');
                            addInputListenerToElement('c_reg');
                            addInputListenerToElement('c_roomno');
                            addInputListenerToElement('c_res');
                            addInputListenerToElement('c_res_off');
                            addInputListenerToElement('c_vaild');
                            addInputListenerToElement('c_issued');
                            addInputListenerToElement('c_passport');
                            addInputListenerToElement('visa_validtill');
                            addInputListenerToElement('zipcode');
                        
                             
                        // Validate Email
                        function validateEmail(input) {
                                 var regex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
                                 var submitButton = document.getElementById("checkSubmit");
                                  input.addEventListener("input", function(event) {
                                      var value = input.value;
                                      var newValue = '';
                                      for (var i = 0; i < value.length; i++) {
                                          var char = value.charAt(i);
                                          if (/[@._A-Za-z0-9-]/.test(char) || event.shiftKey) {
                                              newValue += char;
                                          }
                                      }
                                      input.value = newValue;
                                      var isValid = regex.test(input.value);
                                      if (isValid) {
                                          // Check if there are additional characters after .com or .in
                                          var lastPart = input.value.split('.').pop();
                                          if (lastPart !== 'com' && lastPart !== 'in') {
                                              isValid = false;
                                          }
                                      }
                                      if (isValid) {
                                          document.getElementById("validateemails").style.color = "green";
                                          document.getElementById("validateemails").textContent = "Valid email address";
                                          submitButton.disabled = false;
                                      } else {
                                          document.getElementById("validateemails").style.color = "red";
                                          document.getElementById("validateemails").textContent = "Invalid email address";
                                          submitButton.disabled = true;
                                      }
                                  });
                              }
                        
                            
                     </script>
                  </div>
               </div>
            </div>
         </div>
         <!-- End -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-dismiss="modal"><?php echo display("close") ?></button>
                <button type="button" disabled class="btn btn-primary"
                    id="addcustomer"><?php echo display("ad") ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo display("old_customer") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-floating with-icon mb-3">
                    <input type="number" class="form-control" id="existmobile"
                        placeholder="<?php echo display("mobile_no") ?>" value="">
                    <label><?php echo display("mobile_no") ?></label>
                    <i class="fas fa-mobile"></i>
                </div>
                <ul id="searchResult"></ul>
                <div class="clear"></div>
                <div class="form-floating with-icon">
                    <input type="text" class="form-control" id="existname"
                        placeholder="<?php echo display("customer_name") ?>" disabled value="">
                    <input type="hidden" class="form-control" id="existcustid" placeholder="Customer Name" disabled
                        value="">
                    <label><?php echo display("customer") ?></label>
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="modal-footer py-2 px-4">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo display("close") ?></button>
                <button type="button" disabled id="addoldcustomer"
                    class="btn btn-primary"><?php echo display("ad") ?></button>
            </div>
        </div>
    </div>
</div>
</div>
<div id="roomtlist" hidden>
    <?php foreach($roomdetails as $btype){ ?>
    <option value="<?php echo html_escape($btype->roomid); ?>"><?php echo html_escape($btype->roomtype);?></option>
    <?php } ?>
</div>
<input type="hidden" id="alluser"><input type="hidden" id="allmobile"><input type="hidden" id="allemail"><input
    type="hidden" id="allpitype"><input type="hidden" id="alluserid">
<input type="hidden" id="alllastname"><input type="hidden" id="allgender"><input type="hidden" id="allfather"><input
    type="hidden" id="allpid">
<input type="hidden" id="alloccupation"><input type="hidden" id="alldob"><input type="hidden" id="allanniversary"><input
    type="hidden" id="allimgfront">
<input type="hidden" id="allnationality"><input type="hidden" id="allvip"><input type="hidden" id="allcomments"><input
    type="hidden" id="allimgback">
<input type="hidden" id="allimgguest"><input type="hidden" id="allcontacttype"><input type="hidden" id="allstate"><input
    type="hidden" id="allcity">
<input type="hidden" id="allzipcode"><input type="hidden" id="alladdress"><input type="hidden" id="allcountry">
<input type="hidden" id="intime"
    value="<?php echo date("d-m-Y"); ?> <?php echo html_escape($inouttime->checkintime); ?>">
<input type="hidden" id="outtime"
    value="<?php echo date("d-m-Y"); ?> <?php echo html_escape($inouttime->checkouttime); ?>">
<input type="hidden" id="finyear" value="<?php echo financial_year(); ?>"><input type="hidden" id="findate"
    value="<?php echo maxfindate(); ?>">
    <?php 
    $taxPercent = 0;
    if(!empty($taxsetting)){
        foreach($taxsetting as $tax){
            // $taxPercent += $tax->rate;
            $cgst = $tax->rate;
            $sgst = $tax->rate;
        }
    }
    ?>
<!-- <input type="hidden" id="tax_percent" value="<?php echo $taxPercent; ?>"> -->
<input type="hidden" id="cgst" value="<?php echo $cgst; ?>">
<input type="hidden" id="sgst" value="<?php echo $sgst; ?>">
<input type="hidden" id="service_percent" value="<?php echo $setting->servicecharge; ?>">
<script src="<?php echo MOD_URL.$module;?>/assets/js/editreservation.js"></script>
<script src="<?php echo MOD_URL.$module;?>/assets/js/customedit.js"></script>
<script src="<?php echo MOD_URL.$module;?>/assets/js/bookingedit.js"></script>
<script>
$(document).ready(function() {
    debugger;
   // "use strict";
//  var all = $("table.room-list > tbody tr").length;
//     var baseRent = parseFloat($("#rent-1").val()) || 0;
//     for (var s = 0; s <= all; s++) {
//         var rentValue = parseFloat($("#rent" + s).val()) || 0;
//         console.log("#rent" + s);
//           console.log("rent :"+rentValue);
//         if (!isNaN(rentValue)) {
//             baseRent += rentValue;
//         }
//     }
//     $("#booking_charge").text(baseRent.toFixed(2));

    var all = $("table.room-list > tbody tr").length;
        var baseRent = parseFloat($("#rent-1").val()) || 0;
        var all_bed = parseFloat($("#amount1-1").val()) || 0;
        var all_person = parseFloat($("#amount2-1").val()) || 0;
        var all_child = parseFloat($("#amount3-1").val()) || 0;
        var totalCGSTPercentage = parseFloat($("#cgst").val()) || 0;
        var totalSGSTPercentage = parseFloat($("#sgst").val()) || 0;
        var alltotal = parseFloat($(".get_total").val()) || 0;
    
        for (var s = 0; s <= all; s++) {
            var rentValue = parseFloat($("#rent" + s).val()) || 0;
            if (!isNaN(rentValue)) {
                baseRent += rentValue;
            }
        }
        for (var s = 0; s <= all; s++) {
            var rentValue = parseFloat($("#amount1" + s).val()) || 0;
            if (!isNaN(rentValue)) {
                all_bed += rentValue;
            }
        }
        for (var s = 0; s <= all; s++) {
            var rentValue = parseFloat($("#amount2" + s).val()) || 0;
            if (!isNaN(rentValue)) {
                all_person += rentValue;
            }
        }
        for (var s = 0; s <= all; s++) {
            var rentValue = parseFloat($("#amount3" + s).val()) || 0;
            if (!isNaN(rentValue)) {
                all_child += rentValue;
            }
        }
        var totalCGST = (totalCGSTPercentage * (baseRent+all_bed+all_person+all_child)) / 100;
        var totalSGST = (totalSGSTPercentage * (baseRent+all_bed+all_person+all_child)) / 100;
    
        // var grandTotal = baseRent + totalCGST + totalSGST + alltotal;
        var grandTotal = (baseRent+all_bed+all_person+all_child) + totalCGST + totalSGST;
      var discount=$('#discountrate').val();
    grandTotal=parseFloat(grandTotal-discount);
        $("#totalamount").val(grandTotal.toFixed(2));
        $("#booking_charge").text((baseRent+all_bed+all_person+all_child).toFixed(2));
        $("#cgst_charge").text(totalCGST.toFixed(2));
        $("#sgst_charge").text(totalSGST.toFixed(2));
        $(".cgst_charge").val(totalCGST.toFixed(2));
        $(".sgst_charge").val(totalSGST.toFixed(2));
        $("#total_charge").text(grandTotal.toFixed(2));
        $('#get_total').val(grandTotal.toFixed(2));
});



$("#paymentmode").on('change', function(){
        var paymentmode = $("#paymentmode").find(":selected").val();

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
         document.addEventListener('DOMContentLoaded', function() {
    var nationality = document.getElementById('nationality');
    var contentToToggle = document.getElementById('contentToToggle');
    var cFullNameInput = document.getElementById('c_full_name'); // Moved this declaration outside the event listener
    
    // Check if all necessary elements are found
    if (!nationality || !contentToToggle || !cFullNameInput) return;
    
    // Initial state
    contentToToggle.style.display = 'none'; // Hide the element by default
    
    // Event listener for nationality change
    nationality.addEventListener('change', function() {
        if (this.value.toLowerCase() === 'ind' || this.value.toLowerCase() === 'india' || this.value.toLowerCase() === 'indian') {
            contentToToggle.style.display = 'none';
            cFullNameInput.removeAttribute('required');
        } else {
            contentToToggle.style.display = 'block';
            cFullNameInput.setAttribute('required', 'required');
        }
    });
});
//  $(document).ready(function(){
//     var paymentModes = "<?php echo $bookingdata->paymenttype; ?>";
//     var paymentvalues = "<?php echo $bookingdata->details; ?>";
//     console.log(paymentvalues, "paymentvalues");
//     if(paymentModes== "Card Payment"){
//         paymentvalues=paymentvalues.replace("Advance in Card Number: ", "");
//         var cardvalues = paymentvalues.split("-");
//         console.log(cardvalues, "cardvalues");
//     }else if(paymentModes== "RTGS"){
//         var parts = paymentvalues.split("Advance in RTGS Reference No: ");
//     }else if(paymentModes== "UPI"){
//         var upiparts = paymentvalues.split("Advance in UPI Reference No: ");
//         console.log(upiparts, "upiparts");
//     }else if(paymentModes== "Cheque"){
//          paymentvalues=paymentvalues.replace("Advance in Cheque: ", "");
//         var chequeparts = paymentvalues.split("/");
     
//         console.log(chequeparts, "chequeparts");
//     }else if(paymentModes== "Online Payment"){
//         var onlinevalues = paymentvalues.split("Advance in Online Payment Reference No: ");
//         console.log(onlinevalues, "onlinevalues");
//     }
//     // RTGS
//     if(paymentModes == 'RTGS'){
//         $("#rtgs_referenceno").attr("disabled", false);
//         $("#rtgsreferencediv").attr("hidden", false);
//         $("#rtgs_referenceno").val(parts[1]);
//     }else{
//         $("#rtgs_referenceno").attr("disabled", true);
//         $("#rtgsreferencediv").attr("hidden", true);
//     }
//     // Card Payment
//     if(paymentModes== "Card Payment"){
//         $("#cardno").attr("disabled", false);
//         $("#expirydate").attr("disabled", false);
//         $("#ccv").attr("disabled", false);
//         $("#cardexpirediv").attr("hidden", false);
//         $("#cardcvvdiv").attr("hidden", false);
//         $("#carddiv").attr("hidden", false);
//         $("#cardno").val(cardvalues[0]);
//         $("#expirydate").val(cardvalues[1]);
//         $("#ccv").val(cardvalues[2]);
//     }else{
//         $("#cardno").attr("disabled", true);
//         $("#expirydate").attr("disabled", true);
//         $("#ccv").attr("disabled", true);
//         $("#cardexpirediv").attr("hidden", true);
//         $("#cardcvvdiv").attr("hidden", true);
//         $("#carddiv").attr("hidden", true);
//     }
//     // UPI
//     if(paymentModes=="UPI"){
//         $("#upi_referenceno").attr("disabled", false);
//         $("#upireferencediv").attr("hidden", false);
//         $("#upi_referenceno").val(upiparts[1]);
//     }else{
//         $("#upi_referenceno").attr("disabled", true);
//         $("#upireferencediv").attr("hidden", true);
//     }
//     // Cheque
//     if(paymentModes=="Cheque"){
//         $("#cheque_no").attr("disabled", false);
//         $("#cheque_date").attr("disabled", false);
//         $("#chequenodiv").attr("hidden", false);
//         $("#chequedatediv").attr("hidden", false);
//         $("#cheque_no").val(chequeparts[0]);
//         $("#cheque_date").val(chequeparts[1]);
//     }else{
//         $("#cheque_no").attr("disabled", true);
//         $("#cheque_date").attr("disabled", true);
//         $("#chequenodiv").attr("hidden", true);
//         $("#chequedatediv").attr("hidden", true);
//     }
//     // Online Payment
//     if(paymentModes=="Online Payment"){
//         $("#online_referenceno").attr("disabled", false);
//         $("#onlinereferencediv").attr("hidden", false);
//         $("#online_referenceno").val(onlinevalues[1]);
//     }else{
//         $("#online_referenceno").attr("disabled", true);
//         $("#onlinereferencediv").attr("hidden", true);
//     }
// });
</script>
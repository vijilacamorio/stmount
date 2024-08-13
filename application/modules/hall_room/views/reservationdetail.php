<div class="card">
    <div class="card-body">
        <form>
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <h2 class="font-weight-600"><?php echo display('booking_information') ?></h2>
                    </div>
                </div>
            </div>
            <?php 
                $singleRoom = explode(",", $bookinginfo->roomid);
                $roomname="";
                for($i=0; $i<count($singleRoom); $i++){
                    $roominfo=$this->db->select("roomtype")->from('roomdetails')->where('roomid',$singleRoom[$i])->get()->row();
                    $roomname.=$roominfo->roomtype.",";
                }
                
             ?>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('booking_number') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->invoice_no);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo ('Hall Type') ?></label>
                        <div class=""><?php echo html_escape($htyp[0]->hall_type);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                       <label class="font-weight-600"><?php echo display('checkin') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->start_date);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                      <label class="font-weight-600"><?php echo display('checkout') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->end_date);?></div>
                    </div>
                </div>
            </div>
            <!--<div class="row">-->
            <!--    <div class="col-md-3 pr-md-1">-->
            <!--        <div class="form-group">-->
            <!--            <label class="font-weight-600"><?php echo display('checkin') ?></label>-->
            <!--            <div class=""><?php echo html_escape($bookinginfo->checkindate);?></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="col-md-3 pr-md-1">-->
            <!--        <div class="form-group">-->
            <!--            <label class="font-weight-600"><?php echo display('checkout') ?></label>-->
            <!--            <div class=""><?php echo html_escape($bookinginfo->checkoutdate);?></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="col-md-3 pr-md-1">-->
            <!--        <div class="form-group">-->
            <!--            <label class="font-weight-600"><?php echo display('children') ?></label>-->
            <!--            <div class=""><?php echo html_escape($bookinginfo->children);?></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="col-md-3 pr-md-1">-->
            <!--        <div class="form-group">-->
            <!--            <label class="font-weight-600"><?php echo display('room_no') ?></label>-->
            <!--            <div class=""><?php echo html_escape($bookinginfo->room_no);?></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('room_rate') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->rent);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('paid_amount') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->paid_amount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('discount') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->special_discount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo ("Additional Charge") ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->additional_charges);?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-md-3 pr-md-1">-->
                <!--    <div class="form-group">-->
                <!--        <label class="font-weight-600"><?php echo display("total_complementary_price") ?></label>-->
                <!--        <div class=""><?php echo html_escape($bookinginfo->totalComplementary);?></div>-->
                <!--    </div>-->
                <!--</div>-->
                <?php 
                    $instart = strtotime($bookinginfo->checkindate);
                    $inend = strtotime($bookinginfo->checkoutdate);
                    $indatediff = $inend - $instart;
                    $indays = ceil($indatediff / (60 * 60 * 24));
                    if($bookinginfo->bookingstatus==0 & $bookinginfo->booked_from==1){
                        $indays=1;
                    }
                ?>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('total_price') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->totalamount);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('full_guest_name') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->firstname." ".$bookinginfo->lastname);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('special_request') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->special_request);?></div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <h2 class="font-weight-600"><?php echo display('customer_information') ?></h2>
                    </div>
                </div>
            </div>
            <?php $userinfo=$this->db->select("*")->from('customerinfo')->where('customerid',$bookinginfo->cutomerid)->get()->row(); ?>
            <div class="row">
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('account_name') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->firstname." ".$bookinginfo->lastname);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('email') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->email);?></div>
                    </div>
                </div>
                <div class="col-md-3 r-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('address') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->address);?></div>
                    </div>
                </div>
                <div class="col-md-3 pr-md-1">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('phone') ?></label>
                        <div class=""><?php echo html_escape($bookinginfo->cust_phone);?></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
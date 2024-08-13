<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">

            <div class="card-body">
                <?php echo form_open('hall_room/hallroom/cancelbooking');?>
                <?php echo form_hidden('bookedid', (!empty($bookedid)?$bookedid:null)) ?>
                <div class="form-group row">
                    <label for="cancelreason" class="col-sm-4 col-form-label"><?php echo display("cancel")." ".display("reason"); ?> <span
                            class="text-danger">*</span></label>
                    <input class="col-sm-8" required name="cancelreason" type="text" placeholder="<?php echo display("cancel")." ".display("reason"); ?>"
                        value="">
                </div>
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label"><?php echo display('payment_method') ?> </label>
                    <select class="col-sm-8 form-select selectpicker" name="pmethod" id="paymentmode"
                        data-live-search="true">
                        <option value="" selected><?php echo display('payment_method') ?></option>
                        <?php foreach($paymentdetails as $ptype){ ?>
                        <option value="<?php echo html_escape($ptype->payment_method) ?>">
                            <?php echo html_escape($ptype->payment_method) ?></option>
                        <?php } ?>
                    </select>
                </div>
           <div id="modedetails" hidden>
    <div class="form-group row">
        <label for="rtgsvalue" class="col-sm-4 col-form-label"><?php echo ("RTGS Reference Number"); ?></label>
        <input type="text" name="rtgs" class="col-sm-8" id="rtgsvalue" placeholder="<?php echo ("RTGS Reference Number"); ?>">
    </div>
</div>
<div id="modedetailsupi" hidden>
    <div class="form-group row">
        <label for="upivalue" class="col-sm-4 col-form-label"><?php echo ("UPI Reference Number"); ?></label>
        <input type="text" name="upi_ref_num" class="col-sm-8" id="upivalue" placeholder="<?php echo ("UPI Reference Number"); ?>">
    </div>
</div>
<div id="modedetailsonline" hidden>
    <div class="form-group row">
        <label for="onlinerefnum" class="col-sm-4 col-form-label"><?php echo ("Reference Number"); ?></label>
        <input type="text" name="online_refnum" class="col-sm-8" id="onlinerefnum" placeholder="<?php echo ("Reference Number"); ?>">
    </div>
</div>
<div id="modedetailscard" hidden>
    <div class="form-group row">
        <label for="cardno" class="col-sm-4 col-form-label"><?php echo display("card_number"); ?></label>
        <input type="text" name="cardNumber" class="col-sm-8" id="cardno" placeholder="<?php echo display("card_number"); ?>">
    </div>
    <div class="form-group row">
        <label for="ccvvalue" class="col-sm-4 col-form-label"><?php echo ("CCV"); ?></label>
        <input type="text" name="ccvvalue_num" class="col-sm-8" id="ccvvalue" placeholder="<?php echo ("CCV"); ?>">
    </div>
    <div class="form-group row">
        <label for="ccvvalue" class="col-sm-4 col-form-label"><?php echo ("Date"); ?></label>
        <input type="date" name="carddate" class="col-sm-8" id="date"  >
    </div>
</div>
<div id="modedetailscheque" hidden>
    <div class="form-group row">
        <label for="chqno" class="col-sm-4 col-form-label"><?php echo ("Cheque Number"); ?></label>
        <input type="text" name="cheque_no" class="col-sm-8" id="chqno" placeholder="<?php echo ("Cheque Number"); ?>">
    </div>
    <div class="form-group row">
        <label for="ccvvalue" class="col-sm-4 col-form-label"><?php echo ("Date"); ?></label>
        <input type="date" name="cdate" class="col-sm-8" id="date"  >
    </div>
</div>
                <div class="form-group row">
                    <label for="cancelationcharge"
                        class="col-sm-4 col-form-label"><?php echo display("cancel_charge"); ?></label>
                    <input class="col-sm-8" name="cancelationcharge" type="number" placeholder="<?php echo display("cancel_charge"); ?>" value="">
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo MOD_URL.$module;?>/assets/js/reservationEdit.js"></script>
<script src="<?php echo MOD_URL.$module;?>/assets/js/cancelreservation.js"></script>

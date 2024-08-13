<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<div class="row" id="checkin_list">
    <div class="col-md-12 mb-3">
        <div class="card">
            <?php if($this->permission->method('hall_room','create')->access()): ?>
            <div class="card-header">
                <!--<h4><?php echo "Checkout List"?></h4>-->
                
             <h4><?php echo "Checkout List"; ?><small class="float-right">
                    <!-- <a href="<?php echo base_url('hall_room/hallroom/viewCheckout/'); ?>" class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i> <?php //echo "Direct Checkout"; ?></a> -->
                    </small></h4>
            </div>
         
         
         
    <!--        <div class="col-3 col-sm-3 col-sm-3 col-md-3 col-lg-1 col-xl-1">-->
    <!--    <button type="button" disabled onclick="checkoutinfo()" class="btn btn-success" id="go"><?php echo display("go") ?></button>-->
    <!--</div>-->
         
         
         
         
         
            <div class="row">
                <!--  table area -->
                <div class="col-sm-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" class="datatable table table-striped table-bordered table-hover"
                                id="checkoutdetails">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl_no') ?></th>
                                        <th><?php echo "Invoice No" ?></th>
                                        <th><?php echo "Hall Type" ?></th>
                                        <th><?php echo display('name') ?></th>
                                        <th><?php echo display('phone') ?></th>
                                        <th><?php echo display('check_in') ?></th>
                                        <th><?php echo display('check_out') ?></th>
                                        <th><?php echo display('paid_amount') ?></th>
                                        <th><?php echo display('due_amount') ?></th>
                                        <th><?php echo display('booking_status') ?></th>
                                        <th><?php echo display('payment_status') ?></th>
                                        <th><?php echo display('action') ?></th>
                                    </tr>
                                </thead>
                            </table> <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <strong><?php echo display("cancels_reservation") ?></strong>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body editinfo">
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<div id="newreservation">
</div>
<div id="printreservation" hidden>
</div>

<style>
    td{
        max-width: 120px; 
        overflow: hidden; 
        text-overflow: ellipsis
    }
</style>

<script src="<?php echo MOD_URL.$module;?>/assets/js/checkoutList.js"></script>
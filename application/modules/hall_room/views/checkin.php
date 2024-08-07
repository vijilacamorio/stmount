<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<div class="row" id="booking_list">
    <div class="col-md-12 mb-3">
        <div class="card">
            <?php if($this->permission->method('room_reservation','create')->access()): ?>
            <div class="card-header">
                <h4><?php echo display('hall_room')." ".display('checkin')?><small class="float-right"><a href="#" id="hall_checkinbtn"
                            class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i>
                            <?php echo display('direct_checkin')?></a></small></h4>
            </div>
            <div class="row">
                <!--  table area -->
                <div class="col-sm-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" class="datatable table table-striped table-bordered table-hover"
                                id="bookingdetails">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl_no') ?></th>
                                        <th style="width:100px"><?php echo display('booking_no') ?></th>
                                        <th><?php echo display('hallroom_type') ?></th>
                                        <th><?php echo display('event_name') ?></th>
                                        <th><?php echo display('event_type') ?></th>
                                        <th><?php echo display('seatplan') ?></th>
                                        <th><?php echo display('customer')." ".display('name') ?></th>
                                        <th><?php echo display('phone') ?></th>
                                        <th><?php echo display('s_date') ?></th>
                                        <th><?php echo display('e_date') ?></th>
                                        <th><?php echo display('booking_status') ?></th>
                                        <th><?php echo display('total_amount') ?></th>
                                        <th><?php echo display('paid_amount') ?></th>
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
                <strong><?php echo display("cancel_reservation") ?></strong>
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
<script src="<?php echo MOD_URL.$module;?>/assets/js/checkinList.js"></script>

<script>
    function hallcheckinedit(id){
    "use strict";
    $("#dayClose").trigger("click");
    $(".sidebar-mini").addClass('sidebar-collapse');
    $("#booking_list").hide();
    $("#checkin_list").hide();
    var csrf = $('#csrf_token').val();
    var myurl = baseurl + "hall_room/hallroom/checkinedithall/"+id;
    $.ajax({
        url: myurl,
        type: "POST",
        data: {
            csrf_test_name: csrf,
        },
        success: function(data) {
            $("#newreservation").html(data);
            if($("#checkin_list").length){
                $("#view_checin").text("");
                $("#view_checin").append("<i class='ti-plus' aria-hidden='true'></i> Check In List");
            }
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
</script>
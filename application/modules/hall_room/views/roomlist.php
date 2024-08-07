<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/custom.css">
<?php
$status = array(
    '' => 'Select Status',
    '0' => 'Available',
    '1' => 'Unavailable',
);

?>
<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card">
            <?php if($this->permission->method('room_reservation','create')->access()): ?>
            <div class="card-header">
                <h4><?php echo display('room_status')?></h4>
            </div>
            <div class="row">
                <!--  table area -->
                <div class="col-sm-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%" class="datatable table table-striped table-bordered table-hover"
                                id="exdatatable">
                                <thead>
                                    <tr>
                                        <th><?php echo display('sl_no') ?></th>
                                        <th><?php echo display('room_name') ?></th>
                                        <!--<th><?php echo display('check_in') ?></th>-->
                                        <!--<th><?php echo display('check_out') ?></th>-->
                                        <th><?php echo display('room_status') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                             <?php if (!empty($roomlist)) {
                            ?>
                             <?php $sl = 1; ?>
                             <?php foreach ($roomlist as $list) { ?>
                               <?php 
                                    $room_type = $this->db->select("hall_type, room_status")->from("tbl_hallroom_info")->where("hid", $list->hall_type)->get()->row();
                                ?> 
                             <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                 <td><?php echo $sl; ?></td>
                                 <td><?php echo html_escape($list->hall_type); ?></td>
                                 <!--<td><?php echo html_escape($list->start_date); ?></td>-->
                                 <!--<td><?php echo html_escape($list->end_date); ?></td>-->
                                 <td><?php echo html_escape($status[$list->room_status]); ?></td>
                             </tr>
                             <?php $sl++; ?>
                             <?php } ?>
                             <?php } ?>
                         </tbody>
                            </table> <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="<?php echo MOD_URL.$module;?>/assets/js/timer.js"></script>
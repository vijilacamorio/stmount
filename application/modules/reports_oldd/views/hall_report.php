<link type="text/css" href="<?php echo MOD_URL.$module;?>/assets/css/table.css">
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


  




<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-header">
                <h4><?php echo display('search_report'); ?></h4>
            </div> -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                    <?php echo form_open('reports/report/cosummary', array('class' => 'form-inline')) ?>
                       
                       <div class="form-group" style="margin-left:400px;">
                           <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                           <input type="text" name="start_date" value="<?php    echo $start_date1 ;   ?>" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                           <input type="text" name="to_date" value="<?php    echo $to_date1 ;   ?>" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                       </div>
                      
                       <input name="invoiceurl" type="hidden" value="<?php echo base_url("reports/report/cosummary") ?>" id="invoiceurl" />
                       
                       &nbsp;<button type="submit" class="btn btn-success">
                           <span class="text-white"><?php echo display('search') ?></span>
                       </button>&nbsp;
                               
                       <?php echo form_close()?>



                        
                    </div>
                </div>
            </div>
        </div>



<style>
    .table-responsive {
    max-height: 800px; /* Initial max height; adjust as needed */
    overflow-y: auto; /* Enable vertical scrolling */
    width: 100%; /* Adjust the width as needed */
    border: 1px solid #ddd; /* Optional: Add border for better visualization */
}

/* Hide the scrollbar */
.table-responsive::-webkit-scrollbar {
    display: none;
}

/* Ensure the table fills the container width */
#exdatatable {
    width: 100%;
}

/* Style for the table header */
#exdatatable thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa; /* Change as needed */
    z-index: 2; /* Ensure the header stays above the content */
}
</style>









<div class="card">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd lobidrag">
                <div class="card-header">
                    <h4><?php echo display('co_summary') ?>
                        
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                    <th style="width:80px;"><?php echo display('date') ?></th>
                            <th><?php echo display('bill_no') ?></th>
                            <th><?php echo ('Hall Type') ?></th>
                             <th><?php echo display('Name_of_the_guest') ?></th>
                            <th><?php echo display('number_of_days') ?></th>
                           
  <th><?php echo ('Checkin Date') ?></th>
    <th><?php echo ('Checkout Date') ?></th>
  <th><?php echo  ('Total Value'); ?></th>
                                  <th><?php echo display('discount') ?></th>
                                  
                           <th><?php echo  ('Additional Charge') ?></th>
                 
                      <th><?php echo  ('Room CGST(6%)') ?></th>
                            <th><?php echo  ('Room SGST(6%)') ?></th>
                            <th><?php echo  ('Room Tax(12%)') ?></th>
  <th><?php echo  ('Room-Taxable Value'); ?></th>

                            
                           


                     
                        <th><?php echo ('Paid Amount') ?></th>
                            <th><?php echo  ('User') ?></th>

                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
    <?php
    $total_no_of_people = 0;
            $total_extra_bed = 0;
            $total_extra_person = 0;
            $total_extra_child = 0;
            $total_food_cgst = 0;
            $total_food_sgst = 0;
            $total_room_taxable_value=0;
            $total_food_tax = 0;
            $total_room_cgst = 0;
            $total_room_sgst = 0;
            $total_room_tax = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $total_discount = 0;
            $total_price = 0;
     foreach ($hall_info as $report) {
     ?>
        <tr>
            
            <?php
            $date_time = new DateTime($report->date_time);
            $date_only = $date_time->format('Y-m-d');
            echo '<td>' . html_escape($date_only) . '</td>';
            ?>
            <td><?php echo html_escape($report->invoice_no); ?></td>
            <td style="word-break: break-all;"><?php echo html_escape($report->hall_type); ?></td>
           
           
             <td><?php echo html_escape($report->firstname." ".$report->lastname); ?></td>
            <?php
    $checkinDate = new DateTime($report->start_date);
    $checkoutDate = new DateTime($report->end_date);
    $interval = $checkinDate->diff($checkoutDate);
    $numberOfDays = $interval->days;

    if ($numberOfDays) {
        echo '<td>' . html_escape($numberOfDays) . ' days</td>';
    } else {
        echo '<td>' . html_escape('1 day') . '</td>';
    }
   ?>
<td><?php echo html_escape($report->start_date); ?></td>
<td><?php echo html_escape($report->end_date); ?></td>
<td><?php echo $report->totalamount  ; ?></td>
  <td><?php echo html_escape($report->special_discount); ?></td>
<td>
   <?php echo html_escape($report->additional_charges); ?>
</td>

 
<td><?php echo is_numeric($report->cgst) ? html_escape($report->cgst) : '0'; ?></td>
<td><?php echo is_numeric($report->sgst) ? html_escape($report->sgst) : '0'; ?></td>
<td><?php echo is_numeric($report->cgst) && is_numeric($report->sgst) ? html_escape($report->cgst + $report->sgst) : '0'; ?></td>
<td><?php echo $report->totalamount - $report->sgst - $report->sgst  ; ?></td>





<!-- <td>
    <?php //echo is_numeric($report->ccgst) && is_numeric($report->bcgst) ? html_escape($report->bcgst + $report->ccgst) : (is_numeric($report->bcgst) ? html_escape($report->bcgst) : (is_numeric($report->ccgst) ? html_escape($report->csgst) : '0')); ?>
</td>



<td><?php //echo is_numeric($report->csgst) && is_numeric($report->bsgst) ? html_escape($report->csgst + $report->bsgst) : (is_numeric($report->csgst) ? html_escape($report->csgst) : (is_numeric($report->bcgst) ? html_escape($report->bsgst) : '0'));   ?></td> -->

            <td><?php echo html_escape($report->paid_amount); ?></td>
 <td><?php echo html_escape($userinfo->firstname . ' ' . $userinfo->lastname); ?></td>

            </tr>
            <?php 
         $total_no_of_people += $sum_nuofpeople;
                $total_extra_bed += isset($report->bedcharge) && isset($report->extrabed) && is_numeric($report->bedcharge) && is_numeric($report->extrabed) ? $report->bedcharge * $report->extrabed : 0;
                $total_extra_person += isset($report->personcharge) && isset($sum_extraperson) && is_numeric($report->personcharge) && is_numeric($sum_extraperson) ? $report->personcharge * $sum_extraperson : 0;
                $total_extra_child += isset($report->childcharge) && isset($sum_extrachild) && is_numeric($report->childcharge) && is_numeric($sum_extrachild) ? $report->childcharge * $sum_extrachild : 0;
                $total_food_cgst += is_numeric($report->ccgst) ? $report->ccgst : 0;
               
                $total_food_sgst += is_numeric($report->csgst) ? $report->csgst : 0;
                $total_food_tax += is_numeric($report->ccgst) && is_numeric($report->csgst) ? $report->ccgst + $report->csgst : 0;
                $total_room_cgst += is_numeric($report->bcgst) ? $report->bcgst : 0;
                $total_room_sgst += is_numeric($report->bsgst) ? $report->bsgst : 0;
                $total_room_tax += is_numeric($report->bcgst) && is_numeric($report->bsgst) ? $report->bcgst + $report->bsgst : 0;
                $total_cgst += is_numeric($report->ccgst) && is_numeric($report->bcgst) ? $report->bcgst + $report->ccgst : (is_numeric($report->bcgst) ? $report->bcgst : (is_numeric($report->ccgst) ? $report->csgst : 0));
                $total_sgst += is_numeric($report->csgst) && is_numeric($report->bsgst) ? $report->csgst + $report->bsgst : (is_numeric($report->csgst) ? $report->csgst : (is_numeric($report->bcgst) ? $report->bsgst : 0));
                $total_discount += html_escape($report->discountamount);
                $total_price += html_escape($report->total_price);
         $total_room_taxable_value = $total_price - $total_room_sgst - $total_room_cgst;
        } ?>



</tbody>
<tfoot>
        <tr>
            <td colspan="7"></td>
            <td id="total-value"></td>
            <td id="total-discount"></td>
            <td id="total-additional-charge"></td>
            <td id="total-cgst"></td>
            <td id="total-sgst"></td>
            <td id="total-tax"></td>
            <td id="total-taxable-value"></td>
            <td id="total-paid-amount"></td>
            <td></td> <!-- Empty cell for User column -->
        </tr>
    </tfoot>

     
            </table>
        </div>
        </div>
<script>
     document.addEventListener('DOMContentLoaded', function () {
        const columns = {
            'total-value': 7,
            'total-discount': 8,
            'total-additional-charge': 9,
            'total-cgst': 10,
            'total-sgst': 11,
            'total-tax': 12,
            'total-taxable-value': 13,
            'total-paid-amount': 14
        };

        const footer = document.querySelector('#exdatatable tfoot tr');
        const rows = document.querySelectorAll('#exdatatable tbody tr');

        for (const [columnId, columnIndex] of Object.entries(columns)) {
            let sum = 0;
            rows.forEach(row => {
                const cell = row.cells[columnIndex];
                sum += parseFloat(cell.innerText) || 0;
            });
            footer.querySelector(`#${columnId}`).innerText = sum.toFixed(2);
        }
    });
    </script>
    </div>
</div>
</div>
</div>
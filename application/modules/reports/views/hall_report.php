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
                    <?php echo form_open('reports/report/hall', array('class' => 'form-inline')) ?>
                       
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
                    <h4><?php echo display('hall_report') ?>
                        
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
                     <th style="width:80px;"><?php echo ('Paid Date') ?></th>
                            <th><?php echo display('bill_no') ?></th>
                            <th><?php echo ('Hall Type') ?></th>
                             <th><?php echo display('Name_of_the_guest') ?></th>
                            <th><?php echo display('number_of_days') ?></th>
                           
  <th><?php echo ('Checkin Date') ?></th>
    <th><?php echo ('Checkout Date') ?></th>
  <th><?php echo  ('Total Value'); ?></th>
                                  <th><?php echo display('discount') ?></th>
                                  
                           <th><?php echo  ('Additional Charge') ?></th>
                 <th><?php echo  ('Additional Charge CGST(9%)') ?></th>
                            <th><?php echo  ('Additional Charge SGST(9%)') ?></th>
                      <th><?php echo  ('Hall CGST(9%)') ?></th>
                            <th><?php echo  ('Hall SGST(9%)') ?></th>
                            <th><?php echo  ('Hall Tax(18%)') ?></th>
  <th><?php echo  ('Hall-Taxable Value'); ?></th>

                            
                           
 <th><?php echo ('Advance Amount') ?></th>

                     
                        <th><?php echo ('Paid Amount') ?></th>
                                  <th><?php echo  ('Booking Status') ?></th>
                                            <th><?php echo  ('Payment Status') ?></th>
                            <th><?php echo  ('User') ?></th>

                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
    <?php
    $total_no_of_people = 0;
            $total_extra_bed = 0;
            $total_extra_person = 0;
            $total_extra_child = 0;
             $add_cgst=0;
              $add_cgst=0;
            $total_food_cgst = 0;
            $total_food_sgst = 0;
            $total_room_taxable_value=0;
            $total_food_tax = 0;
            $total_room_cgst = 0;
            $total_room_sgst = 0;
            $total_adv=0;
            $total_room_tax = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $total_discount = 0;
            $total_price = 0;
     foreach ($hall_info as $report) {
         if($report->payment_status==1){
     ?>
        <tr>
            
            <?php
            
            $date_time = new DateTime($report->date_time);
            $date_only = $date_time->format('Y-m-d');
            echo '<td>' . html_escape($date_only) . '</td>';
            echo '<td>' . html_escape($report->paydate) . '</td>';
           // tbl_guestpayments
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
 <?php  $adv_tax = $report->additional_charges * 0.09;  ?>
 <td><?php echo is_numeric($adv_tax) ? html_escape($adv_tax) : '0'; ?></td>
<td><?php echo is_numeric($adv_tax) ? html_escape($adv_tax) : '0'; ?></td>


<td><?php echo is_numeric($report->cgst) ? html_escape($report->cgst) : '0'; ?></td>
<td><?php echo is_numeric($report->sgst) ? html_escape($report->sgst) : '0'; ?></td>
<td><?php echo is_numeric($report->cgst) && is_numeric($report->sgst) ? html_escape($report->cgst + $report->sgst) : '0'; ?></td>
<td><?php echo $report->totalamount - $report->sgst - $report->sgst  ; ?></td>
<td><?php echo html_escape($report->adv_amt);   ?></td>


<!-- <td>
    <?php //echo is_numeric($report->ccgst) && is_numeric($report->bcgst) ? html_escape($report->bcgst + $report->ccgst) : (is_numeric($report->bcgst) ? html_escape($report->bcgst) : (is_numeric($report->ccgst) ? html_escape($report->csgst) : '0')); ?>
</td>



<td><?php //echo is_numeric($report->csgst) && is_numeric($report->bsgst) ? html_escape($report->csgst + $report->bsgst) : (is_numeric($report->csgst) ? html_escape($report->csgst) : (is_numeric($report->bcgst) ? html_escape($report->bsgst) : '0'));   ?></td> -->

            <td><?php echo html_escape($report->paid_amount); ?></td>

<td><?php

if($report->status==1 ){ echo "Booked" ; }else if($report->status==2){echo "Completed" ;} else if($report->status==3){echo "Cancelled" ;}else if($report->status==4){echo "Checkin" ;}else if($report->status==5){echo "Checkout" ;} ?></td>

<td><?php if($report->payment_status==1 ){ echo "Paid" ; }else if($report->payment_status==2){echo "Partially Paid" ;} else if($report->payment_status==0){echo "Unpaid" ;}else if($report->payment_status==3){echo "Refund" ;} ?></td>
      <td><?php echo html_escape($userinfo->firstname . ' ' . $userinfo->lastname); ?></td>
            </tr>
            <?php 
         $total_no_of_people += $sum_nuofpeople;
                $total_extra_bed += isset($report->bedcharge) && isset($report->extrabed) && is_numeric($report->bedcharge) && is_numeric($report->extrabed) ? $report->bedcharge * $report->extrabed : 0;
                $total_extra_person += isset($report->personcharge) && isset($sum_extraperson) && is_numeric($report->personcharge) && is_numeric($sum_extraperson) ? $report->personcharge * $sum_extraperson : 0;
                $total_extra_child += isset($report->childcharge) && isset($sum_extrachild) && is_numeric($report->childcharge) && is_numeric($sum_extrachild) ? $report->childcharge * $sum_extrachild : 0;
                $total_food_cgst += is_numeric($report->ccgst) ? $report->ccgst : 0;
               $add_cgst += is_numeric($adv_tax) ? $adv_tax : 0;
                $add_sgst += is_numeric($adv_tax) ? $adv_tax : 0;
                $total_food_sgst += is_numeric($report->csgst) ? $report->csgst : 0;
                $total_food_tax += is_numeric($report->ccgst) && is_numeric($report->csgst) ? $report->ccgst + $report->csgst : 0;
                $total_room_cgst += is_numeric($report->bcgst) ? $report->bcgst : 0;
                $total_room_sgst += is_numeric($report->bsgst) ? $report->bsgst : 0;
                $total_room_tax += is_numeric($report->bcgst) && is_numeric($report->bsgst) ? $report->bcgst + $report->bsgst : 0;
                $total_cgst += is_numeric($report->ccgst) && is_numeric($report->bcgst) ? $report->bcgst + $report->ccgst : (is_numeric($report->bcgst) ? $report->bcgst : (is_numeric($report->ccgst) ? $report->csgst : 0));
                $total_sgst += is_numeric($report->csgst) && is_numeric($report->bsgst) ? $report->csgst + $report->bsgst : (is_numeric($report->csgst) ? $report->csgst : (is_numeric($report->bcgst) ? $report->bsgst : 0));
                $total_discount += html_escape($report->discountamount);
                   $total_adv += html_escape($report->adv_amt);
                $total_price += html_escape($report->total_price);
         $total_room_taxable_value = $total_price - $total_room_sgst - $total_room_cgst;
        }} ?>



</tbody>
<tfoot>
        <tr>
            <td colspan="8"></td>
            <td id="total-value"></td>
            <td id="total-discount"></td>
            <td id="total-additional-charge"></td>
             <td id="add_cgst"></td>
              <td id="add_sgst"></td>
            <td id="total-cgst"></td>
            <td id="total-sgst"></td>
            <td id="total-tax"></td>
            <td id="total-taxable-value"></td>
               <td id="total-adv-value"></td>
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
        'total-value': 8,
        'total-discount': 9,
        'total-additional-charge': 10,
        'add_cgst': 11,
        'add_sgst': 12,
        'total-cgst': 13,
        'total-sgst': 14,
        'total-tax': 15,
        'total-taxable-value': 16,
        'total-adv-value': 17,
        'total-paid-amount': 18
    };

    const footer = document.querySelector('#exdatatable tfoot tr');
    const rows = document.querySelectorAll('#exdatatable tbody tr');

    for (const [columnId, columnIndex] of Object.entries(columns)) {
        let sum = 0;
        rows.forEach(row => {
            const cell = row.cells[columnIndex];
            sum += parseFloat(cell.innerText) || 0;
        });
        footer.querySelector(`#${columnId}`).innerText = sum.toFixed();
    }
});

    </script>
    </div>
</div>
</div>
</div>
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
            <div style="overflow:auto">
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                    <th style="width:80px;"><?php echo display('date') ?></th>
                            <th><?php echo display('bill_no') ?></th>
                            <th><?php echo display('room_no') ?></th>
                             <th><?php echo display('Name_of_the_guest') ?></th>
                            <th><?php echo display('number_of_days') ?></th>
                              <th><?php echo ('No of People') ?></th>
  <th><?php echo ('Checkin Date') ?></th>
    <th><?php echo ('Checkout Date') ?></th>
  <th><?php echo  ('Total Value'); ?></th>
                                  <th><?php echo display('discount') ?></th>
                                    <th><?php echo ('Additional Charge') ?></th>
                                     <th><?php echo  ('Additional Charge CGST(9%)') ?></th>
                            <th><?php echo  ('Additional Charge SGST(9%)') ?></th>
                           <th><?php echo  ('Extra Bed') ?></th>
                            <th><?php echo  ('Extra Person') ?></th>
                            <th><?php echo  ('Extra Child') ?></th>
                      <th><?php echo  ('Room CGST(6%)') ?></th>
                            <th><?php echo  ('Room SGST(6%)') ?></th>
                            <th><?php echo  ('Room Tax(12%)') ?></th>
  <th><?php echo  ('Room-Taxable Value'); ?></th>

                            
                           


                     
                        <th><?php echo ('Paid Amount') ?></th>
                         <th><?php echo ('Advance Amount') ?></th>
                          <th><?php echo ('Paid Date') ?></th>
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
            $total_addcharge=0;
            $add_cgst=0;
            $total_cgst = 0;
            $total_sgst = 0;
            $total_discount = 0;
            $total_price = 0;
            $total_adv=0;
     foreach ($csreport as $report) {
     ?>
        <tr>
            
            <?php
            $date_time = new DateTime($report->date_time);
            $date_only = $date_time->format('Y-m-d');
            echo '<td>' . html_escape($date_only) . '</td>';
            ?>
            <td><?php echo html_escape($report->booking_number); ?></td>
            <td style="word-break: break-all;"><?php echo html_escape($report->room_no); ?></td>
           
           
             <td><?php echo html_escape($report->full_guest_name); ?></td>
            <?php
    $checkinDate = new DateTime($report->checkindate);
    $checkoutDate = new DateTime($report->checkoutdate);
    $interval = $checkinDate->diff($checkoutDate);
    $numberOfDays = $interval->days;

    if ($numberOfDays) {
        echo '<td>' . html_escape($numberOfDays) . ' days</td>';
    } else {
        echo '<td>' . html_escape('1 day') . '</td>';
    }
    $total_nuofpeople = $report->nuofpeople; // Get the string value

// Split the string into an array based on the comma
$nuofpeople_array = explode(',', $total_nuofpeople);

// Initialize a variable to store the sum
$sum_nuofpeople = 0;

// Loop through the array and add each value to the sum
foreach ($nuofpeople_array as $value) {
    // Convert the string value to an integer and add it to the sum
    $sum_nuofpeople += (int) $value;
}
?>



<td><?php echo html_escape($sum_nuofpeople); ?></td>
<td><?php echo html_escape($report->checkindate); ?></td>
<td><?php echo html_escape($report->checkoutdate); ?></td>
<td><?php echo $report->overalltotal  ; ?></td>
  <td><?php echo html_escape($report->discountamount); ?></td>
   <td><?php echo html_escape($report->addcharge); ?></td>
   <td><?php   $adcgst = $report->addcharge * 0.09; 
   echo $adcgst;
   ?></td>
    <td><?php   
   echo $adcgst;
   ?></td> 
<td><?php
                                        $bedcharge1=explode(",",$report->bed_amount);
                                 
                                    $personcharge1=explode(",",$report->person_amount);
                                    $childcharge1=explode(",",$report->child_amount);
                         

$total_bed_charge = 0;
$total_person_charge = 0;
$total_child_charge = 0;

// Loop through each array and sum up the charges
for ($i = 0; $i < count($bedcharge1); $i++) {
    $total_bed_charge += (int)$bedcharge1[$i];
}

for ($i = 0; $i < count($personcharge1); $i++) {
    $total_person_charge += (int)$personcharge1[$i];
}

for ($i = 0; $i < count($childcharge1); $i++) {
    $total_child_charge += (int)$childcharge1[$i];
}
 echo $total_bed_charge;  ?>
</td>

 <td>
<?php echo $total_person_charge;  ?>
</td>
 <td>
<?php echo $total_child_charge;  ?>
</td>

<td><?php echo is_numeric($report->bcgst) ? html_escape($report->bcgst) : '0'; ?></td>
<td><?php echo is_numeric($report->bsgst) ? html_escape($report->bsgst) : '0'; ?></td>
<td><?php echo is_numeric($report->bcgst) && is_numeric($report->bsgst) ? html_escape($report->bcgst + $report->bsgst) : '0'; ?></td>
<td><?php echo $report->overalltotal-$adcgst-$adcgst- $report->bsgst - $report->bsgst  ; ?></td>





<!-- <td>
    <?php //echo is_numeric($report->ccgst) && is_numeric($report->bcgst) ? html_escape($report->bcgst + $report->ccgst) : (is_numeric($report->bcgst) ? html_escape($report->bcgst) : (is_numeric($report->ccgst) ? html_escape($report->csgst) : '0')); ?>
</td>



<td><?php //echo is_numeric($report->csgst) && is_numeric($report->bsgst) ? html_escape($report->csgst + $report->bsgst) : (is_numeric($report->csgst) ? html_escape($report->csgst) : (is_numeric($report->bcgst) ? html_escape($report->bsgst) : '0'));   ?></td> -->

            <td><?php echo html_escape($report->paid_amount); ?></td>
             <td><?php echo html_escape($report->advance_amount); ?></td>
            <td><?php echo html_escape($report->paid_paydate); ?></td>
            
 <td><?php echo html_escape($userinfo->firstname . ' ' . $userinfo->lastname); ?></td>

            </tr>
            <?php 
         $total_no_of_people += $sum_nuofpeople;
                $total_extra_bed += isset($report->bc)  ? $report->bc : 0;
                $total_extra_person += isset($report->pc)  ? $report->pc : 0;
                $total_extra_child += isset($report->cc) ? $report->cc : 0;
                $total_food_cgst += is_numeric($report->ccgst) ? $report->ccgst : 0;
               
                $total_food_sgst += is_numeric($report->csgst) ? $report->csgst : 0;
                $total_food_tax += is_numeric($report->ccgst) && is_numeric($report->csgst) ? $report->ccgst + $report->csgst : 0;
                $total_room_cgst += is_numeric($report->bcgst) ? $report->bcgst : 0;
                $total_room_sgst += is_numeric($report->bsgst) ? $report->bsgst : 0;
                $total_room_tax += is_numeric($report->bcgst) && is_numeric($report->bsgst) ? $report->bcgst + $report->bsgst : 0;
                $total_cgst += is_numeric($report->ccgst) && is_numeric($report->bcgst) ? $report->bcgst + $report->ccgst : (is_numeric($report->bcgst) ? $report->bcgst : (is_numeric($report->ccgst) ? $report->csgst : 0));
                $total_sgst += is_numeric($report->csgst) && is_numeric($report->bsgst) ? $report->csgst + $report->bsgst : (is_numeric($report->csgst) ? $report->csgst : (is_numeric($report->bcgst) ? $report->bsgst : 0));
                $total_discount += html_escape($report->discountamount);
                 $total_addcharge += html_escape($report->addcharge);
                $add_cgst += is_numeric( $adcgst) ?  $adcgst : 0;  
             $total_price += html_escape($report->overalltotal);
               $total_paid += html_escape($report->paid_amount);
                 $total_adv += html_escape($report->advance_amount);
         $total_room_taxable_value = $report->overalltotal-$adcgst-$adcgst- $report->bsgst - $report->bsgst ;
        } ?>



</tbody>
<tfoot>
<tr>
        <td colspan="8"><strong>Total:</strong></td>
             <td><?php echo number_format(($total_price),2); ?></td>
           <td><?php echo number_format($total_discount,2); ?></td>
            <td><?php echo number_format($total_addcharge,2); ?></td>
             <td><?php echo number_format($add_cgst,2); ?></td>
              <td><?php echo number_format($add_cgst,2); ?></td>
        <td><?php echo number_format($total_extra_bed,2); ?></td>
        <td><?php echo number_format($total_extra_person,2); ?></td>
        <td><?php echo number_format($total_extra_child,2); ?></td>
        <td><?php echo number_format($total_room_cgst,2); ?></td>
        <td><?php echo number_format($total_room_sgst,2); ?></td>
        <td><?php echo number_format(($total_room_tax),2); ?></td>
        <td><?php echo  number_format(($total_room_taxable_value),2); ?></td>
    <td><?php echo  number_format(($total_paid),2); ?></td>
     <td><?php echo  number_format(($total_adv),2); ?></td>
     
    
        <td></td>
    </tr>

    </tfoot>

     
            </table>
            </div>
        </div>
        </div>

    </div>
</div>
</div>
</div>
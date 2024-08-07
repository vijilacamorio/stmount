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
                           <input type="text" name="start_date" value="" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                           <input type="text" name="to_date" value="" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
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
                    <th><?php echo display('date') ?></th>
                            <th><?php echo display('bill_no') ?></th>
                            <th><?php echo display('room_no') ?></th>
                             <th><?php echo display('Name_of_the_guest') ?></th>
                            <th><?php echo display('number_of_days') ?></th>

                             <th><?php echo ('no_of_people') ?></th>
                           <th><?php echo  ('Extra Bed') ?></th>
                            <th><?php echo  ('Extra Person') ?></th>
                            <th><?php echo  ('Extra Child') ?></th>
 
                            <th><?php echo  ('Food CGST(2.5%)') ?></th>
                            <th><?php echo  ('Food SGST(2.5%)') ?></th>
                            <th><?php echo  ('Food Tax(5%)') ?></th>

                            <th><?php echo  ('Room CGST(6%)') ?></th>
                            <th><?php echo  ('Room SGST(6%)') ?></th>
                            <th><?php echo  ('Room Tax(12%)') ?></th>


                            
                            <th><?php echo  ('Total CGST') ?></th>
                            <th><?php echo  ('Total SGST') ?></th>


                            <th><?php echo display('discount') ?></th>
                            <th><?php echo  ('total') ?></th>
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
            $total_food_tax = 0;
            $total_room_cgst = 0;
            $total_room_sgst = 0;
            $total_room_tax = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $total_discount = 0;
            $total_price = 0;
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
<td>
    <?php 
        echo isset($report->bedcharge) && isset($report->extrabed) && is_numeric($report->bedcharge) && is_numeric($report->extrabed)
            ? $report->bedcharge * $report->extrabed 
            : '0';
    ?>
</td>

 <td>
    <?php 
        $extraperson = $report->extraperson; // Get the string value

// Split the string into an array based on the comma
$extraperson_array = explode(',', $extraperson);

// Initialize a variable to store the sum
$sum_extraperson = 0;

// Loop through the array and add each value to the sum
foreach ($extraperson_array as $value) {
    // Convert the string value to an integer and add it to the sum
    $sum_extraperson += (int) $value;
}
        echo isset($report->personcharge) && isset($sum_extraperson) && is_numeric($report->personcharge) && is_numeric($sum_extraperson)
            ? $report->personcharge * $sum_extraperson
            : '0';
    ?>
</td>
 <td>
    <?php 
        $extrachild = $report->extrachild; // Get the string value

// Split the string into an array based on the comma
$extrachild_array = explode(',', $extrachild);

// Initialize a variable to store the sum
$sum_extrachild = 0;

// Loop through the array and add each value to the sum
foreach ($extrachild_array as $value) {
    // Convert the string value to an integer and add it to the sum
    $sum_extrachild += (int) $value;
}
        echo isset($report->childcharge) && isset($sum_extrachild) && is_numeric($report->childcharge) && is_numeric($sum_extrachild)
            ? $report->childcharge * $sum_extrachild
            : '0';
    ?>
</td>
          
            <td><?php echo is_numeric($report->ccgst) ? html_escape($report->ccgst) : '0'; ?></td>
<td><?php echo is_numeric($report->csgst) ? html_escape($report->csgst) : '0'; ?></td>
<td><?php echo is_numeric($report->ccgst) && is_numeric($report->csgst) ? html_escape($report->ccgst + $report->csgst) : '0'; ?></td>


<td><?php echo is_numeric($report->bcgst) ? html_escape($report->bcgst) : '0'; ?></td>
<td><?php echo is_numeric($report->bsgst) ? html_escape($report->bsgst) : '0'; ?></td>
<td><?php echo is_numeric($report->bcgst) && is_numeric($report->bsgst) ? html_escape($report->bcgst + $report->bsgst) : '0'; ?></td>






<td>
    <?php echo is_numeric($report->ccgst) && is_numeric($report->bcgst) ? html_escape($report->bcgst + $report->ccgst) : (is_numeric($report->bcgst) ? html_escape($report->bcgst) : (is_numeric($report->ccgst) ? html_escape($report->csgst) : '0')); ?>
</td>



<td><?php echo is_numeric($report->csgst) && is_numeric($report->bsgst) ? html_escape($report->csgst + $report->bsgst) : (is_numeric($report->csgst) ? html_escape($report->csgst) : (is_numeric($report->bcgst) ? html_escape($report->bsgst) : '0'));   ?></td>

          
          
          
          





             <td><?php echo html_escape($report->discountamount); ?></td>
            <td><?php echo html_escape($report->total_price); ?></td>



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
        
        } ?>



</tbody>

               <tfoot>
        <tr>
            <td colspan="5" style='text-align:right;font-weight:bold;'><?php echo display('total') ?></td>
            <td><?php echo $total_no_of_people ?></td>
            <td><?php echo $total_extra_bed ?></td>
            <td><?php echo $total_extra_person ?></td>
            <td><?php echo $total_extra_child ?></td>
            <td><?php echo $total_food_cgst ?></td>
            <td><?php echo $total_food_sgst ?></td>
            <td><?php echo $total_food_tax ?></td>
            <td><?php echo $total_room_cgst ?></td>
            <td><?php echo $total_room_sgst ?></td>
            <td><?php echo $total_room_tax ?></td>
            <td><?php echo $total_cgst ?></td>
            <td><?php echo $total_sgst ?></td>
            <td><?php echo $total_discount ?></td>
            <td><?php echo $total_price ?></td>
            <td></td> <!-- Leave User column blank in footer -->
        </tr>
    </tfoot>
            </table>
        </div>
        </div>

    </div>
</div>
</div>
</div>
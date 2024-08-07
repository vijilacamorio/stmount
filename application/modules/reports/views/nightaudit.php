<script src="<?php echo MOD_URL.$module;?>/assets/js/prechaseReport.js"></script>
<link type="text/css" href="<?php echo MOD_URL.$module;?>/assets/css/table.css">
<style>
    table {
    table-layout : fixed;
}

.print-button {
  text-align: center;
  
  &__content {
    display: inline-block;
    cursor: pointer;
    margin-top: 1em;
    padding: .5em 1em;
    color: white;
    text-decoration: none;
    font-size: 25px;
    border-radius: 3px;
    transition: .3s;
    background: #a60b0f;
    
    &:hover {
      background-color: #c40004;
    }
  }
  
}
    </style>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                 <h4><?php echo display('Night_Audit')
                // echo $no_of_person;die();
                 ?>
                            <small class="float-right">
                                <!--<input type="button" class="button-print btn btn-info text-white button-print" name="btnPrint"-->
                                <!--    id="btnPrint" value="Print" onclick="printContent('printArea')" />-->
                            </small>
                        </h4>

               
            </div>
            <!-- <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <?php echo form_open('report/index',array('class' => 'form-inline'))?>
                        <?php
                        $today = date('d-m-Y'); ?>
                      <?php echo display('search') ?></span></a>&nbsp;
                        <?php echo form_close()?>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="card">

            <div class="row" id="printArea">
                <div class="col-sm-12">
                    <div class="card-header">
                       
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="text-center" id="getresult2">
                                <div class="table-responsive">
                                <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr style='font-weight:15px;'>
                                            <th><?php echo display('Description') ?></th>
                                            
                                            <th><?php echo ('Today') ?></th>
                                            
                                            <th><?php echo display('This Month') ?></th>
                                            <th><?php echo display('This Year') ?></th>
                                        </tr>
                                                                                <tr>
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>ROOM BOOKING</td>
</tr>
                                    </thead>
                                    <tbody>
                                       <?php
 $no_of_ppl = $no_of_person[0];   
 $no_of_ppl_mnth = $no_of_person_month[0];
 $no_of_ppl_yr = $no_of_person_year[0];
                                   //    echo $no_of_person;die();
                                       $obj = $nightaudit;   
                                    
                                   
                                  //     echo $obj;
                                      $obj_post=$nightaudit_postbill[0];
                                    $month = $nightaudit_current_month;$month_post=$nightaudit_month_postbill;
                                     $year=$nightaudit_current_year;$year_post=$nightaudit_year_postbill[0];
   //  print_r($obj);

$obj_due_room=($obj->total_price)-($obj->paid_amount);
$month_due_room=($month->total_price)-($month->paid_amount);
$year_due_room=($year->total_price)-($year->paid_amount);
$room_rates_array = explode(',',$obj->roomrate);
$room_rates_array = array_map('intval', $room_rates_array);

// Calculate the total room rate
$total_room_rate = array_sum($room_rates_array);


echo '<tr><td style="font-weight:bold;">Tariff</td><td>' . ($obj['total_price'] ) . '</td><td>' . ($month['total_price'] ) . '</td><td>' . ($year['total_price']) . '</td></tr>';

echo '<tr><td style="font-weight:bold;">Room Rent</td><td>' . ($obj['roomrate'] && !empty($checkuser) ? $obj['roomrate'] : '0.00') . '</td><td>' . ($month['roomrate'] ? $month['roomrate'] : '0.00') . '</td><td>' . ($year['roomrate'] ? $year['roomrate'] : '0.00') . '</td></tr>';
 
echo '<tr><td style="font-weight:bold;">Extra Bed</td><td>' . $obj['bed_amount'] . '</td><td>' . $month['bed_amount'] . '</td><td>' .$year['bed_amount'] . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Person</td><td>' . $obj['person_amount'] . '</td><td>' . $month['person_amount']. '</td><td>' .$year['person_amount'] . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Child</td><td>' .  $obj['child_amount'] . '</td><td>' . $month['child_amount'] . '</td><td>' . $year['child_amount'] . '</td></tr>';
echo '<tr><td style="font-weight:bold;">No Show</td><td>' . '' . '</td><td>' . '' . '</td><td>' . '' . '</td></tr>';
// echo '<tr><td style="font-weight:bold;">CGST</td><td>' . ($obj->cgst && !empty($checkuser) ? $obj->cgst : '0.00') . '</td><td>' . ($month->cgst ? $month->cgst : '0.00') . '</td><td>' . ($year->cgst ? $year->cgst : '0.00') . '</td></tr>';
// echo '<tr><td style="font-weight:bold;">SGST</td><td>' . ($obj->sgst && !empty($checkuser) ? $obj->sgst : '0.00') . '</td><td>' . ($month->sgst ? $month->sgst : '0.00') . '</td><td>' . ($year->sgst ? $year->sgst : '0.00') . '</td></tr>';
echo "</tbody></table>";

echo '<table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan="4" style="font-size:15px;text-align:center;font-weight:bold;">Room Advance Amount</td>
</tr>
                                    </thead>
                                    <tbody>';
echo '<tr><td style="font-weight:bold;">Advance Amount</td><td>' . (!empty($nightaudit_advance_ad[0]->advance_amount) && !empty($checkuser) ? $nightaudit_advance_ad[0]->advance_amount : '0.00') . 
'</td><td>' . (!empty($nightaudit_advance_m_ad[0]->advance_amount) ? $nightaudit_advance_m_ad[0]->advance_amount : '0.00') . '</td><td>' . (!empty($nightaudit_advance_y_ad[0]->advance_amount) ? 
$nightaudit_advance_y_ad[0]->advance_amount : '0.00') . '</td></tr>';
echo "</table>";
echo '<table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan="4" style="font-size:15px;text-align:center;font-weight:bold;">Room Booking Discount</td>
</tr>
                                    </thead>
                                    <tbody>';

            
echo '<tr><td style="font-weight:bold;">Discount Amount</td><td>' . ($obj['discountamount'] && !empty($checkuser) ? $obj['discountamount'] : '0.00') . '</td><td>' . ($month['discountamount'] ? $month['discountamount'] : '0.00') . '</td><td>' . ($year['discountamount'] ? $year['discountamount'] : '0.00') . '</td></tr>';
// echo '<tr><td style="font-weight:bold;">Extra Discount</td><td>' . ($obj_post->ex_discount && !empty($checkuser) ? $obj_post->ex_discount : '0.00') . '</td><td>' . ($month_post->ex_discount ? $month_post->ex_discount : '0.00') . '</td><td>' . ($year_post->ex_discount ? $year_post->ex_discount : '0.00') . '</td></tr>';
// echo '<tr><td style="font-weight:bold;">Special Discount </td><td>' . ($obj_post->special_discount && !empty($checkuser) ? $obj_post->special_discount : '0.00') . '</td><td>' . ($month_post->special_discount ? $month_post->special_discount : '0.00') . '</td><td>' . ($year_post->special_discount ? $year_post->special_discount : '0.00') . '</td></tr>'; 
echo "</tbody></table>";
echo '<table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan="4" style="font-size:15px;text-align:center;font-weight:bold;">Miscelleneous</td>
</tr>
                                    </thead>
                                    <tbody>';
 echo '<tr><td style="font-weight:bold;">Additional Charge</td><td>' . ($obj_post->additional_charges + ($obj_post->additional_charges * 0.18)) . '</td><td>' . ($month_post->additional_charges + ($month_post->additional_charges * 0.18)) . '</td><td>' . ($year_post->additional_charges + ($year_post->additional_charges * 0.18)) . '</td></tr>';

echo "</tbody></table>";


echo '<table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan="4" style="font-size:15px;text-align:center;font-weight:bold;">Room Revenue</td>
</tr>
                                    </thead>
                                    <tbody>';
echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . (($nightaudit_advance[0]->paid_amount + $obj['adv']) ?? '0.00') . '</td><td>' . (($nightaudit_advance_m[0]->paid_amount + $month['adv']) ?? '0.00') . '</td><td>' . (($nightaudit_advance_y[0]->paid_amount + $year['adv']) ?? '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . (($obj['total_price']) - ($obj['paid_amount'] + $obj['adv'])) . '</td><td>' . (($month['total_price']) - ($month['paid_amount'] + $month['adv'])) . '</td><td>' .  (($year['total_price']) - ($year['paid_amount'] + $year['adv'])) . '</td></tr>';

echo "</tbody></table>";     
            
            
            
              //    echo '<tr><td>Additional Charge</td><td>'.$obj->additional_charges.'</td><td>'.$month->additional_charges.'</td><td>'.$year->additional_charges.'</td></tr>';
for ($i = 0; $i < count($descriptions); $i++) {
    echo '<tr>';
    echo '<td>'.$descriptions[$i].'</td>';
    echo '<td>'.$amounts_today[$i].'</td>';

 echo '</tr>';
}
     

           
                                        
                                       
                                       
                                       ?>
                                    </tbody>
                                    <!-- <tfoot>
                                    <tr>
        <td style='font-weight:bold;'><?php echo display('Total') ?></td>
        <td>
            <?php
                $totalToday = $obj->total_price + $obj->roomrate + $obj->advance_amount + $obj->discountamount + $obj_post->ex_discount + $obj_post->special_discount + $obj_post->additional_charges + $obj->paid_amount + $obj_due_room;
                //echo $totalToday;
                echo  ($totalToday ? $totalToday : '0.00')
            ?>
        </td>
        <td>
            <?php
                $totalMonth = $month->total_price + $month->roomrate + $month->advance_amount + $month->discountamount + $month_post->ex_discount + $month_post->special_discount + $month_post->additional_charges + $month->paid_amount + $month_due_room;
                echo $totalMonth;
            ?>
        </td>
        <td>
            <?php
                $totalYear = $year->total_price + $year->roomrate + $year->advance_amount + $year->discountamount + $year_post->ex_discount + $year_post->special_discount + $year_post->additional_charges + $year->paid_amount + $year_due_room;
                echo $totalYear;
            ?>
        </td>
    </tr>
</tfoot> -->
                                </table>
 <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>RESTAURANT</td>
</tr>
                                    </thead>
                                    <tbody>

    <?php
    $totalBill = 0;
    $totalServiceCharge = 0;
    $totalVAT = 0;



    // Assuming $nightaudit_restaurent, $nightaudit_restaurent_current_month, and $nightaudit_restaurent_current_year have the data
    $obj = $nightaudit_restaurent;  $obj_due=($obj['bill_amount'])-($obj['customerpaid']);

    $month = $nightaudit_restaurent_current_month;$obj_m_due=($month['bill_amount'])-($month['customerpaid']);
    $year = $nightaudit_restaurent_current_year;$obj_y_due=($year['bill_amount'])-($year['customerpaid']);
$obj_tax=$obj['tax_amount']/2;
$month_tax=$month['tax_amount']/2;
$year_tax=$year['tax_amount']/2;
    // Displaying data rows
    echo '<tr><td style="font-weight:bold;">Tariff</td><td>' . (isset($obj['bill_amount']) && !empty($checkuser) ? number_format($obj['bill_amount'], 2) : '0.00') . '</td><td>' . (isset($month['bill_amount']) ? number_format($month['bill_amount'], 2) : '0.00') . '</td><td>' . (isset($year['bill_amount']) ? number_format($year['bill_amount'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Total Amount (excl.tax)</td><td>' . (isset($obj['total_amount']) && !empty($checkuser)  ? number_format($obj['total_amount']-$obj['discount'], 2) : '0.00') . '</td><td>' . (isset($month['total_amount']) ? number_format($month['total_amount']-$month['discount'], 2) : '0.00') . '</td><td>' . (isset($year['total_amount']) ? number_format($year['total_amount']-$year['discount'], 2) : '0.00') . '</td></tr>';
     echo '<tr><td style="font-weight:bold;">Discount</td><td>' . (isset($obj['discount']) ? number_format($obj['discount'], 2) : '0.00') . '</td><td>' . (isset($month['discount']) ? number_format($month['discount'], 2) : '0.00') . '</td><td>' . (isset($year['discount']) ? number_format($year['discount'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . (isset($obj['customerpaid']) && !empty($checkuser) ? number_format($obj['customerpaid'], 2) : '0.00') . '</td><td>' . (isset($month['customerpaid']) ? number_format($month['customerpaid'], 2) : '0.00') . '</td><td>' . (isset($year['customerpaid']) ? number_format($year['customerpaid'], 2) : '0.00') . '</td></tr>';
   echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . (isset($obj_due) && !empty($checkuser) ? number_format($obj_due, 2) : '0.00') . '</td><td>' . (isset($obj_m_due) ? number_format($obj_m_due, 2) : '0.00') . '</td><td>' . (isset($obj_y_due) ? number_format($obj_y_due, 2) : '0.00') . '</td></tr>';
  
    
    // Accumulate totals
    $totalBill += $obj->total_amount + $month->total_amount + $year->total_amount;
    $totalServiceCharge += $obj->service_charge + $month->service_charge + $year->service_charge;
    $totalVAT += $obj->VAT + $month->VAT + $year->VAT;
    ?>
</tbody>
<!-- <tfoot>
<tr>
        <td style="font-weight:bold;">Total</td>
        <td>
            <?php
                $totalRestaurant = $obj->bill_amount + $obj->total_amount + $obj->discount + $obj->customerpaid + $obj_due;
                echo number_format($totalRestaurant, 2);
            ?>
        </td>
        <td>
            <?php
                $totalRestaurantMonth = $month->bill_amount + $month->total_amount + $month->discount + $month->customerpaid + $obj_m_due;
                echo number_format($totalRestaurantMonth, 2);
            ?>
        </td>
        <td>
            <?php
                $totalRestaurantYear = $year->bill_amount + $year->total_amount + $year->discount + $year->customerpaid + $obj_y_due;
                echo number_format($totalRestaurantYear, 2);
            ?>
        </td>
    </tr>
</tfoot> -->
                                </table>
                                <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>BANQUET HALL</td>
</tr>
                                    </thead>
                                    <tbody>

    <?php
    $totalBill = 0;
    $totalServiceCharge = 0;
    $totalVAT = 0;



    $obj = $nightaudit_hall[0];  $obj_due=($obj->totalamount)-($obj->paid_amount);
  
    $month = $nightaudit_current_month_hall[0];$obj_m_due=($month->totalamount)-($month->paid_amount);
    $year = $nightaudit_current_year_hall[0];$obj_y_due=($year->totalamount)-($year->paid_amount);
//  print_r($year_hall);


    // Displaying data rows
    echo '<tr><td style="font-weight:bold;">Tariff</td><td>'. ( isset($nightaudit_hall['totalamount'])  && !empty($checkuser) ? number_format($nightaudit_hall['totalamount'], 2) : '0.00') .'</td><td>' . (isset($nightaudit_current_month_hall['totalamount']) ? number_format($nightaudit_current_month_hall['totalamount'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_year_hall['totalamount']) ? number_format($nightaudit_current_year_hall['totalamount'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Room Rent (excl.tax)</td><td>' . (isset($nightaudit_hall['rent']) && !empty($checkuser) ? number_format($nightaudit_hall['rent'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_month_hall['rent']) ? number_format($nightaudit_current_month_hall['rent'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_year_hall['rent']) ? number_format($nightaudit_current_year_hall['rent'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Discount</td><td>' . (isset($nightaudit_hall['special_discount']) && !empty($checkuser) ? number_format($nightaudit_hall['special_discount'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_month_hall['special_discount']) ? number_format($nightaudit_current_month_hall['special_discount'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_year_hall['special_discount']) ? number_format($nightaudit_current_year_hall['special_discount'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . (isset($nightaudit_hall['paid_amount']) && !empty($checkuser)  ? number_format($nightaudit_hall['paid_amount'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_month_hall['paid_amount']) ? number_format($nightaudit_current_month_hall['paid_amount'], 2) : '0.00') . '</td><td>' . (isset($nightaudit_current_year_hall['paid_amount']) ? number_format($nightaudit_current_year_hall['paid_amount'], 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . (isset($obj_due) && !empty($checkuser) ? number_format($obj_due, 2) : '0.00') . '</td><td>' . (isset($obj_m_due) ? number_format($obj_m_due, 2) : '0.00') . '</td><td>' . (isset($obj_y_due) ? number_format($obj_y_due, 2) : '0.00') . '</td></tr>';
   
   echo '<table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan="4" style="font-size:15px;text-align:center;font-weight:bold;">Miscelleneous</td>
</tr>
                                    </thead>
                                    <tbody>';
echo '<tr><td style="font-weight:bold;">Additional Charge</td><td>' . ($nightaudit_hall['additional_charges'] && !empty($checkuser) ? $nightaudit_hall['additional_charges'] : '0.00') . '</td><td>' . ($nightaudit_current_month_hall['additional_charges'] ? $nightaudit_current_month_hall['additional_charges']: '0.00') . '</td><td>' . ($nightaudit_current_year_hall['additional_charges'] ? $nightaudit_current_year_hall['additional_charges'] : '0.00') . '</td></tr>';
echo "</tbody></table>";

   
   
    // echo '<tr><td style="font-weight:bold;">CGST</td><td>' . (isset($obj->cgst) && !empty($checkuser) ? number_format($obj->cgst, 2) : '0.00') . '</td><td>' . (isset($month->cgst) ? number_format($month->cgst, 2) : '0.00') . '</td><td>' . (isset($year->cgst) ? number_format($year->cgst, 2) : '0.00') . '</td></tr>';
    // echo '<tr><td style="font-weight:bold;">SGST</td><td>' . (isset($obj->sgst) && !empty($checkuser) ? number_format($obj->sgst, 2) : '0.00') . '</td><td>' . (isset($month->sgst) ? number_format($month->sgst, 2) : '0.00') . '</td><td>' . (isset($year->sgst) ? number_format($year->sgst, 2) : '0.00') . '</td></tr>';
    
    // Accumulate totals
    $totalBill += $obj->total_amount + $month->total_amount + $year->total_amount;
    $totalServiceCharge += $obj->service_charge + $month->service_charge + $year->service_charge;
    $totalVAT += $obj->VAT + $month->VAT + $year->VAT;
    ?>
</tbody>
<!-- <tfoot>
<tr>
        <td style="font-weight:bold;">Total</td>
        <td>
            <?php
                $totalRestaurant = $obj->bill_amount + $obj->total_amount + $obj->discount + $obj->customerpaid + $obj_due;
                echo number_format($totalRestaurant, 2);
            ?>
        </td>
        <td>
            <?php
                $totalRestaurantMonth = $month->bill_amount + $month->total_amount + $month->discount + $month->customerpaid + $obj_m_due;
                echo number_format($totalRestaurantMonth, 2);
            ?>
        </td>
        <td>
            <?php
                $totalRestaurantYear = $year->bill_amount + $year->total_amount + $year->discount + $year->customerpaid + $obj_y_due;
                echo number_format($totalRestaurantYear, 2);
            ?>
        </td>
    </tr>
</tfoot> -->
                                </table>
                                 <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>BANQUET HALL - ADVANCE AMOUNT</td>
</tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                  
echo '<tr><td style="font-weight:bold;">Advance</td><td>' . (isset($hall_adv->total_advance_amount) && !empty($checkuser) ? number_format($hall_adv->total_advance_amount, 2) : '0.00')  . '</td><td>' . number_format($hall_adv_m->total_advance_amount, 2) . '</td><td>' . number_format($hall_adv_y->total_advance_amount, 2) . '</td></tr>';
                                   ?>
                                  
                                        
                                        </tbody>
                                        </table>
                                <table id="" class="table table-bordered table-striped table-hover">
                                    <thead>
                                  
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>TAX SPLIT</td>
</tr>
                                    </thead>
                                    <tbody>

    <?php

 $obj = $nightaudit;   
                                       
                                   
                                  //     echo $obj;
                                     //  $obj_post=$nightaudit_postbill[0];
                                    $month = $nightaudit_current_month;//$month_post=$nightaudit_month_postbill[0];
                                     $year=$nightaudit_current_year;//$year_post=$nightaudit_year_postbill[0];
$obj_room = $nightaudit[0];    
$month_room = $nightaudit_current_month[0];
 $year_room=$nightaudit_current_year[0];

  // Assuming $nightaudit_restaurent, $nightaudit_restaurent_current_month, and $nightaudit_restaurent_current_year have the data
  $obj_res = $nightaudit_restaurent[0];  
  $month_res = $nightaudit_restaurent_current_month[0];
  $year_res = $nightaudit_restaurent_current_year[0];
  
  //Room                     
    $room_additional_cgst_day = $obj_post->additional_charges * 0.09; 
$room_additional_sgst_day = $obj_post->additional_charges * 0.09;
  $room_additional_cgst_month = $month_post->additional_charges * 0.09; 
$room_additional_sgst_month = $month_post->additional_charges * 0.09;
  $room_additional_cgst_year = $year_post->additional_charges * 0.09; 
$room_additional_sgst_year = $year_post->additional_charges * 0.09;
  
  //Hall
  $hall_additional_cgst_day = $nightaudit_hall['additional_charges'] * 0.09; 
$hall_additional_sgst_day = $nightaudit_hall['additional_charges'] * 0.09;
  $hall_additional_cgst_month = $nightaudit_current_month_hall['additional_charges'] * 0.09; 
$hall_additional_sgst_month = $nightaudit_current_month_hall['additional_charges'] * 0.09;
  $hall_additional_cgst_year = $nightaudit_current_year_hall['additional_charges'] * 0.09; 
$hall_additional_sgst_year = $nightaudit_current_year_hall['additional_charges'] * 0.09;


  $obj_hall = $nightaudit_hall[0];  
  $month_hall = $nightaudit_current_month_hall[0];
  $year_hall = $nightaudit_current_year_hall[0];

echo '<tr><td style="font-weight:bold;">Room - CGST(6%)</td><td>' . (isset($obj['cgst']) && !empty($checkuser) ? number_format($obj['cgst'], 2) : '0.00') . '</td><td>' . (isset($month['cgst']) ? number_format($month['cgst'], 2) : '0.00') . '</td><td>' . (isset($year['cgst']) ? number_format($year['cgst'], 2) : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Room - SGST(6%)</td><td>' . (isset($obj['sgst']) && !empty($checkuser) ? number_format($obj['sgst'], 2) : '0.00') . '</td><td>' . (isset($month['sgst']) ? number_format($month['sgst'], 2) : '0.00') . '</td><td>' . (isset($year['sgst']) ? number_format($year['sgst'], 2) : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Room - Add.Charge - CGST(9%)</td><td>' . (isset( $room_additional_cgst_day) && !empty($checkuser) ? number_format( $room_additional_cgst_day, 2) : '0.00') . '</td><td>' . (isset($room_additional_cgst_month) ? number_format($room_additional_cgst_month, 2) : '0.00') . '</td><td>' . (isset($room_additional_cgst_year) ? number_format($room_additional_cgst_year, 2) : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Room - Add.Charge - SGST(9%)</td><td>' . (isset( $room_additional_cgst_day) && !empty($checkuser) ? number_format( $room_additional_cgst_day, 2) : '0.00') . '</td><td>' . (isset($room_additional_sgst_month) ? number_format($room_additional_sgst_month, 2) : '0.00') . '</td><td>' . (isset($room_additional_sgst_year) ? number_format($room_additional_sgst_year, 2) : '0.00') . '</td></tr>';


    echo '<tr><td style="font-weight:bold;">Food - CGST(2.5%)</td><td>' . (isset($obj_tax) && !empty($checkuser) ? number_format($obj_tax, 2) : '0.00')  . '</td><td>' . number_format($month_tax, 2) . '</td><td>' . number_format($year_tax, 2) . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Food - SGST(2.5%)</td><td>' . (isset($obj_tax) && !empty($checkuser) ? number_format($obj_tax, 2) : '0.00')  . '</td><td>' . number_format($month_tax, 2) . '</td><td>' . number_format($year_tax, 2) . '</td></tr>';

echo '<tr><td style="font-weight:bold;">Banquet - CGST(9%)</td><td>' . (isset($nightaudit_hall['cgst']) && !empty($checkuser) ? number_format($nightaudit_hall['cgst'], 2) : '0.00')  . '</td><td>' . number_format($nightaudit_current_month_hall['cgst'], 2) . '</td><td>' . number_format($nightaudit_current_year_hall['cgst'], 2) . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Banquet - SGST(9%)</td><td>' . (isset($nightaudit_hall['sgst']) && !empty($checkuser) ? number_format($nightaudit_hall['sgst'], 2) : '0.00')  . '</td><td>' . number_format($nightaudit_current_month_hall['sgst'], 2) . '</td><td>' . number_format($nightaudit_current_year_hall['sgst'], 2) . '</td></tr>';

echo '<tr><td style="font-weight:bold;">Banquet - Add.Charge - CGST(9%)</td><td>' . (isset($hall_additional_cgst_day) && !empty($checkuser) ? number_format($hall_additional_cgst_day, 2) : '0.00') . '</td><td>' . (isset($hall_additional_cgst_month) ? number_format($hall_additional_cgst_month, 2) : '0.00') . '</td><td>' . (isset($hall_additional_cgst_year) ? number_format($hall_additional_cgst_year, 2) : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Banquet - Add.Charge - SGST(9%)</td><td>' . (isset($hall_additional_sgst_day) && !empty($checkuser) ? number_format($hall_additional_sgst_day, 2) : '0.00') . '</td><td>' . (isset($hall_additional_sgst_month) ? number_format($hall_additional_sgst_month, 2) : '0.00') . '</td><td>' . (isset($hall_additional_sgst_year) ? number_format($hall_additional_sgst_year, 2) : '0.00') . '</td></tr>';

    ?>
</tbody>
<tfoot>

    <tr>
        <td>Total</td>
         <td>
      <?php if (!empty($checkuser)): ?>
   
        <?php
            $totalFoodTax = $obj_tax + $obj_tax + $nightaudit_hall['cgst'] + $nightaudit_hall['sgst'] + $obj['cgst'] + $obj['sgst'];
            echo number_format($totalFoodTax, 2);
        ?>
 
<?php endif; ?>
   </td>
        <td>
            <?php
                $totalFoodMonthTax = $month_tax + $month_tax+$nightaudit_current_month_hall['cgst']+$nightaudit_current_month_hall['sgst']+$month['cgst']+$month['sgst'];
                echo number_format($totalFoodMonthTax, 2);
            ?>
        </td>
        <td>
            <?php
                $totalFoodYearTax = $year_tax + $year_tax+$nightaudit_current_year_hall['cgst']+$nightaudit_current_year_hall['sgst']+$month['cgst']+$month['sgst'];
                echo number_format($totalFoodYearTax, 2);
            ?>
        </td>
    </tr>
</tfoot>
                                </table>
                                                                                
                            
                               <table border="1" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>ROOMS - PAYMENT METHOD SPLIT</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalToday = $totalMonth = $totalYear = 0;
        foreach ($payment_data as $paymenttype => $data):
            $totalToday += isset($data['today']) ? $data['today'] : 0;
            if($paymenttype !=='Bill to company'){ 
                $totalMonth +=  $data['month'] ;
            } else{ 
                $totalMonth += 0;
            }
          
            if($paymenttype !=='Bill to company'){ 
                  $totalYear += isset($data['year']) ? $data['year'] : 0;
            } else{ 
                 $totalYear += 0;
            }
           
            ?>
            <tr>
                <td style="font-weight:bold;"><?= $paymenttype ?></td>
                <td>
                    <?php if (!empty($checkuser) && isset($data['today'])): ?>
                        <?= $data['today'] ?>
                    <?php endif; ?>
                </td>
                <td><?php if($paymenttype !=='Bill to company'){ echo $data['month'];} else{ echo 0 ; } ?></td>
                <td><?php if($paymenttype !=='Bill to company'){ echo $data['year'];} else{ echo 0 ; } ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>Total</td>
            <td>
                <?php if (!empty($checkuser)): ?>
                    <?= number_format($totalToday, 2) ?>
                <?php endif; ?>
            </td>
            <td><?php echo   number_format($totalMonth, 2) ?></td>
            <td><?= number_format($totalYear, 2) ?></td>
        </tr>
    </tbody>
</table>

  <table border="1" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>RESTAURANT - PAYMENT METHOD SPLIT</td>
        </tr>
    </thead>
    <tbody>
        <?php
      //  print_r($payment_data_resto);
        $totalToday_res = $totalMonth_res = $totalYear_res = 0;
        foreach ($payment_data_resto as $paymenttype => $data):
            $totalToday_res += isset($data['today']) ? $data['today'] : 0;

            if($paymenttype !=='Bill to company'){ 
                $totalMonth_res += isset($data['month']) ? $data['month'] : 0;
            } else{ 
                $totalMonth_res += 0;
            }
          
            if($paymenttype !=='Bill to company'){ 
                  $totalYear_res += isset($data['year']) ? $data['year'] : 0;
            } else{ 
                 $totalYear_res += 0;
            }
            ?>
            <tr>
                <td style="font-weight:bold;"><?= $paymenttype ?></td>
                <td>
    <?= !empty($checkuser) ? (isset($data['today']) ? number_format($data['today'], 2) : '0.00') : '' ?>
</td>
 <td><?php if($paymenttype !=='Bill to company'){ echo $data['month'];} else{ echo 0 ; } ?></td>
                <td><?php if($paymenttype !=='Bill to company'){ echo $data['year'];} else{ echo 0 ; } ?></td>
          
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>Total</td>
            
               <td>
    <?= !empty($checkuser) ? (isset($totalToday_res) ? number_format($totalToday_res, 2) : '0.00') : '' ?>
</td>


            
            <td><?= number_format($totalMonth_res, 2) ?></td>
            <td><?= number_format($totalYear_res, 2) ?></td>
        </tr>
    </tbody>
</table>
<table border="1" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>BANQUET HALL - PAYMENT METHOD SPLIT</td>
        </tr>
    </thead>
     <tbody>
        <?php
        // Get all unique payment types
        $paymentTypes = array_unique(array_merge(array_keys($sumToday), array_keys($sumMonth), array_keys($sumYear)));

        // Initialize totals for each column
        $totalToday1 = $totalMonth1 = $totalYear1 = 0;

        // Iterate through payment types and display corresponding amounts
        foreach ($paymentTypes as $type) {
            echo "<tr>";
            echo "<td>$type</td>";
         echo "<td>" . (!empty($checkuser) && isset($sumToday[$type]) ? $sumToday[$type] : 0) . "</td>";
if($type !=='Bill to company'){
            echo "<td>" .  $sumMonth[$type] . "</td>";
}else{
      echo "<td>" .  '0' . "</td>";
}
if($type !=='Bill to company'){
            echo "<td>" .  $sumYear[$type] . "</td>";
}else{
 echo "<td>" .  '0' . "</td>";

}
            echo "</tr>";

            // Accumulate totals for each column
            $totalToday1 += $sumToday[$type] ?? 0;
              if($type !=='Bill to company'){ 
                $totalMonth1 += $sumMonth[$type];
            } else{ 
                $totalMonth1 += 0;
            }
          
            if($type !=='Bill to company'){ 
                  $totalYear1 += $sumYear[$type];
            } else{ 
                 $totalYear1 += 0;
            }
           
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
         <td><?= isset($totalToday1) && !empty($checkuser) ? number_format($totalToday1, 2) : '0.00' ?></td>

            <td><?= $totalMonth1 ?></td>
            <td><?= $totalYear1 ?></td>
        </tr>
    </tfoot>
</table>
 <?php
        $totalToday = $totalMonth = $totalYear = 0;
        foreach ($payment_data as $paymenttype => $data){
            $totalToday += isset($data['today']) ? $data['today'] : 0;
            if($paymenttype !==''){ 
                $totalMonth +=  $data['month'] ;
            } else{ 
                $totalMonth += 0;
            }
          
            if($paymenttype !==''){ 
                  $totalYear += isset($data['year']) ? $data['year'] : 0;
            } else{ 
                 $totalYear += 0;
            }
        }
       $totalToday_res = $totalMonth_res = $totalYear_res = 0;
        foreach ($payment_data_resto as $paymenttype => $data){
            $totalToday_res += isset($data['today']) ? $data['today'] : 0;

            if($paymenttype !==''){ 
                $totalMonth_res += isset($data['month']) ? $data['month'] : 0;
            } else{ 
                $totalMonth_res += 0;
            }
          
            if($paymenttype !==''){ 
                  $totalYear_res += isset($data['year']) ? $data['year'] : 0;
            } else{ 
                 $totalYear_res += 0;
            }
        }
            ?>
    <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>PAYMENT DETAILS</td>
                                  </tr>
                                                                      </thead>
                                                                      <tbody>
        <tr>
            <td style="font-weight:bold;">Room</td>
           <td><?= isset($totalToday) && !empty($checkuser) ? number_format($totalToday, 2) : '0.00' ?></td>

            <td><?= isset($totalMonth) ? number_format($totalMonth, 2) : '0.00' ?></td>
            <td><?= isset($totalYear) ? number_format($totalYear, 2) : '0.00' ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Restaurant</td>
     <?php       // Assuming $nightaudit_restaurent, $nightaudit_restaurent_current_month, and $nightaudit_restaurent_current_year have the data
    $obj_r = $nightaudit_restaurent;  
  //  print_r($obj_r);
    $month_r = $nightaudit_restaurent_current_month;
    $year_r = $nightaudit_restaurent_current_year; ?>
        
        
        
          <td><?= isset($totalToday_res) && !empty($checkuser) ? number_format($obj_r['customerpaid'], 2) : '0.00' ?></td>
            <td><?= isset($totalMonth_res) ? number_format($month_r['customerpaid'], 2) : '0.00' ?></td>
            <td><?= isset($totalYear_res) ? number_format($year_r['customerpaid'], 2) : '0.00' ?></td>
        </tr>
       <tr>
            <td style="font-weight:bold;">BANQUET HALL</td>
           <td><?= isset($totalToday1) && !empty($checkuser) ? number_format($totalToday1, 2) : '0.00' ?></td>
            <td><?= isset($totalMonth1) ? number_format($totalMonth1, 2) : '0.00' ?></td>
            <td><?= isset($totalYear1) ? number_format($totalYear1, 2) : '0.00' ?></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td>Total</td>
      <td><?= isset($totalToday) && !empty($checkuser) ? number_format($totalToday + $obj_r['customerpaid'] + $totalToday1, 2) : '0.00' ?></td>

        <td><?= isset($totalMonth) ? number_format($totalMonth + $month_r['customerpaid'] + $totalMonth1, 2) : '0.00' ?></td>
        <td><?= isset($totalYear) ? number_format($totalYear + $year_r['customerpaid'] + $totalYear1, 2) : '0.00' ?></td>
    </tr>
    </tfoot>
    </table>
    <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
       <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>ROOM STATUS</td>
        </tr>
         </thead>
       <tbody>
       <tr>
            <td colspan="2" style="text-align:right;font-weight:bold;">Total Rooms </td>
            <td colspan="2" style="text-align:left;"><?= $total_rooms[0]->room_count ?></td>
           
        </tr>
        <tr>
            <td style="font-weight:bold;">Checkin </td>
            <td><?= $today_status['checkin'] ?></td>
            <td><?= $current_month_status['checkin'] ?></td>
            <td><?= $current_year_status['checkin'] ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Checkout</td>
            <td><?= $today_status['checkout'] ?></td>
            <td><?= $current_month_status['checkout'] ?></td>
            <td><?= $current_year_status['checkout'] ?></td>
        </tr>
        <!-- <tr>
            <td style="font-weight:bold;">Booked</td>
            <td><?= $today_status['booked'] ?></td>
            <td><?= $current_month_status['booked'] ?></td>
            <td><?= $current_year_status['booked'] ?></td>
        </tr> -->
        <tr>
            <td style="font-weight:bold;">Canceled</td>
            <td><?= $today_status['canceled'] ?></td>
            <td><?= $current_month_status['canceled'] ?></td>
            <td><?= $current_year_status['canceled'] ?></td>
        </tr>
        <!-- <tr>
            <td style="font-weight:bold;">Finish</td>
            <td><?= $today_status['finish'] ?></td>
            <td><?= $current_month_status['finish'] ?></td>
            <td><?= $current_year_status['finish'] ?></td>
        </tr> -->
    </tbody>
    <!-- <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $totalToday + $totalToday_res + $totalToday_hall ?></td>
            <td><?= $totalMonth + $totalMonth_res + $totalMonth_hall ?></td>
            <td><?= $totalYear + $totalYear_res + $totalYear_hall ?></td>
        </tr>
    </tfoot> -->
    </table>
    
    <table border="1" class="table table-bordered table-striped table-hover">
    <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>ROOM TYPE</td>
                                   </tr>
                                    </thead>
                                    <tbody>

    <?php foreach ($processed_data as $roomtype => $counts): ?>
        <tr>
            <td style="font-weight:bold;"><?php echo $roomtype; ?></td>
            <td><?php echo isset($counts['today']) && !empty($checkuser) ? $counts['today'] : 0; ?></td>
            <td><?php echo isset($counts['month']) ? $counts['month'] : 0; ?></td>
            <td><?php echo isset($counts['year']) ? $counts['year'] : 0; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
                            </div>
                            </div>
                        </div>
                        <style>
                            
                             ul.a {list-style-type: none;}
                        </style>
                     
          
                    </div>
                </div>
            </div>
                     <div class="text-right">
                           <?php     $saveid=$this->session->userdata('id'); 
		    $checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid',$saveid)->where('status',0)->order_by('id','DESC')->get()->row(); 
            if(!empty($checkuser)){ ?>
            <div>
                <li style='width:100px;list-style-type: none;' class="day-close"><a href="javascript:;" class="btn" onclick="closeopenresister()" role="button"><span class="text-white"><?php echo display("day_close") ?></span></a></li>
            </div>
            <?php }   ?>
                        </div>
            <script>
            $('.js__action--print').click(function() {
    window.print();
    return false;
});</script>
        </div>
    </div>
</div>
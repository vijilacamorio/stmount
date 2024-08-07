<script src="<?php echo MOD_URL.$module;?>/assets/js/prechaseReport.js"></script>
<link type="text/css" href="<?php echo MOD_URL.$module;?>/assets/css/table.css">
<style>
    table {
    table-layout : fixed;
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
                                <input type="button" class="button-print btn btn-info text-white button-print" name="btnPrint"
                                    id="btnPrint" value="Print" onclick="printContent('printArea')" />
                            </small>
                        </h4>

               
            </div>
            <!-- <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <?php echo form_open('report/index',array('class' => 'form-inline'))?>
                        <?php
                        $today = date('d-m-Y'); ?>
                        <div class="form-group">
                            <label class="padding_right_5px" for="from_date"><?php echo display('start_date') ?>
                            </label>
                            <input type="text" name="from_date" value="<?php echo date('Y-m-d')?>"
                                class="form-control datepickers" id="from_date"
                                placeholder="<?php echo display('start_date') ?>">
                        </div>

                        <div class="form-group">
                            <label class="padding_0_5px" for="to_date"> <?php echo display('end_date') ?> </label>
                            <input type="text" name="to_date" value="<?php echo date('Y-m-d')?>"
                                class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                        </div>
                        &nbsp;<a class="btn btn-success" onclick="getreport()"><span class="text-white">
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
                                     //  echo $no_of_person;die();
                                       $obj = $nightaudit[0];     $obj_post=$nightaudit_postbill[0];
                                    $month = $nightaudit_current_month[0];$month_post=$nightaudit_month_postbill[0];
                                     $year=$nightaudit_current_year[0];$year_post=$nightaudit_year_postbill[0];
     //  print_r($month_post);

$obj_due_room=($obj->total_price)-($obj->paid_amount);
$month_due_room=($month->total_price)-($month->paid_amount);
$year_due_room=($year->total_price)-($year->paid_amount);



echo '<tr><td style="font-weight:bold;">Tariff</td><td>' . ($obj->total_price ? $obj->total_price : '0.00') . '</td><td>' . ($month->total_price ? $month->total_price : '0.00') . '</td><td>' . ($year->total_price ? $year->total_price : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Room Rent</td><td>' . ($obj->roomrate ? $obj->roomrate : '0.00') . '</td><td>' . ($month->roomrate ? $month->roomrate : '0.00') . '</td><td>' . ($year->roomrate ? $year->roomrate : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Advance Amount</td><td>' . ($obj->advance_amount ? $obj->advance_amount : '0.00') . '</td><td>' . ($month->advance_amount ? $month->advance_amount : '0.00') . '</td><td>' . ($year->advance_amount ? $year->advance_amount : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Discount Amount</td><td>' . ($obj->discountamount ? $obj->discountamount : '0.00') . '</td><td>' . ($month->discountamount ? $month->discountamount : '0.00') . '</td><td>' . ($year->discountamount ? $year->discountamount : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Discount</td><td>' . ($obj_post->ex_discount ? $obj_post->ex_discount : '0.00') . '</td><td>' . ($month_post->ex_discount ? $month_post->ex_discount : '0.00') . '</td><td>' . ($year_post->ex_discount ? $year_post->ex_discount : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Special Discount </td><td>' . ($obj_post->special_discount ? $obj_post->special_discount : '0.00') . '</td><td>' . ($month_post->special_discount ? $month_post->special_discount : '0.00') . '</td><td>' . ($year_post->special_discount ? $year_post->special_discount : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Additional Charge</td><td>' . ($obj_post->additional_charges ? $obj_post->additional_charges : '0.00') . '</td><td>' . ($month_post->additional_charges ? $month_post->additional_charges : '0.00') . '</td><td>' . ($year_post->additional_charges ? $year_post->additional_charges : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . ($obj->paid_amount ? $obj->paid_amount : '0.00') . '</td><td>' . ($month->paid_amount ? $month->paid_amount : '0.00') . '</td><td>' . ($year->paid_amount ? $year->paid_amount : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . ($obj_due_room ? $obj_due_room : '0.00') . '</td><td>' . ($month_due_room ? $month_due_room : '0.00') . '</td><td>' . ($year_due_room ? $year_due_room : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">No.of Person</td><td>' . ($no_of_ppl->total_nuofpeople ? $no_of_ppl->total_nuofpeople : '0')  . '</td><td>' . ($no_of_ppl_mnth->total_nuofpeople) . '</td><td>' . ($no_of_ppl_yr->total_nuofpeople) . '</td></tr>';
echo '<tr><td style="font-weight:bold;">No.of Child</td><td>' . ($obj->children ? $obj->children : '0.00') . '</td><td>' . ($month->children ? $month->children : '0.00') . '</td><td>' . ($year->children ? $year->children : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Bed</td><td>' . ($obj->extrabed ? $obj->extrabed : '0.00') . '</td><td>' . ($month->extrabed ? $month->extrabed : '0.00') . '</td><td>' . ($year->extrabed ? $year->extrabed : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Person</td><td>' . ($obj->extraperson ? $obj->extraperson : '0.00') . '</td><td>' . ($month->extraperson ? $month->extraperson : '0.00') . '</td><td>' . ($year->extraperson ? $year->extraperson : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">Extra Child</td><td>' . ($obj->extrachild ? $obj->extrachild : '0.00') . '</td><td>' . ($month->extrachild ? $month->extrachild : '0.00') . '</td><td>' . ($year->extrachild ? $year->extrachild : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">No Show</td><td>' . '' . '</td><td>' . '' . '</td><td>' . '' . '</td></tr>';
echo '<tr><td style="font-weight:bold;">CGST</td><td>' . ($obj->cgst ? $obj->cgst : '0.00') . '</td><td>' . ($month->cgst ? $month->cgst : '0.00') . '</td><td>' . ($year->cgst ? $year->cgst : '0.00') . '</td></tr>';
echo '<tr><td style="font-weight:bold;">SGST</td><td>' . ($obj->sgst ? $obj->sgst : '0.00') . '</td><td>' . ($month->sgst ? $month->sgst : '0.00') . '</td><td>' . ($year->sgst ? $year->sgst : '0.00') . '</td></tr>';

            
            
            
            
            
            
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
    $obj = $nightaudit_restaurent[0];  $obj_due=($obj->bill_amount)-($obj->customerpaid);
    $month = $nightaudit_restaurent_current_month[0];$obj_m_due=($month->bill_amount)-($month->customerpaid);
    $year = $nightaudit_restaurent_current_year[0];$obj_y_due=($year->bill_amount)-($year->customerpaid);

    // Displaying data rows
    echo '<tr><td style="font-weight:bold;">Tariff</td><td>' . (isset($obj->bill_amount) ? number_format($obj->bill_amount, 2) : '0.00') . '</td><td>' . (isset($month->bill_amount) ? number_format($month->bill_amount, 2) : '0.00') . '</td><td>' . (isset($year->bill_amount) ? number_format($year->bill_amount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Total Amount (excl.tax)</td><td>' . (isset($obj->total_amount) ? number_format($obj->total_amount, 2) : '0.00') . '</td><td>' . (isset($month->total_amount) ? number_format($month->total_amount, 2) : '0.00') . '</td><td>' . (isset($year->total_amount) ? number_format($year->total_amount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Discount</td><td>' . (isset($obj->discount) ? number_format($obj->discount, 2) : '0.00') . '</td><td>' . (isset($month->discount) ? number_format($month->discount, 2) : '0.00') . '</td><td>' . (isset($year->discount) ? number_format($year->discount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . (isset($obj->customerpaid) ? number_format($obj->customerpaid, 2) : '0.00') . '</td><td>' . (isset($month->customerpaid) ? number_format($month->customerpaid, 2) : '0.00') . '</td><td>' . (isset($year->customerpaid) ? number_format($year->customerpaid, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . (isset($obj_due) ? number_format($obj_due, 2) : '0.00') . '</td><td>' . (isset($obj_m_due) ? number_format($obj_m_due, 2) : '0.00') . '</td><td>' . (isset($obj_y_due) ? number_format($obj_y_due, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">CGST</td><td>' . (isset($obj->cgst) ? number_format($obj->cgst, 2) : '0.00') . '</td><td>' . (isset($month->cgst) ? number_format($month->cgst, 2) : '0.00') . '</td><td>' . (isset($year->cgst) ? number_format($year->cgst, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">SGST</td><td>' . (isset($obj->sgst) ? number_format($obj->sgst, 2) : '0.00') . '</td><td>' . (isset($month->sgst) ? number_format($month->sgst, 2) : '0.00') . '</td><td>' . (isset($year->sgst) ? number_format($year->sgst, 2) : '0.00') . '</td></tr>';
    
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



    // Displaying data rows
    echo '<tr><td style="font-weight:bold;">Tariff</td><td>' . (isset($obj->totalamount) ? number_format($obj->totalamount, 2) : '0.00') . '</td><td>' . (isset($month->totalamount) ? number_format($month->totalamount, 2) : '0.00') . '</td><td>' . (isset($year->totalamount) ? number_format($year->totalamount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Room Rent (excl.tax)</td><td>' . (isset($obj->rent) ? number_format($obj->rent, 2) : '0.00') . '</td><td>' . (isset($month->rent) ? number_format($month->rent, 2) : '0.00') . '</td><td>' . (isset($year->rent) ? number_format($year->rent, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Discount</td><td>' . (isset($obj->discount) ? number_format($obj->discount, 2) : '0.00') . '</td><td>' . (isset($month->discount) ? number_format($month->discount, 2) : '0.00') . '</td><td>' . (isset($year->discount) ? number_format($year->discount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Amount Received</td><td>' . (isset($obj->paid_amount) ? number_format($obj->paid_amount, 2) : '0.00') . '</td><td>' . (isset($month->paid_amount) ? number_format($month->paid_amount, 2) : '0.00') . '</td><td>' . (isset($year->paid_amount) ? number_format($year->paid_amount, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">Due Amount</td><td>' . (isset($obj_due) ? number_format($obj_due, 2) : '0.00') . '</td><td>' . (isset($obj_m_due) ? number_format($obj_m_due, 2) : '0.00') . '</td><td>' . (isset($obj_y_due) ? number_format($obj_y_due, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">CGST</td><td>' . (isset($obj->cgst) ? number_format($obj->cgst, 2) : '0.00') . '</td><td>' . (isset($month->cgst) ? number_format($month->cgst, 2) : '0.00') . '</td><td>' . (isset($year->cgst) ? number_format($year->cgst, 2) : '0.00') . '</td></tr>';
    echo '<tr><td style="font-weight:bold;">SGST</td><td>' . (isset($obj->sgst) ? number_format($obj->sgst, 2) : '0.00') . '</td><td>' . (isset($month->sgst) ? number_format($month->sgst, 2) : '0.00') . '</td><td>' . (isset($year->sgst) ? number_format($year->sgst, 2) : '0.00') . '</td></tr>';
    
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
                                  
<td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>TAX SPLIT</td>
</tr>
                                    </thead>
                                    <tbody>

    <?php


$obj_room = $nightaudit[0];    
$month_room = $nightaudit_current_month[0];
 $year_room=$nightaudit_current_year[0];

  // Assuming $nightaudit_restaurent, $nightaudit_restaurent_current_month, and $nightaudit_restaurent_current_year have the data
  $obj_res = $nightaudit_restaurent[0];  
  $month_res = $nightaudit_restaurent_current_month[0];
  $year_res = $nightaudit_restaurent_current_year[0];

  $obj_hall = $nightaudit_hall[0];  
  $month_hall = $nightaudit_current_month_hall[0];
  $year_hall = $nightaudit_current_year_hall[0];
  echo '<tr><td style="font-weight:bold;">Room - CGST(6%)</td><td>' . (isset($obj_room->cgst) ? number_format($obj_room->cgst, 2) : '0.00') . '</td><td>' . (isset($month_room->cgst) ? number_format($month_room->cgst, 2) : '0.00') . '</td><td>' . (isset($year_room->cgst) ? number_format($year_room->cgst, 2) : '0.00') . '</td></tr>';
  echo '<tr><td style="font-weight:bold;">Room - SGST(6%)</td><td>' . (isset($obj_room->sgst) ? number_format($obj_room->sgst, 2) : '0.00') . '</td><td>' . (isset($month_room->sgst) ? number_format($month_room->sgst, 2) : '0.00') . '</td><td>' . (isset($year_room->sgst) ? number_format($year_room->sgst, 2) : '0.00') . '</td></tr>';
  
echo '<tr><td style="font-weight:bold;">Food - CGST(2.5%)</td><td>'.number_format($obj_res->cgst, 2).'</td><td>'.number_format($month_res->cgst, 2).'</td><td>'.number_format($year_res->cgst, 2).'</td></tr>';
echo '<tr><td style="font-weight:bold;">Food - SGST(2.5%)</td><td>'.number_format($obj_res->sgst, 2).'</td><td>'.number_format($month_res->sgst, 2).'</td><td>'.number_format($year_res->sgst, 2).'</td></tr>';
echo '<tr><td style="font-weight:bold;">Banquet - CGST(6%)</td><td>'.number_format($obj_hall->cgst, 2).'</td><td>'.number_format($month_hall->cgst, 2).'</td><td>'.number_format($year_hall->cgst, 2).'</td></tr>';
echo '<tr><td style="font-weight:bold;">Banquet - SGST(6%)</td><td>'.number_format($obj_hall->sgst, 2).'</td><td>'.number_format($month_hall->sgst, 2).'</td><td>'.number_format($year_hall->sgst, 2).'</td></tr>';

    // Accumulate totals
    // $totalBill += $obj->total_amount + $month->total_amount + $year->total_amount;
    // $totalServiceCharge += $obj->service_charge + $month->service_charge + $year->service_charge;
    // $totalVAT += $obj->VAT + $month->VAT + $year->VAT;
    ?>
</tbody>
<tfoot>

    <tr>
        <td>Total</td>
        <td>
            <?php
                $totalFoodTax = $obj_res->cgst + $obj_res->sgst;
                echo number_format($totalFoodTax, 2);
            ?>
        </td>
        <td>
            <?php
                $totalFoodMonthTax = $month_res->cgst + $month_res->sgst;
                echo number_format($totalFoodMonthTax, 2);
            ?>
        </td>
        <td>
            <?php
                $totalFoodYearTax = $year_res->cgst + $year_res->sgst;
                echo number_format($totalFoodYearTax, 2);
            ?>
        </td>
    </tr>
</tfoot>
                                </table>
                            
                                <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>ROOMS - PAYMENT METHOD SPLIT</td>
                                  </tr>
                                                                      </thead>
       <tbody>
       <?php
            $totalToday = $totalMonth = $totalYear = 0;
            foreach ($payment_data as $paymenttype => $data):
                $totalToday += isset($data['today']) ? $data['today'] : 0;
                $totalMonth += isset($data['month']) ? $data['month'] : 0;
                $totalYear += isset($data['year']) ? $data['year'] : 0;
            ?>
                <tr>
                    <td style="font-weight:bold;"><?= $paymenttype ?></td>
                    <td><?= isset($data['today']) ? $data['today'] : '0.00' ?></td>
                    <td><?= isset($data['month']) ? $data['month'] : '0.00' ?></td>
                    <td><?= isset($data['year']) ? $data['year'] : '0.00' ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Total</td>
                <td><?= number_format($totalToday, 2) ?></td>
                <td><?= number_format($totalMonth, 2) ?></td>
                <td><?= number_format($totalYear, 2) ?></td>
            </tr>
        </tbody>
    </table>
    <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>RESTAURANT - PAYMENT METHOD SPLIT</td>
                                  </tr>
                                                                      </thead>
       <tbody>
       <?php
            $totalToday_res = $totalMonth_res = $totalYear_res = 0;
            foreach ($payment_data_resto as $paymenttype => $data):
                $totalToday_res += isset($data['today']) ? $data['today'] : 0;
                $totalMonth_res += isset($data['month']) ? $data['month'] : 0;
                $totalYear_res += isset($data['year']) ? $data['year'] : 0;
            ?>
                <tr>

                    <td style="font-weight:bold;"><?= $paymenttype ?></td>
                    <td><?= isset($data['today']) ? $data['today'] : '0.00' ?></td>
                    <td><?= isset($data['month']) ? $data['month'] : '0.00' ?></td>
                    <td><?= isset($data['year']) ? $data['year'] : '0.00' ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Total</td>
                <td><?= number_format($totalToday_res, 2) ?></td>
                <td><?= number_format($totalMonth_res, 2) ?></td>
                <td><?= number_format($totalYear_res, 2) ?></td>
            </tr>
        </tbody>
    </table>
    <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>BANQUET HALL - PAYMENT METHOD SPLIT</td>
                                  </tr>
                                                                      </thead>
       <tbody>
       <?php
            $totalToday_hall = $totalMonth_hall = $totalYear_hall = 0;
            foreach ($payment_hall as $paymenttype => $data):
                $totalToday_hall += isset($data['today']) ? $data['today'] : 0;
                $totalMonth_hall += isset($data['month']) ? $data['month'] : 0;
                $totalYear_hall += isset($data['year']) ? $data['year'] : 0;
            ?>
                <tr>

                    <td style="font-weight:bold;"><?= $paymenttype ?></td>
                    <td><?= isset($data['today']) ? $data['today'] : '0.00' ?></td>
                    <td><?= isset($data['month']) ? $data['month'] : '0.00' ?></td>
                    <td><?= isset($data['year']) ? $data['year'] : '0.00' ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Total</td>
                <td><?= number_format($totalToday_hall, 2) ?></td>
                <td><?= number_format($totalMonth_hall, 2) ?></td>
                <td><?= number_format($totalYear_hall, 2) ?></td>
            </tr>
        </tbody>
    </table>
    <table border="1" class="table table-bordered table-striped table-hover">
        
        <thead>
                                  
                                  <td colspan='4' style='font-size:15px;text-align:center;font-weight:bold;'>PAYMENT DETAILS</td>
                                  </tr>
                                                                      </thead>
                                                                      <tbody>
        <tr>
            <td style="font-weight:bold;">Room</td>
            <td><?= isset($totalToday) ? number_format($totalToday, 2) : '0.00' ?></td>
            <td><?= isset($totalMonth) ? number_format($totalMonth, 2) : '0.00' ?></td>
            <td><?= isset($totalYear) ? number_format($totalYear, 2) : '0.00' ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Restaurant</td>
            <td><?= isset($totalToday_res) ? number_format($totalToday_res, 2) : '0.00' ?></td>
            <td><?= isset($totalMonth_res) ? number_format($totalMonth_res, 2) : '0.00' ?></td>
            <td><?= isset($totalYear_res) ? number_format($totalYear_res, 2) : '0.00' ?></td>
        </tr>
        <tr>
            <td style="font-weight:bold;">BANQUET HALL</td>
            <td><?= isset($totalToday_hall) ? number_format($totalToday_hall, 2) : '0.00' ?></td>
            <td><?= isset($totalMonth_hall) ? number_format($totalMonth_hall, 2) : '0.00' ?></td>
            <td><?= isset($totalYear_hall) ? number_format($totalYear_hall, 2) : '0.00' ?></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td>Total</td>
        <td><?= isset($totalToday) ? number_format($totalToday + $totalToday_res + $totalToday_hall, 2) : '0.00' ?></td>
        <td><?= isset($totalMonth) ? number_format($totalMonth + $totalMonth_res + $totalMonth_hall, 2) : '0.00' ?></td>
        <td><?= isset($totalYear) ? number_format($totalYear + $totalYear_res + $totalYear_hall, 2) : '0.00' ?></td>
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
            <td><?= $$today_status['checkin'] ?></td>
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
            <td><?php echo isset($counts['today']) ? $counts['today'] : 0; ?></td>
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
                     
                        <div class="text-right">
                           <?php     $saveid=$this->session->userdata('id'); 
		    $checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid',$saveid)->where('status',0)->order_by('id','DESC')->get()->row(); 
            if(!empty($checkuser)){ ?>
            <div>
                <li style='width:100px;list-style-type: none;' class="day-close"><a href="javascript:;" class="btn" onclick="closeopenresister()" role="button"><span class="text-white"><?php echo display("day_close") ?></span></a></li>
            </div>
            <?php }   ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                    <th style="width:80px;"><?php echo ('Bill Date') ?></th>
                            <th><?php echo display('bill_no') ?></th>
                            <th><?php echo display('room_no') ?></th>
                             <th><?php echo display('Name_of_the_guest') ?></th>
                           
                      <th><?php echo  ('CGST(2.5%)') ?></th>
                            <th><?php echo  ('SGST(2.5%)') ?></th>
                            <th><?php echo  ('Tax(5%)') ?></th>
                             <th><?php echo display('discount') ?></th>
  <th><?php echo  ('Taxable Value'); ?></th>
        <th><?php echo  ('Total Value'); ?></th>
                         
                        
                            <th><?php echo  ('User') ?></th>

                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
    <?php
    
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
     foreach ($food_info as $report) {
     ?>
        <tr>
            
            <?php
            $date_time = new DateTime($report->date_time);
            $date_only = $date_time->format('Y-m-d');
            echo '<td>' . html_escape($report->bill_date) . '</td>';
            ?>
            <td><?php echo html_escape($report->order_id); ?></td>
            <td style="word-break: break-all;"><?php echo html_escape($report->room_number); ?></td>
           
           <td style="word-break: break-all;"><?php echo html_escape($report->firstname." ".$report->lastname); ?></td>   
 
 
<td><?php echo  ($report->bill_amount - $report->total_amount)/2; ?></td>
<td><?php echo ($report->bill_amount - $report->total_amount)/2; ?></td>
<td><?php echo $report->bill_amount - $report->total_amount; ?></td>
<td><?php echo $report->discount ; ?></td>
<td><?php echo $report->total_amount  ; ?></td>


 <td><?php echo html_escape($report->bill_amount); ?></td>
            <!--<td><?php //echo html_escape($report->total_price); ?></td>-->
 <td><?php echo html_escape($userinfo->firstname . ' ' . $userinfo->lastname); ?></td>

            </tr>
            <?php 
         $total_no_of_people += $sum_nuofpeople;
                $total_extra_bed += isset($report->bedcharge) && isset($report->extrabed) && is_numeric($report->bedcharge) && is_numeric($report->extrabed) ? $report->bedcharge * $report->extrabed : 0;
                $total_extra_person += isset($report->personcharge) && isset($sum_extraperson) && is_numeric($report->personcharge) && is_numeric($sum_extraperson) ? $report->personcharge * $sum_extraperson : 0;
                $total_extra_child += isset($report->childcharge) && isset($sum_extrachild) && is_numeric($report->childcharge) && is_numeric($sum_extrachild) ? $report->childcharge * $sum_extrachild : 0;
                $total_food_cgst += ($report->bill_amount - $report->total_amount)/2;
               
                $total_food_sgst += ($report->bill_amount - $report->total_amount)/2;
                $total_food_tax += $report->bill_amount - $report->total_amount;
                $discount += is_numeric($report->discount) ? $report->discount : 0;
                   $total_amount += html_escape($report->total_amount);
                $bill_amount += html_escape($report->bill_amount);
      //   $total_room_taxable_value = $total_price - $total_room_sgst - $total_room_cgst;
        } ?>



</tbody>
<tfoot>
<tr>
        <td colspan="3"><strong>Total:</strong></td>
       
        
        <td><?php echo $total_room_cgst; ?></td>
        <td><?php echo $total_room_sgst; ?></td>
        <td><?php echo $total_room_tax; ?></td>
        <td><?php echo  $total_room_taxable_value; ?></td>
        <td><?php echo $total_price; ?></td>
        <td><?php echo $total_cgst; ?></td>
        <td><?php echo $total_sgst; ?></td>
        <td><?php echo $total_discount; ?></td>
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
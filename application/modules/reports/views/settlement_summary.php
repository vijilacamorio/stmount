<link type="text/css" href="<?php echo MOD_URL .
    $module; ?>/assets/css/table.css">
<?php defined("BASEPATH") or exit("No direct script access allowed"); ?>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card mb-4">
            <!-- <div class="card-header">
                <h4><?php echo display("search_report"); ?></h4>
            </div> -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                    <?php echo form_open("reports/report/settlement_summary", [
                        "class" => "form-inline",
                    ]); ?>
                       
                       <div class="form-group" style="margin-left:400px;">
                           <label class="padding_right_5px col-form-label" for="from_date"><?php echo display(
                               "start_date"
                           ); ?></label>
                           <input type="text" name="start_date" value="<?php    echo $start_date1 ;   ?>" class="form-control datepickers" id="start_date" placeholder="<?php echo display(
                               "start_date"
                           ); ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display(
                               "end_date"
                           ); ?></label>
                           <input type="text" name="to_date" value="<?php    echo $to_date1 ;   ?>" class="form-control datepickers" id="to_date" placeholder="<?php echo display(
                               "end_date"
                           ); ?>">
                       </div>
                      
                       <input name="invoiceurl" type="hidden" value="<?php echo base_url(
                           "reports/report/settlement_summary"
                       ); ?>" id="invoiceurl" />
                       
                       &nbsp;<button type="submit" class="btn btn-success">
                           <span class="text-white"><?php echo display(
                               "search"
                           ); ?></span>
                       </button>&nbsp;
                       <?php echo form_close(); ?>
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
                    <h4><?php echo display("settlement_summary"); ?>
                        
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th><?php echo "Invoice Number"; ?></th>
                           <th><?php echo ("Paid Date"); ?></th>
                           <th><?php echo "Customer"; ?></th>
                           <th><?php echo display("room_no"); ?></th>
                           <th><?php echo "Taxable Amount"; ?></th>
                           <th><?php echo "CGST"; ?></th>
                           <th><?php echo "SGST"; ?></th>
                           <!--<th><?php echo "Item Price"; ?></th>-->
                           <th><?php echo "Bill Amount"; ?></th>
                           <th><?php echo "Paid Amount"; ?></th>
                           <th><?php echo "Payment Type"; ?></th>
                           <!--<th><?php echo "Staff"; ?></th>-->
                           <!--<th><?php echo "Remarks"; ?></th>-->
                           <th><?php echo "User"; ?></th>
                        </tr>
                     </thead>
                     <tbody id="addinvoiceItem">
                        <?php
                        $totaltaxableAmount = 0;
                        $totalcgstAmount = 0;
                        $totalsgstAmount = 0;
                        $totalbillAmount = 0;
                        $totalpaidAmount = 0;
                           foreach ($settlementsum as $report) {
                               if($report->multiamt != 0.00){
                               $t = $report->bt - $report->dicount;
                               $cgst = $t * 0.025;
                               $sgst = $t * 0.025;
                               $t_amt=($report->totalamount)-$cgst-$sgst;
                               $totaltaxableAmount += $t_amt;
                               $totalcgstAmount += $cgst;
                               $totalsgstAmount += $sgst;
                               $totalbillAmount += $report->totalamount;
                               $totalpaidAmount += $report->multiamt;
                        ?>
                        <tr>
                           <td><?php echo html_escape($report->saleinvoice); ?></td>
                           <td><?php echo html_escape($report->bill_dat) ; ?></td>
                           <td><?php echo html_escape($report->customer_name); ?></td>
                           <td><?php echo html_escape($report->room_number); ?></td>
                           <td><?php echo html_escape($t_amt); ?></td>
                           <td><?php echo html_escape($cgst); ?></td>
                           <td><?php echo html_escape($sgst); ?></td>
                           <td><?php echo html_escape($report->totalamount); ?></td>
                           <td><?php echo html_escape($report->multiamt); ?></td>
                           <td><?php echo html_escape($report->pay_type); ?></td>
                           <td><?php echo html_escape(
                              $userinfo->firstname . " " . $userinfo->lastname
                              ); ?></td>
                        </tr>
                        <?php
                           }
                           }
                           foreach ($settle_room as $sr) {
                           if($sr->paymentamount !=0.00){
                            $p=$sr->paydate;
                            $pay=explode(' ',$p);
                            $pay=$pay[0];
                            $t_amt=(($sr->total_price)-($sr->cgst)-($sr->sgst));
                            $totaltaxableAmount += $t_amt;
                            $totalcgstAmount += $sr->cgst;
                            $totalsgstAmount += $sr->sgst;
                            $totalbillAmount += $sr->total_price;
                            $totalpaidAmount += $sr->paymentamount;
                           ?>
                        <tr>
                           <td><?php echo html_escape($sr->booking_number); ?></td>
                           <td><?php echo html_escape($pay); ?></td>
                           <td><?php echo html_escape($sr->customer_name); ?></td>
                           <td><?php echo html_escape($sr->room_no); ?></td>
                           <td><?php echo html_escape($t_amt); ?></td>
                           <td><?php echo html_escape($sr->cgst); ?></td>
                           <td><?php echo html_escape($sr->sgst); ?></td>
                           <td><?php echo html_escape($sr->total_price); ?></td>
                           <td><?php echo html_escape($sr->paymentamount); ?></td>
                           <td><?php echo html_escape($sr->paymenttype); ?></td>
                           <td><?php echo html_escape(
                              $userinfo->firstname . " " . $userinfo->lastname
                              ); ?></td>
                        </tr>
                        <?php } }
                            foreach ($settle_hall as $sr) { 
                            if($sr->paymentamount != 0.00){
                            $p=$sr->paydate;
                            $pay=explode(' ',$p);
                            $pay=$pay[0];
                            $t_amt=(($sr->totalamount)-($sr->cgst)-($sr->sgst));
                            $totaltaxableAmount += $t_amt;
                            $totalcgstAmount += $sr->cgst;
                            $totalsgstAmount += $sr->sgst;
                            $totalbillAmount += $sr->totalamount;
                            $totalpaidAmount += $sr->paymentamount;
                        ?>
                        <tr>
                           <td><?php echo html_escape($sr->inv); ?></td>
                           <td><?php echo html_escape($pay); ?></td>
                           <td><?php echo html_escape($sr->customer_name); ?></td>
                           <td><?php echo html_escape($sr->hall_name); ?></td>
                           <td><?php echo html_escape($t_amt); ?></td>
                           <td><?php echo html_escape($sr->cgst); ?></td>
                           <td><?php echo html_escape($sr->sgst); ?></td>
                           <td><?php echo html_escape($sr->totalamount); ?></td>
                           <td><?php echo html_escape($sr->paymentamount); ?></td>
                           <td><?php echo html_escape($sr->paymenttype); ?></td>
                           <td><?php echo html_escape(
                              $userinfo->firstname . " " . $userinfo->lastname
                              ); ?></td>
                        </tr>
                        <?php } } ?>
                     </tbody>
                     <tfoot>
                        <tr>
                            <td colspan="8" style='text-align:right;'><strong>Total:</strong></td>
                           
                            <td><?php echo round($totalpaidAmount, 2); ?></td>
                        </tr>
                     </tfoot>
                  </table>
        </div>
        </div>

    </div>
</div>
</div>
</div>
<link type="text/css" href="<?php echo MOD_URL.$module;?>/assets/css/table.css">
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
   <div class="col-sm-12 col-md-12">
      <div class="card mb-4">
         <div class="row">
            <div class="col-sm-12">
               <div class="card-body">
                  <?php echo form_open('reports/report/b2b', array('class' => 'form-inline')) ?>
                  <div class="form-group" style="margin-left:400px;">
                     <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                     <input type="text" name="start_date" value="<?php    echo $start_date1 ;   ?>" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                  </div>
                  <div class="form-group">
                     <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                     <input type="text" name="to_date" value="<?php    echo $to_date1 ;   ?>" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                  </div>
                  <input name="invoiceurl" type="hidden" value="<?php echo base_url("reports/report/b2b") ?>" id="invoiceurl" />
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
                     <h4><?php echo display('b2b') ?>
                     </h4>
                  </div>
               </div>
            </div>
            <div class="card-body" id="printArea">
               <div class="table-responsive">
                  <table id="exdatatable" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th><?php echo ('GSTIN/UIN of Recipient') ?></th>
                           <th><?php echo ('Customer Name') ?></th>
                           <th><?php echo ('Invoice Number') ?></th>
                           <th><?php echo ('Invoice Date') ?></th>
                              <th><?php echo ('CGST') ?></th>
                                 <th><?php echo ('SGST') ?></th>
                           <th><?php echo  ('Invoice Value') ?></th>
                           <th><?php echo  ('Place Of Supply') ?></th>
                           <th><?php echo  ('Reverse Charge') ?></th>
                           <th><?php echo  ('Invoice Type') ?></th>
                           <th><?php echo  ('E-Commerce GSTIN') ?></th>
                           <th><?php echo  ('Rate') ?></th>
                           <th><?php echo  ('Taxable Value') ?></th>
                           <th><?php echo  ('Cess Amount') ?></th>
                        </tr>
                     </thead>
                     <tbody id="addinvoiceItem">
                        <?php 
                           $overall_total = 0;
                                 $overall_total_taxable = 0; // Initialize overall total taxable value
                             ?>
                        <?php foreach ($getbusinessinfo as $report) : 
                           $total_amt=$report->total_price;
                             $cgst=$report->cgst;
                               $sgst=$report->sgst;
                           $taxable_value=$total_amt-($cgst+$sgst);
                            $overall_total_taxable += $taxable_value;
                            $overall_total += $total_amt;
                           ?>
                        <tr>
                           <td><?php echo html_escape($report->gst_no); ?></td>
                           <td><?php echo html_escape($report->full_guest_name); ?>   </td>
                           <td><?php echo html_escape($report->booking_number); ?></td>
                           <td><?php echo html_escape($report->date_time); ?></td>
                                <td><?php echo html_escape($report->cgst); ?></td>
                                     <td><?php echo html_escape($report->sgst); ?></td>
                           <td><?php echo html_escape($report->total_price); ?></td>
                           <td><?php echo html_escape('St.Thomas Mount'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('Room'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('12'); ?>   </td>
                           <td><?php  echo $taxable_value; ?> </td>
                           <td> </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php foreach ($getbusinessinfo_food as $report) : 
                           $total_amt=$report->totalamount;
                             $cgst=$report->cgst;
                               $sgst=$report->sgst;
                           $taxable_value=$total_amt-($cgst+$sgst);
                            $overall_total_taxable += $taxable_value;
                              $overall_total += $total_amt;
                           ?>
                        <tr>
                           <td><?php echo html_escape($report->gst_no); ?></td>
                           <td><?php echo html_escape($report->firstname." ".$report->lastname ); ?>   </td>
                           <td><?php echo html_escape($report->saleinvoice); ?></td>
                           <td><?php echo html_escape($report->order_date." ".$report->order_time); ?></td>
                            <td><?php echo html_escape($report->cgst); ?></td>
                             <td><?php echo html_escape($report->sgst); ?></td>
                           <td><?php echo html_escape($report->totalamount); ?></td>
                           <td><?php echo html_escape('St.Thomas Mount'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('Food'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('5'); ?>   </td>
                           <td><?php  echo $taxable_value; ?> </td>
                           <td> </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Hall Room -->
                        <?php foreach ($hallb2breport as $hall) : 
                            $total_amt=$hall->totalamount;
                            $cgst=$hall->cgst;
                            $sgst=$hall->sgst;
                            $taxable_value=$total_amt-($cgst+$sgst);
                            $overall_total_taxable += $taxable_value;
                            $overall_total += $total_amt;
                           ?>
                        <tr>
                           <td><?php echo html_escape($hall->gst_no); ?></td>
                           <td><?php echo html_escape($hall->firstname." ".$hall->lastname ); ?>   </td>
                           <td><?php echo html_escape($hall->invoice_no); ?></td>
                           <td><?php echo html_escape($hall->date_time); ?></td>
                           <td><?php echo html_escape($hall->cgst); ?></td>
                             <td><?php echo html_escape($hall->sgst); ?></td>
                           <td><?php echo html_escape($hall->totalamount); ?></td>
                           <td><?php echo html_escape('St.Thomas Mount'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('Hall'); ?>   </td>
                           <td> </td>
                           <td><?php echo html_escape('18'); ?>   </td>
                           <td><?php  echo $taxable_value; ?> </td>
                           <td> </td>
                        </tr>
                        <?php endforeach; ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <td style="font-weight:bold;text-align:right;" colspan="6"> Total  Amount:</td>
                           <td><?php echo $overall_total; ?></td>
                           <td style="font-weight:bold;text-align:right;" colspan="5"> Total Taxable Amount:</td>
                           <td><?php echo $overall_total_taxable; ?></td>
                           <td></td>
                           <!-- Adjust colspan if needed -->
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
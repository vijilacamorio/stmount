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
                  <?php echo form_open('reports/report/hsnwise', array('class' => 'form-inline')) ?>
                  <div class="form-group" style="margin-left:400px;">
                     <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                     <input type="text" name="start_date" value="<?php    echo $start_date1 ;   ?>" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                  </div>
                  <div class="form-group">
                     <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                     <input type="text" name="to_date" value="<?php    echo $to_date1 ;   ?>" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                  </div>
                  <input name="invoiceurl" type="hidden" value="<?php echo base_url("reports/report/settlement_summary") ?>" id="invoiceurl" />
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
                     <h4><?php echo display('hsnwise') ?>
                     </h4>
                  </div>
               </div>
            </div>
            <div class="card-body" id="printArea">
               <div class="table-responsive">
                  <table id="exdatatable" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th><?php echo ('HSN') ?></th>
                           <th><?php echo  ('Description') ?></th>
                           <th><?php echo  ('Total Value') ?></th>
                           <th><?php echo  ('Total Taxable Value') ?></th>
                           <th><?php echo  ('Central Tax Amount') ?></th>
                           <th><?php echo  ('State/UT Tax Amount') ?></th>
                           <th><?php echo  ('Cess Amount') ?></th>
                        </tr>
                     </thead>
                     <tbody id="addinvoiceItem">
                        <tr>
                           <td><?php echo html_escape('996311'); ?></td>
                           <?php
                              $amount_t=$gettariffinfo['total_price'];
                                $t_x=($gettariffinfo['roomrate']+$gettariffinfo['cc']+$gettariffinfo['bc']+$gettariffinfo['pc'])-$gettariffinfo['discountamount'];
                             $cgst=$gettariffinfo['cgst'];
                              $sgst=$gettariffinfo['sgst'];
                              $taxable_amount_h=$amount_t-($cgst+$sgst);
                              ?>
                           <td><?php echo html_escape('TARIFF'); ?></td>
                           <td><?php echo html_escape($amount_t); ?></td>
                           <td><?php echo html_escape($t_x); ?></td>
                           <td><?php echo html_escape($cgst); ?></td>
                           <td><?php echo html_escape($sgst); ?></td>
                           <td></td>
                        </tr>
                        <!-- Hall HSN -->
                        <tr>
                           <td><?php echo html_escape('996334'); ?></td>
                           <?php
                               $amount_h=$hallb2csmallreport[0]->total_amount;
                              $cgst_h=$hallb2csmallreport[0]->total_cgst;
                              $sgst_h=$hallb2csmallreport[0]->total_sgst;
                              $taxable_amount_h=$amount_h-($cgst_h+$sgst_h);
                              ?>
                           <td><?php echo html_escape('TARIFF'); ?></td>
                           <td><?php echo html_escape($amount_h); ?></td>
                           <td><?php echo html_escape($taxable_amount_h); ?></td>
                           <td><?php echo html_escape($cgst_h); ?></td>
                           <td><?php echo html_escape($sgst_h); ?></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td><?php echo html_escape('996332'); ?></td>
                           <?php
                             $amount_food=($getfoodinfo->total_amount)-($getfoodinfo->discount);
                            $gst = $amount_food * (2.5 / 100);
                            $cgst =   $amount_food+ $gst+ $gst;
                              $taxable_amount_food=$amount_food-$gst-$gst;
                              ?>
                           <td><?php echo html_escape('FOOD'); ?></td>
                           <td><?php echo  number_format(($getfoodinfo->totalfood),3); ?></td>
                           <td><?php echo number_format(($getfoodinfo->total_amount)-($getfoodinfo->discount),3); ?></td>
                           <td><?php echo number_format(($gst),3); ?></td>
                           <td><?php echo number_format(($gst),3); ?></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td><?php echo html_escape('996311E'); ?></td>
                           <?php
                           //bed
                              $a=$getbedinfo->bc;
                               $gst = $a * (6 / 100);
                             
                              $t=$a-($gst+$gst);
                           //child
                                 $exc=$getbedinfo->cc;
                               $gst_c = $exc * (6 / 100);
                             
                              $tc=$exc-($gst_c+$gst_c);
                           //person
                              $ap=$getbedinfo->pc;
                               $gstp = $ap * (6 / 100);
                             
                              $tp=$ap-($gstp+$gstp);
                              ?>
                           <td><?php echo html_escape('EXTRA BED/CHILD/PERSON'); ?></td>
                           <td><?php echo html_escape($a+$exc+$ap); ?></td>
                           <td><?php echo html_escape($t+$tc+$tp); ?></td>
                           <td><?php echo html_escape($gst+$gst_c+$gstp); ?></td>
                           <td><?php echo html_escape($gst+$gst_c+$gstp); ?></td>
                           <td></td>
                        </tr>
                       </tbody>
                     <tfoot>
                        <tr class="footer-row">
                          <td colspan="2" style="font-weight: bold; text-align: right;">Total</td>
                          <td id="totalValue">0.00</td>
                          <td id="totalTaxableValue">0.00</td>
                          <td id="totalCentralTax">0.00</td>
                          <td id="totalStateTax">0.00</td>
                        </tr>
                      </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Function to calculate and update totals
    function updateTotals() {
      let totalValue = 0;
      let totalTaxableValue = 0;
      let totalCentralTax = 0;
      let totalStateTax = 0;

      // Iterate through each row in the tbody
      document.querySelectorAll('#addinvoiceItem tr').forEach(function(row) {
        // Skip the footer row
        if (!row.classList.contains('footer-row')) {
          // Get the values from the columns
          let value = parseFloat(row.children[2].innerText) || 0;
          let taxableValue = parseFloat(row.children[3].innerText) || 0;
          let centralTax = parseFloat(row.children[4].innerText) || 0;
          let stateTax = parseFloat(row.children[5].innerText) || 0;

          // Update the totals
          totalValue += value;
          totalTaxableValue += taxableValue;
          totalCentralTax += centralTax;
          totalStateTax += stateTax;
        }
      });

      // Update the footer cells with the calculated totals
      document.getElementById('totalValue').innerText = totalValue.toFixed(2);
      document.getElementById('totalTaxableValue').innerText = totalTaxableValue.toFixed(2);
      document.getElementById('totalCentralTax').innerText = totalCentralTax.toFixed(2);
      document.getElementById('totalStateTax').innerText = totalStateTax.toFixed(2);
    }

    // Call the updateTotals function initially
    updateTotals();

    // Add event listener for changes in the tbody (e.g., dynamically adding/removing rows)
    document.querySelector('#addinvoiceItem').addEventListener('change', function() {
      updateTotals();
    });
  });
</script>
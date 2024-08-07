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
                    <?php echo form_open('reports/report/settlement_summary', array('class' => 'form-inline')) ?>
                       
                       <div class="form-group" style="margin-left:400px;">
                           <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                           <input type="text" name="start_date" value="" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                           <input type="text" name="to_date" value="" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
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













<div class="card">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-bd lobidrag">
                <div class="card-header">
                    <h4><?php echo display('settlement_summary') ?>
                        
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                            








  <th><?php echo ('Order Id') ?></th>
                    
                             <th><?php echo ('Category') ?></th>
                             <th><?php echo display('date') ?></th>
                             <th><?php echo display('room_no') ?></th>
                           
                              <th><?php echo ('Qty') ?></th>
                               <th><?php echo ('Item Price') ?></th>
                             <th><?php echo  ('Bill Amount') ?></th>
                         
                             <th><?php echo ('Credit') ?></th>
                             <th><?php echo ('Payment Type') ?></th>
                             <th><?php echo ('Customer') ?></th>
                             <th><?php echo ('Staff') ?></th>
                             <th><?php echo  ('Remarks') ?></th>
                             <th><?php echo  ('User') ?></th>

 
                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
                <?php
// if (is_array($settlementsum) && !empty($settlementsum)) {
    foreach ($settlementsum as $report) {
        ?>
        <tr>
             <td><?php echo html_escape($report->order_id); ?></td>
        <td><?php echo html_escape($report->category); ?></td>
        <td><?php echo html_escape($report->order_date) . ' ' . html_escape($report->order_time); ?></td> 
            <td><?php echo html_escape($report->room_number); ?></td>
               <td><?php echo html_escape($report->qty); ?></td>
                   <td><?php echo html_escape($report->price); ?></td>
            <td><?php echo html_escape($report->totalamount); ?></td>
            <td><?php echo html_escape($report->customerpaid); ?></td>
            <td><?php echo html_escape($report->pay_type); ?></td>
            <td><?php echo html_escape($report->customer_name); ?></td>
            <td></td>
            <td></td>
            <td><?php echo html_escape($userinfo->firstname . ' ' . $userinfo->lastname); ?></td>
        </tr>
    <?php
    }
// } else {
//     // Handle the case where $settlementsum is not an array or is empty
//     // For example, you can display a message or take appropriate action.
//     echo "No data to display.";
// }
?>

                </tbody>
                <tfoot>
                    <tr>
                        

                    </tr>
                </tfoot>
            </table>
        </div>
        </div>

    </div>
</div>
</div>
</div>
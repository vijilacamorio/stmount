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
                    <?php echo form_open('reports/report/advanceadjusted', array('class' => 'form-inline')) ?>
                       
                       <div class="form-group" style="margin-left:400px;">
                           <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                           <input type="text" name="start_date" value="<?php    echo $start_date1 ;   ?>" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                           <input type="text" name="to_date" value="<?php    echo $to_date1 ;   ?>" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                       </div>
                      
                       <input name="invoiceurl" type="hidden" value="<?php echo base_url("reports/report/advanceadjusted") ?>" id="invoiceurl" />
                       
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
    <h4 style="text-align: left;"><?php echo display('advanceadjusted') ?></h4>

</div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                 <thead>
                    <tr>
                       
                    <th><?php echo ('Place Of Supply') ?></th>
                    <th><?php echo  ('Rate') ?></th>
                    <th><?php echo  ('Gross Advance Received') ?></th>
                    <th><?php echo  ('Total Cess') ?></th>
                    </tr>
                </thead>

                <tbody id="addinvoiceItem">
    <?php foreach ($getadvanceinfo_adjust as $report) : ?>
             <tr>
               <td><?php echo html_escape('St Thomas Mount'); ?></td>
               <td><?php echo html_escape('12'); ?></td>
                <td><?php echo html_escape($report->advance); ?></td>
                <td><?php echo html_escape('0'); ?></td>
            </tr>
     <?php endforeach; ?>
      <?php foreach ($getadvanceinfo_hall__adjust as $report) : ?>
             <tr>
               <td><?php echo html_escape('St Thomas Mount'); ?></td>
               <td><?php echo html_escape('18'); ?></td>
                <td><?php echo html_escape($report->advance); ?></td>
                <td><?php echo html_escape('0'); ?></td>
            </tr>
     <?php endforeach; ?>
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
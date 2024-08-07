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
                    <?php echo form_open('reports/report/nittax', array('class' => 'form-inline')) ?>
                       
                       <div class="form-group" style="margin-left:400px;">
                           <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                           <input type="text" name="start_date" value="" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                       </div>

                       <div class="form-group">
                           <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                           <input type="text" name="to_date" value="" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                       </div>
                      
                       <input name="invoiceurl" type="hidden" value="<?php echo base_url("reports/report/nittax") ?>" id="invoiceurl" />
                       
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
                    <h4><?php echo display('nittax') ?>
                        
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body" id="printArea">
            <div class="table-responsive">      
            <table id="exdatatable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                    <th><?php echo  ('Date') ?></th>    
                    <th><?php echo ('Description') ?></th>
                    <th><?php echo  ('Nil Rated Supplies') ?></th>
                    <th><?php echo  ('Exempted (other than nil rated/non GST supply )') ?></th>
                    <th><?php echo  ('Non-GST supplies') ?></th>
                    </tr>
                </thead>

                <tbody id="addinvoiceItem">
 
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
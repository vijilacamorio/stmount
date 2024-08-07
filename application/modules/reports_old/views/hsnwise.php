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
                    <?php echo form_open('reports/report/b2csmall', array('class' => 'form-inline')) ?>
                       
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
                    <th><?php echo ('Date') ?></th>

         
                   
                    <th><?php echo  ('Description') ?></th>
                    <th><?php echo  ('UQC') ?></th>
                    <th><?php echo  ('Total Quantity') ?></th>

                  
                  

                  

                   

                   
                   
                    <th><?php echo  ('Total Value') ?></th>
                    <th><?php echo  ('Total Taxable Value') ?></th>


                    <th><?php echo  ('Integrated Tax Amount') ?></th>


                    <th><?php echo  ('Central Tax Amount') ?></th>
                    <th><?php echo  ('State/UT Tax Amount') ?></th>
                    <th><?php echo  ('Cess Amount') ?></th>

                    
                    
                    

                    </tr>
                </thead>
                <tbody id="addinvoiceItem">
    
       
    
    
    
                    <tr>  
                    <td><?php echo html_escape('996311'); ?></td>


                         <td><?php echo html_escape($getfoodinfo->order_date); ?></td> 
                         <td><?php echo html_escape('TARIFF'); ?></td>
                         <td> </td> 
                                                  <td> </td> 

                         
                         
                         <td><?php echo html_escape($getfoodinfo->totalorder); ?></td> 
                          <td><?php echo html_escape($gettariffinfo->totaltariff); ?></td> 

                            <td></td>
                           <td></td>
                           <td></td>
                            <td></td>


                   </tr>
     
    
                   <tr>  

                   <td><?php echo html_escape('996332'); ?></td>

                         <td><?php echo html_escape($getfoodinfo->order_date); ?></td> 
                         <td><?php echo html_escape('FOOD'); ?></td>
                         <td> </td> 
                         <td><?php echo html_escape($getfoodinfo->totalorder); ?></td> 
                          <td><?php echo html_escape($getfoodinfo->totalfood); ?></td> 
                          <td></td>
                           <td></td>
                           <td></td>
                                                      <td></td>
                           <td></td>

                        </tr>
                   <tr>

                   <td><?php echo html_escape('996311E'); ?></td>

                   <td><?php echo html_escape(date('Y-m-d', strtotime($getbedinfo->extracheckin))); ?></td>
                          <td><?php echo html_escape('EXTRA BED'); ?></td> 
                          <td> </td> 
                          <td><?php echo html_escape($getbedinfo->bed); ?></td>
                          <td></td>
                           <td></td>
                           <td></td>                          
                           <td></td>
                           <td></td>

                     </tr>

    </tr>

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
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
                  <?php echo form_open('reports/report/b2clarge', array('class' => 'form-inline')) ?>
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
                     <h4><?php echo display('b2clarge') ?>
                     </h4>
                  </div>
               </div>
            </div>
            <div class="card-body" id="printArea">
               <div class="table-responsive">
                  <table id="exdatatable" class="table table-bordered table-striped table-hover">
                     <thead>
                        <tr>
                           <th><?php echo ('S.No') ?></th>
                           <th><?php echo  ('Voucher No') ?></th>
                           <th><?php echo  ('Voucher Type') ?></th>
                           <th><?php echo  ('Voucher Date') ?></th>
                           <th><?php echo  ('Credit') ?></th>
                           <th><?php echo  ('Debit') ?></th>
                           <th><?php echo  ('Naration') ?></th>
                        </tr>
                     </thead>
                     <tbody id="addinvoiceItem">
                       <?php   $i=1;
                       foreach($t_report as $t){ ?>
<tr>
           <td><?php echo $i ?></td>
                           <td><?php echo  $t->VNo; ?></td>
                           <td><?php echo  $t->Vtype; ?></td>
                         <td><?php echo date('d-m-Y', strtotime($t->VDate)); ?></td>
                           <td><?php echo   $t->Credit; ?></td>
                           <td><?php echo   $t->Debit; ?></td>
                           <td><?php echo   $t->Narration; ?></td>

                       </tr>
                 <?php    $i++;  }
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
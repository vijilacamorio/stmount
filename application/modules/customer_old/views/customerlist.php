 <div class="card">
     <?php if ($this->permission->method('customer', 'create')->access()) : ?>
     <div class="card-header">
         <h4><?php echo display('guest_list') ?><small class="float-right"><a
                     href="<?php echo base_url("customer/customer_info/create") ?>" class="btn btn-primary btn-md">
                     <i class="ti-plus" aria-hidden="true"></i>
                     <?php echo display('add_new_guest') ?></a></small></h4>
     </div>
     <?php endif; ?>
     
     
     
     
     
     
     
      <div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card mb-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-body">
                        <?php echo form_open('customer/customer_info/index', array('class' => 'form-inline')) ?>
                       
                        <div class="form-group" style="margin-left:475px;">
                            <label class="padding_right_5px col-form-label" for="from_date"><?php echo display('start_date') ?></label>
                            <input type="text" name="start_date" value="" class="form-control datepickers" id="start_date" placeholder="<?php echo display('start_date') ?>">
                        </div>

                        <div class="form-group">
                            <label class="padding_0_5px col-form-label" for="to_date"> <?php echo display('end_date') ?></label>
                            <input type="text" name="to_date" value="" class="form-control datepickers" id="to_date" placeholder="<?php echo display('end_date') ?>">
                        </div>
                       
                        <input name="invoiceurl" type="hidden" value="<?php echo base_url("customer/customer_info/index") ?>" id="invoiceurl" />
                        
                        &nbsp;<button type="submit" class="btn btn-success">
                            <span class="text-white"><?php echo display('search') ?></span>
                        </button>&nbsp;
                                
                        <?php echo form_close()?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     <div id="add0" class="modal fade" role="dialog">
         <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">
                     <strong><?php echo display('add_new'); ?></strong>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>
                 <div class="modal-body">

                     <div class="row">
                         <div class="col-sm-12 col-md-12">
                             <div class="panel">

                                 <div class="panel-body">

                                     <?php echo form_open('customer/customer_info/create'); ?>
                                     <?php echo form_hidden('customerid', (!empty($intinfo->customerid) ? $intinfo->customerid : null)) ?>
                                     <div class="form-group row">
                                         <label for="firstname"
                                             class="col-sm-4 col-form-label"><?php echo display('firstname') ?> <span
                                                 class="text-danger">*</span></label>
                                         <div class="col-sm-8">
                                             <input name="firstname" autocomplete="off" class="form-control" type="text"
                                                 placeholder="<?php echo display('firstname') ?>" id="firstname"
                                                 value="">
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label for="lastname"
                                             class="col-sm-4 col-form-label"><?php echo display('lastname') ?> <span
                                                 class="text-danger">*</span></label>
                                         <div class="col-sm-8">
                                             <input name="lastname" autocomplete="off" class="form-control" type="text"
                                                 placeholder="<?php echo display('lastname') ?>" id="lastname" value="">
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label for="email"
                                             class="col-sm-4 col-form-label"><?php echo display('email') ?></label>
                                         <div class="col-sm-8">
                                             <input name="email" autocomplete="off" class="form-control" type="text"
                                                 placeholder="<?php echo display('email') ?>" id="email" value="">
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label for="phone"
                                             class="col-sm-4 col-form-label"><?php echo display('phone') ?>* </label>
                                         <div class="col-sm-8">
                                             <input name="phone" autocomplete="off" class="form-control" type="number"
                                                 placeholder="<?php echo display('phone') ?>" id="phone" value="">
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label for="address"
                                             class="col-sm-4 col-form-label"><?php echo display('address') ?> </label>
                                         <div class="col-sm-8">
                                             <textarea name="address" cols="30" rows="3" autocomplete="off"
                                                 class="form-control"
                                                 placeholder="<?php echo display('address') ?>"></textarea>
                                         </div>
                                     </div>

                                     <div class="form-group text-right">
                                         <button type="reset"
                                             class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                         <button type="submit"
                                             class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
                                     </div>
                                     <?php echo form_close() ?>

                                 </div>
                             </div>
                         </div>
                     </div>



                 </div>

             </div>
             <div class="modal-footer">

             </div>

         </div>

     </div>

     <div id="edit" class="modal fade" role="dialog">
         <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">
                     <strong><?php echo display('update'); ?></strong>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>
                 <div class="modal-body editinfo">

                 </div>

             </div>
             <div class="modal-footer">

             </div>

         </div>

     </div>
     <div id="pay" class="modal fade" role="dialog">
         <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">
                     <strong><?php echo display("payment_form"); ?></strong>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>
                 <div class="modal-body paymentinfo">

                 </div>

             </div>
             <div class="modal-footer">

             </div>

         </div>

     </div>
     <div class="row">
         <!--  table area -->
         <div class="col-sm-12">

             <div class="card-body">
                 <div class="table-responsive">
                     <table width="100%" id="exdatatable"
                         class="datatable table table-striped table-bordered table-hover">
                       
                                 <thead>
                             <tr>
                                 <th><?php echo display('sl_no') ?></th>
                                 <th><?php echo display('firstname') ?></th>
                                 <th><?php echo display('lastname') ?></th>
                                 <th><?php echo display('email') ?></th>
                                 <th><?php echo display('phone') ?></th>
                                 <th><?php echo display('balance') ?></th>
                                 <th><?php echo 'No of visits' ?></th>
                                 <th><?php echo display('address') ?></th>
                                 <th><?php echo display('city') ?></th>
                                 <th><?php echo display('state') ?></th>
                                 <th><?php echo display('zipcode') ?></th>
                                 <th><?php echo display('country') ?></th>
                                 <th><?php echo display('nationality') ?></th>
                                 <th><?php echo display('gender') ?></th>
                                 <th><?php echo display('occupation') ?></th>
                                 <th><?php echo display('dob') ?></th>
                                 <th><?php echo display('anniversary') ?></th>
                                 <th><?php echo display('booking_type') ?></th>
                                 <th><?php echo 'Sign up date' ?></th>
                                 <th><?php echo 'GST No' ?></th>
                                 <th><?php echo display('remarks') ?></th>
                                 <th><?php echo display('action') ?></th>
                             </tr>
                         </thead>

                             </tr>
                         </thead>
                         <tbody>
                             <?php if (!empty($customer_data)) {
                            ?>
                             <?php $sl = 1; ?>
                             <?php foreach ($customer_data as $customer) { ?>
                             <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                          <td><?php echo $sl; ?></td>
                                 <td><?php echo html_escape($customer->firstname); ?></td>
                                 <td><?php echo html_escape($customer->lastname); ?></td>
                                 <td><?php echo html_escape($customer->email); ?></td>
                                 <td><?php echo html_escape($customer->cust_phone); ?></td>
                                 <td><?php echo html_escape($customer->balance); ?></td>
                                 <td><?php echo html_escape($customer->visit_count); ?></td>
                                 <td style="width: 300px;"><?php echo html_escape($customer->address); ?></td>
                                 <td><?php echo html_escape($customer->city); ?></td>
                                 <td><?php echo html_escape($customer->state); ?></td>
                                 <td><?php echo html_escape($customer->zipcode); ?></td>
                                 <td><?php echo html_escape($customer->country); ?></td>
                                 <td><?php echo html_escape($customer->isnationality); ?></td>
                                 <td><?php echo html_escape($customer->gender); ?></td>
                                 <td><?php echo html_escape($customer->profession); ?></td>
                                 <td><?php echo html_escape($customer->dob); ?></td>
                                 <td><?php echo html_escape($customer->adate); ?></td>
                                 <td><?php echo html_escape($customer->contacttype); ?></td>
                                 <td><?php echo html_escape($customer->signupdate); ?></td>
                                 <td><?php echo html_escape($customer->gst_no); ?></td>
                                 <td><?php echo html_escape($customer->remarks); ?></td>
                                 <td class="center">
                                     <?php

                                            if ($this->permission->method('customer', 'update')->access()) : ?>
                                     <input name="url" type="hidden"
                                         id="url_<?php echo html_escape($customer->customerid); ?>"
                                         value="<?php echo base_url("customer/customer-update") ?>" />
                                     <a href="<?php echo base_url("customer/customer-update/" . html_escape($customer->customerid)) ?>"
                                         class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                         title="Edit"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>
                                     <?php endif;
                                     
                                     
                                     
                                    
                                     
                                     
                                     
                                     
                                            if ($this->permission->method('customer', 'update')->access()) : ?>
                                    <input name="url" type="hidden"
                                        id="url_<?php echo html_escape($customer->customerid); ?>"
                                        value="<?php echo base_url("customer/customer-update") ?>" />
                                    <a href="<?php echo base_url("customer/Customer_info/allInformation/" . html_escape($customer->customerid)) ?>"
                                        class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                        title="View"><i class="ti-eye text-white" aria-hidden="true"></i></a>
                                    <?php endif;
                                            if ($this->permission->method('customer', 'update')->access()) : ?>
                                    <input name="url" type="hidden"
                                        id="url_<?php echo html_escape($customer->customerid); ?>"
                                        value="<?php echo base_url("customer/customer-update") ?>" />
                                    <a href="<?php echo base_url("customer/Customer_info/allTransaction/" . html_escape($customer->customerid)) ?>"
                                        class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                        title="Transaction History"><i class="fas fa-history text-white" aria-hidden="true"></i></a>
                                    <?php endif;
                                            if ($this->permission->method('customer', 'delete')->access()) : ?>
                                     <a href="<?php echo base_url("customer/customer-delete/" . html_escape($customer->customerid)) ?>"
                                         onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                         class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                         title="Delete "><i class="ti-trash"></i></a>
                                     <?php endif; ?>
                                 </td>

                             </tr>
                             <?php $sl++; ?>
                             <?php } ?>
                             <?php } ?>
                         </tbody>
                     </table> <!-- /.table-responsive -->
                 </div>
             </div>
         </div>
     </div>
 </div>
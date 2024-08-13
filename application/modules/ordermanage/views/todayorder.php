 

<table class="table table-fixed table-bordered table-hover bg-white wpr_100" id="onprocessing">
    
                                    <thead>
                                         <tr>
                                                <th class="text-center"><?php echo display('sl');?>. </th>
                                                <th class="text-center"><?php echo 'OrderId';?> </th>
                                                  <th class="text-center"><?php echo display('ordate');?></th>
                                                <th class="text-center"><?php echo 'Room No';?> </th>
                                                <th class="text-center"><?php echo display('customer_name');?></th>
                                                <th class="text-center"><?php echo 'Items';?></th> 
                                                <th class="text-center"><?php echo 'Category';?></th> 
                                                <th class="text-right"><?php echo ('Quantity');?></th>
                                                <th class="text-right"><?php echo display('amount');?></th>
                                                <th class="text-center"><?php echo display('action');?></th>  
                                            </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" class="text-right"><?php echo display('total');?>:</th>
                                            <th colspan="2" class="text-center"></th>
                                        </tr>
                                    </tfoot>
                                </table>
<script src="<?php echo base_url('application/modules/ordermanage/assets/js/todayorder.js'); ?>" type="text/javascript"></script>

 
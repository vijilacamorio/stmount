<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->

<!-- Printable area end -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div id="printableArea">
                <div class="card-body">
                    <div class="table-responsive m-b-20">
                        <table class="table table-fixed table-bordered table-hover bg-white" id="billorder">
                            <thead>
                                <tr>
                                    <th width="20%" class="text-center"><?php echo ('S.No')?> </th>
                                    <th width="20%" class="text-center"><?php echo ('Payment Type')?></th>
                                    <th width="20%" class="text-center"><?php echo ('Amount')?></th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total=0;
								if(!empty($billeport)){
                                    $i=1;
									foreach($billeport as $bill){?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $bill->pt;?></td>
                                    <td><?php echo $bill->amt;?></td>
                                    
                                </tr>
                                <?php $total=$total+$bill->amt;;
                                $i++;
								} } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="1"></td>
                                    <td align="right" style="text-align:right;font-size:14px !Important">&nbsp;
                                        <b><?php echo display('total') ?> </b></td>
                                    <td style="text-align: right;"><b> <?php echo number_format($total,3);?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
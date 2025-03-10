<link type="text/css" href="<?php echo MOD_URL.$module;?>/assets/css/table.css">
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="card">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-footer text-right">
                <a class="btn btn-info" onclick="printContent('printArea')" title="Print"><span
                        class="ti-printer text-white"></span>
                </a>
            </div>
            <div id="printArea">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <?php if(!empty($commoninfo->invoice_logo)){?>
                            <img src="<?php echo base_url();?><?php echo html_escape($commoninfo->invoice_logo)?>"
                                class="imgxs img-responsive"  style="height: 72px;width: 147px;"  alt="">
                            <?php }?>
                        </div>

                        <div class="col-sm-8 text-center">
                            <p class="p_bottom_m_bottom"><strong
                                    class="b_padding_border_font"><?php echo display('guest_registration_card') ?></strong>
                            </p>
                            <h4 class="f_size_22px"><?php echo html_escape($storeinfo->storename);?></h4>
                            <p class="f_size_18px" style="margin-top: -7px;"  > <?php echo html_escape($storeinfo->address);?></p>
                            <p class="f_size_18px" style="margin-top: -13px;"  >Mobile: 04422344756, 9444376141</p>

                        </div>
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-xs-12 table-responsive p_bootom">
                            <?php 

                            $datediff = strtotime($bookinfo->checkoutdate) - strtotime($bookinfo->checkindate);
                            $datediff = ceil($datediff/(60*60*24));
                            $totalnight = $datediff;
					?>
                            <table width="100%" border="1">
                                <tr>
                                    <th colspan="2"><?php echo display('name_of_the_guest') ?></th>
                                    <th colspan="2"><?php echo display('for_foreign_guest') ?></th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <strong>1.<?php echo html_escape($customerinfo->firstname.' '.$customerinfo->lastname);?></strong>
                                    </td>
                                    <td colspan="2"><strong><?php echo display('add') ?>:
                                        </strong><?php echo html_escape($customerinfo->address);?></td>
                                </tr>
                                 
                                <tr>
                                    <td colspan="2"><strong><?php echo display('booking_number') ?>:
                                        </strong><?php echo html_escape($bookinfo->booking_number);?></td>
                                    <td colspan="2"><strong><?php echo  ('Zipcode') ?>:
                                        </strong><?php echo html_escape($customerinfo->zipcode);?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong><?php echo display('dob') ?>:
                                        </strong><?php echo html_escape($customerinfo->dob);?></td>
 
                                    <td colspan="2"><strong><?php echo display('nationality') ?>: </strong>
                                        <?php echo html_escape($customerinfo->isnationality);?> </td>


                                </tr>
                                <tr>
                                    <td colspan="2"><strong><?php echo display('mobile') ?>:
                                        </strong><?php echo html_escape($customerinfo->cust_phone);?></td>
                                    <td colspan="2"><strong><?php echo html_escape($customerinfo->pitype);?> : </strong>
                                        <?php echo html_escape($customerinfo->pid);?></td>
                                </tr>
                             
                             
                                <tr>
                                    <td colspan="2"><strong><?php echo ('Occupation') ?>:
                                        </strong><?php echo html_escape($customerinfo->profession);?></td>
                                    <td colspan="2"></td>

                                </tr>
                                <tr>
                                    <!-- <td colspan="2"><strong><?php echo display('nationality') ?>: </strong><input
                                            type="checkbox"
                                            <?php if($customerinfo->isnationality=='native'){ echo display('checked');}?>
                                            disabled="disabled" name="isnationality" value="Native">
 
                                            <?php echo html_escape($storeinfo->country);?>
                                        <input type="checkbox"
                                            <?php if($customerinfo->isnationality=='foreigner'){ echo display('checked');}?>
                                            disabled="disabled" name="isnationality" id="materialInline2"
                                            value="Foreign"> <?php echo display('foreign') ?>
                                    </td> -->

 

                                        <!-- <td colspan="2"><strong><?php echo display('visa_reg_no') ?>:
                                        </strong><?php echo html_escape($customerinfo->visano);?></td> -->

                                        <!-- <td colspan="2"><strong><?php echo display('purpose') ?>: </strong><input
                                            type="checkbox"
                                            <?php if($customerinfo->purpose=='Tourist'){ echo display('checked');}?>
                                            disabled="disabled" name="isnationality" value="Tourist">
                                        <?php echo display('tourist') ?>
                                        <input type="checkbox"
                                            <?php if($customerinfo->purpose=='Business'){ echo display('checked');}?>
                                            disabled="disabled" name="isnationality" value="Business">
                                        <?php echo display('business') ?>
                                        <input type="checkbox"
                                            <?php if($customerinfo->purpose=='Official'){ echo display('checked');}?>
                                            disabled="disabled" name="isnationality" value="Official">
                                        <?php echo display('official') ?> -->
                                    <!-- </td> -->


                                </tr>
                                
                                <tr>
                                    <td width="36%"><strong><?php echo display('checkin_date') ?>:
                                        </strong><?php echo html_escape($bookinfo->checkindate);?></td>
                                    <td colspan="2"><strong><?php echo display('checkout_date') ?>:
                                        </strong><?php echo html_escape($bookinfo->checkoutdate);?></td>
                                    <td width="32%"><strong><?php echo display('try_duration') ?>:
                                        </strong><?php echo html_escape($totalnight);?> <?php echo display('nights') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="36%"><strong><?php echo display('checkin_time') ?>:
                                        </strong><?php echo html_escape($storeinfo->checkouttime);?>
                                        <?php echo display('noon') ?></td>
                                    <td colspan="2"><strong><?php echo display('checkout_time') ?>:
                                        </strong><?php echo html_escape($storeinfo->checkouttime);?>
                                        <?php echo display('noon') ?></td>
                                    <td width="32%"> </strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4 pl-0 pt-4">
                            <table width="100%" border="1">
                                <tr>
                                    <td class="text-center"><?php echo display('room') ?></td>
                                    <td class="text-center"><?php echo display('adults') ?></td>
                                    <td class="text-center"><?php echo display('defaultrate') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><?php echo html_escape($bookinfo->room_no);?></td>
                                    <td class="text-center"><?php echo html_escape($bookinfo->nuofpeople);?></td>
                                    <td class="text-center"><?php echo html_escape($bookinfo->roomrate);?></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-sm-4  pt-4">
                            <table width="100%" border="1">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <strong><?php echo display('font_desk_office_signature') ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4 pr-0  pt-4">
                            <table width="100%" border="1">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><strong><?php echo display('guest_signature') ?></strong>
                                    </td>
                                </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div>


        </div>

    </div>
</div>
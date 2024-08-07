<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->
<script type="text/javascript">
function printDiv(divName) {
    
    var printContents = document.getElementById(divName).innerHTML;
    $.print(printContents);
    
}
</script>
<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/res_details.css">
<!-- Printable area end -->
<div class="row">
    <div class="col-sm-12">
        <div class="card ">
            <div class="card-footer text-right">
                <a class="btn btn-info" onclick="printDiv('printableArea')" title="Print"><span
                        class="fa fa-print"></span>
                </a>
            </div>
            <div id="printableArea">
                <div class="card-body">
                    <div class="col-md-12">
                            <div class="row">
                            <div class="col-md-8">
                                <img src="<?php echo base_url();?><?php echo (file_exists($comsettinginfo->invoice_logo)?$comsettinginfo->invoice_logo:'application/modules/ordermanage/assets/images/dmi_logo.jpeg')?>"
                                    class="img img-responsive height-mb" alt="">
                                <br>
                                <span
                                    class="label label-success-outline m-r-15 p-10"><?php echo display('billing_from') ?></span>
                                <address class="mt-10">
                                    <strong><?php echo $storeinfo->storeinfo;?></strong><br>
                                    <!-- <//?php echo $storeinfo->address;?><br> -->
                                    <abbr><?php echo "Hill Top, St.Thomas Mount, Chennaiee, 600016, India "; ?>:</abbr> <br>
                                    <abbr><?php echo display('mobile') ?>:</abbr> <?php echo $storeinfo->phone;?><br>
                                    <abbr><?php echo display('email') ?>:</abbr>
                                    <?php echo $storeinfo->email;?><br>
                                </address>
                              
                            </div>
                            <div id="lextleftinvoice" class="col-md-4 " >
                                <h2 class="m-t-0"><?php echo display('invoice') ?></h2>
                                <div><?php echo display('invoice_no') ?>: <?php echo $billinfo[0]->saleinvoice;?></div>
                                
                                <div class="m-b-15"><?php echo display('billing_date') ?>:
                                    <?php echo date('d-m-Y');?></div>
                                <span class="label label-success-outline m-r-15"><?php echo display('billing_to') ?></span>
                                <address class="mt-10">
                                    <strong><?php echo $customerinfo->firstname.' '.$customerinfo->lastname;?> </strong><br>
                                    <abbr><?php echo display('address') ?>:</abbr>
                                    <c class="wmp"><?php echo $customerinfo->address;?></c><br>
                                    <abbr><?php echo display('mobile') ?>:</abbr><?php echo $customerinfo->cust_phone;?></abbr><br>
                                    <abbr><?php //echo ('Room Number');?>: </abbr> <?php //if(!empty($billinfo)){echo $billinfo->room_number;} ?></abbr>
                                </address>
            
            
            <?php // print_r($billinfo); die();?>
            
             
                               
                            </div>
                        </div>
                    </div>
                   
                 
                    <hr>

                    <div class="table-responsive m-b-20">
                        <table class="table table-fixed table-bordered table-hover bg-white" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo display('item')?> </th>
                                    <!--<th class="text-center"><?php echo display('size')?></th>-->
                                    <th class="text-center wp_100"><?php echo display('unit_price')?></th>
                                    <th class="text-center wp_100"><?php echo display('qty')?></th>
                                    <th class="text-center"><?php echo display('total_price')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; 
								  $totalamount=0;
									  $subtotal=0;
									  $total=0;
									  $multiplletax = array();
									foreach ($iteminfo as $item){
										$i++;
										if($item->price>0){
											$itemprice= $item->price*$item->menuqty;
											$singleprice=$item->price;
											}
											else{
												$itemprice= $item->mprice*$item->menuqty;
												$singleprice=$item->mprice;
												}
										$discount=0;
										$adonsprice=0;
										if(!empty($item->add_on_id)){
											$addons=explode(",",$item->add_on_id);
											$addonsqty=explode(",",$item->addonsqty);
											$x=0;
											foreach($addons as $addonsid){
													$adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
													$adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$x];
													$x++;
												}
											$nittotal=$adonsprice;
											$itemprice=$itemprice;
											}
										else{
											$nittotal=0;
											$text='';
											}
									 	 $totalamount=$totalamount+$nittotal;
										 $subtotal=$subtotal+$itemprice;
									    ?>
                                        <tr>
                                            <td>
                                                <?php echo $item->ProductName;?>
                                            </td>
                                            <!--<td>-->
                                            <!--    <?php echo $item->variantName;?>-->
                                            <!--</td>-->
                                            <td class="text-right">
                                                <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                <?php echo $singleprice;?>
                                                <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                            <td class="text-right"><?php echo $item->menuqty;?></td>
                                            <td class="text-right">
                                                <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                    <?php echo $itemprice;?>
                                                    <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                            </td>
                                        </tr>
                                        <?php 
                                            if(!empty($item->add_on_id)){
                                                $y=0;
                                                    foreach($addons as $addonsid){
                                                            $adonsinfo=$this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                                                            $adonsprice=$adonsprice+$adonsinfo->price*$addonsqty[$y];?>
                                        <tr>
                                            <td colspan="2">
                                                <?php echo $adonsinfo->add_on_name;?>
                                            </td>
                                            <td class="text-right">
                                                <?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                <?php echo $adonsinfo->price;?>
                                                <?php if($currency->position==2){echo $currency->curr_icon;}?> </td>
                                            <td class="text-right"><?php echo $addonsqty[$y];?></td>
                                            <td class="text-right">
                                                <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                                    <?php echo $adonsinfo->price*$addonsqty[$y];?>
                                                    <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                            </td>
                                        </tr>
                                        <?php $y++;
												}
										 }
									}
									 $itemtotal=$totalamount+$subtotal;
                                     
									 if(!empty($settinginfo->vat)){
										$settingvat = $settinginfo->vat;
									   }else {
										$settingvat = null;
									   }
									 $calvat=$itemtotal*$settingvat/100;
									 
									 $discountpr=''; 
									 if (!empty($settinginfo)) {
										if($settinginfo->discount_type==1){ 
											$dispr=$billinfo->discount*100/$grandtotalamount;
											$discountpr='('.$dispr.'%)';
											} 
											else{$discountpr='('.$currency->curr_icon.')';}
										 
									 }
									 
									  $sdr=0; 
									 $sdpr=$billinfo->service_charge*100/$grandtotalamount;
									 $sdr='('.round($sdpr).'%)';
								
									  $calvat=$billinfo->VAT;
									 	 $cgst=$itemtotal*2.5/100;
									 	 
									 ?>
                              
                           
                                  <tr>
                                    <td class="text-right" colspan="3">
                                        <strong><?php echo ('Amount')?></strong>
                                    </td>
                                        <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php echo $t_amount;?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>                               
                                    </td>
                                </tr>
              
                              <tr>
                                    <td class="text-right" colspan="3">
                                        <strong><?php echo display('discount')?></strong>
                                    </td>
                                        <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                        <?php $discount=0;  if(empty($dis_amount)){ echo $discount;} else{echo $discount=$dis_amount;} ?>
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>                               
                                    </td>
                                </tr>




                              
                              <tr>
                                    <td class="text-right" colspan="3"><strong><?php echo display('subtotal')?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>                                          
                                       
                                        <?php echo  $t_amount - $dis_amount;?>    
                                        
 
                                        <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>

                             


                                
										
										
							
                                
                                    <tr style="display:none;">
                                    <td class="text-right" colspan="3"><strong><?php echo display('vat_tax')?>
                                            (<?php $vdata = $this->db->select("vat")->from('setting')->get()->row();
                                             if(!empty($vdata)){echo $vdata->vat;} ?>%)</strong></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            <?php echo $calvat; ?>
                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>






                                <tr>
                                    <td class="text-right" colspan="3"><strong><?php echo ('CGST')?>(<?php echo '2.5';?>%)
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                      
                                      

<?php
$sum=0;
if($dis_amount > 0) {
    $amt= $t_amount - $dis_amount;
   
    $sum = $amt * 0.025; 
}else{
      $amt= $t_amount;
     $sum=$amt * 0.025; 
}
echo $sum;
?>                                     
<?php
// if($dis_amount > 0){
//     echo $sum; 
// } else {
//     echo $cgst; 
// }
?>

 

                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>



                                <tr>
                                    <td class="text-right" colspan="3"><strong><?php echo ('SGST')?>(<?php echo '2.5';?>%)
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                      
                                      

<?php
echo $sum;
?>
                                      
                                       
                                             <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>





                                </tr>
                               
                              
                              
                              
                              
                                <tr>
                                    <td class="text-right" colspan="3">
                                        <strong><?php echo display('grand_total')?></strong></td>
                                    <td class="text-right">
                                        <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                            
                                        

<?php
$fin=0;
if($dis_amount > 0) {
    $amt= $t_amount - $dis_amount;
   
    $sum = $amt * 0.025; 
    $fin=$amt+$sum+$sum;
}else{
      $amt= $t_amount;
     $sum=$amt * 0.025; 
       $fin=$amt+$sum+$sum;
}
echo $fin;
?>





                                            <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                                    </td>
                                </tr>













                              
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
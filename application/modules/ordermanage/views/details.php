<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Printable area start -->
<script type="text/javascript">
function printDiv(divName) {
    var table = document.getElementById('purchaseTable');
    var rowCount = table.rows.length;
    console.log(rowCount);
    var firstPageRowCount = 12;

    // Hide all rows
    for (var i = 0; i < rowCount; i++) {
        table.rows[i].style.display = 'none';
    }

    // Show the first page rows
    for (var i = 0; i < firstPageRowCount && i < rowCount; i++) {
        table.rows[i].style.display = 'table-row';
    }

    if (rowCount > firstPageRowCount) {
        // Create a new table for the second page
        var secondPageTable = document.createElement('table');
        secondPageTable.className = 'table table-fixed table-bordered table-hover bg-white';
        
        var headerRow = table.rows[0].cloneNode(true);
        var printableContainer = document.getElementById(divName);
        printableContainer.appendChild(secondPageTable);

        if (rowCount === 16 || rowCount === 17 || rowCount === 18 || rowCount === 19 || rowCount === 20 || rowCount === 21) {
            headerRow.style.display = 'none';
        }

        // Clone the header div and append it to the second page
        var headerDiv = document.getElementById('Headerpart');
        if (headerDiv) {
            var clonedHeaderDiv = headerDiv.cloneNode(true);
            secondPageTable.appendChild(clonedHeaderDiv);
            
            // Create and style the <h1> element
            var helloHeading = document.createElement('p');
            helloHeading.innerHTML = '<strong>Bill Date:</strong> <?php echo $orderinfo->order_date; ?><br><strong>Bill No:</strong> <?php echo $orderinfo->saleinvoice; ?><br><?php $roomNumber = preg_replace("/[^0-9]/", "", $billinfo->room_number); if (!empty($roomNumber) && is_numeric($roomNumber)) { ?><abbr><strong><?php echo "Room No"; ?>:</strong> </abbr><abbr><?php echo $billinfo->room_number; ?></abbr><?php } ?>';
            helloHeading.style.float = 'right'; 
            secondPageTable.appendChild(helloHeading);

            // Hide the header if the first page has less than 12 rows
            if (rowCount < firstPageRowCount) {
                clonedHeaderDiv.style.display = 'none';
            }
        } else {
            // If headerDiv is not found, create an empty header row
            var emptyHeaderRow = document.createElement('tr');
            secondPageTable.appendChild(emptyHeaderRow);
        }

        secondPageTable.appendChild(headerRow);

        for (var i = firstPageRowCount; i < rowCount; i++) {
            var newRow = secondPageTable.insertRow();
            if (i >= rowCount - 8) {
                for (var j = 0; j < 2; j++) { 
                    var newCell = newRow.insertCell();
                    if (table.rows[i] && table.rows[i].cells[j]) {
                        if (j === 0) {
                            newCell.colSpan = 3;
                            newCell.style.textAlign = 'right';
                        } else if(j === 1){
                            newCell.style.textAlign = 'right';
                        }
                        else if (j === 2) {
                            newCell.style.display = 'none';
                        }
                        newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                    } else {
                        newCell.colSpan = 3;
                    }
                }
            } else {
                for (var j = 0; j < table.rows[i].cells.length; j++) {
                    var newCell = newRow.insertCell();
                    if (table.rows[i] && table.rows[i].cells[j]) {
                        newCell.innerHTML = table.rows[i].cells[j].innerHTML;
                    }
                }
            }
        }
        
        removeEmptyColumns(secondPageTable);
        // var footerTable = document.querySelector('.my_table');
        // if (footerTable) {
        //     var clonedFooterTable = footerTable.cloneNode(true);
        //     printableContainer.appendChild(clonedFooterTable);
        // }
        
             var paymentdetailstable = document.getElementById('my_table');
            var paymentdetailrowCount = paymentdetailstable.rows.length;
            console.log(paymentdetailrowCount, "paymentdetailrowCount");
            if (paymentdetailstable) {
                paymentdetailstable.style.fontSize = '14px';
                printableContainer.appendChild(paymentdetailstable);
            }

            // var checkoutsubtotal = document.getElementById('checkoutsubtotal');
            // if (checkoutsubtotal) {
            //     checkoutsubtotal.style.float = 'right'; 
            //     printableContainer.appendChild(checkoutsubtotal);
            // }
            
            
    }

    $('.hide').hide();
    var printContents = document.getElementById(divName).innerHTML;
    $.print(printContents);
}

function removeEmptyColumns(table) {
    var columnCount = table.rows[0].cells.length;
    for (var j = columnCount - 1; j >= 0; j--) {
        var isEmpty = true;
        for (var i = 0; i < table.rows.length; i++) {
            if (table.rows[i].cells[j].innerHTML.trim() !== '') {
                isEmpty = false;
                break;
            }
        }
        if (isEmpty) {
            for (var i = 0; i < table.rows.length; i++) {
                table.rows[i].deleteCell(j);
            }
        }
    }
}



</script>
 
<style>
    @media print {
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
            border-top: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
        /*.header-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            margin-bottom: 50px; 
            box-sizing: border-box; 
            z-index: 999;
        }*/
        @page {
            margin-bottom: 60px; 
        }
        .print-footer-container {
            page-break-before: always;
        }

        /*#purchaseTable {
            width: 100%;
            table-layout: auto; 
        }*/
    }
</style>

<link rel="stylesheet" href="<?php echo MOD_URL.$module;?>/assets/css/res_details.css">
<!-- Printable area end -->
<div class="row" >
   <div class="col-sm-12">
      <div class="card ">
         <div class="card-footer text-right">
            <a class="btn btn-info" onclick="printDiv('printableArea')" title="Print"><span class="fa fa-print"></span>
            </a>

         </div>
         <div id="printableArea" class="content">
            <div class="card-body">
               <div class="col-md-12">
                  <div class="row">
                     <div style="position: absolute;top: 0px;right: 20px;color: #28a745; font-size:30px;"><span id="ipaid" style="font-size:30px;font-weight:bolder;" class="color-red"><?php if(isset($orderinfo->customerpaid)){?>
                        <?php if( $orderinfo->customerpaid < $orderinfo->totalamount){  echo "<span style='font-size:30px;font-weight:bolder;color: red;'>Unpaid</span>";}else{  echo "<span style='font-size:30px;font-weight:bolder;color: green;'>Paid</span>";}?>
                        <?php } else{echo "<span style='color: red;'>Unpaid</span>";}?></span>
                    </div>
                    <div id="Headerpart">
                   
                    <div class="col-md-7">
                        <img style='margin-left:295px;width:80px;height:70px;'src="<?php echo base_url();?><?php echo (file_exists($comsettinginfo->invoice_logo)?$comsettinginfo->invoice_logo:'application/modules/ordermanage/assets/images/dmi_logo.jpeg')?>"
                           class="img img-responsive height-mb" alt=""   >
                        <br>
                        <br>
                        <address class="mt-10" style="margin-left: 38px;text-align:center;width: 600px;margin-top: -40px  !important;">
                           <strong style="font-size: 17px;" ><?php echo $storeinfo->storename;?></strong><br>
                           <abbr>Address:<?php echo "Hill Top , St.Thomas Mount , Chennai - 600016 , Tamilnadu , India."; ?></abbr><br> 
                           <abbr><strong><?php echo display('mobile') ?>:</strong></abbr> <?php echo $storeinfo->phone;?> <abbr>.<strong> <?php echo display('email') ?>:</strong></abbr><?php echo " ".$storeinfo->email;?><br>
                           <abbr><strong><?php echo ('GST NO: 33AACCB5396H1ZN') ?></strong></abbr><br> 
                           <abbr><strong style="font-size:larger;"><?php echo  ('INVOICE') ?></strong></abbr><br>
                           <abbr class="invoicePart" style="display: none;"><strong><?php echo 'Bill No'; ?>: </strong></abbr>
                           <span class="invoicePart" style="font-size: 12px; display: none;"><?php  echo $orderinfo->saleinvoice;?></span>
                        </address>
                        <?php if($billinfo->shipping_type==2||$billinfo->shipping_type==3){?>
                        <address class="mt-10">
                           <strong>Delivary Date & Time: <?php echo $orderinfo->shipping_date;?></strong><br>
                        </address>
                        <?php } ?>
                     </div>
                     </div>
                     <div id="lextleftinvoice" class="col-md-5">
                        <!-- <h2 class="m-t-0"> </h2> -->
                        <div class="m-b-15"  style="margin-top: 240px;" >
                           <strong><?php  echo ('Bill Date') ?>  :</strong>
                           <span style="font-size: 12px;"><?php  echo $orderinfo->order_date;?></span>
                           <br>
                           <strong><?php echo 'Bill No   '; ?>&nbsp;&nbsp;&nbsp;: </strong>
                           <span style="font-size: 12px;"><?php  echo $orderinfo->saleinvoice;?></span>
                           <br>
                           <?php  
                              $roomNumber = preg_replace('/[^0-9]/', '', $billinfo->room_number);
                              
                              // Check if the stripped value is numeric and not empty
                              if (!empty($roomNumber) && is_numeric($roomNumber)) {
                              ?> 
                           <abbr><strong><?php echo ('Room No');?>:</strong> </abbr> <abbr><?php echo $billinfo->room_number; ?></abbr>
                           <?php } ?>
                        </div>
                     </div>
                     <div  style="margin-top: -70px;" >
                        <!-- <strong style="font-size:larger;"><?php //echo  ('GUEST NAME') ?></strong>  -->
                        <?php echo('GUEST INFO'); ?>
                        <address class="mt-10" style="width: 300px;">
                           <strong><?php echo $customerinfo->title . " " . $customerinfo->firstname . ' ' . $customerinfo->lastname; ?> </strong>   <br>
                           <?php if (!empty($customerinfo->gst_no) && $customerinfo->gst_no !== null && $customerinfo->gst_no != '') { ?>
                        
                           <span class="wmp">
                           <?php echo "<span style='font-weight:bold;'>GST NO : </span>".$customerinfo->gst_no ?>
                           </span>
                           <br/>
                           <?php } ?>
                       
                           <?php echo $customerinfo->cust_phone; ?><br>
                        </address>
                        <?php if($billinfo->shipping_type==2){?>
                        <span class="label label-success-outline m-r-15"><?php echo "Shipping To"; ?></span>
                        <address class="mt-10">
                           <c class="wmp"><?php echo $shipinfo->address;?></c>
                           <br>
                        </address>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <?php if($orderinfo->order_status==5){?>
               <div class="row">
                  <p class="col-sm-12">
                     <strong><?php echo display('cancel_reason') ?>:</strong><br /><?php echo $orderinfo->anyreason;?>
                  </p>
               </div>
               <?php } ?>
               <?php if($orderinfo->customer_note!=""){?>
               <div class="row">
                  <p class="col-sm-12">
                     <strong><?php echo display('customer_order') ?>:</strong><br /><?php echo $orderinfo->customer_note;?>
                  </p>
               </div>
               <?php } ?>
               <?php if($billinfo->shipping_type==2){?>
               <div class="row">
                  <p class="col-sm-12">
                     <strong><?php echo display('customerpicktime') ?>:</strong><br /><?php echo $billinfo->delivarydate;?>
                  </p>
               </div>
               <?php } ?>
               <hr>
               <div class="table-responsive m-b-20"  style="margin-top: -45px;"  >
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
                            $total=$orderinfo->totalamount;
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
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> 
                           </td>
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
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> 
                           </td>
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
                           $dispr=$billinfo->discount*100/$billinfo->total_amount;
                           $discountpr='('.$dispr.'%)';
                           } 
                           else{$discountpr='('.$currency->curr_icon.')';}
                           
                           }
                           
                           $sdr=0; 
                           $sdpr=$billinfo->service_charge*100/$billinfo->total_amount;
                           $sdr='('.round($sdpr).'%)';
                           
                           $calvat=$billinfo->VAT;
                           ?>
                    </tbody>
                     <tfoot>
                        <tr>
                           <td class="text-right" colspan="3">
                              <strong><?php echo ('Amount')?><?php if(!empty($discountpr)){echo $discountpr;}?></strong>
                           </td>
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php echo $total_amount=$billinfo->total_amount; ?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>                               
                           </td>
                        </tr>
                        <tr>
                           <td class="text-right" colspan="3">
                              <strong><?php echo display('discount')?><?php if(!empty($discountpr)){echo $discountpr;}?></strong>
                           </td>
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php $discount=0; if(empty($billinfo)){ echo $discount;} else{echo $discount=$billinfo->discount;} ?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>                               
                           </td>
                        </tr>
                        <tr>
                           <td class="text-right" colspan="3"><strong><?php echo display('subtotal')?></strong>
                           </td>
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>                                          
                              <?php echo $itemtotal - $billinfo->discount;?>    
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                           </td>
                        </tr>
                  
                        <?php if(empty($taxinfos)){?>
                        
                        <tr>
                           <td class="text-right" colspan="3"><strong><?php echo ('CGST')?>(<?php echo '2.5';?>%)
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php
                                 if($discount > 0) {
                                     $final = $itemtotal - $billinfo->discount; 
                                     $sum = $final * 0.025; 
                                 }
                                 ?>                                     
                              <?php
                                 if($discount > 0){
                                     echo $sum; 
                                 } else {
                                     echo $orderinfo->cgst; 
                                 }
                                 ?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                           </td>
                        </tr>
                        <tr>
                           <td class="text-right" colspan="3"><strong><?php echo ('SGST')?>(<?php echo '2.5';?>%)
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php
                                 if($discount > 0) {
                                     $final = $itemtotal - $billinfo->discount; 
                                     $sum1 = $final * 0.025; 
                                 }
                                 ?>                                     
                              <?php
                                 if($discount > 0){
                                     echo $sum1; 
                                 } else {
                                     echo $orderinfo->sgst; 
                                 }
                                 ?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                           </td>
                        </tr>
                        <?php }else{
                           $i=0;
                           foreach($taxinfos as $mvat){
                           if($mvat['is_show']==1){
                           $taxinfo=$this->order_model->read('*', 'tax_collection', array('relation_id' => $orderinfo->order_id));  
                           $fieldname='tax'.$i;
                           ?>
                        <tr>    
                        <tr>
                           <td class="text-right" colspan="3"><strong><?php echo $mvat['tax_name'];?></strong>
                           </td>
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php echo $taxinfo->$fieldname;?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                           </td>
                        </tr>
                        <?php $i++;} }}?>
                        <tr>
                           <td class="text-right" colspan="3">
                              <strong><?php echo display('grand_total')?></strong>
                           </td>
                           <td class="text-right Rightalign">
                              <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php
                                 if($discount > 0) {
                                     $gt = $final + $sum + $sum1;  
                                     echo $gt;
                                  } else {
                                     echo $billinfo->bill_amount; 
                                 }
                                 ?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
                           </td>
                        </tr>
                        <?php if($orderinfo->order_status==5){}else{
                           if($orderinfo->customerpaid>0){
                            $customepaid=$orderinfo->customerpaid;
                            $changes=$customepaid-$orderinfo->totalamount;
                            }
                           else{
                            $customepaid=$orderinfo->totalamount;
                            $changes=0;
                            }
                                       
                            if($billinfo->bill_status==1){?>
                        <tr>
                           <td align="right" colspan="3">
                              <nobr><?php echo display('customer_paid_amount')?></nobr>
                           </td>
                           <td align="right" class="Rightalign">
                              <nobr><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                 <?php
                                    if($discount > 0) {
                                        $gt = $final + $sum + $sum1;  
                                        echo $gt;
                                     } else {
                                        echo $customepaid; 
                                    }
                                    ?>                       
                                 <?php if($currency->position==2){echo $currency->curr_icon;}?>
                              </nobr>
                           </td>
                        </tr>
                        <?php } else{ ?>                           
                        <tr>
                           <td align="right" colspan="3">
                              <nobr><?php echo display('total_due')?></nobr>
                           </td>
                           <td align="right" class="Rightalign">
                              <nobr><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                 <?php
                                    if($discount > 0) {
                                        $gt = $final + $sum + $sum1;  
                                        echo $gt;
                                     } else {
                                        echo $customepaid; 
                                    }
                                    ?>                      
                                 <?php if($currency->position==2){echo $currency->curr_icon;}?>
                              </nobr>
                           </td>
                        </tr>
                        <?php } ?>
                     
                        <?php } ?>
                     </tfoot>
                  </table>
                  <!-- style='margin-left:70px;' -->
             
               </div>
 <table id="my_table" class="my_table">
                     <tbody>
                        <tr>
                           <th style='width:180px;'>Payment Mode</th>
                           <th>Amount</th>
                        </tr>
                        <?php 
                           foreach ($multibill as $key => $value) {
                               $getPayment = $this->db->select("*")->from("payment_method")->where("payment_method_id", $value['payment_type_id'])->get()->row();
                           
                               if (!empty($getPayment)) {
                                   // Modify the payment method name according to your specific conditions
                                   $paymentMethod = $getPayment->payment_method; // Get the original payment method name
                                   // Define specific replacements for known payment method names
                                   $replacements = [
                                       'Cash Payment' => 'Cash',
                                       'Online Payment' => 'Online',
                                       'Card Payment' => 'Card',
                                       // Add more replacements as needed
                                   ];
                           
                                   // Check if the original payment method name is in the replacements array
                                   if (array_key_exists($paymentMethod, $replacements)) {
                                       // Replace with the desired name
                                       $paymentMethod = $replacements[$paymentMethod];
                                   }
                           
                                   // Creating a new table row for each payment method
                                   echo "<tr>";
                                   echo "<td>" . $paymentMethod . " </td>"; // Use the possibly replaced payment method name
                                   echo "<td style='text-align: right;'>" . $value['amount'] . "</td>";
                                   echo "</tr>";
                               }
                           }
                           ?>
                     </tbody>
                  </table>
               <div class="print-footer-container">

                    <div class="print-footer">
                      <p style="text-align:center; font-size: 12px; margin: 3px auto; padding: 3px 0;  border-bottom: 1px solid #c7bcbc;">
                         PLEASE RETURN YOUR KEY CARD ON DEPARTURE
                      </p>
                      <p style="text-align:left; height: 0px; font-size: 12px; margin-top: 10px;">
                         I agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or Person indicated Billing Instructions: <strong>DIRECT</strong>
                      </p>
                      <div style="margin-top: 50px;display: flex; justify-content: space-between;">
                         <div>
                            <hr style="border-top: 1px solid #000;">
                            <span><?php echo display("guest_signature") ?></span>
                         </div>
                         <div>
                            <hr style="border-top: 1px solid #000;">
                            <span><?php echo display("authorized_signature") ?></span>
                         </div>
                      </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>

<style>
   table {
   border-collapse: collapse;
   }
   th {
   }
   th, td {
   border: 1px solid white;
   }
   tr:nth-child(even) {
   }
   tr:hover {
   }
</style>

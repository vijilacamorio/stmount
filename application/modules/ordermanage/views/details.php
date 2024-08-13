<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px;  height: 60px; text-align: center; line-height: 20px;font-size: 20px;}
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 90px;text-align: center; line-height: 20px; font-size: 12px;}
    .pagebreak { page-break-after: always; }
    .pagebreak:last-child { page-break-after: never; }
    footer .left {
      text-align: left;
      position: relative;
      top: 20px;
    }

    footer .right {
      text-align: right;
    }
    .header-table{
        margin-top: 80px;
        font-size: 10px !important;
    }
    table {
        font-size: 10px !important;
    }

   .mainTable {
    border-collapse: collapse;
    width: 100%;
}

    .mainTable th, .mainTable td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    .subTable td, th {
      
      font-size: 12px;
    }

</style>
</head>
<body>
<header>
    <?php 
        $logo = base64_encode(file_get_contents(!empty($comsettinginfo->invoice_logo) ? $comsettinginfo->invoice_logo : 'application/modules/ordermanage/assets/images/dmi_logo.jpeg'));
        $total_price = number_format($bookinfo->total_price, 0, '.', '');
    ?>
    <div style="position: absolute; top: 18px; right: 18px; color: #28a745; font-size: 30px; text-align: center;">
        <span id="ipaid" style="font-size: 30px; font-weight: bolder;" class="color-red">
            <?php 
            if (isset($total_price)) {
                if ($paymentinfo_withadv->paid_amount >= $total_price) {
                    echo "<span style='font-size: 20px; font-weight: bolder; color: red;'>Unpaid</span>";
                } else {
                    echo "<span style='font-size: 20px; font-weight: bolder; color: green;'>Paid</span>";
                }
            } else {
                echo "<span style='color: red;'>Unpaid</span>";
            }
            ?>
        </span>
    </div>

    <div style="text-align: center;">
        <img src="data:image/png;base64,<?php echo $logo; ?>" alt="Logo" style="width:60px;height:60px;">
    </div>
    <div class="top-heading">
        <h5 style="font-weight:bold; margin: 0; font-size: 14px; color: #000;"><?php echo html_escape($storeinfo->storename); ?> </h5>
        <div style="color: #000; margin: 0; font-size: 14px;">
            <?php echo display("address") ?>: <?php echo html_escape($storeinfo->address); ?>
            
        </div>
    <div style="color: #000; margin: 0; font-size: 14px;"><strong><?php echo "Mobile : " ?></strong> <?php echo html_escape($storeinfo->phone); ?>&nbsp;<strong style="color: #000;"><?php echo "Email : " ?></strong> <?php echo html_escape($storeinfo->email); ?><br></div>
    <div style="margin: 0; font-size: 14px;"><strong style="color: #000;"><?php echo ("GST NO") ?>: <?php echo ("33AACCB5396H1ZN"); ?></strong></div>
    <h5 class="invp-10"  style="font-weight:bolder; margin: 0; color: #000; font-size: 14px;"><?php echo "INVOICE"; ?></h5>
    </div>
</header>
  <footer>
    <hr />PLEASE RETURN YOUR KEY CARD ON DEPARTURE <br><hr />
    I Agree that I am responsible for the full payment of this bill in the event it is not paid by the Company, Organisation, or
    Person indicated Billing Instructions: DIRECT
    <div class="left">
        Guest Signature
      </div>
      <div class="right">
        Authorized Signature
    </div>
</footer>
<div class="pagebreak header-table">
    <br><br>
    
      <tr style="font-size: 12px !important;"><table style="width: 100%;">
         <td>Guest Name</td>
         <td style="text-align: right !important;" colspan="2">Bill Date: <?php echo $orderinfo->order_date;?></td>
      </tr>
       <tr style="font-size: 12px !important;">
         <td>
            <?php echo html_escape((!empty($customerinfo->firstname)?$customerinfo->title.'.'.$customerinfo->firstname.' '.$customerinfo->lastname:'User Deleted'));?><br>
         </td>  
         <td style="text-align: right !important;" colspan="2">Bill NO: <?php echo $orderinfo->saleinvoice;?></td>
       </tr>
        <tr style="font-size: 12px !important;">
          <td style="min-width: 40px !important; max-width: 100px !important; word-wrap: break-word; word-break: break-all;">
            <?php echo html_escape(!empty($customerinfo->address)?$customerinfo->address:null);?><br>
            <?php echo html_escape(!empty($customerinfo->cust_phone)?$customerinfo->cust_phone:null);?><br>
            <?php echo html_escape(!empty($customerinfo->email)?$customerinfo->email:null);?><br>
            <?php echo html_escape(!empty($customerinfo->gst_no)?"GST No : ".$customerinfo->gst_no:null);?>
            <?php  
              $roomNumber = preg_replace('/[^0-9]/', '', $billinfo->room_number);
              if (!empty($roomNumber) && is_numeric($roomNumber)) {
              ?> 
              <strong><?php echo ('Room No');?>:</strong><?php echo $billinfo->room_number; ?>
            <?php } ?>
          </td>
        </tr>
    </table>
        <table width="100%" class="mainTable">
            <thead>
              <tr>
                <th class="text-center"><?php echo display('item')?> </th>
                <th class="text-center wp_100"><?php echo display('unit_price')?></th>
                <th class="text-center wp_100"><?php echo display('qty')?></th>
                <th class="text-center"><?php echo display('total_price')?></th>
               </tr>
            </thead>
            <tbody>
        <?php 
        $totalamount = 0;
        $subtotal = 0;
        $total = $orderinfo->totalamount;
        $multiplletax = array();
        $row_count = 0; 
        $total_rows = count($iteminfo); 

        foreach ($iteminfo as $item) {
            $i++;
            if ($item->price > 0) {
                $itemprice = $item->price * $item->menuqty;
                $singleprice = $item->price;
            } else {
                $itemprice = $item->mprice * $item->menuqty;
                $singleprice = $item->mprice;
            }

            $discount = 0;
            $adonsprice = 0;
            
            if (!empty($item->add_on_id)) {
                $addons = explode(",", $item->add_on_id);
                $addonsqty = explode(",", $item->addonsqty);
                $x = 0;
                
                foreach ($addons as $addonsid) {
                    $adonsinfo = $this->order_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
                    $adonsprice += $adonsinfo->price * $addonsqty[$x];
                    $x++;
                }
                
                $nittotal = $adonsprice;
            } else {
                $nittotal = 0;
            }

            $totalamount += $nittotal;
            $subtotal += $itemprice;

            $row_count++;

            if ($row_count > 1 && ($row_count - 1) % 15 == 0) {
                echo '</tbody></table></div>'; 
                echo '<div style="page-break-before: always;">';
                echo '<table width="100%" class="mainTable" style="margin-top: 100px;">';
                ?>
                <thead>
                  <tr>
                    <th class="text-center"><?php echo display('item')?></th>
                    <th class="text-center wp_100"><?php echo display('unit_price')?></th>
                    <th class="text-center wp_100"><?php echo display('qty')?></th>
                    <th class="text-center"><?php echo display('total_price')?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
            }
            ?>
                    <tr>
                           <td>
                              <?php echo $item->ProductName;?>
                           </td>
                           <td class="text-right">
                              <?php if($currency->position==1){echo $currency->curr_icon;}?>
                              <?php echo $singleprice;?>
                              <?php if($currency->position==2){echo $currency->curr_icon;}?> 
                           </td>
                           <td class="text-right"><?php echo $item->menuqty;?></td>
                           <td style="text-align: right;">
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
                           
                           $calvat=$billinfo->VAT;?>
        </tbody>
        </table>
    <?php if($tblsta =='closed'){
        echo '</div>';
    } ?>
<table class="subTable" style="margin-right: 10px; width: 30% !important; margin-top: 10px;">
    <tbody>
        <?php if(!empty($bookinfo->additional_reason) || !empty($bookinfo->additional_remarks)){  ?>
        <tr>
            <th style="font-weight:bold;border: none !important;">Additional Charge Reason></th>
            <th style="font-weight:bold;border: none !important;"><?php echo ("Remarks") ?></th>
        </tr>
        <tr style="font-size:18px;">
            <td style="border: none !important; background-color: #fff !important;" id="ad_rsn"><?php  echo $bookinfo->additional_reason; ?></td>
            <td style="border: none !important; background-color: #fff !important;" id="ad_rmk"><?php  echo $bookinfo->additional_remarks; ?></td>
        </tr>
        <?php } ?>

        <tr id="paymentmethod_<?php echo $key; ?>">
            <td style="font-weight:bold; border: none !important;" colspan="1"><?php echo display("payment_mode") ?></td>
            <td style="font-weight:bold; border: none !important;" colspan="1"><?php echo display("amnt") ?></td>
        </tr>
        <tr style="font-size:18px;">
            <td style="border: none !important; background-color: #fff !important;" id="pmode_<?php echo $key; ?>">
                <?php 
                    foreach ($multibill as $key => $value) : 
                    $getPayment = $this->db->select("*")->from("payment_method")->where("payment_method_id", $value['payment_type_id'])->get()->row();
                    $paymentMethod = $getPayment->payment_method;
                    $replacements = [
                       'Cash Payment' => 'Cash',
                       'Online Payment' => 'Online',
                       'Card Payment' => 'Card',
                    ];
           
                    if (array_key_exists($paymentMethod, $replacements)) {
                       $paymentMethod = $replacements[$paymentMethod];
                    }
                    echo $paymentMethod;
                    echo "<br/>";
                    endforeach;
                ?>
            </td>
            <td style="border: none !important; background-color: #fff !important;" id="pamount_<?php echo $key; ?>">
                <?php 
                    foreach ($multibill as $key => $value) {
                        echo $value['amount']; echo "<br/>";
                    }
                ?>
            </td>
        </tr>
    </tbody>
</table>
<table class="subTable" style="width: 70% !important; float: right; position: relative; left: 56; bottom: 45; margin-top:25px;">
    <tbody>
        <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo ('Amount')?><?php if(!empty($discountpr)){echo $discountpr;}?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>
            <?php echo $total_amount=$billinfo->total_amount; ?>
            <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo display('discount')?><?php if(!empty($discountpr)){echo $discountpr;}?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?>
            <?php $discount=0; if(empty($billinfo)){ echo $discount;} else{echo $discount=$billinfo->discount;} ?>
            <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo display('subtotal')?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;"><?php if($currency->position==1){echo $currency->curr_icon;}?><?php echo $itemtotal - $billinfo->discount;?><?php if($currency->position==2){echo $currency->curr_icon;}?>
            </td>
        </tr>
        
        <?php if(empty($taxinfos)){ ?>
        <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo ('CGST')?>(<?php echo '2.5';?>%)</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;">
                <?php if($currency->position==1){echo $currency->curr_icon;}?>
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
                  <?php if($currency->position==2){echo $currency->curr_icon;}?>
            </td>
        </tr>
         <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo ('SGST')?>(<?php echo '2.5';?>%)</td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;">
                <?php if($currency->position==1){echo $currency->curr_icon;}?>
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
                <?php if($currency->position==2){echo $currency->curr_icon;}?>
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
               <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><strong><?php echo $mvat['tax_name'];?></strong>
               </td>
               <td style="border: none !important;">
                  <strong><?php if($currency->position==1){echo $currency->curr_icon;}?>
                  <?php echo $taxinfo->$fieldname;?>
                  <?php if($currency->position==2){echo $currency->curr_icon;}?> </strong>
               </td>
            </tr>
        <?php $i++;} } }?>

        <tr>
            <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;"><?php echo display('grand_total')?></td>
            <td style="border: none !important; text-align: right;">:</td>
            <td style="border: none !important;">
                <?php if($currency->position==1){echo $currency->curr_icon;}?>
                  <?php
                        if($discount > 0) {
                         $gt = $final + $sum + $sum1;   
                         echo $gt;
                        } else {
                            echo $billinfo->bill_amount; 
                        }
                    ?>                                     
                  <?php if($currency->position==2){echo $currency->curr_icon;}?>
            </td>
        </tr>

        <?php 
        if ($orderinfo->order_status != 5) {
            if ($orderinfo->customerpaid > 0) {
                $customepaid = $orderinfo->customerpaid;
                $changes = $customepaid - $orderinfo->totalamount;
            } else {
                $customepaid = $orderinfo->totalamount;
                $changes = 0;
            }
            
            if ($billinfo->bill_status == 1) { ?>
                <tr>
                    <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;">
                        <?php echo display('customer_paid_amount')?>
                    </td>
                    <td style="border: none !important; text-align: right;">:</td>
                    <td style="border: none !important;">
                        <?php if ($currency->position == 1) { echo $currency->curr_icon; } ?>
                        <?php
                        if ($discount > 0) {
                            $gt = $final + $sum + $sum1;  
                            echo $gt;
                        } else {
                            echo $customepaid; 
                        }
                        ?>
                        <?php if ($currency->position == 2) { echo $currency->curr_icon; } ?>
                    </td>
                </tr>
            <?php } else{ ?>
                <tr>
                    <td colspan="4" style="text-align: right; border: none !important; font-weight: bold;">
                        <?php echo display('total_due')?>
                    </td>
                    <td style="border: none !important; text-align: right;">:</td>
                    <td style="border: none !important;">
                        <?php if($currency->position==1){echo $currency->curr_icon;}?>
                            <?php
                                if($discount > 0) {
                                    $gt = $final + $sum + $sum1;  
                                    echo $gt;
                                 } else {
                                    echo $customepaid; 
                                }
                            ?>                      
                        <?php if($currency->position==2){echo $currency->curr_icon;}?>
                    </td>
                </tr>
            <?php } } ?>
    </tbody>
</table>
</body>
</html>
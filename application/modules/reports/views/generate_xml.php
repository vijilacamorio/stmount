<?php

header('Content-Type: application/json');

$t_report_start = '<?xml version="1.0" encoding="ISO-8859-1"?>
<ENVELOPE>
    <HEADER>
        <TALLYREQUEST>Import Data</TALLYREQUEST>
    </HEADER>
    <BODY>
        <IMPORTDATA>
            <REQUESTDESC>
                <REPORTNAME>TALLY INTERFACE</REPORTNAME>
                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>Blue Nile properties pvt ltd-Joe Beach Resort</SVCURRENTCOMPANY>
                </STATICVARIABLES>
            </REQUESTDESC>
            <REQUESTDATA>';


// Room Details
foreach ($roomdatareport as $value) {
$bed_array = explode(",", $value->bed_amount);
$person_array = explode(",", $value->person_amount);
$child_array = explode(",", $value->child_amount);
$roomrate_array = explode(",", $value->roomrate);

// Remove empty elements
$bed_array = array_filter($bed_array, function($value) {
    return $value !== '';
});
$person_array = array_filter($person_array, function($value) {
    return $value !== '';
});
$child_array = array_filter($child_array, function($value) {
    return $value !== '';
});

// Sum the amounts
$bed_sum = array_sum($bed_array);
$person_sum = array_sum($person_array);
$child_sum = array_sum($child_array);
$roomrate_sum = array_sum($roomrate_array);

    $totaltraiff=$value->total_price;
    $cgst = ($bed_sum+$person_sum+$child_sum+$roomrate_sum-$value->discountamount) * 0.06;
   
   $allrent=$bed_sum+$person_sum+$child_sum+$roomrate_sum-$value->discountamount;

    $checkindate = explode(' ', $value->checkindate)[0];
    $checkoutdate = explode(' ', $value->checkoutdate)[0];

    $checkinDateTime = DateTime::createFromFormat('d-m-Y', $checkindate);
    $checkoutDateTime = DateTime::createFromFormat('d-m-Y', $checkoutdate);

    $adv_amount= $this->db->select("sum(paymentamount) as advance")->from("tbl_guestpayments")->like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",0)->get()->row();

    // print_r($adv_amount->advance); die();

    // $guestpaymenttypeamount= $this->db->select("paymenttype,paymentamount")->from("tbl_guestpayments")->not_like('details', 'Advance')->where("bookedid",$value->bookedid)->get()->row();

    $guestpaymenttypeamount= $this->db->select("*")->from("tbl_guestpayments")->like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",0)->get()->result();
 $finalpayment= $this->db->select("*")->from("tbl_guestpayments")->not_like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",0)->get()->result();

    $formattedCheckinDate = $checkinDateTime ? $checkinDateTime->format('Ymd') : 'Invalid Date';
    $formattedCheckinDate1 = $checkinDateTime ? $checkinDateTime->format('d/m/Y') : 'Invalid Date';
    $formattedCheckoutDate2 = $checkoutDateTime ? $checkoutDateTime->format('d/m/Y') : 'Invalid Date';
    $guestName = $value->firstname . ' ' . $value->lastname;

    $t_report_start .= '
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
        <VOUCHER VCHTYPE="Counter Sales" ACTION="Create">
            <ISOPTIONAL>No</ISOPTIONAL>
            <USEFORGAINLOSS>No</USEFORGAINLOSS>
            <USEFORCOMPOUND>No</USEFORCOMPOUND>
            <VOUCHERTYPENAME>Counter Sales</VOUCHERTYPENAME>
            <DATE>'.$formattedCheckinDate.'</DATE>
            <EFFECTIVEDATE>'.$formattedCheckinDate.'</EFFECTIVEDATE>
            <ISCANCELLED>No</ISCANCELLED>
            <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
            <ISPOSTDATED>No</ISPOSTDATED>
            <ISINVOICE>No</ISINVOICE>
            <DIFFACTUALQTY>No</DIFFACTUALQTY>
            <VOUCHERNUMBER>'.$value->booking_number.'</VOUCHERNUMBER>
            <REFERENCE>'.$value->booking_number.'</REFERENCE>
            <PARTYLEDGERNAME></PARTYLEDGERNAME>
            <NARRATION>FO BILL: '.$value->booking_number.' GUEST NAME: '.$guestName.'; Arr.Dt:'.$formattedCheckinDate1.'; Dep.Dt:'.$formattedCheckoutDate2.'; Roomno:'.$value->room_no.' : Recpt#: '.$value->booking_number.'</NARRATION>
            <UDF:HARYANAVAT.LIST DESC="`HARYANAVAT`"></UDF:HARYANAVAT.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>';
            foreach ($guestpaymenttypeamount as $amount) {
            $t_report_start .= '<LEDGERNAME>INH ADVANCE</LEDGERNAME>
                <AMOUNT>-'.$amount->paymentamount.'</AMOUNT>';
            }
            $t_report_start .= '<BILLALLOCATIONS.LIST>
                    <NAME>'.$value->booking_number.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . ($adv_amount->advance > 0 ? $totaltraiff - $adv_amount->advance : $totaltraiff) . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
              <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>';
            foreach ($finalpayment as $amount) {
            $paymenttype = $amount->paymenttype;
             $ledgername = '';

if ($paymenttype == 'Bill to company') {
    $ledgername = $guestName;
} elseif ($paymenttype == 'Cash') {
    $ledgername = 'Cash';
} elseif ($paymenttype == 'Bank Payment') {
  
    $ledgername = 'CENTRAL BANK OF INDIA-5532547292';
} else {
   
    $ledgername = $paymenttype; 
}
   
            $t_report_start .= '<LEDGERNAME>'.$ledgername.'</LEDGERNAME>
                <AMOUNT>-'.$amount->paymentamount.'</AMOUNT>';
            }
            $t_report_start .= '<BILLALLOCATIONS.LIST>
                    <NAME>'.$value->booking_number.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . $amount->paymentamount . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
           <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>TARIFF</LEDGERNAME>
                <AMOUNT>'.$allrent.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>CGST@6%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>SGST@6%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
        </VOUCHER>
    </TALLYMESSAGE>';
}

// Restaurant  Details


foreach ($restaurtantdatareport as $value) {
    $totaltraiff = $value->bill_amount;
    $cgst = ($value->total_amount * 2.5) / 100;

    $restaurant_payment = $this->db->select('multipay_bill.amount,payment_method.payment_method')
        ->from('multipay_bill')
        ->join('payment_method', 'multipay_bill.payment_type_id = payment_method.payment_method_id', 'left')
        ->where('multipay_bill.order_id', $value->order_id)
        ->get()
        ->result();

    $guestName = $value->firstname . ' ' . $value->lastname;

$t_report_start .= '
<TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Counter Sales" ACTION="Create">
        <ISOPTIONAL>No</ISOPTIONAL>
        <USEFORGAINLOSS>No</USEFORGAINLOSS>
        <USEFORCOMPOUND>No</USEFORCOMPOUND>
        <VOUCHERTYPENAME>Counter Sales</VOUCHERTYPENAME>
        <DATE>'.$value->bill_date.'</DATE>
        <EFFECTIVEDATE>'.$value->bill_date.'</EFFECTIVEDATE>
        <ISCANCELLED>No</ISCANCELLED>
        <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
        <ISPOSTDATED>No</ISPOSTDATED>
        <ISINVOICE>No</ISINVOICE>
        <DIFFACTUALQTY>No</DIFFACTUALQTY>
        <VOUCHERNUMBER>'.$value->saleinvoice.'</VOUCHERNUMBER>
        <REFERENCE>'.$value->saleinvoice.'</REFERENCE>
        <PARTYLEDGERNAME></PARTYLEDGERNAME>
      <NARRATION>'.$value->saleinvoice.' GUEST NAME:'.$value->room_number.'</NARRATION>

        <UDF:HARYANAVAT.LIST DESC="`HARYANAVAT`"></UDF:HARYANAVAT.LIST>';

$credit_total = 0;

  $t_report_start .= '<ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>';
foreach ($restaurant_payment as $entry) {
 
    $restaurant_paymentmethod=$entry->payment_method;
            $ledgername = '';

if ($paymenttype == 'Bill to company') {
    $ledgername = $guestName;
} elseif ($paymenttype == 'Cash') {
    $ledgername = 'Cash';
} elseif ($paymenttype == 'Bank Payment') {
    // Assuming you want to append 'CENTRAL BANK OF INDIA - 553247292'
    $ledgername = 'CENTRAL BANK OF INDIA-5532547292';
} else {
    // Handle any other payment type scenarios here
    $ledgername = $paymenttype; // Default to the paymenttype if none of the above conditions match
}
$t_report_start .= '<LEDGERNAME>'.$ledgername.'</LEDGERNAME>
                <AMOUNT>-'.$entry->amount.'</AMOUNT>';
  $t_report_start .= '
     <BILLALLOCATIONS.LIST>
                    <NAME>'.$value->saleinvoice.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . $entry->amount . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
                 </ALLLEDGERENTRIES.LIST>
       <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM><BILLALLOCATIONS.LIST>
                    <NAME>'.$value->saleinvoice.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . $entry->amount . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
             

        ';
   // $credit_total += $entry->amount;
}

// Debit Entry to balance the transaction
$t_report_start .= '

   <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>Tariff</LEDGERNAME>
                <AMOUNT>'.$value->total_amount.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>CGST@2.5%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>SGST@2.5%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
        ';

$t_report_start .= '
    </VOUCHER>
</TALLYMESSAGE>';
}


// Hall Details
foreach ($halldatareport as $value) {

    $totaltraiff=$value->totalamount;
    $cgst = ($value->rent-$value->special_discount) * 0.09;
   
    $checkindate = explode(' ', $value->start_date)[0];
    $checkoutdate = explode(' ', $value->end_date)[0];

    $checkinDateTime = DateTime::createFromFormat('d-m-Y', $checkindate);
    $checkoutDateTime = DateTime::createFromFormat('d-m-Y', $checkoutdate);

    $adv_amount= $this->db->select("sum(paymentamount) as advance")->from("tbl_guestpayments")->like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",1)->get()->row();

   
  //  $guestpaymenttypeamount= $this->db->select("*")->from("tbl_guestpayments")->where("bookedid",$value->bookedid)->where("book_type",1)->get()->result();
    $guestpaymenttypeamount= $this->db->select("*")->from("tbl_guestpayments")->like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",1)->get()->result();

 $finalpayment= $this->db->select("*")->from("tbl_guestpayments")->not_like('details', 'Advance')->where("bookedid",$value->bookedid)->where("book_type",1)->get()->result();
 
 
    $formattedCheckinDate = $checkinDateTime ? $checkinDateTime->format('Ymd') : 'Invalid Date';
    $formattedCheckinDate1 = $checkinDateTime ? $checkinDateTime->format('d/m/Y') : 'Invalid Date';
    $formattedCheckoutDate2 = $checkoutDateTime ? $checkoutDateTime->format('d/m/Y') : 'Invalid Date';
    $guestName = $value->firstname . ' ' . $value->lastname;

    $t_report_start .= '
    <TALLYMESSAGE xmlns:UDF="TallyUDF">
        <VOUCHER VCHTYPE="Counter Sales" ACTION="Create">
            <ISOPTIONAL>No</ISOPTIONAL>
            <USEFORGAINLOSS>No</USEFORGAINLOSS>
            <USEFORCOMPOUND>No</USEFORCOMPOUND>
            <VOUCHERTYPENAME>Counter Sales</VOUCHERTYPENAME>
            <DATE>'.$formattedCheckinDate.'</DATE>
            <EFFECTIVEDATE>'.$formattedCheckinDate.'</EFFECTIVEDATE>
            <ISCANCELLED>No</ISCANCELLED>
            <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
            <ISPOSTDATED>No</ISPOSTDATED>
            <ISINVOICE>No</ISINVOICE>
            <DIFFACTUALQTY>No</DIFFACTUALQTY>
            <VOUCHERNUMBER>'.$value->invoice_no.'</VOUCHERNUMBER>
            <REFERENCE>'.$value->invoice_no.'</REFERENCE>
            <PARTYLEDGERNAME></PARTYLEDGERNAME>
            <NARRATION>FO BILL: '.$value->invoice_no.' GUEST NAME: '.$guestName.'; Arr.Dt:'.$formattedCheckinDate1.'; Dep.Dt:'.$formattedCheckoutDate2.'; Roomno:'.$value->room_no.' : Recpt#: '.$value->invoice_no.'</NARRATION>
            <UDF:HARYANAVAT.LIST DESC="`HARYANAVAT`"></UDF:HARYANAVAT.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>';
            foreach ($guestpaymenttypeamount as $amount) {
            $t_report_start .= '<LEDGERNAME>INH ADVANCE</LEDGERNAME>
                <AMOUNT>-'.$amount->paymentamount.'</AMOUNT>';
            }
            $t_report_start .= '<BILLALLOCATIONS.LIST>
                    <NAME>'.$value->invoice_no.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . ($adv_amount->advance > 0 ? $totaltraiff - $adv_amount->advance : $totaltraiff) . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
              <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>';
            foreach ($finalpayment as $amount) {
            $paymenttype = $amount->paymenttype;
            $ledgername = '';

if ($paymenttype == 'Bill to company') {
    $ledgername = $guestName;
} elseif ($paymenttype == 'Cash') {
    $ledgername = 'Cash';
} elseif ($paymenttype == 'Bank Payment') {
    // Assuming you want to append 'CENTRAL BANK OF INDIA - 553247292'
    $ledgername = 'CENTRAL BANK OF INDIA-5532547292';
} else {
    // Handle any other payment type scenarios here
    $ledgername = $paymenttype; // Default to the paymenttype if none of the above conditions match
}
            $t_report_start .= '<LEDGERNAME>'.$ledgername.'</LEDGERNAME>
                <AMOUNT>-'.$amount->paymentamount.'</AMOUNT>';
            }
            $t_report_start .= '<BILLALLOCATIONS.LIST>
                    <NAME>'.$value->invoice_no.'</NAME>
                    <BILLTYPE>New Ref</BILLTYPE>
                    <BILLCREDITPERIOD/>
                    <AMOUNT>-' . $amount->paymentamount . '
                    </AMOUNT>
                </BILLALLOCATIONS.LIST>
            </ALLLEDGERENTRIES.LIST>
        
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>TARIFF</LEDGERNAME>
                <AMOUNT>'.($value->paid_amount-$cgst-$cgst).'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>CGST@9%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
            <ALLLEDGERENTRIES.LIST>
                <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                <LEDGERFROMITEM>No</LEDGERFROMITEM>
                <LEDGERNAME>SGST@9%</LEDGERNAME>
                <AMOUNT>'.$cgst.'</AMOUNT>
            </ALLLEDGERENTRIES.LIST>
        </VOUCHER>
    </TALLYMESSAGE>';
}

$t_report_end = '</REQUESTDATA>
        </IMPORTDATA>
    </BODY>
</ENVELOPE>';

$t_report = $t_report_start . $t_report_end;

echo json_encode(['xml_data' => $t_report]);

?>


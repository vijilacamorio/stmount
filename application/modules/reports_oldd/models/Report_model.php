<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report_model extends CI_Model {
    
//     public function fetchBookingdata($customer=NULL,$status=NULL,$payment_status=NULL,$fromdate=NULL,$todate=NULL)
//     {
//         $betweenq="booked_info.checkindate >='".$fromdate."' AND booked_info.checkoutdate <='".$todate."'";
// 		$this->db->select('booked_info.*,booked_details.*,roomdetails.roomtype');
//         $this->db->from('booked_info');
// 		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
// 		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
		
// 		$query = $this->db->get();
// 		echo $this->db->last_query(); die();

//         return $query->result();
//     }
	
 
	public function getlist($customer=NULL,$status=NULL,$payment_status=NULL,$fromdate=NULL,$todate=NULL)
	{
		  $fromdate = $fromdate;
$fromdate .= ' 00:00:00'; // Appending the time part
  $todate = $todate;
$todate .= ' 23:59:59'; // Appending the time part
		$betweenq="booked_info.checkindate >='".$fromdate."' AND booked_info.checkoutdate <='".$todate."'";
		$this->db->select('booked_info.*,booked_details.*,roomdetails.roomtype');
        $this->db->from('booked_info');
		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
		if($fromdate != NULL){
			$this->db->where('booked_info.checkindate >=',$fromdate);
		}
		if($todate != NULL){
			$this->db->where('booked_info.checkoutdate <=',$todate);
		}
		if($status != NULL){
			$this->db->where('booked_info.bookingstatus',$status);
		}
		if($customer != NULL){
			$this->db->where('booked_info.cutomerid',$customer);
		}
        $this->db->order_by('booked_info.bookedid', 'desc');
        //  ->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE)
        $query = $this->db->get();
    // echo $this->db->last_query();die();
		$scharge = $this->settinginfo();
		$charge = $scharge->servicecharge;
		$paymentarray = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();    
			foreach($result as $k => $r){
				$start = strtotime($r->checkindate);
				$end = strtotime($r->checkoutdate);
			
				$result[$k]->roomtype = $this->room_type($r->roomid);
				if($r->booked_from==1 & $r->bookingstatus==0){
					$result[$k]->total_price = $r->total_price;
					if($result[$k]->total_price>$result[$k]->paid_amount & $payment_status==3){
						$paymentarray[$k] = $result[$k];
					}
					else if($result[$k]->total_price<=$result[$k]->paid_amount & $payment_status==1){
						$paymentarray[$k] = $result[$k];
					}
				}else{
					$roomId = explode(",",$r->roomid);
					$rent = explode(",",$r->roomrate);
					$offer_discount = explode(",",$r->offer_discount);
					$totalrent=0;
					$totaloffer=0;
					for($i=0;$i<count($rent); $i++){
						$totalrent += $rent[$i] - $offer_discount[$i];
						$totaloffer += $offer_discount[$i];
					}
					$promocode = 0;
					if(!empty($r->promocode)){
						$pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $r->promocode)->get()->row();
						$promocode = $pdiscount->discount;
					}
					if($r->bookingstatus!=5){
						$result[$k]->total_price = $r->total_price + $promocode + ( (($totalrent*$charge)/100));
						$result[$k]->paid_amount = $r->paid_amount;
					}
					if($r->bookingstatus==5){
						$creditamt = $this->db->select("rate,credit,complementary,extrabpc,additional_charges,additional_charges,ex_discount,swimming_pool,restaurant,hallroom,car_parking,special_discount,scharge")->from("tbl_postedbills")->where("bookedid",$r->bookedid)->get()->row();
						$result[$k]->creditamt = $creditamt->credit;
						$result[$k]->total_price =$creditamt->complementary + $creditamt->extrabpc + $r->total_price + $promocode + $creditamt->scharge + $creditamt->additional_charges - $creditamt->ex_discount + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->special_discount;
						$result[$k]->paid_amount = $r->paid_amount + ((($totalrent*$charge)/100)) + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->ex_discount - $creditamt->special_discount;
						if($creditamt->credit>0 & $payment_status==2){
							$paymentarray[$k] = $result[$k];
						}else if($creditamt->credit==0 & $r->bookingstatus==5 & $payment_status==1){
							$paymentarray[$k] = $result[$k];
						}
						if($totaloffer>0){
							$datediff = strtotime($r->checkoutdate) - strtotime($r->checkindate);
							$datediff = ceil($datediff/(60*60*24));
							$singletax = explode(",", $creditamt->rate);
							$total=0;
							for($li = 0; $li < count($rent); $li++){
								for($in = 0; $in < $datediff; $in++){
									$alldays= date("d-m-Y", strtotime($r->checkindate . ' + ' . $in . 'day'));
									$getroom=$this->db->select("*")->from('tbl_room_offer')->where('roomid',$roomId[$li])->where('offer_date',$alldays)->get()->row();
									if(!empty($getroom)){
										$singleDiscount=$getroom->offer;
										$roomrate=$rent[$li]-$singleDiscount;
										}
									else{
										$roomrate=$rent[$li];
										}
									$price=$roomrate;
									$total=$total+$price;
								}
							}
							$toaltax=0;
							for($j=0; $j<count($singletax); $j++){
								$toaltax += ($total*$singletax[$j])/100;
							}
							$result[$k]->total_price =$creditamt->complementary + $creditamt->extrabpc + $total + $toaltax + $promocode + $creditamt->scharge + $creditamt->additional_charges - $creditamt->ex_discount + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->special_discount;
						}
					}else{
						if($result[$k]->total_price>$result[$k]->paid_amount & $payment_status==3){
							$paymentarray[$k] = $result[$k];
						}
						else if($result[$k]->total_price<=$result[$k]->paid_amount & $payment_status==1){
							$paymentarray[$k] = $result[$k];
						}
					}
				}
			}
			//echo $this->db->last_query();die();
		//	print_r($result);
			if(!empty($payment_status)){
				return $paymentarray;
			}
			return $result;
        }
        return false;
	
	}
	public function transaction_report($start_date = NULL, $to_date = NULL){

    $this->db->select("*"); 

    $this->db->from('acc_transaction');
    
if($start_date){
    $this->db->where("VDate BETWEEN '$start_date' AND '$to_date'");
}
    $this->db->where('IsAppove',1);
    $query = $this->db->get();

    return $query->result();

	}
    public function food_info($start_date = NULL, $to_date = NULL){
$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
 $this->db->select("c.*,b.*,a.*,(a.customerpaid) as customerpaid, (b.discount) as discount, (b.bill_amount) as bill_amount, (b.total_amount) as total_amount,(b.discount) as discount"); 
 
    $this->db->from('customer_order a');
       $this->db->join('bill b', ' b.bill_id= a.order_id');
       $this->db->join('customerinfo c','a.customer_id=c.customerid');
  //  $this->db->where("b.bill_status", "1");
   // $this->db->where("b.create_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
//echo $this->db->last_query();die();
return $query->result();

    }
	//For Room Advance
	public function nightaudit_advance(){
	
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

  $query = $this->db->select('SUM(a.paid_amount) as paid_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.paid_paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                    
                      ->get();
// echo $this->db->last_query();die();
    return $query->result();
}
	public function nightaudit_advance_m(){
 $current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

  $query = $this->db->select('SUM(a.paid_amount) as paid_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                    
                    ->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE)
                      ->get();

    return $query->result();
}
	public function nightaudit_advance_y(){
	
    $current_year = date('Y'); // Get current year in 'YYYY' format
 $start_datetime = '01-01-' . $current_year . ' 00:00:00'; // Start of the year
$end_datetime = '31-12-' . $current_year . ' 23:59:59'; // End of the year
  $query = $this->db->select('SUM(a.paid_amount) as paid_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.paid_paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                    
                      ->get();

  
  

//echo $this->db->last_query();die();
    return $query->result();
}
	public function nightaudit_advance_ad(){
	
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

  $query = $this->db->select('SUM(b.advance_amount) as advance_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.paid_paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                     -> where('a.bookingstatus !=5')
                      ->get();
    return $query->result();
}
	public function nightaudit_advance_m_ad(){
 $current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

  $query = $this->db->select('SUM(b.advance_amount) as advance_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      -> where('a.bookingstatus !=5')
                    ->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE)
                      ->get();

    return $query->result();
}
	public function nightaudit_advance_y_ad(){
	
    $current_year = date('Y'); // Get current year in 'YYYY' format
 $start_datetime = '01-01-' . $current_year . ' 00:00:00'; // Start of the year
$end_datetime = '31-12-' . $current_year . ' 23:59:59'; // End of the year
  $query = $this->db->select('SUM(b.advance_amount) as advance_amount')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.paid_paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                     -> where('a.bookingstatus !=5')
                      ->get();

    return $query->result();
}
//for amount receioved and due amount
public function nightaudit_amt_due_received(){
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

$this->db->select('SUM(gp.paymentamount) AS paid_amount, SUM(bi.total_price) AS total_amount');
$this->db->from('booked_info bi');
$this->db->join('tbl_guestpayments gp', 'bi.bookedid = gp.bookedid', 'left');
$this->db->where('gp.paydate BETWEEN "'.$start_datetime.'" AND "'.$end_datetime.'"');
$this->db->where('gp.book_type', 0);

$query = $this->db->get();
//echo $this->db->last_query();die();
    return $query->result();
}
public function nightaudit_amt_due_received_m(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = date('Y-m-01', strtotime($current_month)); // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)); // End of the month


$this->db->select('SUM(gp.paymentamount) AS paid_amount, SUM(bi.total_price) AS total_amount');
$this->db->from('booked_info bi');
$this->db->join('tbl_guestpayments gp', 'bi.bookedid = gp.bookedid', 'left');
$this->db->where('gp.paydate BETWEEN "'.$start_datetime.'" AND "'.$end_datetime.'"');
$this->db->where('gp.book_type', 0);

$query = $this->db->get();

    return $query->result();
}
public function nightaudit_amt_due_received_y(){
     $current_year = date('Y'); // Get current year in 'YYYY' format
 $start_datetime = '01-01-' . $current_year . ' 00:00:00'; // Start of the year
$end_datetime = '31-12-' . $current_year . ' 23:59:59'; // End of the year

$this->db->select('SUM(gp.paymentamount) AS paid_amount, SUM(bi.total_price) AS total_amount');
$this->db->from('booked_info bi');
$this->db->join('tbl_guestpayments gp', 'bi.bookedid = gp.bookedid', 'left');
$this->db->where('gp.paydate BETWEEN "'.$start_datetime.'" AND "'.$end_datetime.'"');
$this->db->where('gp.book_type', 0);

$query = $this->db->get();

    return $query->result();
}

// public function nightaudit()
// {
//     // Get today's date and time
//     $today_date = date('d-m-Y');
//     $start_datetime = $today_date . ' 00:00:00';
//     $end_datetime = $today_date . ' 23:59:59';
     
//   $this->db->select(' 
//                     SUM(a.paid_amount) as paid_amount, 
//                     SUM(a.roomrate) as roomrate, 
//                     SUM(a.total_room) as total_room, 
//                     SUM(a.children) as children, 
//                     SUM(a.nuofpeople) as nuofpeople, 
//                     SUM(a.total_price) as total_price, 
//                      SUM(b.discountamount) as discountamount,
//                     SUM(b.cgst) as cgst, 
//                     SUM(b.sgst) as sgst, 
//                     SUM(a.bc) as bc, 
//                     SUM(a.pc) as pc, 
//                     SUM(a.cc) as cc');

//     // From clause
//     $this->db->from('booked_info a');
//     $this->db->join('booked_details b', 'a.bookedid = b.bookedid');

//     $this->db->where('a.paid_paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');

//  $this->db->where('a.bookingstatus = 5');
//  $this->db->group_by('a.bookedid'); // Group by a unique identifier column

//     $query = $this->db->get();
//     return $query->result();
// }
public function nightaudit()
{
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    // Fetch data from the database
    $query = $this->db->select('a.*, b.*')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.checkindate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                      ->where('a.bookingstatus', 4)
                        ->or_where('a.bookingstatus', 5)
                      ->get();
//echo $this->db->last_query();die();
    // Process the retrieved data
    $result = $query->result();
    $processedData = [];

    foreach ($result as $row) {
        // Perform calculations or manipulations as needed
        $roomRateArray = explode(',', $row->roomrate);
        $totalRoomRate = array_sum($roomRateArray);

        // Add additional calculations or data manipulations here

        // Store the processed data
        $processedData[] = [
            'paid_amount' => $row->paid_amount,
            'total_room' => $row->total_room,
         //   'adv'  => $row->advance_amount,
            'total_price' => $row->total_price,
            'discountamount' => $row->discountamount,
            'cgst' => $row->cgst,
            'sgst' => $row->sgst,
            'bc' => $row->bc,
            'pc' => $row->pc,
            'cc' => $row->cc,
            'roomrate' => $totalRoomRate, // Example additional calculation
        ];
    }

    // Return the processed data to the view
    return $processedData;
}
//hall advance

public function nightaudit_hall_advance()
{
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    
    $this->db->select_sum('tbl_guestpayments.paymentamount', 'total_advance_amount');
    $this->db->from('tbl_guestpayments');
    $this->db->join('tbl_hallroom_booking', 'tbl_hallroom_booking.hbid = tbl_guestpayments.bookedid');
    $this->db->where('tbl_guestpayments.details LIKE', 'Advance%');
      $this->db->where("tbl_guestpayments.paydate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where('tbl_guestpayments.book_type', 1);
    $this->db->where('tbl_hallroom_booking.status !=', 5);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();
    }

    return 0; // If no rows are found, return 0 as the total advance amount
}
public function nightaudit_hall_advance_m()
{
    $current_month = date('d-m-Y'); // Get current date in 'd-m-Y' format
$start_datetime = date('d-m-Y', strtotime('first day of ' . $current_month)); // Start of the month
$end_datetime = date('d-m-Y', strtotime('last day of ' . $current_month)); // End of the month
     $this->db->select_sum('tbl_guestpayments.paymentamount', 'total_advance_amount');
    $this->db->from('tbl_guestpayments');
    $this->db->join('tbl_hallroom_booking', 'tbl_hallroom_booking.hbid = tbl_guestpayments.bookedid');
    $this->db->where('tbl_guestpayments.details LIKE', 'Advance%');
      $this->db->where("tbl_guestpayments.paydate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where('tbl_guestpayments.book_type', 1);
    $this->db->where('tbl_hallroom_booking.status !=', 5);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();
    }

    return 0; // If no rows are found, return 0 as the total advance amount
}
public function nightaudit_hall_advance_y()
{
    $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year
    $this->db->select_sum('tbl_guestpayments.paymentamount', 'total_advance_amount');
    $this->db->from('tbl_guestpayments');
    $this->db->join('tbl_hallroom_booking', 'tbl_hallroom_booking.hbid = tbl_guestpayments.bookedid');
    $this->db->where('tbl_guestpayments.details LIKE', 'Advance%');
      $this->db->where("tbl_guestpayments.paydate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where('tbl_guestpayments.book_type', 1);
    $this->db->where('tbl_hallroom_booking.status !=', 5);

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();
    }

    return 0; // If no rows are found, return 0 as the total advance amount
}
public function nightaudit_hall(){

$current_date = date('Y-m-d');

// Start of the day (00:00:00)
$start_datetime = $current_date . ' 00:00:00';

// End of the day (23:59:59)
$end_datetime = $current_date . ' 23:59:59';

$sql = "SELECT a.*, b.*
FROM tbl_hallroom_booking a
JOIN (
    SELECT bookedid, MAX(paydate) AS max_paydate
    FROM tbl_guestpayments
    WHERE STR_TO_DATE(paydate, '%d-%m-%Y') BETWEEN '$start_datetime' AND '$end_datetime'
    AND book_type = 1
    GROUP BY bookedid
) AS max_paydates ON a.hbid = max_paydates.bookedid
JOIN tbl_guestpayments b ON a.hbid = b.bookedid AND b.paydate = max_paydates.max_paydate
WHERE a.status = 5 GROUP BY a.hbid";

$query = $this->db->query($sql);//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        $result = $query->result();
        $processedData = [
            'rent' => 0,
            'totalamount' => 0,
             'additional_charges' =>0,
            'paid_amount' => 0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ];

        foreach ($result as $row) {
            $processedData['rent'] += $row->rent;
            $processedData['totalamount'] += $row->totalamount;
            $processedData['paid_amount'] += $row->paid_amount;
             $processedData['additional_charges']    += $row-> additional_charges;
            $processedData['special_discount'] += $row->special_discount;
            $processedData['cgst'] += $row->cgst;
            $processedData['sgst'] += $row->sgst;
        }

        return $processedData;
    } else {
        return [
            'rent' => 0,
            'totalamount' => 0,
            'paid_amount' => 0,
             'additional_charges' =>0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ]; // Return empty array if no data found
    }
}
public function nightaudit_current_month_hall() {
$current_month = date('m');
$current_year = date('Y');

// Get the first day of the current month
$start_date = date('Y-m-01', strtotime($current_year . '-' . $current_month . '-01'));

// Get the last day of the current month
$end_date = date('Y-m-t', strtotime($current_year . '-' . $current_month . '-01'));


$sql = "SELECT a.*, b.*
FROM tbl_hallroom_booking a
JOIN (
    SELECT bookedid, MAX(paydate) AS max_paydate
    FROM tbl_guestpayments
    WHERE STR_TO_DATE(paydate, '%d-%m-%Y') BETWEEN '$start_date' AND '$end_date'
    AND book_type = 1
    GROUP BY bookedid
) AS max_paydates ON a.hbid = max_paydates.bookedid
JOIN tbl_guestpayments b ON a.hbid = b.bookedid AND b.paydate = max_paydates.max_paydate
WHERE a.status = 5 GROUP BY a.hbid";

$query = $this->db->query($sql);//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        $result = $query->result();
        $processedData = [
            'rent' => 0,
            'totalamount' => 0,
            'paid_amount' => 0,
            'additional_charges' =>0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ];

        foreach ($result as $row) {
            $processedData['rent'] += $row->rent;
            $processedData['totalamount'] += $row->totalamount;
            $processedData['paid_amount'] += $row->paid_amount;
          $processedData['additional_charges']    += $row-> additional_charges;
            $processedData['special_discount'] += $row->special_discount;
            $processedData['cgst'] += $row->cgst;
            $processedData['sgst'] += $row->sgst;
        }

        return $processedData;
    } else {
        return [
            'rent' => 0,
            'totalamount' => 0,
             'additional_charges' =>0,
            'paid_amount' => 0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ]; // Return empty array if no data found
    }
}

public function nightaudit_current_year_hall(){
$current_year_start = date('Y-01-01'); // Start of the current year
$current_year_end = date('Y-12-31'); // End of the current year
$sql = "SELECT a.*, b.*
FROM tbl_hallroom_booking a
JOIN (
    SELECT bookedid, MAX(paydate) AS max_paydate
    FROM tbl_guestpayments
    WHERE STR_TO_DATE(paydate, '%d-%m-%Y') BETWEEN '$current_year_start' AND '$current_year_end'
    AND book_type = 1
    GROUP BY bookedid
) AS max_paydates ON a.hbid = max_paydates.bookedid
JOIN tbl_guestpayments b ON a.hbid = b.bookedid AND b.paydate = max_paydates.max_paydate
WHERE a.status = 5 GROUP BY a.hbid";

$query = $this->db->query($sql);//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        $result = $query->result();
        $processedData = [
            'rent' => 0,
            'totalamount' => 0,
            'paid_amount' => 0,
             'additional_charges' =>0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ];

        foreach ($result as $row) {
            $processedData['rent'] += $row->rent;
            $processedData['totalamount'] += $row->totalamount;
            $processedData['paid_amount'] += $row->paid_amount;
             $processedData['additional_charges']    += $row-> additional_charges;
            $processedData['special_discount'] += $row->special_discount;
            $processedData['cgst'] += $row->cgst;
            $processedData['sgst'] += $row->sgst;
        }

        return $processedData;
    } else {
        return [
            'rent' => 0,
            'totalamount' => 0,
            'paid_amount' => 0,
             'additional_charges' =>0,
            'special_discount' => 0,
            'cgst' => 0,
            'sgst' => 0,
        ]; // Return empty array if no data found
    }
}


// public function nightaudit_room_status_checkin() {
    
//   $today_date = date('d-m-Y');
//     $start_datetime = $today_date . ' 00:00:00';
//     $end_datetime = $today_date . ' 23:59:59';

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkindate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'4');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
// public function nightaudit_room_status_checkin_month() {
//   $current_month = date('m-Y'); // Get current month in 'YYYY-MM' format
//     $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
//     $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkindate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'4');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
// public function nightaudit_room_status_checkin_year() {
    
//      $current_year = date('Y'); // Get current year in 'YYYY' format
//     $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
//     $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkindate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'4');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
// public function nightaudit_room_status_checkout() {
    
//     $today_date = date('d-m-Y');
//     $start_datetime = $today_date . ' 00:00:00';
//     $end_datetime = $today_date . ' 23:59:59';

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'5');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
// public function nightaudit_room_status_checkout_month() {
    
//     $current_month = date('m-Y'); // Get current month in 'YYYY-MM' format
//     $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
//     $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'5');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
// public function nightaudit_room_status_checkout_year() {
    
//      $current_year = date('Y'); // Get current year in 'YYYY' format
//     $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
//     $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

//     $this->db->select("bookingstatus, COUNT(*) as count");
//     $this->db->from('booked_info');
//     $this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
//      $this->db->where("bookingstatus",'5');
//     $this->db->group_by('bookingstatus');

//     $query = $this->db->get();
//     $result = $query->result();
    
    
// }
public function nightaudit_room_status() {
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    $this->db->select("bookingstatus, COUNT(*) as count");
    $this->db->from('booked_info');
    $this->db->where("date_time BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->group_by('bookingstatus');

    $query = $this->db->get();
    $result = $query->result();

    // Initialize counts to 0
    $counts = array(
        'checkin' => 0,
        'checkout' => 0,
        'booked' => 0,
        'canceled' => 0,
        'finish' => 0,
    );

    // Update counts based on result
    foreach ($result as $status) {
        switch ($status->bookingstatus) {
            case 4: // checkin
                $counts['checkin'] = $status->count;
                break;
            case 5: // checkout
                $counts['checkout'] = $status->count;
                break;
            case 0: // booked
                $counts['booked'] = $status->count;
                break;
            case 1: // canceled
                $counts['canceled'] = $status->count;
                break;
            case 3: // finish
                $counts['finish'] = $status->count;
                break;
        }
    }

    return $counts;
}

public function nightaudit_current_month_room_status() {
 $current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

    $this->db->select("bookingstatus, COUNT(*) as count");
    $this->db->from('booked_info');
    $this->db->where('STR_TO_DATE(date_time, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE);
    $this->db->group_by('bookingstatus');

    $query = $this->db->get();
    $result = $query->result();

    // Initialize counts to 0
    $counts = array(
        'checkin' => 0,
        'checkout' => 0,
        'booked' => 0,
        'canceled' => 0,
        'finish' => 0,
    );

    // Update counts based on result
    foreach ($result as $status) {
        switch ($status->bookingstatus) {
            case 4: // checkin
                $counts['checkin'] = $status->count;
                break;
            case 5: // checkout
                $counts['checkout'] = $status->count;
                break;
            case 0: // booked
                $counts['booked'] = $status->count;
                break;
            case 1: // canceled
                $counts['canceled'] = $status->count;
                break;
            case 3: // finish
                $counts['finish'] = $status->count;
                break;
        }
    }

    return $counts;
}

public function nightaudit_current_year_room_status() {
  $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year

 $this->db->select("bookingstatus, COUNT(*) as count");
    $this->db->from('booked_info');
    $this->db->where("date_time BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->group_by('bookingstatus');

    $query = $this->db->get();
    $result = $query->result();

    // Initialize counts to 0
    $counts = array(
        'checkin' => 0,
        'checkout' => 0,
        'booked' => 0,
        'canceled' => 0,
        'finish' => 0,
    );

    // Update counts based on result
    foreach ($result as $status) {
        switch ($status->bookingstatus) {
            case 4: // checkin
                $counts['checkin'] = $status->count;
                break;
            case 5: // checkout
                $counts['checkout'] = $status->count;
                break;
            case 0: // booked
                $counts['booked'] = $status->count;
                break;
            case 1: // canceled
                $counts['canceled'] = $status->count;
                break;
            case 3: // finish
                $counts['finish'] = $status->count;
                break;
        }
    }

    return $counts;
}
public function nightaudit_total_rooms(){
	
$this->db->select("count(*) as room_count"); 
	$this->db->from(' tbl_roomnofloorassign');
$query = $this->db->get();
return $query->result();
}
public function nightauditpostedbills(){
	$today_date = date('Y-m-d');
	$start_datetime = $today_date;
$end_datetime = $today_date ;
$this->db->select("SUM(tbl_postedbills.special_discount) as special_discount, SUM(tbl_postedbills.additional_charges) as additional_charges, SUM(tbl_postedbills.ex_discount) as ex_discount"); 
$this->db->from('tbl_postedbills');
$this->db->join('booked_info', 'tbl_postedbills.bookedid = booked_info.bookedid');
$this->db->where('STR_TO_DATE(booked_info.paid_paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE);
//$this->db->where("booked_info.paid_paydate BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();
//echo $this->db->last_query();die();
return $query->result();
}
public function nightaudit_current_month() {
$current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

// Fetch data from the database
$query = $this->db->select('a.*, b.*')
                  ->from('booked_info a')
                  ->join('booked_details b', 'a.bookedid = b.bookedid')
                  ->where('STR_TO_DATE(a.checkindate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE)
                  ->where('a.bookingstatus', 4)
                        ->or_where('a.bookingstatus', 5)
                  ->get();
    // Process the retrieved data
    $result = $query->result();
//echo $this->db->last_query();die();
    $processedData = [];

    foreach ($result as $row) {
        // Perform calculations or manipulations as needed
        $roomRateArray = explode(',', $row->roomrate);
        $totalRoomRate = array_sum($roomRateArray);

        // Add additional calculations or data manipulations here

        // Store the processed data
        $processedData[] = [
            'paid_amount' => $row->paid_amount,
            'total_room' => $row->total_room,
          
            'total_price' => $row->total_price,
            'discountamount' => $row->discountamount,
            'cgst' => $row->cgst,
            'sgst' => $row->sgst,
            'bc' => $row->bc,
            'pc' => $row->pc,
            'cc' => $row->cc,
            'roomrate' => $totalRoomRate, // Example additional calculation
        ];
    }

    // Return the processed data to the view
    return $processedData;
}
public function nightaudit_current_month_postedbills(){
$current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month
$this->db->select("SUM(tbl_postedbills.special_discount) as special_discount, SUM(tbl_postedbills.additional_charges) as additional_charges, SUM(tbl_postedbills.ex_discount) as ex_discount"); 
$this->db->from('tbl_postedbills');
$this->db->join('booked_info', 'tbl_postedbills.bookedid = booked_info.bookedid');
$this->db->where('STR_TO_DATE(booked_info.paid_paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE);


$query = $this->db->get();//echo $this->db->last_query();die();
$result = $query->row();

return $result;
}
public function nightaudit_current_year(){
   $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year
     
    // Fetch data from the database
    $query = $this->db->select('a.*, b.*')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                      ->where('a.checkindate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"')
                      ->where('a.bookingstatus', 4)
                      ->or_where('a.bookingstatus', 5)
                      ->get();

    // Process the retrieved data
    $result = $query->result();
  //  echo $this->db->last_query();
    $processedData = [];

    foreach ($result as $row) {
        // Perform calculations or manipulations as needed
        $roomRateArray = explode(',', $row->roomrate);
        $totalRoomRate = array_sum($roomRateArray);

        // Add additional calculations or data manipulations here

        // Store the processed data
        $processedData[] = [
            'paid_amount' => $row->paid_amount,
            'total_room' => $row->total_room,
          
            'total_price' => $row->total_price,
            'discountamount' => $row->discountamount,
            'cgst' => $row->cgst,
            'sgst' => $row->sgst,
            'bc' => $row->bc,
            'pc' => $row->pc,
            'cc' => $row->cc,
            'roomrate' => $totalRoomRate, // Example additional calculation
        ];
    }
//print_r($processedData);die();
    // Return the processed data to the view
    return $processedData;
}
public function nightaudit_current_year_postedbills(){
	$current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year


$this->db->select("SUM(tbl_postedbills.special_discount) as special_discount, SUM(tbl_postedbills.additional_charges) as additional_charges, SUM(tbl_postedbills.ex_discount) as ex_discount"); 
$this->db->from('tbl_postedbills');
$this->db->join('booked_info', 'tbl_postedbills.bookedid = booked_info.bookedid');
$this->db->where("booked_info.paid_paydate BETWEEN '$start_datetime' AND '$end_datetime'");

$query = $this->db->get();//echo $this->db->last_query();die();
$result = $query->row();

return $query->result();
}


public function nightaudit_restaurent(){
    $today_date = date('d-m-Y');
    $start_datetime = $today_date;
    $end_datetime = $today_date;
    
    $this->db->select("SUM(b.customerpaid) as customerpaid, SUM(a.discount) as discount, SUM(a.bill_amount) as bill_amount, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount"); 
    $this->db->from('bill a');
    $this->db->join('customer_order b', 'a.order_id = b.order_id');
    $this->db->where("a.bill_status", "1");
    $this->db->where("a.create_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
    $result = $query->row();
//echo $this->db->last_query();
    // Calculate tax amount
     $billAmountSum = ($result->total_amount-$result->discount);
     echo $billAmountSum."<br/>";
    $taxAmount = $billAmountSum * 0.05;

    // Prepare the data to return
    $data['tax_amount'] = $taxAmount;
    $data['customerpaid'] = $result->customerpaid;
     $data['total_amount'] = $result->total_amount;
    $data['discount'] = $result->discount;
    $data['bill_amount'] = $result->bill_amount;

    return $data;
}
public function nightaudit_restaurent_current_month(){


   $current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

$this->db->select("SUM(b.customerpaid) as customerpaid, SUM(a.discount) as discount, SUM(a.bill_amount) as bill_amount, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount"); 
$this->db->from('bill a');
$this->db->join('customer_order b', 'a.order_id = b.order_id');
$this->db->where("a.bill_status", "1");
$this->db->where("STR_TO_DATE(a.create_date, '%d-%m-%Y') BETWEEN '$start_datetime' AND '$end_datetime'", NULL, FALSE);
$query = $this->db->get();
    $result = $query->row();
//echo $this->db->last_query();die();
    // Calculate tax amount
    $billAmountSum = ($result->total_amount-$result->discount);
    $taxAmount = $billAmountSum * 0.05;

    // Prepare the data to return
    $data['tax_amount'] = $taxAmount;
    $data['customerpaid'] = $result->customerpaid;
       $data['total_amount'] = $result->total_amount;
    $data['discount'] = $result->discount;
    $data['bill_amount'] = $result->bill_amount;

    return $data;
}
public function nightaudit_restaurent_current_year(){
  $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year
    $this->db->select("SUM(b.customerpaid) as customerpaid, SUM(a.discount) as discount, SUM(a.bill_amount) as bill_amount, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount"); 
    $this->db->from('bill a');
    $this->db->join('customer_order b', 'a.order_id = b.order_id');
    $this->db->where("a.bill_status", "1");
    $this->db->where("a.create_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
    $result = $query->row();
//echo $this->db->last_query();
    // Calculate tax amount
    $billAmountSum = ($result->total_amount-$result->discount);
    $taxAmount = $billAmountSum * 0.05;

    // Prepare the data to return
    $data['tax_amount'] = $taxAmount;
    $data['customerpaid'] = $result->customerpaid;
       $data['total_amount'] = $result->total_amount;
    $data['discount'] = $result->discount;
    $data['bill_amount'] = $result->bill_amount;
// print_r($data);
// die();
    return $data;
}
public function nightaudit_payment_method_room(){
$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
	
$this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
$this->db->where("book_type","0");
$this->db->group_by("paymenttype");
$query = $this->db->get();
//echo $this->db->last_query();die();
return $query->result();
}

public function nightaudit_payment_method_room_month(){
$current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

$this->db->select("paymenttype, SUM(paymentamount) as paymentamount"); 
$this->db->from('tbl_guestpayments');
$this->db->where('STR_TO_DATE(paydate, "%d-%m-%Y") BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"', NULL, FALSE);
$this->db->where("book_type", "0");
$this->db->group_by("paymenttype");

$query = $this->db->get();

//	echo $this->db->last_query();die();
 return $query->result();
}

public function nightaudit_payment_method_room_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year

	$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->where("book_type","0");
    $this->db->group_by("paymenttype");

    $query = $this->db->get();
   // echo $this->db->last_query(); // Output the generated SQL query for debugging
   // die();

    return $query->result();
}
public function no_of_person(){
	$today_date = date('d-m-Y');
		$start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

$sql = "
    SELECT SUM(SUBSTRING_INDEX(SUBSTRING_INDEX(bi.nuofpeople, ',', numbers.n), ',', -1)) AS total_nuofpeople
    FROM booked_info AS bi
    JOIN (
        SELECT 1 + a.N + b.N * 10 AS n
        FROM 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
        CROSS JOIN 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
    ) AS numbers
    ON CHAR_LENGTH(bi.nuofpeople) - CHAR_LENGTH(REPLACE(bi.nuofpeople, ',', '')) >= numbers.n - 1
    JOIN tbl_guestpayments AS gp ON gp.bookedid = bi.bookedid
    WHERE  gp.paydate BETWEEN '$start_datetime' AND '$end_datetime';
";
$query = $this->db->query($sql);
if ($query === false) {
	echo $this->db->error(); // Output the error details for debugging
	die("Query failed!");
}

$result = $query->row();

if ($result !== null) {
	return $query->result();
} else {
	echo "No result found.";
}


}
public function no_of_person_month(){
	$current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
$start_datetime = $current_month . '-01 00:00:00'; // Start of the month
$end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59';

$sql = "
    SELECT SUM(SUBSTRING_INDEX(SUBSTRING_INDEX(nuofpeople, ',', numbers.n), ',', -1)) AS total_nuofpeople
    FROM booked_info
    JOIN (
        SELECT 1 + a.N + b.N * 10 AS n
        FROM 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
        CROSS JOIN 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
    ) numbers
    ON CHAR_LENGTH(nuofpeople) - CHAR_LENGTH(REPLACE(nuofpeople, ',', '')) >= numbers.n - 1
    WHERE checkoutdate BETWEEN '$start_datetime' AND '$end_datetime';
";

$query = $this->db->query($sql);

if ($query === false) {
    echo $this->db->error(); // Output the error details for debugging
    die("Query failed!");
}

$result = $query->row();

if ($result !== null) {
	return $query->result();
} else {
	echo "No result found.";
}
}
public function no_of_person_year(){
	$current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

$sql = "
    SELECT SUM(SUBSTRING_INDEX(SUBSTRING_INDEX(nuofpeople, ',', numbers.n), ',', -1)) AS total_nuofpeople
    FROM booked_info
    JOIN (
        SELECT 1 + a.N + b.N * 10 AS n
        FROM 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
        CROSS JOIN 
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
    ) numbers
    ON CHAR_LENGTH(nuofpeople) - CHAR_LENGTH(REPLACE(nuofpeople, ',', '')) >= numbers.n - 1
    WHERE checkoutdate BETWEEN '$start_datetime' AND '$end_datetime';
";

$query = $this->db->query($sql);

if ($query === false) {
    echo $this->db->error(); // Output the error details for debugging
    die("Query failed!");
}

$result = $query->row();

if ($result !== null) {
	return $query->result();
} else {
	echo "No result found.";
}
}
public function getroombills() {

	$today_date = date('d-m-Y');
		$start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
	$this->db->select('COUNT(DISTINCT bookedid) AS num_rows');
	$this->db->from('tbl_guestpayments');
	$this->db->where('book_type', 0);
	$this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
	$query = $this->db->get();
	$result = $query->row();
	
	if ($result) {
		$num_rows = $result->num_rows;
		echo $num_rows;
	} else {
		echo "No result found.";
	}
}
public function getBookingStatusToday() {
    
    $this->db->select("
        SUM(CASE WHEN bookingstatus = 4 THEN 1 ELSE 0 END) AS checkins,
        SUM(CASE WHEN bookingstatus = 3 THEN 1 ELSE 0 END) AS checkouts,
        SUM(CASE WHEN bookingstatus = 1 THEN 1 ELSE 0 END) AS canceled
    ");
    $this->db->from('booked_info');
    $this->db->where('DATE(checkindate)', date('d-m-Y'));
    $query = $this->db->get();
    // echo $this->db->last_query();die();
    return $query->row();
}
public function getOccupancyData() {
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    $this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
    $this->db->from('roomdetails rd');
    $this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
  //  $this->db->where("bi.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where("bi.checkindate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->group_by('rd.roomtype');

    $query = $this->db->get();
   
    return $query->result();
}
public function getOccupancyData_current_month() {
    $current_month = date('m'); // Get current month in 'm' format (numeric representation of month)
    $current_year = date('Y'); // Get current year in 'Y' format (4-digit representation of year)

    // Calculate start and end datetimes using the current month and year
    $start_datetime = '01-' . $current_month . '-' . $current_year . ' 00:00:00';
    $end_datetime = date('t', strtotime($current_year . '-' . $current_month)) . '-' . $current_month . '-' . $current_year . ' 23:59:59';

    $this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
    $this->db->from('roomdetails rd');
    $this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
    $this->db->where("STR_TO_DATE(bi.checkoutdate, '%d-%m-%Y') BETWEEN STR_TO_DATE('$start_datetime', '%d-%m-%Y %H:%i:%s') AND STR_TO_DATE('$end_datetime', '%d-%m-%Y %H:%i:%s')", NULL, FALSE);
     $this->db->where("STR_TO_DATE(bi.checkindate, '%d-%m-%Y') BETWEEN STR_TO_DATE('$start_datetime', '%d-%m-%Y %H:%i:%s') AND STR_TO_DATE('$end_datetime', '%d-%m-%Y %H:%i:%s')", NULL, FALSE);
    $this->db->group_by('rd.roomtype');

    $query = $this->db->get();
    return $query->result();
}

public function getOccupancyData_current_year() {
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = '01-01-' . $current_year . ' 00:00:00'; // Start of the year
    $end_datetime = '31-12-' . $current_year . ' 23:59:59'; // End of the year

    $this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
    $this->db->from('roomdetails rd');
    $this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
    $this->db->where("STR_TO_DATE(bi.checkoutdate, '%d-%m-%Y') BETWEEN STR_TO_DATE('$start_datetime', '%d-%m-%Y %H:%i:%s') AND STR_TO_DATE('$end_datetime', '%d-%m-%Y %H:%i:%s')", NULL, FALSE);
     $this->db->where("STR_TO_DATE(bi.checkindate, '%d-%m-%Y') BETWEEN STR_TO_DATE('$start_datetime', '%d-%m-%Y %H:%i:%s') AND STR_TO_DATE('$end_datetime', '%d-%m-%Y %H:%i:%s')", NULL, FALSE);
    $this->db->group_by('rd.roomtype');

    $query = $this->db->get();
    return $query->result();
}
public function nightaudit_payment_method_hall() {
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    $this->db->select("paymenttype, paymentamount"); 
    $this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where("book_type", "1");
    
    $query = $this->db->get();

    // Initialize arrays to store payment types and amounts
    $paymentTypes = [];
    $paymentAmounts = [];

    // Iterate through the query result to split payment types and amounts
    foreach ($query->result() as $row) {
        $types = explode(',', $row->paymenttype);
        $amounts = explode(',', $row->paymentamount);

        // Merge arrays
        $paymentTypes = array_merge($paymentTypes, $types);
        $paymentAmounts = array_merge($paymentAmounts, $amounts);
    }

    // Construct an associative array containing payment types and amounts
    $paymentData = [];
    for ($i = 0; $i < count($paymentTypes); $i++) {
        $paymentData[] = [
            'paymentType' => $paymentTypes[$i],
            'paymentAmount' => $paymentAmounts[$i]
        ];
    }

    return $paymentData;
}


public function nightaudit_payment_method_hall_month(){
$current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
$start_datetime = date('Y-m-01', strtotime($current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime($current_month)); // End of the month

$this->db->select("paymenttype, paymentamount"); 
$this->db->from('tbl_guestpayments');
$this->db->where("STR_TO_DATE(paydate, '%d-%m-%Y') BETWEEN '$start_datetime' AND '$end_datetime'");
$this->db->where("book_type", "1");

$query = $this->db->get();

    // Initialize arrays to store payment types and amounts
    $paymentTypes = [];
    $paymentAmounts = [];

    // Iterate through the query result to split payment types and amounts
    foreach ($query->result() as $row) {
        $types = explode(',', $row->paymenttype);
        $amounts = explode(',', $row->paymentamount);

        // Merge arrays
        $paymentTypes = array_merge($paymentTypes, $types);
        $paymentAmounts = array_merge($paymentAmounts, $amounts);
    }

    // Construct an associative array containing payment types and amounts
    $paymentData = [];
    for ($i = 0; $i < count($paymentTypes); $i++) {
        $paymentData[] = [
            'paymentType' => $paymentTypes[$i],
            'paymentAmount' => $paymentAmounts[$i]
        ];
    }

    return $paymentData;
}

public function nightaudit_payment_method_hall_year(){
   $current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year
     $this->db->select("paymenttype, paymentamount"); 
    $this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->where("book_type", "1");
    
    $query = $this->db->get();

    // Initialize arrays to store payment types and amounts
    $paymentTypes = [];
    $paymentAmounts = [];

    // Iterate through the query result to split payment types and amounts
    foreach ($query->result() as $row) {
        $types = explode(',', $row->paymenttype);
        $amounts = explode(',', $row->paymentamount);

        // Merge arrays
        $paymentTypes = array_merge($paymentTypes, $types);
        $paymentAmounts = array_merge($paymentAmounts, $amounts);
    }

    // Construct an associative array containing payment types and amounts
    $paymentData = [];
    for ($i = 0; $i < count($paymentTypes); $i++) {
        $paymentData[] = [
            'paymentType' => $paymentTypes[$i],
            'paymentAmount' => $paymentAmounts[$i]
        ];
    }

    return $paymentData;
}

public function nightaudit_payment_method_restaurant(){
 $today_date = date('d-m-Y');
    $start_datetime = $today_date ;
    $end_datetime = $today_date;

   $this->db->select('b.order_id, b.payment_type_id, b.amount, c.payment_method as payment_method');
    $this->db->from('bill a');
    $this->db->join('multipay_bill b', 'a.order_id = b.order_id');
    $this->db->join('payment_method c', 'b.payment_type_id = c.payment_method_id');
    $this->db->where('a.bill_status', '1');
    $this->db->where('a.create_date >=', $start_datetime);
    $this->db->where('a.create_date <=', $end_datetime);
    $this->db->group_by('b.order_id, b.payment_type_id, b.amount, c.payment_method');

    $query = $this->db->get();
  //  echo $this->db->last_query();die();
    $result = $query->result();

    return $result;
}
public function nightaudit_payment_method_restaurant_month(){
$current_month = date('m-Y'); // Get current month in 'MM-YYYY' format
$start_datetime = date('Y-m-01', strtotime('01-' . $current_month)); // Start of the month
$end_datetime = date('Y-m-t', strtotime('01-' . $current_month)); // End of the month

$query = $this->db->query("
    SELECT b.order_id, b.payment_type_id, b.amount, c.payment_method as payment_method
    FROM bill a
    JOIN multipay_bill b ON a.order_id = b.order_id
    JOIN payment_method c ON b.payment_type_id = c.payment_method_id
    WHERE a.bill_status = '1'
    AND STR_TO_DATE(a.create_date, '%d-%m-%Y') BETWEEN '$start_datetime' AND '$end_datetime'
    GROUP BY b.order_id, b.payment_type_id, b.amount, c.payment_method
");

return $query->result();
}

public function nightaudit_payment_method_restaurant_year(){
$current_year = date('Y'); // Get current year in 'YYYY' format
$start_datetime = '01-01-' . $current_year; // Start of the year
$end_datetime = '31-12-' . $current_year; // End of the year

   $this->db->select('b.order_id, b.payment_type_id, b.amount, c.payment_method as payment_method');
    $this->db->from('bill a');
    $this->db->join('multipay_bill b', 'a.order_id = b.order_id');
    $this->db->join('payment_method c', 'b.payment_type_id = c.payment_method_id');
    $this->db->where('a.bill_status', '1');
    $this->db->where('a.create_date >=', $start_datetime);
    $this->db->where('a.create_date <=', $end_datetime);
    $this->db->group_by('b.order_id, b.payment_type_id, b.amount, c.payment_method');

    $query = $this->db->get();
  //  echo $this->db->last_query();die();
    $result = $query->result();

    return $result;
}
	public function getstocklist()
	{
		
		$this->db->select("products.product_name,unit_of_measurement.uom_name,unit_of_measurement.uom_short_code,purchase_details.*,SUM(purchase_details.quantity) as qty,SUM(purchase_details.price) as sumprice");
		$this->db->from('purchase_details');
		$this->db->join('products','products.id = purchase_details.proid', 'left');
		$this->db->join('unit_of_measurement','unit_of_measurement.id = products.uom_id', 'Inner');
		$this->db->group_by('purchase_details.proid');
		$this->db->order_by('purchase_details.purchaseid','desc');
		$query = $this->db->get();
		return $query->result();
	
	}
	
	public function getcosummarylist($start_date = NULL, $to_date = NULL)
{
$this->db->select('booked_info.*, roomdetails.*,  booked_details.*, 
    booked_details.cgst as bcgst, booked_details.sgst as bsgst, 

    booked_info.nuofpeople,booked_details.extrabed,booked_details.extraperson,booked_details.extrachild'); // Add the nu_of_people field to the select statement
$this->db->from('booked_info');

$this->db->join('booked_details', 'booked_info.bookedid = booked_details.bookedid', 'left');
$this->db->join('roomdetails', 'booked_info.roomid = roomdetails.roomid', 'left');
    $this->db->where('booked_info.bookingstatus','5');
if ($start_date !== NULL) {
    	$start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(booked_info.date_time, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(booked_info.date_time, "%d-%m-%Y") <=', $to_date1);
     

}
$query = $this->db->get();
//echo $this->db->last_query();die();

if ($query->num_rows() > 0) {
    return $query->result();
}
return false;

}
	
	
	
	
	public function getadvanceinfo_hall($start_date = NULL, $to_date = NULL)
	{
	     $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    
   
		$this->db->select('tbl_guestpayments.*,SUM(tbl_guestpayments.paymentamount) as advance ');
		$this->db->from('tbl_guestpayments');
			$this->db->join('tbl_hallroom_booking', ' tbl_hallroom_booking.invoice_no = tbl_guestpayments.invoice');
	  $this->db->like('tbl_guestpayments.details', 'Advance', 'after'); 
	    $this->db->where('tbl_guestpayments.book_type','1');
	      $this->db->where('tbl_hallroom_booking.status != 5');
	if ($start_date) {
			$start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
		}
	if(empty($start_date)) {
	    	$this->db->where('tbl_guestpayments.paydate >=', $start_datetime);
	    		$this->db->where('tbl_guestpayments.paydate <=', $end_datetime);
	    
	}
		$query = $this->db->get();
	//	echo $this->db->last_query();die();
    if ($query === FALSE) {
    			$error = $this->db->error();
    			log_message('error', 'Database Error: ' . $error['message']);
    			return false;
    		}
    		if ($query->num_rows() > 0) {
    			return $query->result();
    		}
    		return false;
	}
		
	public function getadvanceinfo($start_date = NULL, $to_date = NULL)
	{
	       $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    

		$this->db->select('tbl_guestpayments.*,SUM(tbl_guestpayments.paymentamount) as advance ');
		$this->db->from('booked_details');
		$this->db->join('booked_info', 'booked_details.bookedid = booked_info.bookedid');
	    $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_info.bookedid');
	   $this->db->where('tbl_guestpayments.paymentamount = booked_details.advance_amount');
	   $this->db->where('tbl_guestpayments.book_type','0');
	    $this->db->where('booked_info.bookingstatus != 5');
	if ($start_date) {
		$start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
		}else{
		  	$this->db->where('tbl_guestpayments.paydate >=', $start_datetime);
	    		$this->db->where('tbl_guestpayments.paydate <=', $end_datetime);  
		    
		}
	
		$query = $this->db->get();
if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	

	public function getadvanceinfo_hall__adjust($start_date = NULL, $to_date = NULL)
	{
	     $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    
    
	
		$this->db->select('tbl_guestpayments.*,SUM(tbl_guestpayments.paymentamount) as advance ');
		$this->db->from('tbl_guestpayments');
			$this->db->join('tbl_hallroom_booking', ' tbl_hallroom_booking.invoice_no = tbl_guestpayments.invoice');
	  $this->db->like('tbl_guestpayments.details', 'Advance', 'after'); 
	    $this->db->where('tbl_guestpayments.book_type','1');
	      $this->db->where('tbl_hallroom_booking.status','5');
	if ($start_date) {
	      $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
		}
	if(empty($start_date)) {
	    	$this->db->where('tbl_guestpayments.paydate >=', $start_datetime);
	    		$this->db->where('tbl_guestpayments.paydate <=', $end_datetime);
	    
	}
		$query = $this->db->get();
	//	echo $this->db->last_query();die();
    if ($query === FALSE) {
    			$error = $this->db->error();
    			log_message('error', 'Database Error: ' . $error['message']);
    			return false;
    		}
    		if ($query->num_rows() > 0) {
    			return $query->result();
    		}
    		return false;
	}
		
	public function getadvanceinfo_adjust($start_date = NULL, $to_date = NULL)
	{
	       $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

		$this->db->select('tbl_guestpayments.*,SUM(tbl_guestpayments.paymentamount) as advance ');
		$this->db->from('booked_details');
		$this->db->join('booked_info', 'booked_details.bookedid = booked_info.bookedid');
	    $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_info.bookedid');
	   $this->db->where('tbl_guestpayments.paymentamount = booked_details.advance_amount');
	   $this->db->where('tbl_guestpayments.book_type','0');
	     $this->db->where('booked_info.bookingstatus','5');
	if ($start_date) {
	    
  $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
     
		}else{
		    
		    $this->db->where('tbl_guestpayments.paydate >=', $start_datetime);
	
			$this->db->where('tbl_guestpayments.paydate <=', $end_datetime);
		}
	
		$query = $this->db->get();
	//	echo $this->db->last_query();die();
if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}



 


    // Hall HSN
	public function halldatahsnlist($start_date = NULL, $to_date = NULL)
	{
	    	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
	    $this->db->select('tbl_guestpayments.*,SUM(tbl_hallroom_booking.totalamount) as h_amount,SUM(tbl_hallroom_booking.cgst) as h_cgst,SUM(tbl_hallroom_booking.sgst) as h_sgst');
	    $this->db->from('tbl_hallroom_booking');
	    $this->db->join('tbl_hallroom_info', 'tbl_hallroom_info.hid = tbl_hallroom_booking.hall_type');
	    $this->db->join('tbl_guestpayments', 'tbl_guestpayments.invoice = tbl_hallroom_booking.invoice_no');
	      $this->db->where('tbl_guestpayments.paymentamount >0');
	      if (empty($start_date)) {
      $this->db->where('tbl_guestpayments.paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
 
  }
	    if ($start_date !== NULL) {
	        $this->db->where("tbl_guestpayments.paydate >=", $start_date);
	    }
	    if ($to_date !== NULL) {
	        $this->db->where("tbl_guestpayments.paydate <=", $to_date);
	    }

	    $query = $this->db->get();

	   //  echo $this->db->last_query(); die();

	    if ($query->num_rows() > 0) {
	        return $query->row();    
	    }
	    
	    return false;
	}
	
  // Hall B2CSmall
    public function halldatab2csmalllist($start_date = NULL, $to_date = NULL)
{   
    // Get today's date and time for default values
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    // Format the provided dates
    $start_date = ($start_date) ? DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d') . ' 00:00:00' : '';
    $to_date = ($to_date) ? DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d') . ' 23:59:59' : '';

    // Select query with SUM() functions and subquery
    $this->db->select('SUM(tbl_hallroom_booking.totalamount) AS total_amount, 
                      SUM(tbl_hallroom_booking.cgst) AS total_cgst, 
                      SUM(tbl_hallroom_booking.sgst) AS total_sgst');
    $this->db->from('tbl_hallroom_booking');

    // Subquery for filtering based on guest payments and customer info
    $this->db->where('tbl_hallroom_booking.hbid IN (
        SELECT `tbl_hallroom_booking`.`hbid`
        FROM `tbl_hallroom_booking`
        JOIN `tbl_guestpayments` ON `tbl_guestpayments`.`bookedid` = `tbl_hallroom_booking`.`hbid`
        JOIN `customerinfo` ON `customerinfo`.`customerid` = `tbl_hallroom_booking`.`customerid`
        WHERE `tbl_guestpayments`.`book_type` = "1"
        AND `tbl_guestpayments`.`paymentamount` > 0
        AND `tbl_hallroom_booking`.`status` = "5"
        AND (`customerinfo`.`gst_no` IS NULL OR `customerinfo`.`gst_no` = "")' . 
        ($start_date ? 'AND STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y %H:%i:%s") >= "' . $start_date . '" AND STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y %H:%i:%s") <= "' . $to_date . '" ' : '') . '
        GROUP BY `tbl_hallroom_booking`.`hbid`
    )');

    // Execute the query
    $query = $this->db->get();

    // Output the executed SQL query for debugging
   

    // Return the results
    return $query->result();
}



	public function gettariffinfo($start_date = NULL, $to_date = NULL)
	{
 $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    if($start_date && $to_date){
                $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
 $query=  $this->db->select('a.*,b.*')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                       ->join('customerinfo','customerinfo.customerid=a.cutomerid')
   
                       ->where('(customerinfo.gst_no IS NULL OR customerinfo.gst_no = "")')
                      ->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y")   BETWEEN "'. $start_date1 .'" AND "'. $to_date1 .'"')
                      ->where('a.bookingstatus', 5)
                    ->get();
   
}else{
   $query= $this->db->select('a.*,b.*')
                      ->from('booked_info a')
                      ->join('booked_details b', 'a.bookedid = b.bookedid')
                       ->join('customerinfo','customerinfo.customerid=a.cutomerid')
   
                       ->where('(customerinfo.gst_no IS NULL OR customerinfo.gst_no = "")')
                     
                      ->where('a.bookingstatus', 5)
                  
     ->get();
    
    
}
// echo $this->db->last_query();die();
    $result = $query->result();
    $processedData = [];

      foreach ($result as $row) {
        // Perform calculations or manipulations as needed
        $roomRateArray = explode(',', $row->roomrate);
        $totalRoomRate = array_sum($roomRateArray);

        // Add additional calculations or data manipulations here

        // Store the processed data
        $processedData[] = [
            'paid_amount' => $row->paid_amount,
            'total_room' => $row->total_room,
         //   'adv'  => $row->advance_amount,
            'total_price' => $row->total_price,
            'discountamount' => $row->discountamount,
            'cgst' => $row->cgst,
            'sgst' => $row->sgst,
            'bc' => $row->bc,
            'pc' => $row->pc,
            'cc' => $row->cc,
            'roomrate' => $totalRoomRate, // Example additional calculation
        ];
    }
   // echo $gettariffinfo['roomrate'];
    // Return the processed data to the view
    //print_r($processedData);die();
    return $processedData;
//   echo $this->db->last_query();die();
//     if ($query->num_rows() > 0) {
//         return $query->row();    
//     }
    
//     return false;
	}
	
	


	
		public function getfoodinfo($start_date = NULL, $to_date = NULL)
	{
	    
	    $today_date = date('d-m-Y');
   // $start_datetime = $today_date ;
   // $end_datetime = $today_date;


    //die();

		$this->db->select('SUM(bill.bill_amount) as totalfood,SUM(bill.discount) as discount,SUM(bill.total_amount) as total_amount');
		$this->db->from('customer_order');
	     $this->db->join('bill', 'customer_order.order_id = bill.order_id');
	       $this->db->join('customerinfo','customerinfo.customerid=customer_order.customer_id');
    $this->db->where('customer_order.totalamount >= customer_order.customerpaid');
      $this->db->where('(customerinfo.gst_no IS NULL OR customerinfo.gst_no = "")');
    $this->db->where('bill.bill_status','1');
    
//     if (empty($start_date)) {
//           $this->db->where('bill.bill_date >=', $today_date);
//     $this->db->where('bill.bill_date <=', $today_date);
    
//     //  $this->db->where('bill.date BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
 
       
//   }
		if ($start_date) {
		             $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") <=', $to_date1);

	
		}
	

		$query = $this->db->get();
//	echo $this->db->last_query();die();
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
public function getbedinfo($start_date = NULL, $to_date = NULL)	
{
     $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

      if($start_date){
          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part

        
 
    $this->db->select('SUM(subquery.bc) as bc, SUM(subquery.pc) as pc, SUM(subquery.cc) as cc');
    $this->db->from('(SELECT DISTINCT bi.bc, bi.pc, bi.cc
                     FROM booked_info bi
                     INNER JOIN tbl_guestpayments tp ON bi.bookedid = tp.bookedid
                       INNER JOIN customerinfo ci ON bi.cutomerid = ci.customerid 
                     WHERE bi.total_price >= bi.paid_amount 
                     AND    STR_TO_DATE(tp.paydate, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
                    
                      AND (ci.gst_no IS NULL OR ci.gst_no = "")
                 ) AS subquery');
}else{
     $this->db->select('SUM(subquery.bc) as bc, SUM(subquery.pc) as pc, SUM(subquery.cc) as cc');
    $this->db->from('(SELECT DISTINCT bi.bc, bi.pc, bi.cc
                     FROM booked_info bi
                     INNER JOIN tbl_guestpayments tp ON bi.bookedid = tp.bookedid
                       INNER JOIN customerinfo ci ON bi.cutomerid = ci.customerid 
                     WHERE bi.total_price >= bi.paid_amount 
                  
                      AND (ci.gst_no IS NULL OR ci.gst_no = "")
                 ) AS subquery'); 
    
}
    $query = $this->db->get();
//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        return $query->row();    
    }

    return false;
}
public function getpersoninfo($start_date = NULL, $to_date = NULL)	
{   

    	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
    //SUM(booked_info.total_price) as amount
    $this->db->select('SUM(tbl_postedbills.pc) as amount');
    $this->db->from('booked_info');
    $this->db->join('tbl_postedbills', 'tbl_postedbills.bookedid = booked_info.bookedid');
      $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_info.bookedid');

         $this->db->where('booked_info.total_price >= booked_info.paid_amount');
  if (empty($start_date)) {
       $this->db->where('tbl_guestpayments.paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
 
  }
  if (!empty($start_date)) {
       $this->db->where('tbl_guestpayments.paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
         
      $this->db->where('booked_info.total_price >= booked_info.paid_amount');
      
  }
    if ($to_date) {
        $this->db->where("tbl_guestpayments.paydate <=", $to_date);
    }

    $query = $this->db->get();
//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        return $query->row();    
    }
    
    return false;
}
public function getkidinfo($start_date = NULL, $to_date = NULL)	
{
     	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
    //SUM(booked_info.total_price) as amount
    $this->db->select('SUM(tbl_postedbills.cc) as amount');
    $this->db->from('booked_info');
    $this->db->join('tbl_postedbills', 'tbl_postedbills.bookedid = booked_info.bookedid');
      $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_info.bookedid');
  $this->db->where('booked_info.total_price >= booked_info.paid_amount');
  if (empty($start_date)) {
       $this->db->where('tbl_guestpayments.paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
 
  }
  if (!empty($start_date)) {
       $this->db->where('tbl_guestpayments.paydate BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
         
      $this->db->where('booked_info.total_price >= booked_info.paid_amount');
      
  }
    if ($to_date) {
        $this->db->where("tbl_guestpayments.paydate <=", $to_date);
    }

    $query = $this->db->get();
//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        return $query->row();    
    }
    
    return false;
}
	








public function b2c_large_gettariffinfo($start_date = NULL, $to_date = NULL)
	{
	    
		//SUM(booked_info.total_price) as amount
    $this->db->select('tbl_guestpayments.*,SUM(booked_info.total_price) as t_amount,SUM(booked_details.cgst) as t_cgst,SUM(booked_details.sgst) as t_sgst');
    $this->db->from('booked_details');
    $this->db->join('booked_info', 'booked_info.bookedid = booked_details.bookedid');
	  $this->db->where('booked_info.total_price >=', '250000');
     $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_details.bookedid');
   
    if ($start_date) {
        $this->db->where("tbl_guestpayments.paydate >=", $start_date);
           $this->db->where("tbl_guestpayments.paydate <=", $to_date);
    }else{
   
     
    }

    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row();    
    }
    
    return false;
	}
	
	public function b2c_large_getfoodinfo($start_date = NULL, $to_date = NULL)
	{
		
		$this->db->select('SUM(totalamount) as totalfood, SUM(cgst) as food_cgst, SUM(sgst) as food_sgst');
		$this->db->from('customer_order');
	     $this->db->join('bill', 'customer_order.order_id = bill.order_id');
    $this->db->where('customer_order.totalamount >=', '250000');
		if ($start_date !== NULL) {
			$this->db->where("bill.create_date >=", $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where("bill.create_date <=", $to_date);
		}

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
	
	
	// Hall B2C
    public function halldatab2clist($start_date = NULL, $to_date = NULL)
	{   
		$this->db->select('tbl_guestpayments.*,SUM(tbl_hallroom_booking.totalamount) as amount,SUM(tbl_hallroom_booking.cgst) as cgst,SUM(tbl_hallroom_booking.sgst) as sgst');
		$this->db->from('tbl_hallroom_booking');
 		$this->db->join('tbl_guestpayments', 'tbl_guestpayments.invoice = tbl_hallroom_booking.invoice_no');
	    $this->db->where('tbl_hallroom_booking.totalamount >=', '250000');
	    if ($start_date !== NULL) {
	        $this->db->where("tbl_guestpayments.paydate >=", $start_date);
	    }
	    if ($to_date !== NULL) {
	        $this->db->where("tbl_guestpayments.paydate <=", $to_date);
	    }

	    $query = $this->db->get();

	    // echo $this->db->last_query(); die();

	    if ($query->num_rows() > 0) {
	        return $query->row();    
	    }
	    
	    return false;
       
	}
	
	
    public function b2c_large_getbedinfo($start_date = NULL, $to_date = NULL)	
    {
        
        //SUM(booked_info.total_price) as amount
        $this->db->select('tbl_guestpayments.*,SUM(booked_info.total_price) as amount,SUM(booked_details.cgst) as cgst,SUM(booked_details.sgst) as sgst');
        $this->db->from('booked_details');
        $this->db->join('booked_info', 'booked_info.bookedid = booked_details.bookedid');
         $this->db->join('tbl_guestpayments', 'tbl_guestpayments.bookedid = booked_details.bookedid');
        // Adding the condition to filter out extrabed values with only zeros
        $this->db->where("booked_details.extrabed REGEXP '[1-9]'");
           $this->db->where('booked_info.total_price >=', '250000');
        if ($start_date !== NULL) {
            $this->db->where("tbl_guestpayments.paydate >=", $start_date);
        }
        if ($to_date !== NULL) {
            $this->db->where("tbl_guestpayments.paydate <=", $to_date);
        }
    
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            return $query->row();    
        }
        
        return false;
    }
	










	public function userinfo()
	{
		$this->db->select('firstname, lastname');
		$this->db->from('user');
		$query = $this->db->get();
	
		// Check for query execution errors
		if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		// Check for results
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
	
	
	public function halldatab2blist($start_date = NULL, $to_date = NULL)
	{   
		$this->db->select('tbl_hallroom_info.*,customerinfo.*,tbl_hallroom_booking.*');
		$this->db->from('tbl_hallroom_info');
 		$this->db->join('tbl_hallroom_booking', 'tbl_hallroom_booking.hall_type = tbl_hallroom_info.hid', 'left');
		$this->db->join('customerinfo', 'customerinfo.customerid = tbl_hallroom_booking.customerid', 'left');
	
        $this->db->where('customerinfo.gst_no IS NOT NULL', NULL, FALSE);
        $this->db->where('customerinfo.gst_no !=', '');
		if ($start_date !== NULL) {
			$this->db->where('tbl_hallroom_booking.start_date >=', $start_date);
	
			$this->db->where('tbl_hallroom_booking.end_date <=', $to_date);
		}
		$query = $this->db->get();
		// echo $this->db->last_query(); die();
	    log_message('debug', 'Query: ' . $this->db->last_query());
	    if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	 
	 public function hall_report($start_date = NULL, $to_date = NULL){
	$this->db->select('tbl_hallroom_info.*,customerinfo.*,tbl_hallroom_booking.*');
		$this->db->from('tbl_hallroom_booking');
 		$this->db->join('tbl_hallroom_info', 'tbl_hallroom_booking.hall_type = tbl_hallroom_info.hid', 'left');
		$this->db->join('customerinfo', 'customerinfo.customerid = tbl_hallroom_booking.customerid', 'left');
	
       
		if ($start_date !== NULL) {
			$this->db->where('tbl_hallroom_booking.start_date >=', $start_date);
	
			$this->db->where('tbl_hallroom_booking.end_date <=', $to_date);
		}
		$query = $this->db->get();
	//	 echo $this->db->last_query(); die();
	    log_message('debug', 'Query: ' . $this->db->last_query());
	    if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;

     }


    public function getbusinessinfo($start_date = NULL, $to_date = NULL)
	{
	    	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';

		$this->db->select('booked_info.*,customerinfo.*,booked_details.*');
		$this->db->from('booked_info');
 		$this->db->join('booked_details', 'booked_info.bookedid = booked_details.bookedid', 'left');
		$this->db->join('customerinfo', 'customerinfo.customerid = booked_info.cutomerid', 'left');
	
             $this->db->where('customerinfo.gst_no IS NOT NULL', NULL, FALSE);
    $this->db->where('customerinfo.gst_no !=', '');
	    if (!empty($start_date) && $start_date !== '') {
		          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(booked_info.paid_paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(booked_info.paid_paydate, "%d-%m-%Y") <=', $to_date1);



		}
// 		else{
		    
// 			$this->db->where('booked_info.paid_paydate >=', $start_datetime);
	
// 			$this->db->where('booked_info.paid_paydate <=', $end_datetime);
		    
// 		}
		$query = $this->db->get();
	 log_message('debug', 'Query: ' . $this->db->last_query());
	if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	 
	 public function getbusinessinfo_food($start_date = NULL, $to_date = NULL)
	{ //echo $start_date;die();
	    	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
		$this->db->select('customer_order.*,customerinfo.*');
		$this->db->from('customer_order');
 		$this->db->join('customerinfo', 'customer_order.customer_id = customerinfo.customerid', 'left');
			$this->db->join('bill', 'customer_order.order_id = bill.order_id', 'left');
	
             $this->db->where('customerinfo.gst_no IS NOT NULL', NULL, FALSE);
    $this->db->where('customerinfo.gst_no !=', '');
    if (!empty($start_date) && $start_date !== '') {
		          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") <=', $to_date1);
     
    	}
//     	else{
// 			$this->db->where('bill.bill_date >=', $start_datetime);
		
	
// 			$this->db->where('bill.bill_date <=', $end_datetime);
// 		}
		$query = $this->db->get();
		//echo $this->db->last_query();die();
	 log_message('debug', 'Query: ' . $this->db->last_query());
	if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}
	 
	public function getbusinessinfo_hall($start_date = NULL, $to_date = NULL)
{
    $this->db->select('tbl_hallroom_booking.*, customerinfo.*, tbl_guestpayments.*');
    $this->db->from('tbl_hallroom_booking');
    $this->db->join('customerinfo', 'tbl_hallroom_booking.customerid = customerinfo.customerid', 'left');

    $this->db->where('customerinfo.gst_no IS NOT NULL', NULL, FALSE);
    $this->db->where('customerinfo.gst_no !=', '');

    if ($start_date !== NULL || $to_date !== NULL) {
        $this->db->where('EXISTS (SELECT 1 FROM tbl_guestpayments WHERE tbl_guestpayments.customerid = tbl_hallroom_booking.customerid AND tbl_guestpayments.book_type = 1)', NULL, FALSE);
    }

    if ($start_date !== NULL) {
        $this->db->where('tbl_guestpayments.paydate >=', $start_date);
    }

    if ($to_date !== NULL) {
        $this->db->where('tbl_guestpayments.paydate <=', $to_date);
    }

    $query = $this->db->get();
  //  echo $this->db->last_query(); die();
    log_message('debug', 'Query: ' . $this->db->last_query());

    if ($query === FALSE) {
        $error = $this->db->error();
        log_message('error', 'Database Error: ' . $error['message']);
        return false;
    }

    if ($query->num_rows() > 0) {
        return $query->result();
    }

    return false;
}
	 
public function getsettlementsummaryinfo($start_date = NULL, $to_date = NULL)
{
    
 //   $this->db->distinct();
    $this->db->select('bill.bill_date as bill_dat,multipay_bill.amount as multiamt,bill.total_amount as bt, bill.discount as dicount, GROUP_CONCAT(payment_method.payment_method) as pay_type, customer_order.*, CONCAT_WS(" ", customerinfo.firstname, customerinfo.lastname) as customer_name');
    $this->db->from('customer_order');
    $this->db->join('customerinfo', 'customer_order.customer_id = customerinfo.customerid', 'left');
    $this->db->join('bill', 'bill.order_id = customer_order.order_id', 'left');
    $this->db->join('multipay_bill', 'customer_order.order_id = multipay_bill.order_id', 'left');
    $this->db->join('payment_method', 'multipay_bill.payment_type_id = payment_method.payment_method_id', 'left');
    $this->db->order_by('customer_order.order_id', 'desc');


  if (!empty($start_date) && $start_date !== '') {
		          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part

     
              $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") <=', $to_date1);
     
  }

    $this->db->group_by('customer_order.order_id,multipay_bill.amount,payment_method.payment_method'); // Group by order_id to handle duplicates

    $query = $this->db->get();
  //  echo $this->db->last_query(); die();
    log_message('debug', 'Query: ' . $this->db->last_query());

    // Check for query execution errors
    if ($query === FALSE) {
        $error = $this->db->error();
        log_message('error', 'Database Error: ' . $error['message']);
        return false;
    }

    // Check for results
    if ($query->num_rows() > 0) {
        return $query->result();
    }

    return false;
}

	
		public function getsettlementsummaryinfo_room($start_date = NULL, $to_date = NULL)
	{
	
		$this->db->select('tbl_guestpayments.paydate as paydate,booked_details.cgst as cgst,booked_details.sgst as sgst,tbl_guestpayments.*,booked_info.*,CONCAT_WS(" ", customerinfo.firstname,customerinfo.lastname) as customer_name');
		$this->db->from('booked_info');
		$this->db->join('customerinfo','customerinfo.customerid=booked_info.cutomerid','left');
		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
        $this->db->join('tbl_guestpayments','tbl_guestpayments.bookedid=booked_details.bookedid','left');
	    $this->db->order_by('booked_info.booking_number','desc');
	    
	      if (!empty($start_date) && $start_date !== '') {
		          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part

             $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
     
  }
  
  	$this->db->where('tbl_guestpayments.book_type', '0');
		$query = $this->db->get();
	 //  echo $this->db->last_query(); die();
		log_message('debug', 'Query: ' . $this->db->last_query());
		// Check for query execution errors
		if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		// Check for results
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return false;
	}
	
		public function getsettlementsummaryinfo_hall($start_date = NULL, $to_date = NULL)
	{
	
		$this->db->select('tbl_guestpayments.paydate as paydate,tbl_guestpayments.*,tbl_hallroom_booking.invoice_no as inv,tbl_hallroom_info.hall_type as hall_name,tbl_hallroom_booking.*,CONCAT_WS(" ", customerinfo.firstname,customerinfo.lastname) as customer_name');
		$this->db->from('tbl_hallroom_booking');
		$this->db->join('customerinfo','customerinfo.customerid=tbl_hallroom_booking.customerid','left');
		$this->db->join('tbl_hallroom_info','tbl_hallroom_info.hid=tbl_hallroom_booking.hall_type','left');
        $this->db->join('tbl_guestpayments','tbl_guestpayments.bookedid=tbl_hallroom_booking.hbid','left');
	    $this->db->order_by('tbl_hallroom_booking.invoice_no','desc');
	    
	     if (!empty($start_date) && $start_date !== '') {
		          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part

     
        $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y") <=', $to_date1);
     
  }

	$this->db->where('tbl_guestpayments.book_type', '1');
		$query = $this->db->get();
	 //   echo $this->db->last_query(); die();
		log_message('debug', 'Query: ' . $this->db->last_query());
		// Check for query execution errors
		if ($query === FALSE) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false;
		}
		// Check for results
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	
		return false;
	}
	
	
	
	
	
	
// 		public function getsettlementsummaryinfo($start_date = NULL, $to_date = NULL)
// 	{
	
// 		$this->db->select('payment_method.payment_method as pay_type,order_menu.price as price ,order_menu.menuqty as  qty, item_foods.*,order_menu.*,customer_order.*,CONCAT_WS(" ", customerinfo.firstname,customerinfo.lastname) as customer_name,bill.bill_status,customer_order.food_need_date as food_date,item_foods.ProductName as item,customer_order.totalamount as totalamount,item_category.Name as category,order_menu.order_id as order_id');
// 		$this->db->from('customer_order');
// 		$this->db->join('customerinfo','customer_order.customer_id=customerinfo.customerid','left');
// 		$this->db->join('order_menu','customer_order.order_id=order_menu.order_id');
// 		$this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID');
// 		$this->db->join('item_category','item_category.CategoryID=item_foods.CategoryID');
// 		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
// 		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
// 		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
// 		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
// 		$this->db->join('multipay_bill','customer_order.order_id=multipay_bill.order_id','left');
// 			$this->db->join('payment_method','multipay_bill.payment_type_id=payment_method.payment_method_id','left');
// 	$this->db->order_by('order_menu.order_id','desc');
	
// 		if ($start_date !== NULL) {
// 			$this->db->where('customer_order.order_date >=', $start_date);
// 		}
// 		if ($to_date !== NULL) {
// 			$this->db->where('customer_order.order_date <=', $to_date);
// 		}
	
// 		$query = $this->db->get();
// 	   // echo $this->db->last_query(); die();
// 		log_message('debug', 'Query: ' . $this->db->last_query());
// 		// Check for query execution errors
// 		if ($query === FALSE) {
// 			$error = $this->db->error();
// 			log_message('error', 'Database Error: ' . $error['message']);
// 			return false;
// 		}
// 		// Check for results
// 		if ($query->num_rows() > 0) {
// 			return $query->result();
// 		}
	
// 		return false;
// 	}
	
	
	
	
	
	
	
	
	
	public function details($id)
	{
		
		$this->db->select('booked_details.*,booked_info.*,GROUP_CONCAT(roomdetails.roomtype ORDER BY booked_info.roomid,roomdetails.roomtype) as roomtype,roomdetails.rate');
        $this->db->from('booked_info');
		$this->db->join('tbl_roomnofloorassign','FIND_IN_SET(tbl_roomnofloorassign.roomno,booked_info.room_no)<>0','left');
		$this->db->join('roomdetails','FIND_IN_SET(roomdetails.roomid,tbl_roomnofloorassign.roomid)<>0','left');
			$this->db->join('booked_details','booked_info.bookedid=booked_details.bookedid','left');
		$this->db->where('booked_info.bookedid',$id);
		$query = $this->db->get();
        if ($query->num_rows() > 0) {
             return $query->row();    
        }
        return false;
	
	}
	
	public function customerinfo($cid){
			$this->db->select('*');
			$this->db->from('customerinfo');
			$this->db->where('customerid',$cid);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function storeinfo(){
			$this->db->select('*');
			$this->db->from('setting');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function taxinfo(){
			$this->db->select('*');
			$this->db->from('tbl_taxmgt');
			$this->db->where('isactive',1);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();    
			}
			return false;
		}
	public function btaxinfo($id){
			$this->db->select('*');
			$this->db->from('tbl_postedbills');
			$this->db->where('bookedid',$id);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function commoninfo(){
			$this->db->select('*');
			$this->db->from('common_setting');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
	public function paymentinfo($bookno){
			$this->db->select('tbl_guestpayments.*,payment_method.payment_method,booked_info.paid_amount');
			$this->db->from('tbl_guestpayments');
			$this->db->join('payment_method','payment_method.payment_method_id=tbl_guestpayments.paymenttype','left');
			$this->db->join('booked_info','booked_info.bookedid=tbl_guestpayments.bookedid','left');
			$this->db->where('tbl_guestpayments.bookedid',$bookno);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		}
		
	public function pruchasereport($start_date,$end_date)
	{
		$dateRange = "a.purchasedate BETWEEN '$start_date%' AND '$end_date%'";
		$this->db->select("a.*,b.supid,b.supName");
		$this->db->from('purchaseitem a');
		$this->db->join('supplier b','b.supid = a.suplierID');
		$this->db->where($dateRange, NULL, FALSE); 	
		$this->db->order_by('a.purchasedate','desc');
		$query = $this->db->get();
		return $query->result();
	} 
	public function settinginfo()
	{ 
		return $this->db->select("*")->from('setting')
			->get()
			->row();
	}
	public function currencysetting($id = null)
	{ 
		return $this->db->select("*")->from('currency')
			->where('currencyid',$id) 
			->get()
			->row();
	}
	
// 	public function read($limit = null, $start = null)
// 	{
// 	    $this->db->select('booked_info.*, booked_details.*');
//         $this->db->from('booked_info');
//         $this->db->join('booked_details', 'booked_details.bookedid = booked_info.bookedid', 'left');
//         $this->db->where('booked_info.bookingstatus', 5);
//         $this->db->or_where('booked_details.advance_amount > 0');
//         $this->db->order_by('booked_info.bookedid', 'DESC');
//         $this->db->limit($limit, $start);
//         $query = $this->db->get();
//         return $query->result();
// 	}
	
	
	
	
public function read($limit = null, $start = null)
	{
	    $this->db->select('booked_info.*,booked_details.*');
        $this->db->from('booked_info');
		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

// echo $this->db->last_query(); die();
 
		$scharge = $this->settinginfo();
		$charge = $scharge->servicecharge;
        if ($query->num_rows() > 0) {
            $result = $query->result();    

			foreach($result as $k => $r){
				$start = strtotime($r->checkindate);
				$end = strtotime($r->checkoutdate);
				$datediff = $end - $start;
				$days = ceil($datediff / (60 * 60 * 24));
				$result[$k]->roomtype = $this->room_type($r->roomid);
				if($r->booked_from==1 & $r->bookingstatus==0){
					$result[$k]->total_price = $r->total_price;
				}else{
					$roomId = explode(",",$r->roomid);
					$rent = explode(",",$r->roomrate);
					$offer_discount = explode(",",$r->offer_discount);
					$totalrent=0;
					$totaloffer=0;
					for($i=0;$i<count($rent); $i++){
						$totalrent += $rent[$i] - $offer_discount[$i];
						$totaloffer += $offer_discount[$i];
					}
					$promocode = 0;
					if(!empty($r->promocode)){
						$pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $r->promocode)->get()->row();
						$promocode = $pdiscount->discount;
					}
					if($r->bookingstatus!=5){
						$result[$k]->total_price = $r->total_price + $promocode + ((($totalrent*$charge)/100));
						$result[$k]->paid_amount = $r->paid_amount;
					}
					if($r->bookingstatus==5){
						$creditamt = $this->db->select("rate,credit,complementary,extrabpc,additional_charges,additional_charges,ex_discount,swimming_pool,restaurant,hallroom,car_parking,special_discount, scharge")->from("tbl_postedbills")->where("bookedid",$r->bookedid)->get()->row();
						$result[$k]->creditamt = $creditamt->credit;
						$result[$k]->total_price = $r->total_price + $promocode + $creditamt->scharge + $creditamt->additional_charges  + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking;
						$result[$k]->paid_amount = $r->paid_amount + ((($totalrent*$charge)/100)) + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->discountamount - $creditamt->special_discount;
						if($totaloffer>0){
							$datediff = strtotime($r->checkoutdate) - strtotime($r->checkindate);
							$datediff = ceil($datediff/(60*60*24));
							$singletax = explode(",", $creditamt->rate);
							$total=0;
							for($li = 0; $li < count($rent); $li++){
								for($in = 0; $in < $datediff; $in++){
									$alldays= date("d-m-Y", strtotime($r->checkindate . ' + ' . $in . 'day'));
									$getroom=$this->db->select("*")->from('tbl_room_offer')->where('roomid',$roomId[$li])->where('offer_date',$alldays)->get()->row();
									if(!empty($getroom)){
										$singleDiscount=$getroom->offer;
										$roomrate=$rent[$li]-$singleDiscount;
										}
									else{
										$roomrate=$rent[$li];
										}
									$price=$roomrate;
									$total=$total+$price;
								}
							}
							$toaltax=0;
							for($j=0; $j<count($singletax); $j++){
								$toaltax += ($total*$singletax[$j])/100;
							}
							$result[$k]->total_price =$creditamt->complementary + $creditamt->extrabpc + $total + $toaltax + $promocode + $creditamt->scharge + $creditamt->additional_charges  + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking ;
						}
					}
				}
			}
		//	print_r($result);die();
			return $result;
        }
        return false;
	}
	
	
	
	function room_type($rtype){
		$sroomtype = explode(",", $rtype);
		$type = "";
		for($i=0; $i<count($sroomtype); $i++){
			$row = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$sroomtype[$i])->get()->row();
			$type .= $row->roomtype.",";
		}
		return trim($type,",");
	} 
	
	public function customerlist()
	{
		$data = $this->db->select("customerid,firstname,lastname,cust_phone")
			->from('customerinfo')
			->get()
			->result();

		$list[''] = 'Select Customer';

		if (!empty($data)) {
			foreach($data as $value)
				$list[$value->customerid] = $value->firstname.' '.$value->lastname.'-'.$value->cust_phone;
			return $list;
		} else {
			return $list; 
		}
	}
public function gettariffinfo_hsn($start_date = NULL, $to_date = NULL)
{
    $today_date = date('Y-m-d');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    if ($start_date && $to_date) {
        $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d') . ' 00:00:00';
        $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d') . ' 23:59:59';

        $this->db->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y") BETWEEN "' . $start_date1 . '" AND "' . $to_date1 . '"');
     }
    //else {
    //     $this->db->where('STR_TO_DATE(a.paid_paydate, "%d-%m-%Y") BETWEEN "' . $start_datetime . '" AND "' . $end_datetime . '"');
    // }

    $this->db->select('a.*, b.*')
             ->from('booked_info a')
             ->join('booked_details b', 'a.bookedid = b.bookedid')
             ->where('a.bookingstatus', 5);

    $query = $this->db->get();
    $result = $query->result();
    $processedData = [];
$add=[];

 foreach ($result as $row) {
        // Perform calculations or manipulations as needed
        $roomRateArray = explode(',', $row->roomrate);
        $totalRoomRate = array_sum($roomRateArray);

        // Add additional calculations or data manipulations here

        // Store the processed data
        $processedData[] = [
            'paid_amount' => $row->paid_amount,
            'total_room' => $row->total_room,
         //   'adv'  => $row->advance_amount,
            'total_price' => $row->total_price,
            'discountamount' => $row->discountamount,
            'cgst' => $row->cgst,
            'sgst' => $row->sgst,
            'bc' => $row->bc,
            'pc' => $row->pc,
            'cc' => $row->cc,
            'roomrate' => $totalRoomRate, // Example additional calculation
        ];
    }
    foreach ($result as $row) {
        $additionalChargesQuery = $this->db->select_sum('additional_charges')
                                           ->where('bookedid', $row->bookedid)
                                           ->get('tbl_postedbills');

        $additionalChargesRow = $additionalChargesQuery->row();
        $additionalCharges = $additionalChargesRow ? $additionalChargesRow->additional_charges : 0;
//echo $additionalCharges;echo "<br/>";

 $add[] = $additionalChargesRow->additional_charges;
   $additionalChargesSum = array_sum($add);
   

    }
  $processedData[] = [
    'additional_charge' => $additionalChargesSum,
];


           // print_r($processedData); die();
    return $processedData;
}


	
	public function getfoodinfo_hsn($start_date = NULL, $to_date = NULL)
	{
	    
	    $today_date = date('d-m-Y');
   // $start_datetime = $today_date ;
   // $end_datetime = $today_date;


    //die();

		$this->db->select('SUM(bill.bill_amount) as totalfood,SUM(bill.discount) as discount,SUM(bill.total_amount) as total_amount');
		$this->db->from('customer_order');
	     $this->db->join('bill', 'customer_order.order_id = bill.order_id');
	     
    $this->db->where('customer_order.totalamount >= customer_order.customerpaid');
    
    $this->db->where('bill.bill_status','1');
    
//     if (empty($start_date)) {
//           $this->db->where('bill.bill_date >=', $today_date);
//     $this->db->where('bill.bill_date <=', $today_date);
    
//     //  $this->db->where('bill.date BETWEEN "'. $start_datetime .'" AND "'. $end_datetime .'"');
 
       
//   }
		if ($start_date) {
		             $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part
		     //  AND    STR_TO_DATE(bill.bill_date, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
          $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") >=', $start_date1);
     $this->db->where('STR_TO_DATE(bill.bill_date, "%d-%m-%Y") <=', $to_date1);

	
		}
	

		$query = $this->db->get();
//	echo $this->db->last_query();die();
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
public function getbedinfo_hsn($start_date = NULL, $to_date = NULL)	
{
     $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

      if($start_date){
          $start_date1 = DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d');
$start_date1 .= ' 00:00:00'; // Appending the time part
  $to_date1 = DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
$to_date1 .= ' 23:59:59'; // Appending the time part

        
 
    $this->db->select('SUM(subquery.bc) as bc, SUM(subquery.pc) as pc, SUM(subquery.cc) as cc');
    $this->db->from('(SELECT DISTINCT bi.bc, bi.pc, bi.cc
                     FROM booked_info bi
                     INNER JOIN tbl_guestpayments tp ON bi.bookedid = tp.bookedid
                     
                     WHERE bi.total_price >= bi.paid_amount 
                     AND    STR_TO_DATE(tp.paydate, "%d-%m-%Y") BETWEEN "'.$start_date1.'" AND "'.$to_date1.'"
                    
                 ) AS subquery');
}else{
     $this->db->select('SUM(subquery.bc) as bc, SUM(subquery.pc) as pc, SUM(subquery.cc) as cc');
    $this->db->from('(SELECT DISTINCT bi.bc, bi.pc, bi.cc
                     FROM booked_info bi
                     INNER JOIN tbl_guestpayments tp ON bi.bookedid = tp.bookedid
                      
                     WHERE bi.total_price >= bi.paid_amount 
                    
                     
                 ) AS subquery'); 
    
}
    $query = $this->db->get();
//echo $this->db->last_query();die();
    if ($query->num_rows() > 0) {
        return $query->row();    
    }

    return false;
}
public function halldatab2csmalllist_hsn($start_date = NULL, $to_date = NULL)
{   
    // Get today's date and time for default values
    $today_date = date('d-m-Y');
    $start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';

    // Format the provided dates
    $start_date = ($start_date) ? DateTime::createFromFormat('d-m-Y', $start_date)->format('Y-m-d') . ' 00:00:00' : '';
    $to_date = ($to_date) ? DateTime::createFromFormat('d-m-Y', $to_date)->format('Y-m-d') . ' 23:59:59' : '';

    // Select query with SUM() functions and subquery
    $this->db->select('SUM(tbl_hallroom_booking.totalamount) AS total_amount, 
                      SUM(tbl_hallroom_booking.cgst) AS total_cgst, 
                      SUM(tbl_hallroom_booking.sgst) AS total_sgst');
    $this->db->from('tbl_hallroom_booking');

    // Subquery for filtering based on guest payments and customer info
    $this->db->where('tbl_hallroom_booking.hbid IN (
        SELECT `tbl_hallroom_booking`.`hbid`
        FROM `tbl_hallroom_booking`
        JOIN `tbl_guestpayments` ON `tbl_guestpayments`.`bookedid` = `tbl_hallroom_booking`.`hbid`
        JOIN `customerinfo` ON `customerinfo`.`customerid` = `tbl_hallroom_booking`.`customerid`
        WHERE `tbl_guestpayments`.`book_type` = "1"
        AND `tbl_guestpayments`.`paymentamount` > 0
        AND `tbl_hallroom_booking`.`status` = "5"' . 
        ($start_date ? 'AND STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y %H:%i:%s") >= "' . $start_date . '" AND STR_TO_DATE(tbl_guestpayments.paydate, "%d-%m-%Y %H:%i:%s") <= "' . $to_date . '" ' : '') . '
        GROUP BY `tbl_hallroom_booking`.`hbid`
    )');

    // Execute the query
    $query = $this->db->get();

    // Return the results
    return $query->result();
}


}

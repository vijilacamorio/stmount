    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report_model extends CI_Model {
	
 
	public function getlist($customer=NULL,$status=NULL,$payment_status=NULL,$fromdate=NULL,$todate=NULL)
	{
		
		$betweenq="booked_info.checkindate >='".$fromdate."' AND booked_info.checkoutdate <='".$todate."'";
		$this->db->select('booked_info.*,booked_details.*,roomdetails.roomtype');
        $this->db->from('booked_info');
		$this->db->join('roomdetails','roomdetails.roomid=booked_info.roomid','left');
		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
		if($fromdate != NULL){
			$this->db->where('booked_info.checkindate>=',$fromdate);
		}
		if($todate != NULL){
			$this->db->where('booked_info.checkoutdate<=',$todate);
		}
		if($status != NULL){
			$this->db->where('booked_info.bookingstatus',$status);
		}
		if($customer != NULL){
			$this->db->where('booked_info.cutomerid',$customer);
		}
        $this->db->order_by('booked_info.bookedid', 'desc');
        $query = $this->db->get();
		$scharge = $this->settinginfo();
		$charge = $scharge->servicecharge;
		$paymentarray = array();
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
						$result[$k]->total_price = $days*$r->total_price + $promocode + ($days * (($totalrent*$charge)/100));
						$result[$k]->paid_amount = $r->paid_amount;
					}
					if($r->bookingstatus==5){
						$creditamt = $this->db->select("rate,credit,complementary,extrabpc,additional_charges,additional_charges,ex_discount,swimming_pool,restaurant,hallroom,car_parking,special_discount,scharge")->from("tbl_postedbills")->where("bookedid",$r->bookedid)->get()->row();
						$result[$k]->creditamt = $creditamt->credit;
						$result[$k]->total_price =$creditamt->complementary + $creditamt->extrabpc + $days*$r->total_price + $promocode + $creditamt->scharge + $creditamt->additional_charges - $creditamt->ex_discount + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->special_discount;
						$result[$k]->paid_amount = $r->paid_amount + ($days * (($totalrent*$charge)/100)) + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->ex_discount - $creditamt->special_discount;
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
			if(!empty($payment_status)){
				return $paymentarray;
			}
			return $result;
        }
        return false;
	
	}
public function nightaudit(){
		$today_date = date('d-m-Y');
		$start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
$this->db->select("SUM(a.paid_amount) as paid_amount,SUM(a.roomrate) as roomrate,SUM(a.total_room) as total_room,SUM(a.children) as children,SUM(a.nuofpeople) as nuofpeople, SUM(a.total_price) as total_price,SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.advance_amount) as advance_amount,SUM(b.discountamount) as discountamount,SUM(b.extrabed) as extrabed,SUM(b.extraperson) as extraperson,SUM(b.extrachild) as extrachild"); 
		$this->db->from('booked_info a');
$this->db->join('booked_details b','a.bookedid = b.bookedid');

	$this->db->where("a.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();

	return $query->result();
}
public function nightaudit_hall(){
	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
$this->db->select("SUM(rent) as rent,SUM(totalamount) as totalamount,SUM(paid_amount) as paid_amount,SUM(cgst) as cgst,SUM(sgst) as sgst, SUM(people) as people"); 
	$this->db->from('tbl_hallroom_booking');
$this->db->where("end_date BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();
//echo $this->db->last_query();die();
return $query->result();
}
public function nightaudit_current_month_hall(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("SUM(rent) as rent,SUM(totalamount) as totalamount,SUM(paid_amount) as paid_amount,SUM(cgst) as cgst,SUM(sgst) as sgst, SUM(people) as people"); 
	$this->db->from('tbl_hallroom_booking');
$this->db->where("end_date BETWEEN '$start_datetime' AND '$end_datetime'");
 $query = $this->db->get();
 return $query->result();
}
public function nightaudit_current_year_hall(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year
	$this->db->select("SUM(rent) as rent,SUM(totalamount) as totalamount,SUM(paid_amount) as paid_amount,SUM(cgst) as cgst,SUM(sgst) as sgst, SUM(people) as people"); 
	$this->db->from('tbl_hallroom_booking');
$this->db->where("end_date BETWEEN '$start_datetime' AND '$end_datetime'");
   
    $query = $this->db->get();
    return $query->result();
}
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
    $current_month = date('Y-m');
    $start_datetime = $current_month . '-01 00:00:00';
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59';

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

public function nightaudit_current_year_room_status() {
    $current_year = date('Y');
    $start_datetime = $current_year . '-01-01 00:00:00';
    $end_datetime = $current_year . '-12-31 23:59:59';

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
	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
$this->db->select("SUM(special_discount) as special_discount,SUM(additional_charges) as additional_charges,SUM(ex_discount) as ex_discount"); 
	$this->db->from('tbl_postedbills');
$this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();
//echo $this->db->last_query();die();
return $query->result();
}
public function nightaudit_current_month(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("SUM(a.paid_amount) as paid_amount,SUM(a.roomrate) as roomrate,SUM(a.total_room) as total_room,SUM(a.children) as children,SUM(a.nuofpeople) as nuofpeople, SUM(a.total_price) as total_price,SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.advance_amount) as advance_amount,SUM(b.discountamount) as discountamount,SUM(b.extrabed) as extrabed,SUM(b.extraperson) as extraperson,SUM(b.extrachild) as extrachild"); 
	$this->db->from('booked_info a');
$this->db->join('booked_details b','a.bookedid = b.bookedid');
	
    $this->db->where("a.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
 return $query->result();
}
public function nightaudit_current_month_postedbills(){
	$current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; 
$this->db->select("SUM(special_discount) as special_discount,SUM(additional_charges) as additional_charges,SUM(ex_discount) as ex_discount"); 
	$this->db->from('tbl_postedbills');
$this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();
return $query->result();
}
public function nightaudit_current_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year
	$this->db->select("SUM(a.paid_amount) as paid_amount,SUM(a.roomrate) as roomrate,SUM(a.total_room) as total_room,SUM(a.children) as children,SUM(a.nuofpeople) as nuofpeople, SUM(a.total_price) as total_price,SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.advance_amount) as advance_amount,SUM(b.discountamount) as discountamount,SUM(b.extrabed) as extrabed,SUM(b.extraperson) as extraperson,SUM(b.extrachild) as extrachild"); 
	$this->db->from('booked_info a');
$this->db->join('booked_details b','a.bookedid = b.bookedid');
    $this->db->where("a.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
    
    $query = $this->db->get();
    return $query->result();
}
public function nightaudit_current_year_postedbills(){
	$current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; 
$this->db->select("SUM(special_discount) as special_discount,SUM(additional_charges) as additional_charges,SUM(ex_discount) as ex_discount"); 
	$this->db->from('tbl_postedbills');
$this->db->where("checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();
return $query->result();
}

public function nightaudit_restaurent(){
		$today_date = date('d-m-Y');
		$start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
$this->db->select("SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.customerpaid) as customerpaid, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount,SUM(a.bill_amount) as bill_amount"); 
		$this->db->from('bill a');
		$this->db->join('customer_order b','a.order_id = b.order_id');
	$this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
$query = $this->db->get();

	return $query->result();
}
public function nightaudit_restaurent_current_month(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.customerpaid) as customerpaid, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount,SUM(a.bill_amount) as bill_amount"); 
	$this->db->from('bill a');
	$this->db->join('customer_order b','a.order_id = b.order_id');
    $this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
 return $query->result();
}
public function nightaudit_restaurent_current_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year
	$this->db->select("SUM(b.cgst) as cgst,SUM(b.sgst) as sgst,SUM(b.customerpaid) as customerpaid, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount,SUM(a.bill_amount) as bill_amount"); 
	$this->db->from('bill a');
	$this->db->join('customer_order b','a.order_id = b.order_id');
    $this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
    
    $query = $this->db->get();
    return $query->result();
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
return $query->result();
}

public function nightaudit_payment_method_room_month(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->where("book_type","0");
	$this->db->group_by("paymenttype");
    $query = $this->db->get();

	//echo $this->db->last_query();die();
 return $query->result();
}

public function nightaudit_payment_method_room_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

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

public function getOccupancyData() {

	$today_date = date('d-m-Y');
		$start_datetime = $today_date . ' 00:00:00';
    $end_datetime = $today_date . ' 23:59:59';
    $this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
	$this->db->from('roomdetails rd');
	$this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
	$this->db->where("bi.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->group_by('rd.roomtype');
	$query = $this->db->get();
	//echo $this->db->last_query();
	return $query->result();
}
public function getOccupancyData_current_month() {
	$current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
	$this->db->from('roomdetails rd');
	$this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
	$this->db->where("bi.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->group_by('rd.roomtype');
	  $query = $this->db->get();
	 // echo $this->db->last_query();
 return $query->result();
}
public function getOccupancyData_current_year() {
	$current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59';
	$this->db->select('rd.roomtype, COUNT(bi.roomid) as total_occupied');
	$this->db->from('roomdetails rd');
	$this->db->join('booked_info bi', 'rd.roomid = bi.roomid', 'left');
	$this->db->where("bi.checkoutdate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->group_by('rd.roomtype');
	  $query = $this->db->get();
	 // echo $this->db->last_query();die();
      return $query->result();
}
public function nightaudit_payment_method_hall(){
	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
	
$this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
$this->db->where("book_type","1");
$this->db->group_by("paymenttype");
$query = $this->db->get();
return $query->result();
}

public function nightaudit_payment_method_hall_month(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->where("book_type","1");
	$this->db->group_by("paymenttype");
    $query = $this->db->get();

	//echo $this->db->last_query();die();
 return $query->result();
}

public function nightaudit_payment_method_hall_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

	$this->db->select("paymenttype,SUM(paymentamount) as paymentamount"); 
	$this->db->from('tbl_guestpayments');
    $this->db->where("paydate BETWEEN '$start_datetime' AND '$end_datetime'");
	$this->db->where("book_type","1");
    $this->db->group_by("paymenttype");

    $query = $this->db->get();
   // echo $this->db->last_query(); // Output the generated SQL query for debugging
   // die();

    return $query->result();
}
public function nightaudit_payment_method_restaurant(){
	$today_date = date('d-m-Y');
	$start_datetime = $today_date . ' 00:00:00';
$end_datetime = $today_date . ' 23:59:59';
$this->db->select("b.payment_method as payment_method, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount,SUM(a.bill_amount) as bill_amount"); 
	$this->db->from('bill a');
	$this->db->join('payment_method b','a.payment_method_id = b.payment_method_id');
$this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
  $this->db->group_by("b.payment_method_id, b.payment_method");
$query = $this->db->get();
//echo $this->db->last_query();
return $query->result();
}

public function nightaudit_payment_method_restaurant_month(){
    $current_month = date('Y-m'); // Get current month in 'YYYY-MM' format
    $start_datetime = $current_month . '-01 00:00:00'; // Start of the month
    $end_datetime = date('Y-m-t', strtotime($current_month)) . ' 23:59:59'; // End of the month
	$this->db->select("b.payment_method as payment_method, SUM(a.total_amount) as total_amount,SUM(a.discount) as discount,SUM(a.bill_amount) as bill_amount"); 
	$this->db->from('bill a');
	$this->db->join('payment_method b','a.payment_method_id = b.payment_method_id');
    $this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $query = $this->db->get();
	$this->db->group_by("b.payment_method_id, b.payment_method");
	//echo $this->db->last_query();die();
 return $query->result();
}

public function nightaudit_payment_method_restaurant_year(){
    $current_year = date('Y'); // Get current year in 'YYYY' format
    $start_datetime = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_datetime = $current_year . '-12-31 23:59:59'; // End of the year

    $this->db->select("b.payment_method_id, b.payment_method, SUM(a.total_amount) as total_amount, SUM(a.discount) as discount, SUM(a.bill_amount) as bill_amount");
    $this->db->from('bill a');
    $this->db->join('payment_method b', 'a.payment_method_id = b.payment_method_id');
    $this->db->where("a.bill_date BETWEEN '$start_datetime' AND '$end_datetime'");
    $this->db->group_by("b.payment_method_id, b.payment_method");

    $query = $this->db->get();
	//echo $this->db->last_query();die();
   return $query->result();
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
$this->db->select('booked_info.*, roomdetails.*, customer_order.*, booked_details.*, 
    booked_details.cgst as bcgst, booked_details.sgst as bsgst, 
    customer_order.cgst as ccgst, customer_order.sgst as csgst,
    booked_info.nuofpeople,booked_details.extrabed,booked_details.extraperson,booked_details.extrachild'); // Add the nu_of_people field to the select statement
$this->db->from('booked_info');
$this->db->join('customer_order', 'booked_info.cutomerid = customer_order.customer_id', 'left');
$this->db->join('booked_details', 'booked_info.bookedid = booked_details.bookedid', 'left');
$this->db->join('roomdetails', 'booked_info.roomid = roomdetails.roomid', 'left');
if ($start_date !== NULL) {
    $this->db->where('booked_info.date_time >=', $start_date);
}
if ($to_date !== NULL) {
    $this->db->where('booked_info.date_time <=', $to_date);
}
$query = $this->db->get();

if ($query->num_rows() > 0) {
    return $query->result();
}
return false;

}
	
	
	
	
// public function getcosummarylist($start_date = NULL, $to_date = NULL)
// {
//     $this->db->select('booked_info.*,roomdetails.*,customer_order.*, booked_details.*, 
//         booked_details.cgst as bcgst, booked_details.sgst as bsgst, 
//         customer_order.cgst as ccgst, customer_order.sgst as csgst,
//         (SELECT SUM(SUBSTRING_INDEX(SUBSTRING_INDEX(nuofpeople, ",", n), ",", -1)) 
//             FROM (SELECT nuofpeople, LENGTH(nuofpeople) - LENGTH(REPLACE(nuofpeople, ",", "")) + 1 AS n 
//                   FROM booked_info AS sub WHERE sub.booking_number = booked_info.booking_number) AS split_nuofpeople) AS total_nuofpeople');
//     $this->db->from('booked_info');
//     $this->db->join('customer_order', 'booked_info.cutomerid = customer_order.customer_id', 'left');
//     $this->db->join('booked_details', 'booked_info.bookedid = booked_details.bookedid', 'left');
//     $this->db->join('roomdetails', 'booked_info.roomid = roomdetails.roomid', 'left');
//     if ($start_date !== NULL) {
//         $this->db->where('booked_info.date_time >=', $start_date);
//     }
//     if ($to_date !== NULL) {
//         $this->db->where('booked_info.date_time <=', $to_date);
//     }
//     $query = $this->db->get();
    
//     if ($query->num_rows() > 0) {
//         return $query->result();
//     }
//     return false;
// }



	
	
		
	public function getadvanceinfo($start_date = NULL, $to_date = NULL)
	{
		$this->db->select('booked_info.*, booked_details.*');
		$this->db->from('booked_details');
		$this->db->join('booked_info', 'booked_details.bookedid = booked_info.bookedid');
	
		// Assuming 'date_time' is a column in the 'booked_info' table
		if ($start_date !== NULL) {
			$this->db->where('booked_info.date_time >=', $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where('booked_info.date_time <=', $to_date);
		}
	
		$query = $this->db->get();

		// echo $this->db->last_query(); die();

		// Uncomment the next line for debugging purposes
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
	







	public function getfoodinfo($start_date = NULL, $to_date = NULL)
	{
		$this->db->select('SUM(totalamount) as totalfood, COUNT(order_id) as totalorder ,order_date');
		$this->db->from('customer_order');
	 
 
		if ($start_date !== NULL) {
			$this->db->where("customer_order.order_date >=", $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where("customer_order.order_date <=", $to_date);
		}

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
	

	public function gettariffinfo($start_date = NULL, $to_date = NULL)
	{
		$this->db->select('SUM(cgst + sgst) as totaltariff,order_date');
		$this->db->from('customer_order');
	 

		if ($start_date !== NULL) {
			$this->db->where("customer_order.order_date >=", $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where("customer_order.order_date <=", $to_date);
		}

		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->row();    
		}
	
		return false;
	}
	


	public function getbedinfo($start_date = NULL, $to_date = NULL)	
	{
		$this->db->select('SUM(extrabed) as bed, COUNT(extrabed) as totalbed,extracheckin');
		$this->db->from('booked_details');
	 
		if ($start_date !== NULL) {
			$this->db->where("booked_details.extracheckin >=", $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where("booked_details.extracheckin <=", $to_date);
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
	 
	 
public function getbusinessinfo($start_date = NULL, $to_date = NULL)
	{
		$this->db->select('booked_info.*, customer_order.*,customerinfo.*');
		$this->db->from('booked_info');
 		$this->db->join('customer_order', 'booked_info.cutomerid = customer_order.customer_id', 'left');
		$this->db->join('customerinfo', 'customerinfo.customerid = customer_order.order_id', 'left');
	

		if ($start_date !== NULL) {
			$this->db->where('customer_order.order_date >=', $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where('customer_order.order_date <=', $to_date);
		}
		$query = $this->db->get();
	    // echo $this->db->last_query(); die();
		// Log the query
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
	 
	 
	 
	 
	public function getsettlementsummaryinfo($start_date = NULL, $to_date = NULL)
	{
	
		$this->db->select('payment_method.payment_method as pay_type,order_menu.price as price ,order_menu.menuqty as  qty, item_foods.*,order_menu.*,customer_order.*,CONCAT_WS(" ", customerinfo.firstname,customerinfo.lastname) as customer_name,bill.bill_status,customer_order.food_need_date as food_date,item_foods.ProductName as item,customer_order.totalamount as totalamount,item_category.Name as category,order_menu.order_id as order_id');
		$this->db->from('customer_order');
		$this->db->join('customerinfo','customer_order.customer_id=customerinfo.customerid','left');
		$this->db->join('order_menu','customer_order.order_id=order_menu.order_id');
		$this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID');
		$this->db->join('item_category','item_category.CategoryID=item_foods.CategoryID');
		$this->db->join('customer_type','customer_order.cutomertype=customer_type.customer_type_id','left');
		$this->db->join('employee_history','customer_order.waiter_id=employee_history.emp_his_id','left');
		$this->db->join('rest_table','customer_order.table_no=rest_table.tableid','left');
		$this->db->join('bill','customer_order.order_id=bill.order_id','left');
		$this->db->join('multipay_bill','customer_order.order_id=multipay_bill.order_id','left');
			$this->db->join('payment_method','multipay_bill.payment_type_id=payment_method.payment_method_id','left');
	$this->db->order_by('order_menu.order_id','desc');
	
		if ($start_date !== NULL) {
			$this->db->where('customer_order.order_date >=', $start_date);
		}
		if ($to_date !== NULL) {
			$this->db->where('customer_order.order_date <=', $to_date);
		}
	
		$query = $this->db->get();
	   // echo $this->db->last_query(); die();
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function details($id)
	{
		
		$this->db->select('booked_info.*,GROUP_CONCAT(roomdetails.roomtype ORDER BY booked_info.roomid,roomdetails.roomtype) as roomtype,roomdetails.rate');
        $this->db->from('booked_info');
		$this->db->join('tbl_roomnofloorassign','FIND_IN_SET(tbl_roomnofloorassign.roomno,booked_info.room_no)<>0','left');
		$this->db->join('roomdetails','FIND_IN_SET(roomdetails.roomid,tbl_roomnofloorassign.roomid)<>0','left');
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
	
	public function read($limit = null, $start = null)
	{
	    $this->db->select('booked_info.*,booked_details.*');
        $this->db->from('booked_info');
		$this->db->join('booked_details','booked_details.bookedid=booked_info.bookedid','left');
        $this->db->order_by('booked_info.bookedid', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
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
						$result[$k]->total_price = $days*$r->total_price + $promocode + ($days * (($totalrent*$charge)/100));
						$result[$k]->paid_amount = $r->paid_amount;
					}
					if($r->bookingstatus==5){
						$creditamt = $this->db->select("rate,credit,complementary,extrabpc,additional_charges,additional_charges,ex_discount,swimming_pool,restaurant,hallroom,car_parking,special_discount, scharge")->from("tbl_postedbills")->where("bookedid",$r->bookedid)->get()->row();
						$result[$k]->creditamt = $creditamt->credit;
						$result[$k]->total_price =$creditamt->complementary + $creditamt->extrabpc + $days*$r->total_price + $promocode + $creditamt->scharge + $creditamt->additional_charges - $creditamt->ex_discount + $creditamt->swimming_pool + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->special_discount;
						$result[$k]->paid_amount = $r->paid_amount + ($days * (($totalrent*$charge)/100)) + $creditamt->restaurant + $creditamt->hallroom + $creditamt->car_parking - $creditamt->ex_discount - $creditamt->special_discount;
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
					}
				}
			}
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
}

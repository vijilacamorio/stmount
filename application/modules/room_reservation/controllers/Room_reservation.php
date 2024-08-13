<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Room_reservation extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'roomreservation_model'
		));	
    }
    public function bookingdatatable(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
    		0 => 'booked_info.bookedid', 
    		1 => 'booking_number', 
    		2 => 'roomtype',
    		3 => 'booked_info.room_no',
    		4 => 'customerinfo.firstname',
    		5 => 'customerinfo.cust_phone',
    		6 => 'checkindate',
    		7 => 'checkoutdate',
    		8 => 'bookingstatus',
    		9 => 'paid_amount',
    		10 => 'advance_amount',
    	    11=> 'customerinfo.lastname',
		);

		$where = $sqlTot = $sqlRec = "";
		// check search value exist
		if(!empty($params['search']['value']) ) {   
			$where .=" WHERE ";
			$where .=" ( booked_info.booking_number LIKE '".$params['search']['value']."%' ";    
			$where .=" OR booked_info.full_guest_name LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.room_no LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.checkindate LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.checkoutdate LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.bookingstatus LIKE '0' )";

		}else{
			$where =" WHERE (booked_info.bookingstatus=0 )";
		}
		// getting total number records without any search
		$sql = "SELECT booked_details.*,booked_details.advance_amount,booked_info.*,customerinfo.firstname,customerinfo.cust_phone FROM booked_info Left join customerinfo ON customerinfo.customerid=booked_info.cutomerid Left join booked_details ON booked_details.bookedid=booked_info.bookedid";
		
		
		$sqlTot .= $sql;
		$sqlRec .= $sql;
		//concatenate search sql if value exist
		if(isset($where) && ($where != '')) {
			$sqlTot .= $where;
			$sqlRec .= $where;
		}
		$SQLtotal=$this->db->query($sqlTot);
		$totalRecords = $SQLtotal->num_rows();	
		if ($params['length'] == '-1'){	
			$params['length']= intval($totalRecords);
		}
	//	$sqlRec .=  " ORDER BY ".$columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";
    $sqlRec .=  " ORDER BY booked_info.bookedid ASC LIMIT ".$params['start']." ,".$params['length']." ";
		$SQLoffer=$this->db->query($sqlRec);
		$queryRecords=$SQLoffer->result();
		$i=0;
		foreach($queryRecords as  $value){
			$i++;
			$row = array();
			$update='';
			$delete='';
			if($this->permission->method('room_reservation','update')->access()):
			$update='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="editresrvation('.$value->bookedid.')" class="btn btn-warning btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Edit"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('room_reservation','update')->access()):
			$checkin='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="checkinresrvation('.$value->bookedid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Check In" title="Check-In"><i class="ti-check-box text-white" aria-hidden="true"></i></a>';
			endif;
// 			if($this->permission->method('room_reservation','read')->access()):
// 				$view='<a href="'.base_url().'room_reservation/booking-information/'.$value->bookedid.'" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="View" title="View"><i class="ti-eye"></i></a>';
// 			endif;
// 			if($this->permission->method('room_reservation','read')->access()):
// 			$print='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="printresrvation('.$value->bookedid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print"><i class="fa fa-print text-white" aria-hidden="true"></i></a>';
// 			endif;
			if($this->permission->method('room_reservation','read')->access()):
			$adv='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="print_adv_resrvation('.$value->bookedid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print"><i class="ti-money text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('room_reservation','create')->access()):
			$Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->bookedid.'" class="btn btn-success btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
			endif;
			if($this->permission->method('room_reservation','update')->access()):
			$cancel='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="cancelreservation('.$value->bookedid.')" class="btn btn-danger btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Cancel" title="Cancel Reservation"><i class="ti-close text-white" aria-hidden="true"></i></a>';
			endif;
			$datediff = strtotime($value->checkoutdate) - strtotime($value->checkindate);
            $datediff = ceil($datediff/(60*60*24));
			$totalPrice = $value->total_price*$datediff;
			if($value->bookingstatus==0){
				$status="Booked";
				}
			else if($value->bookingstatus==1){
				$status="Cancel";
				}
			else if($value->bookingstatus==2){
				$status="Success";
				}
			else if($value->bookingstatus==3){
				$status="Completed";
				}
			else if($value->bookingstatus==4){
				$status="Check In";
				}
			else if($value->bookingstatus==5){
				$status="Checkout";
				}
				if($value->paid_amount<=0){
				    	$paymentStatus="Pending";
				}
		   else	if($value->paid_amount<$totalPrice){
				$paymentStatus="Partially Paid";
				}
			else{
				$paymentStatus="Success";
			}
			$allroomname = "";
			$uniqueRoomTypes = [];
			$row[] =$i;
			$row[] =$value->booking_number;
			$rname = explode(",",$value->roomid);
			for($l=0;$l<count($rname); $l++){
				$roomname = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$rname[$l])->get()->row();
				$allroomname .= $roomname->roomtype.", ";
				$uniqueRoomTypes[] = $roomname->roomtype;
			}
		$uniqueRoomTypes = implode(", ", array_unique($uniqueRoomTypes));

$row[] = trim($uniqueRoomTypes, ", ");
            $room_no = implode(', ',explode(',',$value->room_no));
            
            $row[] =$room_no;
		
			$row[] =$value->firstname." ".$value->lastname;
			$row[] =$value->cust_phone;
			$row[] =$value->checkindate;
			$row[] =$value->checkoutdate;
				$row[] = $value->total_price;
			$row[] =$value->advance_amount;
			
			$due = $value->total_price - $value->advance_amount;
			$row[] = $due<0?0:number_format($due,2);
		
			$row[] =$status;
			$row[] =$paymentStatus;
			if($paymentStatus=="Partially Paid"){
			$row[] =$update.$checkin.$view.$adv.$cancel;
			}else{
			 	$row[] =$update.$checkin.$view.$cancel;   
			}
			$data[] = $row;
			
			}
		
		$json_data = array(
				"draw"            => intval( $params['draw'] ),   
				"recordsTotal"    => intval( $totalRecords ),  
				"recordsFiltered" => intval($totalRecords),
				"data"            => $data   // total data array
				);

		echo json_encode($json_data);
   	}
    public function checkindatatable(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'booked_info.bookedid', 
		1 => 'booking_number', 
		2 => 'roomtype',
		3 => 'booked_info.room_no',
		4 => 'customerinfo.firstname',
		5 => 'customerinfo.cust_phone',
		6 => 'checkindate',
		7 => 'checkoutdate',
		8 => 'bookingstatus',
		9 => 'paid_amount',
		10 => 'customerinfo.lastname',
		);

		$where = $sqlTot = $sqlRec = "";
		// check search value exist
		if(!empty($params['search']['value']) ) {   
			$where .=" WHERE ";
			$where .=" ( booked_info.booking_number LIKE '".$params['search']['value']."%' ";    
			$where .=" OR roomdetails.roomtype LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.room_no LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.checkindate LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.checkoutdate LIKE '".$params['search']['value']."%' ";
			$where .=" OR booked_info.bookingstatus LIKE '".$params['search']['value']."%' )";
		}
		// getting total number records without any search
		$sql = "SELECT booked_details.advance_amount,booked_info.*,customerinfo.firstname,customerinfo.lastname,customerinfo.cust_phone FROM booked_info Left join customerinfo ON customerinfo.customerid=booked_info.cutomerid Left join booked_details ON booked_details.bookedid=booked_info.bookedid where booked_info.bookingstatus=4";
		
		
		$sqlTot .= $sql;
		$sqlRec .= $sql;
		//concatenate search sql if value exist
		if(isset($where) && ($where != '')) {
			$sqlTot .= $where;
			$sqlRec .= $where;
		}
		$SQLtotal=$this->db->query($sqlTot);
		$totalRecords = $SQLtotal->num_rows();	
		if ($params['length'] == '-1'){	
			$params['length']= intval($totalRecords);
		}
		$sqlRec .=  " ORDER BY ".$columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";

		$SQLoffer=$this->db->query($sqlRec);
		$queryRecords=$SQLoffer->result();
		$i=0;
		foreach($queryRecords as $value){
			$i++;
			$row = array();
			$update='';
			$delete='';
			$Payment='';
			if($this->permission->method('room_reservation','update')->access()):
			$update='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="editresrvation('.$value->bookedid.')" class="btn btn-warning btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Edit"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('room_reservation','update')->access()):
			$checkin='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="checkoutresrvation('.$value->bookedid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Checkout" title="Check-Out"><i class="ti-thumb-up text-white" aria-hidden="true"></i></a>';
			endif;
		
			if($this->permission->method('room_reservation','read')->access()):
			$print='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a  onclick="printresrvation('.$value->bookedid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print"><i class="fa fa-print text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('room_reservation','read')->access()):
			$adv='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a  onclick="print_adv_resrvation('.$value->bookedid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Advance Receipt"><i class="ti-money text-white" aria-hidden="true"></i></a>';
			endif;
       
			if($this->permission->method('room_reservation','update')->access()):
			$cancel='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="cancelreservation('.$value->bookedid.')" class="btn btn-danger btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Cancel" title="Cancel Reservation"><i class="ti-close text-white" aria-hidden="true"></i></a>';
			endif;
			$datediff = strtotime($value->checkoutdate) - strtotime($value->checkindate);
            $datediff = ceil($datediff/(60*60*24));
			$totalPrice = $value->total_price*$datediff;
			if($value->bookingstatus==0){
				$status="Pending";
				}
			else if($value->bookingstatus==1){
				$status="Cancel";
				}
			else if($value->bookingstatus==2){
				$status="Success";
				}
			else if($value->bookingstatus==3){
				$status="Completed";
				}
			else if($value->bookingstatus==4){
				$status="Check In";
				}
			else if($value->bookingstatus==5){
				$status="Checkout";
				}
			if($value->advance_amount<= 0){
				$paymentStatus="Pending";
				}
			else if($value->advance_amount < $totalPrice){
				$paymentStatus="Partially Paid";
			}else{
			  	$paymentStatus="Success";  
			}
			$allroomname = "";
			$uniqueRoomTypes=array();
			$row[] =$i;
			$row[] =$value->booking_number;
			$rname = explode(",",$value->roomid);
			for($l=0;$l<count($rname); $l++){
				$roomname = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$rname[$l])->get()->row();
				$allroomname .= $roomname->roomtype.", ";
				$uniqueRoomTypes[] = $roomname->roomtype;
			}
			$uniqueRoomTypes = implode(", ", array_unique($uniqueRoomTypes));
			$row[] =trim($uniqueRoomTypes,", ");
			  $room_no = implode(', ',explode(',',$value->room_no));
		$row[] = $room_no;
			$row[] =$value->firstname." ".$value->lastname;
			$row[] =$value->cust_phone;
			$row[] =$value->checkindate;
			$row[] =$value->checkoutdate;
				$row[] = $value->total_price;
				$row[] =$value->advance_amount;
			
			$due = $value->total_price - $value->advance_amount;
		
		
		
			$row[] = $due<0?0:number_format($due,2);
		
			$row[] =$status;
			$row[] =$paymentStatus;
			
			if($paymentStatus=="Partially Paid"){
			$row[] =$update.$checkin.$adv;
			}else{
			 	$row[] =$update.$checkin;
			}
			
			
		
			$data[] = $row;
			
			}
		
		$json_data = array(
				"draw"            => intval( $params['draw'] ),   
				"recordsTotal"    => intval( $totalRecords ),  
				"recordsFiltered" => intval($totalRecords),
				"data"            => $data   // total data array
				);

		echo json_encode($json_data);
   	}
 	public function einvoice() {
   	    
     $number = $_SERVER['REQUEST_URI'];
     $path = parse_url($number, PHP_URL_PATH);
     $lastParameter = basename($path);
      $data["details"] = $this->roomreservation_model->room_details($lastParameter);
      $data['store']=$this->roomreservation_model->storeinfo();
  //  print_r($data['store']);die();
    //   echo $data['details']->booking_number;
     $url = "https://api.mastergst.com/einvoice/type/GENERATE/version/V1_03?email=suryavenkatesh3093@gmail.com";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
   "accept: */*",
   "ip_address: 2401:4900:1f2d:9e2b:9498:2784:fb5f:a6d6",
   "client_id: 03d6e6f1-75fb-4cc3-855a-85e4a742abc7",
   "client_secret: a3446f02-0d5c-472e-a0a1-532924e3e8ad",
   "username: mastergst",
   "auth-token: BVI9hqBZkhEd403VnnZql3Jsi",
   "gstin: 29AABCT1332L000",
   "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = '{"Version":"1.1","TranDtls":{"TaxSch":"GST","SupTyp":"B2B","RegRev":"N","EcmGstin": null ,"IgstOnIntra":"N"},"DocDtls":{"Typ":"INV","No":"' . $data['details']->booking_number . '","Dt":"02/10/2022"},"SellerDtls":{"Gstin":"29AABCT1332L000","LglNm":"' . $data['store']->title . '","TrdNm":null,"Addr1":"' . $data['store']->address . '","Addr2":null,"Loc":"' . $data['store']->city . '","Pin":587315,"Stcd":"29","Ph":null,"Em":"' . $data['store']->email . '"},"BuyerDtls":{"Gstin":"' . $data['details']->gst_no . '","LglNm":"' . $data['details']->c_full_name . '","TrdNm":null,"Pos":"37","Addr1":"' . $data['details']->c_address . '","Addr2":null,"Loc":"GANDHINAGAR","Pin":600100,"Stcd":"33","Ph":null,"Em":"' . $data['details']->c_email . '"},"ItemList":[{"SlNo":"1","IsServc":"N","PrdDesc":"Rice","HsnCd":"1001","Barcde":"123456","BchDtls":{"Nm":"123456","Expdt":"01/08/2023","wrDt":"01/09/2023"},"Qty":100.345,"FreeQty":10,"Unit":"NOS","UnitPrice":99.545,"TotAmt":9988.84,"Discount":10,"PreTaxVal":1,"AssAmt":9978.84,"GstRt":12,"SgstAmt":0,"IgstAmt":1197.46,"CgstAmt":0,"CesRt":5,"CesAmt":498.94,"CesNonAdvlAmt":10,"StateCesRt":12,"StateCesAmt":1197.46,"StateCesNonAdvlAmt":5,"OthChrg":10,"TotItemVal":12897.7,"OrdLineRef":"3256","OrgCntry":"AG","PrdSlNo":"12345","AttribDtls":[{"Nm":"Rice","Val":"10000"}]}],"ValDtls":{"AssVal":9978.84,"CgstVal":0,"SgstVal":0,"IgstVal":1197.46,"CesVal":508.94,"StCesVal":1202.46,"Discount":10,"OthChrg":20,"RndOffAmt":0.3,"TotInvVal":12908,"TotInvValFc":12897.7},"PayDtls":{"Nm":"ABCDE","Accdet":"5697389713210","Mode":"Cash","Fininsbr":"SBIN11000","Payterm":"100","Payinstr":"Gift","Crtrn":"test","Dirdr":"test","Crday":100,"Paidamt":10000,"Paymtdue":5000},"RefDtls":{"InvRm":"TEST","DocPerdDtls":{"InvStDt":"02/10/2022","InvEndDt":"02/11/2022"},"PrecDocDtls":[{"InvNo":"INVTWC/101","InvDt":"02/10/2022","OthRefNo":"123456"}],"ContrDtls":[{"RecAdvRefr":"DOC/002","RecAdvDt":"01/10/2022","Tendrefr":"Abc001","Contrrefr":"Co123","Extrefr":"Yo456","Projrefr":"Doc-456","Porefr":"Doc-789","PoRefDt":"01/10/2022"}]},"AddlDocDtls":[{"Url":"https://einv-apisandbox.nic.in","Docs":"Test Doc","Info":"Document Test"}],"ExpDtls":{"ShipBNo":"A-248","ShipBDt":"01/08/2020","Port":"INABG1","RefClm":"N","ForCur":"AED","CntCode":"AE"},"EwbDtls":{"Transid":"12AWGPV7107B1Z1","Transname":"XYZ EXPORTS","Distance":100,"Transdocno":"DOC01","TransdocDt":"01/08/2020","Vehno":"ka123456","Vehtype":"R","TransMode":"1"}}';

//echo ($data);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataString);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);

// Check for cURL errors
if ($resp === false) {
    $error = curl_error($curl);
    echo "cURL Error: " . $error;
} else {
    // Print response for debugging
    var_dump($resp);
}

// Close cURL session
curl_close($curl);
}
   	
   	public function checkoutdatatable(){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $columns = array(
        0 => 'booked_info.bookedid',
        1 => 'booking_number',
        2 => 'roomtype',
        3 => 'booked_info.room_no',
        4 => 'customerinfo.firstname',
        5 => 'customerinfo.cust_phone',
        6 => 'checkindate',
        7 => 'checkoutdate',
        8 => 'bookingstatus',
        9 => 'paid_amount',
        10 => 'total_price',
           11=> 'overalltotal',
        );
        $where = $sqlTot = $sqlRec = "";
        // check search value exist
        if(!empty($params['search']['value']) ) {
            $where .=" WHERE ";
            $where .=" ( booked_info.booking_number LIKE '".$params['search']['value']."%' ";
            $where .=" OR roomdetails.roomtype LIKE '".$params['search']['value']."%' ";
            $where .=" OR booked_info.room_no LIKE '".$params['search']['value']."%' ";
            $where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
            $where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
            $where .=" OR booked_info.checkindate LIKE '".$params['search']['value']."%' ";
            $where .=" OR booked_info.checkoutdate LIKE '".$params['search']['value']."%' ";
            $where .=" OR booked_info.bookingstatus LIKE '".$params['search']['value']."%' )";
        }
        
        // getting total number records without any search
        $sql = "
        SELECT 
    booked_info.*, 
    customerinfo.firstname, 
    customerinfo.cust_phone, 
    tbl_postedbills.additional_charges,
     booked_details.*
FROM 
    ((booked_info 
    LEFT JOIN customerinfo ON customerinfo.customerid = booked_info.cutomerid) 
    LEFT JOIN tbl_postedbills ON booked_info.bookedid = tbl_postedbills.bookedid)
    LEFT JOIN  booked_details ON booked_info.bookedid = booked_details.bookedid
WHERE 
    booked_info.bookingstatus = 5";
        $sqlTot .= $sql;
        $sqlRec .= $sql;
        //echo $this->db->last_query();die()
        //concatenate search sql if value exist
        if(isset($where) && ($where != '')) {
            $sqlTot .= $where;
            $sqlRec .= $where;
        }
        $SQLtotal=$this->db->query($sqlTot);
        $totalRecords = $SQLtotal->num_rows();
        if ($params['length'] == '-1'){
            $params['length']= intval($totalRecords);
        }
        $sqlRec .=  " ORDER BY ".$columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";
        $SQLoffer=$this->db->query($sqlRec);
        $queryRecords=$SQLoffer->result();
    // echo $this->db->last_query();die();
        $i=0;
        $paymentStatus='';
       
      // print_r($queryRecords);
        foreach($queryRecords as $value){
      
            $i++;
            $row = array();
            $update='';
            $delete='';
            // if($this->permission->method('room_reservation','read')->access()):
            // $view='<a href="'.base_url().'room_reservation/booking-information/'.$value->bookedid.'" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="View" title="View"><i class="ti-eye"></i></a>';
            // endif;
            if($this->permission->method('room_reservation','read')->access()):
            $print='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a href="'.base_url().'room_reservation/viewdetailsprint/'.$value->bookedid.'" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print" target="_blank"><i class="fa fa-print text-white" aria-hidden="true"></i></a>';
            endif;
			 //if($this->permission->method('room_reservation','read')->access()):
    //         $adv='<input name="url" type="hidden" id="url_'.$value->bookedid.'"/><a onclick="print_adv_resrvation('.$value->bookedid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print"><i class="ti-money text-white" aria-hidden="true"></i></a>';
    //         endif;
            if($this->permission->method('room_reservation','create')->access()):
            $Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->bookedid.'" class="btn btn-success btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
            endif;
            $datediff = strtotime($value->checkoutdate) - strtotime($value->checkindate);
            $datediff = ceil($datediff/(60*60*24));
            $totalPrice = $value->total_price*$datediff;
            if (strpos($totalPrice, '.') === false) {
    // If $totalPrice has no decimal value, append '.00'
    $totalPrice = number_format($totalPrice, 2, '.', ''); // Converts to 2 decimal places
}

if($value->additional_charges)
             $additionalCharges = $value->additional_charges;
            $taxRate = 0.18; // 18% tax rate
$taxAmount = $additionalCharges * $taxRate;

// Add tax to the additional charges
$totalAmount = $additionalCharges + $taxAmount;
//echo $totalPrice; // Output: 1200.00
           // echo $value->paid_amount."/". $value->total_price."/".$datediff;echo "<br/>";
            if($value->bookingstatus==0){
                $status="Pending";
                }
            else if($value->bookingstatus==1){
                $status="Cancel";
                }
            else if($value->bookingstatus==2){
                $status="Success";
                }
            else if($value->bookingstatus==3){
                $status="Completed";
                }
            else if($value->bookingstatus==4){
                $status="Check In";
                }
            else if($value->bookingstatus==5){
                $status="Checkout";
                }
                
              if(($totalPrice+$totalAmount) >= ($value->paid_amount+$value->advance_amount)){
				$paymentStatus="Success";
                  $allroomname = "";
            $row[] =$i;
            $row[] =$value->booking_number;
            $rname = explode(",",$value->roomid);
            for($l=0;$l<count($rname); $l++){
                $roomname = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$rname[$l])->get()->row();
                $allroomname .= $roomname->roomtype.", ";
            }
            $row[] =trim($allroomname,", ");
            $row[] =$value->room_no;
            $row[] =$value->firstname;
            $row[] =$value->cust_phone;
            $row[] =$value->checkindate;
            $row[] =$value->checkoutdate;
            $row[] = $value->overalltotal;
            $row[] = $value->paid_amount+$value->advance_amount;
            $due = ($value->overalltotal - ($value->paid_amount+$value->advance_amount));
              $row[] =$due;
            $row[] =$status;
            $row[] =$paymentStatus;
            $row[] =$update.$checkin.$print.$cancel;
            $data[] = $row;
                }
      else  if(($totalPrice+$totalAmount) <= ($value->paid_amount+$value->advance_amount)){
			$paymentStatus="Pending";
                  $allroomname = "";
            $row[] =$i;
            $row[] =$value->booking_number;
            $rname = explode(",",$value->roomid);
            for($l=0;$l<count($rname); $l++){
                $roomname = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$rname[$l])->get()->row();
                $allroomname .= $roomname->roomtype.", ";
            }
            if($value->additional_charges > 0){

            $row[] =trim($allroomname,", ");
            $row[] =$value->room_no;
            $row[] =$value->firstname;
            $row[] =$value->cust_phone;
            $row[] =$value->checkindate;
            $row[] =$value->checkoutdate;
            $row[] = $value->total_price +$totalAmount;
            $row[] = $value->paid_amount+$value->advance_amount;
           

// Calculate the tax


            $due = ($value->total_price + $totalAmount)-($value->paid_amount+$value->advance_amount);
              $row[] =$due;
            }else{
                
                       
       
            $row[] =trim($allroomname,", ");
            $row[] =$value->room_no;
            $row[] =$value->firstname;
            $row[] =$value->cust_phone;
            $row[] =$value->checkindate;
            $row[] =$value->checkoutdate;
            $row[] = $value->total_price;
            $row[] = $value->paid_amount+$value->advance_amount;
           

// Calculate the tax


            $due = ($value->total_price)-($value->paid_amount+$value->advance_amount);
              $row[] =$due;
            
            }
            $row[] =$status;
            $row[] =$paymentStatus;
            $row[] =$update.$checkin.$print.$cancel;
            $data[] = $row;
        
            }
          
            }
       //    print_r($data);die();
        $json_data = array(
                "draw"            => intval( $params['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval($totalRecords),
                "data"            => $data   // total data array
                );
        echo json_encode($json_data);
     //   echo $this->db->last_query();die();
    }

   	
   	


    public function index($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();
		$sc =array('isSeen'         =>  1);  
			    $this->db->update('booked_info',$sc);
				
        $data['title']    = display('room_reservation'); 
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "reservationlist";   
        echo Modules::run('template/layout', $data); 
    }
    



	public function existcustomer(){
		$mobile = $this->input->post("existmobile", TRUE);
		$search = $this->input->post("search", TRUE);
		$type = $this->input->post("type", TRUE);
		if($type!=1){
			$user = $this->db->select("customerid, concat_ws(' ', firstname, lastname) as firstname")->from("customerinfo")->where("cust_phone", $mobile)->get()->row();
			if(empty($user)){
				$data = array(
					'user' => "No User Found",
					'existuser'=> "0"
				);			
			}else{
			$data = array(
				'user' => $user->firstname,
				'userid' => $user->customerid,
				'existuser'=> "1"
			);
			}
			echo json_encode($data); 
		}else{
			$user = $this->db->select('customerid,firstname,cust_phone, city')->from('customerinfo')->where("cust_phone LIKE '%$search%'")->get()->result_array();
			if($user){
				$data = array(
					'user' => $user,
				);
				echo json_encode($data); 
			}else{
				$data = array(
					"user" => "Not found"
				);
				echo json_encode($data);
			}
		}
	}
	
	public function mobilenocheck(){
		$mobile = $this->input->post("mobileno", TRUE);
		$user = $this->db->select("COUNT(customerid) as customer")->from("customerinfo")->where("cust_phone", $mobile)->get()->row();
		if($user->customer<1){
			$data = array(
				'user' => "Number not used before",
				'existuser'=> "0"
			  );			
		}else{
		$data = array(
			'user' => "Number already used",
			'existuser'=> "1"
		  );
		}
		echo json_encode($data); 
	}
    public function booking($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("servicecharge")->from("setting")->get()->row();

        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "addereservation";   
        $this->load->view("room_reservation/addreservation", $data); 
    }








    public function bookingedit($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $data["bookingdata"] = $this->roomreservation_model->editbooking($id);
	    $data["payment_method"] = $this->roomreservation_model->paymentmethod($id);
	    $data["guestdata"] = $this->db->select("customerid,guestname,mobile")->from("tbl_otherguest")->where("tbl_otherguest.bookedid", $id)->get()->result();
	    $data["custdata"] = $this->db->select("cutomerid as customerid,firstname,cust_phone")->from("booked_info")->join("customerinfo","customerinfo.customerid=booked_info.cutomerid","left")->where("booked_info.bookedid", $id)->get()->result();
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["bookingsource"] = $this->roomreservation_model->get_all('*','tbl_booking_type_info','btypeinfoid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("servicecharge")->from("setting")->get()->row();
// 		$data["guestPayments"] = $this->db->select("*")->from("tbl_guestpayments")->where("tbl_otherguest.bookedid", $id)->get()->result();
	//	print_r($data["payment_method"]); die();

        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "editreservation";   
        $this->load->view("room_reservation/editreservation", $data); 
    }



	


	public function checkin($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();
				
        $data['title']    = display('checkin'); 
        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "checkin";   
        echo Modules::run('template/layout', $data); 
    }





	public function checkout()
    {
		$this->permission->method('room_reservation','read')->redirect();
        $data['title']    = display('checkout'); 
		
        $data['module'] = "room_reservation";
        $data['page']   = "checkout";   
        echo Modules::run('template/layout', $data); 
    }
	public function directcheckin($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();
		$data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
        $data['title']    = display('checkin'); 
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
        $data['currency']    = getCurrency(); 
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("servicecharge")->from("setting")->get()->row();
        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "addcheckin";   
        $this->load->view("room_reservation/addcheckin", $data); 
    }
    public function bookingcheckin($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $data["bookingdata"] = $this->roomreservation_model->editbooking($id);
	 //   print_r($data["bookingdata"]); die();
		$data["guestdata"] = $this->db->select("customerid,guestname,mobile")->from("tbl_otherguest")->where("tbl_otherguest.bookedid", $id)->get()->result();
	    $data["custdata"] = $this->db->select("cutomerid as customerid,firstname,cust_phone")->from("booked_info")->join("customerinfo","customerinfo.customerid=booked_info.cutomerid","left")->where("booked_info.bookedid", $id)->get()->result();
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["bookingsource"] = $this->roomreservation_model->get_all('*','tbl_booking_type_info','btypeinfoid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("servicecharge")->from("setting")->get()->row();
        $data["payment_method"] = $this->roomreservation_model->paymentmethod($id);
        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "checkinreservation";   
        $this->load->view("room_reservation/checkinreservation", $data); 
    }
    public function bookingcheckout($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $bid = explode(",", $id);
		for($i=0; $i<count($bid); $i++){
			$bdetails[$i] = $this->roomreservation_model->detailbooking($bid[$i]);
			if ($this->db->table_exists('tbl_pool_booking') ){
				$allpoolbill[$i] = $this->db->select('p.*,c.firstname')->from("tbl_pool_booking p")->join("customerinfo c","c.customerid=p.custid","left")->where("p.entrydate>=",$bdetails[$i]->checkindate)->where("p.entrydate<=",$bdetails[$i]->checkoutdate)->where("custid",$bdetails[$i]->cutomerid)->where("p.status!=",3)->get()->result();
			}else{
				$allpoolbill = "";
			}
			if ($this->db->table_exists('customer_order') ){
				$allrestaurant[$i] = $this->db->select('b.bill_amount,co.order_id,c.firstname')->from("customer_order co")->join("customerinfo c","c.customerid=co.customer_id","left")->join("bill b", "b.order_id=co.order_id", "left")->where("CONCAT_WS(' ',co.order_date,co.order_time)>=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkindate)))->where("CONCAT_WS(' ',co.order_date,co.order_time)<=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkoutdate)))->where("co.customer_id",$bdetails[$i]->cutomerid)->where("co.order_status=",6)->where("b.bill_status=",0)->get()->result();
			}else{
				$allrestaurant = "";
			}
			if ($this->db->table_exists('tbl_hallroom_booking') ){
				$allhallroom[$i] = $this->db->select('hb.totalamount,hb.hbid,c.firstname')->from("tbl_hallroom_booking hb")->join("customerinfo c","c.customerid=hb.customerid","left")->where("hb.booked_id",$bdetails[$i]->bookedid)->where("hb.customerid",$bdetails[$i]->cutomerid)->where("hb.status",1)->where("hb.payment_status",0)->get()->result();
			}else{
				$allhallroom = "";
			}
			if ($this->db->table_exists('tbl_bookParking') ){
				$allcarParking[$i] = $this->db->select('bp.total_price,bp.bookParking_id,c.firstname')->from("tbl_bookParking bp")->join("booked_info bi","bi.bookedid=bp.bookedid","left")->join("customerinfo c","c.customerid=bi.cutomerid","left")->where("bp.bookedid",$bdetails[$i]->bookedid)->where("c.customerid",$bdetails[$i]->cutomerid)->where("bp.status",1)->where("bp.paymentStatus",0)->get()->result();
			}else{
				$allcarParking = "";
			}

		}
		$data["poolbill"] = $allpoolbill;
		$data["restaurantbill"] = $allrestaurant;
		$data["hallroombill"] = $allhallroom;
		$data["carParkingBill"] = $allcarParking;
	    $data["bookingdata"] = $bdetails;
		$data["setting"] = $this->db->select("title,address,email,phone")->from("setting")->where("id",2)->get()->row();
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("title,address,email,phone,servicecharge")->from("setting")->get()->row();
		$data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
		$data["checkinrooms"] = $this->db->select('b.bookedid,b.room_no,c.firstname')->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("b.bookingstatus",4)->get()->result();
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["bookingsource"] = $this->roomreservation_model->get_all('*','tbl_booking_type_info','btypeinfoid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 

        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "checkoutreservation";   
        $this->load->view("room_reservation/checkoutreservation", $data); 
    }

    public function printCheckinbill($id = null)
    {
    	echo $id ; die();
    	
        $this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $bid = explode(",", $id);
		for($i=0; $i<count($bid); $i++){
			$bdetails[$i] = $this->roomreservation_model->detailbooking($bid[$i]);
			if ($this->db->table_exists('tbl_pool_booking') ){
				$allpoolbill[$i] = $this->db->select('p.*,c.firstname')->from("tbl_pool_booking p")->join("customerinfo c","c.customerid=p.custid","left")->where("p.entrydate>=",$bdetails[$i]->checkindate)->where("p.entrydate<=",$bdetails[$i]->checkoutdate)->where("custid",$bdetails[$i]->cutomerid)->where("p.status!=",3)->get()->result();
			}else{
				$allpoolbill = "";
			}
			if ($this->db->table_exists('customer_order') ){
				$allrestaurant[$i] = $this->db->select('b.bill_amount,co.order_id,c.firstname')->from("customer_order co")->join("customerinfo c","c.customerid=co.customer_id","left")->join("bill b", "b.order_id=co.order_id", "left")->where("CONCAT_WS(' ',co.order_date,co.order_time)>=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkindate)))->where("CONCAT_WS(' ',co.order_date,co.order_time)<=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkoutdate)))->where("co.customer_id",$bdetails[$i]->cutomerid)->where("co.order_status=",6)->where("b.bill_status=",0)->get()->result();
			}else{
				$allrestaurant = "";
			}
			if ($this->db->table_exists('tbl_hallroom_booking') ){
				$allhallroom[$i] = $this->db->select('hb.totalamount,hb.hbid,c.firstname')->from("tbl_hallroom_booking hb")->join("customerinfo c","c.customerid=hb.customerid","left")->where("hb.booked_id",$bdetails[$i]->bookedid)->where("hb.customerid",$bdetails[$i]->cutomerid)->where("hb.status",1)->where("hb.payment_status",0)->get()->result();
			}else{
				$allhallroom = "";
			}
			if ($this->db->table_exists('tbl_bookParking') ){
				$allcarParking[$i] = $this->db->select('bp.total_price,bp.bookParking_id,c.firstname')->from("tbl_bookParking bp")->join("booked_info bi","bi.bookedid=bp.bookedid","left")->join("customerinfo c","c.customerid=bi.cutomerid","left")->where("bp.bookedid",$bdetails[$i]->bookedid)->where("c.customerid",$bdetails[$i]->cutomerid)->where("bp.status",1)->where("bp.paymentStatus",0)->get()->result();
			}else{
				$allcarParking = "";
			}

		}
		$data["poolbill"] = $allpoolbill;
		$data["restaurantbill"] = $allrestaurant;
		$data["hallroombill"] = $allhallroom;
		$data["carParkingBill"] = $allcarParking;
	    $data["bookingdata"] = $bdetails;
		$data["setting"] = $this->db->select("title,address,email,phone")->from("setting")->where("id",2)->get()->row();
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["setting"] = $this->db->select("title,address,email,phone,servicecharge")->from("setting")->get()->row();
		$data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
		$data["checkinrooms"] = $this->db->select('b.bookedid,b.room_no,c.firstname')->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("b.bookingstatus",4)->get()->result();
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["bookingsource"] = $this->roomreservation_model->get_all('*','tbl_booking_type_info','btypeinfoid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 
        $data['module'] = "room_reservation";

        $content = $this->load->view('printcheckin', $data, true);

	    $dompdf = new Dompdf();

	    $dompdf->loadHtml($content);

	    $dompdf->setPaper('A4', 'portrait');

	    $dompdf->render();

	    $filename = 'invoice.pdf';

	    if (empty($pdf)) {
	    	//echo $content; exit;
	        $dompdf->stream($filename, array('Attachment' => 0));
	    } else {
	        return $content;
	    }
    }

    public function checkoutall($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
		$bid = explode(",", $id);
		for($i=0; $i<count($bid); $i++){
			 $bdetails[$i] = $this->roomreservation_model->detailbooking($bid[$i]);
			 if ($this->db->table_exists('tbl_pool_booking') ){
				$allpoolbill[$i] = $this->db->select('p.*,c.firstname')->from("tbl_pool_booking p")->join("customerinfo c","c.customerid=p.custid","left")->where("p.entrydate>=",$bdetails[$i]->checkindate)->where("p.entrydate<=",$bdetails[$i]->checkoutdate)->where("custid",$bdetails[$i]->cutomerid)->where("p.status!=",3)->get()->result();
			}else{
				$allpoolbill = "";
			}
			if ($this->db->table_exists('customer_order') ){
				$allrestaurant[$i] = $this->db->select('b.bill_amount,co.order_id,c.firstname')->from("customer_order co")->join("customerinfo c","c.customerid=co.customer_id","left")->join("bill b", "b.order_id=co.order_id", "left")->where("CONCAT_WS(' ',co.order_date,co.order_time)>=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkindate)))->where("CONCAT_WS(' ',co.order_date,co.order_time)<=",date('d-m-Y H:i:s', strtotime($bdetails[$i]->checkoutdate)))->where("co.customer_id",$bdetails[$i]->cutomerid)->where("co.order_status=",6)->where("b.bill_status=",0)->get()->result();
			}else{
				$allrestaurant = "";
			}
			if ($this->db->table_exists('tbl_hallroom_booking') ){
				$allhallroom[$i] = $this->db->select('hb.totalamount,hb.hbid,c.firstname')->from("tbl_hallroom_booking hb")->join("customerinfo c","c.customerid=hb.customerid","left")->where("hb.booked_id",$bdetails[$i]->bookedid)->where("hb.customerid",$bdetails[$i]->cutomerid)->where("hb.status",1)->where("hb.payment_status",0)->get()->result();
			}else{
				$allhallroom = "";
			}
			if ($this->db->table_exists('tbl_bookParking') ){
				$allcarParking[$i] = $this->db->select('bp.total_price,bp.bookParking_id,c.firstname')->from("tbl_bookParking bp")->join("booked_info bi","bi.bookedid=bp.bookedid","left")->join("customerinfo c","c.customerid=bi.cutomerid","left")->where("bp.bookedid",$bdetails[$i]->bookedid)->where("c.customerid",$bdetails[$i]->cutomerid)->where("bp.status",1)->where("bp.paymentStatus",0)->get()->result();
			}else{
				$allcarParking = "";
			}
		}
		$data["poolbill"] = $allpoolbill;
		$data["restaurantbill"] = $allrestaurant;
		$data["hallroombill"] = $allhallroom;
		$data["carParkingBill"] = $allcarParking;
	    $data["bookingdata"] = $bdetails;
	 //   print_r($data["bookingdata"]);die();
		$data["setting"] = $this->db->select("title,address,email,phone,servicecharge")->from("setting")->get()->row();
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
	    $data["checkinrooms"] = $this->roomreservation_model->get_all('room_no,cutomerid','booked_info','bookedid');
	    $data["bookingtype"] = $this->roomreservation_model->get_all('*','bookingtype','booktypeid');
	    $data["bookingsource"] = $this->roomreservation_model->get_all('*','tbl_booking_type_info','btypeinfoid');
	    $data["roomdetails"] = $this->roomreservation_model->get_all('roomid,roomtype','roomdetails','roomid');
	    $data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
	    $data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 

        #
        #pagination ends
        #   
        $data['module'] = "room_reservation";
        $data['page']   = "checkoutall";   
        $this->load->view("room_reservation/checkoutall", $data); 
    }
    public function customerpay($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
	    $data["bookingdata"] = $this->roomreservation_model->detailbooking($id);
        $data['module'] = "room_reservation";
        $data['page']   = "custdetails";   
        $this->load->view("room_reservation/customerdetails", $data); 
    }
    
    
    
    
    
    
    
    
	public function submitcheckout($bookedid){
	   // print_r($_POST);die();
		$bid = explode(",", $bookedid);
		$creditamount = $this->input->post("creditamount", true);
	  
		$refunddamt = $this->input->post("refunddamt", true);
		$disamount = $this->input->post("disamount", true);
		$allcomplementarycharge = $this->input->post("allcomplementarycharge", true);
		$allbpccharge = $this->input->post("allbpccharge", true);
			$additional_reason = $this->input->post("additional_reason", true);
		$additionalcharge = $this->input->post("additionalcharge", true);
		$additional_remarks = $this->input->post("additional_remarks", true);
		$specialdis = $this->input->post("specialdis", true);
	$discount_cgst = $this->input->post("discount_cgst", true);
	$discount_sgst = $this->input->post("discount_sgst", true);
		$tax = $this->input->post("tax", true);
		$totalroomrent = $this->input->post("roomRent", true);
		$tax_with_amount = $this->input->post("tax_with_amount", true);
	$collectedamt = $this->input->post("collectedamt", true);
	$rentafterdiscount = $this->input->post("rent", true);

		$poolbill = $this->input->post("poolbill", true);
		$restbill = $this->input->post("restbill", true);
		$poolid = $this->input->post("poolid", true);
		$orderid = $this->input->post("orderid", true);
		$hallid = $this->input->post("hallid", true);
		$parking_id = $this->input->post("parking_id", true);
		$parkingbill = $this->input->post("parkingbill", true);
		$bedrate = $this->input->post("bc", true);
        $personrate = $this->input->post("pc", true);
        $childrate = $this->input->post("cc", true);
		$rid = $orderid;
		$mrid = explode(",,", $rid);
		$netamount = 0;
		if(!empty($orderid)){
			for($i=0;$i<count($mrid);$i++){
				$srid = explode(",", $mrid[$i]);
				for($j=0;$j<count($srid);$j++){
					$ritems[$i][$j]     = $this->roomreservation_model->ritemdatasingle($srid[$j]);
				}
			}
			for($i=0; $i<count($ritems); $i++){
				for($j=0; $j<count($ritems[$i]); $j++){
					$netbill = $ritems[$i][$j];
				}
			}
			foreach($netbill->details as $value){
				$netamount += $value->subtotal;
			}
		}
		$nod = $this->input->post("nod", true);
		$taxamount = $this->input->post("taxamount", true);
		$scharge = $this->input->post("scharge", true);
		$payableamt = $this->input->post("payableamt", true);
		
		$alladvanceamount = $this->input->post("alladvanceamount", true);
		
		$paymentmode = $this->input->post("paymentmode", true);
		$paymentamount = $this->input->post("paymentamount", true);
		$bankname = $this->input->post("bankname", true);
		$cardno = $this->input->post("cardno", true);
		$taxdetail = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$taxname="";
		$rate="";
		foreach($taxdetail as $taxinfo){
			$taxname .= $taxinfo->taxname.",";
			$rate .= $taxinfo->rate.",";
		}
		$mspoolid = explode(",,", $poolid);
		$msorderid = explode(",,", $orderid);
		$mshallid = explode(",,", $hallid);
		$allorderbill = 0;
		$restscharge = 0;
		$allpoolbill = 0;
		$allhallbill = 0;
		for($i=0; $i<count($bid); $i++){
			$totalbill = $this->db->select("total_price,booking_source,commissionamount,cutomerid,booked_details.advance_amount")->from("booked_info")->join("booked_details","booked_details.bookedid=booked_info.bookedid","left")->where("booked_info.bookedid",$bid[$i])->get()->row();
			$bill = $totalbill->total_price*(trim($nod)==0?1:trim($nod));
			if($totalbill->advance_amount > 0){
			    date_default_timezone_set('Asia/Kolkata');
                $advance_paydate = date("d-m-Y H:i:s");
			}
			
		
		$overalltotal = isset($payableamt, $alladvanceamount) ? ($payableamt + $alladvanceamount) : 0;
          
          
            $checkoutdata = array(
                'bookedid' => $bid[$i],
                'paid_amount' => $collectedamt,
                'overalltotal' => $overalltotal,
                'bookingstatus' => 5,
                'paid_paydate' => date("d-m-Y H:i:s"),
                'advance_paydate' => $advance_paydate,
                'bc' => $bedrate,
                'pc' => $personrate,
                'cc' => $childrate
            );
			$result = $this->db->where("bookedid",$bid[$i])->update("booked_info",$checkoutdata);
			             //   echo $this->db->last_query();die();

			
			
		// $additionalTaxdata = array(
        //      'additional_cgst' => $this->input->post("Additionalcgst", true),
        //      'additional_sgst' => $this->input->post("Additionalsgst", true),
        //      'additional_reason' => $this->input->post("additional_reason", true),
        //      'additional_remarks'  =>$this->input->post("additional_remarks", true)
        //     );
        //     $additionalTaxresult = $this->db->where("bookedid",$bid[$i])->update("booked_details",$additionalTaxdata);
			if($result && $totalbill->booking_source){
				$balance = $this->db->select("balance")->from("tbl_booking_type_info")->where("btypeinfoid",$totalbill->booking_source)->get()->row();
				$newbalance = $balance->balance+$totalbill->commissionamount;
				$bl = array(
					'balance' => $newbalance
				);
				$this->db->where("btypeinfoid",$totalbill->booking_source)->update("tbl_booking_type_info",$bl);
			}
			
			if ($disamount > 0) {
                $discount_data = array(
                    'discountamount' => $disamount,
                    'cgst' => $this->input->post("discount_cgst", true),
                    'sgst' => $this->input->post("discount_sgst", true)
                );
            
                $result = $this->db->where("bookedid", $bid[$i])->update("booked_details", $discount_data);
           //   echo $this->db->last_query();
               $discount_data1 = array(
                    'roomrate' => $totalroomrent,
                    'total_price' => $overalltotal,
                    'paid_amount' => $collectedamt,
                    'overalltotal' => $overalltotal,
                ); 
            
                $result = $this->db->where("bookedid", $bid[$i])->update("booked_info", $discount_data1);
                // echo $this->db->last_query();die();
            }

				
			
			if($result && ($creditamount || $totalbill->advance_amount)){
				//customer balance reduction for credit amount
				$credit = $this->db->select("balance")->from("customerinfo")->where("customerid",$totalbill->cutomerid)->get()->row();
				$newcredit = $credit->balance-(!empty($creditamount)?$creditamount:0)-$totalbill->advance_amount;
				$cramount = array(
					'balance' => $newcredit
				);
				$this->db->where("customerid",$totalbill->cutomerid)->update("customerinfo",$cramount);
			}
			if(!empty($poolid)){
				$spoolid = explode(",", $mspoolid[$i]);
				for($j=0; $j<count($spoolid); $j++){
					$spoollbill = $this->db->select("total_amount")->from("tbl_pool_booking")->where("pbookingid", $spoolid[$j])->get()->row();
					$allpoolbill += $spoollbill->total_amount;
				}
			}
			if(!empty($orderid)){
				$sorder = explode(",", $msorderid[$i]);
				for($k=0; $k<count($sorder); $k++){
					$sorderlbill = $this->db->select("totalamount")->from("customer_order")->where("order_id", $sorder[$k])->get()->row();
					$sbill = $this->db->select("service_charge")->from("bill")->where("order_id", $sorder[$k])->get()->row();
					$allorderbill += $sorderlbill->totalamount;
					$restscharge += $sbill->service_charge;
				}
			}
			if(!empty($hallid)){
				$shallid = explode(",", $mshallid[$i]);
				for($j=0; $j<count($shallid); $j++){
					$shallbill = $this->db->select("totalamount")->from("tbl_hallroom_booking")->where("hbid", $shallid[$j])->get()->row();
					$allhallbill += $shallbill->totalamount;
				}
			}
			$bc=$this->input->post("bedcharge_rate", true);
			$pc=$this->input->post("personcharge_rate", true);
			$cc=$this->input->post("childcharge_rate", true);
            $tax_with_amount = str_replace("\n", "", $tax_with_amount);
            $tax_with_amount = str_replace(" ", "", $tax_with_amount);
    
            $extraBPC = $bedrate + $personrate + $childrate;
			$btax = array(
				'bookedid' => $bid[$i],
				'taskname' => trim($taxname,","),
				'rate' => trim($rate,","),
				'scharge' => $scharge,
				'credit' => $creditamount,
				 'tax'  => $tax,
				 'totalroomrent'  => $totalroomrent,
				 'tax_with_amount'  => $tax_with_amount,
				 'bc'  => $bc,
				 'pc'  => $pc,
				 'cc'  => $cc,
		
				'complementary' => $allcomplementarycharge,
				'additional_charges' => $additionalcharge,
				'extrabpc' => $extraBPC,
				'ex_discount' => $disamount,
				'swimming_pool' => $allpoolbill,
				'restaurant' => $allorderbill,
				'hallroom' => $allhallbill,
				'car_parking' => $parkingbill,
				'special_discount' => $specialdis,
				'checkoutdate' => date("d-m-Y H:i:s"),
			);
			if($result){
				

				$this->db->insert("tbl_postedbills",$btax);
				
				if(!empty($poolid)){
					for($j=0; $j<count($spoolid); $j++){
						$paid = $this->db->select("total_amount")->from("tbl_pool_booking")->where("pbookingid", $spoolid[$j])->get()->row();
						$this->db->where("pbookingid", $spoolid[$j])->update("tbl_pool_booking", array('paid_amount'=>$paid->total_amount,'status'=>1));
					}
				}
				if(!empty($orderid)){
					for($k=0; $k<count($sorder); $k++){
						$paidbill = $this->db->select("totalamount")->from("customer_order")->where("order_id", $sorder[$k])->get()->row();
						$this->db->where("order_id", $sorder[$k])->update("customer_order", array('customerpaid'=>$paidbill->totalamount,'order_status'=>4));
						$this->db->where("order_id", $sorder[$k])->update("bill", array('payment_method_id'=>0,'bill_status'=>1));
					}
				}
				if(!empty($hallid)){
					for($j=0; $j<count($shallid); $j++){
						$paidhall = $this->db->select("totalamount")->from("tbl_hallroom_booking")->where("hbid", $shallid[$j])->get()->row();
						$this->db->where("hbid", $shallid[$j])->update("tbl_hallroom_booking", array('paid_amount'=>$paidhall->totalamount,'payment_status'=>1));
					}
				}
				if(!empty($parking_id)){
					$sparking_id = explode(",", $parking_id);
					$this->db->where_in("bookParking_id", $sparking_id)->update("tbl_bookParking", array('status'=>0));
				}
				$roomno = $this->db->select("room_no")->from("booked_info")->where("bookedid", $bid[$i])->get()->row();
				$singleroom = explode(",", $roomno->room_no);
				for($l=0;$l<count($singleroom); $l++){
					$this->db->where("roomno", $singleroom[$l])->update("tbl_roomnofloorassign", array("status"=>1));
				}

			}
		}
			if($result){
				if($paymentamount>0){
					//insert payment
					$payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
					if(!empty($payinfo)){
						$invoicenum=$payinfo->invoice;
            			$currentYear = date('Y');
            			$currentMonth = date('m');
            			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            			$prefix = "FO" . $financialYearStart;
            			$lastBookedId = isset($bookno) && !empty($bookno) ? $bookno : $prefix . "0000";
            			$numericPart = intval(substr($lastBookedId, strlen($prefix)));
            			$nextNumericPart = $numericPart + 1;
            			$invoice_no = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
					}
					
					
				// 	$nextno=$invoicenum+1;
				// 	$bk_length = strlen((int)$nextno); 
				// 	$bkstr = '000000';
				// 	$bknumber = substr($bkstr, $bk_length); 
				// 	$invoice_no = $bknumber.$nextno;
					$newdate = date("d-m-Y H:i:s");
					$saveid = $this->session->userdata('id');
					$singlepayment = explode(",", $paymentmode);
				$singlepayment=	str_replace("undefined","",$singlepayment);
					$singleamount = explode(",", $paymentamount);
					$singlebankname = explode(",", $bankname);
					$singlecardno = explode(",", $cardno);
					for($i=0, $j=0; $i<count($singlepayment); $i++){
					    	echo $singlepayment[i];
						if($i==(count($singlepayment)-1)){
							//change ammount refunded to customer
							$singleamount[$i] -= (!empty($refunddamt)?$refunddamt:0);
						}
						$postData = array(
							'bookedid' 	         	 => $bid[0],
							'invoice' 	             => $invoice_no,
							'paydate' 	             => $newdate,
							'paymenttype' 	         => $singlepayment[$i],
							'paymentamount' 	     => $singleamount[$i],
						//	'details' 	     		 => "Card/Account No: ".$singlecardno[$i]." Bank Name: ".$singlebankname[$i],
							'book_type' 	     	 => 0,
							);
						//	echo $singlepayment[$i];
							 if ($singlepayment[$i] == "UPI") {
                    $upi_referenceno = $this->input->post('upi_referenceno', TRUE);
                    $postData['details'] = "UPI Reference No: " . $upi_referenceno;
                } elseif ($singlepayment[$i] == "RTGS") {
                    $rtgs_referenceno = $this->input->post('rtgs_referenceno', TRUE);
                    $postData['details'] = "RTGS Reference No: " . $rtgs_referenceno;
                } elseif ($singlepayment[$i] == "Cheque") {
                    $cheque_no = $this->input->post('cheque_no', TRUE);
                    $cheque_date = $this->input->post('cheque_date', TRUE);
                    $postData['details'] = "Cheque No: " . $cheque_no . " Date: " . $cheque_date;
                } elseif ($singlepayment[$i] == "Bank Payment") {
                    $bankname = $this->input->post('bankname', TRUE);
                      $accountnumber = $this->input->post('accountnumber', TRUE);
                    $postData['details'] = "Bank Payment: " . $bankname."-".$accountnumber;
                } elseif ($singlepayment[$i] == "Card Payment") {
                    $cardno = $this->input->post('cardno', TRUE);
                    $expirydate = $this->input->post('expirydate', TRUE);
                    $ccv = $this->input->post('ccv', TRUE);
                    $postData['details'] = "Card Number: " . $cardno . " Expiry date: " . $expirydate . " CCV: " . $ccv;
                }elseif ($singlepayment[$i] == "Cash Payment") {
                   
                    $postData['details'] = "Cash Payment";
                }
                 $postData['details']=	str_replace("undefined","", $postData['details']);
						$this->db->insert('tbl_guestpayments', $postData);
					//	echo '3'.$this->db->last_query(); die();
						
						if($singlepayment[$i]=="Bank Payment"){
							$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$singlebankname[$j]'");
							$row = $query->row();
							$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
							if(empty($headcode)){
								$coa = $this->roomreservation_model->headcode(4,1020102);
								if($coa->HeadCode!=NULL){
									$headcode=$coa->HeadCode+1;
								}
								else{
									$headcode="102010201";
								}
								//insert Coa for Customer Receivable
								$postData1['HeadCode']   	=$headcode;
								$postData1['HeadName']   	=$singlebankname[$j];
								$postData1['PHeadName']   	='Cash At Bank';
								$postData1['HeadLevel']   	='4';
								$postData1['IsActive']  	='1';
								$postData1['IsTransaction'] ='1';
								$postData1['IsGL']   		='0';
								$postData1['HeadType']  	='A';
								$postData1['IsBudget'] 		='0';
								$postData1['IsDepreciation']='0';
								$postData1['DepreciationRate']='0';
								$postData1['CreateBy'] 		=$saveid;
								$postData1['CreateDate'] 	=$newdate;
								$this->db->insert('acc_coa',$postData1);
								//end
							}
							$narration = 'Cash in Bank Debited For '.$singlebankname[$j].' Invoice#'.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, $headcode, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
							$j++;
						}
						else if($singlepayment[$i]=="SSLCommerz"){
							$narration = 'Cash in SSLCOMMERZ Debited For Invoice#'.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 102010302, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
						}
						else if($singlepayment[$i]=="Cash Payment"){
							$narration = 'Cash in Hand Debited For Invoice#'.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 1020101, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
						}
						else if($singlepayment[$i]=="Paypal"){
							$narration = 'Cash in Paypal Debited For Invoice#'.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 102010301, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
						}
						else if($singlepayment[$i]=="Card Payment"){
							$narration = 'Cash in Card Debited For Invoice#'.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 102010304, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
						}
						else{
							$path = 'application/modules/';
							$map  = directory_map($path);
							$HmvcMenu   = array();
							if (is_array($map) && sizeof($map) > 0)
							foreach ($map as $key => $value) {
								$env = str_replace("\\", '/', $path.$key.'assets/data/env'); 
								$transaction = str_replace("\\", '/', $path.$key.'controllers/transaction.php'); 
								if (file_exists($env)) {
									if (file_exists($transaction)) {
										@include($transaction);
										if($singlepayment[$i]==$paymentMethod){
											$narration = 'Cash in '.$paymentMethod.' Debited For Invoice#'.$invoice_no;
											transaction($invoice_no, 'CIV', $newdate, $headCode, $narration, $singleamount[$i], 0, 0, 1, $saveid, $newdate, 1);
										}
									}
								}
							}
							$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020103%' And HeadName LIKE '$singlepayment[$i]'");
							$row = $query->row();
							$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
						}
					}

					//Customer debit for Rent Value
					$narration = 'Customer debit for Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102030101, $narration, $payableamt, 0, 0, 1, $saveid, $newdate, 1);
					if($payableamt>0){
						//Hotel Owner credit for Hotel Rent Value
						$narration = 'Hotel Credited for Hotel Rent Invoice# '.$invoice_no;
						$s_amount = $payableamt-$allpoolbill-$netamount-$taxamount-($allorderbill-$netamount)-$scharge;
						transaction($invoice_no, 'CIV', $newdate, 30301, $narration, 0, $s_amount, 0, 1, $saveid, $newdate, 1);
						//Hotel Owner credit for Hotel Service Charge
						$narration = 'Hotel Credited for Hotel Service Charge Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 30304, $narration, 0, $scharge, 0, 1, $saveid, $newdate, 1);
					}
					//Hotel Owner credit for Swimming Pool Rent Value
					if($allpoolbill>0){
						$narration = 'Hotel Credited for Swimming Pool Rent Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 30302, $narration, 0, $allpoolbill, 0, 1, $saveid, $newdate, 1);
					}
					//Hotel Owner credit for Restauramt food Value
					if($netamount>0){
						$narration = 'Hotel Credited for Restaurant Food Invoice# '.$invoice_no;
						$n_amount = $netamount-$restscharge;
						transaction($invoice_no, 'CIV', $newdate, 30303, $narration, 0, $n_amount, 0, 1, $saveid, $newdate, 1);
						//restaurant s charge
						$narration = 'Hotel Credited for Restaurant Food Service Charge Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 30304, $narration, 0, $restscharge, 0, 1, $saveid, $newdate, 1);
					}
					// Customer Credit for paid amount.
					$narration = 'Customer Credit for Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102030101, $narration, 0, $payableamt, 0, 1, $saveid, $newdate, 1);

					//Debited tax in tax recievable
					$narration = 'Hotel Debited For Hotel Room TAX Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 1020204, $narration, $taxamount, 0, 0, 1, $saveid, $newdate, 1);

					//Credited tax in tax payable
					$narration = 'Hotel Credited For Hotel Room TAX Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 5020303, $narration, 0, $taxamount, 0, 1, $saveid, $newdate, 1);
					$resttax=0;
					if($netamount>0){
						$resttax = $allorderbill-$netamount;
						//Debited tax in tax recievable for restaurant
						$narration = 'Hotel Debited For Restaurant TAX Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 1020204, $narration, $resttax, 0, 0, 1, $saveid, $newdate, 1);

						//Credited tax in tax payable for restaurant
						$narration = 'Hotel Credited For Restaurant TAX Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 5020303, $narration, 0, $resttax, 0, 1, $saveid, $newdate, 1);
					}
				}else{
					$creditedRent = $this->db->select("at.Credit,at.ID,VNo")->from("acc_transaction at")->join("tbl_guestpayments tg","tg.invoice=at.VNo","left")->where("COAID",30301)->where("tg.bookedid",$bid[0])->get()->row();
					if(!empty($creditedRent)){
						$invoice_no = $creditedRent->VNo;
						$newdate = date("d-m-Y H:i:s");
						$saveid = $this->session->userdata('id');
						$amount = $creditedRent->Credit - $taxamount - $scharge;
						if($amount>0){
							//Debited tax in tax recievable
							$narration = 'Hotel Debited For Hotel Room TAX Invoice# '.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 1020204, $narration, $taxamount, 0, 0, 1, $saveid, $newdate, 1);

							//Credited tax in tax payable
							$narration = 'Hotel Credited For Hotel Room TAX Invoice# '.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 5020303, $narration, 0, $taxamount, 0, 1, $saveid, $newdate, 1); 
							//Hotel Owner credit for Hotel Service Charge
							$narration = 'Hotel Credited for Hotel Service Charge Invoice# '.$invoice_no;
							transaction($invoice_no, 'CIV', $newdate, 30304, $narration, 0, $scharge, 0, 1, $saveid, $newdate, 1);
							//removing tax and service charge from rent
							$this->db->where("ID", $creditedRent->ID)->update("acc_transaction",array('Credit'=>$amount));
						}
					}
				}
				
				$file = $this->viewdetailsprint($bid[0],'pdf');
        		
        		$dompdf = new Dompdf();
				$dompdf->loadHtml($file);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();

				$filename = 'invoice_' . $bid[0] . '.pdf';
				$dir = 'assets/pdf/';
				if (!is_dir($dir)) {
				    mkdir($dir, 0755, true);
				}
				$output = $dompdf->output();
				$file_path = $dir . $filename;
				file_put_contents($file_path, $output);

        		// $file_path = $this->pdfgenerator->generate_pdf($bid[0], $file);
				//sending email to customer
				$binfo = $this->db->select("b.booking_number,b.room_no,b.total_price,c.firstname,c.email")->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("bookedid",$bid[0])->get()->row();
				$this->email_send($binfo,5,$file_path);
				//end
				echo json_encode(['file_url' => base_url($file_path), 'success' => 'Checkout Successfully']);
			} else {
				echo json_encode(['error' => 'Failed to send email. Please try again.']);
			}

	}
    public function cancelreservation($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();		
        $data['title']    = display('room_reservation'); 
        $data['module'] = "room_reservation";
		$data["paymentdetails"] = $this->roomreservation_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
        $data['bookedid']   = $id;   
        $data['page']   = "cancelreservation";   
        $this->load->view("room_reservation/cancelreservation", $data); 
    }
	public function bookingSource(){
		$booking_type = $this->input->post('booking_type', TRUE);
		$bSource = $this->roomreservation_model->readall('*','tbl_booking_type_info','btypeinfoid',array('booking_type'=>trim($booking_type)));
		$data = array(
			'soruce' => $bSource,
		  );
		  echo json_encode($data);
	}
	public function bsourcerate(){
		$booking_source = $this->input->post('booking_source', TRUE);
		$bSource = $this->roomreservation_model->readone('commissionrate','tbl_booking_type_info',array('btypeinfoid'=>trim($booking_source)));
		$data = array(
			'commissionrate' => $bSource->commissionrate,
		  );
		  echo json_encode($data);
	}
	public function getroomno(){
		$room_type = $this->input->post('room_type', TRUE);
		$allroom = $this->roomreservation_model->read2('roomno','tbl_roomnofloorassign','roomno',array('roomid'=>$room_type),array('status'=>1));
		$typename = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$complementary = $this->roomreservation_model->read2('complementaryname,rate','tbl_complementary','complementary_id',array('roomtype'=>$typename->roomtype),null);
		$data = array(
			'roomno' => $allroom,
			'complementary' => $complementary,
		  );
		  echo json_encode($data);
	}
	public function checknewroom(){
		$room_type = $this->input->post('room_type', TRUE);
		$bookingid = $this->input->post('bookingid', TRUE);
		$checkin=$this->input->post('datefilter1',true);
		$checkout=$this->input->post('datefilter2',true);
		$status="bookingstatus!=1 AND bookingstatus!=5";
		$croom ="FIND_IN_SET(".$room_type.",roomid)";
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where($status)->where("$croom !=",0)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where($status)->where("$croom !=",0)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where($status)->where("$croom !=",0)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$room_type)->get()->row();
		$roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$room_type)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$room_type)->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit)&&empty($check)){
				$allroom=$gtroomno;
				$data['isfound']=0;
			}
		else{
			$bookedroom="";
			if(!empty($exits)){
			foreach($exits as $booked){
				$bookedroom.=$booked->room_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->room_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->room_no.',';
				}
			}
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom1=$totalroom1->allroom;
		$allbokedroom2=$totalroom2->allroom;
		$allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
		$allfreeroom=$totalroomfound->totalroom;
			//	if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					if(!empty($output)){
					$allroom=$output;
					$data['isfound']='1';
						}
					else{
						$allroom='';
						$data['isfound']='2';
						}
			//	}
				// else{
				// 	$allroom='';
				// 	$data['isfound']='2';
				// 	}
			}
		$typename = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$complementary = $this->roomreservation_model->read2('complementaryname,rate','tbl_complementary','complementary_id',array('roomtype'=>$typename->roomtype),null);

		$data['chargeinfo']=$this->roomreservation_model->chargeinfo();
		$availableroom = explode(",",$allroom);
		$room_list = explode(",",$gtroomno);
		$free_room = array_intersect($room_list, $availableroom);
		$data = array(
			'roomno' => array_values($free_room),
			'complementary' => $complementary,
		  );
		  echo json_encode($data);

	 }
	public function getcapacity(){
		$start = $this->input->post('start', TRUE);
		$end = $this->input->post('end', TRUE);
		$start_date = strtotime($start);
		$end_date = strtotime($end);
		$difference = $end_date - $start_date;
		$days =  ceil($difference / (60 * 60 * 24));
		$roomno = $this->input->post('roomno', TRUE);
		$roomid = $this->db->select("roomid")->from("tbl_roomnofloorassign")->where("roomno",$roomno)->get()->row();
		$capacity = $this->roomreservation_model->readone('child_limit,capacity,rate,exbedcapability','roomdetails',array('roomid'=>$roomid->roomid));
		$newDate = date('d-m-Y', strtotime($start . ' -1 day'));
		$totalOffer = 0;
		for($i=0; $i<$days; $i++){
			$newDate = date('d-m-Y', strtotime($newDate . ' +1 day'));
			$offer_amount = $this->roomreservation_model->readone('offer','tbl_room_offer',array('roomid'=>$roomid->roomid),array('offer_date'=>$newDate));
			if(empty($offer_amount)){
				$offer = 0;
			}else{
				$offer = $offer_amount->offer;
			}
			$totalOffer += $offer;
		}
		$data = array(
			'capacity' => $capacity->capacity,
			'price' => $capacity->rate * $days,
			'room_amount' => $capacity->rate,
			'offer_amount' => $totalOffer,
			'child' => $child_limit,
			'excapacity' => $capacity->exbedcapability,
		);
		  echo json_encode($data);
	}





	public function bedprice(){
		$room_type = $this->input->post('room_type', TRUE);
		$bed = $this->input->post('bed', TRUE);
		
		$startDateStr = $this->input->post('start', TRUE); 
		$endDateStr = $this->input->post('end', TRUE); 
		
		$start_date = strtotime($startDateStr);
		$end_date = strtotime($endDateStr);
		
		$difference = $end_date - $start_date;
		$days =  ceil($difference / (60 * 60 * 24));
	
        
		$bedprice = $this->db->select("bedcharge")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$data = array(
			'bedrate' => ($bedprice->bedcharge * $bed) * $days

		  );
		  echo json_encode($data);  
 	}



	public function personprice(){
		$room_type = $this->input->post('room_type', TRUE);
		$person = $this->input->post('person', TRUE);
		
		$startDateStr = $this->input->post('start', TRUE); 
		$endDateStr = $this->input->post('end', TRUE); 
		
		$start_date = strtotime($startDateStr);
		$end_date = strtotime($endDateStr);
		
		$difference = $end_date - $start_date;
		$days =  ceil($difference / (60 * 60 * 24));
		
		$personprice = $this->db->select("personcharge")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$data = array(
			'personrate' => ($personprice->personcharge * $person) * $days
		  );
		  echo json_encode($data);
	}

 
	public function childprice(){
		$room_type = $this->input->post('room_type', TRUE);
		$child = $this->input->post('child', TRUE);
		
		$startDateStr = $this->input->post('start', TRUE); 
		$endDateStr = $this->input->post('end', TRUE); 
		
		$start_date = strtotime($startDateStr);
		$end_date = strtotime($endDateStr);
		
		$difference = $end_date - $start_date;
		$days =  ceil($difference / (60 * 60 * 24));
		
		$childprice = $this->db->select("childcharge")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$data = array(
            'childrate' => ($childprice->childcharge * $child) * $days
		  );
		  echo json_encode($data);
	}









	public function imageupload(){
		$image = $this->fileupload->do_upload(
			'assets/img/customer/',
			'img'
		);

		// if image is uploaded then resize the image
		if ($image !== false && $image != null) {
			$this->fileupload->do_resize(
				$image, 
				500,
				500
			);
		}
		//if image is not uploaded
		if ($image === false) {
			echo "<h5>Failed</h5>Invalid Image Format";
            exit;
		}
		echo $image;

	}
	
	
	
	
	
	
	
	
	
	
	public function newBooking(){
	
			//reservation details
		$bookingid = $this->input->post('bookingid', TRUE);
		$datefilter1 = $this->input->post('datefilter1', TRUE);
		$datefilter2 = $this->input->post('datefilter2', TRUE);
			$rentafterdiscount = $this->input->post('rent', TRUE);
		$booking_type = $this->input->post('booking_type', TRUE);
		$booking_source = $this->input->post('booking_source', TRUE);
		$bsorurce_no = $this->input->post('bsorurce_no', TRUE);
		$arrival_from = $this->input->post('arrival_from', TRUE);
		$pof_visit = $this->input->post('pof_visit', TRUE);
		$booking_remarks = $this->input->post('booking_remarks', TRUE);
		//room details
		$room_type = $this->input->post('room_type', TRUE);
		$roomno = $this->input->post('roomno', TRUE);
		$adults = $this->input->post('adults', TRUE);
		$children = $this->input->post('children', TRUE);
		$bed = $this->input->post('bed', TRUE);
		$amount1 = $this->input->post('amount1', TRUE);
		$person = $this->input->post('person', TRUE);
		$amount2 = $this->input->post('amount2', TRUE);
		$child = $this->input->post('child', TRUE);
		$amount3 = $this->input->post('amount3', TRUE);
		$extrastart = $this->input->post('extrastart', TRUE);
		$extraend = $this->input->post('extraend', TRUE);
		$rent = $this->input->post('rent', TRUE);
		$discount_price = $this->input->post('discount_price', TRUE);
		$complementary = $this->input->post('complementary', TRUE);
		$complementaryprice = $this->input->post('complementaryprice', TRUE);
         $c_reservation = $this->input->post('c_reservation', TRUE);
        $c_reg = $this->input->post('c_reg', TRUE);
        $c_roomno = $this->input->post('c_roomno', TRUE);
        $c_full_name = $this->input->post('c_full_name', TRUE);
        $c_company_taname = $this->input->post('c_company_taname', TRUE);
        $c_address = $this->input->post('c_address', TRUE);
        $c_pincode = $this->input->post('c_pincode', TRUE);
        $c_res_off = $this->input->post('c_res_off', TRUE);
        $c_moblie = $this->input->post('c_moblie', TRUE);
        $c_res = $this->input->post('c_res', TRUE);
        $c_dob = $this->input->post('c_dob', TRUE);
        $c_email = $this->input->post('c_email', TRUE);
        $c_vehicleno = $this->input->post('c_vehicleno', TRUE);
        $c_nationality = $this->input->post('c_nationality', TRUE);
        $c_noofperson = $this->input->post('c_noofperson', TRUE);
        $c_adults = $this->input->post('c_adults', TRUE);
        $c_children = $this->input->post('c_children', TRUE);
        $c_arrival = $this->input->post('c_arrival', TRUE);
        $c_atime = $this->input->post('c_atime', TRUE);
        $c_departutedate = $this->input->post('c_departutedate', TRUE);
        $c_dtime = $this->input->post('c_dtime', TRUE);
        $c_aform = $this->input->post('c_aform', TRUE);
        $c_pov = $this->input->post('c_pov', TRUE);
        $c_proceedingto = $this->input->post('c_proceedingto', TRUE);
        $c_cash = $this->input->post('modeofpayment', TRUE);
        $c_passport = $this->input->post('c_passport', TRUE);
        $c_country = $this->input->post('c_country', TRUE);
        $c_issued = $this->input->post('c_issued', TRUE);
        $c_poi = $this->input->post('c_poi', TRUE);
        $c_vaild = $this->input->post('c_vaild', TRUE);
        $visa_validtill = $this->input->post('visa_validtill', TRUE);
        $c_pdfi = $this->input->post('c_pdfi', TRUE);
        $c_weii = $this->input->post('c_weii', TRUE);
        $c_amount = $this->input->post('c_amount', TRUE);
		//payment details
		$discountreason = $this->input->post('discountreason', TRUE);
		$discountamount = $this->input->post('discountrate', TRUE);
		$commissionrate = $this->input->post('commissionrate', TRUE);
		$commissionamount = $this->input->post('commissionamount', TRUE);
		$paymentmode = $this->input->post('paymentmode', TRUE);
		$advanceamount = $this->input->post('advanceamount', TRUE);
		$advanceremarks = $this->input->post('advanceremarks', TRUE);
		   $currentYear = date('Y');
                         $currentMonth = date('m');
                         $financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
                     if (!isset($_SESSION['payment_counters'])) {
                     $_SESSION['payment_counters'] = 1;
                     }
                     $payment_id = $_SESSION['payment_counters']++;
                     $value = htmlspecialchars("ADV" . $financialYearStart . sprintf('%04d', $payment_id), ENT_QUOTES, 'UTF-8');
        $advanceid =  $value;
		//user details
		$userid = $this->input->post('userid', TRUE);
		$alluserid = explode(",", trim($userid));
		$name = $this->input->post('name', TRUE);
		$allname = explode(",", trim($name));
// 		echo $allname;
// 		echo "<br/>";
		$mobile = $this->input->post('mobile', TRUE);
		$allmobile = explode(",", trim($mobile));
		$email = $this->input->post('email', TRUE);
		$allemail = explode(",", trim($email));
		$lastname = $this->input->post('lastname', TRUE);
		$alllastname = explode(",", trim($lastname));
		$gender = $this->input->post('gender', TRUE);
		$allgender = explode(",",trim($gender));
		$father = $this->input->post('father', TRUE);
		$occupation = $this->input->post('occupation', TRUE);
		$dob = $this->input->post('dob', TRUE);
		$anniversary = $this->input->post('anniversary', TRUE);
		$pitype = $this->input->post('pitype', TRUE);
		$allpitype = explode(",", trim($pitype));
		$pid = $this->input->post('pid', TRUE);
		$allpid = explode(",", trim($pid));
		$imgfront = $this->input->post('imgfront', TRUE);
		$allimgfront = explode(",", trim($imgfront));
		$imgback = $this->input->post('imgback', TRUE);
		$allimgback = explode(",", trim($imgback));
		$imgguest = $this->input->post('imgguest', TRUE);
		$allimgguest = explode(",", trim($imgguest));
		$contacttype = $this->input->post('contacttype', TRUE);
		$state = $this->input->post('state', TRUE);
		$city = $this->input->post('city', TRUE);
		$zipcode = $this->input->post('zipcode', TRUE);
		$address = $this->input->post('address', TRUE);
		//end
		$allroom = explode(",",trim($roomno));
		$price = explode(",",trim($rent));
		$totalprice=0;
		for($i=0;$i<count($price);$i++){
			$totalprice+=$price[$i];
		}
		$totalprice -= !empty($discountamount)?$discountamount:0;

		$extradays="";
		$exstart = explode(",", $extrastart);
		$exend = explode(",", $extraend);
		for($r=0;$r<count($allroom);$r++){
			$start_date = strtotime($exstart[$r]);
			$end_date = strtotime($exend[$r]);
			$difference = $end_date - $start_date;
			$extradays .=  ceil($difference / (60 * 60 * 24)).",";
		}
		$allextradays = trim($extradays,",");

		//user details insert
		if($bookingid){
			$bookedid = $this->db->select("full_guest_name,cutomerid,room_no,booked_info.bookedid,advance_amount,promocode,checkindate,checkoutdate")->from("booked_info")->join("booked_details","booked_details.bookedid=booked_info.bookedid","left")->where("booked_info.bookedid",$bookingid)->get()->row();
			$customer = explode(",",$bookedid->full_guest_name);
			
			$room_no = $bookedid->room_no;
			$roomnum = explode(",",$room_no);
			$roomstatus = array(
				'status' => 1
			);
			for($i=0;$i<count($roomnum); $i++){
				$this->db->where("roomno",$roomnum[$i])->update("tbl_roomnofloorassign", $roomstatus);
			}
			$oldbid = $bookedid->bookedid;
			$promocode = 0;
			if(!empty($bookedid->promocode)){
				$pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $bookedid->promocode)->get()->row();
				$promocode = $pdiscount->discount;
			}
			if($advanceamount){
				$credit = $this->db->select("balance")->from("customerinfo")->where("customerid",$bookedid->cutomerid)->get()->row();
				$newcredit = $credit->balance+$advanceamount-$bookedid->advance_amount;
				$cramount = array(
					'balance' => $newcredit
				);
				$this->db->where("customerid",$bookedid->cutomerid)->update("customerinfo",$cramount);
			}
		}
		if(empty($alluserid[0])){
		 // echo "IF1";
		if((!empty($customer[0])?$customer[0]:null)!=$allname[0] && !empty($allname[0])){
		  //  echo "IF2";
			$lastid=$this->db->select("*")->from('customerinfo')->order_by('customerid','desc')->get()->row();
			if(!empty($lastid)){
				$sl=(int)$lastid->customerid;
				}
			else{
				$sl = "0001"; 
				}
			$nextno=$sl+1;
			$si_length = strlen((int)$nextno); 
			
			$str = '0000';
			$cutstr = substr($str, $si_length); 
			$sino = $cutstr.$nextno;
		   $userdata = array(
			'firstname'   => $allname[0],
			'lastname' 	  => $alllastname[0],
			'customernumber'   => $sino,
			'cust_phone'  => $mobile,
			'email' 	  => $allemail[0],
			'gender' 	  => $allgender[0],
			'fathername'  => $this->input->post('father',TRUE),
			'profession'  => $this->input->post('occupation',TRUE),
			'dob' 	  	  => $this->input->post('dob',TRUE),
			'pass' 	      => md5('123456'),
			'c_reservation' => $this->input->post('c_reservation', TRUE),
        'c_reg' => $this->input->post('c_reg', TRUE),
        'c_roomno' => $this->input->post('c_roomno', TRUE),
        'c_full_name' => $this->input->post('c_full_name', TRUE),
        'c_company_taname' => $this->input->post('c_company_taname', TRUE),
        'c_address' => $this->input->post('c_address', TRUE),
        'c_pincode' => $this->input->post('c_pincode', TRUE),
        'c_res_off' => $this->input->post('c_res_off', TRUE),
        'c_moblie' => $this->input->post('c_moblie', TRUE),
        'c_res' => $this->input->post('c_res', TRUE),
        'c_dob' => $this->input->post('c_dob', TRUE),
        'c_email' => $this->input->post('c_email', TRUE),
        'c_vehicleno' => $this->input->post('c_vehicleno', TRUE),
        'c_nationality' => $this->input->post('c_nationality', TRUE),
        'c_noofperson' => $this->input->post('c_noofperson', TRUE),
        'c_adults' => $this->input->post('c_adults', TRUE),
        'c_children' => $this->input->post('c_children', TRUE),
        'c_arrival' => $this->input->post('c_arrival', TRUE),
        'c_atime' => $this->input->post('c_atime', TRUE),
        'c_departutedate' => $this->input->post('c_departutedate', TRUE),
        'c_dtime' => $this->input->post('c_dtime', TRUE),
        'c_aform'=> $this->input->post('c_aform', TRUE),
        'c_pov' => $this->input->post('c_pov', TRUE),
        'c_proceedingto' => $this->input->post('c_proceedingto', TRUE),
        'c_cash' => $this->input->post('modeofpayment', TRUE),
        'c_passport' => $this->input->post('c_passport', TRUE),
        'c_country' => $this->input->post('c_country', TRUE),
        'c_issued' => $this->input->post('c_issued', TRUE),
        'c_poi' => $this->input->post('c_poi', TRUE),
        'c_vaild' => $this->input->post('c_vaild', TRUE),
        'visa_validtill' => $this->input->post('visa_validtill', TRUE),
        'c_pdfi' => $this->input->post('c_pdfi', TRUE),
        'c_weii' => $this->input->post('c_weii', TRUE),
        'c_amount' => $this->input->post('c_amount', TRUE),
			'anniversary' => $this->input->post('anniversary',TRUE),
			'pitype' 	  => $allpitype[0],
			'pid' 	  	  => $allpid[0],
			'imgfront' 	  => $allimgfront[0],
			'imgback' 	  => $allimgback[0],
			'imgguest' 	  => (!empty($allimgguest[0])?$allimgguest[0]:""),
			'contacttype' => $this->input->post('contacttype',TRUE),
			'country' 	  => $this->input->post('state',TRUE),
			'city' 		  => $this->input->post('city',TRUE),
			'zipcode' 	  => $this->input->post('zipcode',TRUE),
			'address' 	  => $this->input->post('address',TRUE),
			'signupdate'  => date('d-m-Y')
		);

		$this->db->insert('customerinfo',$userdata);
		// echo $this->db->last_query(); die();
		//end
		$customerid = $this->db->insert_id();
		
		//insert Coa for Customer Receivable
		//end
		}else{
		  //  echo "else 1";
			$customerid = $bookedid->cutomerid;
		}
	   }else{
	        // echo "else 2";
		$customerid = $alluserid[0];
		}
	
		//booking info insert
		if(empty($this->input->post('bookingid', TRUE))) {
			$bookinginfo=$this->db->select("*")->from('booked_info')->order_by('bookedid','desc')->get()->row();
			
			
			
			
 
			
			
						if(!empty($bookinginfo)){
			$bookno=$bookinginfo->bookedid;
			}
			else{
				$bookno = "FO-".$financialYearStart ."0000"; 
				}
			$nextno=$bookno+1;
			$bk_length = strlen((int)$nextno); 
			$bkstr = '00000';
			$bknumber = substr($bkstr, $bk_length); 

			$currentYear = date('Y');
			$currentMonth = date('m');
		 	$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;

			$bookingnumber = "FO-".$financialYearStart .$bknumber.$nextno; 
			
			
// 				if(!empty($bookinginfo)){
// 			$bookno=$bookinginfo->bookedid;

// 			$currentYear = date('Y');
// 			$currentMonth = date('m');
// 			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
// 			$prefix = "FO" . $financialYearStart;
// 			$lastBookedId = isset($bookinginfo) && !empty($bookinginfo->bookedid) ? $bookinginfo->bookedid : $prefix . "0000";
// 			$numericPart = intval(substr($lastBookedId, strlen($suffix)));
// 			$nextNumericPart = $numericPart + 1;
// 			$bookingnumber = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
// 			}
//     else {

// 		$currentYear = date('Y');
// 		$currentMonth = date('m');
// 		$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
// 		$prefix = "FO" . $financialYearStart;
// 	    $lastBookedId = isset($bookinginfo) && !empty($bookinginfo->bookedid) ? $bookinginfo->bookedid : $prefix . "0000";
// 		$numericPart = intval(substr($lastBookedId, strlen($suffix)));
// 		$nextNumericPart = $numericPart + 1;
// 		$bookingnumber = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
//      }
			
			
			
			
// 			$currentYear = date('Y');
// $currentMonth = date('m');
// $financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
// $prefix = "FO" . $financialYearStart;
// $lastBookedId = isset($bookinginfo) && !empty($bookinginfo->bookedid) ? $bookinginfo->bookedid : $prefix . "0000";
// $numericPart = intval(substr($lastBookedId, strlen($suffix)));
// $nextNumericPart = $numericPart + 1;
// $bookingnumber = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
			
			
		//charge and tax
		$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$taxamount=0;
		foreach($setting as $st){
			$taxamount+=($st->rate*$totalprice)/100;
		}
		$children1 = $this->input->post('child', TRUE);
		$bed1 = $this->input->post('bed', TRUE);
		$amount_1 = $this->input->post('amount1', TRUE);
		$person_1 = $this->input->post('adults', TRUE);
		$amount_2 = $this->input->post('amount2', TRUE);
		$child_2 = $this->input->post('child', TRUE);
		$amount_3 = $this->input->post('amount3', TRUE);
		$grandtotal=($totalprice+$taxamount);
		if($advanceamount > 0) {
			date_default_timezone_set('Asia/Kolkata');
            $paid_paydate = date("d-m-Y H:i:s");
            $advance_paydate = date("d-m-Y H:i:s");
            
	    }
		//end
			 $postData = array(
			   'booking_number' 	     => $bookingnumber,
			   'date_time' 	             => date('d-m-Y H:i:s'),
			   'roomid' 	             => $room_type,
			   'nuofpeople'              => $person_1,
			   'children'              	 => $children1,
			   'total_room'              => count($allroom),
			   'room_no'              	 => ($roomno),
			
			   'advance_paydate' => $advance_paydate,
			   'roomrate'                => $rentafterdiscount,
			   'offer_discount'          => trim($discount_price,","),
			   'total_price'             => $this->input->post('total_price',TRUE),
			   'paid_amount'             => $advanceamount,
			   'paid_paydate'            => $paid_paydate,
			   'coments'                 => 'Booking from admin',
			   'checkindate'             => date('d-m-Y H:i:s', strtotime($datefilter1)),
			   'checkoutdate'            => date('d-m-Y H:i:s', strtotime($datefilter2)),
			   'cutomerid' 	             => $customerid,
			   'full_guest_name' 	     => trim($name),
			   'bookingstatus' 	         => 0
			  );
		//	  print_r($postData);die();

			if($advanceamount){
				$credit = $this->db->select("balance")->from("customerinfo")->where("customerid",$customerid)->get()->row();
				$newcredit = $credit->balance+$advanceamount;
				$cramount = array(
					'balance' => $newcredit
				);
				$this->db->where("customerid",$customerid)->update("customerinfo",$cramount);
			}
			for($ch=0;$ch<count($allroom);$ch++){
				$status="bookingstatus!=1 AND bookingstatus!=5";
				$croom ="FIND_IN_SET(".$allroom[$ch].",room_no)";
				$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$datefilter1)->where('checkoutdate>',$datefilter1)->where($status)->where("$croom !=",0)->get()->result();
				$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$datefilter2)->where('checkoutdate>=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
				$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$datefilter1)->where('checkoutdate<=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
				if(!empty($exits)||!empty($exit)||!empty($check)){
					echo '<h5>Failed</h5>Room No '.$allroom[$ch].' is not available';
					exit;
				}
			}
			$this->permission->method('room_reservation','create')->redirect();
			if($this->roomreservation_model->create($postData)) { 
				//end
				$bookedid = $this->db->insert_id();
				//Customer Advance account transaction 
				if($advanceamount>0){
					$this->advance_payment($bookedid, $paymentmode, $advanceamount,null);					
				}
				//insert into booking details
				$bdetails_data = array(
					'bookedid'   => $bookedid,
					'booking_type'   => $booking_type,
					'booking_source'   => $booking_source,
					'booking_source_no'   => $bsorurce_no,
					'extracheckin'   => $extrastart,
					'extracheckout'   => $extraend,
					'arival_from'   => $arrival_from,
					'purpose'   => $pof_visit,
					'extra_facility_days'   => $allextradays,
					'extrabed'   => trim($bed,","),
					'extraperson'   => trim($person,","),
					'extrachild'   => trim($child,","),
				    'bed_amount' => $amount1,
				    'person_amount' => $amount2,
				    'child_amount' => $amount3,
					'discountreason'   => $discountreason,
					'discountamount'   => $this->input->post('discountamount', TRUE),
					'cgst' => $this->input->post('cgst', TRUE),
					'sgst' => $this->input->post('sgst', TRUE),
					'commissionpersent'   => $commissionrate,
					'commissionamount'   => $commissionamount,
					'payment_method'   => $paymentmode,
					'advance_amount'   => $advanceamount,
					'advance_remarks'   => $advanceremarks,
					'advance_id'   => $advanceid,
					'remarks'   => $booking_remarks
				);
				$this->db->insert('booked_details',$bdetails_data);
				
			
		
				if($customerid){
					for($l=1;$l<count($allname);$l++){
						if(empty($alluserid[$l])){
							$guestdata = array(
								'bookedid'   => $bookedid,
								'guestname'   => $allname[$l],
								'mobile' 	  => (!empty($allmobile[$l])?$allmobile[$l]:null),
								'email'   => (!empty($allemail[$l])?$allemail[$l]:null),
								'gender'   => (!empty($allgender[$l])?$allgender[$l]:null),
								'photo_id_type'  => (!empty($allpitype[$l])?$allpitype[$l]:null),
								'photo_id' 	  => (!empty($allpid[$l])?$allpid[$l]:null),
								'front_image' 	  => (!empty($allimgfront[$l])?$allimgfront[$l]:null),
								'back_image'  => (!empty($allimgback[$l])?$allimgback[$l]:null),
								'occupant_image'  => (!empty($allimgguest[$l])?$allimgguest[$l]:null),
							);
						 }else{
							$guestdata = array(
								'bookedid'   => $bookedid,
								'customerid'   => $alluserid[$l],
							);
						 }
						$this->db->insert("tbl_otherguest",$guestdata);
					}
				}
				//end
				//sending email to customer
				$binfo = $this->db->select("b.booking_number,b.room_no,b.total_price,c.firstname,c.email")->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("bookedid",$bookedid)->get()->row();
				$this->email_send($binfo);
				//end
				if(ENVIRONMENT=="production"){
					$msg ="";
					$type = "processing";
					$response = $this->lsoft_setting->send_sms($bookingnumber, $customerid, $type);
					$data = json_decode($response);
					$msg = $data->message;
					if($msg)
						echo '<h5>Success</h5>';
				}
				if(empty($msg)){
					echo '<h5>Success</h5>Saved Successfully';
				}else{
					echo 'Saved Successfully<br>'.$msg;
				}
			} else {
			 echo '<h5>Failed</h5>Please Try Again';
			}
		   } else {
			$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
			$taxamount=0;
			foreach($setting as $st){
				$taxamount+=($st->rate*$totalprice)/100;
			}
			$grandtotal=($totalprice+$taxamount)-$promocode;
			if($advanceamount > 0) {
    			date_default_timezone_set('Asia/Kolkata');
                $paid_paydate = date("d-m-Y H:i:s");
                
    	    }
			$this->permission->method('room_reservation','update')->redirect();
			$updateData = array(
				'bookedid' 	             => $bookingid,
				'roomid' 	             => $room_type,
				'nuofpeople'              => $adults,
				'children'              => $children,
				'total_room'              => count($allroom),
				'room_no'              	 => ($roomno),
				'roomrate'                => $rent,
				'offer_discount'          => trim($discount_price,","),
				'total_price'             => $this->input->post('total_price',TRUE),
				//'paid_amount'             => $advanceamount,
				'paid_paydate'            => $paid_paydate,
				'coments'                 => 'Booking from admin',
				'checkindate'             => date('d-m-Y H:i:s', strtotime($datefilter1)),
				'checkoutdate'            => date('d-m-Y H:i:s', strtotime($datefilter2)),
				'cutomerid' 	             => $customerid,
				'full_guest_name' 	     => trim($name),
			   );
			   for($ch=0;$ch<count($allroom);$ch++){
				   if($oldbid != $bookingid | $bookedid->checkindate!=$datefilter1 | $bookedid->checkoutdate!=$datefilter2){
				$status="bookingstatus!=1 AND bookingstatus!=5";
				$croom ="FIND_IN_SET(".$allroom[$ch].",room_no)";
				$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$datefilter1)->where('checkoutdate>',$datefilter1)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$datefilter2)->where('checkoutdate>=',$datefilter2)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$datefilter1)->where('checkoutdate<=',$datefilter2)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				if(!empty($exits)||!empty($exit)||!empty($check)){
					echo '<h5>Failed</h5>Room No '.$allroom[$ch].' is not available';
					exit;
				}
				}
			}
			if ($this->roomreservation_model->update($updateData)) { 
				if($advanceamount>0){
					$this->advance_payment($bookingid, $paymentmode, $advanceamount,1);					
				}
			//insert into booking details
			$bdetails_data = array(
				'booking_type'   => $booking_type,
				'booking_source'   => $booking_source,
				'booking_source_no'   => $bsorurce_no,
				'extracheckin'   => $extrastart,
				'extracheckout'   => $extraend,
				'arival_from'   => $arrival_from,
				'purpose'   => $pof_visit,
				'extra_facility_days'   => $allextradays,
				'extrabed'   => trim($bed,","),
				'extraperson'   => trim($person,","),
				'extrachild'   => trim($child,","),
				'bed_amount' => $amount1,
				 'person_amount' => $amount2,
				 'child_amount' => $amount3,
				'complementary'   => trim($complementary,","),
				'complementaryprice'   => trim($complementaryprice,","),
				'discountreason'   => $discountreason,
				'discountamount'   => $this->input->post('discountamount', TRUE),
				'cgst' => $this->input->post('cgst', TRUE),
				'sgst' => $this->input->post('sgst', TRUE),
				'commissionpersent'   => $commissionrate,
				'commissionamount'   => $commissionamount,
				'payment_method'   => $paymentmode,
				'advance_amount'   => $advanceamount,
				'advance_remarks'   => $advanceremarks,
				'advance_id'   => $advanceid,
				'remarks'   => $booking_remarks
			);
			$this->db->where("bookedid",$bookingid)->update('booked_details',$bdetails_data);
			//end
			//other guest update and insert
			$gid = $this->db->select("otherguest_id")->from("tbl_otherguest")->where("bookedid",$bookingid)->get()->result();
			for($l=1;$l<count($allname);$l++){
				if(empty($alluserid[$l])){
					$guestdata = array(
						'bookedid'   => $bookingid,
						'guestname'   => $allname[$l],
						'mobile' 	  => (!empty($allmobile[$l])?$allmobile[$l]:null),
						'email'   => (!empty($allemail[$l])?$allemail[$l]:null),
						'gender'   => (!empty($allgender[$l])?$allgender[$l]:null),
						'photo_id_type'  => (!empty($allpitype[$l])?$allpitype[$l]:null),
						'photo_id' 	  => (!empty($allpid[$l])?$allpid[$l]:null),
						'front_image' 	  => (!empty($allimgfront[$l])?$allimgfront[$l]:null),
						'back_image'  => (!empty($allimgback[$l])?$allimgback[$l]:null),
						'occupant_image'  => (!empty($allimgguest[$l])?$allimgguest[$l]:null),
					);
				 }else{
					$guestdata = array(
						'bookedid'   => $bookingid,
						'customerid'   => $alluserid[$l],
					);
				 }
				if(empty($gid[$l-1]->otherguest_id)){
					$this->db->insert("tbl_otherguest",$guestdata);
				}else{
					$this->db->where("otherguest_id",$gid[$l-1]->otherguest_id)->update('tbl_otherguest',$guestdata);
				}
			}
			if(count($gid)>(count($allname)-1)){
				for($gl=count($allname)-1; $gl<count($gid);$gl++){
					$this->db->where("otherguest_id",$gid[$gl]->otherguest_id)->delete('tbl_otherguest');
				}
			}
			//end
			   echo '<h5>Success</h5>Updated Successfully';
			} else {
				echo '<h5>Failed</h5>Please Try Again';
			}
		   }
		
	}
	public function checkinBooking(){
	   // print_r($_POST); die();
		//reservation details
		$bookingid = $this->input->post('bookingid', TRUE);
		$datefilter1 = $this->input->post('datefilter1', TRUE);
		$datefilter2 = $this->input->post('datefilter2', TRUE);
		$booking_type = $this->input->post('booking_type', TRUE);
		$booking_source = $this->input->post('booking_source', TRUE);
		$bsorurce_no = $this->input->post('bsorurce_no', TRUE);
		$arrival_from = $this->input->post('arrival_from', TRUE);
		$pof_visit = $this->input->post('pof_visit', TRUE);
		$booking_remarks = $this->input->post('booking_remarks', TRUE);
		//room details
		$room_type = $this->input->post('room_type', TRUE);
		$roomno = $this->input->post('roomno', TRUE);
		$adults = $this->input->post('adults', TRUE);
		$children = $this->input->post('children', TRUE);
		$bed = $this->input->post('bed', TRUE);
		$amount1 = $this->input->post('amount1', TRUE);
		$person = $this->input->post('person', TRUE);
		$amount2 = $this->input->post('amount2', TRUE);
		$child = $this->input->post('child', TRUE);
		$amount3 = $this->input->post('amount3', TRUE);
		$extrastart = $this->input->post('extrastart', TRUE);
		$extraend = $this->input->post('extraend', TRUE);
		$rent = $this->input->post('rent', TRUE);
		$discount_price = $this->input->post('discount_price', TRUE);
		$complementary = $this->input->post('complementary', TRUE);
		$complementaryprice = $this->input->post('complementaryprice', TRUE);

		//payment details
		$discountreason = $this->input->post('discountreason', TRUE);
		$discountamount = $this->input->post('discountamount', TRUE);
		$commissionrate = $this->input->post('commissionrate', TRUE);
		$commissionamount = $this->input->post('commissionamount', TRUE);
		$paymentmode = $this->input->post('paymentmode', TRUE);
		$advanceamount = $this->input->post('advanceamount', TRUE);
		$advanceremarks = $this->input->post('advanceremarks', TRUE);
		   $currentYear = date('Y');
                         $currentMonth = date('m');
                         $financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
                     if (!isset($_SESSION['payment_counters'])) {
                     $_SESSION['payment_counters'] = 1;
                     }
                     $payment_id = $_SESSION['payment_counters']++;
                     $value = htmlspecialchars("ADV" . $financialYearStart . sprintf('%04d', $payment_id), ENT_QUOTES, 'UTF-8');
                     
		$advanceid = $value;
		//user details
		$userid = $this->input->post('userid', TRUE);
		$alluserid = explode(",", trim($userid));
		$name = $this->input->post('name', TRUE);
		$allname = explode(",", trim($name));
		$mobile = $this->input->post('mobile', TRUE);
		$lastname = $this->input->post('lastname', TRUE);
		$gender = $this->input->post('gender', TRUE);
		$father = $this->input->post('father', TRUE);
		$occupation = $this->input->post('occupation', TRUE);
		$dob = $this->input->post('dob', TRUE);
		$anniversary = $this->input->post('anniversary', TRUE);
		$pitype = $this->input->post('pitype', TRUE);
		$imgfront = $this->input->post('imgfront', TRUE);
		$imgback = $this->input->post('imgback', TRUE);
		$imgguest = $this->input->post('imgguest', TRUE);
		$contacttype = $this->input->post('contacttype', TRUE);
		$state = $this->input->post('state', TRUE);
		$city = $this->input->post('city', TRUE);
		$zipcode = $this->input->post('zipcode', TRUE);
		$address = $this->input->post('address', TRUE);
		//end
		$allroom = explode(",",trim($roomno));
		$price = explode(",",trim($rent));
		$totalprice=0;
		for($i=0;$i<count($price);$i++){
			$totalprice+=$price[$i];
		}
		$totalprice -= !empty($discountamount)?$discountamount:0;
		$extradays="";
		$exstart = explode(",", $extrastart);
		$exend = explode(",", $extraend);
		for($r=0;$r<count($allroom);$r++){
			$start_date = strtotime($exstart[$r]);
			$end_date = strtotime($exend[$r]);
			$difference = $end_date - $start_date;
			$extradays .=  ceil($difference / (60 * 60 * 24)).",";
		}
		$allextradays = trim($extradays,",");

		//user details insert
		if($bookingid){
			$bookedid = $this->db->select("full_guest_name,cutomerid,room_no,booked_info.bookedid,advance_amount,promocode,checkindate,checkoutdate")->from("booked_info")->join("booked_details","booked_details.bookedid=booked_info.bookedid","left")->where("booked_info.bookedid",$bookingid)->get()->row();
			$customer = explode(",",$bookedid->full_guest_name);
			$room_no = $bookedid->room_no;
			$roomnum = explode(",",$room_no);
			$roomstatus = array(
				'status' => 1
			);
			for($i=0;$i<count($roomnum); $i++){
				$this->db->where("roomno",$roomnum[$i])->update("tbl_roomnofloorassign", $roomstatus);
			}
			$oldbid = $bookedid->bookedid;
			$promocode = 0;
			if(!empty($bookedid->promocode)){
				$pdiscount = $this->db->select("discount")->from("promocode")->where("promocode", $bookedid->promocode)->get()->row();
				$promocode = $pdiscount->discount;
			}
			if($advanceamount){
				$credit = $this->db->select("balance")->from("customerinfo")->where("customerid",$bookedid->cutomerid)->get()->row();
				$newcredit = $credit->balance+$advanceamount-$bookedid->advance_amount;
				$cramount = array(
					'balance' => $newcredit
				);
				$this->db->where("customerid",$bookedid->cutomerid)->update("customerinfo",$cramount);
			}
		}
		if(empty($alluserid[0])){
		if((!empty($customer[0])?$customer[0]:null)!=$allname[0] && !empty($allname[0])){
			$lastid=$this->db->select("*")->from('customerinfo')->order_by('customerid','desc')->get()->row();
			if(!empty($lastid)){
				$sl=(int)$lastid->customerid;
				}
			else{
				$sl = "0001"; 
				}
			$nextno=$sl+1;
			$si_length = strlen((int)$nextno); 
			
			$str = '0000';
			$cutstr = substr($str, $si_length); 
			$sino = $cutstr.$nextno;
		   $userdata = array(
			'firstname'   => $allname[0],
			'customernumber'   => $sino,
			'lastname' 	  => $this->input->post('lastname',TRUE),
			'cust_phone'  => $this->input->post('mobile',TRUE),
			'email' 	  => $this->input->post('email',TRUE),
			'gender' 	  => $this->input->post('gender',TRUE),
			'fathername'  => $this->input->post('father',TRUE),
			'profession'  => $this->input->post('occupation',TRUE),
			'dob' 	  	  => $this->input->post('dob',TRUE),
			'pass' 	      => md5('123456'),
			'anniversary' => $this->input->post('anniversary',TRUE),
			'pitype' 	  => $this->input->post('pitype',TRUE),
			'imgfront' 	  => $this->input->post('imgfront', TRUE),
			'imgback' 	  => $this->input->post('imgback', TRUE),
			'imgguest' 	  => $this->input->post('imgguest', TRUE),
			'contacttype' => $this->input->post('contacttype',TRUE),
			'country' 	  => $this->input->post('state',TRUE),
			'city' 		  => $this->input->post('city',TRUE),
			'zipcode' 	  => $this->input->post('zipcode',TRUE),
			'address' 	  => $this->input->post('address',TRUE),
			'signupdate'  => date('d-m-Y')
		);

		$this->db->insert('customerinfo',$userdata);
		//end
		$customerid = $this->db->insert_id();
		
		}else{
			$customerid = $bookedid->cutomerid;
		}
	   }else{
		$customerid = $alluserid[0];
		}
		//booking info insert
		if(empty($this->input->post('bookingid', TRUE))) {
			$bookinginfo=$this->db->select("*")->from('booked_info')->order_by('bookedid','desc')->get()->row();
			if(!empty($bookinginfo)){
			$bookno=$bookinginfo->bookedid;
			}
			else{
				$bookno = "00000000"; 
				}
			
// 			$nextno=$bookno+1;
// 			$bk_length = strlen((int)$nextno); 
			
// 			$bkstr = '00000000';
// 			$bknumber = substr($bkstr, $bk_length); 
// 			$bookingnumber = $bknumber.$nextno;   
			
			
			
			
			$nextno=$bookno+1;
			$bk_length = strlen((int)$nextno); 
			$bkstr = '00000';
			$bknumber = substr($bkstr, $bk_length); 

			$currentYear = date('Y');
			$currentMonth = date('m');
		 	$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;

			$bookingnumber = "FO-".$financialYearStart .$bknumber.$nextno; 
			
			
			
			
		//charge and tax
		$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$taxamount=0;
		foreach($setting as $st){
			$taxamount+=($st->rate*$totalprice)/100;
		}
		$grandtotal=($totalprice+$taxamount);
		if($advanceamount > 0) {
    	    date_default_timezone_set('Asia/Kolkata');
            $paid_paydate = date("d-m-Y H:i:s");
            $advance_paydate = date("d-m-Y H:i:s");
                
    	}
    	 $paid_paydate = date("d-m-Y H:i:s");
	  $advance_paydate = date("d-m-Y H:i:s");
			 $postData = array(
			   'booking_number' 	     => $bookingnumber,
			   'date_time' 	             => date('d-m-Y H:i:s'),
			   'roomid' 	             => $room_type,
			   'nuofpeople'              => $adults,
			   'children'                => $children,
			   'total_room'              => count($allroom),
			   'room_no'              	 => ($roomno),
			   'roomrate'                => $rent,
			   'offer_discount'          => trim($discount_price,","),
			   'total_price'             => $this->input->post('total_price', TRUE),
			 //  'paid_amount'             => $advanceamount,
			 'paid_paydate'                     =>$paid_paydate,
			   'advance_paydate'         => $advance_paydate,
			   'coments'                 => 'Booking from admin',
			   'checkindate'             => $datefilter1,
			   'checkoutdate'            => $datefilter2,
			   'cutomerid' 	             => $customerid,
			   'full_guest_name' 	     => trim($name),
			   'bookingstatus' 	         => 4			  
			  );
	 // echo "2" ;print_r($postData);die();
			  if($advanceamount){
				$credit = $this->db->select("balance")->from("customerinfo")->where("customerid",$customerid)->get()->row();
				$newcredit = $credit->balance+$advanceamount;
				$cramount = array(
					'balance' => $newcredit
				);
				$this->db->where("customerid",$customerid)->update("customerinfo",$cramount);
			}
			for($ch=0;$ch<count($allroom);$ch++){
				$status="bookingstatus!=1 AND bookingstatus!=5";
				$croom ="FIND_IN_SET(".$allroom[$ch].",room_no)";
				$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$datefilter1)->where('checkoutdate>',$datefilter1)->where($status)->where("$croom !=",0)->get()->result();
				$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$datefilter2)->where('checkoutdate>=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
				$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$datefilter1)->where('checkoutdate<=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
				if(!empty($exits)||!empty($exit)||!empty($check)){
					echo '<h5>Failed</h5>Room No '.$allroom[$ch].' is not available';
					exit;
				}
			}
			$this->permission->method('room_reservation','create')->redirect();
			if($this->roomreservation_model->create($postData)) { 
				//end
				$bookedid = $this->db->insert_id();
				if($advanceamount>0){
					$this->advance_payment($bookedid, $paymentmode, $advanceamount,null);					
				}
				//insert into booking details
				$bdetails_data = array(
					'bookedid'   => $bookedid,
					'booking_type'   => $booking_type,
					'booking_source'   => $booking_source,
					'booking_source_no'   => $bsorurce_no,
					'extracheckin'   => $extrastart,
					'extracheckout'   => $extraend,
					'arival_from'   => $arrival_from,
					'purpose'   => $pof_visit,
					'extra_facility_days'   => $allextradays,
					'extrabed'   => trim($bed,","),
					'extraperson'   => trim($person,","),
					'extrachild'   => trim($child,","),
					'bed_amount' => $amount1,
				    'person_amount' => $amount2,
				    'child_amount' => $amount3,
					'complementary'   => trim($complementary,","),
					'complementaryprice'   => trim($complementaryprice,","),
					'discountreason'   => $discountreason,
					'discountamount'   => $discountamount,
					'commissionpersent'   => $commissionrate,
					'commissionamount'   => $commissionamount,
					'commissionpersent'   => $commissionrate,
					'commissionamount'   => $commissionamount,
					'payment_method'   => $paymentmode,
					'advance_amount'   => $advanceamount,
					'advance_remarks'   => $advanceremarks,
					'advance_id'   => $advanceid,
					'cgst' => $this->input->post('cgst', TRUE),
					'sgst' => $this->input->post('sgst', TRUE),
					'remarks'   => $booking_remarks
				);
				$this->db->insert('booked_details',$bdetails_data);
				//end
				//insert other guest
				if($customerid){
					for($l=1;$l<count($allname);$l++){
						if(empty($alluserid[$l])){
							$guestdata = array(
								'bookedid'   => $bookedid,
								'guestname'   => $allname[$l],
								'mobile' 	  => (!empty($allmobile[$l])?$allmobile[$l]:null),
								'email'   => (!empty($allemail[$l])?$allemail[$l]:null),
								'gender'   => (!empty($allgender[$l])?$allgender[$l]:null),
								'photo_id_type'  => (!empty($allpitype[$l])?$allpitype[$l]:null),
								'photo_id' 	  => (!empty($allpid[$l])?$allpid[$l]:null),
								'front_image' 	  => (!empty($allimgfront[$l])?$allimgfront[$l]:null),
								'back_image'  => (!empty($allimgback[$l])?$allimgback[$l]:null),
								'occupant_image'  => (!empty($allimgguest[$l])?$allimgguest[$l]:null),
							);
						}else{
							$guestdata = array(
								'bookedid'   => $bookedid,
								'customerid'   => $alluserid[$l],
							);
						}
						$this->db->insert("tbl_otherguest",$guestdata);
					}
				}
				//end
			//sending email to customer
			$binfo = $this->db->select("b.booking_number,b.room_no,b.total_price,c.firstname,c.email")->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("bookedid",$bookingid)->get()->row();
			$this->email_send($binfo,4);
			//end
			 echo '<h5>Success</h5>Checkin Successfully';
			} else {
			 echo '<h5>Failed</h5>Please Try Again';
			}
		   } else {
			$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
			$taxamount=0;
			foreach($setting as $st){
				$taxamount+=($st->rate*$totalprice)/100;
			}
			$grandtotal=($totalprice+$taxamount)-$promocode;
			$this->permission->method('room_reservation','update')->redirect();
			  $advance_paydate = date("d-m-Y H:i:s");
			  	 $paid_paydate = date("d-m-Y H:i:s");
			$updateData = array(
				'bookedid' 	             => $bookingid,
				'roomid' 	             => $room_type,
				'nuofpeople'              => $adults,
				'children'              => $children,
				'total_room'              => count($allroom),
				'room_no'              	 => ($roomno),
				'roomrate'                => $rent,
				'roomrate'                => $rent,
				'offer_discount'          => trim($discount_price,","),
				'total_price'             => $this->input->post('total_price', TRUE),
			//	'paid_amount'             => $advanceamount,
			     'paid_paydate'                     =>$paid_paydate,
				'advance_paydate'         => $advance_paydate,
				'coments'                 => 'Booking from admin',
				'checkindate'             => $datefilter1,
				'checkoutdate'            => $datefilter2,
				'cutomerid' 	             => $customerid,
				'full_guest_name' 	     => trim($name),
				'bookingstatus' 	         => 4
			   );
		//	  echo "1" ;print_r($updateData);die();
			   for($ch=0;$ch<count($allroom);$ch++){
				   if($oldbid != $bookingid | $bookedid->checkindate!=$datefilter1 | $bookedid->checkoutdate!=$datefilter2){
				$status="bookingstatus!=1 AND bookingstatus!=5";
				$croom ="FIND_IN_SET(".$allroom[$ch].",room_no)";
				$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$datefilter1)->where('checkoutdate>',$datefilter1)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$datefilter2)->where('checkoutdate>=',$datefilter2)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$datefilter1)->where('checkoutdate<=',$datefilter2)->where($status)->where("$croom !=",0)->where('bookedid!=',$bookingid)->get()->result();
				if(!empty($exits)||!empty($exit)||!empty($check)){
					echo '<h5>Failed</h5>Room No '.$allroom[$ch].' is not available';
					exit;
				}
				}
			}
			if ($this->roomreservation_model->update($updateData)) { 
				if($advanceamount>0){
					$this->advance_payment($bookingid, $paymentmode, $advanceamount,1);					
				}
			//insert into booking details
			$bdetails_data = array(
				'booking_type'   => $booking_type,
				'booking_source'   => $booking_source,
				'booking_source_no'   => $bsorurce_no,
				'extracheckin'   => $extrastart,
				'extracheckout'   => $extraend,
				'arival_from'   => $arrival_from,
				'purpose'   => $pof_visit,
				'extra_facility_days'   => $allextradays,
				'extrabed'   => trim($bed,","),
				'extraperson'   => trim($person,","),
				'extrachild'   => trim($child,","),
				'bed_amount' => $amount1,
				'person_amount' => $amount2,
				'child_amount' => $amount3,
				'complementary'   => trim($complementary,","),
				'complementaryprice'   => trim($complementaryprice,","),
				'discountreason'   => $discountreason,
				'discountamount'   => $discountamount,
				'commissionpersent'   => $commissionrate,
				'commissionamount'   => $commissionamount,
				'commissionpersent'   => $commissionrate,
				'commissionamount'   => $commissionamount,
				'payment_method'   => $paymentmode,
				'advance_amount'   => $advanceamount,
				'advance_remarks'   => $advanceremarks,
				'advance_id'   => $advanceid,
				'cgst' => $this->input->post('cgst', TRUE),
				'sgst' => $this->input->post('sgst', TRUE),
				'remarks'   => $booking_remarks
			);
			$this->db->where("bookedid",$bookingid)->update('booked_details',$bdetails_data);
			//end

			//other guest update and insert
			$gid = $this->db->select("otherguest_id")->from("tbl_otherguest")->where("bookedid",$bookingid)->get()->result();
			for($l=1;$l<count($allname);$l++){
				if(empty($alluserid[$l])){
					$guestdata = array(
						'bookedid'   => $bookingid,
						'guestname'   => $allname[$l],
						'mobile' 	  => (!empty($allmobile[$l])?$allmobile[$l]:null),
						'email'   => (!empty($allemail[$l])?$allemail[$l]:null),
						'gender'   => (!empty($allgender[$l])?$allgender[$l]:null),
						'photo_id_type'  => (!empty($allpitype[$l])?$allpitype[$l]:null),
						'photo_id' 	  => (!empty($allpid[$l])?$allpid[$l]:null),
						'front_image' 	  => (!empty($allimgfront[$l])?$allimgfront[$l]:null),
						'back_image'  => (!empty($allimgback[$l])?$allimgback[$l]:null),
						'occupant_image'  => (!empty($allimgguest[$l])?$allimgguest[$l]:null),
					);
				 }else{
					$guestdata = array(
						'bookedid'   => $bookingid,
						'customerid'   => $alluserid[$l],
					);
				 }
				if(empty($gid[$l-1]->otherguest_id)){
					$this->db->insert("tbl_otherguest",$guestdata);
				}else{
					$this->db->where("otherguest_id",$gid[$l-1]->otherguest_id)->update('tbl_otherguest',$guestdata);
				}
			}
			if(count($gid)>(count($allname)-1)){
				for($gl=count($allname)-1; $gl<count($gid);$gl++){
					$this->db->where("otherguest_id",$gid[$gl]->otherguest_id)->delete('tbl_otherguest');
				}
			}
			//end
				//sending email to customer
				$binfo = $this->db->select("b.booking_number,b.room_no,b.total_price,c.firstname,c.email")->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("bookedid",$bookingid)->get()->row();
				$this->email_send($binfo,4);
				//end
			   if(ENVIRONMENT=="production"){
				$msg ="";
				$type = "completeorder";
				$response = $this->lsoft_setting->send_sms($bookingnumber, $customerid, $type);
				$data = json_decode($response);
				$msg = $data->message;
				if($msg)
					echo '<h5>Success</h5>';
			}
			if(empty($msg)){
				echo '<h5>Success</h5>Checkin Successfully';
			}else{
				echo 'Checkin Successfully<br>'.$msg;
			}
			} else {
				echo '<h5>Failed</h5>Please Try Again';
			}
		   }
	}
	public function cancelbooking(){
		$this->form_validation->set_rules('cancelreason',"Cancel Reason",'required|xss_clean');
		$pmethod = $this->input->post('pmethod', TRUE);
		if($pmethod=="Bank Payment"){
			$this->form_validation->set_rules('bankName',"Bank Name",'required|xss_clean');
		}
		$bookingid = $this->input->post('bookedid', TRUE);
		$cancelreason = $this->input->post('cancelreason', TRUE);
		$cancelationcharge = $this->input->post('cancelationcharge', TRUE);
		$hstatus = $this->db->where("hid", $bookingid)->update("tbl_hallroom_info", array("room_status" => 0));
		if ($this->form_validation->run()) { 
			$cancel =  array(
				'coments' => $cancelreason,
				'paid_amount' => $cancelationcharge,
				'bookingstatus' => 1
			);
			$method = array(
				'payment_method' => $pmethod 
			);
			$cancelbooking = $this->db->where("bookedid",$bookingid)->update("booked_info", $cancel);
			if($cancelbooking && $pmethod){
				$this->db->where("bookedid",$bookingid)->update("booked_details", $method);
			}
			if($cancelbooking){
				$allroom = $this->db->select("room_no")->from("booked_info")->where("bookedid",$bookingid)->get()->row();
				$roomno = explode(",",$allroom->room_no);
				$roomstatus = array(
					'status' => 1
				);
				for($i=0;$i<count($roomno); $i++){
					$this->db->where("roomno",$roomno[$i])->update("tbl_roomnofloorassign", $roomstatus);
				}
				if($cancelationcharge>0){
					$cardNumber = $this->input->post('cardNumber', TRUE);
					$bankName = $this->input->post('bankName', TRUE);
					//insert payment
					$payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
					if(!empty($payinfo)){
						$invoicenum=$payinfo->invoice;
					}else{
						$invoicenum = "000000"; 
					}
					$nextno=$invoicenum+1;
					$bk_length = strlen((int)$nextno); 
					$bkstr = '000000';
					$bknumber = substr($bkstr, $bk_length); 
					$invoice_no = $bknumber.$nextno;
					$newdate = date("d-m-Y H:i:s");
					$saveid = $this->session->userdata('id');
					$postData = array(
						'bookedid' 	         	 => $bookingid,
						'invoice' 	             => $invoice_no,
						'paydate' 	             => $newdate,
						'paymenttype' 	         => $pmethod,
						'paymentamount' 	     => $cancelationcharge,
						'details' 	     		 => "Card/Account No: ".$cardNumber." Bank Name: ".$bankName,
						'book_type' 	     	 => 0,
						);
					$this->db->insert('tbl_guestpayments', $postData);
					//Payment method Debit for paid value
					if($pmethod=="Bank Payment"){
						$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$bankName'");
						$row = $query->row();
						$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
						if(empty($headcode)){
							$coa = $this->roomreservation_model->headcode(4,1020102);
							if($coa->HeadCode!=NULL){
								$headcode=$coa->HeadCode+1;
							}
							else{
								$headcode="102010201";
							}
							//insert Coa for Customer Receivable
							$postData1['HeadCode']   	=$headcode;
							$postData1['HeadName']   	=$bankName;
							$postData1['PHeadName']   	='Cash At Bank';
							$postData1['HeadLevel']   	='4';
							$postData1['IsActive']  	='1';
							$postData1['IsTransaction'] ='1';
							$postData1['IsGL']   		='0';
							$postData1['HeadType']  	='A';
							$postData1['IsBudget'] 		='0';
							$postData1['IsDepreciation']='0';
							$postData1['DepreciationRate']='0';
							$postData1['CreateBy'] 		=$saveid;
							$postData1['CreateDate'] 	=$newdate;
							$this->db->insert('acc_coa',$postData1);
							//end
						}
						$narration = 'Cash in Bank Debited For '.$bankName.' Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, $headcode, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					}
					else if($pmethod=="SSLCommerz"){
						$narration = 'Cash in SSLCOMMERZ Debited For Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 102010302, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					}
					else if($pmethod=="Cash Payment"){
						$narration = 'Cash in Hand Debited For Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 1020101, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					}
					else if($pmethod=="Paypal"){
						$narration = 'Cash in Paypal Debited For Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 102010301, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					}
					else if($pmethod=="Card Payment"){
						$narration = 'Cash in Card Debited For Invoice# '.$invoice_no;
						transaction($invoice_no, 'CIV', $newdate, 102010304, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					}
					else{
						$path = 'application/modules/';
						$map  = directory_map($path);
						$HmvcMenu   = array();
						if (is_array($map) && sizeof($map) > 0)
						foreach ($map as $key => $value) {
							$env = str_replace("\\", '/', $path.$key.'assets/data/env'); 
							$transaction = str_replace("\\", '/', $path.$key.'controllers/transaction.php'); 
							if (file_exists($env)) {
								if (file_exists($transaction)) {
									@include($transaction);
									if($pmethod==$paymentMethod){
										$narration = 'Cash in Paystack Debited For Invoice# '.$invoice_no;
										transaction($invoice_no, 'CIV', $newdate, $headCode, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
									}
								}
							}
						}
						$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020103%' And HeadName LIKE '$pmethod'");
						$row = $query->row();
						$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
				    }

					//Customer debit for Rent Value
					$narration = 'Customer debited for Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102030101, $narration, $cancelationcharge, 0, 0, 1, $saveid, $newdate, 1);
					//Hotel Owner credit for Hotel Rent Value
					$narration = 'Hotel Credited for Hotel Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 30301, $narration, 0, $cancelationcharge, 0, 1, $saveid, $newdate, 1);
					// Customer Credit for paid amount.
					$narration = 'Customer Credited for Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102030101, $narration, 0, $cancelationcharge, 0, 1, $saveid, $newdate, 1);
				} 
			}
			$this->session->set_flashdata('message', "Reservation Canceled Successfully");
			redirect($_SERVER['HTTP_REFERER']);
		}
		else{
			$data['module'] = "room_reservation";
			$data['page']   = "reservationlist";   
			echo Modules::run('template/layout', $data); 
		}
	}
    public function create($id = null)
    {
	  $data['title'] = display('room_reservation');
	  $this->form_validation->set_rules('guest',display('guest'),'required|xss_clean');
	  $this->form_validation->set_rules('room_name',display('room_name'),'required|xss_clean');
	  $this->form_validation->set_rules('no_of_people',display('no_of_people'),'required|xss_clean');
	  $this->form_validation->set_rules('check_in',display('check_in'),'required|xss_clean');
	  $this->form_validation->set_rules('check_out',display('check_out'),'required|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $this->input->post('discount',true);
	  $data['intinfo']="";
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('bookedid', TRUE))) {
		$bookinginfo=$this->db->select("*")->from('booked_info')->order_by('bookedid','desc')->get()->row();
		if(!empty($bookinginfo)){
		$bookno=$bookinginfo->bookedid;
		}
		else{
			$bookno = "00000"; 
			}
		
// 		$nextno=$bookno+1;
// 		$bk_length = strlen((int)$nextno); 
		
// 		$bkstr = '00000000';
// 		$bknumber = substr($bkstr, $bk_length); 
// 		$bookingnumber = $bknumber.$nextno;  
		
		
		
			$nextno=$bookno+1;
			$bk_length = strlen((int)$nextno); 
			$bkstr = '00000';
			$bknumber = substr($bkstr, $bk_length); 

			$currentYear = date('Y');
			$currentMonth = date('m');
		 	$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;

			$bookingnumber = "FO-".$financialYearStart .$bknumber.$nextno; 
			
		
		
		
		
		
		
		$length=count($this->input->post('slroomno',TRUE)); 
		$room=$this->input->post('slroomno',TRUE);
		$roomnosel='';
		$custID=$this->input->post('guest', TRUE);
		for($i=0;$i<$length;$i++){
			$roomnosel.=$room[$i].',';
			} 
		 $roomnosel=rtrim($roomnosel,',');  
		 $postData = array(
		   'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
		   'booking_number' 	     => $bookingnumber,
		   'date_time' 	             => date('d-m-Y H:i:s'),
		   'roomid' 	             => $this->input->post('room_name',TRUE),
		   'nuofpeople'              => $this->input->post('no_of_people',TRUE),
		   'total_room'              => $this->input->post('numofroom',TRUE),
		   'room_no'              	 => $roomnosel,
		   'roomrate'                => $this->input->post('roomrate',TRUE),
		   'total_price'             => $this->input->post('total_price',TRUE),
		   // 'total_price'             => $this->input->post('gramount',TRUE),
		   'offer_discount'          => $this->input->post('discount',TRUE),
		   'coments'                 => '',
		   'checkindate'             => $this->input->post('check_in',TRUE),
		   'checkoutdate'            => $this->input->post('check_out',TRUE),
		   'cutomerid' 	             => $this->input->post('guest', TRUE),
		   'bookingstatus' 	         => 0
		  
		  );
		$this->permission->method('room_reservation','create')->redirect();
		if($this->roomreservation_model->create($postData)) { 
		 $type = "processing";
		 $response = $this->lsoft_setting->send_sms($bookingnumber, $custID, $type);
		 $data = json_decode($response);
		 $msg = $data->message;
		 $this->session->set_flashdata('message', display('save_successfully'));
		 if($msg)
		 $this->session->set_userdata('msg', $msg);
		 redirect('room_reservation/room-booking');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		 redirect('room_reservation/room-booking');
	   } else {
		$this->permission->method('room_reservation','update')->redirect();
		$roomnosel=$this->input->post('room_no', TRUE);
		$status = $this->input->post('status', TRUE);
		$bookingnumber = $this->input->post('bookingnumber', TRUE);
		$custID = $this->input->post('guest', TRUE);
		if(empty($roomnosel)){
			$length=count($this->input->post('slroomno',TRUE)); 
			$room=$this->input->post('slroomno',TRUE);
			$roomnosel='';
			for($i=0;$i<$length;$i++){
				$roomnosel.=$room[$i].',';
				} 
			 $roomnosel=rtrim($roomnosel,',');  
		}
		$data['room_reservation']   = (Object) $updateData = array(
		   'room_no'              	 => $roomnosel,
		   'bookedid'     	     	 => $this->input->post('bookedid', TRUE),
		   'bookingstatus' 	         => $this->input->post('status', TRUE)
		  );
		if ($this->roomreservation_model->update($updateData)) { 
			if(ENVIRONMENT=="production"){
				$msg="";
				if($status==4){
					$type = "completeorder";
					$response = $this->lsoft_setting->send_sms($bookingnumber, $customerid, $type);
					$data = json_decode($response);
					$msg = $data->message;
				}
				if($status==1){
					$type = "cancel";
					$response = $this->lsoft_setting->send_sms($bookingnumber, $customerid, $type);
					$data = json_decode($response);
					$msg = $data->message;
				}
				if($msg)
					echo '<h5>Success</h5>'.$msg;
			}
		 $this->session->set_flashdata('message', display('update_successfully'));
		 if($msg)
		 $this->session->set_userdata('msg', $msg);
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/booking-list");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('reservation_edit');
		$data['intinfo']   = $this->roomreservation_model->findById($id);
	   }
	   $data["roomlist"] = $this->roomreservation_model->allrooms();
	   $data["customerlist"] = $this->roomreservation_model->customerlist();
	   $data['module'] = "room_reservation";
	   $data['page']   = "addbooking";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
   public function updateintfrm($id){
	  
		$this->permission->method('room_reservation','update')->redirect();
		$data['title'] = display('bed_edit');
		$data["roomlist"] = $this->roomreservation_model->allrooms();
		$data["customerlist"] = $this->roomreservation_model->customerlist();
		$data['intinfo']   = $this->roomreservation_model->findById($id);

		$roomname=$data['intinfo']->roomid;
		$checkin=$data['intinfo']->checkindate;
		$checkout=$data['intinfo']->checkoutdate;
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->row();
		$roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$roomname)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit) && empty($check)){
			$data['freeroom']=$gtroomno;
			$data['isfound']=0;
		}
		else{
			$bookedroom="";
			if(!empty($exits)){
			foreach($exits as $booked){
			$bookedroom.=$booked->room_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->room_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->room_no.',';
				}
			}
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom1=$totalroom1->allroom;
		$allbokedroom2=$totalroom2->allroom;
		$allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
		$allfreeroom=$totalroomfound->totalroom;
				if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					if(!empty($output)){
						$data['freeroom']=$output;
						$data['isfound']='1';
							}
					else{
						$data['freeroom']='';
						$data['isfound']='2';
						}
				}
				else{
					$data['freeroom']='';
					$data['isfound']='2';
					}
			}

        $data['module'] = "room_reservation";  
        $data['page']   = "reservationedit";
		$this->load->view('room_reservation/reservationedit', $data);   
	   }
 
    public function delete($id = null)
    {
        $this->permission->module('room_reservation','delete')->redirect();
		
		if ($this->roomreservation_model->delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('room_reservation/booking-list');
    }
 	public function checkroom(){
	    $guest =$this->input->post('guest',true);
		$roomname=$this->input->post('room_name',true);
		$checkin=$this->input->post('check_in',true);
		$checkout=$this->input->post('check_out',true);
		$status=1;
		$exits = $this->db->select("*")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$exit = $this->db->select("*")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$check = $this->db->select("*")->from('booked_info')->where('checkindate>',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<=',$checkin)->where('checkoutdate>',$checkin)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate<',$checkout)->where('checkoutdate>=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('booked_info')->where('checkindate>=',$checkin)->where('checkoutdate<=',$checkout)->where('bookingstatus!=',$status)->where('roomid',$roomname)->group_by('checkindate')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(roomid) as totalroom")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->row();
		$roomdetails=$this->db->select("*")->from('roomdetails')->where('roomid',$roomname)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('roomid',$roomname)->get()->result();
		$roomlist='';
		foreach($numberlist as $singleno){
			$roomlist.=$singleno->roomno.',';
			}
		$gtroomno=rtrim($roomlist,',');
		if(empty($exits)&&empty($exit)&&empty($check)){
				$data['freeroom']=$gtroomno;
				$data['isfound']=0;
			}
		else{
			$bookedroom="";
			if(!empty($exit)){
			foreach($exits as $booked){
				$bookedroom.=$booked->room_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->room_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->room_no.',';
				}
			}
			
		$getbookedall=rtrim($bookedroom,',');
		$allbokedroom1=$totalroom1->allroom;
		$allbokedroom2=$totalroom2->allroom;
		$allbokedroom=max((int)$allbokedroom1,(int)$allbokedroom2,(int)$allbokedroom3);
		$allfreeroom=$totalroomfound->totalroom;
				if($allfreeroom>$allbokedroom){
					$output=$this->Differences($getbookedall, $gtroomno);
					if(!empty($output)){
					$data['freeroom']=$output;
					$data['isfound']='1';
						}
					else{
						$data['freeroom']='';
						$data['isfound']='2';
						}
				}
				else{
					$data['freeroom']='';
					$data['isfound']='2';
					}
			}
		$data['checkin']=$checkin;
		$data['checkout']=$checkout;
		$data['guest']=$guest;
		$data['roomno']=$roomname;
		$data['roominfo']=$roomdetails;
		$data['chargeinfo']=$this->roomreservation_model->chargeinfo();
		$data['module'] = "room_reservation";
	    $data['page']   = "bookinginfo";   
	    $this->load->view('room_reservation/bookinginfo', $data);  
	 }
	 
	 public function Differences ($Arg1, $Arg2){
		$Arg1 = explode (',', $Arg1);
		$Arg2 = explode (',', $Arg2);
	
		$Difference_1 = array_diff($Arg1, $Arg2);
		$Difference_2 = array_diff($Arg2, $Arg1);
		$Diff = array_merge($Difference_1, $Difference_2);
		$Difference = implode(',', $Diff);
		return $Difference;
	}
	public function detailView($id){
	   $data["bookinginfo"] = $this->roomreservation_model->findBookingDetail($id);
	   //print_r($data["bookinginfo"]); die();
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $data["paymentlist"] = $this->roomreservation_model->findBypayId($id);
	   $data['module'] = "room_reservation";
	   $data['page']   = "reservationdetail";   
	    echo Modules::run('template/layout', $data); 
	}
  public function paymentsdatatable($id){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'tbl_guestpayments.invoice', 
		1 => 'bookingnumber', 
		2 => 'paydate',
		3 => 'paymenttype',
		4 => 'paymentamount',
	);

	$where = $sqlTot = $sqlRec = "";
	// check search value exist
	if(!empty($params['search']['value']) ) {   
		$where .=" WHERE ";
		$where .=" ( tbl_guestpayments.invoice LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.bookingnumber LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.paydate LIKE '".$params['search']['value']."%' ";
		$where .=" OR tbl_guestpayments.paymentamount LIKE '".$params['search']['value']."%' ";
		$where .=" OR payment_method.payment_method LIKE '".$params['search']['value']."%' )";

	}
	// getting total number records without any search
	$sql = "SELECT tbl_guestpayments.*,booked_info.bookedid,payment_method.payment_method FROM tbl_guestpayments Left Join booked_info ON booked_info.booking_number=tbl_guestpayments.bookingnumber Left Join payment_method ON payment_method.payment_method_id=tbl_guestpayments.paymenttype where booked_info.bookedid='$id'";
	
	
	$sqlTot .= $sql;
	$sqlRec .= $sql;
	//concatenate search sql if value exist
	if(isset($where) && ($where != '')) {
		$sqlTot .= $where;
		$sqlRec .= $where;
	}
	
 	$sqlRec .=  " ORDER BY ".$columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";
	$SQLtotal=$this->db->query($sqlTot);
	$SQLoffer=$this->db->query($sqlRec);
	$totalRecords = $SQLtotal->num_rows();	
	$queryRecords=$SQLoffer->result();
	$i=0;
	foreach($queryRecords as  $value){
		$i++;
		$row = array();
		$update='';
		$delete='';
		if($this->permission->method('room_reservation','update')->access()):
		$update='<a onclick="editpayment(\''.$value->payid.'\',\''.$value->bookedid.'\',\''.$value->bookingnumber.'\',\''.$value->invoice.'\',\''.$value->paydate.'\',\''.$value->paymenttype.'\',\''.$value->paymentamount.'\')" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Update"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
		endif;
		 if($this->permission->method('room_reservation','create')->access()):
		 $Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->bookedid.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
		 endif;
		
		$row[] =$value->invoice;
		$row[] =$value->bookingnumber;
		$row[] =$value->paydate;
		$row[] =$value->payment_method;
		$row[] =$value->paymentamount;
		$row[] =$update;
        $data[] = $row;
		
		}
	
	$json_data = array(
			"draw"            => intval( $params['draw'] ),   
			"recordsTotal"    => intval( $totalRecords ),  
			"recordsFiltered" => intval($totalRecords),
			"data"            => $data   // total data array
			);

	echo json_encode($json_data);
	}
  public function payments($id){
	   $data["bookinginfo"] = $this->roomreservation_model->findById($id);
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $data["paymentlist"] = $this->roomreservation_model->findBypayId($id);
	   $payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
		if(!empty($payinfo)){
		$invoicenum=$payinfo->invoice;
		}
		else{
			$invoicenum = "000000"; 
			}
		$nextno=$invoicenum+1;
		$bk_length = strlen((int)$nextno); 
		$bkstr = '000000';
		$bknumber = substr($bkstr, $bk_length); 
		
		
		
		 
		
		
		
		
		
		
		$data['invoice'] = $bknumber.$nextno;  
	    $data['title'] = "payments";
	    $data['module'] = "room_reservation";
	    $data['page']   = "payments";   
	    echo Modules::run('template/layout', $data); 
	  }
 public function addpayment($bid){
	  $data['title'] = "Add Payment";
	  $this->form_validation->set_rules('booking_number',display('booking_number'),'required|xss_clean');
	  $this->form_validation->set_rules('invoice_no',display('invoice_no'),'required|xss_clean');
	  $this->form_validation->set_rules('pay_date',display('pay_date'),'required|xss_clean');
	  $this->form_validation->set_rules('payment_method',display('payment_method'),'required|xss_clean');
	  $this->form_validation->set_rules('amount',display('amount'),'required|xss_clean');
	  $saveid=$this->session->userdata('id');
	  $id= $this->input->post('payid', TRUE);
	  $data['intinfo']="";
	  // Find the acc COAID for the Transaction
	   $thisbookinfo = $this->db->select('cutomerid')->from('booked_info')->where('bookedid',$bid)->get()->row();
	   $customerid=$thisbookinfo->cutomerid;
	   $cusifo = $this->db->select('*')->from('customerinfo')->where('customerid',$customerid)->get()->row();
	   $headn = $cusifo->customernumber.'-'.$cusifo->firstname.' '.$cusifo->lastname;
	   $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
	   $customer_headcode = 102030101;
	   $invoice_no=$this->input->post('invoice_no',TRUE);
	   $newdate= date('d-m-Y');
	
	  if ($this->form_validation->run()) { 
	   if(empty($this->input->post('payid', TRUE))) {
		   $total_amount = $this->input->post('total_amount',TRUE);
			$paid_amount = $this->input->post('amount',TRUE);
			if($total_amount-$paid_amount>=0){
			$this->db->set('paid_amount', 'paid_amount+'.$paid_amount, FALSE);
			$this->db->where('bookedid', $bid);
			$this->db->update('booked_info');

			$data['room_reservation']   = (Object) $postData = array(
		   'payid'     	     		 => $this->input->post('payid', TRUE),
		   'bookingnumber' 	         => $this->input->post('booking_number',TRUE),
		   'invoice' 	             => $this->input->post('invoice_no',TRUE),
		   'paydate' 	             => $this->input->post('pay_date',TRUE),
		   'paymenttype' 	         => $this->input->post('payment_method',TRUE),
		   'paymentamount' 	         => $this->input->post('amount',TRUE),
		  );
		}else{
			$this->session->set_flashdata('exception',  display('pay_exact_amount'));
			redirect("room_reservation/payment-information/".$bid);
		}
		$this->permission->method('room_reservation','create')->redirect();
		if($this->roomreservation_model->createpayment($postData)) { 

			//Customer debit for Rent Value
			$invoice_no=$this->input->post('invoice_no',TRUE);
			$newdate= date('d-m-Y');
			$narration = 'Customer debit for Rent Invoice# '.$invoice_no;
			$amount = $this->input->post('amount',TRUE);
			transaction($invoice_no, 'CIV', $newdate, $customer_headcode, $narration, $amount, 0, 0, 1, $saveid, $newdate, 1);
			//Hotel Owner credit for Rent Value
			$narration = 'Hotel Credit for Rent Invoice# '.$invoice_no;
			transaction($invoice_no, 'CIV', $newdate, 10107, $narration, 0, $amount, 0, 1, $saveid, $newdate, 1);
				
			// Customer Credit for paid amount.
			$narration = 'Customer Credit for Rent Invoice# '.$invoice_no;
			transaction($invoice_no, 'CIV', $newdate, $customer_headcode, $narration, 0, $amount, 0, 1, $saveid, $newdate, 1);
		
			//Cash In hand Debit for paid value
			$narration = 'Cash in hand Debit For Invoice# '.$invoice_no;
			transaction($invoice_no, 'CIV', $newdate, 1020101, $narration, $amount, 0, 0, 1, $saveid, $newdate, 1);		
		
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('room_reservation/payment-information/'.$bid);
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/payment-information/".$bid); 
	
	   } else {
		$this->permission->method('room_reservation','update')->redirect();
		$data['room_reservation']   = (Object) $postData = array(
		   'payid'     	     		 => $this->input->post('payid', TRUE),
		   'bookingnumber' 	         => $this->input->post('booking_number',TRUE),
		   'invoice' 	             => $this->input->post('invoice_no',TRUE),
		   'paydate' 	             => $this->input->post('pay_date',TRUE),
		   'paymenttype' 	         => $this->input->post('payment_method',TRUE),
		   'paymentamount' 	         => $this->input->post('amount',TRUE),
		  );
	 
		if ($this->roomreservation_model->updatepayment($postData)) { 
		$crtransac = $this->db->select('*')->from('acc_transaction')->where('COAID',$customer_headcode)->where('VNo',$invoice_no)->where('Credit>',0)->get()->row();
		$detransac = $this->db->select('*')->from('acc_transaction')->where('COAID',$customer_headcode)->where('VNo',$invoice_no)->where('Debit>',0)->get()->row();
		$storetransac = $this->db->select('*')->from('acc_transaction')->where('COAID','10107')->where('VNo',$invoice_no)->get()->row();
		$cashtransac = $this->db->select('*')->from('acc_transaction')->where('COAID','1020101')->where('VNo',$invoice_no)->get()->row();
		
		//Customer debit for Product Value
		$saveddate=date("d-m-Y");
				 $cosdr = array(
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate,
				); 
				$this->db->where('ID',$detransac->ID);
			    $this->db->update('acc_transaction',$cosdr);
				 //Store credit for Product Value
				  $sc =array(
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate,
				);  
				$this->db->where('ID',$storetransac->ID);
			    $this->db->update('acc_transaction',$sc);
				 
				 // Customer Credit for paid amount.
				  $cc =array(
				  'Credit'         =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate
				);  
				$this->db->where('ID',$crtransac->ID);
			    $this->db->update('acc_transaction',$cc);
				
				 //Cash In hand Debit for paid value
				 $cdv = array(
				  'Debit'          =>  $this->input->post('amount',TRUE),
				  'CreateBy'       => $saveid,
				  'UpdateDate'     => $saveddate
				); 
				 $this->db->where('ID',$cashtransac->ID);
			    $this->db->update('acc_transaction',$cdv);
		
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("room_reservation/payment-information/".$bid);  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('bed_edit');
		$data['intinfo']   = $this->roomreservation_model->findById($id);
	   }
	   
	   $data["bookinginfo"] = $this->roomreservation_model->findById($bid);
	   $data["paymentmethod"] = $this->roomreservation_model->paymentlist();
	   $payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
		if(!empty($payinfo)){
		$invoicenum=$payinfo->invoice;
		}
		else{
			$invoicenum = "000000"; 
			}
		$nextno=$invoicenum+1;
		$bk_length = strlen((int)$nextno); 
		$bkstr = '000000';
		$bknumber = substr($bkstr, $bk_length); 
		$data['invoice'] = $bknumber.$nextno;  
	    $data['module'] = "room_reservation";
	    $data['page']   = "payments";   
	    echo Modules::run('template/layout', $data); 
	   }   
	 }
	 public function smpooldetails(){
		$pid = $this->input->post('pid',TRUE);
		$mpool = explode(",,", $pid);
		$cid = explode(",", $mpool[0]);
		$cinfo = $this->roomreservation_model->poolcastinfodata($cid[0]);
		for($i=0;$i<count($mpool);$i++){
			$spool = explode(",", $mpool[$i]);
			for($j=0;$j<count($spool);$j++){
				$pitemlist[$i][$j]    = $this->roomreservation_model->pitemlistdata($spool[$j]);
				$pitemrow[$i][$j]     = $this->roomreservation_model->pitemdatarow($spool[$j]);
			}
		}
		$data['poolcastinfo']  = $cinfo;
		$data['pitemlist']     = $pitemlist;
		$data['pitemdata']     = $pitemrow;
        $data['currency']    = getCurrency(); 
        $this->load->view('room_reservation/poolprintview', $data); 
	}
	public function restaurantDetails(){
		$rid = $this->input->post('order_id', TRUE);
		$mrid = explode(",,", $rid);
		$cid = explode(",", $mrid[0]);
		$cinfo = $this->roomreservation_model->restaurantCust($cid[0]);
		for($i=0;$i<count($mrid);$i++){
			$srid = explode(",", $mrid[$i]);
			for($j=0;$j<count($srid);$j++){
				$ritems[$i][$j]     = $this->roomreservation_model->ritemdatasingle($srid[$j]);
			}
		}
		$data['rcustomer']  = $cinfo;
		$data['ritems']     = $ritems;
		$data['currency']    = getCurrency(); 
		$this->load->view('room_reservation/restaurantbillprint', $data); 
	}
	public function hallDetails(){
		$rid = $this->input->post('hall_id', TRUE);
		$mrid = explode(",,", $rid);
		$cid = explode(",", $mrid[0]);
		$cinfo = $this->roomreservation_model->hallRoomCust($cid[0]);
		for($i=0;$i<count($mrid);$i++){
			$srid = explode(",", $mrid[$i]);
			for($j=0;$j<count($srid);$j++){
				$ritems[$i][$j]     = $this->roomreservation_model->hallDetailsList($srid[$j]);
			}
		}
		$data['rcustomer']  = $cinfo;
		$data['ritems']     = $ritems;
		$data['currency']    = getCurrency(); 
		$this->load->view('room_reservation/hallroombillprint', $data); 
	}
	public function parkingDetails(){
		$rid = $this->input->post('parking_id', TRUE);
		$mrid = explode(",,", $rid);
		$cid = explode(",", $mrid[0]);
		$cinfo = $this->roomreservation_model->carParkingCust($cid[0]);
		for($i=0;$i<count($mrid);$i++){
			$srid = explode(",", $mrid[$i]);
			for($j=0;$j<count($srid);$j++){
				$ritems[$i][$j]     = $this->roomreservation_model->parkingDetailsList($srid[$j]);
			}
		}
		$data['rcustomer']  = $cinfo;
		$data['ritems']     = $ritems;
		$data['currency']    = getCurrency(); 
		$this->load->view('room_reservation/carparkingbillprint', $data); 
	}
	public function viewdetailsprint($id,$pdf=null){
	   
		$details=$this->roomreservation_model->details($id);
		$data['bookinfo']   = $details;
	    $data['bookingdata'] = $this->roomreservation_model->detailbooking($id);
		$data['customerinfo']   = $this->roomreservation_model->customerinfo($details->cutomerid);
		$data['paymentinfo']   = $this->roomreservation_model->paymentinfo($details->bookedid);
		$data['storeinfo']=$this->roomreservation_model->storeinfo();
		$data['taxinfo']=$this->roomreservation_model->taxinfo();
		$data['paymentinfo_withadv']   = $this->roomreservation_model->paymentinfo_withadv($details->bookedid);
		$data['btaxinfo']=$this->roomreservation_model->btaxinfo($id);
		$data['commominfo']=$this->roomreservation_model->commoninfo();
		$data['currency']=$this->roomreservation_model->currencysetting($data['storeinfo']->currency); 
        
        $content = $this->load->view('bookindetailsprint', $data, true);

	    $dompdf = new Dompdf();

	    $dompdf->loadHtml($content);

	    $dompdf->setPaper('A4', 'portrait');

	    $dompdf->render();

	    $filename = 'invoice_' . $data['bookinfo']->booking_number . '.pdf';

	    if (empty($pdf)) {
	    	//echo $content; exit;
	        $dompdf->stream($filename, array('Attachment' => 0));
	    } else {
	        return $content;
	    }
	}
		public function viewdetails_advprint($id,$pdf=null){
		$details=$this->roomreservation_model->details($id);
		$data['bookinfo']   = $details;
		$data['customerinfo']   = $this->roomreservation_model->customerinfo($details->cutomerid);
	//	$data['paymentinfo']   = $this->roomreservation_model->paymentinfo_withadv($details->bookedid);
	$data['paymentinfo']  = $this->db->select("*")->from("tbl_guestpayments")->where('tbl_guestpayments.bookedid',$details->bookedid)->where("book_type",0)->get()->row();
//print_r($data['paymentinfo']);
		$data['storeinfo']=$this->roomreservation_model->storeinfo();
		$data['taxinfo']=$this->roomreservation_model->taxinfo();
		$data['btaxinfo']=$this->roomreservation_model->btaxinfo($id);
		$data['commominfo']=$this->roomreservation_model->commoninfo();
		$data['currency']=$this->roomreservation_model->currencysetting($data['storeinfo']->currency);  
	//	print_r($data);die();
		if(empty($pdf)){
			$this->load->view('room_reservation/advance_receipt', $data); 
		}else{
			$pdfhtml = $this->load->view('room_reservation/advance_receipt', $data, true);
			return $pdfhtml;
		}
	}
	public function advance_payment($bookedid, $paymentmode, $advanceamount,$id){
		$payment = $this->db->select("invoice")->from("tbl_guestpayments")->where("bookedid", $bookedid)->where("book_type",0)->get()->row();
		$invoice = $this->db->select("invoice")->from("tbl_guestpayments")->where("bookedid", $bookedid)->where("book_type",0)->where("paymentamount<>", $advanceamount)->get()->row();
	//	echo $paymentmode;

		if((!empty($invoice->invoice) | $id!=1 | empty($payment->invoice))){
			if($id==1 & !empty($invoice->invoice)){
				//Payment record
				$cardNumber = $this->input->post('cardno', TRUE);
				$bankName = $this->input->post('bankname', TRUE);
				$newdate = date("d-m-Y H:i:s");
				$saveid = $this->session->userdata('id');
				$postData = array(
					'paydate' 	             => $newdate,
					'paymenttype' 	         => $paymentmode,
					'paymentamount' 	     => $advanceamount,
					//'details' 	     		 => "Advance in Card/Account No: ".$cardNumber." Bank Name: ".$bankName,
				);
				 if ($paymentmode == "UPI") {
                    $upi_referenceno = $this->input->post('upi_referenceno', TRUE);
                    $postData['details'] = "Advance in UPI Reference No: " . $upi_referenceno;
                } elseif ($paymentmode == "RTGS") {
                    $rtgs_referenceno = $this->input->post('rtgs_referenceno', TRUE);
                    $postData['details'] = "Advance in RTGS Reference No: " . $rtgs_referenceno;
                } elseif ($paymentmode == "Cheque") {
                    $cheque_no = $this->input->post('cheque_no', TRUE);
                    $cheque_date = $this->input->post('cheque_date', TRUE);
                    $postData['details'] = "Advance in Cheque: ". $cheque_no . '/' . $cheque_date;
                } elseif ($paymentmode == "Bank Payment") {
                    $bankname = $this->input->post('bankname', TRUE);
                       $accountnumber = $this->input->post('accountnumber', TRUE);
                    $postData['details'] = "Advance in Bank Payment Reference No: " . $bankname ."-".$accountnumber;
                } elseif ($paymentmode == "Card Payment") {
                    $cardno = $this->input->post('cardno', TRUE);
                    $expirydate = $this->input->post('expirydate', TRUE);
                    $ccv = $this->input->post('ccv', TRUE);
                  $postData['details'] = "Advance in Card Number: " . $cardno . '-' . $expirydate . '-' . $ccv;
                }elseif ($paymentmode == "Cash") {
                   
                    $postData['details'] = "Advance in Cash";
                }elseif ($paymentmode == "Bill to company") {
                   
                    $postData['details'] = "Advance in Bill to Company";
                }
				$old_mode = $this->db->select("paymenttype")->from("tbl_guestpayments")->where('bookedid',$bookedid)->get()->row();
				if($old_mode->paymenttype=="SSLCommerz"){
					$old_code = 102010302;
				}
				else if($old_mode->paymenttype=="Cash Payment"){
					$old_code = 1020101;
				}
				else if($old_mode->paymenttype=="Paypal"){
					$old_code = 102010301;
				}
				else if($old_mode->paymenttype=="Card Payment"){
					$old_code = 102010304;
				}else{
					$path = 'application/modules/';
						$map  = directory_map($path);
						$HmvcMenu   = array();
						if (is_array($map) && sizeof($map) > 0)
						foreach ($map as $key => $value) {
							$env = str_replace("\\", '/', $path.$key.'assets/data/env'); 
							$transaction = str_replace("\\", '/', $path.$key.'controllers/transaction.php'); 
							if (file_exists($env)) {
								if (file_exists($transaction)) {
									@include($transaction);
									if($old_mode->paymenttype==$paymentMethod){
										$old_code = $headCode;
									}
								}
							}
						}
				}
				$this->db->where('bookedid',$bookedid)->update("tbl_guestpayments",$postData);
			//	echo $this->db->last_query();die();
				//Payment method Debit for paid value
				$acc_id = $this->db->select("ID")->from("acc_transaction")->where('VNo',$invoice->invoice)->order_by("ID","ASC")->get()->result();
				if($paymentmode=="Bank Payment"){
				$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$bankName'");
				$row = $query->row();
				$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
				if(empty($headcode)){
					$coa = $this->roomreservation_model->headcode(4,1020102);
					if($coa->HeadCode!=NULL){
						$headcode=$coa->HeadCode+1;
					}
					else{
						$headcode="102010201";
					}
					//insert Coa for Customer Receivable
					$postData1['HeadCode']   	=$headcode;
					$postData1['HeadName']   	=$bankName;
					$postData1['PHeadName']   	='Cash At Bank';
					$postData1['HeadLevel']   	='4';
					$postData1['IsActive']  	='1';
					$postData1['IsTransaction'] ='1';
					$postData1['IsGL']   		='0';
					$postData1['HeadType']  	='A';
					$postData1['IsBudget'] 		='0';
					$postData1['IsDepreciation']='0';
					$postData1['DepreciationRate']='0';
					$postData1['CreateBy'] 		=$saveid;
					$postData1['CreateDate'] 	=$newdate;
					$this->db->insert('acc_coa',$postData1);
					//end
				}
				$narration = 'Cash in Bank Debited For advance payment '.$bankName.' Invoice# '.$invoice->invoice;
				transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, $headcode, $narration, $old_code);
			}
			else if($paymentmode=="SSLCommerz"){
				$narration = 'Cash in SSLCOMMERZ Debited For advance payment Invoice# '.$invoice->invoice;
				transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 102010302, $narration, $old_code);
			}
			else if($paymentmode=="Cash Payment"){
				$narration = 'Cash in Hand Debited For advance payment Invoice# '.$invoice->invoice;
				transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 1020101, $narration, $old_code);
			}
			else if($paymentmode=="Paypal"){
				$narration = 'Cash in Paypal Debited For advance payment Invoice# '.$invoice->invoice;
				transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 102010301, $narration, $old_code);
			}
			else if($paymentmode=="Card Payment"){
				$narration = 'Cash in Card Debited For advance payment Invoice# '.$invoice->invoice;
				transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 102010304, $narration, $old_code);
			}
			else{
					$path = 'application/modules/';
						$map  = directory_map($path);
						$HmvcMenu   = array();
						if (is_array($map) && sizeof($map) > 0)
						foreach ($map as $key => $value) {
							$env = str_replace("\\", '/', $path.$key.'assets/data/env'); 
							$transaction = str_replace("\\", '/', $path.$key.'controllers/transaction.php'); 
							if (file_exists($env)) {
								if (file_exists($transaction)) {
									@include($transaction);
									if($paymentmode==$paymentMethod){
										$narration = 'Cash in '.$paymentMethod.' Debited For advance payment Invoice# '.$invoice->invoice;
										transaction_update($acc_id[0]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, $headCode, $narration, $old_code);
									}
								}
							}
						}
				$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020103%' And HeadName LIKE '$pmethod'");
				$row = $query->row();
				$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
			  }
				//hotel service credited for advance rent
				transaction_update($acc_id[1]->ID, $invoice->invoice, $newdate, 0, $advanceamount, $saveid, $newdate, 30301);
				//Customer debited for advance room booking
				transaction_update($acc_id[2]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 102030101);
				//Customer credited for advance payment
				transaction_update($acc_id[3]->ID, $invoice->invoice, $newdate, 0, $advanceamount, $saveid, $newdate, 102030101);
			}else{
				$cardNumber = $this->input->post('cardno', TRUE);
				$bankName = $this->input->post('bankname', TRUE);
				//insert payment
				$payinfo=$this->db->select("*")->from('tbl_guestpayments')->order_by('payid','desc')->get()->row();
				if(!empty($payinfo)){
				//	$invoicenum=$payinfo->invoice;
						$invoicenum=$payinfo->invoice;
            			$currentYear = date('Y');
            			$currentMonth = date('m');
            			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            			$prefix = "FO" . $financialYearStart;
            			$lastBookedId = isset($bookno) && !empty($bookno) ? $bookno : $prefix . "0000";
            			$numericPart = intval(substr($lastBookedId, strlen($prefix)));
            			$nextNumericPart = $numericPart + 1;
            			$invoice_no = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
				}else{
					$currentYear = date('Y');
            			$currentMonth = date('m');
            			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            			$prefix = "FO" . $financialYearStart;
            			$lastBookedId = isset($bookno) && !empty($bookno) ? $bookno : $prefix . "0000";
            			$numericPart = intval(substr($lastBookedId, strlen($prefix)));
            			$nextNumericPart = $numericPart + 1;
            			$invoice_no = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
				}
			//	$nextno=$invoicenum+1;
					$invoicenum=$invoicenum+1;
            			$currentYear = date('Y');
            			$currentMonth = date('m');
            			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            			$prefix = "FO" . $financialYearStart;
            			$lastBookedId = isset($bookno) && !empty($bookno) ? $bookno : $prefix . "0000";
            			$numericPart = intval(substr($lastBookedId, strlen($prefix)));
            			$nextNumericPart = $numericPart + 1;
            			$invoice_no = $prefix . str_pad($nextNumericPart, 4, '0', STR_PAD_LEFT);
				$saveid = $this->session->userdata('id');
					$newdate = date("d-m-Y H:i:s");
		
				$postData = array(
					'bookedid' 	         	 => $bookedid,
					'invoice' 	             => $invoice_no,
					'paydate' 	             => $newdate,
					'paymenttype' 	         => $paymentmode,
					'paymentamount' 	     => $advanceamount,
				//	'details' 	     		 => "Advance in Card/Account No: ".$cardNumber." Bank Name: ".$bankName,
					'book_type' 	     	 => 0,
					);
								 if ($paymentmode == "UPI") {
                    $upi_referenceno = $this->input->post('upi_referenceno', TRUE);
                    $postData['details'] = "Advance in UPI Reference No: " . $upi_referenceno;
                } elseif ($paymentmode == "RTGS") {
                    $rtgs_referenceno = $this->input->post('rtgs_referenceno', TRUE);
                    $postData['details'] = "Advance in RTGS Reference No: " . $rtgs_referenceno;
                } elseif ($paymentmode == "Cheque") {
                    $cheque_no = $this->input->post('cheque_no', TRUE);
                    $cheque_date = $this->input->post('cheque_date', TRUE);
                   $postData['details'] = "Advance in Cheque: ". $cheque_no . '/' . $cheque_date;
                } elseif ($paymentmode == "Bank Payment") {
                    $bankname = $this->input->post('bankname', TRUE);
                       $accountnumber = $this->input->post('accountnumber', TRUE);
                    $postData['details'] = "Advance in Bank Payment Reference No: " . $bankname ."-".$accountnumber;
                } elseif ($paymentmode == "Card Payment") {
                    $cardno = $this->input->post('cardno', TRUE);
                    $expirydate = $this->input->post('expirydate', TRUE);
                    $ccv = $this->input->post('ccv', TRUE);
                   $postData['details'] = "Advance in Card Number: " . $cardno . '-' . $expirydate . '-' . $ccv;
                }elseif ($paymentmode == "Cash") {
                   
                    $postData['details'] = "Advance in Cash";
                }elseif ($paymentmode == "Bill to company") {
                   
                    $postData['details'] = "Advance in Bill to Company";
                }
				//	print_r($postData);die();
				$this->db->insert('tbl_guestpayments', $postData);
			//	echo "1".$this->db->last_query();die();
				//Payment method Debit for paid value
				if($paymentmode=="Bank Payment"){
					$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$bankName'");
					$row = $query->row();
					$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
					if(empty($headcode)){
						$coa = $this->roomreservation_model->headcode(4,1020102);
						if($coa->HeadCode!=NULL){
							$headcode=$coa->HeadCode+1;
						}
						else{
							$headcode="102010201";
						}
						//insert Coa for Customer Receivable
						$postData1['HeadCode']   	=$headcode;
						$postData1['HeadName']   	=$bankName;
						$postData1['PHeadName']   	='Cash At Bank';
						$postData1['HeadLevel']   	='4';
						$postData1['IsActive']  	='1';
						$postData1['IsTransaction'] ='1';
						$postData1['IsGL']   		='0';
						$postData1['HeadType']  	='A';
						$postData1['IsBudget'] 		='0';
						$postData1['IsDepreciation']='0';
						$postData1['DepreciationRate']='0';
						$postData1['CreateBy'] 		=$saveid;
						$postData1['CreateDate'] 	=$newdate;
						$this->db->insert('acc_coa',$postData1);
						//end
					}
					$narration = 'Cash in Bank Debited For advance payment '.$bankName.' Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, $headcode, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else if($paymentmode=="SSLCommerz"){
					$narration = 'Cash in SSLCOMMERZ Debited For advance payment Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102010302, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else if($paymentmode=="Cash Payment"){
					$narration = 'Cash in Hand Debited For advance payment Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 1020101, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else if($paymentmode=="Paypal"){
					$narration = 'Cash in Paypal Debited For advance payment Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102010301, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else if($paymentmode=="Card Payment"){
					$narration = 'Cash in Card Debited For advance payment Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102010304, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else if($paymentmode=="Paystack"){
					$narration = 'Cash in Paystack Debited For advance payment Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102010303, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				}
				else{
						$path = 'application/modules/';
						$map  = directory_map($path);
						$HmvcMenu   = array();
						if (is_array($map) && sizeof($map) > 0)
						foreach ($map as $key => $value) {
							$env = str_replace("\\", '/', $path.$key.'assets/data/env'); 
							$transaction = str_replace("\\", '/', $path.$key.'controllers/transaction.php'); 
							if (file_exists($env)) {
								if (file_exists($transaction)) {
									@include($transaction);
									if($paymentmode==$paymentMethod){
										$narration = 'Cash in '.$paymentMethod.' Debited For advance payment Invoice# '.$invoice_no;
										transaction($invoice_no, 'CIV', $newdate, $headCode, $narration, $advanceamount, 0, 0, 1, $saveid, $newdate, 1);
									}
								}
							}
						}
					$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020103%' And HeadName LIKE '$paymentmode'");
					$row = $query->row();
					$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
				}
				//hotel service credited for advance rent
				$narration = 'Hotel credited for room advance rent Invoice# '.$invoice_no;
				transaction($invoice_no, 'CIV', $newdate, 30301, $narration, 0, $advanceamount, 0, 1, $saveid, $newdate, 1);
				//Customer debited for advance room booking
				$narration = 'Hotel customer debited for advance room booking Invoice# '.$invoice_no;
				transaction($invoice_no, 'CIV', $newdate, 102030101, $narration,$advanceamount, 0, 0, 1, $saveid, $newdate, 1);
				//Customer credited for advance payment
				$narration = 'Hotel customer credited for advance room booking Invoice# '.$invoice_no;
				transaction($invoice_no, 'CIV', $newdate, 102030101, $narration,0, $advanceamount, 0, 1, $saveid, $newdate, 1);
			}
		}
	}
public function room_status($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();				
		//update as available if time ended
		$this->db->set('tbl_roomnofloorassign.status',1);
		$this->db->where('booked_info.checkoutdate<',date("d-m-Y H:i:s"));
		$this->db->where('tbl_roomnofloorassign.status<>',1);
		$this->db->update('tbl_roomnofloorassign JOIN booked_info ON FIND_IN_SET(tbl_roomnofloorassign.roomno,booked_info.room_no)<>0');
		//update as booked if time is not ended
		$this->db->set('tbl_roomnofloorassign.status',2);
		$this->db->where('booked_info.checkindate<',date("d-m-Y H:i:s"));
		$this->db->where('booked_info.checkoutdate>',date("d-m-Y H:i:s"));
		$this->db->where('tbl_roomnofloorassign.status<>',2);
		$this->db->where_in('booked_info.bookingstatus',array(0,4));
		$this->db->update('tbl_roomnofloorassign JOIN booked_info ON FIND_IN_SET(tbl_roomnofloorassign.roomno,booked_info.room_no)<>0');
        $data['title']    = display('room_reservation');
		$hall_room = $this->db->where('directory', 'hall_room')->where('status', 1)->get('module')->num_rows();
        if ($hall_room == 1) {
			$data["roomlist"] = $this->db->select('*')->from('tbl_roomnofloorassign')->where('roomid<>',NULL)->get()->result();
		}else{
			$data["roomlist"] = $this->roomreservation_model->get_all('*','tbl_roomnofloorassign','floorid');
		}
	    $data["floordetails"] = $this->roomreservation_model->floorwithRoom();
		$data["problemList"] = $this->roomreservation_model->read2('*','tbl_note','note_id',array("status"=>0));
		$data["solvedList"] = $this->roomreservation_model->read2('*','tbl_note','note_id',array("status"=>1));
        $data['module'] = "room_reservation";
        $data['page']   = "roomlist";
        echo Modules::run('template/layout', $data);
    }
	public function roomlistDetail(){
		$bookedid = $this->input->post("bookedid", true);
		$dateTime = $this->input->post("datetime", true);
		$roomno = $this->input->post("roomno", true);
		$details = $this->db->select("b.*,c.*,rd.roomtype")->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->join("roomdetails rd","rd.roomid=b.roomid","left")->where("date(checkindate)<=",$dateTime)->where("date(checkoutdate)>=",$dateTime)->where("bookedid", $bookedid)->where_in("bookingstatus",array(0,4))->get()->row();
		$note = $this->db->select("*")->from("tbl_note")->where("bookedid", $bookedid)->where("roomno",$roomno)->get()->result();
		if(!empty($note)){
			$details->note = $note;
		}
		$tax = $this->db->select("rate")->from("tbl_taxmgt")->get()->result();
		$scharge = $this->db->select("servicecharge")->from("setting")->get()->row();
		$car_parking = $this->db->where('directory', 'car_parking')->where('status', 1)->get('module')->num_rows();
		if ($car_parking == 1) {
			$car_parking = $this->db->select("total_price")->from("tbl_bookParking")->where("bookedid",$details->bookedid)->get()->result();
		}
		$details->p_status = "No";
		$datediff = strtotime($details->checkoutdate) - strtotime($details->checkindate);
		$datediff = ceil($datediff/(60*60*24));
		$totalPrice = $details->total_price*$datediff;
		$totalTax = 0;
		$totalScharge = 0;
		$totalParking = 0;
		if(!empty($tax)){
			$rate = 0;
			foreach($tax as $val){
				$rate += $val->rate;
			}
			$totalTax = ($totalPrice*$rate)/100; 
		}
		if($scharge->servicecharge){
			$totalScharge = ($totalPrice*$scharge->servicecharge)/100; 
		}
		if(!empty($car_parking)){
			foreach($car_parking as $cp){
				$totalParking += $cp->total_price;
			}
			$details->p_status = "Yes";
		}
		$details->due_amount =($totalPrice+$totalTax+$totalScharge+$totalParking)-$details->paid_amount;
		if($details->due_amount<0){
			$details->due_amount = 0;
		}
		echo json_encode($details);
	}
	public function roomNoDetail(){
		$roomno = $this->input->post("roomno", true);
		$details = $this->db->select("rd.*")->from("roomdetails rd")->join("tbl_roomnofloorassign rfa","rfa.roomid=rd.roomid","left")->where("rfa.roomno",$roomno)->get()->row();
		echo json_encode($details);
	}
	public function searchResult($search=null,$key=null,$key1=null,$key2=null){
		$data['title']    = display('room_reservation'); 
		$data['module'] = "room_reservation";
		if($search!="null"){
			$hall_room = $this->db->where('directory', 'hall_room')->where('status', 1)->get('module')->num_rows();
			if ($hall_room == 1) {
				$data["roomlist"] = $this->db->select('*')->from('tbl_roomnofloorassign')->where('roomid<>',NULL)->get()->result();
			}else{
				if($key==""){
					$data["roomlist"] = $this->roomreservation_model->get_all('*','tbl_roomnofloorassign','floorid');
				}else{
					$data["roomlist"] = $this->roomreservation_model->matchedRooms($search);
				}
			}
		}
		else if($key!="null"){
			$data["roomlist"] = $this->roomreservation_model->matchedRooms(null, $key, $key1, $key2);
		}
		else if($key1!="null"){
			$data["roomlist"] = $this->roomreservation_model->matchedRooms(null, null, $key1, $key2);
		}
		else if($key2!="null"){
			$data["roomlist"] = $this->roomreservation_model->matchedRooms(null, null, null, $key2);
		}
		$this->load->view("room_reservation/roomlistSearch", $data);  
	}
	public function saveNote(){
		$note = $this->input->post("note", true);
		$bookedid = $this->input->post("bookedid", true);
		$roomno = $this->input->post("roomno", true);
		$insert = array(
			'note' => $note,
			'roomno' => $roomno, 
			'bookedid' => $bookedid, 
			'status' =>0 
		);
		$result = $this->db->insert("tbl_note", $insert);
		$note = $this->db->select("*")->from("tbl_note")->where("bookedid", $bookedid)->where("roomno",$roomno)->get()->result();
		if($result){
			$msg = '<h5>Success</h5>Saved Successfully'; 
			$data["note"] = $note;
			$data["msg"] = $msg;
			echo json_encode($data);
		}else{
			$msg = '<h5>Failed</h5>Please Try Again';
			$data["note"] = $note;
			$data["msg"] = $msg;
			echo json_encode($data);

		}
	}
	public function solveNote(){
		$id = $this->input->post("id", true);
		$bookedid = $this->input->post("bookedid", true);
		$roomno = $this->input->post("roomno", true);
		$update = array(
			'status' =>1 
		);
		$id = trim($id,",");
		$sl = explode(",",$id);
		for($i=0; $i<count($sl); $i++){
			$result = $this->db->where("note_id", $sl[$i])->update("tbl_note", $update);
		}
		$note = $this->db->select("*")->from("tbl_note")->where("bookedid", $bookedid)->where("roomno",$roomno)->get()->result();
		if($result){
			$msg = '<h5>Success</h5>Saved Successfully'; 
			$data["note"] = $note;
			$data["msg"] = $msg;
			echo json_encode($data);
		}else{
			$msg = '<h5>Failed</h5>Please Try Again';
			$data["note"] = $note;
			$data["msg"] = $msg;
			echo json_encode($data);

		}
	}
	public function email_send($binfo=null, $check=null, $path=null){
		if(!empty($binfo)){
			$appName = $this->db->select("title")->from("setting")->where("id",2)->get()->row();
			$subject = "Booking Successfull";
			$body = "Your Booking Successfully Completed.";
			if($check==4){
				$subject = "Checkin Successfull";
				$body = "Your Checkin Successfully Completed.";
			}
			if($check==5){
				$subject = "Checkout Successfull";
				$body = "Your Checkout Successfully Completed.";
			}
			$htmlContent = nl2br("Dear ".$binfo->firstname.",\n\n".$body."\nBooking Number:".$binfo->booking_number."\nRoom No:".$binfo->room_no."\nTotal Rent:".$binfo->total_price."\n\nThank You");
			$this->roomreservation_model->send_email(strtolower($binfo->email),$subject,$appName->title,$htmlContent,$path);
		}
	}
	
// 	public function checkout($id = null)
//     {
// 		$this->permission->method('room_reservation','read')->redirect();
				
//         $data['title']    = display('checkout'); 
// 		$data["checkinrooms"] = $this->db->select('b.bookedid,b.room_no,c.firstname')->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("b.bookingstatus",4)->get()->result();
//         $data['module'] = "room_reservation";
//         $data['page']   = "checkout";   
//         echo Modules::run('template/layout', $data); 
//     }

    public function viewCheckout($id = null)
    {
		$this->permission->method('room_reservation','read')->redirect();
				
        $data['title']    = display('checkout'); 
		$data["checkinrooms"] = $this->db->select('b.bookedid,b.room_no,c.firstname')->from("booked_info b")->join("customerinfo c","c.customerid=b.cutomerid","left")->where("b.bookingstatus",4)->get()->result();
        $data['module'] = "room_reservation";
        $data['page']   = "directcheckout";   
        echo Modules::run('template/layout', $data); 
    }
}

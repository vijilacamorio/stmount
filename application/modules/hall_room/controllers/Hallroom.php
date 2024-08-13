<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hallroom extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'hallroom_model'
		));	
    }
    public function bookingdatatable(){
		$params = $columns = $totalRecords = $data = array();
		$params = $_REQUEST;
	    $columns = array( 
		0 => 'tbl_hallroom_booking.hbid', 
		1 => 'invoice_no', 
		2 => 'hall_type',
		3 => 'tbl_hallroom_booking.hall_no',
		4 => 'customerinfo.firstname',
		5 => 'customerinfo.cust_phone',
		6 => 'start_date',
		7 => 'end_date',
		8 => 'payment_status',
		9 => 'status',
		10 => 'event_name',
		11 => 'event_type',
		12=> 'adv_amt',
		);

		$where = $sqlTot = $sqlRec = "";
		// check search value exist
		if(!empty($params['search']['value']) ) {   
			$where .=" WHERE ";
			$where .=" ( tbl_hallroom_booking.invoice_no LIKE '".$params['search']['value']."%' ";    
			$where .=" OR tbl_hallroom_booking.hall_type LIKE '".$params['search']['value']."%' ";
			$where .=" OR tbl_hallroom_booking.hall_no LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
			$where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
			$where .=" OR tbl_hallroom_booking.start_date LIKE '".$params['search']['value']."%' ";
			$where .=" OR tbl_hallroom_booking.end_date < '".$params['search']['value']."%' ";
			$where .=" OR tbl_hallroom_booking.status LIKE '1' )  AND (tbl_hallroom_booking.status=1)";

		}else{
			$where =" WHERE (tbl_hallroom_booking.status=1)";
		}
		// getting total number records without any search
		$sql = "SELECT tbl_hallroom_booking.*,customerinfo.firstname,customerinfo.cust_phone FROM tbl_hallroom_booking Left join customerinfo ON customerinfo.customerid=tbl_hallroom_booking.customerid";
	//	echo $sql;die();
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
		$queryRecords=$SQLoffer->result();	//print_r($queryRecords);die();
		$i=0;
		foreach($queryRecords as  $value){
			$i++;
			$row = array();
			$update='';
			$delete='';
			if($this->permission->method('hall_room','update')->access()):
			$update='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="halleditresrvation('.$value->hbid.')" class="btn btn-warning btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Update Reservation"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('hall_room','create')->access()):
			$Payment='<a href="'.base_url().'hall_room/payment-information/'.$value->hbid.'" class="btn btn-success btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
			endif;
			if($this->permission->method('hall_room','update')->access()):
			$cancel='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallcancelreservation('.$value->hbid.')" class="btn btn-danger btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Cancel" title="Cancel Reservation"><i class="ti-close text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('hall_room','update')->access()):
			$adv='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hall_adv_resrvation('.$value->hbid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Check In" title="Advance Receipt"><i class="ti-money text-white" aria-hidden="true"></i></a>';
			endif;
			if($this->permission->method('hall_room','update')->access()):
			$checkin='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallcheckinresrvation('.$value->hbid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Check In" title="Check-In"><i class="ti-check-box text-white" aria-hidden="true"></i></a>';
			endif;
			if($value->status==0){
				$status="Pending";
			}
			else if($value->status==1){
				$status="Booked";
			}
			else if($value->status==2){
				$status="Completed";
			}
			else if($value->status==3){
				$status="Canceled";
			}
			if($value->payment_status==1){
				$paymentStatus="Paid";
			}
			else if($value->payment_status==2){
				$paymentStatus="Partialy Paid";
			}
			else if($value->payment_status==3){
				$paymentStatus="Refunded";
			}
			else{
				$paymentStatus="Unpaid";
			}
			$allroomname = "";
			$row[] =$i;
			$row[] =$value->invoice_no;
			$rname = explode(",",$value->hall_type);
			if(empty($value->seatplan)){
				$plan ="None";
			}else{
				$seat = explode(",",$value->seatplan);
				$plan="";
				for($l=0;$l<count($seat); $l++){
					$seatplan = $this->db->select("plan_name")->from("tbl_hallroom_seatplan")->where("hsid",$seat[$l])->get()->row();
					if(empty($seatplan->plan_name)){
						$plan .= "None,";
					}else{
						$plan .= $seatplan->plan_name.",";
					}
				}
			}
			$allfacility = "";
			for($l=0;$l<count($rname); $l++){
				$roomname = $this->db->select("hall_type")->from("tbl_hallroom_info")->where("hid",$rname[$l])->get()->row();
				$allroomname .= $roomname->hall_type.", ";
				$facilities = $this->db->select("rfa.*, rfd.*")->from("roomfaility_ref_accomodation rfa")->join("roomfacilitydetails rfd", "rfd.facilityid=rfa.facilityid", "left")->where("hallid",$rname[$l])->get()->result();
				foreach($facilities as $fl){
					$allfacility .= $fl->facilitytitle.",";
				}
				$allfacility .= ",";
			}
			$row[] =trim($allroomname,", ");
		
			$row[] =$value->event_name;
	
			$row[] =$value->firstname;
			$row[] =$value->cust_phone;
			$row[] =$value->start_date;
			$row[] =$value->end_date;
			$row[] =$status;
			$row[] =$value->totalamount;
			$row[] =($value->totalamount - $value->adv_amt );
				$row[] =($value->adv_amt );
			$row[] =$paymentStatus;
	
			$row[] =$update.$cancel.$adv.$checkin;
	
		 
		
			$data[] = $row;
			
			}
			//echo $this->db->last_query();die();
		$json_data = array(
				"draw"            => intval( $params['draw'] ),   
				"recordsTotal"    => intval( $totalRecords ),  
				"recordsFiltered" => intval($totalRecords),
				"data"            => $data   // total data array
				);

		echo json_encode($json_data);
   	}
    public function index($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();
		$sc =array('status'         =>  2);  
		$this->db->where('status',1);
		$this->db->where_in('payment_status',array(1,3));
		$this->db->where('end_date<',date("d-m-Y H:i:s"));
		$this->db->update('tbl_hallroom_booking',$sc);
				
        $data['title']    = display('hall_room'); 
	    $data["halldetails"] = $this->hallroom_model->get_all('hid,hall_type','tbl_hallroom_info','hid');
	    $data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
	    $data["roomlist"] = $this->hallroom_model->allrooms();
		$data["customerlist"] = $this->hallroom_model->customerlist();
        $data['module'] = "hall_room";
        $data['page']   = "hallreservationlist";   
        echo Modules::run('template/layout', $data); 
    }
    public function hallroom_type($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();				
        $data['title']    = display('hallroom_type'); 
	    $data["hrdetails"] = $this->hallroom_model->hallroomread();
		$data["allsizes"] = $this->hallroom_model->allsizes();
		$data["seatplan"] = $this->hallroom_model->seatplan();
        $data['module'] = "hall_room";
        $data['page']   = "hallroomlist";   
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
			$user = $this->db->select('customerid,firstname,cust_phone')->from('customerinfo')->where("cust_phone LIKE '%$search%'")->get()->result_array();
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
		$this->permission->method('hall_room','read')->redirect();		
        $data['title']    = display('hall_room'); 
	    $data["bookingtype"] = $this->hallroom_model->get_all('*','bookingtype','booktypeid');
	    $data["hallroomdetails"] = $this->hallroom_model->get_all('hid,hall_type,rate,room_status','tbl_hallroom_info','hid');
	    $data["checkincustomer"] = $this->db->select("cust_phone,customerid,b.bookedid,b.booking_number")->from("customerinfo")->join("booked_info b","b.cutomerid=customerinfo.customerid")->where("b.checkindate<",date("d-m-Y H:i:s"))->where("b.checkoutdate>=",date("d-m-Y H:i:s"))->where("bookingstatus",4)->get()->result();
	   $data["paymentdetails"] = $this->hallroom_model->paymentlist();
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->hallroom_model->allrooms();
		$data["customerlist"] = $this->hallroom_model->customerlist();
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency(); 
		$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		// $scharge=$this->db->select("servicecharge")->from("setting")->get()->row();
		// $taxrate=0;
		// foreach($setting as $st){
		// 	$taxrate += $st->rate;
		// }   
		// $scharge->tax = $taxrate;
		$data["setting"] = $setting;
        $data['module'] = "hall_room";
        $data['page']   = "addereservation";   
        $this->load->view("hall_room/addreservation", $data); 
    }

 
    public function bookingedit($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();		
        $data['title']    = display('hall_room'); 
	    $data["bookingdata"] = $this->hallroom_model->editbooking($id);
		//print_r($data["bookingdata"]);
	    $data["bookingpaymentdata"] = $this->hallroom_model->booking_payment($id);
	    $data["guestdata"] = $this->db->select("customerid,guestname,mobile")->from("tbl_otherguest")->where("tbl_otherguest.bookedid", $id)->where('type',1)->get()->result();
	    $data["custdata"] = $this->db->select("tbl_hallroom_booking.customerid,firstname,cust_phone")->from("tbl_hallroom_booking")->join("customerinfo","customerinfo.customerid=tbl_hallroom_booking.customerid","left")->where("tbl_hallroom_booking.hbid", $id)->get()->result();
		$data["hallroomdetails"] = $this->hallroom_model->get_all('hid,hall_type,room_status','tbl_hallroom_info','hid');
//	
		$data["roomseatplan"] = $this->hallroom_model->roomseatplan();
	    $data["checkincustomer"] = $this->db->select("cust_phone,customerid,b.bookedid,b.booking_number")->from("customerinfo")->join("booked_info b","b.cutomerid=customerinfo.customerid")->where("b.checkindate<=",date("d-m-Y H:i:s"))->where("b.checkoutdate>=",date("d-m-Y H:i:s"))->where("bookingstatus",4)->get()->result();
		$data["paymentdetails"] = $this->hallroom_model->paymentlist();
//	print_r($data["bookingpaymentdata"]);die();
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
	    $data["roomlist"] = $this->hallroom_model->allrooms();
		$data["customerlist"] = $this->hallroom_model->customerlist();
	
		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
		$data['currency']    = getCurrency();   
		$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		
		$data["setting"] = $setting;
        $data['module'] = "hall_room";
        $data['page']   = "editreservation";   
        $this->load->view("hall_room/editreservation", $data); 
    }
	public function getcustomer(){
		$custid = $this->input->post("custid", true);
		$custname = $this->db->select("CONCAT_WS(' ',firstname,lastname) as fullname,customerid,b.bookedid")->from("customerinfo")->join("booked_info b","b.cutomerid=customerinfo.customerid","left")->where("b.bookedid", $custid)->get()->row();
		$data = array(
			'custname' => $custname->fullname,
			'custid' => $custname->customerid,
			'bookedid' => $custname->bookedid,
		  );
		echo json_encode($data); 	
	}
	public function hallroom_create($id = null)
    {
	  $data['title'] = display('hall_room');
	  $this->form_validation->set_rules('roomtype',display('hallroom_type'),'required|xss_clean');
	  $this->form_validation->set_rules('capacity',display('capacity'),'required|xss_clean');
	  $this->form_validation->set_rules('defaultrate',display("hourly")." ".display('defaultrate'),'required|xss_clean');
	  $this->form_validation->set_rules('roomsize',display('roomsize'),'required|xss_clean');
	  $this->form_validation->set_rules('size_unit',display('size_unit'),'required|xss_clean');
	  $this->form_validation->set_rules('description',display('description'),'xss_clean');
	  $id=$this->input->post('roomid', TRUE);
	  $data['intinfo']="";
	  if ($this->form_validation->run()) {
		  $img = $this->fileupload->do_upload(
			  'application/modules/hall_room/assets/images/','picture'
		  );
		  // if favicon is uploaded then resize the favicon
		if ($img !== false && $img != null) {
			$this->fileupload->do_resize(
				$img, 
				320,
				320
			);
		}
		$seatplan = $this->input->post('seatplan',TRUE);
		$allseat = "";
		for($i=0; $i<count($seatplan); $i++){
			$allseat .= $seatplan[$i].",";
		}
		if(empty($this->input->post('roomid', TRUE))) {
			$data['hall_room']   = (Object) $postData = array(
				'hall_type' 	             => $this->input->post('roomtype',TRUE),
				'person_limit' 	         => $this->input->post('capacity',TRUE),
				'rate' 	             	 => $this->input->post('defaultrate',TRUE),
				'size' 	             	 => $this->input->post('roomsize',TRUE),
				'mesurement' 	 		 => $this->input->post('size_unit',TRUE),
				'seatplan' 	     	 	 => trim($allseat,","),
				'description' 	     	 => $this->input->post('description',TRUE),
				'image' 	     	 		 => $img,
			);
			$this->permission->method('hall_room','create')->redirect();
			if ($this->hallroom_model->create('tbl_hallroom_info',$postData)) { 
			//if favicon is not uploaded
			if ($img === false) {
				$this->session->set_flashdata('exception', "Please Upload a Valid Image");
			}
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('hall_room/hallroom-type');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("hall_room/hallroom-type"); 
	
	   } else {
		$this->permission->method('hall_room','update')->redirect();
		if(!empty($id)) {
			$imageinfo=$this->db->select('image')->from('tbl_hallroom_info')->where('hid',$id)->get()->row();
		   if(!empty($img)){
			  unlink($imageinfo->image);
			 }
			 else{
				 $img=$imageinfo->image;
				 }
			} 

		$data['hall_room']   = (Object) $postData = array(
		    'hid'     	     	 	 => $this->input->post('roomid', TRUE),
			'hall_type' 	         => $this->input->post('roomtype',TRUE),
			'person_limit' 	         => $this->input->post('capacity',TRUE),
			'rate' 	             	 => $this->input->post('defaultrate',TRUE),
			'size' 	             	 => $this->input->post('roomsize',TRUE),
			'mesurement' 	 		 => $this->input->post('size_unit',TRUE),
			'seatplan' 	     	 	 => trim($allseat,","),
			'description' 	     	 => $this->input->post('description',TRUE),
			'image' 	     	 	 => $img,
		  );
		if ($this->hallroom_model->update('tbl_hallroom_info','hid',$postData)) { 
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("hall_room/hallroom-type");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['intinfo']   = $this->hallroom_model->findById($id);
	   }
	   $data['title']    = display('hallroom_type'); 
	   $data["hrdetails"] = $this->hallroom_model->hallroomread();
	   $data["allsizes"] = $this->hallroom_model->allsizes();
	   $data["seatplan"] = $this->hallroom_model->seatplan();
	   $data['module'] = "hall_room";
	   $data['page']   = "hallroomlist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
	public function seatplan_create($id = null)
    {
	  $data['title'] = display('hall_room');
	  $this->form_validation->set_rules('plan_name',display('plan_name'),'required|xss_clean');
	  $this->form_validation->set_rules('description',display('description'),'xss_clean');
	  $id=$this->input->post('roomid', TRUE);
	  $data['intinfo']="";
	  if ($this->form_validation->run()) {
		  $img = $this->fileupload->do_upload(
			  'application/modules/hall_room/assets/images/','picture'
		  );
		  // if favicon is uploaded then resize the favicon
		if ($img !== false && $img != null) {
			$this->fileupload->do_resize(
				$img, 
				320,
				320
			);
		}
		
		if(empty($this->input->post('hsid', TRUE))) {
			$data['hall_room']   = (Object) $postData = array(
				'plan_name' 	             => $this->input->post('plan_name',TRUE),
				'description' 	     	 => $this->input->post('description',TRUE),
				'image' 	     	 		 => $img,
			);
			$this->permission->method('hall_room','create')->redirect();
			if ($this->hallroom_model->create('tbl_hallroom_seatplan',$postData)) { 
				//if favicon is not uploaded
				if ($img === false) {
				$this->session->set_flashdata('exception', "Please Upload a Valid Image");
			}
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('hall_room/seatplan');
		} else {
		 $this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("hall_room/seatplan"); 
	
	   } else {
		$this->permission->method('hall_room','update')->redirect();
		if(!empty($id)) {
			$imageinfo=$this->db->select('image')->from('tbl_hallroom_info')->where('hid',$id)->get()->row();
		   if(!empty($img)){
			  unlink($imageinfo->image);
			 }
			 else{
				 $img=$imageinfo->image;
				 }
			} 

		$data['hall_room']   = (Object) $postData = array(
		    'hsid'     	     	 	 => $this->input->post('hsid', TRUE),
			'plan_name' 	         => $this->input->post('plan_name',TRUE),
			'description' 	     	 => $this->input->post('description',TRUE),
			'image' 	     	 	 => $img,
		  );
	 
		if ($this->hallroom_model->update('tbl_hallroom_seatplan','hsid',$postData)) { 
		 $this->session->set_flashdata('message', display('update_successfully'));
		} else {
		$this->session->set_flashdata('exception',  display('please_try_again'));
		}
		redirect("hall_room/seatplan");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('bed_edit');
		$data['intinfo']   = $this->hallroom_model->findById($id);
	   }
	   $data["hrdetails"] = $this->hallroom_model->get_all('*','tbl_hallroom_info','hid');
	   $data["allsizes"] = $this->hallroom_model->allsizes();
	   $data['module'] = "hall_room";
	   $data['page']   = "hallroomlist";  
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
    public function customerpay($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();		
        $data['title']    = display('hall_room'); 
	    $data["bookingdata"] = $this->hallroom_model->detailbooking($id);
        $data['module'] = "hall_room";
        $data['page']   = "custdetails";   
        $this->load->view("hall_room/customerdetails", $data); 
    }
	
    public function cancelreservation($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();		
        $data['title']    = display('hall_room'); 
        $data['module'] = "hall_room";
		$data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
        $data['bookedid']   = $id;   
        $data['page']   = "cancelreservation";   
        $this->load->view("hall_room/cancelreservation", $data); 
    }
	public function getroomno(){
		$room_type = $this->input->post('room_type', TRUE);
		$allroom = $this->hallroom_model->read2('roomno','tbl_roomnofloorassign','roomno',array('roomid'=>$room_type),array('status'=>1));
		$typename = $this->db->select("roomtype")->from("roomdetails")->where("roomid",$room_type)->get()->row();
		$complementary = $this->hallroom_model->read2('complementaryname,rate','tbl_complementary','complementary_id',array('roomtype'=>$typename->roomtype),null);
		$data = array(
			'roomno' => $allroom,
			'complementary' => $complementary,
		  );
		  echo json_encode($data);
	}
	public function checknewroom(){
		$room_type = $this->input->post('room_type', TRUE);
		$checkin=$this->input->post('datefilter1',true);
		$checkout=$this->input->post('datefilter2',true);
		$status="status!=2 AND status!=3";
		$croom ="FIND_IN_SET(".$room_type.",hall_type)";
		$exits = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<=',$checkin)->where('end_date>',$checkin)->where($status)->where("$croom !=",0)->get()->result();
		$exit = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<',$checkout)->where('end_date>=',$checkout)->where($status)->where("$croom !=",0)->get()->result();
		$check = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date>',$checkin)->where('end_date<=',$checkout)->where($status)->where("$croom !=",0)->get()->result();
		$totalroom1 = $this->db->select("SUM(total_room) as allroom")->from('tbl_hallroom_booking')->where('start_date<=',$checkin)->where('end_date>',$checkin)->where($status)->where("$croom !=",0)->get()->row();
		$totalroom2 = $this->db->select("SUM(total_room) as allroom")->from('tbl_hallroom_booking')->where('start_date<',$checkout)->where('end_date>=',$checkout)->where($status)->where("$croom !=",0)->get()->row();
		$totalroom3 = $this->db->select("SUM(total_room) as allroom")->from('tbl_hallroom_booking')->where('start_date>=',$checkin)->where('end_date<=',$checkout)->where($status)->where("$croom !=",0)->group_by('start_date')->get()->result();
		$allbokedroom3 = (!empty($allbokedroom3)?max(array_column($totalroom3, 'allroom')):0);
		$totalroomfound=$this->db->select("count(hallid) as totalroom")->from('tbl_roomnofloorassign')->where('hallid',$room_type)->get()->row();
		$roomdetails=$this->db->select("*")->from('tbl_hallroom_info')->where('hid',$room_type)->get()->row();
		$numberlist=$this->db->select("*")->from('tbl_roomnofloorassign')->where('hallid',$room_type)->get()->result();
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
				$bookedroom.=$booked->hall_no.',';
				}
			}
			if(!empty($exit)){
				foreach($exit as $ex){
					$bookedroom.=$ex->hall_no.',';
				}
			}
			if(!empty($check)){
				foreach($check as $ch){
					$bookedroom.=$ch->hall_no.',';
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
					$allroom=$output;
					$data['isfound']='1';
						}
					else{
						$allroom='';
						$data['isfound']='2';
						}
				}
				else{
					$allroom='';
					$data['isfound']='2';
					}
			}
		$typename = $this->db->select("hall_type")->from("tbl_hallroom_info")->where("hid",$room_type)->get()->row();

		$data['chargeinfo']=$this->hallroom_model->chargeinfo();
		$availableroom = explode(",",$allroom);
		$room_list = explode(",",$gtroomno);
		$free_room = array_intersect($room_list, $availableroom);
		$data = array(
			'roomno' => array_values($free_room),
		  );
		  echo json_encode($data);

	 }
	public function getcapacity(){
		$start = $this->input->post('start', TRUE);
		$end = $this->input->post('end', TRUE);
		$start_date = strtotime($start);
		$end_date = strtotime($end);
              $difference = $end - $start;
		$days =  ceil($difference / (60 * 60 * 24));
	
		$date1Value = explode(' ', $start);
$date2Value = explode(' ',$end);
$date1Timestamp = strtotime($date1Value[0]);
$date2Timestamp = strtotime($date2Value[0]);

    $date1 = new DateTime(date('d-m-Y', $date1Timestamp));
    $date2 = new DateTime(date('d-m-Y', $date2Timestamp));

    $difference = $date2->diff($date1)->days;

		$roomno = $this->input->post('roomno', TRUE);
	//	echo "Room : ".$roomno;
		$room_type = $this->input->post('room_type', TRUE);
		$roomid = $this->db->select("hallid")->from("tbl_roomnofloorassign")->where("roomno",$roomno)->get()->row();
		$hallroomdata = $this->db->select("rate, room_status")->from("tbl_hallroom_info")->where("hid",$room_type)->get()->row();
		//$capacity = $this->hallroom_model->readone('person_limit,rate,seatplan','tbl_hallroom_info',array('hid'=>$roomid->hallid));
	//echo $this->db->last_query();
		$roomseat = explode(",", $capacity->seatplan);
		$allseat = $this->db->select("hsid,plan_name")
		->from('tbl_hallroom_seatplan')
		->where_in('hsid', $roomseat)
		->get()
		->result();
		//echo $capacity->rate ."$". $difference;
		$rate=$hallroomdata->rate*$difference;
		$data = array(
			
			'price' => $rate,
		
			'hallData' => $hallroomdata
		  );
		//  print_r($data);
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
	 //	print_r($this->input->post()); die();
		//reservation details
		$bookingid = $this->input->post('bookingid', TRUE);
		$datefilter1 = $this->input->post('datefilter1', TRUE);
		$datefilter2 = $this->input->post('datefilter2', TRUE);
		$event_name = $this->input->post('event_name', TRUE);
		$event_type = $this->input->post('event_type', TRUE);
		$booking_remarks = $this->input->post('booking_remarks', TRUE);
		//room details
		$room_type = $this->input->post('room_type', TRUE);
		$roomno = $this->input->post('roomno', TRUE);
		$adults = $this->input->post('adults', TRUE);
		$rent = $this->input->post('rent', TRUE);
		$discount_price = $this->input->post('discount_price', TRUE);
		$seatplan = $this->input->post('seatplan', TRUE);
		$checkinbookedid = $this->input->post('checkinbookedid', TRUE);
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
		$discountamount = $this->input->post('discountamount', TRUE);
		$commissionrate = $this->input->post('commissionrate', TRUE);
		$commissionamount = $this->input->post('commissionamount', TRUE);
		$paymentmode = $this->input->post('paymentmode', TRUE);
		$advanceamount = $this->input->post('advanceamount', TRUE);
		$advanceremarks = $this->input->post('advanceremarks', TRUE);
		//user details
		$userid = $this->input->post('userid', TRUE);
		$alluserid = explode(",", trim($userid));
		$name = $this->input->post('name', TRUE);
		$allname = explode(",", trim($name));
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
		$advanceid = $this->input->post('advanceid', TRUE);
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
		$paid_amt =$this->input->post('collectedamt', TRUE);  
		//end
		$allroom = explode(",",trim($roomno));
		$price = explode(",",trim($rent));
		
		$currentDateTime = date("d-m-Y H:i:s");

        if ($currentDateTime >= $datefilter1 && $currentDateTime <= $datefilter2) {
            $roomStatus = 1;
        } else {
            $roomStatus = 0;
        }
        
        $hstatus = $this->db->where("hid", $room_type)->update("tbl_hallroom_info", array("room_status" => $roomStatus));

		
		$totalprice=0;
		for($i=0;$i<count($price);$i++){
			$totalprice+=$price[$i];
		}
		$totalprice -= !empty($discountamount)?$discountamount:0;

		//user details insert
		if($bookingid){
			$bookedid = $this->db->select("hbid,customerid,hall_no,paid_amount,start_date,end_date")->from("tbl_hallroom_booking")->where("hbid",$bookingid)->get()->row();
			$customer = $this->db->select("concat_ws(' ',firstname,lastname) as fullname")->from("customerinfo")->where("customerid", $bookedid->customerid)->get()->row();
			$room_no = $bookedid->hall_no;
			$roomnum = explode(",",$room_no);
			$roomstatus = array(
				'status' => 1
			);
			for($i=0;$i<count($roomnum); $i++){
				$this->db->where("roomno",$roomnum[$i])->update("tbl_roomnofloorassign", $roomstatus);
			}
			$oldbid = $bookedid->hbid;
		}
		if(empty($alluserid[0])){
			if((!empty($customer->fullname)?$customer->fullname:null)!=$allname[0] && !empty($allname[0])){
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
				'cust_phone'  => $allmobile[0],
				'email' 	  => $allemail[0],
				'gender' 	  => $allgender[0],
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
				'fathername'  => $this->input->post('father',TRUE),
				'profession'  => $this->input->post('occupation',TRUE),
				'dob' 	  	  => $this->input->post('dob',TRUE),
				'pass' 	      => md5('123456'),
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
			//end
			$customerid = $this->db->insert_id();
			
			//insert Coa for Customer Receivable
			//end
			}else{
				$customerid = $bookedid->customerid;
			}
	   }else{
		$customerid = $alluserid[0];
		}
		//booking info insert
		if(empty($this->input->post('bookingid', TRUE))) {
			$bookinginfo=$this->db->select("invoice_no")->from('tbl_hallroom_booking')->order_by('hbid','desc')->get()->row();
			if(!empty($bookinginfo)){
			$bno=explode("BQT-",$bookinginfo->invoice_no);
			$bookno=$bno[1];
			}
			else{
			$currentYear = date('Y');
				$currentMonth = date('m');
				$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
			//	echo $financialYearStart;die();
				$bookno = $financialYearStart . "0000";
				}
			$nextno=$bookno+1;
            $bk_length = strlen((int)$nextno);
          //  echo  $bk_length; echo "<br/>";
            $currentYear = date('Y');
            $currentMonth = date('m');
            $financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            //	echo $financialYearStart;
            $bkstr = $financialYearStart . "0000";
            $bknumber = substr($bkstr, $bk_length);
           // echo   $bknumber;die();
            $bookingnumber = "BQT-".$bknumber.$nextno;
// 			$nextno=$bookno+1;
// 			$bk_length = strlen((int)$nextno); 
			
// 		//	$bkstr = '00000000';
// 		$currentYear = date('Y');
// 			$currentMonth = date('m');
// 			$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
// 			$bkstr = $financialYearStart . "000";
// 			$bknumber = substr($bkstr, $bk_length); 
// 			$bookingnumber = "BQT-".$bknumber.$nextno;   
		//charge and tax
		$setting=$this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
		$scharge=$this->db->select("servicecharge")->from("setting")->get()->row();
		$taxamount=0;
		$schargeamount=0;
		$taxname="";
		$taxrate="";
		$date1 = new DateTime($this->input->post('datefilter1', TRUE));
		$date2 = new DateTime($this->input->post('datefilter2', TRUE));
		
		$difference = abs($date2->getTimestamp() - $date1->getTimestamp());
		$days_difference = ceil($difference / (60 * 60 * 24));
		
		
		
		//echo $rent;echo "<br/>";
		//$rent = $rent*$days_difference;
		foreach($setting as $st){
			$taxamount+=(18*$totalprice)/100;
			$taxrate .= '9'.",";
			$taxname .= $st->taxname.",";
		}
		$cgst = $this->input->post('cgst', TRUE);
$sgst = $this->input->post('sgst', TRUE);
		$schargeamount = ($scharge->servicecharge*$totalprice)/100;
		$grandtotal=($rent+$cgst+$sgst);
		//end
		if($grandtotal<=$advanceamount){
			$payment_status = 1;
		}
		else if($advanceamount>0){
			$payment_status = 2;
			$adv_date=date('d-m-Y');
		}
		else{
			$payment_status = 0;
		}

//echo "days :".$days_difference;echo "<br/>";
			 $postData = array(
			   'invoice_no' 	     	 => $bookingnumber,
			   'date_time' 	             => date('d-m-Y H:i:s'),
			   'booked_id' 	         	 => $checkinbookedid,
			   'event_name' 	         => $event_name,
			   'event_type' 	         => $event_type,
			   'hall_type' 	             => $room_type,
			   'people'              	 => $adults,
			    'adv_amt'                => $advanceamount,
			   'total_room'              => count($allroom),
			   'hall_no'              	 => trim($roomno),
			   'rent'                	 => $rent,
			   'adv_date'   => $adv_date,
			   'totalamount'             => $grandtotal,
			   'paid_amount'             => $paid_amt,
			   'cgst' => $this->input->post('cgst', TRUE),
			   'sgst' => $this->input->post('sgst', TRUE),
			   'remarks'                 => $booking_remarks,
			   'advance_remarks'         => $advanceremarks,
			   'advance_id'         => $advanceid,
			   'start_date'              => $datefilter1,
			   'end_date'            	 => $datefilter2,
			   'customerid' 	         => $customerid,
			 //  'status' 	         	 => 1,
			   'status' => $this->input->post('addcheckin') === 'add_checkin' ? 4 : 1,
			   'seatplan'				 => $seatplan,
			   'payment_status' 	     => $payment_status
			  );
			  
//print_r( $postData);die();
			// for($ch=0;$ch<count($allroom);$ch++){
			// 	$status="status!=2 AND status!=3";
			// 	$croom ="FIND_IN_SET(".$allroom[$ch].",hall_no)";
			// 	$exits = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<=',$datefilter1)->where('end_date>',$datefilter1)->where($status)->where("$croom !=",0)->get()->result();
			// 	$exit = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<',$datefilter2)->where('end_date>=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
			// 	$check = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date>',$datefilter1)->where('end_date<=',$datefilter2)->where($status)->where("$croom !=",0)->get()->result();
			// 	if(!empty($exits)||!empty($exit)||!empty($check)){
			// 		echo '<h5>Failed</h5>Hall No '.$allroom[$ch].' is not available';
			// 		exit;
			// 	}
			// }
			$this->permission->method('hall_room','create')->redirect();
			if($this->hallroom_model->create('tbl_hallroom_booking', $postData)) { 
				//end
				$bookedid = $this->db->insert_id();
				//tax and service charge
				$tc = array(
					'hrbooking' => $bookedid,
					'taxname' => trim($taxname,","),
					'taxrate' => trim($taxrate,","),
					'scharge' => $schargeamount,
				);
				$this->hallroom_model->create('tbl_hallroom_postbill', $tc);
	
    			
				
				
				
			
				//Customer Advance account transaction 
				if($advanceamount>0){
					$this->advance_payment($bookedid, $bookingnumber, $paymentmode, $advanceamount,null);					
				}
				
				
				
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
								'type'  => 1,
							);
						 }else{
							$guestdata = array(
								'bookedid'   => $bookedid,
								'customerid'   => $alluserid[$l],
								'type'   => 1,
							);
						 }
						$this->db->insert("tbl_otherguest",$guestdata);
					}
				}
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
			$setting=$this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
			$scharge=$this->db->select("servicecharge")->from("setting")->get()->row();
			$taxamount=0;
			$taxname="";
			$taxrate="";
			$tx=(18*$totalprice)/100;
			foreach($setting as $st){
				$taxamount+=(18*$totalprice)/100;
				$taxrate .= '18'.",";
				$taxname .= $st->taxname.",";
			}
			$schargeamount = ($scharge->servicecharge*$totalprice)/100;
			$grandtotal=($totalprice+$tx);
			if($grandtotal<=$advanceamount){
				$payment_status = 1;
			}
			else if($advanceamount>0){
				$payment_status = 2;
				$adv_date=date('d-m-Y');
			}
			else{
				$payment_status = 0;
			}
			$this->permission->method('hall_room','update')->redirect();

			$date1 = new DateTime($this->input->post('datefilter1', TRUE));
		    $date2 = new DateTime($this->input->post('datefilter2', TRUE));
		
		    $difference = abs($date2->getTimestamp() - $date1->getTimestamp());
		    $days_difference = ceil($difference / (60 * 60 * 24));
		
		
		
		//echo $rent;echo "<br/>";
	//	$rent = $rent*$days_difference;
		foreach($setting as $st){
			$taxamount+=(18*$totalprice)/100;
			$taxrate .= '9'.",";
			$taxname .= $st->taxname.",";
		}
		$cgst = $this->input->post('cgst', TRUE);
        $sgst = $this->input->post('sgst', TRUE);
		$schargeamount = ($scharge->servicecharge*$totalprice)/100;
		$grandtotal=($rent+$cgst+$sgst);

			$updateData = array(
				'hbid' 	             	 => $bookingid,
				'hall_type' 	         => $room_type,
				'event_name' 	         => $event_name,
				'event_type' 	         => $event_type,
				'people'              	 => $adults,
				'total_room'             => count($allroom),
				'hall_no'              	 => trim($roomno),
				'rent'                	 => $rent,
				'totalamount'            => $grandtotal,
				'paid_amount'            => $advanceamount,
				'adv_amt'             => $advanceamount,
				 'adv_date'   => $adv_date,
				'cgst' => $this->input->post('cgst', TRUE),
			    'sgst' => $this->input->post('sgst', TRUE),
				'remarks'                => $booking_remarks,
				'start_date'             => $datefilter1,
				'end_date'            	 => $datefilter2,
				'customerid' 	         => $customerid,
				'seatplan'				 => $seatplan,
				'status' => $this->input->post('update') === 'update_hallroomstatus1' ? 1 : 4,
				'payment_status' 	     => $payment_status,
			   );
			 
			 //  }else if($this->input->post('update')==='update_hallroom'){
			 //       $updateData['status']='1';
			 //  }
			   
			//  print_r($updateData); die();

			   $datefilter1 = $this->input->post('datefilter1', TRUE);
			   $datefilter2 = $this->input->post('datefilter2', TRUE);
// 			   for($ch=0;$ch<count($allroom);$ch++){
// 				   if($oldbid != $bookingid | $bookedid->start_date!=$datefilter1 | $bookedid->end_date!=$datefilter2){
// 				$status="status!=2 AND status!=3";
// 				$croom ="FIND_IN_SET(".$allroom[$ch].",hall_no)";
// 				$exits = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<=',$datefilter1)->where('end_date>',$datefilter1)->where($status)->where("$croom !=",0)->where('hbid!=',$bookingid)->get()->result();
// 				echo $this->db->last_query();
// 				$exit = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date<',$datefilter2)->where('end_date>=',$datefilter2)->where($status)->where("$croom !=",0)->where('hbid!=',$bookingid)->get()->result();
// 				$check = $this->db->select("*")->from('tbl_hallroom_booking')->where('start_date>',$datefilter1)->where('end_date<=',$datefilter2)->where($status)->where("$croom !=",0)->where('hbid!=',$bookingid)->get()->result();
// 				if(!empty($exits)||!empty($exit)||!empty($check)){
// 					echo '<h5>Failed</h5>Hall No '.$allroom[$ch].' is not available';
// 					exit;
// 				}
// 				}
// 			}

			$bookinginfo=$this->db->select("invoice_no")->from('tbl_hallroom_booking')->order_by('hbid','desc')->get()->row();
			if(!empty($bookinginfo)){
			$bno=explode("BQT-",$bookinginfo->invoice_no);
			$bookno=$bno[1];
			}
			else{
			$currentYear = date('Y');
				$currentMonth = date('m');
				$financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
			//	echo $financialYearStart;die();
				$bookno = $financialYearStart . "0000";
				}
			$nextno=$bookno+1;
            $bk_length = strlen((int)$nextno);
          //  echo  $bk_length; echo "<br/>";
            $currentYear = date('Y');
            $currentMonth = date('m');
            $financialYearStart = ($currentMonth > 3) ? $currentYear : $currentYear - 1;
            //	echo $financialYearStart;
            $bkstr = $financialYearStart . "0000";
            $bknumber = substr($bkstr, $bk_length);
           // echo   $bknumber;die();
            $bookingnumber = "BQT-".$bknumber.$nextno;
			if ($this->hallroom_model->update('tbl_hallroom_booking','hbid',$updateData)) { 
				//tax and service charge
				$tc = array(
					'hrbooking' => $bookingid,
					'taxname' 	=> trim($taxname,","),
					'taxrate' 	=> trim($taxrate,","),
					'scharge' 	=> $schargeamount,
				);
				$this->hallroom_model->update('tbl_hallroom_postbill','hrbooking', $tc);
				if($advanceamount>0){
					$this->advance_payment($bookingid, $bookingnumber, $paymentmode, $advanceamount,1);					
				}
			//other guest update and insert
			$gid = $this->db->select("otherguest_id")->from("tbl_otherguest")->where("bookedid",$bookingid)->where("type",1)->get()->result();
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
						'type'  => 1,
					);
				 }else{
					$guestdata = array(
						'bookedid'   => $bookingid,
						'customerid'   => $alluserid[$l],
						'type'   => 1,
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
	public function cancelbooking(){
		$this->form_validation->set_rules('cancelreason',"Cancel Reason",'required|xss_clean');
		$pmethod = $this->input->post('pmethod', TRUE);
		if($pmethod=="Bank Payment"){
			$this->form_validation->set_rules('bankName',"Bank Name",'required|xss_clean');
		}
		$bookingid = $this->input->post('bookedid', TRUE);
		$cancelreason = $this->input->post('cancelreason', TRUE);
		$cancelationcharge = $this->input->post('cancelationcharge', TRUE);
		$paidTotal = $this->db->select("paid_amount, hall_type")->from("tbl_hallroom_booking")->where("hbid", $bookingid)->get()->row();
		$totalCancelationcharge = $cancelationcharge + $paidTotal->paid_amount;
		$hstatus = $this->db->where("hid", $paidTotal->hall_type)->update("tbl_hallroom_info", array("room_status" => 0));
		if ($this->form_validation->run()) { 
			$cancel =  array(
				'remarks' => $cancelreason,
				'paid_amount' => $totalCancelationcharge,
				'status' => 3
			);
			$method = array(
				'payment_method' => $pmethod 
			);
			$cancelbooking = $this->db->where("hbid",$bookingid)->update("tbl_hallroom_booking", $cancel);
			if($cancelbooking){
				$allroom = $this->db->select("hall_no")->from("tbl_hallroom_booking")->where("hbid",$bookingid)->get()->row();
				$roomno = explode(",",$allroom->hall_no);
				$roomstatus = array(
					'status' => 1
				);
				for($i=0;$i<count($roomno); $i++){
					$this->db->where("roomno",$roomno[$i])->update("tbl_roomnofloorassign", $roomstatus);
				}
				if($cancelationcharge>0){
					$cardNumber = $this->input->post('cardNumber', TRUE);
					$ccv = $this->input->post('ccvvalue_num', TRUE);
					$date = $this->input->post('carddate', TRUE);
 					$RTGS = $this->input->post('rtgs', TRUE);
					$UPI = $this->input->post('upi_ref_num', TRUE);
					$ONLINE = $this->input->post('online_refnum', TRUE);
					$CHEQUENO = $this->input->post('cheque_no', TRUE);
					$CDATE = $this->input->post('cdate', TRUE);
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
						'details' =>  "Card/Account No: " . $cardNumber . " CCV: " . $ccv . " Date: " . $date . " RTGS: " . $RTGS  . " UPI: " . $UPI . " ONLINE: " . $ONLINE . " CHEQUENO: " . $CHEQUENO . " CDATE: " . $CDATE,
						'book_type'				 => 1, 
						);
					$this->db->insert('tbl_guestpayments', $postData);
					//Payment method Debit for paid value
					if($pmethod=="Bank Payment"){
						$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$bankName'");
						$row = $query->row();
						$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
						if(empty($headcode)){
							$coa = $this->hallroom_model->headcode(4,1020102);
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
					//Hotel Hall Room Owner credit for Hotel Hall Room Rent Value
					$narration = 'Hotel Hall Room Credited for Hotel Hall Room Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 30305, $narration, 0, $cancelationcharge, 0, 1, $saveid, $newdate, 1);
					// Customer Credit for paid amount.
					$narration = 'Customer Credited for Rent Invoice# '.$invoice_no;
					transaction($invoice_no, 'CIV', $newdate, 102030101, $narration, 0, $cancelationcharge, 0, 1, $saveid, $newdate, 1);
				} 
			}
			$this->session->set_flashdata('message', "Reservation Canceled Successfully");
			redirect($_SERVER['HTTP_REFERER']);
		}
		else{
			$data['module'] = "hall_room";
			$data['page']   = "reservationlist";   
			echo Modules::run('template/layout', $data); 
		}
	}
	
	
	
public function advance_payment($bookedid, $bookingnumber, $paymentmode, $advanceamount,$id){
	    
		$payment = $this->db->select("bookedid")->from("tbl_guestpayments")->where("bookedid", $bookedid)->where("book_type",1)->get()->row();
		$invoice = $this->db->select("bookedid")->from("tbl_guestpayments")->where("bookedid", $bookedid)->where("book_type",1)->where("paymentamount", $advanceamount)->get()->row();
	//echo $this->db->last_query();
if($invoice->bookedid){
    
        $this->db
        ->where("bookedid", $bookedid)
        ->where("book_type", 1)
        ->delete("tbl_guestpayments");
        	//echo $this->db->last_query();
}
	$invoice1 = $this->db->select("bookedid")->from("tbl_guestpayments")->where("bookedid", $bookedid)->where("book_type",1)->where("paymentamount<>", $advanceamount)->get()->row();
	
		if (empty($invoice1->bookedid)) {
				//Payment record
				
			//	echo "Inside IF" ;
				
				
				$cardNumber = $this->input->post('cardno', TRUE);
				$bankName = $this->input->post('bankname', TRUE);
				
								$upi_referenceno = $this->input->post('upi_referenceno', TRUE);
								
								$rtgs_referenceno = $this->input->post('rtgs_referenceno', TRUE);
								
								$cheque_no = $this->input->post('cheque_no', TRUE);
								$cheque_date = $this->input->post('cheque_date', TRUE);
								
								$online_referenceno = $this->input->post('online_referenceno', TRUE);

							    $expirydate = $this->input->post('expirydate', TRUE);
								$ccv = $this->input->post('ccv', TRUE);

				
				
				    
				
				
				
				
				
				
				$newdate = date("d-m-Y H:i:s");
				$saveid = $this->session->userdata('id');
				$postData = array(
				    'bookedid'               => $bookedid,
					'paydate' 	             => $newdate,
					'paymenttype' 	         => $paymentmode,
					'paymentamount' 	     => $advanceamount,
                          'invoice'          => $bookingnumber,
                           'book_type'          => 1
				//	'details' 	     		 => "Advance in Card/Account No: ".$cardNumber." Bank Name: ".$bankName."Card Number: ".$cardno.  "Expiry Date: ".$expirydate. "CCV : ".$ccv.    "Online REF NO : ".$online_referenceno.    "UPI : ".$upi_referenceno     ,

			
			
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
                    $postData['details'] = "Advance in Cheque: ". $cheque_no . '-' . $cheque_date;
                 
                }elseif ($paymentmode == "Bank Payment") {
                    $bankname = $this->input->post('bankname', TRUE);
                       $accountnumber = $this->input->post('accountnumber', TRUE);
                    $postData['details'] = "Advance in Bank Payment : " . $bankname ."-".$accountnumber;
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
				//	$this->db->where('bookedid',$bookedid)->update("tbl_guestpayments",$postData);
						$this->db->insert("tbl_guestpayments",$postData);
		//	echo $this->db->last_query();die();
					
					
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
			
				//Payment method Debit for paid value
				$acc_id = $this->db->select("ID")->from("acc_transaction")->where('VNo',$invoice->invoice)->order_by("ID","ASC")->get()->result();
				if($paymentmode=="Bank Payment"){
				$query=$this->db->query("SELECT HeadCode FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%' And HeadName LIKE '$bankName'");
				$row = $query->row();
				$headcode = (!empty($row->HeadCode)?$row->HeadCode:null);
				if(empty($headcode)){
					$coa = $this->hallroom_model->headcode(4,1020102);
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
			  	//charge and tax
				$setting=$this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
				$scharge=$this->db->select("servicecharge")->from("setting")->get()->row();
				$taxamount=0;
				$schargeamount=0;
				$taxname="";
				$taxrate="";
				foreach($setting as $st){
					$taxamount+=($st->rate*$advanceamount)/100;
					$taxrate .= $st->rate.",";
					$taxname .= $st->taxname.",";
				}
				$schargeamount = ($scharge->servicecharge*$advanceamount)/100;
				//hotel service credited for advance rent
				transaction_update($acc_id[1]->ID, $invoice->invoice, $newdate, 0, ($advanceamount-$taxamount-$schargeamount), $saveid, $newdate, 30305);
				//Customer debited for advance room booking
				transaction_update($acc_id[2]->ID, $invoice->invoice, $newdate, $advanceamount, 0, $saveid, $newdate, 102030101);
				//Customer credited for advance payment
				transaction_update($acc_id[3]->ID, $invoice->invoice, $newdate, 0, $advanceamount, $saveid, $newdate, 102030101);
				//Hotel Hall Room Debited For Hotel Hall Room TAX Invoice
				transaction_update($acc_id[4]->ID, $invoice->invoice, $newdate, $taxamount, 0, $saveid, $newdate, 1020204);
				//Hotel Hall Room Credited For Hotel Hall Room TAX Invoice
				transaction_update($acc_id[5]->ID, $invoice->invoice, $newdate, 0, $taxamount, $saveid, $newdate, 5020303);
				//Hotel Hall Room Credited for Hotel Hall Room Service Charge Invoice
				transaction_update($acc_id[6]->ID, $invoice->invoice, $newdate, 0, $schargeamount, $saveid, $newdate, 30304);
			}
		
	}
	
	
	
	
	
	
	
	
// 	public function hallroom_status($id = null)
//     {
// 		$this->permission->method('hall_room','read')->redirect();				
// 		//update as available if time ended
// 		$this->db->set('tbl_roomnofloorassign.status',1);
// 		$this->db->where('tbl_hallroom_booking.end_date<',date("d-m-Y H:i:s"));
// 		$this->db->where('tbl_roomnofloorassign.status<>',1);
// 		$this->db->update('tbl_roomnofloorassign JOIN tbl_hallroom_booking ON FIND_IN_SET(tbl_roomnofloorassign.roomno,tbl_hallroom_booking.hall_no)<>0');
// 		//update as booked if time is not ended
// 		$this->db->set('tbl_roomnofloorassign.status',2);
// 		$this->db->where('tbl_hallroom_booking.start_date<',date("d-m-Y H:i:s"));
// 		$this->db->where('tbl_hallroom_booking.end_date>',date("d-m-Y H:i:s"));
// 		$this->db->where('tbl_roomnofloorassign.status<>',2);
// 		$this->db->where('tbl_hallroom_booking.status',1);
// 		$this->db->update('tbl_roomnofloorassign JOIN tbl_hallroom_booking ON FIND_IN_SET(tbl_roomnofloorassign.roomno,tbl_hallroom_booking.hall_no)<>0');

//         $data['title']    = display('hall_room'); 
//         $currentDateTime = date("d-m-Y H:i:s");
// 	   $data["roomlist"] = $this->db->select('*')->from('tbl_hallroom_booking')->join('tbl_hallroom_info', 'tbl_hallroom_info.hid = tbl_hallroom_booking.hbid', 'inner')->get()->result();
// 	   //$data["roomlist"] = $this->db->select('*') ->from('tbl_hallroom_booking') ->join('tbl_hallroom_info', 'tbl_hallroom_info.hid = tbl_hallroom_booking.hbid', 'inner') ->where('tbl_hallroom_booking.start_date <=', $currentDateTime) ->where('tbl_hallroom_booking.end_date >=', $currentDateTime)->where('tbl_hallroom_info.room_status', '0')->get()->result_array();
// 	   //echo $this->db->last_query(); die();
//         $data['module'] = "hall_room";
//         $data['page']   = "roomlist";   
//         echo Modules::run('template/layout', $data); 
//     }
    
    
    public function checkoutdatatable()
            {
                $params = $columns = $totalRecords = $data = array();
                $params = $_REQUEST;
                $columns = array(
                    0 => 'tbl_hallroom_booking.hbid',
                    1 => 'invoice_no',
                    2 => 'hall_type',
                    3 => 'customerinfo.firstname',
                    4 => 'customerinfo.cust_phone',
                    5 => 'start_date',
                    6 => 'end_date',
                    7 => 'paid_amount',
                    8 => 'due_amount',
                    9 => 'payment_status',
                    10 => 'status',
                );
                $where = $sqlTot = $sqlRec = "";
                // check search value exist
                if(!empty($params['search']['value']) ) {
                    $where .=" WHERE ";
                    $where .=" ( tbl_hallroom_booking.invoice_no LIKE '".$params['search']['value']."%' ";
                    $where .=" OR tbl_hallroom_booking.hall_type LIKE '".$params['search']['value']."%' ";
                    $where .=" OR tbl_hallroom_booking.hall_no LIKE '".$params['search']['value']."%' ";
                    $where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
                    $where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
                    $where .=" OR tbl_hallroom_booking.start_date LIKE '".$params['search']['value']."%' ";
                    $where .=" OR tbl_hallroom_booking.end_date < '".$params['search']['value']."%' ";
                    $where .=" OR tbl_hallroom_booking.status LIKE '1' )  AND (tbl_hallroom_booking.status=1)";
                }
                // getting total number records without any search
                $sql = "SELECT tbl_hallroom_booking.*, customerinfo.firstname, customerinfo.cust_phone, tbl_hallroom_info.* FROM tbl_hallroom_booking LEFT JOIN customerinfo ON customerinfo.customerid = tbl_hallroom_booking.customerid JOIN tbl_hallroom_info ON tbl_hallroom_info.hid = tbl_hallroom_booking.hall_type WHERE tbl_hallroom_booking.status = 5";
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
                foreach($queryRecords as  $value){
                    $i++;
                    $row = array();
                    $update='';
                    $delete='';
                    if($this->permission->method('hall_room','update')->access()):
                    $view='<a href="'.base_url().'hall_room/hallroom/detailView/'.$value->hbid.'" class="btn btn-info btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="View" title="View"><i class="ti-eye"></i></a>';
                    endif;
                    if($this->permission->method('room_reservation','read')->access()):
                    $print='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallprintresrvation('.$value->hbid.')" class="btn btn-primary btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Print" title="Print"><i class="fa fa-print text-white" aria-hidden="true"></i></a>';
                    endif;
                    if($this->permission->method('room_reservation','create')->access()):
                    $Payment='<a href="'.base_url().'room_reservation/payment-information/'.$value->hbid.'" class="btn btn-success btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
                    endif;
                    if($value->status==0){
                        $status="Pending";
                    }
                    else if($value->status==1){
                        $status="Booked";
                    }
                    else if($value->status==2){
                        $status="Completed";
                    }
                    else if($value->status==3){
                        $status="Canceled";
                    }
                    else if($value->status==4){
                        $status="Check In";
                    }
                    else if($value->status==5){
                        $status="Check Out";
                    }
                    if($value->payment_status==1){
                        $paymentStatus="Paid";
                    }
                    else if($value->payment_status==2){
                        $paymentStatus="Partialy Paid";
                    }
                    else if($value->payment_status==3){
                        $paymentStatus="Refunded";
                    }
                    else{
                        $paymentStatus="Unpaid";
                    }
                    $allroomname = "";
                    $row[] =$i;
                    $row[] =$value->invoice_no;
                    $rname = explode(",",$value->hall_type);
                    if(empty($value->seatplan)){
                        $plan ="None";
                    }else{
                        $seat = explode(",",$value->seatplan);
                        $plan="";
                        for($l=0;$l<count($seat); $l++){
                            $seatplan = $this->db->select("plan_name")->from("tbl_hallroom_seatplan")->where("hsid",$seat[$l])->get()->row();
                            if(empty($seatplan->plan_name)){
                                $plan .= "None,";
                            }else{
                                $plan .= $seatplan->plan_name.",";
                            }
                        }
                    }
                    $allfacility = "";
                    for($l=0;$l<count($rname); $l++){
                        $roomname = $this->db->select("hall_type")->from("tbl_hallroom_info")->where("hid",$rname[$l])->get()->row();
                        $allroomname .= $roomname->hall_type.", ";
                        $facilities = $this->db->select("rfa.*, rfd.*")->from("roomfaility_ref_accomodation rfa")->join("roomfacilitydetails rfd", "rfd.facilityid=rfa.facilityid", "left")->where("hallid",$rname[$l])->get()->result();
                        foreach($facilities as $fl){
                            $allfacility .= $fl->facilitytitle.",";
                        }
                        $allfacility .= ",";
                    }
                    // $row[] =$i;
                    // $row[] =$value->invoice_no;
                    $row[] =$value->hall_type;
                    $row[] =$value->firstname;
                    $row[] =$value->cust_phone;
                    $row[] =$value->start_date;
                    $row[] =$value->end_date;
                    $row[] =$value->paid_amount;
                    $row[] =$value->totalamount;
                    $row[] =$status;
                    $row[] =$paymentStatus;
                    $row[] =$view.$print;
                    $data[] = $row;
                    }
                $json_data = array(
                    "draw"            => intval( $params['draw'] ),
                    "recordsTotal"    => intval( $totalRecords ),
                    "recordsFiltered" => intval($totalRecords),
                    "data"            => $data
                );
                echo json_encode($json_data);
            }
    
    
    public function hallroom_status($id = null)
    {
		$this->permission->method('hall_room','read')->redirect();				
		$this->db->set('tbl_roomnofloorassign.status',1);
		$this->db->where('tbl_hallroom_booking.end_date<',date("d-m-Y H:i:s"));
		$this->db->where('tbl_roomnofloorassign.status<>',1);
		$this->db->update('tbl_roomnofloorassign JOIN tbl_hallroom_booking ON FIND_IN_SET(tbl_roomnofloorassign.roomno,tbl_hallroom_booking.hall_no)<>0');
		$this->db->set('tbl_roomnofloorassign.status',2);
		$this->db->where('tbl_hallroom_booking.start_date<',date("d-m-Y H:i:s"));
		$this->db->where('tbl_hallroom_booking.end_date>',date("d-m-Y H:i:s"));
		$this->db->where('tbl_roomnofloorassign.status<>',2);
		$this->db->where('tbl_hallroom_booking.status',1);
		$this->db->update('tbl_roomnofloorassign JOIN tbl_hallroom_booking ON FIND_IN_SET(tbl_roomnofloorassign.roomno,tbl_hallroom_booking.hall_no)<>0');
        $data['title']    = display('hall_room');
        $currentDateTime = date("d-m-Y H:i:s");
	   $data["roomlist"] = $this->db->select('*')->from('tbl_hallroom_info')->get()->result();
        $data['module'] = "hall_room";
        $data['page']   = "roomlist";
        echo Modules::run('template/layout', $data);
    }
    
    
    
    
    
    
    
	public function hallroom_assign(){
		$data['title'] = display('assign_room');
		$data["allroom"] = $this->hallroom_model->allrooms();
		$data['module'] = "hall_room";
		$data['page']   = "hallroomassign";   
		echo Modules::run('template/layout', $data); 
	   }
	public function hallroom_facility(){
		$data['title'] = display('assign_facilities');
		$data["allroom"] = $this->hallroom_model->allrooms();
		$data['module'] = "hall_room";
		$data['page']   = "hallroomfacilitiassign";   
		echo Modules::run('template/layout', $data); 
	   }
	public function seatplan(){
		$data['title'] = display('seatplan');
		$data["seatplan"] = $this->hallroom_model->get_all('*','tbl_hallroom_seatplan','hsid');
		$data['module'] = "hall_room";
		$data['page']   = "seatplanlist";   
		echo Modules::run('template/layout', $data); 
	   }
	   public function updatehallfrm($id){
		$this->permission->method('hall_room','update')->redirect();
		$data['intinfo']   = $this->hallroom_model->findByHallId($id);
		$data['title']    = display('hallroom_type'); 
	    $data["hrdetails"] = $this->hallroom_model->hallroomread();
		$data["allsizes"] = $this->hallroom_model->allsizes();
		$data["seatplan"] = $this->hallroom_model->seatplan();
        $data['module'] = "hall_room";
        $data['page']   = "hallroomlistedit";  
		$this->load->view('hall_room/hallroomlistedit', $data);   
	   }
	   public function hallroomdelete($id = null)
	   {
		   $this->permission->module('hall_room','delete')->redirect();
		   
		   if ($this->hallroom_model->delete('tbl_hallroom_info','hid',$id)) {
			   $this->session->set_flashdata('message',display('delete_successfully'));
		   } else {
			   $this->session->set_flashdata('exception',display('please_try_again'));
		   }
		   redirect('hall_room/hallroom-type');
	   }
	   public function updateseatfrm($id){
		$this->permission->method('hall_room','update')->redirect();
		$data['intinfo']   = $this->hallroom_model->readall('*','tbl_hallroom_seatplan','hsid',array('hsid'=>$id));
		$data['title']    = display('seatplan'); 
        $data['module'] = "hall_room";
        $data['page']   = "seatplanlistedit";  
		$this->load->view('hall_room/seatplanlistedit', $data);   
	   }
	   public function seatdelete($id = null)
	   {
		   $this->permission->module('hall_room','delete')->redirect();
		   
		   if ($this->hallroom_model->delete('tbl_hallroom_seatplan','hsid',$id)) {
			   $this->session->set_flashdata('message',display('delete_successfully'));
		   } else {
			   $this->session->set_flashdata('exception',display('please_try_again'));
		   }
		   redirect('hall_room/seatplan');
	   }
	   public function getfloorwithroom($id){
		$data["allfloorwiseroom"] = $this->hallroom_model->allfloor();
		$data['crroomid'] = $id;
		$data['module'] = "hall_room";
		$data['page']   = "hallcheckroomassign";   
		$this->load->view('hall_room/hallcheckroomassign', $data); 
	   }
	   public function roomassigninsert(){
		$roomid=$this->input->post('roomid', TRUE);
		$exitsroom=$this->db->select("hallid")->from('tbl_roomnofloorassign')->where('hallid',$roomid)->get()->row();
		if(!empty($exitsroom)){
			$this->db->where('hallid',$roomid)->delete('tbl_roomnofloorassign');
		}
		$floor="floorid_".$roomid;
		$allfloor=$this->input->post($floor, true);
		$totalfloor=count($allfloor);
		for($i=0;$i<$totalfloor;$i++){
			$floorid=$allfloor[$i];
			$roomno="roomno".$allfloor[$i].$roomid;
			$allroomno=$this->input->post($roomno, true);
			if(!empty($allroomno)){
				$totalroom=count($allroomno);
				for($j=0;$j<$totalroom;$j++){
					$roomnoid=$allroomno[$j];					
						
						$postData = array(
					   'hallid'     	     	 => $roomid,
					   'floorid' 	             => $allfloor[$i],
					   'roomno' 	     		 => $roomnoid,
					  );
					  $this->db->insert('tbl_roomnofloorassign',$postData);
					}
			}
				
		}
		$this->session->set_flashdata('message', display('save_successfully'));
		redirect('hall_room/hallroom-assign');	
		}
		public function getfacilities($id){
			$data["allfacilities"] = $this->hallroom_model->allfacility();
			$data['crroomid'] = $id;
			$data['module'] = "hall_room";
			$data['page']   = "hallcheckroomfacility";   
			$this->load->view('hall_room/hallcheckroomfacility', $data); 
		   }
		public function roomfacilitiassigninsert(){
			$roomid=$this->input->post('roomid', TRUE);
			$exitsroom=$this->db->select("hallid")->from('roomfaility_ref_accomodation')->where('hallid',$roomid)->get()->row();
			if(!empty($exitsroom)){
			$this->db->where('hallid',$roomid)->delete('roomfaility_ref_accomodation');
			}
			$service="services_".$roomid;
			$allservice=$this->input->post($service);
			$totalservice=count($allservice);
			for($i=0;$i<$totalservice;$i++){
					$serviceid=$allservice[$i];
					$facility="facilities".$allservice[$i].$roomid;
					$allfacility=$this->input->post($facility);
					if(!empty($allfacility)){
						$totalfacility=count($allfacility);
						for($j=0;$j<$totalfacility;$j++){
							$getfacility=$allfacility[$j];
							
								$postData = array(
							   'hallid'     	     	 => $roomid,
							   'facilititypeid' 	     => $allservice[$i],
							   'facilityid' 	     	 => $getfacility,
							  );
							  $this->db->insert('roomfaility_ref_accomodation',$postData);
							}
					}
					
				}
			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('hall_room/hallroom-facility');	
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
			public function report(){
				$data['title'] = display('report');
				$data["report"] = $this->hallroom_model->get_all('*','tbl_hallroom_booking','hbid');
				$data['customerlist']   = $this->hallroom_model->hallcustomerlist(); 
				$data['module'] = "hall_room";
				$data['page']   = "reportlist";   
				echo Modules::run('template/layout', $data); 
			}
			public function report_search(){
				$customer=$this->input->post('customer',TRUE);
				$status=$this->input->post('status',TRUE);
				$payment_status=$this->input->post('payment_status',TRUE);
				$startdates=$this->input->post('start_date',TRUE);
				$endate=$this->input->post('to_date',TRUE);
				$data['report']   = $this->hallroom_model->getlist($customer,$status,$payment_status,$startdates,$endate);
				$data['module'] = "hall_room";  
				$data['page']   = "getbookingreport";
				$this->load->view('hall_room/getbookingreport', $data);   
			}
			public function report_details($id){
				$details=$this->hallroom_model->details($id);
				$data['bookinfo']   = $details;
				$data['customerinfo']   = $this->hallroom_model->customerinfo($details->customerid);
				$data['paymentinfo']   = $this->hallroom_model->paymentinfo($details->hbid);
				$data['storeinfo']=$this->hallroom_model->storeinfo();
				$data['taxinfo']=$this->hallroom_model->taxinfo();
				$data['btaxinfo']=$this->hallroom_model->btaxinfo($id);
				$data['setting'] = $this->hallroom_model->settinginfo();
				$data['commominfo']=$this->hallroom_model->commoninfo();
				$data['currency']=$this->hallroom_model->currencysetting($data['storeinfo']->currency);
				$data['module'] = "hall_room";
				$data['page']   = "bookindetails";   
				echo Modules::run('template/layout', $data); 
			}

			public function checkindatatable()
			{
				$params = $columns = $totalRecords = $data = array();
				$params = $_REQUEST;
			    $columns = array( 
				0 => 'tbl_hallroom_booking.hbid', 
				1 => 'invoice_no', 
				// 2 => 'hall_type',
				// 3 => 'tbl_hallroom_booking.hall_no',
				4 => 'customerinfo.firstname',
				5 => 'customerinfo.cust_phone',
				6 => 'start_date',
				7 => 'end_date',
				8 => 'payment_status',
				9 => 'status',
				10 => 'event_name',
				11 => 'event_type',
				);

				$where = $sqlTot = $sqlRec = "";
				// check search value exist
				if(!empty($params['search']['value']) ) {   
					$where .=" WHERE ";
					$where .=" ( tbl_hallroom_booking.invoice_no LIKE '".$params['search']['value']."%' ";    
					$where .=" OR tbl_hallroom_booking.hall_type LIKE '".$params['search']['value']."%' ";
					$where .=" OR tbl_hallroom_booking.hall_no LIKE '".$params['search']['value']."%' ";
					$where .=" OR customerinfo.firstname LIKE '".$params['search']['value']."%' ";
					$where .=" OR customerinfo.cust_phone LIKE '".$params['search']['value']."%' ";
					$where .=" OR tbl_hallroom_booking.start_date LIKE '".$params['search']['value']."%' ";
					$where .=" OR tbl_hallroom_booking.end_date < '".$params['search']['value']."%' ";
					$where .=" OR tbl_hallroom_booking.status LIKE '1' )  AND (tbl_hallroom_booking.status=1)";

				}
				// getting total number records without any search
				$sql = "SELECT tbl_hallroom_booking.*,customerinfo.firstname,customerinfo.cust_phone FROM tbl_hallroom_booking Left join customerinfo ON customerinfo.customerid=tbl_hallroom_booking.customerid where tbl_hallroom_booking.status=4";
				
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
				foreach($queryRecords as  $value){
					$i++;
					$row = array();
					$update='';
					$delete='';
					if($this->permission->method('hall_room','update')->access()):
					$update='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallcheckinedit('.$value->hbid.')" class="btn btn-warning btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Update" title="Update Reservation"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>';
					endif;
					if($this->permission->method('hall_room','create')->access()):
					$Payment='<a href="'.base_url().'hall_room/payment-information/'.$value->hbid.'" class="btn btn-success btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Payment" title="Payment"><i class="ti-wallet"></i></a>';
					endif;
					if($this->permission->method('hall_room','update')->access()):
					$cancel='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallcancelreservation('.$value->hbid.')" class="btn btn-danger btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Cancel" title="Cancel Reservation"><i class="ti-close text-white" aria-hidden="true"></i></a>';
					endif;
					if($this->permission->method('hall_room','update')->access()):
					$checkin='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hallcheckoutresrvation('.$value->hbid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Check Out" title="Check-Out"><i class="ti-thumb-up text-white" aria-hidden="true"></i></a>';
					endif;
						if($this->permission->method('hall_room','update')->access()):
					$adv='<input name="url" type="hidden" id="url_'.$value->hbid.'"/><a onclick="hall_adv_resrvation('.$value->hbid.')" class="btn btn-dark btn-sm margin_right_5px" data-toggle="tooltip" data-placement="top" data-original-title="Check Out" title="Advance Receipt"><i class="ti-money text-white" aria-hidden="true"></i></a>';
					endif;
					if($value->status==0){
						$status="Pending";
					}
					else if($value->status==1){
						$status="Booked";
					}
					else if($value->status==2){
						$status="Completed";
					}
					else if($value->status==3){
						$status="Canceled";
					}
					else if($value->status==4){
						$status="Check In";
					}
					else if($value->status==5){
						$status="Check Out";
					}
					if($value->payment_status==1){
						$paymentStatus="Paid";
					}
					else if($value->payment_status==2){
						$paymentStatus="Partialy Paid";
					}
					else if($value->payment_status==3){
						$paymentStatus="Refunded";
					}
					else{
						$paymentStatus="Unpaid";
					}
					$allroomname = "";
					$row[] =$i;
					$row[] =$value->invoice_no;
					$rname = explode(",",$value->hall_type);
					if(empty($value->seatplan)){
						$plan ="None";
					}else{
						$seat = explode(",",$value->seatplan);
						$plan="";
						for($l=0;$l<count($seat); $l++){
							$seatplan = $this->db->select("plan_name")->from("tbl_hallroom_seatplan")->where("hsid",$seat[$l])->get()->row();
							if(empty($seatplan->plan_name)){
								$plan .= "None,";
							}else{
								$plan .= $seatplan->plan_name.",";
							}
						}
					}
					$allfacility = "";
					for($l=0;$l<count($rname); $l++){
						$roomname = $this->db->select("hall_type")->from("tbl_hallroom_info")->where("hid",$rname[$l])->get()->row();
						$allroomname .= $roomname->hall_type.", ";
						$facilities = $this->db->select("rfa.*, rfd.*")->from("roomfaility_ref_accomodation rfa")->join("roomfacilitydetails rfd", "rfd.facilityid=rfa.facilityid", "left")->where("hallid",$rname[$l])->get()->result();
						foreach($facilities as $fl){
							$allfacility .= $fl->facilitytitle.",";
						}
						$allfacility .= ",";
					}
					$row[] =trim($allroomname,", ");
					$row[] =$value->event_name;
					$row[] =$value->event_type;
					$row[] =trim($plan,",");
					$row[] =$value->firstname;
					$row[] =$value->cust_phone;
					$row[] =$value->start_date;
					$row[] =$value->end_date;
					$row[] =$status;
					$row[] =$value->totalamount;
					$row[] =$value->paid_amount;
					$row[] =$paymentStatus;
					if($paymentStatus=='Partialy Paid'){
			$row[] =$update.$cancel.$adv.$checkin;
		}else{
		   	$row[] =$update.$cancel.$checkin; 
		}
		
					//$row[] =$update.$cancel.$adv.$checkin;
					$data[] = $row;
					
					}
				
				$json_data = array(
					"draw"            => intval( $params['draw'] ),   
					"recordsTotal"    => intval( $totalRecords ),  
					"recordsFiltered" => intval($totalRecords),
					"data"            => $data   
				);

				echo json_encode($json_data);
			}
			
		

	 


			public function submitcheckout($hbid){
			   // print_r($_POST);die();
				$creditamount = $this->input->post("creditamount", true);
				$refunddamt = $this->input->post("refunddamt", true);
				$disamount = $this->input->post("disamount", true);
				$allcomplementarycharge = $this->input->post("allcomplementarycharge", true);
				$specialdiscount = $this->input->post("specialdis", true);
				$allbpccharge = $this->input->post("allbpccharge", true);
			$alladvanceamount = $this->input->post("alladvanceamount", true);
			
				$collectedamt = $this->input->post("collectedamt", true);
				$payableamt = $this->input->post("payableamt", true);
					$a = $this->input->post("additionalcharge", true);
						$additional_reason = $this->input->post("additional_reason", true);
		$additionalcharge = $this->input->post("additionalcharge", true);
		$additional_remarks = $this->input->post("additional_remarks", true);
				$nod = $this->input->post("nod", true);
				$cgst = $this->input->post("discount_cgst", true);
				$sgst = $this->input->post("discount_sgst", true);
				$taxamount = $this->input->post("taxamount", true);
				$totalroomrentamount= $this->input->post("totalroomrentamount", true);
				$total_Amount = $this->input->post("total_Amount", true);
			
				$paymentmode = $this->input->post("paymentmode", true);   
					$rentafterdiscount = $this->input->post("rentafterdiscount", true);
					
				$paymentamount = $this->input->post("paymentamount", true);
				$hallType = $this->input->post("h_type", true);
				$bookingid = $this->input->post("booking_id", true);
				$taxdetail = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
				$taxname="";
				$rate="";
				
				foreach($taxdetail as $taxinfo){
					$taxname .= $taxinfo->taxname.",";
					$rate .= $taxinfo->rate.",";
				}
				
                $roomupdatestatus = $this->db->where("hid", $hallType)->update("tbl_hallroom_info", array("room_status" => 0));
    
				$totalbill = $this->db->select("*")->from("tbl_hallroom_booking")->where("tbl_hallroom_booking.hbid",$hbid)->get()->row();
			//	echo $this->db->last_query();
				
// 			if ($specialdiscount > 0) {
//     $t_fr_tx = $totalroomrentamount; // Assuming $totalroomrentamount holds the total amount
//     $t = floatval($t_fr_tx); // Parse total amount as a floating point number

//     // Calculate CGST and SGST
//     $cgst = ($t * 9) / 100; // 9% CGST
//     $sgst = ($t * 9) / 100;

//    // echo $totalroomrentamount . "//" . $sgst . "//" . $cgst;
// }

//echo $specialdiscount . "//" . $additionalcharge . "//" . $sgst;

// Check the values of specialdiscount, additionalcharge, and sgst outside the if block
//echo "spl" . $this->input->post("specialdis", true);
			//	 echo  $specialdiscount."//".$additionalcharge."//".$sgst;
			//	echo "spl".$this->input->post("specialdis", true);
			$bill = $totalroomrentamount+ $sgst+$cgst+$additionalcharge;
			
				$additional_reason = $this->input->post("additional_reason", true);
		$additionalcharge = $this->input->post("additionalcharge", true);
		$additional_remarks = $this->input->post("additional_remarks", true);
		$pstatus='';
		if($collectedamt >= $payableamt){
		    $pstatus=1;
		}else if ($collectedamt < $payableamt)
		{
		     $pstatus=2;
		}else if($collectedamt <=0){
		    $pstatus=0; 
		}
$checkoutdata = array(
    'hbid' => $hbid,
    'paid_amount' => ($collectedamt+$alladvanceamount),
    'totalamount' => ($payableamt+$alladvanceamount),
    'status' => 5,
    'payment_status'=> $pstatus,
	'cgst' => $cgst,
	'overall_amount'  =>($payableamt),
	'adv_amt' => $alladvanceamount,
		'sgst' => $sgst,
		'additional_remarks' => $additional_remarks,
	'additional_reason' => $additional_reason,
    'additional_charges' => $a,
    'special_discount' => $this->input->post("specialdis", true)
);

$result = $this->db->where("hbid", $hbid)->update("tbl_hallroom_booking", $checkoutdata);
	//	echo $this->db->last_query();die();
					if ($specialdiscount > 0) {
					$checkoutdata = array(
				   'rent'  => $totalroomrentamount,
					'cgst' => $cgst,
						'sgst' => $sgst
			
				);

				$result = $this->db->where("hbid",$hbid)->update("tbl_hallroom_booking",$checkoutdata);
	//	echo $this->db->last_query();die();
					}
            //   die();
				$paidhall = $this->db->select("totalamount")->from("tbl_hallroom_booking")->where("hbid", $hbid)->get()->row();
			    $roomStatus = $this->db->where("hbid", $hbid)->update("tbl_hallroom_booking", array('paid_amount'=>$paidhall->totalamount,'payment_status'=>1));
			    
			    $checkout_booking = $this->db->select("*")->from("tbl_hallroom_booking")->where("invoice_no", $bookingid)->get()->row();

				if(!empty($checkout_booking)) {
				    $bookingnum = (int) substr($checkout_booking->invoice_no, 2); 
				} else {
				    $bookingnum = 0;
				}
				
				$bookingnextno = $bookingnum + 1;
                $bookinginvoice_no = "BQT-" . str_pad($bookingnextno, 8, '0', STR_PAD_LEFT);  
			 //  echo  $paymentamount;die();
				if($result){
					if($paymentamount>0){
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
						$cheque_no = explode(",", $cheque_no);
						$cheque_date = explode(",", $cheque_date);
						$expirydate = explode(",", $expirydate);
						$online_referenceno = explode(",", $online_referenceno);
						$upi_referenceno = explode(",", $upi_referenceno);
                   $singlepayment = explode(",", $paymentmode);
					$singleamount = explode(",", $paymentamount);
						$singlepayment=	str_replace("undefined","",$singlepayment);
					$singlebankname = explode(",", $bankname);
					$singlecardno = explode(",", $cardno);
					for($i=0, $j=0; $i<count($singlepayment); $i++){
						if($i==(count($singlepayment)-1)){
							//change ammount refunded to customer
							$singleamount[$i] -= (!empty($refunddamt)?$refunddamt:0);
						}
						$postData = array(
							'bookedid' 	         	 => $hbid,
							'invoice' 	             => $bookinginvoice_no,
							'paydate' 	             => $newdate,
							'paymenttype' 	         => $singlepayment[$i],
							'paymentamount' 	     => $singleamount[$i],
						
							'book_type' 	     	 => 1,
							);
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
                    $postData['details'] = "Bank Payment : " . $bankname."-".$accountnumber;
                } elseif ($singlepayment[$i] == "Card Payment") {
                    $cardno = $this->input->post('cardno', TRUE);
                    $expirydate = $this->input->post('expirydate', TRUE);
                    $ccv = $this->input->post('ccv', TRUE);
                    $postData['details'] = "Card Number: " . $cardno . " Expiry date: " . $expirydate . " CCV: " . $ccv;
                }elseif ($paymentmode == "Cash") {
                   
                    $postData['details'] = "Cash";
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

					//generate pdf
					$this->load->library('pdfgenerator');
					$file = $this->viewdetailsprint($hbid,'pdf');
	        		$file_path = $this->pdfgenerator->generate_pdf($hbid, $file);
					//sending email to customer
					$binfo = $this->db->select("b.*,c.*, hi.*")->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info hi","hi.hid=b.hbid","left")->where("hbid",$hbid)->get()->row();
					$this->email_send($binfo,5,$file_path);
					//end
					echo '<h5>Success</h5>Checkout Successfully';

				}else {
				    echo '<h5>Failed</h5>Please Try Again';
			    }

				
			}




public function viewdetailsprint($id,$pdf=null){
                $halldata = $this->hallroom_model->cdetailbooking($id);
                $data["bookingdata"] = $halldata;
            //    print_r($halldata);die();
                //  $data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
                // $data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
                $data['customerinfo']   = $this->hallroom_model->customerinfo2($halldata->customerid);
                // $data["paymentdetails"] = $this->hallroom_model->get_all($halldata->invoice_no,$halldata->hbid);
                $data["paymentdetails"] = $this->hallroom_model->paymentinfo_hall($halldata->hbid);
                $data['commominfo']=$this->hallroom_model->commoninfo2();
                $data['storeinfo']=$this->hallroom_model->storeinfo2();
                $data['taxinfo']=$this->hallroom_model->taxinfo2();
                $data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
                $data['currency']    = getCurrency();
                if(empty($pdf)){
                    $this->load->view('hall_room/bookindetailsprint', $data);
                }else{
                    $pdfhtml = $this->load->view('hall_room/bookindetailsprintpdf', $data, true);
                    return $pdfhtml;
                }
            }

public function viewdetails_adv_print($id,$pdf=null){
                $halldata = $this->hallroom_model->cdetailbooking($id);
                $data["bookinfo"] = $halldata;
             $data['customerinfo']   = $this->hallroom_model->customerinfo2($halldata->customerid);
                // $data["paymentdetails"] = $this->hallroom_model->get_all($halldata->invoice_no,$halldata->hbid);
                $data["paymentdetails"] = $this->hallroom_model->paymentinfo_hall($halldata->hbid);
                $data['commominfo']=$this->hallroom_model->commoninfo2();
                $data['storeinfo']=$this->hallroom_model->storeinfo2();
                $data['taxinfo']=$this->hallroom_model->taxinfo2();
                $data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
                $data['currency']    = getCurrency();
		//		print_r($data);die();
                if(empty($pdf)){
                    $this->load->view('hall_room/advance_receipt', $data);
                }else{
                    $pdfhtml = $this->load->view('hall_room/advance_receipt', $data, true);
                    return $pdfhtml;
                }
            }


// 			public function viewdetailsprint($id,$pdf=null){
// 				$halldata = $this->hallroom_model->cdetailbooking($id);
//                 $data["bookingdata"] = $halldata;
//               //  print_r($halldata);die();
//                 $data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
//                 $data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
//                 $data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
//                 $data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
// 		        $data['currency']    = getCurrency(); 
// 				if(empty($pdf)){
// 					$this->load->view('hall_room/bookindetailsprint', $data); 
// 				}else{
// 					$pdfhtml = $this->load->view('hall_room/bookindetailsprintpdf', $data, true);
// 					return $pdfhtml;
// 				}
// 			}


			public function bookingcheckout($id = null)
			{
				$this->permission->method('hall_room','read')->redirect();
		        $data['title'] = display('checkout');   
		        
		        for($i=0; $i<count($id); $i++){
		        	$halldata[$i] = $this->hallroom_model->cdetailbooking($id);
		        }
		        	$data["setting"] = $this->db->select("title,address,email,phone")->from("setting")->where("id",2)->get()->row();
             
                $data["bookingdata"] = $halldata;
                  // print_r($data["bookingdata"]);
                $data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
                $data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
                  $data["advance"] = $this->db->select("paymentamount")->from("tbl_guestpayments ")->where("invoice",$data["bookingdata"][0]->invoice_no)->get()->row();
             // echo $this->db->last_query();
                $data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
                
               $data["paymentdetails"] = $this->hallroom_model->paymentlist();
		        $data['currency']    = getCurrency(); 
		        $data['module'] = "hall_room";
		        $data['page']   = "checkoutreservation";   
		        $this->load->view("hall_room/checkoutreservation", $data); 
			}

			public function directcheckin($id = null)
			{
				$this->permission->method('hall_room','read')->redirect();
				$data["hallroomdetails"] = $this->hallroom_model->get_all('hid,hall_type,rate,room_status','tbl_hallroom_info','hid');
				$data["paymentdetails"] = $this->hallroom_model->paymentlist();
				$data['currency']    = getCurrency(); 
				$data["setting"] =$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
				$data['module'] = "hall_room";
		        $data['page']   = "addcheckin";   
		        $this->load->view("hall_room/addcheckin", $data);
			}


		    public function checkin($id = null)
		    {
				$this->permission->method('hall_room','read')->redirect();
		        $data['title']    = display('checkin');   
		        $data['module'] = "hall_room";
		        $data['page']   = "checkin";   
		        echo Modules::run('template/layout', $data); 
		    }

		    public function bookingcheckin($id = null)
		    {
				$this->permission->method('hall_room','read')->redirect();		
		        $data['title']   = display('checkin'); 
			    $data["bookingdata"] = $this->hallroom_model->editbooking($id);
			    $data["bookingpaymentdata"] = $this->hallroom_model->booking_payment($id);
			    $data["guestdata"] = $this->db->select("customerid,guestname,mobile")->from("tbl_otherguest")->where("tbl_otherguest.bookedid", $id)->where('type',1)->get()->result();
			    $data["custdata"] = $this->db->select("tbl_hallroom_booking.customerid,firstname,cust_phone")->from("tbl_hallroom_booking")->join("customerinfo","customerinfo.customerid=tbl_hallroom_booking.customerid","left")->where("tbl_hallroom_booking.hbid", $id)->get()->result();
				$data["hallroomdetails"] = $this->hallroom_model->get_all('hid,hall_type','tbl_hallroom_info','hid');
				$data["roomseatplan"] = $this->hallroom_model->roomseatplan();
			    $data["checkincustomer"] = $this->db->select("cust_phone,customerid,b.bookedid,b.booking_number")->from("customerinfo")->join("booked_info b","b.cutomerid=customerinfo.customerid")->where("b.checkindate<=",date("d-m-Y H:i:s"))->where("b.checkoutdate>=",date("d-m-Y H:i:s"))->where("bookingstatus",4)->get()->result();
				$data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
				$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
			    $data["roomlist"] = $this->hallroom_model->allrooms();
				$data["customerlist"] = $this->hallroom_model->customerlist();
				$data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
				$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
				$data['currency']    = getCurrency();   
				$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
				$data["setting"] = $setting;
		        $data['module'] = "hall_room";
		        $data['page']   = "checkinreservation";   
		        $this->load->view("hall_room/checkinreservation", $data);  
		    } 

		    public function checkout($id = null)
		    {
				$this->permission->method('hall_room','read')->redirect();
						
		        $data['title']    = display('checkout'); 
				$data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
		        $data['module'] = "hall_room";
		        $data['page']   = "checkout";   
		        echo Modules::run('template/layout', $data); 
		    }

		    public function checkoutall($id = null)
		    {
		    	$this->permission->method('hall_room','read')->redirect();
		        $data['title'] = display('checkout');   
		        
		        $halldata = $this->hallroom_model->cdetailbooking($id);
             	$data["setting"] = $this->db->select("title,address,email,phone")->from("setting")->where("id",2)->get()->row();
                $data["bookingdata"] = $halldata;
                $data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
                $data["invoicelogo"] = $this->db->select("invoice_logo")->from("common_setting")->where("id",1)->get()->row();
                $data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
                $data["paymentdetails"] = $this->hallroom_model->get_all('*','payment_method','payment_method_id');
		        $data['currency']    = getCurrency(); 
		        $data['module'] = "hall_room";
		        $data['page']   = "checkoutall";   
		        $this->load->view("hall_room/checkoutall", $data);  
		    }

		    public function email_send($binfo=null, $check=null, $path=null){
				if(!empty($binfo)){
					$appName = $this->db->select("title")->from("setting")->where("id",2)->get()->row();
					$subject = "Hall Booking Successfull";
					$body = "Your Hall Booking Successfully Completed.";
					if($check==4){
						$subject = "Checkin Successfull";
						$body = "Your Checkin Successfully Completed.";
					}
					if($check==5){
						$subject = "Checkout Successfull";
						$body = "Your Checkout Successfully Completed.";
					}
					$htmlContent = nl2br("Dear ".$binfo->firstname.",\n\n".$body."\nBooking Number:".$binfo->invoice_no."\nRoom No:".$binfo->hall_type."\nTotal Rent:".$binfo->totalamount."\n\nThank You");
					$this->hallroom_model->send_email(strtolower($binfo->email),$subject,$appName->title,$htmlContent,$path);
				}
			}
			
			public function checkinedithall($id = null){
			    $this->permission->method('hall_room','read')->redirect();		
                $data['title']    = display('hall_room'); 
        	    $data["bookingdata"] = $this->hallroom_model->editbooking($id);
        		//print_r($data["bookingdata"]);
        	    $data["bookingpaymentdata"] = $this->hallroom_model->booking_payment($id);
        	    $data["guestdata"] = $this->db->select("customerid,guestname,mobile")->from("tbl_otherguest")->where("tbl_otherguest.bookedid", $id)->where('type',1)->get()->result();
        	    $data["custdata"] = $this->db->select("tbl_hallroom_booking.customerid,firstname,cust_phone")->from("tbl_hallroom_booking")->join("customerinfo","customerinfo.customerid=tbl_hallroom_booking.customerid","left")->where("tbl_hallroom_booking.hbid", $id)->get()->result();
        		$data["hallroomdetails"] = $this->hallroom_model->get_all('hid,hall_type,room_status','tbl_hallroom_info','hid');
        		$data["roomseatplan"] = $this->hallroom_model->roomseatplan();
        	    $data["checkincustomer"] = $this->db->select("cust_phone,customerid,b.bookedid,b.booking_number")->from("customerinfo")->join("booked_info b","b.cutomerid=customerinfo.customerid")->where("b.checkindate<=",date("d-m-Y H:i:s"))->where("b.checkoutdate>=",date("d-m-Y H:i:s"))->where("bookingstatus",4)->get()->result();
        		
        		$data["banklist"] = $this->db->query("SELECT HeadCode,HeadName FROM acc_coa WHERE HeadLevel='4' And HeadCode LIKE '1020102%'")->result();
        	    $data["roomlist"] = $this->hallroom_model->allrooms();
        		$data["customerlist"] = $this->hallroom_model->customerlist();
        		$data["paymentdetails"] = $this->hallroom_model->paymentlist();
        		$data["inouttime"] = $this->db->select("checkintime,checkouttime")->from("setting")->where("id",2)->get()->row();
        		$data['currency']    = getCurrency();   
        		$setting=$this->db->select("rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
        		
        		$data["setting"] = $setting;
                $data['module'] = "hall_room";
                $data['page']   = "editcheckinhall";   
                $this->load->view("hall_room/editcheckinhall", $data); 
		    }






		public function viewCheckout($id = null)
	    {
			$this->permission->method('hall_room','read')->redirect();
					
	        $data['title']    = display('checkout'); 
			$data["checkinrooms"] = $this->db->select('b.*,c.*, h.*')->from("tbl_hallroom_booking b")->join("customerinfo c","c.customerid=b.customerid","left")->join("tbl_hallroom_info h","h.hid=b.hbid","left")->where("b.status",4)->get()->result();
	        $data['module'] = "hall_room";
	        $data['page']   = "directcheckout";   
	        echo Modules::run('template/layout', $data); 
	    }




		
	    public function detailView($id = null){

		   $data["bookinginfo"] = $this->hallroom_model->findhallDetail($id);
		    $data["htyp"] = $this->db->select('h.hall_type')->from("tbl_hallroom_booking b")->join("tbl_hallroom_info h","h.hid=b.hall_type","left")->where('b.hbid',$id)->get()->result();
		   
		   $data["paymentmethod"] = $this->hallroom_model->paymentlist();
		   $data["paymentlist"] = $this->hallroom_model->findBypayId($id);
		   $data['module'] = "hall_room";
		   $data['page']   = "reservationdetail";   
		    echo Modules::run('template/layout', $data); 
		}









}

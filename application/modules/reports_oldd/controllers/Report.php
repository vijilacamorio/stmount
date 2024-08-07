    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->load->model(array(
			'report_model',
			'day_closing/cashregister_model'
		));	
			
		 $this->load->library('cart');
    }
    public function orderlist_report(){
			// echo "module";  die();
			$data['title'] = display('order_list');
			$data['module'] = "reports";
			$data['customerlist']   = $this->report_model->customerlist();
			$data['page']   = "orderlist_search";
	
			$this->load->library('pagination');
			#
			#pagination starts
			#
			$config["base_url"]       = base_url('reports/report/orderlist_search/');
			$config["total_rows"]     = $this->db->count_all('booked_info');
			$config["per_page"]       = 15;
			$config["uri_segment"]    = 4;
			$config["num_links"]      = 5;
			/* This Application Must Be Used With BootStrap 4 * */
			$config['full_tag_open']='<ul class="pagination pagination-md">';
			$config['full_tag_close']='</ul>';
			$config['first_link'] = false;
			$config['first_tag_open'] = '<li class="page-item disabled">';
			$config['first_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li class="page-item">';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
			$config['cur_tag_close'] = '</a></li>';
			$config['next_link'] = '<i class="ti-angle-right"></i>';
			$config['next_tag_open'] = '<li class="page-item">';
			$config['next_tagl_close'] = '</a></li>';
			$config['prev_link'] = '<i class="ti-angle-left"></i>';
			$config['prev_tag_open'] = '<li class="page-item">';
			$config['prev_tagl_close'] = '</li>';
			$config['last_link'] =false;
			$config['last_tag_open'] = '<li class="page-item">';
			$config['last_tagl_close'] = '</a></li>';
			$config['attributes'] = array('class' => 'page-link');
			/* ends of bootstrap */
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data["bookings"] = $this->report_model->read();
			$data["links"] = $this->pagination->create_links();
			#
			#pagination ends
			#
			echo Modules::run('template/layout', $data);
			}
			
			
public function nightaudit(){
	$this->permission->method('reports','read')->redirect();
        $data['title']    = display('purchase_report'); 
		$today_date = date('d-m-Y');
		$current_month = date('Y-m');
		$start_month = $current_month . '-01 00:00:00'; // Start of the month
    $end_month = date('Y-m-t', strtotime($current_month)) . ' 23:59:59';
			$saveid=$this->session->userdata('id'); 
	$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid',$saveid)->where('status',0)->order_by('id','DESC')->get()->row(); 
	
	$current_year = date('Y'); // Get current year in 'YYYY' format
    $start_year = $current_year . '-01-01 00:00:00'; // Start of the year
    $end_year = $current_year . '-12-31 23:59:59';
		$first_date = str_replace('/','-',$this->input->post('from_date',TRUE));
		$start_date= date('d-m-Y' , strtotime($first_date));
		$second_date = str_replace('/','-',$this->input->post('to_date',TRUE));
		$end_date= date('d-m-Y' , strtotime($second_date));
		
			$data['nightaudit_advance']  = $this->report_model->nightaudit_advance();
				$data['nightaudit_advance_m']  = $this->report_model->nightaudit_advance_m();
					$data['nightaudit_advance_y']  = $this->report_model->nightaudit_advance_y();
					$data['nightaudit_advance_ad']  = $this->report_model->nightaudit_advance_ad();
				$data['nightaudit_advance_m_ad']  = $this->report_model->nightaudit_advance_m_ad();
					$data['nightaudit_advance_y_ad']  = $this->report_model->nightaudit_advance_y_ad();
			
		$data['nightaudit_amt_due_received']  = $this->report_model->nightaudit_amt_due_received();
 				$data['nightaudit_amt_due_received_m']  = $this->report_model->nightaudit_amt_due_received_m();
 					$data['nightaudit_amt_due_received_y']  = $this->report_model->nightaudit_amt_due_received_y();
	
	$data['hall_adv'] = $this->report_model->nightaudit_hall_advance();
		$data['hall_adv_m'] = $this->report_model->nightaudit_hall_advance_m();
			$data['hall_adv_y'] = $this->report_model->nightaudit_hall_advance_y();
//	print_r($data['nightaudit_advance_y']);die();
	
	$total_advance_amounty = 0.00;
foreach ($data['nightaudit_advance_y'] as $object) {
    $data['total_advance_amounty'] += $object->advance_amount;
}
	
		$total_advance_amountm = 0.00;
foreach ($data['nightaudit_advance_m'] as $object) {
    $data['total_advance_amountm'] += $object->advance_amount;
}
	
	$total_advance_amountt = 0.00;
foreach ($data['nightaudit_advance'] as $object) {
    $data['total_advance_amountt'] += $object->advance_amount;
}	
		$data["taxsetting"] = $this->db->select("taxname,rate")->from("tbl_taxmgt")->where("isactive",1)->get()->result();
       $data["tax_with_amount"] = $this->db->select("tax_with_amount")->from("tbl_postedbills")->where("checkoutdate BETWEEN '" . date('d-m-Y', strtotime($today_date)) . " 00:00:00' AND '" . date('d-m-Y', strtotime($today_date)) . " 23:59:59'")->where("tax_with_amount IS NOT NULL")->get()->result();
	    $data["tax_with_amount_mnth"] = $this->db->select("tax_with_amount")->from("tbl_postedbills")->where("checkoutdate BETWEEN '$start_month' AND '$end_month'")->get()->result();
		 $data["tax_with_amount_year"] = $this->db->select("tax_with_amount")->from("tbl_postedbills")->where("checkoutdate BETWEEN '$start_year' AND '$end_year'")->get()->result();
	//echo $this->db->last_query();	
	 //  print_r($data["tax_with_amount"]);die();
		$data['nightaudit1']  = $this->report_model->nightaudit();
$sums = [
    'paid_amount' => 0,
    'roomrate' => 0,
    'total_room' => 0,
    'children' => 0,
   
    'nuofpeople' => 0,
    'total_price' => 0,
    'discountamount' => 0,
    'cgst' => 0,
    'sgst' => 0,
    'bc' => 0,
    'pc' => 0,
    'cc' => 0
];

foreach ($data['nightaudit1'] as $item) {
    foreach ($sums as $key => $value) {
        $sums[$key] += $item[$key]; // Update to use array indexing
    }
}

// Pass the summed values to the view
$data['nightaudit'] = $sums;

		$data['nightaudit_hall']  = $this->report_model->nightaudit_hall();

  
		$data['nightaudit_current_month_hall']  = $this->report_model->nightaudit_current_month_hall();
	//	print_r($data['nightaudit_current_month_hall']);die();
		
	
    
		$data['nightaudit_current_year_hall']  = $this->report_model->nightaudit_current_year_hall();

    
		$today_data=$this->report_model->nightaudit_payment_method_room();
		$month_data=$this->report_model->nightaudit_payment_method_room_month();
		$year_data =$this->report_model->nightaudit_payment_method_room_year();
		$payment_hall_today =$this->report_model->nightaudit_payment_method_hall();
		$payment_hall_month =$this->report_model->nightaudit_payment_method_hall_month();
		$payment_hall_year  =$this->report_model->nightaudit_payment_method_hall_year();
// 		$data['checkin']=$this->report_model->nightaudit_room_status_checkin();
// 	//	print_r(	$data['checkin']);die();
// 			$data['checkin_m']=$this->report_model->nightaudit_room_status_checkin();
// 			$data['checkin_y']=$this->report_model->nightaudit_room_status_checkin();
// 			$data['checkout']=$this->report_model->nightaudit_room_status_checkout();
// 				$data['checkout_m']=$this->report_model->nightaudit_room_status_checkout();
// 				$data['checkout_y']=$this->report_model->nightaudit_room_status_checkout();
		
	$today_payment_room = $this->report_model->nightaudit_payment_method_restaurant();
$today_payment_month_room = $this->report_model->nightaudit_payment_method_restaurant_month();
$today_payment_year_room = $this->report_model->nightaudit_payment_method_restaurant_year();

$payment_data_resto = array();

// Initialize sums for today, month, and year
foreach ($today_payment_room as $row) {
    if (!isset($payment_data_resto[$row->payment_method])) {
        $payment_data_resto[$row->payment_method] = [
            'today' => 0,
            'month' => 0,
            'year' => 0
        ];
    }
    // Sum amounts for today
    $payment_data_resto[$row->payment_method]['today'] += $row->amount;
}

foreach ($today_payment_month_room as $row) {
    if (!isset($payment_data_resto[$row->payment_method])) {
        $payment_data_resto[$row->payment_method] = [
            'today' => 0,
            'month' => 0,
            'year' => 0
        ];
    }
    // Sum amounts for month
    $payment_data_resto[$row->payment_method]['month'] += $row->amount;
}

foreach ($today_payment_year_room as $row) {
    if (!isset($payment_data_resto[$row->payment_method])) {
        $payment_data_resto[$row->payment_method] = [
            'today' => 0,
            'month' => 0,
            'year' => 0
        ];
    }
    // Sum amounts for year
    $payment_data_resto[$row->payment_method]['year'] += $row->amount;
}

$data['payment_data_resto'] = $payment_data_resto;
//print_r($data['payment_data_resto']);die();
		
		//print_r($nightaudit_payment_method_room_year);die();
		$data['nightaudit_postbill']=$this->report_model->nightauditpostedbills();
//	echo "1<br/>";	print_r($data['nightaudit_postbill']);
		$data['nightaudit_month_postbill']=$this->report_model->nightaudit_current_month_postedbills();
		//	echo "2<br/>";	print_r(	$data['nightaudit_month_postbill']);
 	$data['nightaudit_year_postbill']=$this->report_model->nightaudit_current_year_postedbills();
 	//	echo "3<br/>";	print_r(	$data['nightaudit_year_postbill']);
 	//	die();
		$data['nightaudit_current_month1']  = $this->report_model->nightaudit_current_month();
$sums = [
    'paid_amount' => 0,
    'roomrate' => 0,
    'total_room' => 0,
    'children' => 0,
    'nuofpeople' => 0,
    'total_price' => 0,
    
    'discountamount' => 0,
    'cgst' => 0,
    'sgst' => 0,
    'bc' => 0,
    'pc' => 0,
    'cc' => 0
];

foreach ($data['nightaudit_current_month1'] as $item) {
    foreach ($sums as $key => $value) {
        $sums[$key] += $item[$key]; // Update to use array indexing
    }
}

// Pass the summed values to the view
$data['nightaudit_current_month'] = $sums;

$data['nightaudit_current_year'] = $this->report_model->nightaudit_current_year();

$sums = [
    'paid_amount' => 0,
    'roomrate' => 0,
    'total_room' => 0,
    'children' => 0,
    'nuofpeople' => 0,
   
    'total_price' => 0,
    'discountamount' => 0,
    'cgst' => 0,
    'sgst' => 0,
    'bc' => 0,
    'pc' => 0,
    'cc' => 0
];

foreach ($data['nightaudit_current_year'] as $item) {
    foreach ($sums as $key => $value) {
        $sums[$key] += $item[$key]; // Update to use array indexing
    }
}

// Pass the summed values to the view
$data['nightaudit_current_year'] = $sums;
		$data['nightaudit_restaurent']  = $this->report_model->nightaudit_restaurent();
	//	print_r($data['nightaudit_restaurent']);die();
		$data['nightaudit_restaurent_current_month']  = $this->report_model->nightaudit_restaurent_current_month();
	//		print_r($data['nightaudit_restaurent_current_month']);die();
		$data['nightaudit_restaurent_current_year']  = $this->report_model->nightaudit_restaurent_current_year();
		$data['occupancy_data_today'] = $this->report_model->getOccupancyData();
$data['occupancy_data_month'] = $this->report_model->getOccupancyData_current_month();
$data['occupancy_data_year'] = $this->report_model->getOccupancyData_current_year();

// Initialize an array to hold the processed data
$processed_data = array();

// Process today's data
foreach ($data['occupancy_data_today'] as $row) {
    $processed_data[$row->roomtype]['today'] = $row->total_occupied;
}

// Process monthly data
foreach ($data['occupancy_data_month'] as $row) {
    $processed_data[$row->roomtype]['month'] = $row->total_occupied;
}

// Process yearly data
foreach ($data['occupancy_data_year'] as $row) {
    $processed_data[$row->roomtype]['year'] = $row->total_occupied;
}

// Pass processed data to the view
$data['processed_data'] = $processed_data;
	// print_r($data['occupancy_data_month']);echo "<br/>";
	// print_r($data['occupancy_data_year']);echo "<br/>";

		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
		$payment_data = array();
        foreach ($today_data as $row) {
            $payment_data[$row->paymenttype]['today'] = $row->paymentamount;
        }
        foreach ($month_data as $row) {
            $payment_data[$row->paymenttype]['month'] = $row->paymentamount;
        }
        foreach ($year_data as $row) {
            $payment_data[$row->paymenttype]['year'] = $row->paymentamount;
        }

$queryResult = $resultData['query_result'];

    $paymentAmounts = $resultData['paymentAmounts'];

    $paymentTypes = $resultData['paymentTypes'];
//print_r($today_data);die();

		   $payment_hall = array();

    // // Merge data from today, this month, and this year
    // foreach ($payment_hall_today as $payment) {
    //     $payment_hall[$payment['paymentType']]['paymentType'] = $payment['paymentType'];
    //     $payment_hall[$payment['paymentType']]['today'] = $payment['paymentAmount'];
    // }

    // foreach ($payment_hall_month as $payment) {
    //     $payment_hall[$payment['paymentType']]['month'] = $payment['paymentAmount'];
    // }

    // foreach ($payment_hall_year as $payment) {
    //     $payment_hall[$payment['paymentType']]['year'] = $payment['paymentAmount'];
    // }

	$sumToday = $sumMonth = $sumYear = [];

// Iterate through the arrays and accumulate the amounts for each payment type
foreach ($payment_hall_today as $payment) {
    $paymentType = $payment['paymentType'];
    $paymentAmount = floatval($payment['paymentAmount']);

    // Sum the amount for each payment type for today
    $sumToday[$paymentType] = isset($sumToday[$paymentType]) ? $sumToday[$paymentType] + $paymentAmount : $paymentAmount;
}

foreach ($payment_hall_month as $payment) {
    $paymentType = $payment['paymentType'];
    $paymentAmount = floatval($payment['paymentAmount']);

    // Sum the amount for each payment type for this month
    $sumMonth[$paymentType] = isset($sumMonth[$paymentType]) ? $sumMonth[$paymentType] + $paymentAmount : $paymentAmount;
}

foreach ($payment_hall_year as $payment) {
    $paymentType = $payment['paymentType'];
    $paymentAmount = floatval($payment['paymentAmount']);

    // Sum the amount for each payment type for this year
    $sumYear[$paymentType] = isset($sumYear[$paymentType]) ? $sumYear[$paymentType] + $paymentAmount : $paymentAmount;
}
$data['sumToday']=$sumToday;
$data['sumMonth']=$sumMonth;
$data['sumYear']=$sumYear;

		//print_r($data['no_of_person_month']);die();
        $data['payment_data'] = $payment_data;
		$data['no_of_person']=$this->report_model->no_of_person();
		$data['no_of_person_month']=$this->report_model->no_of_person_month();
		$data['no_of_person_year']=$this->report_model->no_of_person_year();
		// print_r($data['no_of_person']);
		// echo "<br/>";
		// print_r($data['no_of_person_month']);
		// echo "<br/>";
		// print_r($data['no_of_person_year']);die();
	
$data['checkuser']=$checkuser;
		$data['total_rooms'] = $this->report_model->nightaudit_total_rooms();
		$data['today_status'] = $this->report_model->nightaudit_room_status();
$data['current_month_status'] = $this->report_model->nightaudit_current_month_room_status();
$data['current_year_status'] = $this->report_model->nightaudit_current_year_room_status();
        $data['module'] = "reports";
        $data['page']   = "nightaudit";   
        echo Modules::run('template/layout', $data); 
 }
    public function index(){
		$data['title'] = display('booking_report');
		$data['module'] = "reports";
		$data['customerlist']   = $this->report_model->customerlist(); 
		$data['page']   = "report_search"; 

		$this->load->library('pagination'); 
        #
        #pagination starts
        #
        $config["base_url"]       = base_url('reports/report/index/'); 
        $config["total_rows"]     = $this->db->count_all('booked_info'); 
        $config["per_page"]       = 15;
        $config["uri_segment"]    = 4; 
        $config["num_links"]      = 5;  
        /* This Application Must Be Used With BootStrap 4 * */
        $config['full_tag_open']='<ul class="pagination pagination-md">';
        $config['full_tag_close']='</ul>';
		$config['first_link'] = false;
		$config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item"><a class="page-link active">';
        $config['cur_tag_close'] = '</a></li>';
		$config['next_link'] = '<i class="ti-angle-right"></i>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
		$config['prev_link'] = '<i class="ti-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
		$config['last_link'] =false;
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["bookings"] = $this->report_model->read();
    //   print_r( $data["bookings"]);die();
        $data["links"] = $this->pagination->create_links(); 
        #
        #pagination ends
        #   
	    echo Modules::run('template/layout', $data); 
		}
	public function getinvoice(){
		$customer=$this->input->post('customer',TRUE);
		$status=$this->input->post('status',TRUE);
		$payment_status=$this->input->post('payment_status',TRUE);
		$startdates=$this->input->post('start_date',TRUE);
		$endate=$this->input->post('to_date',TRUE);
		$data['bookinfo']   = $this->report_model->getlist($customer,$status,$payment_status,$startdates,$endate);
	//	print_r($data['bookinfo']);die();
		$data['module'] = "reports";  
        $data['page']   = "getbookingreport";
		$this->load->view('reports/getbookingreport', $data);   
		}
	public function viewdetails($id){
		$details=$this->report_model->details($id);
		$data['bookinfo']   = $details;
	//	print_r($data['bookinfo'] );die();
		$data['customerinfo']   = $this->report_model->customerinfo($details->cutomerid);
		$data['paymentinfo']   = $this->report_model->paymentinfo($details->bookedid);
		$data['storeinfo']=$this->report_model->storeinfo();
		$data['taxinfo']=$this->report_model->taxinfo();
		$data['btaxinfo']=$this->report_model->btaxinfo($id);
		$data['setting'] = $this->report_model->settinginfo();
		$data['commominfo']=$this->report_model->commoninfo();
		$data['currency']=$this->report_model->currencysetting($data['storeinfo']->currency);
		$data['module'] = "reports";
	    $data['page']   = "bookindetails";   
	    echo Modules::run('template/layout', $data); 
		}
	public function customer_receit($id){
		$details=$this->report_model->details($id);
		$data['bookinfo']   = $details;
		$data['customerinfo']   = $this->report_model->customerinfo($details->cutomerid);
		$data['paymentinfo']   = $this->report_model->paymentinfo($details->bookedid);
		$data['commoninfo']=$this->report_model->commoninfo();
		$data['storeinfo']=$this->report_model->storeinfo();
		$data['module'] = "reports";
	    $data['page']   = "guest_invoice";   
	    echo Modules::run('template/layout', $data); 
		}
	public function productreport($id = null)
    {
		$this->permission->method('reports','read')->redirect();
        $data['title']    = display('purchase_report'); 
		$first_date = str_replace('/','-',$this->input->post('from_date',TRUE));
		$start_date= date('d-m-Y' , strtotime($first_date));
		$second_date = str_replace('/','-',$this->input->post('to_date',TRUE));
		$end_date= date('d-m-Y' , strtotime($second_date));
        $data['preport']  = $this->report_model->pruchasereport($start_date,$end_date);
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "prechasereport";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function purchasereport()
    {
	    $this->permission->method('reports','read')->redirect();
        $data['title']    = display('purchase_report'); 
		$first_date = str_replace('/','-',$this->input->post('from_date',TRUE));
		$start_date= date('d-m-Y' , strtotime($first_date));
		$second_date = str_replace('/','-',$this->input->post('to_date',TRUE));
		$end_date= date('d-m-Y' , strtotime($second_date));
        $data['preport']  = $this->report_model->pruchasereport($start_date,$end_date);
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "getpreport";  
		$this->load->view('reports/getpreport', $data);  
 
    }
	public function stockreport()
    {
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('stock_report'); 
        $data['stockreport']  = $this->report_model->getstocklist();
		$settinginfo=$this->report_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=$this->report_model->currencysetting($settinginfo->currency);
        $data['module'] = "reports";
        $data['page']   = "purchaseview";   
        echo Modules::run('template/layout', $data); 
 
    }
    
    
    
    	public function cosummary($start_date = null, $to_date = null)
    {

		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('co_summary'); 
        $data['csreport']  = $this->report_model->getcosummarylist($start_date, $to_date);
      //  print_r($data['csreport']);die();
        $data['userinfo']  = $this->report_model->userinfo();
 if($start_date){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
        
        $data['module'] = "reports";
        $data['page']   = "cosummary";   
        echo Modules::run('template/layout', $data); 

    }
    
    
    public function settlement_summary($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('settlement_summary'); 
		$data['settlementsum']  = $this->report_model->getsettlementsummaryinfo($start_date, $to_date);
		$data['settle_room']  = $this->report_model->getsettlementsummaryinfo_room($start_date, $to_date);
		$data['settle_hall']  = $this->report_model->getsettlementsummaryinfo_hall($start_date, $to_date);
	//	print_r($data['settle_hall']);die();
	 if($start_date){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
		$data['userinfo']  = $this->report_model->userinfo();
		$data['module'] = "reports";
        $data['page']   = "settlement_summary";   
        echo Modules::run('template/layout', $data); 
// 		$this->load->view('reports/settlement_summary', $data);  

    }



	public function b2b($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('settlement_summary'); 
        $data['getbusinessinfo']  = $this->report_model->getbusinessinfo($start_date, $to_date);
		$data['getbusinessinfo_food']=$this->report_model->getbusinessinfo_food($start_date, $to_date);
		$data['hallb2breport']  = $this->report_model->halldatab2blist($start_date, $to_date);
		// $data['getbusinessinfo_hall']=$this->report_model->getbusinessinfo_hall($start_date, $to_date);
		 if($start_date){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
        $data['module'] = "reports";
        $data['page']   = "b2b";   
        echo Modules::run('template/layout', $data); 
		// $this->load->view('reports/b2b', $data);  

    }



	public function b2clarge($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = ('Transaction Report'); 
        $data['t_report']  = $this->report_model->transaction_report($start_date = NULL, $to_date = NULL);
		
        $data['module'] = "reports";
        $data['page']   = "transaction_report";   
        echo Modules::run('template/layout', $data); 
// 		$this->load->view('reports/b2clarge', $data);  

    }

// 	public function b2clarge($start_date = null, $to_date = null)
//     {
// 		$start_date = $this->input->post('start_date', TRUE);
// 		$to_date = $this->input->post('to_date', TRUE);
// 		$this->permission->method('reports','read')->redirect();
// 		$data['title']    = display('b2clarge'); 
//         $data['getfoodinfo']  = $this->report_model->b2c_large_getfoodinfo($start_date = NULL, $to_date = NULL);
// 		$data['gettariffinfo']  = $this->report_model->b2c_large_gettariffinfo($start_date = NULL, $to_date = NULL);
//         $data['getbedinfo']  = $this->report_model->b2c_large_getbedinfo($start_date = NULL, $to_date = NULL);
//         $data['hallb2creport']  = $this->report_model->halldatab2clist($start_date = NULL, $to_date = NULL);
//         $data['module'] = "reports";
//         $data['page']   = "b2clarge";   
//         echo Modules::run('template/layout', $data); 
// // 		$this->load->view('reports/b2clarge', $data);  

//     }

public function hall($start_date = null, $to_date = null){
        $start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('hall_report'); 
        $data['userinfo']  = $this->report_model->userinfo();
        $data['hall_info']  = $this->report_model->hall_report($start_date, $to_date);
    //    print_r($data['hall_info']);die();
        $data['module'] = "reports";
        $data['page']   = "hall_report";   
        echo Modules::run('template/layout', $data); 

}


    public function hsnwise($start_date = null, $to_date = null)
    {


		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('hsnwise'); 
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
        // $data['csreport']  = $this->report_model->getcosummarylist($start_date, $to_date);
	  $data['getfoodinfo']  = $this->report_model->getfoodinfo_hsn($start_date, $to_date);
				$data['nightaudit1']  = $this->report_model->gettariffinfo_hsn($start_date, $to_date);
$sums = [
    'paid_amount' => 0,
    'roomrate' => 0,
    'total_room' => 0,
    'children' => 0,
    'additional_charge' => 0,
    'nuofpeople' => 0,
    'total_price' => 0,
    'discountamount' => 0,
    'cgst' => 0,
    'sgst' => 0,
    'bc' => 0,
    'pc' => 0,
    'cc' => 0
];

foreach ($data['nightaudit1'] as $item) {
    foreach ($sums as $key => $value) {
        $sums[$key] += $item[$key]; // Update to use array indexing
    }
}

// Pass the summed values to the view
$data['gettariffinfo'] = $sums;
//print_r($sums);die();
        $data['getbedinfo']  = $this->report_model->getbedinfo_hsn($start_date, $to_date);
         $data['getkidinfo']  = $this->report_model->getkidinfo($start_date, $to_date);
          $data['getpersoninfo']  = $this->report_model->getpersoninfo($start_date, $to_date);
       $data['hallb2csmallreport']  = $this->report_model->halldatab2csmalllist_hsn($start_date, $to_date);
 if($start_date){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
		
		$data['module'] = "reports";
        $data['page']   = "hsnwise";   
        echo Modules::run('template/layout', $data); 
// 		$this->load->view('reports/hsnwise', $data);  

    }














    public function b2csmall($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);

		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('b2csmall'); 
      $data['getfoodinfo']  = $this->report_model->getfoodinfo($start_date, $to_date);
	//	$data['gettariffinfo']  = $this->report_model->gettariffinfo($start_date, $to_date);
		
			$data['nightaudit1']  = $this->report_model->gettariffinfo($start_date, $to_date);
$sums = [
    'paid_amount' => 0,
    'roomrate' => 0,
    'total_room' => 0,
    'children' => 0,
   
    'nuofpeople' => 0,
    'total_price' => 0,
    'discountamount' => 0,
    'cgst' => 0,
    'sgst' => 0,
    'bc' => 0,
    'pc' => 0,
    'cc' => 0
];

foreach ($data['nightaudit1'] as $item) {
    foreach ($sums as $key => $value) {
        $sums[$key] += $item[$key]; // Update to use array indexing
    }
}

// Pass the summed values to the view
$data['gettariffinfo'] = $sums;

//print_r($data['gettariffinfo']);die();
        $data['getbedinfo']  = $this->report_model->getbedinfo($start_date, $to_date);
         $data['getkidinfo']  = $this->report_model->getkidinfo($start_date, $to_date);
          $data['getpersoninfo']  = $this->report_model->getpersoninfo($start_date, $to_date );
       $data['hallb2csmallreport']  = $this->report_model->halldatab2csmalllist($start_date, $to_date);
     //  print_r( $data['hallb2csmallreport']);die();
       if($start_date){
       $data['start_date'] = 	$start_date;
          $data['to_date'] = 	$to_date;
       }
//print_r($data['hallb2csmallreport']);die();
		$data['module'] = "reports";
        $data['page']   = "b2csmall";   
        echo Modules::run('template/layout', $data); 

    }








	public function advanceadjusted($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('advanceadjusted'); 
         $data['getadvanceinfo_hall__adjust']  = $this->report_model->getadvanceinfo_hall__adjust($start_date, $to_date);
           $data['getadvanceinfo_adjust']  = $this->report_model->getadvanceinfo_adjust($start_date, $to_date);
           if($start_date && 	$to_date ){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
      // print_r($data);die();
		$data['module'] = "reports";
        $data['page']   = "advanceadjusted";   
		echo Modules::run('template/layout', $data); 
 
    }


	public function nittax($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('nittax'); 
        $data['userinfo']  = $this->report_model->userinfo();
    $data['food_info']  = $this->report_model->food_info($start_date, $to_date);
        $data['module'] = "reports";
        $data['page']   = "nittax";   
        echo Modules::run('template/layout', $data); 
 
    }






















	public function advancereceived($start_date = null, $to_date = null)
    {
		$start_date = $this->input->post('start_date', TRUE);
		$to_date = $this->input->post('to_date', TRUE);
		$this->permission->method('reports','read')->redirect();
		$data['title']    = display('advancereceived'); 
        $data['getadvance']  = $this->report_model->getadvanceinfo($start_date, $to_date);
		$data['getadvanceinfo_hall']= $this->report_model->getadvanceinfo_hall($start_date, $to_date);
		 if($start_date && 	$to_date ){
       $data['start_date1'] = 	$start_date;
          $data['to_date1'] = 	$to_date;
       }
        $data['module'] = "reports";
        $data['page']   = "advancereceived";   
        echo Modules::run('template/layout', $data); 
// 		$this->load->view('reports/advancereceived', $data);  

    }





 
}

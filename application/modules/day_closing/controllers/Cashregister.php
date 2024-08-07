<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cashregister extends MX_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			 'reports/report_model',
			'cashregister_model'
		));

		
	}
	public function checkcashregister(){
		$saveid=$this->session->userdata('id'); 
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid',$saveid)->where('status',0)->order_by('id','DESC')->get()->row(); 
		$openamount = $this->db->select('closing_balance')->from('tbl_cashregister')->where('userid',$saveid)->where('closing_balance>','0.000')->order_by('id','DESC')->get()->row();
		
		$counterlist = $this->db->select('*')->from('tbl_cashcounter')->get()->result(); 
		$list[''] = 'Select Counter No';
		if (!empty($counterlist)) {
			foreach($counterlist as $value){
				$list[$value->ccid] = $value->counterno;
			}
		} 
		$data['allcounter']=$list;
		if(empty($checkuser)){
			if(!empty($openamount)){
				if($openamount->closing_balance>'0.000'){
					$data['openingbalance']=$openamount->closing_balance;
				}
				else{
					$data['openingbalance']="0.000";
				}
			}else{
				$data['openingbalance']="0.000";
			}
			$this->load->view('cashregister',$data); 
			}
		 else{echo 1; exit;}
		
	 }
 	public function addcashregister(){
	//	$this->form_validation->set_rules('counter',display('counter'),'required');
		$this->form_validation->set_rules('totalamount',display('amount'),'required');
		$saveid=$this->session->userdata('id'); 
	//	$counter=$this->input->post('counter',true);
		$openingamount=$this->input->post('totalamount',true);
		if ($this->form_validation->run() === true) {
			$postData = array(
					'userid' 	        => $saveid,
				//	'counter_no' 	    => $this->input->post('counter',true),
					'opening_balance' 	=> $this->input->post('totalamount',true),
					'closing_balance' 	=> '0.000',
					'openclosedate' 	=> date('d-m-Y'),
					'opendate' 	        => date('d-m-Y H:i:s'),
					'closedate' 	    => "1970-01-01 00:00:00",
					'status' 	        => 0,
					'openingnote' 	    => $this->input->post('OpeningNote',true),
					'closing_note' 	    => "",
				);
				if ($this->cashregister_model->addopeningcash($postData)) {
							echo 1;
						} else {
							echo 0;
						}
			}
			else{ echo 0;}
	 }
//  	public function cashregisterclose(){
// 		$saveid=$this->session->userdata('id'); 
// 		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid',$saveid)->where('status',0)->order_by('id','DESC')->get()->row();
// 		$data['userinfo'] = $this->db->select('*')->from('user')->where('id',$saveid)->get()->row(); 
// 		$data['registerinfo']=$checkuser;
// 		$data['totalamount']=$this->cashregister_model->collectcash($saveid,$checkuser->opendate);
// 		if(!empty($checkuser)){
// 			$this->load->view('cashregisterclose',$data); 
// 			}
// 		 else{echo 1; exit;}
		
// 	 }
public function cashregisterclose() {
    $saveid = $this->session->userdata('id');

    // Retrieve cash register information
    $checkuser = $this->db->select('*')
                          ->from('tbl_cashregister')
                          ->where('userid', $saveid)
                          ->where('status', 0)
                          ->order_by('id', 'DESC')
                          ->get()
                          ->row();
//echo $this->db->last_query();die();
    // Retrieve user information
    $data['userinfo'] = $this->db->select('*')
                                  ->from('user')
                                  ->where('id', $saveid)
                                  ->get()
                                  ->row();

    // Retrieve payment data from various sources
    $today_data = $this->report_model->nightaudit_payment_method_room();
    $payment_hall_today = $this->report_model->nightaudit_payment_method_hall();
    $today_payment_room = $this->report_model->nightaudit_payment_method_restaurant();
// print_r(  $today_payment_room );
// print_r(  $payment_hall_today );
    // Initialize arrays to hold payment data
    $payment_data = [];
    $payment_data_resto = [];

    // Process payment data from different sources
    foreach ($today_data as $row) {
        $payment_data[$row->paymenttype]['today'] = $row->paymentamount;
    }

    foreach ($today_payment_room as $row) {
        $payment_data_resto[$row->payment_method]['today'] = $row->amount;
    }

    // Calculate the total amount for each payment type
    $totals = [];

    // Sum amounts for each payment type from the 'today' array
    foreach ($payment_data as $paymentType => $data) {
        $totals[$paymentType] = isset($totals[$paymentType]) ? $totals[$paymentType] + $data['today'] : $data['today'];
    }

    // Sum amounts for each payment type from the 'today' array of restaurant payments
    foreach ($payment_data_resto as $paymentType => $data) {
        $totals[$paymentType] = isset($totals[$paymentType]) ? $totals[$paymentType] + $data['today'] : $data['today'];
    }

    // Sum amounts for each payment type from the 'sumToday' array
    foreach ($payment_hall_today as $payment) {
        $paymentType = $payment['paymentType'];
        $paymentAmount = floatval($payment['paymentAmount']);
        $totals[$paymentType] = isset($totals[$paymentType]) ? $totals[$paymentType] + $paymentAmount : $paymentAmount;
    }

    // Output the total amount for each payment type
    // foreach ($totals as $paymentType => $totalAmount) {
    //     echo "$paymentType: $totalAmount\n";
    // }
$data['totals'] = $totals;
	$data['registerinfo']=$checkuser;
//print_r($data);
  
        // Load the view with the data
        $this->load->view('cashregisterclose', $data);
   
}

	public function closecashregister(){
		$this->form_validation->set_rules('totalamount',display('amount'),'required');
		$saveid=$this->session->userdata('id'); 
		$counter=$this->input->post('counter');
		$openingamount=$this->input->post('totalamount');
		$cashclose=$this->input->post('registerid');
			//print_r($data['id']);die();
			  $checkuser = $this->db->select('*')
                          ->from('tbl_cashregister')
                          ->where('userid', $saveid)
                          ->where('status', 0)
                          ->order_by('id', 'DESC')
                          ->get()
                          ->row();
	$payment_hall_today =$this->report_model->nightaudit_payment_method_hall();
$today_payment_room=$this->report_model->nightaudit_payment_method_restaurant();
$payment_data_resto = array();
  foreach ($today_payment_room as $row) {
            $payment_data_resto[$row->payment_method]['today'] = $row->bill_amount;
        }
		$hall_tday=[];
// Iterate through the arrays and accumulate the amounts for each payment type
foreach ($payment_hall_today as $payment) {
    $paymentType = $payment['paymentType'];
    $paymentAmount = floatval($payment['paymentAmount']);

    // Sum the amount for each payment type for today
    $hall_tday[$paymentType]['today'] = isset($hall_tday[$paymentType]) ? $hall_tday[$paymentType] + $paymentAmount : $paymentAmount;
}
$today_data=$this->report_model->nightaudit_payment_method_room();
$payment_data = array();
        foreach ($today_data as $row) {
            $payment_data[$row->paymenttype]['today'] = $row->paymentamount;
        }
      $merged_payment_data = [];

// Merge and sum the payment data for ROOMS, RESTAURANT, and BANQUET HALL
foreach ([$payment_data, $payment_data_resto, $hall_tday] as $payment_array) {
    foreach ($payment_array as $paymenttype => $data) {
        // If the payment type already exists in the merged array, add the amounts
        if (isset($merged_payment_data[$paymenttype])) {
            $merged_payment_data[$paymenttype]['today'] += isset($data['today']) ? $data['today'] : 0;
           
        } else { // Otherwise, initialize the payment type in the merged array
            $merged_payment_data[$paymenttype] = [
                'today' => isset($data['today']) ? $data['today'] : 0,
               
            ];
        }
    }
}
$data['registerinfo']=$checkuser;
$data['id']=$data['registerinfo']->id;
// Pass the merged and summed payment data to the view
$data['merged_payment_data'] = $merged_payment_data;
//print_r($data['merged_payment_data']);
 foreach ($data['merged_payment_data'] as $k=>$v) { 
							// $total=$total+$amount->totalamount;
					$details = array(
							      'close_id'      => $cashclose,
                              'paytype' => $k,
							   'closedate' 	    => date('d-m-Y H:i:s'),
							   'closing_balance' 	=> $this->input->post('totalamount',true),
							   'amount' => $v['today'],
                         
						);
						$this->db->insert('tbl_cashcounter',$details);
				//	echo $this->db->last_query();
						  }  
						//  die();
		if ($this->form_validation->run() === true) {
		$postData = array(
					'id' 				=> $cashclose,
					'closing_balance' 	=> $this->input->post('totalamount',true),
					'closedate' 	    => date('d-m-Y H:i:s'),
					'openclosedate'     => date('d-m-Y'),
					'status' 	        => 1,
					'closing_note' 	    => $this->input->post('closingnote',true),
				);
				//print_r($postData);die();
				if ($this->cashregister_model->closeresister($postData)) {
						$this->session->set_flashdata('message',"Day Successfully closed");
							echo 1;
						} else {
							echo 0;
						}
			}
	 } 
	public function counterlist(){
		$data['title'] = display('counter_list');
		$data['module'] 	= "day_closing";  
		$data['counterlist'] = $this->db->select('*')->from('tbl_cashcounter')->get()->result(); 
		$data['page']   = "cashcounter";  
		echo Modules::run('template/layout', $data); 
	}
	public function createcounter(){
		$data['title'] = display('counter_list');
		$this->form_validation->set_rules('counter',display('counter'),'required|is_unique[tbl_cashcounter.counterno]');
		$postData = array(
			'counterno' 	        => $this->input->post('counter',true),
		);
		
			if ($this->form_validation->run() === true) {
					if ($this->cashregister_model->createcounter($postData)) {
						#set success message
						$this->session->set_flashdata('message',display('save_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception',display('please_try_again'));
					}
	 
				redirect('day_closing/cashregister/counterlist');
	
			} else { 
				$data['title'] = display('counter_list');
				$data['module'] 	= "day_closing";  
				$data['counterlist'] = $this->db->select('*')->from('tbl_cashcounter')->get()->result(); 
				$data['page']   = "cashcounter";  
				echo Modules::run('template/layout', $data); 
			}
	}
	public function editcounter($id){
		$data['title'] = display('counter_list');
		$this->form_validation->set_rules('counter',display('counter'),'required');
		$oldCounter = $this->db->select("counterno")->from("tbl_cashcounter")->where("ccid",$id)->get()->row();
		if($oldCounter->counterno!=$this->input->post('counter',true)){
			$this->form_validation->set_rules('counter',display('counter'),'required|is_unique[tbl_cashcounter.counterno]');
		}
		$postData = array(
			'ccid' 	        		=> $id,
			'counterno' 	        => $this->input->post('counter',true),
		);
			if ($this->form_validation->run() === true) {
					if ($this->cashregister_model->updatecounter($postData)) {
						#set success message
						$this->session->set_flashdata('message',display('update_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception',display('please_try_again'));
					}
	 
				redirect('day_closing/cashregister/counterlist');
	
			} else { 
				$data['title'] = display('counter_list');
				$data['module'] 	= "day_closing";  
				$data['counterlist'] = $this->db->select('*')->from('tbl_cashcounter')->get()->result(); 
				$data['page']   = "cashcounter";  
				echo Modules::run('template/layout', $data); 
			}
	}
public function cashregisterlist(){
		$this->permission->method('report','read')->redirect();
		$data['title']    = display("day_closing")." ".display("report");
		$settinginfo=$this->cashregister_model->settinginfo();
		$data['setting']=$settinginfo;
		$data['currency']=getCurrency();
		$counterlist = $this->db->select('*')->from('tbl_cashcounter')->get()->result(); 
		$userlist = $this->db->select('tbl_cashregister.*,user.firstname,user.lastname')->from('tbl_cashregister')->join('user','user.id=tbl_cashregister.userid','left')->get()->result(); 
		 $data['list']=$userlist;
	//	print_r($data);die();
		$data['module'] = "day_closing";
		$data['page']   = "cashregister_report";   
		echo Modules::run('template/layout', $data); 
		}
	public function getcashregister(){
		$data['cashreport']=$this->cashregister_model->cashregister();
		$this->load->view('day_closing/cash_report', $data);
		}
	public function getcashregisterorder(){
		$opendate= $this->input->post('opendate');
		$openbal= $this->input->post('openbal');
		$uid= $this->input->post('uid');
		$close_amt= $this->input->post('close_amt');
		$data['billeport']=$this->cashregister_model->cashregisterbill($opendate,$openbal,$uid,$close_amt);
		$this->load->view('day_closing/details', $data);
		}
}
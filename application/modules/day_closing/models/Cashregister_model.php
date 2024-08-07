<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Cashregister_model extends CI_Model{ 
        
  
    public function __construct()
    {
        parent::__construct();
    }
    public function addopeningcash($data = array())
    {	 
      return $this->db->insert('tbl_cashregister',$data);
    } 
    public function closeresister($data = array()){
        return $this->db->where('id', $data["id"])->update('tbl_cashregister', $data);

       //  echo $this->db->last_query();
    }
    public function collectcash($id,$tdate){
      $crdate=date('d-m-Y H:i:s');
      $where="acc_transaction.CreateDate Between '$tdate' AND '$crdate'";
      $this->db->select('acc_transaction.COAID,SUM(acc_transaction.Debit) as recieved,SUM(acc_transaction.Credit) as expense,SUM(acc_transaction.Debit-acc_transaction.Credit) as totalamount,acc_coa.HeadName');
          $this->db->from('acc_transaction');
      $this->db->join('acc_coa','acc_coa.HeadCode=acc_transaction.COAID','left');
      $this->db->where('acc_transaction.CreateBy',$id);
      $this->db->where($where);
      $this->db->where('acc_transaction.IsAppove',1);
      $this->db->like('acc_transaction.COAID','10201');
      $this->db->group_by('acc_transaction.COAID');
      $query = $this->db->get();
      return $orderdetails=$query->result();
      }
       public function collectcash_cash($id, $tdate){
    $crdate = date('d-m-Y H:i:s');
    $crdate1 = date('d-m-Y 00:00:00');

    $this->db->select('acc_transaction.COAID, 
                       SUM(acc_transaction.Debit) as received, 
                       SUM(acc_transaction.Credit) as expense, 
                       SUM(acc_transaction.Debit - acc_transaction.Credit) as totalamount');
    $this->db->from('acc_transaction');

    $this->db->where('acc_transaction.CreateBy', $id);
    $this->db->where('DATE(acc_transaction.CreateDate) >=', $crdate1);
    $this->db->where('DATE(acc_transaction.CreateDate) <=', $crdate);
    $this->db->where('acc_transaction.IsAppove', 1);
    $this->db->like('acc_transaction.COAID', '1020101');
    $this->db->group_by('acc_transaction.COAID');
    $query = $this->db->get();

    $result = $query->result(); // Use $query->result() to get the result
    return $result;
}

public function collectcash_online($id, $tdate){
    $crdate = date('d-m-Y H:i:s');
    $crdate1 = date('d-m-Y 00:00:00');

    $this->db->select('acc_transaction.COAID, 
                       SUM(acc_transaction.Debit) as received, 
                       SUM(acc_transaction.Credit) as expense, 
                       SUM(acc_transaction.Debit - acc_transaction.Credit) as totalamount');
    $this->db->from('acc_transaction');

    $this->db->where('acc_transaction.CreateBy', $id);
    $this->db->where('DATE(acc_transaction.CreateDate) >=', $crdate1);
    $this->db->where('DATE(acc_transaction.CreateDate) <=', $crdate);
    $this->db->where('acc_transaction.IsAppove', 1);
    $this->db->like('acc_transaction.COAID', '10203010');
    $this->db->group_by('acc_transaction.COAID');
    $query = $this->db->get();

    $result = $query->result(); // Use $query->result() to get the result
    return $result;
}

public function collectcash_upi($id, $tdate){
    $crdate = date('d-m-Y H:i:s');
    $crdate1 = date('d-m-Y 00:00:00');

    $this->db->select('acc_transaction.COAID, 
                       SUM(acc_transaction.Debit) as received, 
                       SUM(acc_transaction.Credit) as expense, 
                       SUM(acc_transaction.Debit - acc_transaction.Credit) as totalamount');
    $this->db->from('acc_transaction');

    $this->db->where('acc_transaction.CreateBy', $id);
    $this->db->where('DATE(acc_transaction.CreateDate) >=', $crdate1);
    $this->db->where('DATE(acc_transaction.CreateDate) <=', $crdate);
    $this->db->where('acc_transaction.IsAppove', 1);
    $this->db->like('acc_transaction.COAID', '102030101');
    $this->db->group_by('acc_transaction.COAID');
    $query = $this->db->get();

    $result = $query->result(); // Use $query->result() to get the result
    return $result;
}

    public function createcounter($data = array())
    {	 
      return $this->db->insert('tbl_cashcounter',$data);
    }
    public function updatecounter($data = array()){
      return $this->db->where('ccid',$data["ccid"])->update('tbl_cashcounter', $data);
     }
    public function cashregister(){
			$start_date= $this->input->post('from_date');
			$end_date= $this->input->post('to_date');
		
			$dateRange = "tbl_cashregister.openclosedate BETWEEN '$start_date' AND '$end_date'";
			
			$this->db->select("tbl_cashregister.*,user.firstname,user.lastname");
			$this->db->from('tbl_cashregister');
			$this->db->join('user','user.id=tbl_cashregister.userid','left');
			if($start_date!=''){
			$this->db->where($dateRange);
			}
		
			$this->db->where('tbl_cashregister.status',1);
			$query = $this->db->get();
	//	echo $this->db->last_query();die();
			return $query->result();
		}
	public function cashregisterbill($opendate,$openbal,$uid,$amt){
   	$this->db->select("cc.paytype as pt,cc.amount as amt");
        $this->db->from('tbl_cashregister AS cr');
        $this->db->join('tbl_cashcounter AS cc', '(cr.id) = (cc.close_id)');
         $this->db->where('(cr.openclosedate)', $opendate);
            $this->db->where('(cr.opening_balance)', $openbal);
               $this->db->where('(cr.userid)', $uid);
                  $this->db->where('(cr.closing_balance)', $amt);
     	$query = $this->db->get();
			$result = $query->result();
      return $result;
		}
    public function settinginfo()
    { 
      return $this->db->select("*")->from('setting')
        ->get()
        ->row();
    }
}
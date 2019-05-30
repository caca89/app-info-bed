<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_model extends X_Model {

    public $table = 'trans';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;
        $this->timestamps = TRUE;
    }

    function get_loan($nip) {
    	$this->db->select('trans.id as loan_id, trans.number, trans.date_add, trans.status,  
    		members.name, members.id as member_id, 
    		loan_detail.due_date, loan_detail.insurance, loan_detail.tenor, loan_detail.basic, loan_detail.profit_value, loan_detail.payment, 
    		trans_detail.amount, trans_detail.chart_id, 
    		(SELECT COUNT(*) FROM trans t WHERE t.parent=loan_id) as orders');
    	$this->db->from('trans');
    	$this->db->join('trans_detail', 'trans_detail.trans_id=trans.id','INNER');
    	$this->db->join('loan_detail','loan_detail.trans_id=trans.id', 'INNER');
    	$this->db->join('members', 'members.id=trans.member_id', 'INNER');
    	$this->db->where('trans.status', NULL);
    	$this->db->where('trans.type', 'pinjaman');
    	$this->db->where('members.number', $nip);
    	$query = $this->db->get();
    	return ($query->num_rows() > 0) ? $query->row() : false;
    }
    function get_report_deposit($trans_id) {
        $this->db->select('trans.id, trans.number, trans.created_at, members.name, trans_detail.amount, options.name as chart_name');
        $this->db->from('trans');
        $this->db->join('trans_detail', 'trans_detail.trans_id=trans.id','INNER');
        $this->db->join('members', 'members.id=trans.member_id', 'INNER');
        $this->db->join('options', 'trans_detail.chart_id=options.chart_id', 'INNER');
        $this->db->where('trans.id', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : false;

    }
    function get_report_withdraw($trans_id) {
        $this->db->select('trans.id, trans.number, trans.created_at, trans_detail.amount');
        $this->db->from('trans');
        $this->db->join('trans_detail', 'trans_detail.trans_id=trans.id','INNER');
        $this->db->where('trans.id', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;

    }
    function get_report_loan($trans_id) {
        $this->db->select('trans.id, trans.number, trans.date_add, 
            members.name as member_name, members.number as member_number, members.address as member_address, 
            loan_detail.tenor, loan_detail.profit_value, loan_detail.basic, loan_detail.payment, loan_detail.due_date, 
            trans_detail.amount');
        $this->db->from('trans');
        $this->db->join('trans_detail', 'trans_detail.trans_id=trans.id','INNER');
        $this->db->join('loan_detail','loan_detail.trans_id=trans.id', 'INNER');
        $this->db->join('members', 'members.id=trans.member_id', 'INNER');
        $this->db->where('trans.id', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }
    function get_report_payment($trans_id) {
        $this->db->select('trans.id as loan_id, trans.number, trans.date_add, trans.parent as parents, 
            members.name as member_name, 
            (SELECT COUNT(*) FROM trans t WHERE t.parent=parents) as orders, 
            (SELECT SUM(amount) FROM trans_detail d WHERE d.trans_id=loan_id) as amounts, 
            (SELECT number FROM trans WHERE id=parents) as loan_number');
        $this->db->from('trans');
        $this->db->join('members', 'members.id=trans.member_id', 'INNER');
        $this->db->where('trans.id', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    function get_loan_detail($trans_id) {
        $this->db->select('trans.number, trans.date_add, trans.status, members.number as member_number, members.name, loan_detail.due_date, loan_detail.insurance, loan_detail.tenor, loan_detail.basic, 
            loan_detail.profit_value, loan_detail.payment, trans_detail.amount');
        $this->db->from('trans');
        $this->db->join('trans_detail', 'trans_detail.trans_id=trans.id','INNER');
        $this->db->join('loan_detail','loan_detail.trans_id=trans.id', 'INNER');
        $this->db->join('members', 'members.id=trans.member_id', 'INNER');
        $this->db->where('trans.id', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row() : false;

    }

    function get_trans_detail($trans_id) {
        $this->db->select('trans.id, trans.created_at, (SELECT SUM(amount) FROM trans_detail WHERE trans_id=trans.id) as total_payment');
        $this->db->from('trans');
        $this->db->where('trans.parent', $trans_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

}

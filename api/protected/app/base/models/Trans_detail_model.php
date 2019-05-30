<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_detail_model extends X_Model {

    public $table = 'trans_detail';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;
        $this->timestamps = TRUE;
    }

    public function sum_amount($type, $chart_id = null, $periode = null) {
    	$this->db->select('SUM(amount) as amount');
		$this->db->from('trans_detail');
        $this->db->where('type', $type);
    	if($chart_id) {
            $this->db->where('chart_id', $chart_id);
        }
        if($periode) {
    		$this->db->where('DATE_FORMAT(date_add, "%Y-%m")=', $periode);
    	}
    	$query = $this->db->get();
    	return ($query) ? $query->row() : 0;
    }
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chart_model extends X_Model {

    public $table = 'charts';
    public $primary_key = 'id';

    public function sum_amount($type) {
    	$this->db->select('SUM(amount) as amount');
		$this->db->from('charts');
        $this->db->where('type', $type);
    	$query = $this->db->get();
    	return ($query->num_rows() > 0) ? $query->row()->amount : 0;
    }

    public function get_chart($id_number) {
    	$query = $this->db->select()
    					->from('charts')
    					->where('number', $id_number)
    					->or_where('id', $id_number)
    					->get();
    	return ($query->num_rows() > 0) ? $query->row() : false;
    }
}

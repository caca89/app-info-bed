<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends X_Model {

    public $table = '';
    public $primary_key = '';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;

        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function get_data($param) {
        $search  = $param['search'];
        $filters = $param['columns'];
        $start 	 = $param['start'];
        $end 	 = $param['length'];

        $sql = "SELECT a1.id, a1.name, a1.username, a1.status 
                FROM  (SELECT row_number() OVER (ORDER BY id ASC) AS ROWINDEX, *  FROM bed_users WHERE group_id > 0) a1
                WHERE ROWINDEX BETWEEN ".$start." AND ".$end." ";

        if(strlen($search['value']) > 0) {
        	$sql .= 'AND (';
            foreach ($filters as $filter) {
                if($filter['name'] != 'id') {
                    if($filter['name'] != 'actions') {
                        $sql .= "a1.".$filter['name'] . " LIKE '%" . $search['value'] . "%' OR ";
                    }
                }
            }
        	$sql = substr_replace($sql, '', -3) . ") ";
        }
        $sql .= "ORDER BY ROWINDEX";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result_array() : false;
    }
    public function get_total($param) {
        $search  = $param['search'];
        $filters = $param['columns'];

        $sql = "SELECT COUNT(id) as total FROM  bed_users ";

        if(strlen($search['value']) > 0) {
        	$sql .= 'WHERE ';
            foreach ($filters as $filter) {
                if($filter['name'] != 'id') {
                    if($filter['name'] != 'actions') {
                        $sql .= "a1.".$filter['name'] . " LIKE '%" . $search['value'] . "%' OR ";
                    }
                }
            }
        	$sql = substr_replace($sql, '', -3);
        }

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row()->total : 0;
    }
	public function get_user($id) {
		$sql = "SELECT id, name, username, status FROM bed_users WHERE id=".$id;

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row() : 0;
	}    
	public function save_user($data) {
		$sql = "INSERT INTO bed_users (group_id, name, username, password, created_at, created_by) 
						VALUES(
						'".$data["group_id"]."',
						'".$data["name"]."',
						'".$data["username"]."',
						'".$data["password"]."',
						'".$data["created_at"]."',
						'".$data["created_by"]."')";

        $query = $this->db2->query($sql);
        return $query ? true : false;
	}
	public function update_user($data, $id) {
		$sql = "UPDATE bed_users SET 
						name = '".$data["name"]."',
						username = '".$data["username"]."',
						updated_at = '".$data["updated_at"]."',
						updated_by = '".$data['updated_by']."' 
						WHERE id=".$id;

        $query = $this->db2->query($sql);
        return $query ? true : false;
	}    
}

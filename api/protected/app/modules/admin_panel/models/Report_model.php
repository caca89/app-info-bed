<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends X_Model {

    public $table = '';
    public $primary_key = '';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;

        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function get_data($param) {
        $month   = isset($param['month']) ? $param['month'] : "0";
        $year    = isset($param['year']) ? $param['year'] : "0";
        $date    = $year."-".$month;
        $search  = $param['search'];
        $filters = $param['columns'];
        $start 	 = $param['start'];
        $end 	 = $param['length'];

        $time_adm_ri = ", (SELECT CONVERT(VARCHAR(8), a2.time_end, 108) as time_end FROM bed_log_details as a2 WHERE a2.no_registrasi=a1.no_registrasi AND a2.process=1) as time_adm_ri";
        $time_depo = ", (SELECT CONVERT(VARCHAR(8), a3.time_end, 108) as time_end FROM bed_log_details as a3 WHERE a3.no_registrasi=a1.no_registrasi AND a3.process=2) as time_depo";
        $time_kasir_ri = ", (SELECT CONVERT(VARCHAR(8), a4.time_end, 108) as time_end FROM bed_log_details as a4 WHERE a4.no_registrasi=a1.no_registrasi AND a4.process=3) as time_kasir_ri";
        $time_patient_out = ", (SELECT CONVERT(VARCHAR(8), a5.time_end, 108) as time_end FROM bed_log_details as a5 WHERE a5.no_registrasi=a1.no_registrasi AND a5.process=4) as time_patient_out";
        // $sql = "SELECT a1.no_bed".$time_adm_ri.$time_depo.$time_kasir_ri.$time_patient_out."
        //         FROM  bed_logs as a1
        //         WHERE a1.dates='".$date_current."' AND a1.status=1 
        //         ORDER BY a1.created_at DESC";
        $sql = "SELECT a10.dates, a10.no_bed, a10.status, a10.time_adm_ri, a10.time_depo, a10.time_kasir_ri, a10.time_patient_out  
                FROM  (
                    SELECT row_number() OVER (ORDER BY a1.dates ASC) AS ROWINDEX, a1.dates, a1.no_bed, a1.status".$time_adm_ri.$time_depo.$time_kasir_ri.$time_patient_out." 
                    FROM bed_logs a1 
                    WHERE CONVERT(nvarchar(7), a1.dates, 120)='".$date."'
                    ) a10 
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

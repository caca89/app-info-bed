<?php defined('BASEPATH') or exit('No direct script access allowed!');

class Report extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->data     = json_decode(file_get_contents('php://input'), TRUE);
        $this->header   = $this->input->request_headers(TRUE);
        $this->db2      = $this->load->database('db_rsannisa', TRUE);
        $this->load->model('rsannisa_model');
    }

    public function index() 
    {
        $data  = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Bad Request.'], 200);
        } else {
            $date_start = isset($data['date_start']) ? date('Y-m-d', strtotime($data['date_start'])) : 0;
            $date_end = isset($data['date_end']) ? date('Y-m-d', strtotime($data['date_end'])) : 0;

            $results = $this->get_report($date_start, $date_end);
            if($results) {
                $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$results], 200);
            } else {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Data tidak ditemukan.'], 200);
            }
        }
    }
    private function get_report($date_start, $date_end) {
        $output = array();
        $patient_out_by_date = $this->rsannisa_model->get_patient_out_by_date($date_start, $date_end);
        if($patient_out_by_date) {
            $no_registrasi = "";
            foreach($patient_out_by_date as $row) {
                if($no_registrasi!=$row->no_registrasi) {
                    $no_registrasi = $row->no_registrasi;

                    array_push($output, $row);
                }
            }
        }

        return $output;
    }

}

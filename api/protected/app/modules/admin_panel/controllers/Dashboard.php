<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends App_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data     = json_decode(file_get_contents('php://input'), TRUE);
        $this->header   = $this->input->request_headers(TRUE);
        $this->load->model('rsannisa_model');
    }

    public function index() {
        $average_time      = array();
        $patient_out_daily = array();

        $average_time = array(
            "period"        => get_month5(date('m')).' '.date('Y'),
            "depo"          => 0,
            "adm_ri"        => (int) $this->rsannisa_model->get_average_time_month(2),
            "kasir_ri"      => (int) $this->rsannisa_model->get_average_time_month(3),
            "pasien_pulang" => 0
        );
        
        $output = array(
            'average_time'      => $average_time,
            'patient_out_daily' => $this->set_patient_out_daily()
        );
        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
    }

    private function set_patient_out_daily() {
        $output = array();
        $patient_out_daily = $this->rsannisa_model->get_patient_out_daily();
        if($patient_out_daily) {
            $no_registrasi = "";
            foreach($patient_out_daily as $row) {
                if($no_registrasi!=$row->no_registrasi) {
                    $no_registrasi = $row->no_registrasi;

                    array_push($output, $row);
                }
            }
        }

        return $output;
    }
}

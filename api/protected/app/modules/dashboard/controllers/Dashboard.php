<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends App_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data     = json_decode(file_get_contents('php://input'), TRUE);
        $this->header   = $this->input->request_headers(TRUE);
        $this->load->model('rsannisa_model');
        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function general() {
        $output  = array();
        $ruangan = "";
        $kelas   = "";
        $a = 0;
        $b = 0;
        $c = 0;
        $beds   = $this->rsannisa_model->bed_dashboard_general();
        if($beds) {
            foreach($beds as $key => $row) {
                $row->status_bed = intval($row->status_bed) > 2 ? intval($row->status_bed) : 0;
                if($row->status=='ISI') {
                    $row->status_bed = $row->status_bed < 3 ? 2 : $row->status_bed;
                } else {
                    if(!is_null($row->booking)) {
                        $row->status_bed = $row->status_bed < 3 ? 1 : $row->status_bed;
                    }
                }

                if($key==0) {
                    $ruangan = $row->no_kamar;
                    $kelas   = $row->nama_klas;

                    if(trim($row->no_kamar)!='') {
                        if(!is_numeric($row->no_kamar)) {
                            $output[$a]['nama_ruangan']                         = $row->no_kamar;
                            $output[$a]['kelas'][$b]['nama_kelas']              = $row->nama_klas;
                            $output[$a]['kelas'][$b]['bed'][$c]['no_bed']       = $row->no_bed;
                            $output[$a]['kelas'][$b]['bed'][$c]['status']       = $row->status;
                            $output[$a]['kelas'][$b]['bed'][$c]['status_bed']   = intval($row->status_bed);
                        }
                    }
                } else {
                    if(!is_numeric($row->no_kamar)) {
                        if($ruangan!=trim($row->no_kamar)) {
                            $ruangan = trim($row->no_kamar);
                            $kelas   = $row->nama_klas;

                            $a++;
                            $b = 0;
                            $c = 0;

                            $output[$a]['nama_ruangan']                         = $row->no_kamar;
                            $output[$a]['kelas'][$b]['nama_kelas']              = $row->nama_klas;
                            $output[$a]['kelas'][$b]['bed'][$c]['kode_ruangan'] = $row->kode_ruangan;
                            $output[$a]['kelas'][$b]['bed'][$c]['no_bed']       = $row->no_bed;
                            $output[$a]['kelas'][$b]['bed'][$c]['status']       = $row->status;
                            $output[$a]['kelas'][$b]['bed'][$c]['status_bed']   = intval($row->status_bed);

                        } else {
                            if($kelas!=$row->nama_klas) {
                                $kelas = $row->nama_klas;
                                
                                $b++;
                                $c = 0;
                                
                                $output[$a]['kelas'][$b]['nama_kelas']              = $row->nama_klas;
                                $output[$a]['kelas'][$b]['bed'][$c]['kode_ruangan'] = $row->kode_ruangan;
                                $output[$a]['kelas'][$b]['bed'][$c]['no_bed']       = $row->no_bed;
                                $output[$a]['kelas'][$b]['bed'][$c]['status']       = $row->status;
                                $output[$a]['kelas'][$b]['bed'][$c]['status_bed']   = intval($row->status_bed);

                            } else {
                                $c++;

                                $output[$a]['kelas'][$b]['bed'][$c]['kode_ruangan'] = $row->kode_ruangan;
                                $output[$a]['kelas'][$b]['bed'][$c]['no_bed']       = $row->no_bed;
                                $output[$a]['kelas'][$b]['bed'][$c]['status']       = $row->status;
                                $output[$a]['kelas'][$b]['bed'][$c]['status_bed']   = intval($row->status_bed);
                            }

                        }
                    }
                }
            }
        }

        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
    }
    public function reload_dashboard_general() {
        $this->general();
    }
    public function nurse() {
        $list_beds    = array();
        $list_classes = array();
        $list_rooms   = $this->rsannisa_model->get_list_rooms();

        if($list_rooms) {
            $room = $list_rooms[0]->no_kamar;

            $list_classes = $this->rsannisa_model->get_list_classes($room);

            if($list_classes) {
                $classes   = $list_classes[0]->nama_klas;
                $all_beds  = $this->rsannisa_model->get_bed_by_room($room, $classes);
                $list_beds = $this->get_list_beds($all_beds);
            }
        }

        $output = array(
            'room_name'     =>$room,
            'list_rooms'    => $list_rooms,
            'list_classes'  => $list_classes,
            'list_beds'     => $list_beds,
        );

        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
    }
    public function select_classes() {
        $data = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Parameter tidak ditemukan.'], 200);
        } else {
            $room      = isset($data['room']) ? $data['room'] : 0;
            $classes   = isset($data['classes']) ? $data['classes'] : 0;
            $all_beds  = $this->rsannisa_model->get_bed_by_room($room, $classes);
            $list_beds = $this->get_list_beds($all_beds);

            $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$list_beds], 200);
        }
    }
    public function select_room() {
        $data = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Parameter tidak ditemukan.'], 200);
        } else {
            $list_beds      = array();
            $room           = isset($data['room']) ? $data['room'] : 0;
            $list_classes   = $this->rsannisa_model->get_list_classes($room);

            if($list_classes) {
                $classes  = $list_classes[0]->nama_klas;
                $all_beds = $this->rsannisa_model->get_bed_by_room($room, $classes);
                $list_beds = $this->get_list_beds($all_beds);
            }

            $output = array(
                'room_name'     =>$room,
                'list_classes'  => $list_classes,
                'list_beds'     => $list_beds,
            );
            $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
        }
    }
    public function reload_dashboard_nurse() {
        $data       = $this->data;
        $room       = isset($data['room']) ? $data['room'] : "";
        $classes    = isset($data['classes']) ? $data['classes'] : "";
        $list_beds  = array();
        $all_beds   = $this->rsannisa_model->get_bed_by_room($room, $classes);
        $list_beds = $this->get_list_beds($all_beds);

        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$list_beds], 200);
    }
    private function get_list_beds($data) {
        $output = array();

        if($data) {
            foreach($data as $row) {
                $info_booking = array();
                $info_pasien  = array();

                $status     = $row->status;
                $status_bed = intval($row->status_bed);

                if($status=='ISI') {
                    $status_bed = $status_bed < 3 ? 2 : $status_bed;
                    $info_pasien = array(
                        'no_mr'         => $row->no_mr,
                        'nama_pasien'   => $row->nama_pasien,
                        'diagnosa_awal' => $row->diagnosa_awal,
                    );
                    // $info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
                } else {
                    if($row->booking) {
                        $status_bed = $status_bed < 3 ? 1 : $status_bed;
                        //$info_booking = $this->rsannisa_model->get_patient_booking($booking);
                    } else {
                        $status_bed = $status_bed < 3 ? 0 : $status_bed;
                        if($status_bed==4 OR $status_bed==3) {
                            $info_pasien = array(
                                'no_mr'         => $row->no_mr,
                                'nama_pasien'   => $row->nama_pasien,
                                'diagnosa_awal' => $row->diagnosa_awal,
                            );
                            // $info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
                        }
                    }
                }
                $bed = array(
                    'kode_ruangan'  => $row->kode_ruangan,
                    'no_bed'        => $row->no_bed,
                    'status'        => $row->status,
                    'status_bed'    => $status_bed,
                    'info_booking'  => $info_booking,
                    'info_pasien'   => $info_pasien
                );
                array_push($output, $bed);
            }
        }

        return $output;
    }
}

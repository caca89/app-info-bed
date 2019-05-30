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
        $list_beds      = array();
        $list_classes   = array();

        $data = $this->data;
        $room = isset($data['room']) ? $data['room'] : "";

        $list_classes = $this->rsannisa_model->get_list_classes($room);
        if($list_classes) {
            $classes  = $list_classes[0]->nama_klas;
            $all_beds = $this->rsannisa_model->get_bed_by_room($room, $classes);

            if($all_beds) {
                foreach($all_beds as $row) {
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
                        //$info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
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
    							//$info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
                            }
    					}
                    }
                    $bed = array(
                        'no_registrasi'  => $row->no_registrasi,
                        'kode_ruangan'  => $row->kode_ruangan,
                        'no_bed'        => $row->no_bed,
                        'status'        => $row->status,
                        'status_bed'    => $status_bed,
                        'info_booking'  => $info_booking,
                        'info_pasien'   => $info_pasien
                    );
                    array_push($list_beds, $bed);
                }

            }

        }
        $output = array(
            'list_classes' => $list_classes,
            'list_beds'    => $list_beds,
        );

        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
    }
    public function select_classes() {
        $data = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Parameter tidak ditemukan.'], 200);
        } else {
            $list_beds  = array();
            $room       = isset($data['room']) ? $data['room'] : 0;
            $classes    = isset($data['classes']) ? $data['classes'] : 0;
            $all_beds   = $this->rsannisa_model->get_bed_by_room($room, $classes);

            if($all_beds) {
                foreach($all_beds as $key=>$row) {
                    $info_booking = array();
                    $info_pasien  = array();

                    $status     = $row->status;
                    $status_bed = intval($row->status_bed);

                    if($status=='ISI') {
                        $status_bed  = $status_bed < 3 ? 2 : $status_bed;
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
                            } else {
                                $status_bed = 0;
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
                    array_push($list_beds, $bed);
                }
            }

            $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$list_beds], 200);
        }
    }

    public function reload_data() {
        $data       = $this->data;
        $room       = isset($data['room']) ? $data['room'] : "";
        $classes    = isset($data['classes']) ? $data['classes'] : "";
        $list_beds  = array();

        $all_beds = $this->rsannisa_model->get_bed_by_room($room, $classes);
        if($all_beds) {
            foreach($all_beds as $row) {
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
                    //$info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
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
							//$info_pasien = $this->rsannisa_model->get_patient_inpatient($row->no_bed);
						}
					}
                }
                $bed = array(
                    'no_registrasi' => $row->no_registrasi,
                    'kode_ruangan'  => $row->kode_ruangan,
                    'no_bed'        => $row->no_bed,
                    'status'        => $row->status,
                    'status_bed'    => $status_bed,
                    'info_booking'  => $info_booking,
                    'info_pasien'   => $info_pasien
                );
                array_push($list_beds, $bed);
            }

        }

        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$list_beds], 200);
    }

    // public function save($method = '')
    // {
    //     $data   = $this->data;
    //     $header = $this->header;
    //     if(!$data) {
    //         $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Ubah status bed gagal [Parameter tidak ditemukan].'], 200);
    //     } else {
    //         $no_registrasi   	= isset($data['no_registrasi']) ? $data['no_registrasi'] : 0;
    //         $kode_ruangan   	= isset($data['kode_ruangan']) ? $data['kode_ruangan'] : 0;
    //         $status_bed_before 	= isset($data['status_bed']) ? $data['status_bed'] : 0;
    //         $status_bed_after 	= isset($data['set_status_bed']) ? $data['set_status_bed'] : 0;
    //         $no_induk       	= isset($header['No-Induk']) ? decode($header['No-Induk']) : 0;
    //         $date           	= date('Y-m-d H:i:s');

    //         $check_bed = $this->rsannisa_model->check_bed($kode_ruangan, $status_bed_before);
    //         if(!$check_bed) {
    //             $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Ubah status bed gagal [Status bed sudah berubah].'], 200);
    //         } else {
    //         	$data_update = array(
    //         		'status_bed_before'	=> $status_bed_before,
    //         		'status_bed_after' 	=> $status_bed_after,
    //         		'no_registrasi' 	=> $no_registrasi,
    //         		'kode_ruangan' 		=> $kode_ruangan,
    //         		'process' 			=> 4,
    //         		'time_end' 			=> $date,
    //         		'no_induk' 			=> $no_induk
    //         	);
    //     		$save = $this->rsannisa_model->update_status_bed($data_update);
    //         	if($status_bed_before < 5) {
    //     			$log_detail_before = $this->rsannisa_model->get_log_detail($no_registrasi, 3);
    //     			if($log_detail_before) {
    //     				$date_start = $log_detail_before->time_end;
    //     				$date_end 	= $data_update['time_end'];

    //     				$data_update['amount_time'] = $this->amount_time($date_start, $date_end);
        				
	   //          		$save = $this->rsannisa_model->insert_log_detail($data_update);
	   //          		if($save) {
	   //          			$log = $this->rsannisa_model->check_log($no_registrasi);
	   //          			if(!$log) {
			 //            		$save = $this->rsannisa_model->update_status_log($data_update);
			 //            		if(!$save) {
			 //            			$this->rsannisa_model->update_status_bed($data_update, true);
			 //            			$this->rsannisa_model->insert_log_detail($data_update, true);
			 //            		}
	   //          			}
	   //          		} else {
	   //          			$this->rsannisa_model->update_status_bed($data_update, true);
    // 	        			$save = false;
	   //          		}
    //     			} else {
    //         			$this->rsannisa_model->update_status_bed($data_update, true);
    //         			$save = false;
    //     			}
    //         	}

    //             if($save) {
    //                 $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'message'=>'Ubah status bed berhasil.'], 200);
    //             } else {
    //                 $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Terjadi kesalahan ketika menyimpan data.'], 200);
    //             }
    //         }
    //     }
    // }

    public function save($method = '')
    {
        $data   = $this->data;
        $header = $this->header;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Ubah status bed gagal [Parameter tidak ditemukan].'], 200);
        } else {
            $no_registrasi   	= isset($data['no_registrasi']) ? $data['no_registrasi'] : 0;
            $kode_ruangan   	= isset($data['kode_ruangan']) ? $data['kode_ruangan'] : 0;
            $status_bed_before 	= isset($data['status_bed']) ? $data['status_bed'] : 0;
            $status_bed_after 	= isset($data['set_status_bed']) ? $data['set_status_bed'] : 0;
            $no_induk       	= isset($header['No-Induk']) ? decode($header['No-Induk']) : 0;
            $date           	= date('Y-m-d H:i:s');

            $check_bed = $this->rsannisa_model->check_bed($kode_ruangan, $status_bed_before);
            if(!$check_bed) {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Ubah status bed gagal [Status bed tidak sesuai.].'], 200);
            } else {
            	$data_update = array(
            		'status_bed_before'	=> $status_bed_before,
            		'status_bed_after' 	=> $status_bed_after,
            		'no_registrasi' 	=> $no_registrasi,
            		'kode_ruangan' 		=> $kode_ruangan,
            		'process' 			=> 4,
            		'time_end' 			=> $date,
            		'no_induk' 			=> $no_induk
            	);
        		$save = $this->rsannisa_model->update_status_bed($data_update);
            	if($status_bed_before < 5) {
                    $date_start = "";
                    $date_start = $data_update['time_end'];

                    /* AMBIL DATA LOG DETAIL PROSES SEBELUMNYA */
        			$log_detail_before = $this->rsannisa_model->get_log_detail($no_registrasi, 3);
        			if($log_detail_before) {
                        /* SET TOTAL TIME */
                        $data_update['amount_time'] = $this->amount_time($date_start, $date_end);
                    }

                    $save = $this->rsannisa_model->insert_log_detail($data_update);
                    if($save) {
                        $log = $this->rsannisa_model->check_log($no_registrasi);
                        if($log) {
                            $save = $this->rsannisa_model->update_status_log($data_update);
                            if(!$save) {
                                $this->rsannisa_model->update_status_bed($data_update, true);
                                $this->rsannisa_model->insert_log_detail($data_update, true);
                            }
                        // } else {
                        //     $this->rsannisa_model->update_status_bed($data_update, true);
                        //     $this->rsannisa_model->insert_log_detail($data_update, true);
                        //     $save = false;
                        }
                    } else {
                        $this->rsannisa_model->update_status_bed($data_update, true);
                        $save = false;
                    }
            	}

                if($save) {
                    $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'message'=>'Ubah status bed berhasil.'], 200);
                } else {
                    $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Terjadi kesalahan ketika menyimpan data.'], 200);
                }
            }
        }
    }

    private function amount_time($date_start, $date_end) {
		$date_start = new DateTime($date_start);
		$date_end 	= new DateTime($date_end);
	
		$diff = $date_end->diff($date_start);

		$count_second = 0;
		if($diff->d > 0) {
			$count_second = $count_second + intval($diff->d * 24 * 60 * 60);
		}
		if($diff->h > 0) {
			$count_second = intval($count_second) + intval($diff->h * 60 * 60);
		}
		if($diff->i > 0) {
			$count_second = intval($count_second) + intval($diff->i * 60);
		}
		if($diff->s > 0) {
			$count_second = intval($count_second) + intval($diff->s);
		}

		return $count_second;
    }
}

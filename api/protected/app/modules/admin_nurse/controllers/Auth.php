<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends App_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data     = json_decode(file_get_contents('php://input'), TRUE);
        $this->header   = $this->input->request_headers(TRUE);
        $this->load->model('rsannisa_model');
        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function list_rooms() {
        $output = $this->rsannisa_model->get_list_rooms();
        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output], 200);
    }
    public function login() {
        $data = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Parameter tidak ditemukan.'], 200);
        } else {
            $username = isset($data['username']) ? $data['username'] : 0;
            $password = isset($data['password']) ? $data['password'] : 0;
            $room     = isset($data['room']) ? $data['room'] : 0;
            $date     = date('Y-m-d H:i:s');

            if ($username===0 OR $password===0 OR $room===0) {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Username dan password harus diisi.'], 200);
            } else {
                $user = $this->db2->select('a1.no_induk, a1.password, a2.nama_pegawai, a2.kode_bagian')
                                    ->join('mt_karyawan a2', 'a2.no_induk=a1.no_induk', 'INNER')
                                    ->where('a1.username', $username)
                                    ->where('a1.status', 0)
                                    ->get('dd_user a1')->row();
                if ($user) {
                    $password = md5($password);
                    if ($user->password==$password) {
                        $sql_ruangan = $this->db2->select('kode_bagian')
                                                // ->where('kode_bagian', $login->kode_bagian)
                                                ->where('no_kamar', $room)
                                                ->get('mt_ruangan')->row();
                        if($sql_ruangan) {
                            $output = array(
                                'user_id'   => encode($user->no_induk),
                                'name'      => $user->nama_pegawai,
                                'room'      => $room,
                                'group'     => 2,
                                'last_login'=> $date
                            );

                            $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output, 'message'=>'Login berhasil, sedang mengalihkan...'], 200);
                        } else {
                            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Ruangan yang anda pilih tidak ditemukan.'], 200);
                        }
                    } else {
                        $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Username dan password tidak valid.'], 200);
                    }
                } else {
                    $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Username belum terdaftar.'], 200);
                }
            }
        }
    }
}

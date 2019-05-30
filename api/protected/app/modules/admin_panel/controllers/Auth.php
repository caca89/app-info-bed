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
            $date     = date('Y-m-d H:i:s');

            if ($username===0 OR $password===0) {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Username dan password harus diisi.'], 200);
            } else {
                $user = $this->db2->select('id, no_induk, password, name, group_id, status')
                                    ->where('username', $username)
                                    ->get('bed_users')->row();
                if ($user) {
                	if($user->status==1) {
	                    $check_password = $this->password->check_password($password, $user->password);
	                    if ($check_password) {
	                        $update = $this->db2->update('bed_users', ['last_login'=>$date], ['id'=>$user->id]);
	                        if($update) {
	                            $output = array(
	                                'user_id'   => encode($user->id),
	                                'name'      => $user->name,
	                                'group'     => $user->group_id,
	                                'last_login'=> $date
	                            );

	                            $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$output, 'message'=>'Login berhasil, sedang mengalihkan...'], 200);
	                        } else {
	                            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Terjadi kesalahan ketika menyimpan data.'], 200);
	                        }
	                    } else {
	                        $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Password anda tidak valid.'], 200);
	                    }
                    } else {
                        $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Akun anda tidak aktif.'], 200);
                    }
                } else {
                    $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Username tidak valid.'], 200);
                }
            }
        }
    }
}

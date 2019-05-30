<?php defined('BASEPATH') or exit('No direct script access allowed!');

class User extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->data     = json_decode(file_get_contents('php://input'), TRUE);
        $this->header   = $this->input->request_headers(TRUE);
        $this->db2      = $this->load->database('db_rsannisa', TRUE);
        $this->load->model('user_model');
    }

    public function index()
    {
        $user_id    = isset($this->header['User-Id']) ? decode($this->header['User-Id']) : 0;
        $user       = $this->db2->get_where('bed_users', ['id'=>$user_id])->row();
        $group_id   = $user ? $user->group_id : 100;
        $param      = $this->input->get();

        $data_users = $this->user_model->get_data($param);
        $total      = $this->user_model->get_total($param);

        $array = array (
            'draw'              => intval($this->input->get('draw')),
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data_users
        );

        if($array['data']) {
            $number = $this->input->get('start');
            foreach($array['data'] as $key => $value) {
                $number++;
                $actions = '
                    <div class="text-center actions">
                        <a class="edit" href="javascript:;" data-id="'.$value['id'].'"><i class="fa fa-pencil"></i> Edit</a>
                        <a class="reset-pass" href="javascript:;" data-id="'.$value['id'].'"><i class="fa fa-lock"></i> Reset Password</a>
                    </div>';
                $status = '<div class="text-center"><span class="label label-lg label-success">Aktif</span></div>';
                if($value['status']==0) {
                    $status = '<div class="text-center"><span class="label label-lg label-danger">Non Aktif</span></div>';
                }
                $array['data'][$key]['id']      = $number;
                $array['data'][$key]['status']  = $status;
                $array['data'][$key]['actions'] = $actions;
            }
        }

        echo json_encode($array);
    }

    public function save($method = '')
    {
        $data   = $this->data;
        $header = $this->header;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Bad Request.'], 200);
        } else {
            $id      = isset($data['id']) ? $data['id'] : 0;
            $username= isset($data['username']) ? $data['username'] : 0;
            $user_id = isset($header['User-Id']) ? decode($header['User-Id']) : 0;
            $date    = date('Y-m-d H:i:s');

            $check_username = $this->user_model->get(['id !='=>$id, 'username'=>$username]);
            if($check_username) {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Nama pengguna sudah ada di database.'], 200);
            } else {
                if($method=='create') {
                    $data['group_id']   = 1;
                    $data['password']   = $this->password->hash_password('1234');
                    $data['created_at'] = $date;
                    $data['created_by'] = $user_id;
                    $save = $this->user_model->save_user($data);
                    if($save) {
                        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'message'=>'Data anda berhasil disimpan.'], 200);
                    } else {
                        $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Terjadi kesalahan ketika menyimpan data.'], 200);
                    }
                } else {
                    $data['updated_at'] = $date;
                    $data['updated_by'] = $user_id;
                    $save = $this->user_model->update_user($data, $id);
                    if($save) {
                        $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'message'=>'Data anda berhasil disimpan.'], 200);
                    } else {
                        $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Terjadi kesalahan ketika menyimpan data.'], 200);
                    }
                }
            }
        }
    }

    public function get_data() 
    {
        $data  = $this->data;
        if(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Bad Request.'], 200);
        } else {
            $id = isset($data['id']) ? $data['id'] : 0;
            $user = $this->user_model->get_user($id);
            if($user) {
                $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'data'=>$user], 200);
            } else {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Data not found.'], 200);
            }
        }
    }

    public function reset_password() 
    {
        $data  = $this->data;
        if(!$this->auth_api) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'This API key does not have enough permissions.'], 200);
        } elseif(!$data) {
            $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Bad Request.'], 200);
        } else {
            $id = isset($data['id']) ? $data['id'] : 0;
            $data['password'] = $this->password->hash_password(1234);
            $user = $this->user_model->update($data, $id);
            if($user) {
                $this->api->set_response(['status'=>200, 'status_text'=>'OK', 'message'=>'Reset password user is successfully.'], 200);
            } else {
                $this->api->set_response(['status'=>200, 'status_text'=>'NO', 'message'=>'Reset password user is fail.'], 200);
            }
        }
    }

}

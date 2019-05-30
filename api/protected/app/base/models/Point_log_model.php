<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Point_log_model extends X_Model {

    public $table = 'points_logs';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;
        $this->timestamps = FALSE;
    }
}

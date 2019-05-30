<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_detail_model extends X_Model {

    public $table = 'loan_detail';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = FALSE;
        $this->timestamps = TRUE;
    }
}

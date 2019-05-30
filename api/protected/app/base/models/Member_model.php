<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends X_Model {

    public $table = 'members';
    public $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->soft_deletes = TRUE;
    }
}

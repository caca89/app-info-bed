<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends App_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('administration');
        $this->administration->logged();
    }

    public function index($view) {
        // if($view == '' OR $view == 'login.html') {
            $this->template->_login();
            $this->output->set_title('Login');

            $this->load->view($view);            
        // }
    }
}

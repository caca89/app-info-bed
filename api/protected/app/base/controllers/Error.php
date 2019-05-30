<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends App_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('administration');
        $this->administration->logged();
    }

    public function index() {
        if (!$this->input->is_ajax_request()) {
            $this->template->_default();
            $this->output->set_title('404 Page Not Found!');
            $this->data['menu'] = array('menu' => '', 'submenu' => '');
        }
        $this->load->view('themes/default/error_404', $this->data);
    }
}

<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
* Omeoo Framework
* A framework for PHP development
*
* @package     Omeoo Framework
* @author      Omeoo Dev Team
* @copyright   Copyright (c) 2016, Omeoo Media (http://www.omeoo.com)
*/

class Administration {

    public function logged()
    {
        $app =& get_instance();
        if (!$app->session->userdata('logged_in'))
        {
            echo json_encode(['status' => 'NO', 'message' => 'You not logged in, please refresh this page.']);
            exit;
        }
    }

    public function language()
    {
        $app =& get_instance();
        if ($app->input->get('lang')) {
            $lang = $app->input->get('lang');
        } elseif ($app->session->userdata('language')) {
            $lang = $app->session->userdata('language');
        } else {
            $lang = $app->config->item('language');
        }
        $app->session->set_userdata('language', $lang);
        $app->load->language('common', $lang);
        $app->load->language('crud', $lang);

        return $lang;
    }
}

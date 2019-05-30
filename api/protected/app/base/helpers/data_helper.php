<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Omeoo Framework
 * A framework for PHP development
 *
 * @package     Omeoo Framework
 * @author      Omeoo Dev Team
 * @copyright   Copyright (c) 2016, Omeoo Media (http://www.omeoo.com)
 */

if (!function_exists('set_point')) {
    function set_point($user_id, $type, $post = null)
    {
        $app =& get_instance();
        $app->load->model('point_log_model');

        switch ($type) {
            case 'register':
                $point = get_option('point_register');
                $text = 'Registration.';
                break;
            case 'ultra_sharing':
                $point = get_option('point_share');
                $text = 'Like <a href="'.permalink($post).'" target="_blank">'.$post->title.'</a> published.';
                break;
            case 'ultra_like':
                $point = get_option('point_like');
                $text = 'Tanya Pakar <a href="'.permalink($post).'" target="_blank">'.$post->title.'</a> published.';
                break;
            case 'ultra_subscribe':
                $point = get_option('point_subscribe');
                $text = 'Subscription.';
                break;
            case 'post_publish':
                $point = get_option('point_tanya_pakar');
                $text = 'Tanya Pakar <a href="'.site_url('tanya-pakar/'.$post->slug).'">'.$post->title.'</a> published.';
                break;
            default:
                $point = 0;
                break;
        }
        $app->point_log_model->insert([
            'user_id' => $user_id,
            'log_type' => $type,
            'points' => $point,
            'points_type' => 'point',
            'text' => $text,
            'date' => date('Y-m-d H:i:s')
        ]);

        $current_point = $app->usermeta_model->get(['user_id' => $user_id, 'name' => 'points']);
        $app->usermeta_model->insert([
            'user_id' => $user_id,
            'name' => 'points',
            'value' => intval($current_point->value) + intval($point)
        ]);
    }
}

if (!function_exists('get_point')) {
    function get_point($user_id)
    {
        $app =& get_instance();
        $app->load->model('point_log_model');

        $get_point = $app->usermeta_model->get(['user_id' => $user_id, 'name' => 'points']);

        return ($get_point) ? $get_point->value : 0;
    }
}

if (!function_exists('get_point_purepassion')) {
    function get_point_purepassion($user)
    {
        $app =& get_instance();
        $query = $app->db->query("SELECT score FROM bolt_players JOIN bolt_scores ON bolt_players.id = bolt_scores.player WHERE bolt_players.email = '$user->email'");

        return ($query->num_rows() > 0) ? $query->row()->score : 0;
    }
}

if (!function_exists('check_point')) {
    function check_point($user_id, $type, $post_id)
    {
        $app =& get_instance();
        $check = $app->db->query("SELECT COUNT(*) AS count FROM points_logs INNER JOIN points_log_meta ON(points_logs.id = points_log_meta.log_id)
                                 WHERE points_logs.user_id = '$user_id' AND points_logs.log_type = '$type' AND points_log_meta.value = '$post_id'");

        return $check->row()->count;
    }
}

/* End of file point_helper.php */
/* Location: ./system/helpers/point_helper.php */

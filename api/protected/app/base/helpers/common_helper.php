<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Vetronnumber
 * A framework for PHP development
 *
 * @package     Vetronnumber
 * @author      Omeoo Dev Team
 * @copyright   Copyright (c) 2015, Omeoo Media Solusindo (http://www.omeoo.com)
 */

if (!function_exists('data_limit_max')) {
    function data_limit_max() {
        $datas = array(
            array('id'=>1, 'name'=>'Per Hari'),
            array('id'=>2, 'name'=>'Per Tahun'),
            array('id'=>3, 'name'=>'Per Kejadian'),
            array('id'=>4, 'name'=>'Per Kunjungan'),
            array('id'=>5, 'name'=>'Per Kasus'),
            array('id'=>6, 'name'=>'Per Penyakit')
        );
        return $datas;
    }
}

if (!function_exists('get_limit_max')) {
    function get_limit_max($id) {
        $results = '';
        $datas = data_limit_max();
        foreach($datas as $key => $value) {
            if($value['id']==$id) {
                $results = $value['name'];
                break;
            }
        }
        return $results;
    }
}

if (!function_exists('benefit_date_start')) {
    function benefit_date_start($date = false) {
        $date_start = date('Y-01-01');
        if($date) {
            $date_start = $date;
        }
        return $date_start;
    }
}
if (!function_exists('benefit_date_end')) {
    function benefit_date_end($date = false) {
        $date_end = date('Y-12-31');
        if($date) {
            $date_end = date('Y-m-d', strtotime('+1 year', strtotime($date)));
            $date_end = date('Y-m-d', strtotime('-1 days', strtotime($date_end)));
        }
        return $date_end;
    }
}

if (!function_exists('message_before_print')) {
    function message_before_print($url='', $title='', $message='', $modal_id = '') {
        $modal_print ='<script>
                $("'.$modal_id.'").find(".modal-title").text("'.$title.'");
                $("'.$modal_id.'").find(".modal-body").html("'.$message.'");
                $("'.$modal_id.'").find("#print_out").attr("data-url", "'.$url.'");
                $("'.$modal_id.'").modal();
            </script>';

        return $modal_print;
    }
}

if (!function_exists('print_out')) {
    function print_out($url='', $title='', $modal_id = '') {
        $modal_print ='<script>
            $("'.$modal_id.'").find(".modal-body").load('.$url.', function() {
                $(".modal-title").text("'.$title.'");
            })
            </script>';

        return $modal_print;
    }
}

if (!function_exists('window_open')) {
    function window_open($url = '') {
        return '<script>
                var windowName = "Cetak Laporan"; 
                var popUp = window.open("'.$url.'", windowName);
                if (popUp == null || typeof(popUp)=="undefined") {  
                    alert("Please disable your pop-up blocker and click the \"Open\" link again."); 
                } 
                else {  
                    popUp.focus();
                }
                </script>';
    }
}

if ( ! function_exists('get_payment_number')) 
{
    function get_payment_number() {
        $app =& get_instance();
        $loans = $app->trans_model->order_by('id', 'desc')->get(['type' => 'setoran']);
        if($loans) {
            $number1 = $loans->number;
            $number2 = explode('/', $number1);
            $number3 = intval($number2[1]) + 1;
            switch(strlen($number3)) {
                case 1: $number = '0000'.$number3; break;
                case 2: $number = '000'.$number3; break;
                case 3: $number = '00'.$number3; break;
                case 4: $number = '0'.$number3; break;
                case 5: $number = $number3; break;
            }
            $number = 'STR/'.$number.'/'.date('d').'/'.get_month4(date('m')).'/'.date('Y');
        } else {
            $number = 'STR/00001/'.date('d').'/'.get_month4(date('m')).'/'.date('Y');
        }
        return $number;
    }
}

if ( ! function_exists('get_loan_number')) 
{
    function get_loan_number() {
        $app =& get_instance();
        $loans = $app->trans_model->order_by('id', 'desc')->get(['type' => 'pinjaman']);
        if($loans) {
            $number1 = $loans->number;
            $number2 = explode('/', $number1);
            $number3 = intval($number2[1]) + 1;
            switch(strlen($number3)) {
                case 1: $number = '0000'.$number3; break;
                case 2: $number = '000'.$number3; break;
                case 3: $number = '00'.$number3; break;
                case 4: $number = '0'.$number3; break;
                case 5: $number = $number3; break;
            }
            $number = 'BMT-DS/'.$number.'/'.get_month4(date('m')).'/'.date('Y');
        } else {
            $number = 'BMT-DS/00001/'.get_month4(date('m')).'/'.date('Y');
        }
        return $number;
    }
}

if ( ! function_exists('get_trans_number')) 
{
    function get_trans_number($type) {
        $app =& get_instance();
        $string = ($type == 'simpanan') ? "CRT" : "DBT";
        $trans = $app->trans_model->order_by('id', 'desc')->where('date_add', date('Y-m-d'))->get(['type' => $type]);
        if($trans) {
            $number  = $string.'-'.date('d').'-'.date('m').'-'.date('Y').'-'; 
            $number1 = $trans->number;
            $number2 = explode('-', $number1);
            $number3 = intval($number2[count($number2) - 1]) + 1;
            switch(strlen($number3)) {
                case 1: $number .= '0000'.$number3; break;
                case 2: $number .= '000'.$number3; break;
                case 3: $number .= '00'.$number3; break;
                case 4: $number .= '0'.$number3; break;
                case 5: $number .= $number3; break;
            }            
        } else {
            $number  = $string.'-'.date('d').'-'.date('m').'-'.date('Y').'-00001'; 
        }
        return $number;
    }
}

if ( ! function_exists('get_member_number')) 
{
    function get_member_number() 
    {
        $app =& get_instance();
        $member = $app->member_model->order_by('id', 'desc')->get();
        if($member) {
            $number1 = explode('-', $member->number);
            $number2 = intval($number1[1]) + 1;
            switch(strlen($number2)) {
                case 1: $number = 'A-000'.$number2; break;
                case 2: $number = 'A-00'.$number2; break;
                case 3: $number = 'A-0'.$number2; break;
                case 4: $number = 'A-'.$number2; break;
            }
        } else {
            $number = 'A-0001';
        }
        return $number;
    }
}

if ( ! function_exists('get_time')) 
{
    function get_time($fulldate, $time = 24) 
    {
        $date   = date_create($fulldate);
        $hour   = date_format($date, 'H');
        $minute = date_format($date, 'i');
        $AM_PM  = ($time != 24) ? ($hour < 12 ? 'AM':'PM'):'';

        return $hour . ":" . $minute . " ". $AM_PM;
    }
}

if ( ! function_exists('get_option')) {
    function get_option($name = null)
    {
        $app =& get_instance();
        if (is_null($name))
        {
            $setting = $app->option_model->get_all();
            if ($setting) {
                foreach($setting as $set) {
                    $settings[$set->name] = $set->value;
                }
            }
            return (isset($settings)) ? $settings : '';
        }
        else
        {
            $setting = $app->option_model->fields('id,value')->get(['name' => $name]);
            return ($setting) ? $setting->value : '';
        }
    }
}

if (!function_exists('upload_path')) {
    function upload_path() {
        $path_year = '../uploads/'.date('Y').'/';
        if (!is_dir($path_year)) {
            mkdir($path_year);
        }
        $path_month = $path_year.date('m').'/';
        if (!is_dir($path_month)) {
            mkdir($path_month);
        }

        return $path_month;
    }
}

if (!function_exists('encode')) {
    function encode($string) {
        return encrypt_decrypt('encrypt', $string);
    }
}

if (!function_exists('decode')) {
    function decode($string) {
        return encrypt_decrypt('decrypt', $string);
    }
}

if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'eXnumberFramework';
        $secret_iv = 'Omeoo Media';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}

/*==-- Image link --==*/
if (!function_exists('image_url')) {
    function image_url($url) {
        $base_url = str_replace('/administrator', '', site_url());
        return str_replace($base_url.'media/images/', "", $url);
    }
}

/*==-- Print link --==*/
if (!function_exists('print_link')) {
    function print_link($link, $title) {
        $anchor = anchor($link, '<i class="fa fa-print"></i>', array('class' => 'btn btn-sm btn-success', 'title' => $title));
        return $anchor;
    }
}

/*==-- Edit link --==*/
if (!function_exists('edit_link')) {
    function edit_link($link, $title) {
        $anchor = '<a href="#" data-href="'.site_url($link).'" class="btn btn-sm green-jungle ajax" title="'.$title.'"><i class="fa fa-pencil"></i></a>';
        return $anchor;
    }
}

/*==-- Delete link --==*/
if (!function_exists('delete_link')) {
    function delete_link($link, $title, $alert) {
        $anchor = anchor($link, '<i class="fa fa-trash-o"></i>', array('class' => 'btn btn-sm red-flamingo', 'title' => $title, 'onclick' => "return confirm('".sprintf(lang('alert_delete'), $alert)."');"));
        return $anchor;
    }
}

/*==-- Add URL --==*/
if (!function_exists('add_url')) {
    function add_url($controller) {
        return site_url($controller.'/create');
    }
}

/*==-- JSON to Array --==*/
if (!function_exists('to_array')) {
    function to_array($json) {
        $array = json_decode($json, TRUE);
        return (is_array($array)) ? $array : FALSE;
    }
}

/*==-- Array to JSON --==*/
if (!function_exists('to_json')) {
    function to_json($array) {
        return (is_array($array)) ? json_encode($array) : FALSE;
    }
}

/*==-- Age calculator --==*/
if (!function_exists('age')) {
    function age($birthdate) {
        $from = new DateTime($birthdate);
        $to   = new DateTime('today');
        return $from->diff($to)->y;
    }
}

/*==-- Rupiah currency --==*/
if (!function_exists('rupiah')) {
    function rupiah($val) {
        $value = number_format($val, 0, '.', ',');
        return $value;
    }
}

/*==-- return save --==*/
if (!function_exists('returnSave')) {
    function returnSave($save, $submit, $control, $action, $return_url = NULL) {
        if ($action == 'insert') {
            $act_msg = 'added';
        } else {
            $act_msg = 'updated';
        }
        if ($save === TRUE) {
            if ($submit == '1') {
                echo successMessage($control.' succesfully '.$act_msg.'.', $return_url);
            } elseif ($submit == '2') {
                echo successMessage($control.' succesfully '.$act_msg.'.', $return_url.'/insert');
            }
        } else {
            echo errorMessage('Failed to save '.$control.'.');
        }
    }
}

/*==-- Success message --==*/
if (!function_exists('successMessage')) {
    function successMessage($message, $redirect = NULL) {
        $msg = '<div class="alert alert-success">'.$message.'</div>';
        return $msg;
    }
}

/*==-- Error message --==*/
if (!function_exists('errorMessage')) {
    function errorMessage($message) {
        $msg = '<div class="alert alert-danger">'.$message.'</div>';
        return $msg;
    }
}

if (!function_exists('deleteMessage')) {
    function deleteMessage($status, $title) {
        if ($status == "success") {
            echo successMessage($title.' successfully deleted.');
        } elseif ($status == "error") {
            echo errorMessage('Failed to delete '.$title);
        }
    }
}

if (!function_exists('clearForm')) {
    function clearForm() {
        return '<script>$("input,textarea,select").val("");</script>';
    }
}

if (!function_exists('loadPage')) {
    function loadPage($url, $title) {
        return "<script>
        var url = '$url';
        var title = '$title';
        $('#content').load(url);
        document.title = title + ' | mwkCMS';
        window.history.pushState('', title, url);
        $(this).parents('#sidebar-menu').find('.menu_section').removeClass('active');
        $(this).parents('.menu_section').addClass('active');
        $(this).parent().siblings().removeClass('active current-page');
        $(this).parent().addClass('active current-page');
        $(this).parent().parent().parent().siblings().removeClass('active current-page');
        </script>";
    }
}

/*==-- Ajax redirect --==*/
if (!function_exists('ajaxRedirect')) {
    function ajaxRedirect($redirect = '', $timer = 10000) {
        if ($timer == 0) {
            return '<script>window.location.href="' . site_url($redirect) . '";</script>';
        } else {
            return '<script>setTimeout("window.location.href=\'' . site_url($redirect) . '\'",' . $timer . ');</script>';
        }
    }
}

if (!function_exists('excerpt')) {
    function excerpt($content, $length = 200, $striptags = FALSE, $delimiter = ''){
		$excerpt = explode('<div style="page-break-after: always;"><span style="display:none">&nbsp;</span></div>', $content, 2);
		if(count($excerpt) > 1){
			$excerpt_fix = ($striptags!=FALSE) ? strip_tags(htmlspecialchars_decode($excerpt[0])) : closetags(htmlspecialchars_decode($excerpt[0]));
		} else {
			$excerpt_fix = ($striptags!=FALSE) ? strip_tags(substr(htmlspecialchars_decode($content), 0, $length)) : closetags(substr(htmlspecialchars_decode($content), 0, $length));
		}
		return ($length < strlen($content)) ? $excerpt_fix.$delimiter : $excerpt_fix;
	}
}

if (!function_exists('closetags')) {
    function closetags($html){
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];

		$len_opened = count($openedtags);

		if (count($closedtags) == $len_opened) {
			return $html;
		}

		$openedtags = array_reverse($openedtags);

		for ($i = 0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</' . $openedtags[$i] . '>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}
}

if (!function_exists('date_indo')) {
    function date_indo($fulldate) {
        $date = substr($fulldate, 8, 2);
        $month = get_month(substr($fulldate, 5, 2));
        $year = substr($fulldate, 0, 4);
        return $date . ' ' . $month . ' ' . $year;
    }
}

if (!function_exists('date_simple')) {
    function date_simple($fulldate) {
        $date = substr($fulldate, 8, 2);
        $month = substr($fulldate, 5, 2);
        $year = substr($fulldate, 0, 4);
        return $date . '/' . $month . '/' . $year;
    }
}

if (!function_exists('date_normal')) {
    function normal_date($fulldate) {
        $date = substr($fulldate, 8, 2);
        $month = get_month3(substr($fulldate, 5, 2));
        $year = substr($fulldate, 0, 4);
        return $date . '/' . $month . '/' . $year;
    }
}

if (!function_exists('date_time')) {
    function date_time($fulldate) {
        $date = substr($fulldate, 0, 2);
        $month = get_month2(substr($fulldate, 3, 3));
        $year = substr($fulldate, 7, 4);
        $time = substr($fulldate, 12, 5);
        return $year . '-' . $month . '-' . $date . ' ' . $time;
    }
}

if (!function_exists('date_full')) {
    function date_full($fulldate) {
        $date = substr($fulldate, 8, 2);
        $month = substr($fulldate, 5, 2);
        $year = substr($fulldate, 0, 4);
        $time = substr($fulldate, 11, 8);
        return $date . '/' . $month . '/' . $year . ' ' . $time;
    }
}

if (!function_exists('mysql_date')) {
    function mysql_date($fulldate) {
        $date = substr($fulldate, 0, 2);
        $month = get_month2(substr($fulldate, 3, 3));
        $year = substr($fulldate, 7, 4);
        return $year . '-' . $month . '-' . $date;
    }
}

if (!function_exists('get_month')) {
    function get_month($month) {
        switch ($month) {
            case 1: return "Januari";
            case 2: return "Februari";
            case 3: return "Maret";
            case 4: return "April";
            case 5: return "Mei";
            case 6: return "Juni";
            case 7: return "Juli";
            case 8: return "Agustus";
            case 9: return "September";
            case 10: return "Oktober";
            case 11: return "November";
            case 12: return "Desember";
        }
    }
}

if (!function_exists('get_month2')) {
    function get_month2($month) {
        switch ($month) {
            case "Jan": return "01";
            case "Feb": return "02";
            case "Mar": return "03";
            case "Apr": return "04";
            case "May": return "05";
            case "Jun": return "06";
            case "Jul": return "07";
            case "Aug": return "08";
            case "Sep": return "09";
            case "Oct": return "10";
            case "Nov": return "11";
            case "Dec": return "12";
        }
    }
}

if (!function_exists('get_month3')) {
    function get_month3($month) {
        switch ($month) {
            case "01": return "Jan";
            case "02": return "Feb";
            case "03": return "Mar";
            case "04": return "Apr";
            case "05": return "May";
            case "06": return "Jun";
            case "07": return "Jul";
            case "08": return "Aug";
            case "09": return "Sep";
            case "10": return "Oct";
            case "11": return "Nov";
            case "12": return "Dec";
        }
    }
}

if (!function_exists('get_month4')) {
    function get_month4($month) {
        switch ($month) {
            case "01": return "I";
            case "02": return "II";
            case "03": return "III";
            case "04": return "IV";
            case "05": return "V";
            case "06": return "VI";
            case "07": return "VII";
            case "08": return "VIII";
            case "09": return "IX";
            case "10": return "X";
            case "11": return "XI";
            case "12": return "XII";
        }
    }
}

if (!function_exists('get_month5')) {
    function get_month5($month) {
        switch ($month) {
            case "01": return "Januari";
            case "02": return "Februari";
            case "03": return "Maret";
            case "04": return "April";
            case "05": return "Mei";
            case "06": return "Juni";
            case "07": return "Juli";
            case "08": return "Agustus";
            case "09": return "September";
            case "10": return "Oktober";
            case "11": return "November";
            case "12": return "Desember";
        }
    }
}
if (!function_exists('dropdown_month')) {
    function dropdown_month($month = '') {
        $month = ($month == '') ? date('m') : $month;
        $options = '';
        for($a=1; $a<=12; $a++) {
            $key = ($a<10) ? '0'.$a : $a;
            $selected = ($key==$month) ? 'selected' : '';
            $options .= '<option value="'.$key.'" '.$selected.'>'.get_month5($key).'</option>';
        }
        return $options;
    }
}
if (!function_exists('dropdown_year')) {
    function dropdown_year($year = '') {
        $options = '';
        for($a=0; $a<5; $a++) {
            $year = ($year == '') ? date('Y') : $year;
            $years = intval(date('Y')) - $a;
            $selected = ($years == $year) ? 'selected' : '';
            $options .= '<option value="'.$years.'" '.$selected.'>'.$years.'</option>';
        }
        return $options;
    }
}

if (!function_exists('terbilang')) {
    function terbilang($nominal = 0) {
        $x = abs($nominal);
        $angka = array("","satu","dua","tiga","empat","lima",
        "enam","tujuh","delapan","sembilan","sepuluh","sebelas");
        $temp="";
        if($x<12){
            $temp=" ".$angka[$x];
        }elseif($x<20){
            $temp=terbilang($x-10)." belas";
        }elseif($x<100){
            $temp=terbilang($x/10)." puluh".terbilang($x%10);
        }elseif($x<200){
            $temp=" seratus".terbilang($x-100);
        }elseif($x<1000){
            $temp=terbilang($x/100)." ratus".terbilang($x%100);
        }elseif($x<2000){
            $temp=" seribu".terbilang($x-1000);
        }elseif($x<1000000){
            $temp=terbilang($x/1000)." ribu".terbilang($x%1000);
        }elseif($x<1000000000){
            $temp=terbilang($x/1000000)." juta".terbilang($x%1000000);
        }elseif($x<1000000000000){
            $temp=terbilang($x/1000000000)." milyar".terbilang(fmod($x,1000000000));
        }elseif($x<1000000000000000){
            $temp=terbilang($x/1000000000000)." trilyun".terbilang(fmod($x,1000000000000));
        }    
        return ucwords(strtolower($temp));
    }
}


/* End of file common_helper.php */
/* Location: ./system/helpers/common_helper.php */

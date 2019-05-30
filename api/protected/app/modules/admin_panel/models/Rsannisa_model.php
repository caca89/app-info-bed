<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rsannisa_model extends App_Model {

    public function __construct()
    {
        parent::__construct();

        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function get_average_time_month($process) {
        $month_current = date('Y-m');
        $sql = "SELECT AVG(a2.amount_time) as avg_time
                FROM  bed_logs as a1 
                INNER JOIN bed_log_details as a2 ON a2.no_registrasi=a1.no_registrasi 
                WHERE CONVERT(nvarchar(7), a1.dates, 120)='".$month_current."'
                AND a2.process='".$process."' 
                AND a1.status=1";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row()->avg_time : 0;
    }

    public function get_patient_out_daily() {
        $date_current = date('Y-m-d');
        $time_adm_ri = ", (
                        SELECT TOP(1) CONVERT(VARCHAR(8), a2.time_end, 108) as time_end 
                        FROM bed_log_details as a2 
                        WHERE a2.no_registrasi=a1.no_registrasi 
                        AND a2.process=2) as time_adm_ri";
        // $time_depo = ", (
        //                 SELECT TOP(1) CONVERT(VARCHAR(8), a3.time_end, 108) as time_end 
        //                 FROM bed_log_details as a3 
        //                 WHERE a3.no_registrasi=a1.no_registrasi 
        //                 AND a3.process=2) as time_depo";
        $time_kasir_ri = ", (
                            SELECT TOP (1) CONVERT(VARCHAR(8), a4.time_end, 108) as time_end 
                            FROM bed_log_details as a4 
                            WHERE a4.no_registrasi=a1.no_registrasi 
                            AND a4.process=3) as time_kasir_ri";
        // $time_patient_out = ", (
        //                     SELECT TOP(1) CONVERT(VARCHAR(8), a5.time_end, 108) as time_end 
        //                     FROM bed_log_details as a5 
        //                     WHERE a5.no_registrasi=a1.no_registrasi 
        //                     AND a5.process=4) as time_patient_out";
        // $time_in = ", (
        //                 SELECT TOP (1) CONVERT(varchar(10), a81.tgl_masuk, 121) as time_in 
        //                 FROM ri_tc_riwayat_kelas as a8 
        //                 INNER JOIN ri_tc_rawatinap a81 ON a81.kode_ri=a8.kode_ri 
        //                 WHERE a8.no_registrasi=a1.no_registrasi 
        //                 ORDER BY a8.tgl_masuk DESC) as time_in";
        // $time_out = ", (
        //                 SELECT TOP (1) CONVERT(varchar(10), a101.tgl_keluar, 121) as time_in 
        //                 FROM ri_tc_riwayat_kelas as a10 
        //                 INNER JOIN ri_tc_rawatinap a101 ON a101.kode_ri=a10.kode_ri 
        //                 WHERE a10.no_registrasi=a1.no_registrasi 
        //                 ORDER BY a10.tgl_masuk DESC) as time_out";
        $sql = "SELECT a1.no_registrasi, a1.no_bed, a1.no_mr, b1.nama_pasien as patient_name".$time_adm_ri.$time_kasir_ri." 
                FROM  bed_logs as a1 
                INNER JOIN mt_master_pasien b1 ON b1.no_mr=a1.no_mr 
                WHERE a1.dates='".$date_current."' 
                ORDER BY a1.created_at DESC";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result() : 0;
    }
    public function get_patient_out_by_date($date_start, $date_end) {
        $time_adm_ri = ", (
                        SELECT TOP(1) CONVERT(VARCHAR(8), a2.time_end, 108) as time_end 
                        FROM bed_log_details as a2 
                        WHERE a2.no_registrasi=a1.no_registrasi 
                        AND a2.process=2) as time_adm_ri";
        // $time_depo = ", (
        //                 SELECT TOP(1) CONVERT(VARCHAR(8), a3.time_end, 108) as time_end 
        //                 FROM bed_log_details as a3 
        //                 WHERE a3.no_registrasi=a1.no_registrasi 
        //                 AND a3.process=2) as time_depo";
        $time_kasir_ri = ", (
                            SELECT TOP (1) CONVERT(VARCHAR(8), a4.time_end, 108) as time_end 
                            FROM bed_log_details as a4 
                            WHERE a4.no_registrasi=a1.no_registrasi 
                            AND a4.process=3) as time_kasir_ri";
        // $time_patient_out = ", (
        //                     SELECT TOP(1) CONVERT(VARCHAR(8), a5.time_end, 108) as time_end 
        //                     FROM bed_log_details as a5 
        //                     WHERE a5.no_registrasi=a1.no_registrasi 
        //                     AND a5.process=4) as time_patient_out";
        // $time_in = ", (
        //                 SELECT TOP (1) CONVERT(varchar(10), a81.tgl_masuk, 121) as time_in 
        //                 FROM ri_tc_riwayat_kelas as a8 
        //                 INNER JOIN ri_tc_rawatinap a81 ON a81.kode_ri=a8.kode_ri 
        //                 WHERE a8.no_registrasi=a1.no_registrasi 
        //                 ORDER BY a8.tgl_masuk DESC) as time_in";
        // $time_out = ", (
        //                 SELECT TOP (1) CONVERT(varchar(10), a101.tgl_keluar, 121) as time_in 
        //                 FROM ri_tc_riwayat_kelas as a10 
        //                 INNER JOIN ri_tc_rawatinap a101 ON a101.kode_ri=a10.kode_ri 
        //                 WHERE a10.no_registrasi=a1.no_registrasi 
        //                 ORDER BY a10.tgl_masuk DESC) as time_out";
        $sql = "SELECT a1.no_registrasi, a1.dates, a1.no_bed, a1.no_mr, b1.nama_pasien as patient_name".$time_adm_ri.$time_kasir_ri." 
                FROM  bed_logs as a1 
                INNER JOIN mt_master_pasien b1 ON b1.no_mr=a1.no_mr 
                WHERE a1.dates BETWEEN '".$date_start."' AND '".$date_end."' 
                ORDER BY a1.created_at ASC";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result() : 0;
    }

}

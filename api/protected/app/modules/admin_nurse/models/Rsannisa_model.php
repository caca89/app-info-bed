<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rsannisa_model extends App_Model {

    public function __construct()
    {
        parent::__construct();

        $this->db2 = $this->load->database('db_rsannisa', TRUE);
    }

    public function get_list_rooms() {
        $sql = "SELECT no_kamar
                FROM  mt_ruangan AS a1
                WHERE kode_bagian NOT IN ('030901', '031301', '032001', '032002', '030115') 
                AND aktif IS NULL
                AND no_kamar!=''
                GROUP BY no_kamar
                ORDER BY no_kamar";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result() : false;
    }
    public function get_list_classes($room) {
        $sql = "SELECT a2.nama_klas 
                FROM  mt_ruangan a1
                INNER JOIN mt_klas a2 ON a2.kode_klas=a1.kode_klas
                WHERE a1.aktif IS NULL 
                AND a1.no_kamar='".$room."' 
                GROUP BY a2.nama_klas 
                ORDER BY a2.nama_klas";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    public function get_bed_by_room($room, $classes) {
        $date_current = date('Y-m-d');
        $booking = ", (
                        SELECT TOP(1) a3.id_tc_pesanan 
                        FROM tc_pesanan a3 
                        WHERE a3.tgl_pesanan >= CONVERT(datetime, '".$date_current."') 
                        AND (a3.status_batal IS NULL OR a3.status_batal=0) 
                        AND a3.kode_bagian=a1.kode_bagian 
                        AND a3.no_bed=a1.no_bed 
                        ORDER BY a3.tgl_pesanan ASC
                    ) as booking";
        $no_registrasi = ", (
                    SELECT TOP(1) a3.no_registrasi 
                    FROM ri_tc_riwayat_kelas a3 
                    WHERE a3.kode_ruangan=a1.kode_ruangan 
                    ORDER BY a3.kode_riw_klas DESC) as no_registrasi";
        $no_mr = ", (
                    SELECT TOP(1) a3.no_mr 
                    FROM ri_tc_riwayat_kelas a3 
                    WHERE a3.kode_ruangan=a1.kode_ruangan 
                    ORDER BY a3.kode_riw_klas DESC) as no_mr";
        $nama_pasien = ", (
                            SELECT TOP(1) a5.nama_pasien 
                            FROM ri_tc_riwayat_kelas a4 
                            INNER JOIN mt_master_pasien a5 ON a5.no_mr=a4.no_mr 
                            WHERE a4.kode_ruangan=a1.kode_ruangan 
                            ORDER BY kode_riw_klas DESC) as nama_pasien";
        /* ambil diagnosa awal dari tabel th_riwayat_pasien */
        $diagnosa_awal = ", (
                            SELECT TOP(1) a7.diagnosa_awal 
                            FROM ri_tc_riwayat_kelas a6 
                            INNER JOIN th_riwayat_pasien a7 ON a7.no_kunjungan=a6.kode_kunjungan 
                            WHERE a6.kode_ruangan=a1.kode_ruangan 
                            ORDER BY kode_riw_klas DESC) as diagnosa_awal";
        $sql = "SELECT a1.kode_ruangan, a1.kode_bagian, a1.kode_klas, a1.no_kamar, a1.no_bed, a1.status, a1.status_bed, a2.nama_klas, NULL as booking".$no_registrasi.$no_mr.$nama_pasien.$diagnosa_awal." 
            FROM mt_ruangan a1 
            INNER JOIN mt_klas a2 ON a2.kode_klas=a1.kode_klas 
            WHERE a1.aktif IS NULL 
            AND a1.no_kamar='".$room."' 
            AND a2.nama_klas='".$classes."' 
            ORDER BY a1.no_kamar, a2.nama_klas, a1.no_bed ASC";
            // WHERE a1.kode_bagian NOT IN ('030901', '031301', '032001', '032002', '030115') 

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->result() : false;
    }
	public function get_bed($kode_ruangan) {
        $date_current = date('Y-m-d');
        $sql = "SELECT a1.kode_ruangan, a1.kode_bagian, a1.kode_klas, a1.no_kamar, a1.no_bed, a1.status, a1.status_bed, a2.nama_klas 
            FROM mt_ruangan a1 
            INNER JOIN mt_klas a2 ON a1.kode_klas=a2.kode_klas 
            WHERE a1.kode_bagian NOT IN ('030901', '031301', '031001', '032001', '033001', '032002', '030115') 
            AND a1.flag IS NULL 
            AND a1.aktif IS NULL 
            AND a1.kode_ruangan='".$kode_ruangan."'";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row() : false;
	}

/*
    public function get_patient_booking($id_tc_pesanan) {
        $sql = "SELECT a2.no_mr, a2.nama_pasien, a2.tgl_lhr 
                FROM tc_pesanan a1 
                INNER JOIN mt_master_pasien a2 ON a2.no_mr=a1.no_mr 
                WHERE a1.id_tc_pesanan='".$id_tc_pesanan."'";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row() : false;
    }

    public function get_patient_inpatient($no_bed) {
        $sql = "SELECT TOP(1) a1.no_mr, a2.nama_pasien, a2.tgl_lhr, a4.diagnosa_awal
                FROM ri_tc_riwayat_kelas a1 
                INNER JOIN mt_master_pasien a2 ON a2.no_mr=a1.no_mr 
                LEFT JOIN ri_tc_rawatinap a3 ON a3.kode_ri=a1.kode_ri 
                LEFT JOIN th_riwayat_pasien a4 ON a4.no_kunjungan=a1.kode_kunjungan AND a4.diagnosa_awal IS NOT NULL 
                WHERE a1.kode_ruangan='".$no_bed."' 
                ORDER BY a1.kode_riw_klas DESC";

        $query = $this->db2->query($sql);
        return $query->num_rows() > 0 ? $query->row() : false;
    }
*/
    public function check_bed($kode_ruangan, $status_bed) {
        $sql = "SELECT TOP (1) status FROM mt_ruangan WHERE kode_ruangan='".$kode_ruangan."' AND ISNULL(status_bed, 0)=".$status_bed;

        $query = $this->db2->query($sql);
        return $query ? true : false;
    }
    public function update_status_bed($data, $rollback = false) {
        if($rollback) {
            $sql_update_status_bed = "UPDATE mt_ruangan SET status_bed=".$data['status_bed_before']." WHERE kode_ruangan='".$data['kode_ruangan']."'";
        } else {
            $sql_update_status_bed = "UPDATE mt_ruangan SET status_bed=".$data['status_bed_after']." WHERE kode_ruangan='".$data['kode_ruangan']."'";
        }
        
        $query = $this->db2->query($sql_update_status_bed);
        return $query ? TRUE : FALSE;
    }
    public function insert_log_detail($data, $rollback = false) {
        if($rollback) {
            $sql_insert_log_detail = "DELETE FROM bed_log_details WHERE no_registrasi='".$data['no_registrasi']."' AND process=".$data['process'];        
        } else {
            $sql_insert_log_detail = "INSERT INTO bed_log_details (no_registrasi,process,time_end,amount_time,created_by)
                                        VALUES (
                                            '".$data['no_registrasi']."',
                                            '".$data['process']."',
                                            '".$data['time_end']."',
                                            '".$data['amount_time']."',
                                            '".$data['no_induk']."'
                                        )";        
        }
        $query = $this->db2->query($sql_insert_log_detail);
        return $query ? TRUE : FALSE;
    }
    public function update_status_log($data) {
        $sql_update_log = "UPDATE bed_logs SET 
                                        status=1, 
                                        updated_at='".$data['time_end']."', 
                                        updated_by='".$data['no_induk']."' 
                                        WHERE no_registrasi='".$data['no_registrasi']."'";   
        $query = $this->db2->query($sql_update_log);
        return $query ? TRUE : FALSE;
    }
    public function check_log($no_registrasi) {
        $sql = "SELECT id FROM bed_logs WHERE no_registrasi=".$no_registrasi;   
        $query = $this->db2->query($sql);
        return $query ? $query->row() : FALSE;
    }
    public function get_log_detail($no_registrasi, $process) {
        $sql = "SELECT time_start, time_end FROM bed_log_details WHERE no_registrasi='".$no_registrasi."' AND process=".$process;   
        $query = $this->db2->query($sql);
        return $query ? $query->row() : FALSE;
    }
    // public function get_log_detail($no_registrasi, $process) {
    //     $sql = "BEGIN TRANSACTION;";
    //     $sql .= "UPDATE mt_ruangan SET status_bed=".$data['status_bed_after']." WHERE kode_ruangan='".$data['kode_ruangan']."'";
    //                 DELETE FROM HumanResources.JobCandidate  
    //                 WHERE JobCandidateID = 13;   
    //             COMMIT TRANSACTION;";   
    //     $query = $this->db2->query($sql);
    //     return $query ? $query->row() : FALSE;
    // }
}

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_app extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model(array('M_user'));
  }

  function currentBanner(){
    $check = $this->db->get('tb_banner');
    if ($check->num_rows() > 0) {
      return base_url($check->row_array()['banner']);
    } else {
      return base_url('assets/images/banner-contoh.jpg');
    }
  }

  function listKecamatan(){
    return $this->db->get('tb_kecamatan')->result_array();
  }

  function listKelurahan($id_kecamatan = ''){
    if ($id_kecamatan != '') $this->db->where('id_kecamatan', $id_kecamatan);
    return $this->db->get('tb_kelurahan')->result_array();
  }
  
  function getLatestId($key, $table) {
    $this->db->select_max($key);
    $query = $this->db->get($table);
    $result = $query->row_array();
    return $result[$key] + 1;
  }

  function getDataByParameter($parameters, $values, $tables){
    return $this->db->where($parameters, $values)->get($tables);
  }

  function getDataByParameters($parameters, $tables, $order = NULL, $order_mode = "ASC"){
    $this->db->where($parameters);
    if($order != NULL) $this->db->order_by($order, $order_mode);
    return $this->db->get($tables);
  }

  function setAlert($alert_type, $message){
    $this->session->set_flashdata('color', $alert_type);
    $this->session->set_flashdata('message', $message);
  }

  function uploadFile($filename) {
    $check = $this->db->get('tb_banner');
    if ($check->num_rows() > 0) {
      $old_files = $check->row_array()['banner'];
      unlink($old_files);
      return $this->db->update('tb_banner', ['banner' => $filename]);
    } else {
      return $this->db->insert('tb_banner', ['id' => 1,'banner' => $filename]);
    }
  }

  function datetimeNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("Y-m-d H:i:s");
	}

	function timeNow(){
        date_default_timezone_set("Asia/Jakarta");
        return date("H:i:s");
    }

	function millisecondSinceEpoch(){
		return round(microtime(true) * 1000);
	}

	function dateNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("Y-m-d");
	}

	function yearNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("Y");
	}

	function monthNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("m");
  }
  
  function yearMonthNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("Y-m");
	}

	function dayNow() {
		date_default_timezone_set("Asia/Jakarta");
		return date("d");
	}
}
?>
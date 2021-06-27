<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengeluaran extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model('M_item');    
  }
  
  public function get_pengeluaran($id_pengeluaran = '') {
    if ($id_pengeluaran != '') $this->db->where('id', $id_pengeluaran);
    $this->db->where('deleted_at', NULL);
    return $this->db->get('tb_pengeluaran');
  }

  public function insert_pengeluaran($data, $detail_keluar) {
    return $this->db->insert('tb_pengeluaran', $data) && $this->db->insert_batch('tb_pengeluaran_detail', $detail_keluar);
  }

  public function update_pengeluaran($id, $data, $detail_keluar) {
    return $this->db->update('tb_pengeluaran', $data, ['id' => $id]) && $this->db->delete('tb_pengeluaran_detail', ['id_pengeluaran' => $id]) && $this->db->insert_batch('tb_pengeluaran_detail', $detail_keluar);
  }

  public function delete_pengeluaran($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->db->update('tb_pengeluaran', $data, ['id' => $id]);
  }

  public function get_detail_pengeluaran($id_pengeluaran) {
    return $this->db->where('id_pengeluaran', $id_pengeluaran)->get('tb_pengeluaran_detail')->result_array();
  }

}

/* End of file M_pengeluaran.php */
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_item extends CI_Model {

  public function __construct(){
    parent::__construct();
  }
  
  public function get_item($id_item = '', $is_stok_tersedia = false) {
    $this->db->select('ti.*, kategori.keterangan AS kategori, satuan.keterangan AS satuan');
    $this->db->join('tb_general AS kategori', 'ti.id_kategori = kategori.id', 'left');
    $this->db->join('tb_general AS satuan', 'ti.id_satuan = satuan.id', 'left');
    $this->db->where('ti.deleted_at', NULL);
    if ($id_item != '') $this->db->where('ti.id', $id_item);
    if ($is_stok_tersedia) $this->db->where('ti.qty > ', 0);
    return $this->db->get('tb_item AS ti');
  }

  public function tambah_item($data) {
    return $this->db->insert('tb_item', $data);
  }

  public function edit_item($id, $data){
    return $this->db->update('tb_item', $data, ['id' => $id]);
  }

  public function hapus_item($id){
    $data = ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('name')];
    return $this->edit_item($id, $data);
  }

  public function get_item_stock($id) {
    return $this->db->where('id_item', $id)->get('tb_item_stok')->result_array();
  }

  public function tambah_stock($id, $data) {
    $proc = $this->db->insert('tb_item_stok', $data);
    if ($proc == TRUE) {
      $cur_stock = $this->get_item($id)->row_array()['qty'];
      $new_data = [
        'qty' => $cur_stock + $data['stok_masuk'] - $data['stok_keluar'],
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $this->session->userdata('name')
      ];
      $proc = $this->edit_item($id, $new_data);
    }
    return $proc;
  }
}

/* End of file M_item.php */
?>
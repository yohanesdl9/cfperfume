<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pelanggan extends CI_Model {

  public function __construct(){
    parent::__construct();
  }
  
  public function get_pelanggan($id = '') {
    if ($id != '') $this->db->where('id', $id);
    $this->db->where('deleted_at', NULL);
    return $this->db->get('tb_pelanggan');
  }

  public function insert_pelanggan($data) {
    return $this->db->insert('tb_pelanggan', $data);
  }

  public function update_pelanggan($id, $data) {
    return $this->db->update('tb_pelanggan', $data, ['id' => $id]);
  }

  public function delete_pelanggan($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->update_pelanggan($id, $data);
  }

  public function alamat_pelanggan($id_pelanggan, $id = '') {
    if ($id != '') $this->db->where('id', $id);
    $this->db->select('tpa.*, prov.keterangan AS prov, kota.keterangan AS kota, kec.keterangan AS kec, kel.keterangan AS kel, kel.kodepos');
    $this->db->join('tb_provinsi AS prov', 'tpa.id_provinsi = prov.id');
    $this->db->join('tb_kota AS kota', 'tpa.id_kota = kota.id');
    $this->db->join('tb_kecamatan AS kec', 'tpa.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan AS kel', 'tpa.id_kelurahan = kel.id');
    $this->db->where('tpa.id_pelanggan', $id_pelanggan)->where('tpa.deleted_at', NULL);
    return $this->db->get('tb_pelanggan_alamat AS tpa');
  }

  public function insert_pelanggan_alamat($data) {
    return $this->db->insert('tb_pelanggan_alamat', $data);
  }

  public function update_pelanggan_alamat($id, $data) {
    return $this->db->update('tb_pelanggan_alamat', $data, ['id' => $id]);
  }

  public function delete_pelanggan_alamat($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->update_pelanggan_alamat($id, $data);
  }

}

/* End of file M_pelanggan.php */
?>
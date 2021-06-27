<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_toko extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model(array('M_user'));
  }
  
  public function tambah_toko($data) {
    return $this->db->insert('tb_toko', $data);
  }

  public function get_toko($id_toko = '') {
    $this->db->select('tt.*, pro.keterangan AS provinsi, ko.keterangan AS kota, kec.keterangan AS kecamatan, kel.keterangan AS kelurahan, kel.kodepos');
    $this->db->join('tb_provinsi pro', 'tt.id_provinsi = pro.id');
    $this->db->join('tb_kota ko', 'tt.id_kota = ko.id');
    $this->db->join('tb_kecamatan kec', 'tt.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan kel', 'tt.id_kelurahan = kel.id');
    if ($id_toko != '') $this->db->where('tt.id', $id_toko);
    $this->db->where('tt.deleted_at', NULL);
    return $this->db->get('tb_toko tt');
  }

  public function edit_toko($id, $data){
    return $this->db->update('tb_toko', $data, ['id' => $id]);
  }

  public function delete_toko($id){
    $data = ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('name')];
    return $this->edit_toko($id, $data);
  }
}

/* End of file M_toko.php */
?>
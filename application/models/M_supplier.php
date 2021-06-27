<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_supplier extends CI_Model {

  public function __construct(){
    parent::__construct();
    $this->load->model(array('M_user'));
  }
  
  public function tambah_supplier($data) {
    return $this->db->insert('tb_supplier', $data);
  }

  public function get_supplier($id_toko = '') {
    $this->db->select('tt.*, pro.keterangan AS provinsi, ko.keterangan AS kota, kec.keterangan AS kecamatan, kel.keterangan AS kelurahan, kel.kodepos');
    $this->db->join('tb_provinsi pro', 'tt.id_provinsi = pro.id');
    $this->db->join('tb_kota ko', 'tt.id_kota = ko.id');
    $this->db->join('tb_kecamatan kec', 'tt.id_kecamatan = kec.id');
    $this->db->join('tb_kelurahan kel', 'tt.id_kelurahan = kel.id');
    if ($id_toko != '') $this->db->where('tt.id', $id_toko);
    $this->db->where('tt.deleted_at', NULL);
    return $this->db->get('tb_supplier tt');
  }

  public function edit_supplier($id, $data){
    return $this->db->update('tb_supplier', $data, ['id' => $id]);
  }

  public function delete_supplier($id){
    $data = ['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('name')];
    return $this->edit_supplier($id, $data);
  }
}

/* End of file M_supplier.php */
?>
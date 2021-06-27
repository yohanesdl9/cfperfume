<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tipe extends CI_Model {

  public function __construct(){
    parent::__construct();
  }
  
  public function get_tipe($id_tipe = ''){
    if ($id_tipe != '') $this->db->where('id', $id_tipe);
    $this->db->where('deleted_at', NULL);
    return $this->db->get('tb_tipe');
  }

  public function insert_tipe($data){
    return $this->db->insert('tb_tipe', $data);
  }

  public function update_tipe($id, $data){
    return $this->db->update('tb_tipe', $data, ['id' => $id]);
  }

  public function delete_tipe($id){
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->update_tipe($id, $data);
  }

  public function get_general($id_tipe, $id_general = ''){
    if ($id_general != '') $this->db->where('id', $id_general);
    $this->db->where('id_tipe', $id_tipe)->where('deleted_at', NULL);
    return $this->db->get('tb_general');
  }

  public function insert_general($data){
    return $this->db->insert('tb_general', $data);
  }

  public function update_general($id, $data){
    return $this->db->update('tb_general', $data, ['id' => $id]);
  }

  public function delete_general($id){
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->update_general($id, $data);
  }
}

/* End of file M_tipe.php */
?>
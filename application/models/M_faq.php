<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_faq extends CI_Model {
  
  public function __construct(){
    parent::__construct();
  }
  
  public function get_faq($id = '') {
    if ($id != '') $this->db->where('id', $id);
    $this->db->where('deleted_at', NULL);
    return $this->db->get('tb_faq');
  }

  public function insert_faq($data) {
    return $this->db->insert('tb_faq', $data);
  }

  public function update_faq($id, $data) {
    return $this->db->update('tb_faq', $data, ['id' => $id]);
  }

  public function delete_faq($id) {
    $data = [
      'deleted_at' => date('Y-m-d H:i:s'),
      'deleted_by' => $this->session->userdata('name')
    ];
    return $this->update_faq($id, $data);
  }
}

/* End of file M_faq.php */
?>
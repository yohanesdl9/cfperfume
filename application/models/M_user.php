<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

  public function __construct(){
    parent::__construct();
  }
  
  public function get_user($id_user = ''){
    $this->db->select('cu.*, cp.`name` AS `privileges`, tt.nama_toko');
    $this->db->join('cms_privileges AS cp', 'cu.id_cms_privileges = cp.id');
    $this->db->join('tb_toko AS tt', 'cu.id_toko = tt.id', 'left');
    $this->db->where('cu.deleted_at', NULL)->where('cp.deleted_at', NULL)->where('tt.deleted_at', NULL);
    if ($id_user != '') $this->db->where('cu.id', $id_user);
    return $this->db->get('cms_users AS cu');
  }

  public function insert_user($data){
    return $this->db->insert('cms_users', $data);
  }

  public function update_user($id, $data){
    return $this->db->update('cms_users', $data, ['id' => $id]);
  }

  public function delete_user($id){
    $data = ['deleted_at' => date('Y-m-d H:i:s')];
    return $this->update_user($id, $data);
  }
}

/* End of file M_user.php */
?>
 
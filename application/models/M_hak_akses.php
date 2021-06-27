<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hak_akses extends CI_Model {

  public function __construct(){
    parent::__construct();
  }

  public function get_hak_akses_menu($id_menu){
    $query = "SELECT cp.id, cp.`name`, main.id_cms_menus AS id_menu, main.is_create, main.is_read, main.is_edit, main.is_delete FROM
    (SELECT cm.`name`, cpr.* FROM cms_menus AS cm
    INNER JOIN cms_privileges_roles AS cpr ON cpr.id_cms_privileges = cm.id WHERE cpr.id_cms_menus = $id_menu) as main
    RIGHT JOIN cms_privileges AS cp ON main.id_cms_privileges = cp.id
    WHERE cp.deleted_at IS NULL";
    return $this->db->query($query);
  }
  
  public function get_hak_akses($id = ''){
    if ($id != '') $this->db->where('id', $id);
    $this->db->where('deleted_at', NULL);
    return $this->db->get('cms_privileges');
  }

  public function insert_hak_akses($data){
    return $this->db->insert('cms_privileges', $data);
  }

  public function update_hak_akses($id, $data){
    return $this->db->update('cms_privileges', $data, ['id' => $id]);
  }

  public function delete_hak_akses($id){
    $data = ['deleted_at' => date('Y-m-d H:i:s')];
    return $this->update_hak_akses($id, $data);
  }

  public function get_hak_akses_by_privileges($id = ''){
    $query = "SELECT id, `name` FROM cms_menus WHERE is_dashboard = 0 AND id NOT IN (SELECT parent_id FROM cms_menus WHERE parent_id <> 0) AND deleted_at IS NULL";
    $result = $this->db->query($query)->result_array();
    if ($id != ''){
      foreach ($result as $res){
        $this->db->select('*');
        $this->db->where('id_cms_menus', $res['id'])->where('id_cms_privileges', $id);
        $data = $this->db->get('cms_privileges_roles')->row_array();
        $res['id_cms_privileges_roles'] = $data['id'];
        $res['is_create'] = isset($data['is_create']) ? $data['is_create'] : 0;
        $res['is_read'] = isset($data['is_read']) ? $data['is_read'] : 0;
        $res['is_edit'] = isset($data['is_edit']) ? $data['is_edit'] : 0;
        $res['is_delete'] = isset($data['is_delete']) ? $data['is_delete'] : 0;
      }
    }
    return $result;
  }

  public function insert_hak_akses_menu($privileges_roles, $menus_privileges){
    $proc_1 = $this->db->insert_batch('cms_privileges_roles', $privileges_roles);
    $proc_2 = $this->db->insert_batch('cms_menus_privileges', $menus_privileges);
    return $proc1 && $proc2;
  }
}
/* End of file M_hak_akses.php */
?>
 
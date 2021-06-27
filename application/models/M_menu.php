<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends CI_Model {
  
  public function __construct(){
    parent::__construct();
  }

  function get_item_menu_by_access_rights($id_cms_privileges, $childID = 0){
    $this->db->select("cms_menus.*");
    $this->db->join("cms_menus_privileges", "cms_menus_privileges.id_cms_menus = cms_menus.id");
    $this->db->join("cms_privileges", "cms_menus_privileges.id_cms_privileges = cms_privileges.id");
    $this->db->where("cms_menus_privileges.id_cms_privileges", $id_cms_privileges);
    $this->db->where("cms_menus.parent_id", $childID);
    $this->db->where("cms_menus.deleted_at", NULL);
    $this->db->where("cms_menus.is_active", 1);
    $this->db->group_by("cms_menus.id");
    $this->db->order_by("sorting", "ASC");
    $sql = $this->db->get("cms_menus");

    if ($sql->num_rows() == 0) {
      return [];
    } else {
      foreach ($sql->result_array() as $row) {
        $row['child'] = $this->get_item_menu_by_access_rights($id_cms_privileges, $row['id']);
        $output[] = $row;
      }
      return $output;
    }
  }

  function get_item_menu($childID = 0, $is_active = 1) {
    $this->db->select("cms_menus.*, GROUP_CONCAT(cms_privileges.name) AS privileges_names");
    $this->db->join("cms_menus_privileges", "cms_menus_privileges.id_cms_menus = cms_menus.id", "LEFT");
    $this->db->join("cms_privileges", "cms_menus_privileges.id_cms_privileges = cms_privileges.id", "LEFT");
    $this->db->where("cms_menus.parent_id", $childID);
    $this->db->where("cms_menus.deleted_at", NULL);
    $this->db->where("cms_menus.is_active", $is_active);
    $this->db->group_by("cms_menus.id");
    $this->db->order_by("sorting", "ASC");
    $sql = $this->db->get("cms_menus");

    if ($sql->num_rows() == 0) {
      return [];
    } else {
      foreach ($sql->result_array() as $row) {
        $row['child'] = $this->get_item_menu($row['id']);
        $output[] = $row;
      }
      return $output;
    }
  }

  public function get_menu($id){
    $this->db->select('cm.`name`, cm.id, cm.path, cm.icon, cm.parent_id, cm.is_active, cm.is_dashboard, GROUP_CONCAT(cmp.id_cms_privileges) AS cms_privileges');
    $this->db->join('cms_menus_privileges AS cmp', 'cmp.id_cms_menus = cm.id');
    $this->db->where('cm.id', $id);
    return $this->db->get('cms_menus AS cm')->row_array();
  }

  public function get_all_icons(){
    return $this->db->get('tb_icon')->result_array();
  }
  
  public function insert_menu($data, $privileges){
    $proc = $this->db->insert('cms_menus', $data);
    $proc_priv = $this->db->insert_batch('cms_menus_privileges', $privileges);
    return $proc && $proc_priv;
  }

  public function update_menu_only($id, $data){
    return $this->db->update('cms_menus', $data, ['id' => $id]);
  }

  public function update_menu_with_privileges($id, $data, $privileges){
    $proc = $this->db->update('cms_menus', $data, ['id' => $id]);
    $this->db->delete('cms_menus_privileges', ['id_cms_menus' => $id]);
    $proc_priv = $this->db->insert_batch('cms_menus_privileges', $privileges);
    return $proc && $proc_priv;
  }

  public function delete_menu($id){
    $menu_child = $this->M_app->getDataByParameter('parent_id', $id, 'cms_menus')->result_array();
    foreach ($menu_child as $mc) {
      $this->update_menu_only($mc['id'], ['parent_id' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
    }
    $data = ['deleted_at' => date('Y-m-d H:i:s')];
    return $this->update_menu_only($id, $data);
  }

  public function checkAccessRightExists($id_menu){
    $check = $this->db->where('id_cms_menus', $id_menu)->get('cms_privileges_roles');
    if ($check->num_rows() > 0){
      return $this->db->delete('cms_privileges_roles', ['id_cms_menus' => $id_menu]);
    } else {
      return false;
    }
  }

  public function insertMenuAccessRights($data){
    return $this->db->insert_batch('cms_privileges_roles', $data);
  }

  public function get_item_menu_access_rights($id_cms_menus, $id_cms_privileges){
    $this->db->where('id_cms_menus', $id_cms_menus)->where('id_cms_privileges', $id_cms_privileges);
    return $this->db->get('cms_privileges_roles')->row();
  }
}
?>
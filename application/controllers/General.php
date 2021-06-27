<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

  protected $menu;

  public function __construct(){
    parent::__construct();
    $this->load->model(array('M_app'));
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
  }
  
  public function kota($id_provinsi){
    $data = $this->M_app->getDataByParameters(['kode_provinsi' => $id_provinsi, 'deleted_at' => NULL], 'tb_kota')->result_array();
    echo json_encode($data);
  }

  public function kecamatan($id_kota){
    $data = $this->M_app->getDataByParameters(['kode_kota' => $id_kota, 'deleted_at' => NULL], 'tb_kecamatan')->result_array();
    echo json_encode($data);
  }

  public function kelurahan($id_kecamatan){
    $data = $this->M_app->getDataByParameters(['kode_kecamatan' => $id_kecamatan, 'deleted_at' => NULL], 'tb_kelurahan')->result_array();
    echo json_encode($data);
  }

  public function nested_dropdown($table, $fk_name, $fk_value) {
    echo json_encode($this->M_app->getDataByParameter($fk_name, $fk_value, $table)->result_array());
  }
}

/* End of file General.php */
 ?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

  protected $choice_is_active;
  protected $choice_is_dashboard;
  protected $menu;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->choice_is_active = [1 => 'Aktif', 0 => 'Nonaktif'];
    $this->choice_is_dashboard = [1 => 'Ya', 0 => 'Tidak'];
    $this->load->model(array('M_hak_akses'));
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
  }

  public function index(){
    $menus = []; $icons = [];
    foreach ($this->M_menu->get_item_menu() as $menu) $menus[$menu['id']] = $menu['name'];
    foreach ($this->M_menu->get_all_icons() as $icon) $icons[$icon['icon']] = $icon['icon'];

    $form = [
      ['label' => 'Hak Akses*', 'label_width' => 'col-md-3', 'name' => 'privileges[]', 'type' => 'select', 'datatable' => 'cms_privileges,name', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control select2 select2-multiple', 'required' => true, 'multiple' => 'true']],
      ['label' => 'Nama Menu*', 'label_width' => 'col-md-3', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'id' => 'name']],
      ['label' => 'Icon*', 'label_width' => 'col-md-3', 'name' => 'icon', 'type' => 'select', 'width' => 'col-md-9', 'dataenum' => $icons, 'attributes' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'required' => true]],
      ['label' => 'URL*', 'label_width' => 'col-md-3', 'name' => 'path', 'type' => 'text', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'id' => 'path']],
      ['label' => 'Menu Parent*', 'label_width' => 'col-md-3', 'name' => 'menu_parent', 'type' => 'select', 'width' => 'col-md-9', 'dataenum' => $menus, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Menu Parent']],
      ['label' => 'Menu Aktif*', 'label_width' => 'col-md-3', 'name' => 'active', 'type' => 'radio', 'width' => 'col-md-9', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => 0],
      ['label' => 'Dashboard*', 'label_width' => 'col-md-3', 'name' => 'dashboard', 'type' => 'radio', 'width' => 'col-md-9', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => 0],
    ];

    $this->load->view('template/index', [
      'allmenu' => $this->M_menu->get_item_menu(),
      'inactivemenu' => $this->M_menu->get_item_menu(0, 0),
      'title' => 'Manajemen Menu',
      'content' => 'menu/index',
      'menu' => $this->menu,
      'form' => $form
    ]);
  }

  public function edit($id){
    $menu_edit = $this->M_menu->get_menu($id);

    $menus = []; $icons = [];
    foreach ($this->M_menu->get_item_menu() as $menu) $menus[$menu['id']] = $menu['name'];
    foreach ($this->M_menu->get_all_icons() as $icon) $icons[$icon['icon']] = $icon['icon'];

    $form = [
      ['label' => 'Hak Akses*', 'label_width' => 'col-md-3', 'name' => 'privileges[]', 'type' => 'select', 'datatable' => 'cms_privileges,name', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control select2 select2-multiple', 'required' => true, 'multiple' => 'true'], 'value' => explode(',', $menu_edit['cms_privileges'])],
      ['label' => 'Nama Menu*', 'label_width' => 'col-md-3', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'id' => 'name'], 'value' => $menu_edit['name']],
      ['label' => 'Icon*', 'label_width' => 'col-md-3', 'name' => 'icon', 'type' => 'select', 'width' => 'col-md-9', 'dataenum' => $icons, 'attributes' => ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'required' => true], 'value' => $menu_edit['icon']],
      ['label' => 'URL*', 'label_width' => 'col-md-3', 'name' => 'path', 'type' => 'text', 'width' => 'col-md-9', 'attributes' => ['class' => 'form-control', 'id' => 'path'], 'value' => $menu_edit['path']],
      ['label' => 'Menu Parent*', 'label_width' => 'col-md-3', 'name' => 'menu_parent', 'type' => 'select', 'width' => 'col-md-9', 'dataenum' => $menus, 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Menu Parent'], $menu_edit['parent_id']],
      ['label' => 'Menu Aktif*', 'label_width' => 'col-md-3', 'name' => 'active', 'type' => 'radio', 'width' => 'col-md-9', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => $menu_edit['is_active']],
      ['label' => 'Dashboard*', 'label_width' => 'col-md-3', 'name' => 'dashboard', 'type' => 'radio', 'width' => 'col-md-9', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => $menu_edit['is_dashboard']],
    ];

    $this->load->view('template/index', [
      'hak_akses' => $this->M_hak_akses->get_hak_akses()->result_array(),
      'menu_edit' => $this->M_menu->get_menu($id),
      'title' => 'Edit Menu',
      'content' => 'menu/edit',
      'menu' => $this->menu,
      'form' => $form
    ]);
  }

  public function hak_akses($id){
    $menu = $this->M_menu->get_menu($id);
    $this->load->view('template/index', [
      'hak_akses' => $this->M_hak_akses->get_hak_akses_menu($id)->result_array(),
      'menu' => $this->menu,
      'title' => 'Hak Akses Menu ' . $menu['name'],
      'content' => 'menu/hak_akses'
    ]);
  }

  public function tambah_menu(){
    $id = $this->M_app->getLatestId('id', 'cms_menus');
    $privileges = $this->input->post('privileges');
    $data = [
      'name' => $this->input->post('name'),
      'path' => $this->input->post('path'),
      'icon' => $this->input->post('icon'),
      'parent_id' => $this->input->post('menu_parent') ? $this->input->post('menu_parent') : NULL,
      'is_active' => $this->input->post('active'),
      'is_dashboard' => $this->input->post('dashboard'),
      'sorting' => $id,
      'created_at' => date('Y-m-d H:i:s')
    ];
    $data_privileges = [];
    for ($i = 0; $i < count($privileges); $i++){
      $data_privileges[] = ['id_cms_menus' => $id, 'id_cms_privileges' => $privileges[$i]];
    }
    $proc = $this->M_menu->insert_menu($data, $data_privileges);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Menu berhasil ditambahkan!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan menu. Terjadi kesalahan.');
    }
    redirect('menu');
  }

  public function update_menu($id){
    $privileges = $this->input->post('privileges');
    $data = [
      'name' => $this->input->post('name'),
      'path' => $this->input->post('path'),
      'icon' => $this->input->post('icon'),
      'parent_id' => $this->input->post('menu_parent') ? $this->input->post('menu_parent') : NULL,
      'is_active' => $this->input->post('active'),
      'is_dashboard' => $this->input->post('dashboard'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    $data_privileges = [];
    for ($i = 0; $i < count($privileges); $i++){
      $data_privileges[] = ['id_cms_menus' => $id, 'id_cms_privileges' => $privileges[$i]];
    }
    $proc = $this->M_menu->update_menu_with_privileges($id, $data, $data_privileges);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Menu berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah menu. Terjadi kesalahan.');
    }
    redirect('menu');
  }

  public function hapus_menu($id){
    $proc = $this->M_menu->delete_menu($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Menu berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus menu. Terjadi kesalahan.');
    }
    redirect('menu');
  }

  function save() {
    $data = json_decode($this->input->post('data'));
    $readableArray = $this->parseJsonArray($data);
    $i = 0;
    foreach ($readableArray as $row) {
      $i++;
      $data = [
        'parent_id' => $row['parentID'],
        'sorting' => $i,
        'updated_at' => date('Y-m-d H:i:s')
      ];
      $this->M_menu->update_menu_only($row['id'], $data);
    }
  }

  function parseJsonArray($jsonArray, $parentID = 0) {
    $return = array();
    foreach ($jsonArray as $subArray) {
      $returnSubSubArray = array();
      if (isset($subArray->children)) {
        $returnSubSubArray = $this->parseJsonArray($subArray->children, $subArray->id);
      }
      $return[] = array('id' => $subArray->id, 'parentID' => $parentID);
      $return = array_merge($return, $returnSubSubArray);
    }
    return $return;
  }

  public function atur_hak_akses($id_menu){
    $id_role = $this->input->post('id_privileges');
    $hak_akses = [];
    $this->M_menu->checkAccessRightExists($id_menu);
    for ($i = 0; $i < count($id_role); $i++){
      $create = $this->input->post('create_' . $id_role[$i]);
      $read = $this->input->post('read_' . $id_role[$i]);
      $update = $this->input->post('update_' . $id_role[$i]);
      $delete = $this->input->post('delete_' . $id_role[$i]);
      $hak_akses[] = [
        'id_cms_menus' => $id_menu, 
        'id_cms_privileges' => $id_role[$i], 
        'is_create' => $create ? $create : 0, 
        'is_read' => $read ? $read : 0,
        'is_edit' => $update ? $update : 0,
        'is_delete' => $delete ? $delete : 0,
        'created_at' => date('Y-m-d H:i:s')
      ];
    }
    if (count($hak_akses) > 0){
      $this->M_menu->insertMenuAccessRights($hak_akses);
    }
    $this->M_app->setAlert('success', 'Hak akses user berhasil diubah.');
    redirect('menu/hak_akses/' . $id_menu);
  }
}
/* End of file Menu.php */
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Hak_akses extends CI_Controller {

  protected $choice;
  protected $menu;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->choice = [1 => 'Ya', 0 => 'Tidak'];
    $this->load->model('M_hak_akses');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
  }
  
  public function index(){
    $this->load->view('template/index', [
      'hak_akses' => $this->M_hak_akses->get_hak_akses()->result_array(),
      'title' => 'Detail Hak Akses',
      'content' => 'hak_akses/index',
      'menu' => $this->menu
    ]);
  }

  public function add(){
    $form = [
      ['label' => 'Privilege Name*', 'label_width' => 'col-md-2', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'required' => true]], 
      ['label' => 'Set as Superadmin*', 'label_width' => 'col-md-2', 'name' => 'is_superadmin', 'type' => 'radio', 'width' => 'col-md-10', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => 0],
      ['label' => 'Set as Root*', 'label_width' => 'col-md-2', 'name' => 'is_root', 'type' => 'radio', 'width' => 'col-md-10', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => 0],
    ];

    $this->load->view('template/index', [
      'title' => 'Tambah Hak Akses',
      'content' => 'hak_akses/tambah',
      'detail_hak_akses' => $this->M_hak_akses->get_hak_akses_by_privileges(),
      'menu' => $this->menu,
      'form' => $form
    ]);
  }

  public function edit($id){
    $hak_akses = $this->M_hak_akses->get_hak_akses($id)->row_array();
    $form = [
      ['label' => 'Privilege Name*', 'label_width' => 'col-md-2', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-10', 'attributes' => ['class' => 'form-control', 'required' => true], 'value' => $hak_akses['name']], 
      ['label' => 'Set as Superadmin*', 'label_width' => 'col-md-2', 'name' => 'is_superadmin', 'type' => 'radio', 'width' => 'col-md-10', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => $hak_akses['is_superadmin']],
      ['label' => 'Set as Root*', 'label_width' => 'col-md-2', 'name' => 'is_root', 'type' => 'radio', 'width' => 'col-md-10', 'inline' => true, 'dataenum' => ['1' => 'Ya', '0' => 'Tidak'], 'attributes' => ['class' => 'form-check-input'], 'value' => $hak_akses['is_root']],
    ];

    $this->load->view('template/index', [
      'title' => 'Edit Hak Akses',
      'content' => 'forms',
      'detail_hak_akses' => $this->M_hak_akses->get_hak_akses_by_privileges($id),
      'menu' => $this->menu,
      'form' => create_form('hak_akses/update_hak_akses/' . $id, $form),
      'back_text' => 'Kembali ke halaman Hak Akses',
      'base_url' => 'hak_akses'
    ]);
  }

  public function tambah_hak_akses(){
    $id = $this->M_app->getLatestId('id', 'cms_privileges');
    $hak_akses = [];
    $menu_akses = [];
    $menu = $this->input->post('id_menu');
    for ($i = 0; $i < count($menu); $i++){
      $is_create = $this->input->post('is_create[' . $menu[$i] . ']');
      $is_read = $this->input->post('is_read[' . $menu[$i] . ']');
      $is_edit = $this->input->post('is_edit[' . $menu[$i] . ']');
      $is_delete = $this->input->post('is_delete[' . $menu[$i] . ']');
      if ($is_create == 1 || $is_read == 1 || $is_edit == 1 || $is_delete == 1) {
        $hak_akses[] = [
          'is_create' => $is_create,
          'is_read' => $is_read,
          'is_edit' => $is_edit,
          'is_delete' => $is_delete,
          'id_cms_menus' => $menu[$i],
          'id_cms_privileges' => $id,
        ];
        $menu_akses[] = [
          'id_cms_menus' => $menu[$i],
          'id_cms_privileges' => $id,
        ];
      }
    }
    $data = [
      'id' => $id,
      'name' => $this->input->post('name'),
      'is_superadmin' => $this->input->post('is_superadmin'),
      'is_root' => $this->input->post('is_root'),
      'created_at' => date('Y-m-d H:i:s')
    ];
    $proc = $this->M_hak_akses->insert_hak_akses($data);
    if (count($hak_akses) > 0 && count($menu_akses) > 0) {
      $proc_1 = $this->M_hak_akses->insert_hak_akses_menu($hak_akses, $menu_akses);
    }
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil menambahkan hak akses.');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('hak_akses/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan hak akses. Terjadi kesalahan.');
    }
    redirect('hak_akses');
  }

  public function update_hak_akses($id){
    $data = [
      'name' => $this->input->post('name'),
      'is_superadmin' => $this->input->post('is_superadmin'),
      'is_root' => $this->input->post('is_root'),
      'created_at' => date('Y-m-d H:i:s')
    ];
    $proc = $this->M_hak_akses->update_hak_akses($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil mengubah hak akses.');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah hak akses. Terjadi kesalahan.');
    }
    redirect('hak_akses');
  }

  public function hapus_hak_akses($id){
    $proc = $this->M_hak_akses->delete_hak_akses($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil menghapus hak akses.');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus hak akses. Terjadi kesalahan.');
    }
    redirect('hak_akses');
  }

}

/* End of file Hak_akses.php */
 ?>
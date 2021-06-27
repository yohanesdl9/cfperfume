<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  protected $menu;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->library('image_lib');
    $this->load->model(array('M_user', 'M_toko', 'M_hak_akses'));
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
  }

  public function index(){
    $this->load->view('template/index', [
      'user' => $this->M_user->get_user()->result_array(),
      'title' => 'Manajemen User',
      'content' => 'user/index',
      'menu' => $this->menu
    ]);
  }

  public function add(){
    $form = [
      ['label' => 'Nama', 'label_width' => 'col-md-1', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'required' => true]], 
      ['label' => 'Username', 'label_width' => 'col-md-1', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'required' => true]],
      ['label' => 'Password', 'label_width' => 'col-md-1', 'name' => 'password', 'type' => 'password', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'required' => true]],
      ['label' => 'Privilege', 'label_width' => 'col-md-1', 'name' => 'id_cms_privileges', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'cms_privileges,name', 'attributes' => ['class' => 'form-control select2', 'required' => true]],
      ['label' => 'Toko', 'label_width' => 'col-md-1', 'name' => 'id_toko', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'tb_toko,nama_toko', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Toko', 'required' => true]]
    ];

    $this->load->view('template/index', [
      'title' => 'Tambah User',
      'content' => 'forms',
      'menu' => $this->menu,
      'form' => create_form('user/tambah_user', $form, true),
      'back_text' => 'Kembali ke Halaman User',
      'base_url' => 'user'
    ]);
  }

  public function edit($id){
    $user = $this->M_user->get_user($id)->row_array();

    $form = [
      ['label' => 'Nama', 'label_width' => 'col-md-1', 'name' => 'name', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'required' => true], 'value' => $user['name']], 
      ['label' => 'Username', 'label_width' => 'col-md-1', 'name' => 'email', 'type' => 'text', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'required' => true], $user['email']],
      ['label' => 'Password', 'label_width' => 'col-md-1', 'name' => 'password', 'type' => 'password', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control']],
      ['label' => 'Privilege', 'label_width' => 'col-md-1', 'name' => 'id_cms_privileges', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'cms_privileges,name', 'attributes' => ['class' => 'form-control select2', 'required' => true], 'value' => $user['id_cms_privileges']],
      ['label' => 'Toko', 'label_width' => 'col-md-1', 'name' => 'id_toko', 'type' => 'select', 'width' => 'col-md-11', 'datatable' => 'tb_toko,nama_toko', 'attributes' => ['class' => 'form-control select2', 'placeholder' => 'Pilih Toko', 'required' => true], 'value' => $user['id_toko']]
    ];

    $this->load->view('template/index', [
      'title' => 'Edit User',
      'content' => 'forms',
      'menu' => $this->menu,
      'form' => create_form('user/update_user/' . $id, $form, false),
      'back_text' => 'Kembali ke Halaman User',
      'base_url' => 'user'
    ]);
  }

  public function tambah_user(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'cms_users'),
      'name' => $this->input->post('name'),
      'email' => $this->input->post('email'),
      'id_toko' => $this->input->post('id_toko'),
      'id_cms_privileges' => $this->input->post('id_cms_privileges'),
      'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
      'created_at' => date('Y-m-d H:i:s')
    ];
    $proc = $this->M_user->insert_user($data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil menambahkan user.');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('user/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan user. Terjadi kesalahan.');
    }
    redirect('user');
  }

  public function update_user($id){
    $data = [
      'name' => $this->input->post('name'),
      'email' => $this->input->post('email'),
      'id_toko' => $this->input->post('id_toko'),
      'id_cms_privileges' => $this->input->post('id_cms_privileges'),
      'created_at' => date('Y-m-d H:i:s')
    ];
    if ($this->input->post('password')) $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
    $proc = $this->M_user->update_user($id, $data);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil mengubah user.');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah user. Terjadi kesalahan.');
    }
    redirect('user');
  }

  public function hapus_user($id){
    $proc = $this->M_user->delete_user($id);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil menghapus user.');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus user. Terjadi kesalahan.');
    }
    redirect('user');
  }

  public function profile(){
    $this->load->view('template/index', [
      'user' => $this->M_user->get_user($this->session->userdata('id'))->row_array(),
      'title' => 'Profil Pengguna',
      'content' => 'user/profile',
      'menu' => $this->menu
    ]);
  }

  public function ubah_profile(){
    $password = $this->input->post('password');
    if ($password) {
      $data = [
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'updated_at' => date('Y-m-d H:i:s')
      ];
      $proc = $this->M_user->update_user($this->session->userdata('id'), $data);
      if ($proc == TRUE){
        $this->M_app->setAlert('success', 'Berhasil mengubah profil.');
      } else {
        $this->M_app->setAlert('danger', 'Gagal mengubah profil. Terjadi kesalahan.');
      }
    }
    redirect('user/profile');
  }

  public function change_photo(){
    $config = [
      'upload_path' => './uploads/',
      'allowed_types' => 'png|jpg|jpeg|gif|jfif',
      'encrypt_name' => true,
      'max_sizes' => 2048
    ];
    $this->upload->initialize($config);
    list($width, $height, $type, $attr) = getimagesize($_FILES['profile']['tmp_name']);
    if ($width != $height){
      $config['source_image'] = $_FILES['profile']['tmp_name'];
      $config['x_axis'] = ($width-min($width, $height))/2;
      $config['y_axis'] = ($height-min($width, $height))/2;
      $config['maintain_ratio'] = FALSE;
      $config['width'] = min($width, $height);
      $config['height'] = min($width, $height);
      $this->image_lib->initialize($config);
      $this->image_lib->crop();
    }
    $old_files = $this->M_app->getDataByParameter('id', $this->session->userdata('id'), 'cms_users')->row();
    if ($this->upload->do_upload('profile')){
      $profile_picture = 'uploads/' . $this->upload->data('file_name');
      $data = ['photo' => $profile_picture, 'updated_at' => date('Y-m-d H:i:s')];
      $proc = $this->M_user->update_user($this->session->userdata('id'), $data);
      if ($proc == TRUE){
        if (isset($old_files->photo)) unlink($old_files->photo);
        $this->session->set_userdata('photo', $profile_picture);
        $this->M_app->setAlert('success', "Foto profil berhasil diubah");
      } else {
        $this->M_app->setAlert('danger', "Gagal, terjadi kesalahan saat mengubah foto profil");
      }
    }
    redirect('user/profile');
  }
}

/* End of file User.php */
 ?>
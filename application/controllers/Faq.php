<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

  protected $menu;
  protected $id_module;
  protected $access;

  public function __construct(){
    parent::__construct();
    if (!$this->session->has_userdata('id')){
      redirect('login');
    }
    $this->load->model('M_faq');
    $this->menu = $this->M_menu->get_item_menu_by_access_rights($this->session->userdata('id_privileges'));
    $this->id_module = 10;
    $this->access = $this->M_menu->get_item_menu_access_rights($this->id_module, $this->session->userdata('id_privileges'));
  }
  
	public function index(){
		$this->load->view('template/index', [
      'faq' => $this->M_faq->get_faq()->result_array(),
      'title' => 'FAQ',
      'content' => 'faq',
      'menu' => $this->menu,
      'access' => $this->access
    ]);
  }

  public function add() {
    $form = [
      ['label' => 'Pertanyaan', 'label_width' => 'col-md-1', 'name' => 'question', 'type' => 'textarea', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'rows' => 5]],
      ['label' => 'Jawaban', 'label_width' => 'col-md-1', 'name' => 'answer', 'type' => 'textarea', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'rows' => 10]],
    ];
    $this->load->view('template/index', [
      'form' => create_form('faq/tambah_faq', $form, true),
      'title' => 'Tambah FAQ',
      'content' => 'forms',
      'menu' => $this->menu,
      'back_text' => 'Kembali ke halaman FAQ',
      'base_url' => 'faq'
    ]);
  }

  public function tambah_faq(){
    $data = [
      'id' => $this->M_app->getLatestId('id', 'tb_faq'),
      'question' => $this->input->post('question'),
      'answer' => $this->input->post('answer'),
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_faq->insert_faq($data);
    if ($proc == TRUE) {
      $this->M_app->setAlert('success', 'Data berhasil ditambahkan!');
      if ($this->input->post('submit') == 'Simpan & Tambah Lagi') redirect('faq/add');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menambahkan data. Terjadi kesalahan');
    }
    redirect('faq');
  }

  public function edit($id) {
    $faq = $this->M_faq->get_faq($id)->row_array();
    $form = [
      ['label' => 'Pertanyaan', 'label_width' => 'col-md-1', 'name' => 'question', 'type' => 'textarea', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'rows' => 5], 'value' => $faq['question']],
      ['label' => 'Jawaban', 'label_width' => 'col-md-1', 'name' => 'answer', 'type' => 'textarea', 'width' => 'col-md-11', 'attributes' => ['class' => 'form-control', 'rows' => 10], 'value' => $faq['answer']],
    ];
    $this->load->view('template/index', [
      'form' => create_form('faq/edit_faq/' . $id, $form),
      'title' => 'Tambah FAQ',
      'content' => 'forms',
      'menu' => $this->menu,
      'back_text' => 'Kembali ke halaman FAQ',
      'base_url' => 'faq'
    ]);
  }

  public function edit_faq($id){
    $data = [
      'question' => $this->input->post('question'),
      'answer' => $this->input->post('answer'),
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('name')
    ];
    $proc = $this->M_faq->update_faq($id, $data);
    if ($proc == TRUE) {
      $this->M_app->setAlert('success', 'Data berhasil diubah!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal mengubah data. Terjadi kesalahan');
    }
    redirect('faq');
  }

  public function hapus_faq($id) {
    $proc = $this->M_faq->delete_faq($id);
    if ($proc == TRUE) {
      $this->M_app->setAlert('success', 'Data berhasil dihapus!');
    } else {
      $this->M_app->setAlert('danger', 'Gagal menghapus data. Terjadi kesalahan');
    }
    redirect('faq');
  }
}

/* End of file Faq.php */
?>
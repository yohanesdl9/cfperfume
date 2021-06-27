<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('M_auth');
  }

	public function index(){
		$this->load->view('template/login', [
      'title' => 'Beautica Store - Login',
      'content' => 'login/index'
    ]);
  }

  public function forgot_password(){
		$this->load->view('template/login', [
      'title' => 'Beautica Store - Lupa Password',
      'content' => 'login/forgot_password'
    ]);
  }

  public function reset(){
    $proc = $this->M_auth->forgot_password($this->input->post('email'));
    if ($proc == TRUE) {
      $this->session->set_userdata('resetted_acc', $this->input->post('email'));
      $this->load->view('template/login', [
        'title' => 'Beautica Store - Reset Password',
        'content' => 'login/reset_password'
      ]);
    } else {
      $this->M_app->setAlert('danger', 'Username tidak terdaftar pada sistem');
      redirect('login/forgot_password');
    }
  }

  public function reset_password(){
    $password = $this->input->post('password');
    $email = $this->session->userdata('resetted_acc');
    $proc = $this->M_auth->reset_password($email, $password);
    if ($proc == TRUE){
      $this->M_app->setAlert('success', 'Berhasil me-reset password. Silahkan login.');
    }
    redirect('login');
  }

  public function auth(){
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $check = $this->M_auth->login($email, $password);
    if ($check['status'] == TRUE){
      $this->session->set_userdata([
        'id' => $check['value']->id,
        'name' => $check['value']->name,
        'photo' => $check['value']->photo,
        'email' => $check['value']->email,
        'id_privileges' => $check['value']->id_cms_privileges,
        'id_cabang' => $check['value']->id_cabang,
        'id_owner' => $check['value']->id_owner,
        'id_vendor' => $check['value']->id_vendor
      ]);
      redirect('dashboard');
    } else {
      $this->M_app->setAlert('danger', 'Email/password tidak cocok. Silahkan coba lagi');
      redirect('login');
    }
  }

  public function check_user(){
    $proc = $this->M_auth->forgot_password($this->input->post('email'));
    if ($proc == TRUE) {
      $this->session->set_userdata('resetted_acc', $this->input->post('email'));
      echo json_encode(['status' => true]);
    } else {
      echo json_encode(['status' => false]);
    }
  }

  public function logout(){
    $this->M_app->setAlert('warning', 'Terima kasih. Sampai jumpa lagi!');
    redirect('login');
  }
}

?>
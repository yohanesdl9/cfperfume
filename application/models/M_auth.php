<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

  public function login($email, $password){
    $check = $this->M_app->getDataByParameter('email', $email, 'cms_users');
    if ($check->num_rows() > 0){
      $password_hash = $check->row()->password;
      if (password_verify($password, $password_hash) == TRUE){
        $data['value'] = $check->row();
        $data['status'] = TRUE;
      } else {
        $data['value'] = [];
      } 
    } else {
      $data['value'] = [];
    }
    return $data;
  }

  public function forgot_password($email){
    $check = $this->M_app->getDataByParameter('email', $email, 'cms_users');
    return $check->num_rows() > 0;
  }

  public function reset_password($email, $password){
    $data = [
      'password' => password_hash($password, PASSWORD_BCRYPT),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    return $this->db->update('cms_users', $data, ['email' => $email]);
  }
}

/* End of file M_auth.php */
 ?>
<form class="form-horizontal auth-form my-4" action="<?= base_url('login/reset') ?>">
  Masukkan Username untuk Proses Reset Password
  <div class="form-group">
    <div class="input-group mt-3 mb-3">
      <span class="auth-form-icon"><i class="dripicons-mail"></i></span>                                                                                                              
      <input type="email" class="form-control" id="useremail" placeholder="Username">
    </div>                                    
  </div>     
  <div class="form-group mb-0 row">
    <div class="col-12 mt-2">
      <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="submit">Reset <i class="fas fa-sign-in-alt ml-1"></i></button>
    </div>
  </div>                       
</form>
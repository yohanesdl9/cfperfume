<form class="form-horizontal auth-form my-4" action="<?= base_url('login/auth') ?>" method="POST">
  <div class="form-group">
    <label for="username">Email</label>
    <div class="input-group mb-3">
      <span class="auth-form-icon"><i class="dripicons-user"></i> </span>                                                                                                              
      <input type="text" class="form-control" name="email" id="username" placeholder="Masukkan Email">
    </div>                                    
  </div>
  <div class="form-group">
    <label for="userpassword">Password</label>                                            
    <div class="input-group mb-3"> 
      <span class="auth-form-icon">
        <i class="dripicons-lock"></i> 
      </span>                                                       
      <input type="password" class="form-control" name="password" id="userpassword" placeholder="Masukkan Password">
    </div>                               
  </div>
  <div class="form-group row mt-4">
    <div class="col-sm-6">
      <a href="<?= base_url('login/forgot_password') ?>" class="text-muted font-13"><i class="dripicons-lock"></i> Lupa password?</a>                                    
    </div>
  </div>
  <div class="form-group mb-0 row">
    <div class="col-12 mt-2">
      <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
    </div>
  </div>                         
</form>
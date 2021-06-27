<form class="form-horizontal auth-form my-4" action="<?= base_url('login/reset_password') ?>" method="POST" id="formResetPassword">
  <div class="form-group">
    <label for="userpassword">Password</label>                                            
    <div class="input-group mb-3"> 
      <span class="auth-form-icon">
        <i class="dripicons-lock"></i> 
      </span>                                                       
      <input type="password" class="form-control" name="password" id="userpassword" placeholder="Masukkan Password">
    </div>                               
  </div>
  <div class="form-group">
    <label for="userpassword">Password</label>                                            
    <div class="input-group mb-3"> 
      <span class="auth-form-icon">
        <i class="dripicons-lock"></i> 
      </span>                                                       
      <input type="password" class="form-control" name="retype_password" id="userpassword" placeholder="Masukkan Ulang Password">
    </div>                               
  </div>
  <div class="form-group mb-0 row">
    <div class="col-12 mt-2">
      <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="submit">Reset Password <i class="fas fa-sign-in-alt ml-1"></i></button>
    </div>
  </div>                         
</form>
<script>
$("#formResetPassword").submit(function(){
  var password = $('input[name="password"]').val();
  var retype_password = $('input[name="password"]').val();
  if (password == retype_password) {
    $(this).submit();
  } else {
    Swal.fire(
      icon: 'error',
      title: 'Oops...',
      text: 'Password tidak cocok. Silahkan mencoba lagi.'
    )
  }
})
</script>
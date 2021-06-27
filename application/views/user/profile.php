<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
  </div>
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <form action="<?= base_url('user/change_photo') ?>" method="POST" enctype="multipart/form-data">
          <img id="preview" class="ml-auto mr-auto d-block" src="<?= base_url($this->session->userdata('photo') ? $this->session->userdata('photo') : 'assets/images/users/user-1.png') ?>" alt="user" width="150">
          <input type="file" name="profile" id="image" style="display: none;">
          <div class="row mt-2">
            <div class="mx-auto">
              <a class="btn btn-sm btn-default" href="javascript:changeProfile()">Ubah</a>
              <a class="btn btn-sm btn-danger" href="javascript:removeImage()">Hapus</a>
            </div>
          </div>
          <h3 class="text-center card-title mt-3"><?= $user['name'] ?></h3>
          <button type="submit" class="btn btn-primary btn-block">Ubah Foto</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Ubah Profil Pengguna</h4>
      </div>
      <div class="card-body">
        <form action="<?= base_url('user/ubah_profile') ?>" method="POST">
          <div class="form-group row">
            <?= form_label('Nama', '', ['class' => 'col-md-2 col-form-label']) ?>
            <div class="col-md-10">
              <?= form_input('name', $user['name'], ['class' => 'form-control', 'readonly' => true]) ?>
            </div>
          </div>
          <div class="form-group row">
            <?= form_label('Email', '', ['class' => 'col-md-2 col-form-label']) ?>
            <div class="col-md-10">
              <?= form_input('email', $user['email'], ['class' => 'form-control', 'readonly' => true]) ?>
            </div>
          </div>
          <div class="form-group row">
            <?= form_label('Password', '', ['class' => 'col-md-2 col-form-label']) ?>
            <div class="col-md-10">
              <?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'Kosongkan jika tidak ingin mengubah password']) ?>
            </div>
          </div>
          <div class="form-group row">
          <?php echo form_label('Toko', '', ['class' => 'col-md-2 col-form-label']);
          echo form_label(isset($user['id_toko']) ? $this->M_app->getDataByParameter('id', $user['id_toko'], 'tb_toko')->row()->nama_toko : 'Tidak ada', '', ['class' => 'ml-3 col-form-label']) ?>
          </div>
          <button type="submit" class="btn btn-success btn-block">Ubah Profil</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function changeProfile() {
  $('#image').click();
}

$('#image').change(function () {
  var imgPath = this.value;
  var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
  if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
    readURL(this);
  else
    alert("Please select image file (jpg, jpeg, png).")
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.readAsDataURL(input.files[0]);
    reader.onload = function (e) {
      $('#preview').attr('src', e.target.result);
    };
  }
}

function removeImage() {
  $('#preview').attr('src', '<?= base_url('assets/images/users/user-1.png') ?>');
}
</script>
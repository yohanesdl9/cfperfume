<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url('hak_akses') ?>"><i class="fas fa-chevron-circle-left"></i> Kembali ke halaman Detail Hak Akses</a>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Tambah Hak Akses</h4>
      </div>
      <div class="card-body">
        <form action="<?= base_url('hak_akses/tambah_hak_akses') ?>" method="POST">
          <?php foreach ($form as $f) echo generate($f); ?>
          <div class="form-group">
            <?= form_label('Konfigurasi Hak Akses', '', ['class' => 'col-form-label']) ?>
            <table class="table table-borderless table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama Menu</th>
                  <th class="text-center"></th>
                  <th class="text-center">Create</th>
                  <th class="text-center">Read</th>
                  <th class="text-center">Update</th>
                  <th class="text-center">Delete</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="checkbox" id="is_create">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="checkbox" id="is_read">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="checkbox" id="is_edit">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="checkbox" id="is_delete">
                    </div>
                  </td>
                </tr>
                <?php $i = 1; foreach ($detail_hak_akses as $dha) { ?>
                <tr>
                  <td class="text-center"><?= $i ?></td>
                  <td><?= $dha['name'] ?></td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="hidden" name="id_menu[]" value="<?= $dha['id'] ?>">
                      <input type="checkbox" id="menu_<?= $dha['id'] ?>">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="hidden" name="is_create[<?= $dha['id'] ?>]" value="0">
                      <input type="checkbox" name="is_create[<?= $dha['id'] ?>]" value="1" class="is_create menu_<?= $dha['id'] ?>">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="hidden" name="is_read[<?= $dha['id'] ?>]" value="0">
                      <input type="checkbox" name="is_read[<?= $dha['id'] ?>]" value="1" class="is_read menu_<?= $dha['id'] ?>">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="hidden" name="is_edit[<?= $dha['id'] ?>]" value="0">
                      <input type="checkbox" name="is_edit[<?= $dha['id'] ?>]" value="1" class="is_edit menu_<?= $dha['id'] ?>">
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="form-check">
                      <input type="hidden" name="is_delete[<?= $dha['id'] ?>]" value="0">
                      <input type="checkbox" name="is_delete[<?= $dha['id'] ?>]" value="1" class="is_delete menu_<?= $dha['id'] ?>">
                    </div>
                  </td>
                </tr>
                <?php $i++; } ?>
              </tbody>
            </table>
          </div>
          <input type="submit" class="btn btn-success" name="submit" value="Simpan & Tambah Lagi">
          <input type="submit" class="btn btn-success float-md-right" name="submit" value="Simpan">
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$('form').submit(function(e){
  var name = $('input[name="name"]').val();
  if (name == '') {
    e.preventDefault();
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Harap masukkan data dengan benar dan lengkap!'
    })
  }
});

$('input[type="checkbox"]').change(function(){
  var id = $(this).attr('id');
  var checked = $(this).is(":checked");
  $('.' + id).each(function(){
    this.checked = checked;
  });
});
</script>
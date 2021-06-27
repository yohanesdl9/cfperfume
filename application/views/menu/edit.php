<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
  </div>
  <div class="col-md-12">
    <form action="<?php echo base_url('menu/update_menu/' . $this->uri->segment(3)) ?>" method="post">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Update Menu</h4>
        </div>
        <div class="card-body">
          <?php foreach ($form as $f) echo generate($f); ?>
          <button type="submit" class="btn btn-success float-md-right">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.16/dist/js/bootstrap-select.min.js"></script>
<script>
  $.fn.selectpicker.Constructor.BootstrapVersion = '4';
  $.fn.selectpicker.Constructor.DEFAULTS.display = 'static';
  $('form').submit(function(e){
    var privileges = $('select[name="privileges[]"]').val();
    var name = $('input[name="name"]').val();
    var icon = $('select[name="icon"]').val();
    var path = $('input[name="path"]').val();
    if (privileges.length == 0 || name == '' || icon == '' || path == '') {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Harap masukkan data dengan benar dan lengkap!'
      })
    }
  });
</script>
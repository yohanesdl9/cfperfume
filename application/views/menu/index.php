<style>
.h-80p {
  height: 80px!important;
}
</style>
<div class="row">
  <div class="col-md-12">
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Daftar Menu (Aktif)</h4>
      </div>
      <div class="card-body">
        <menu id="nestable-menu">
          <button class="btn btn-sm btn-info" data-action="expand-all">Expand All</button>
          <button class="btn btn-sm btn-info" data-action="collapse-all">Collapse All</button>
        </menu>
        <div class="custom-dd dd" id="nestable">
          <ol class="dd-list" id="nestable">
          <?php foreach($allmenu as $am){ ?>
            <li class="dd-item dd3-item" data-id="<?= $am['id'] ?>">
              <div class="dd-handle dd3-handle"></div>
              <div class="dd3-content dd3-content-p"><?= $am['name'] ?>
                <span class="float-right">
                  <?php if ((isset($am['child']) && count($am['child']) == 0) && $am['is_dashboard'] == 0) { ?>
                  <a href="<?= base_url('menu/hak_akses/' . $am['id']) ?>"><i class="fas fa-sliders-h"></i></a>
                  <?php } ?>
                  <a href="<?= base_url('menu/edit/' . $am['id']) ?>"><i class="fas fa-pencil-alt"></i></a>
                  <a href="#" onclick="hapusMenu(<?= $am['id'] ?>)"><i class="fas fa-trash"></i></a>
                </span>
                <!-- <br><em><?= $am['privileges_names'] ?></em> -->
              </div>
              <?php if(isset($am['child'])){ ?>
              <ol class="dd-list child" id="menu-id">
              <?php foreach($am['child'] as $ams){ ?>
                <li class="dd-item dd3-item" data-id="<?= $ams['id'] ?>">
                  <div class="dd-handle dd3-handle"></div>
                  <div class="dd3-content dd3-content-p"><?= $ams['name'] ?> 
                    <span class="float-right">
                      <a href="<?= base_url('menu/hak_akses/' . $ams['id']) ?>" data-toggle="tooltip" data-placement="top" title="Hak Akses"><i class="fas fa-sliders-h"></i></a>
                      <a href="<?= base_url('menu/edit/' . $ams['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                      <a href="#" onclick="hapusMenu(<?= $ams['id'] ?>)" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></a>
                    </span>
                    <!-- <br><em><?= $am['privileges_names'] ?></em> -->
                  </div>
                </li>
              <?php } ?>
              </ol>
              <?php } ?> 
            </li>
          <?php } ?>
          </ol>
        </div>
        <input type="hidden" id="nestable-output">
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Daftar Menu (Nonaktif)</h4>
      </div>
      <div class="card-body">
        <menu id="nestable-menu">
          <button class="btn btn-sm btn-info" data-action="expand-all">Expand All</button>
          <button class="btn btn-sm btn-info" data-action="collapse-all">Collapse All</button>
        </menu>
        <div class="custom-dd dd" id="nestable_inactive">
          <ol class="dd-list" id="nestable_inactive">
          <?php foreach($inactivemenu as $am){ ?>
            <li class="dd-item dd3-item" data-id="<?= $am['id'] ?>" >
              <div class="dd-handle dd3-handle"></div>
              <div class="dd3-content dd3-content-p"><?= $am['name'] ?>
                <span class="float-right">
                  <a href="<?= base_url('menu/edit/' . $am['id']) ?>"><i class="fas fa-pencil-alt"></i></a>
                  <a href="#" onclick="hapusMenu(<?= $am['id'] ?>)"><i class="fas fa-trash"></i></a>
                </span>
              </div>
              <?php if(isset($am['child'])){ ?>
              <ol class="child" id="menu-id">
              <?php foreach($am['child'] as $ams){ ?>
                <li class="dd-item dd3-item" data-id="<?= $ams['id'] ?>" >
                  <div class="dd-handle dd3-handle"></div>
                  <div class="dd3-content dd3-content-p"><?= $ams['name'] ?> 
                    <span class="float-right">
                      <a href="<?= base_url('menu/hak_akses/' . $am['id']) ?>"><i class="fas fa-sliders-h"></i></a>
                      <a href="<?= base_url('menu/edit/' . $ams['id']) ?>"><i class="fas fa-pencil-alt"></i></a>
                      <a href="#" onclick="hapusMenu(<?= $ams['id'] ?>)"><i class="fas fa-trash"></i></a>
                    </span>
                  </div>
                </li>
              <?php } ?>
              </ol>
              <?php } ?> 
            </li>
          <?php } ?>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <form action="<?php echo base_url('menu/tambah_menu') ?>" method="post">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Tambah Menu Baru</h4>
        </div>
        <div class="card-body">
          <?php foreach ($form as $f) echo generate($f); ?>
          <button type="submit" class="btn btn-success float-md-right">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="<?= base_url('assets/plugins/nestable/jquery.nestable.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.16/dist/js/bootstrap-select.min.js"></script>
<script>
$.fn.selectpicker.Constructor.BootstrapVersion = '4';
$.fn.selectpicker.Constructor.DEFAULTS.display = 'static';
</script>
<script>
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
function hapusMenu(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan data Anda!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  }).then((result) => {
    if (result.value) {
      window.location.href = '<?= base_url('menu/hapus_menu/') ?>' + id;
    }
  });
}
$(document).ready(function(){

  var updateOutput = function(e){
    var list = e.length ? e : $(e.target),
      output = list.data('output');
    if (window.JSON) {
      output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
    } else {
      output.val('JSON browser support required for this demo.');
    }
  };

  // activate Nestable for list 1
  $('#nestable').nestable({
    maxDepth: 2
  }).nestable('expandAll').on('change', updateOutput);

  // output initial serialised data
  updateOutput($('#nestable').data('output', $('#nestable-output')));

  $('#nestable-menu').on('click', function(e){
    var target = $(e.target),
      action = target.data('action');
    if (action === 'expand-all') {
      $('.dd').nestable('expandAll');
    }
    if (action === 'collapse-all') {
      $('.dd').nestable('collapseAll');
    }
  });

  $('.dd').on('change', function() {
  
    var dataString = { 
      data : $("#nestable-output").val(),
    };

    $.ajax({
      type: "POST",
      url: "<?= base_url('menu/save') ?>",
      data: dataString,
      cache : false,
      success: function(data){
        console.log(data); 
      },error: function(xhr, status, error) {
        alert(error);
      },
    });
  });
});
</script>
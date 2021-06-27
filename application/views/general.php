<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('tipe/general_add/' . $this->uri->segment(3)) ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php } if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="mb-2">
      <a href="<?= base_url('tipe') ?>"><i class="fas fa-chevron-circle-left"></i> Kembali ke halaman Tipe</a>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless">
            <tr>
              <td class="w-25"><strong>Kode</strong></td>
              <td><?= $tipe['kode'] ?></td>
            </tr>
            <tr>
              <td class="w-25"><strong>Keterangan</strong></td>
              <td><?= $tipe['keterangan'] ?></td>
            </tr>
          </table>
          <table class="table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">Gambar</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($general as $t){ ?>
              <tr>
                <td class="text-center"><img src="<?= base_url(isset($d['gambar']) ? $d['gambar'] : 'assets/images/users/user-1.jpg') ?>" alt="" width="50"></td>
                <td><?= $t['keterangan'] ?></td>
                <td class="text-center">
                  <?php if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('tipe/general_edit/' . $this->uri->segment(3) . '/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                  <?php } if ($access->is_delete == 1) { ?>
                  <a href="#" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="hapusData('<?= $t['id'] ?>')"><i class="fas fa-trash"></i></a>
                  <?php } ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function hapusData(id){
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan data Anda!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Tidak"
  }).then((result) => {
    if (result.value) {
      window.location.href = '<?= base_url('tipe/hapus_general/' . $this->uri->segment(3) . '/') ?>' + id;
    }
  });
}
</script>
<div class="row">
  <div class="col-md-12">
    <a href="<?= base_url('hak_akses/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Superadmin</th>
                <th class="text-center">Root</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($hak_akses as $t){ ?>
              <tr>
                <td class="text-center"><?= $t['id'] ?></td>
                <td><?= $t['name'] ?></td>
                <td><?php if ($t['is_superadmin'] == 1) {
                  echo '<span class="badge badge-success">Superadmin</span>';
                } else {
                  echo '<span class="badge badge-secondary">Standard</span>';
                } ?></td>
                <td><?php if ($t['is_root'] == 1) {
                  echo '<span class="badge badge-success">Root</span>';
                } else {
                  echo '<span class="badge badge-secondary">Standard</span>';
                } ?></td>
                <td class="text-center">
                  <a href="<?= base_url('hak_akses/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                  <a href="#" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="hapusData('<?= $t['id'] ?>')"><i class="fas fa-trash"></i></a>
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
      window.location.href = '<?= base_url('hak_akses/hapus_hak_akses/') ?>' + id;
    }
  });
}
</script>
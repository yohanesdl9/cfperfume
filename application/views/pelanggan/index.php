<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('pelanggan/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
    <?php } if($this->session->flashdata('message')) { ?>
    <div class="alert alert-<?= $this->session->flashdata('color') ?> alert-dismissible mb-3" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?= $this->session->flashdata('message') ?>
    </div> 
    <?php } ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless table-striped table-hover datatable">
            <thead>
              <tr>
                <th class="text-center">Kode</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Email</th>
                <th class="text-center">No. Telepon 1</th>
                <th class="text-center">No. Telepon 2</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($pelanggan as $t){ ?>
              <tr>
                <td><?= $t['kode'] ?></td>
                <td><?= $t['nama'] ?></td>
                <td><?= $t['email'] ?></td>
                <td><?= $t['telepon'] ?></td>
                <td><?= $t['telepon2'] ?></td>
                <td class="text-center">
                  <a href="<?= base_url('pelanggan/alamat/' . $t['id']) ?>" data-toogle="tooltip" data-placement="top" title="Alamat"><i class="fas fa-address-book"></i></a>
                  <?php if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('pelanggan/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
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
      window.location.href = '<?= base_url('pelanggan/hapus_pelanggan/') ?>' + id;
    }
  });
}
</script>
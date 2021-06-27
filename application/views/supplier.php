<div class="row">
  <div class="col-md-12">
    <?php if ($access->is_create == 1) { ?>
    <a href="<?= base_url('supplier/add') ?>" class="btn btn-sm btn-success mb-3"><i class="fas fa-plus-circle"></i> Tambah Data</a>
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
                <th class="text-center">Nama Supplier</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">Telepon</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($supplier as $t){ ?>
              <tr>
                <td><?= $t['kode'] ?></td>
                <td><?= $t['nama_supplier'] ?></td>
                <td><?= $t['alamat_supplier'] . ', Kelurahan ' . $t['kelurahan'] . ', Kecamatan ' . $t['kecamatan'] . ', ' . $t['kota'] . ', ' . $t['provinsi'] . ' ' . $t['kodepos']  ?></td>
                <td><?= $t['telepon'] ?></td>
                <td class="text-center">
                  <?php if ($access->is_edit == 1) { ?>
                  <a href="<?= base_url('supplier/edit/' . $t['id']) ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
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
      window.location.href = '<?= base_url('supplier/hapus_supplier/') ?>' + id;
    }
  });
}
</script>
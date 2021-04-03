<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card">
      <div class="card-body">
        <h1 class="h4 mb-1 text-gray-800"><?= $notif['judul_notif']; ?></h1>
        <hr />
        <p class="">Surat <?= $notif['kategori_surat']; ?></p>
        <p class="">Pembuat <?= $notif['fullname']; ?></p>
        <p class="">Keterangan : <?= $notif['isi_notif']; ?></p>
        <hr />

        <?php $link = ($_SESSION['role'] == 3) ? 'mahasiswa/surat/tambah' : 'admin/surat/detail'; ?>
        <a href="<?= base_url($link . '/' . $notif['id_surat']); ?>" class="btn btn-warning btn-md">Lihat surat</a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
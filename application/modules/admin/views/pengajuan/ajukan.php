<div class="row">
  <div class="col-md-12">


    <div class="accordion" id="accordionExample">

      <?php
      foreach ($kategori_surat as $kategori) : ?>

        <div class="card">
          <div class="card-header" id="heading-<?= $kategori['id']; ?>">
            <h2 class="h6 mb-0">
              <a href="#" data-toggle="collapse" data-target="#collapse-<?= $kategori['id']; ?>" aria-expanded="true" aria-controls="collapse-<?= $kategori['id']; ?>">
                <?= $kategori['kategori_surat']; ?>
              </a>
            </h2>
          </div>

          <div id="collapse-<?= $kategori['id']; ?>" class="collapse" aria-labelledby="heading-<?= $kategori['id']; ?>" data-parent="#accordionExample">
            <div class="card-body">
              <?= $kategori['deskripsi']; ?>

              <a class="btn btn-md btn-ijomuda" href="<?= base_url('admin/surat/buat_surat/' . $kategori['id']) ?>">Buat Surat</a>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>
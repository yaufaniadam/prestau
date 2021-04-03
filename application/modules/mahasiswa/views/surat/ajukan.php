<div class="row">
	<div class="col-md-12">


		<div class="accordion" id="accordionExample">

			<?php foreach ($kategori_surat as $kategori) : ?>

				<div class="card <?= ($kategori['prodi'] > 0) ? (($kategori['prodi'] == $this->session->userdata('id_prodi')) ? '' : 'd-none') : ''; ?>">
					<div class="card-header" id="heading-<?= $kategori['id']; ?>">
						<h2 class="h6 mb-0">
							<a href="#" data-toggle="collapse" data-target="#collapse-<?= $kategori['id']; ?>" aria-expanded="true" aria-controls="collapse-<?= $kategori['id']; ?>">
								<?= $kategori['kategori_surat']; ?>
							</a>
						</h2>
					</div>

					<div id="collapse-<?= $kategori['id']; ?>" class="collapse" aria-labelledby="heading-<?= $kategori['id']; ?>" data-parent="#accordionExample">
						<div class="card-body">
							<?= $kategori['deskripsi'];

							$cek_sudah_buat_surat = cek_sudah_buat_surat($this->session->userdata('user_id'), $kategori['id'], $kategori['min_semester']); ?>


							<a class="btn btn-md <?= ($cek_sudah_buat_surat == 1) ? 'btn-ijomuda' : 'd-none'; ?>" href="<?= ($cek_sudah_buat_surat == 1) ? base_url('mahasiswa/surat/buat_surat/' . $kategori['id']) : '#'; ?>"><?= ($cek_sudah_buat_surat == 1) ? 'Ajukan Surat' : 'Saat ini Anda tidak dapat mengajukan surat ini.'; ?></a>
							<?php
							if ($cek_sudah_buat_surat > 1) { ?>
								<div class="alert alert-danger">
									<span><i class="fas fa-exclamation-triangle"></i><strong> Anda tidak diperkenankan mengajukan surat ini</strong> karena
										<?php if ($cek_sudah_buat_surat == 2) {
											echo "surat " . $kategori['kategori_surat'] . " yang Anda ajukan sebelumnya masih diproses. ";
										} ?>
										<?php if ($cek_sudah_buat_surat == 3) {
											echo "syarat minimum semester (semester 2) belum terpenuhi.";
										} ?>
									</span>
								</div>

							<?php } ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</div>
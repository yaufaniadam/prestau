<div class="row">
	<div class="col-md-12">

		<div class="accordion" id="accordion">

			<div class="accordion" id="accordionExample">
				<div class="card">
					<div class="card-header" id="heading-1">
						<h5 class="h6 mb-0">
							<a role="button" data-toggle="collapse" href="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
								Rekognisi
							</a>
						</h5>
					</div>
					<div id="collapse-1" class="collapse" data-parent="#accordion" aria-labelledby="heading-1">
						<div class="card-body">
							<div id="accordion-1">
								<?php foreach ($rekognisi as $rekognisi) { ?>
									<div class="card">
										<div class="card-header" id="heading-1-2">
											<h5 class="h6 mb-0">
												<a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-<?= $rekognisi['Jenis_Pengajuan_Id']; ?>" aria-expanded="false" aria-controls="collapse-1-2">
													<?= $rekognisi['Jenis_Pengajuan']; ?>
												</a>
											</h5>
										</div>
										<div id="collapse-1-<?= $rekognisi['Jenis_Pengajuan_Id']; ?>" class="collapse" data-parent="#accordion-1" aria-labelledby="heading-1-2">
											<div class="card-body">
												<?= $rekognisi['deskripsi']; ?>
												<a class="btn btn-md" href="<?= base_url('mahasiswa/pengajuan/baru/' . $rekognisi['Jenis_Pengajuan_Id']); ?>">Ajukan Surat</a>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<?php foreach ($jenis_pengajuan as $kategori) { ?>
					<div class="card">
						<div class="card-header" id="heading-<?= $kategori['Jenis_Pengajuan_Id']; ?>">
							<h5 class="h6 mb-0">
								<a href="#" data-toggle="collapse" data-target="#collapse-<?= $kategori['Jenis_Pengajuan_Id']; ?>" aria-expanded="true" aria-controls="collapse-<?= $kategori['Jenis_Pengajuan_Id']; ?>">
									<?= $kategori['Jenis_Pengajuan']; ?>
								</a>
							</h5>
						</div>

						<div id="collapse-<?= $kategori['Jenis_Pengajuan_Id']; ?>" class="collapse" aria-labelledby="heading-<?= $kategori['Jenis_Pengajuan_Id']; ?>" data-parent="#accordion">
							<div class="card-body">
								<?= $kategori['deskripsi']; ?>
								<a class="btn btn-md" href="<?= base_url('mahasiswa/pengajuan/baru/' . $kategori['Jenis_Pengajuan_Id']); ?>">Ajukan Surat</a>
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
</div>

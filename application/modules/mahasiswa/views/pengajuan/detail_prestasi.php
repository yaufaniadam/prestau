<!-- catatan:
error message pada field jika invalidnya masih muncul, padahal field yg salah sudah diganti isinya,
mestinya ketika user mengganti, error messagenya langsung ilang -->
<div class="row">
	<div class="col-8">

		<div class="card shadow">
			<a href="#collKeterangan" class="d-block card-header pt-3 pb-2 bg-abumuda collapsed" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collKeterangan">
				<p class="h6 font-weight-bold text-white">Keterangan</p>
			</a>
			<div class="collapse show" id="collKeterangan">
				<div class="card-body">

					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label ">Judul Karya</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label ">
								<?= $prestasi['judul']; ?>
								<a href="<?= base_url('mahasiswa/pengajuan/tambah/' . $prestasi['id_pengajuan']); ?>">
									<i class="fas fa-external-link-alt"></i> 
								</a>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label">Kategori Prestasi/Rekognisi</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label ">
								<?= $prestasi['Jenis_Pengajuan']; ?>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label ">Reward</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label ">
								<?= $prestasi['nominal']; ?>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label ">Tanggal Pencairan</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label ">
								<?= $prestasi['status_pencairan'] != '' ? $prestasi['status_pencairan'] : 'belum dicairkan' ?>
							</label>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- /.col -->
	<div class="col-4">
		<div class="card shadow">
			<a href="#collMhs" class="d-block card-header pt-3 pb-2 bg-warning" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collMhs">
				<p class="h6 font-weight-bold text-white">Prestasi dari</p>
			</a>
			<div class="collapse show" id="collMhs">
				<div class="card-body pb-3">
					<div class="media">

						<?= profPic($prestasi['STUDENTID'], 60); ?>

						<div class="media-body ml-2">
							<h5 class="mt-0 text-gray-900 mb-0 font-weight-bold"><?= $prestasi['FULLNAME']; ?></h5>
							<span class="mb-0 badge badge-ijomuda"> <?= $prestasi['STUDENTID']; ?></span>
							<p class="mb-0 text-gray-800"> <?= $prestasi['NAME_OF_DEPARTMENT']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


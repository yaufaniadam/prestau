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
						<label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Judul</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label col-form-label-sm">
								<?= get_meta_value('judul', $pengajuan['pengajuan_id'], false); ?>
								<a href="<?= base_url('mahasiswa/pengajuan/tambah/' . $pengajuan['pengajuan_id']); ?>">
									<i class="fas fa-external-link-alt"></i>
								</a>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Kategori</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label col-form-label-sm">
								<?= $pengajuan['Jenis_Pengajuan']; ?>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Nominal</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label col-form-label-sm">
								<?= $pengajuan['nominal']; ?>
							</label>
						</div>
					</div>
					<div class="form-group row">
						<label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Tanggal Pencairan</label>
						<div class="col-sm-8">
							<label class="col-sm-12 col-form-label col-form-label-sm">
								<?= $pengajuan['tanggal_pencairan'] != '' ? $pengajuan['tanggal_pencairan'] : 'belum dicairkan' ?>
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
				<p class="h6 font-weight-bold text-white">Penerima</p>
			</a>
			<div class="collapse show" id="collMhs">
				<div class="card-body pb-3">
					<div class="media">

						<?= profPic($pengajuan['STUDENTID'], 60); ?>

						<div class="media-body ml-2">
							<h5 class="mt-0 text-gray-900 mb-0 font-weight-bold"><?= $pengajuan['FULLNAME']; ?></h5>
							<span class="mb-0 badge badge-ijomuda"> <?= $pengajuan['STUDENTID']; ?></span>
							<p class="mb-0 text-gray-800"> <?= $pengajuan['NAME_OF_DEPARTMENT']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


<!-- Modal -->
<div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Terjadi kesalahan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<p><i class="fas fa-exclamation-triangle"> </i> Opsi ini hanya jika semua data yang dikirimkan sudah sesuai seluruhnya!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#kat_tujuan_surat').change(function() {
			var id = $(this).val();
			$.ajax({
				url: '<?= base_url('admin/pengajuan/get_tujuan_surat'); ?>',
				method: 'POST',
				data: {
					kat_tujuan_surat: id
				},
				dataType: 'json',
				success: function(data) {
					console.log(data)
					var html = '';
					var i;
					if (data.length == 0) {
						html += '<option>Tujuan tidak ditemukan</option>'
					} else {
						for (i = 0; i < data.length; i++) {
							html += '<option value = ' + data[i].id + '>' + data[i].tujuan_surat + '</option>'
						}
					}
					$('#tujuan_surat').html(html);
				}
			});
		});
	});
</script>

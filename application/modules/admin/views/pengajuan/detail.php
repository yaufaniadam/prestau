<!-- catatan:
error message pada field jika invalidnya masih muncul, padahal field yg salah sudah diganti isinya,
mestinya ketika user mengganti, error messagenya langsung ilang -->
<h1 class="h3 mb-4 text-gray-900"><?= $pengajuan['Jenis_Pengajuan']; ?> </h1>

<div class="row">
	<div class="col-8">
		<?php if (isset($msg) || validation_errors() !== '') : ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="fa fa-exclamation"></i> Terjadi Kesalahan</h4>
				<?= validation_errors(); ?>
				<?= isset($msg) ? $msg : ''; ?>
			</div>
		<?php endif; ?>
		<!-- fash message yang muncul ketika proses penghapusan data berhasil dilakukan -->
		<?php if ($this->session->flashdata('msg') != '') : ?>
			<div class="alert alert-success flash-msg alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Sukses!</h4>
				<?= $this->session->flashdata('msg'); ?>
			</div>
		<?php endif; ?>

		<?php
		if (
			($pengajuan['status_id'] == 8 && $this->session->userdata('role') == 5) ||
			($pengajuan['status_id'] == 7 && $this->session->userdata('role') == 6)
		) {
			echo form_open('admin/pengajuan/disetujui');
		}

		if (($pengajuan['status_id'] == 2 || $pengajuan['status_id'] == 5) && $this->session->userdata('role') == 2) {
			echo form_open('admin/pengajuan/verifikasi');
		}

		if (($pengajuan['status_id'] == 7) && $this->session->userdata('role') == 2) {
			echo form_open('admin/pengajuan/selesai');
		}
		?>

		<!-- Surat diverifikasi oleh staf-->
		<?php if (($pengajuan['status_id'] == 7) && $this->session->userdata('role') == 2) { ?>
		<?php } ?>

		<div class="card shadow">
			<a href="#collKeterangan" class="d-block card-header pt-3 pb-2 bg-abumuda <?= (($pengajuan['status_id'] == 9 || $pengajuan['status_id'] == 10) && $this->session->userdata('role') == 1) ? "collapsed" : "" ?>" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collKeterangan">
				<p class="h6 font-weight-bold text-white">Keterangan</p>
			</a>
			<div class="collapse<?= (($pengajuan['status_id'] == 9 || $pengajuan['status_id'] == 10) && $this->session->userdata('role') == 1) ? "" : " show" ?>" id="collKeterangan">
				<div class="card-body">
					<input type="hidden" name="pengajuan_id" value="<?= $pengajuan['pengajuan_id']; ?>">

					<input type="hidden" name="user_id" value="<?= $pengajuan['STUDENTID']; ?>">

					<?php foreach ($fields as $field) { ?>

						<div class="form-row">
							<label class="col-md-5" for="dokumen[<?= $field['field_id'] ?>]">
								<?= $field['field'] ?></label>
							<div class="col-md-7">
								<?php
								generate_keterangan_surat($field['field_id'], $pengajuan['pengajuan_id'], $pengajuan['status_id']);
								?>
							</div>
						</div>

					<?php } ?>

					<?php if (($pengajuan['status_id'] == 2 || $pengajuan['status_id'] == 5) && $this->session->userdata('role') == 2) { ?>
						<div class="form-row pt-3">
							<div class="col-md-12">

								<div class="card">
									<div class="card-header">
										Hasil Verifikasi Dokumen
									</div>
									<div class="card-body">

										<p> Setelah diperiksa dengan seksama, maka <span class="badge badge-perak">
												<?= $this->session->userdata('fullname'); ?></span> menyatakan bahwa permohonan <strong>Pengajuan prestasi <?= $pengajuan['Jenis_Pengajuan']; ?></strong> yang diajukan oleh <strong><?= $pengajuan['FULLNAME']; ?></strong> : </p>

										<ul class="list-group list-group-flush">
											<li class="list-group-item"><input type="radio" name="rev2" id="diterima" value="7" /> Diterima dan dapat diproses lebih lanjut
											</li>

											<li class="list-group-item"><input type="radio" name="rev2" id="ditolak" value="6" /> Ditolak
												<?php if ($pengajuan['status_id'] == 2) { ?>
											<li class="list-group-item"><input type="radio" name="rev2" id="revisi" value="4" /> Perlu direvisi kembali
											</li>
										<?php } ?> </li>
										</ul>


										<p class="mt-3">
											<span class="pl-2 mb-2 d-inline-block"><input type="checkbox" name="" id="sudahPeriksa"> Pernyataan ini dibuat dengan sebenar-benarnya dan dapat dipertanggung jawabkan kebenarannya. <a class="help" data-toggle="tooltip" data-placement="top" title="Centang untuk mengaktifkan tombol verifikasi."><i class="fa fa-info-circle"></i></a></span>
										</p>

										<input type="submit" id="sub1" value="Kirim Hasil Verifikasi" name="submit" class="btn btn-<?= $pengajuan['badge']; ?> btn-md btn-block" disabled>
									</div>

								</div>

							</div>
						</div>

						<script>
							$(function() {

								<?php if ($pengajuan['status_id'] == 2) { ?>

									// cek jumlah field dengan class .verifikasi
									var numItems = $('.verifikasi').length;

									// jika check radio id="diterima" diklik
									$('#diterima').click(function(e) {
										// lalu cocokkan dengan fungsi dibawah ini
										// jumalh field yang dichecked harus sama dengan jumalh field
										if ($('.verifikasi:checked').length != numItems) {

											//  jika jumlah field tidak sama, maka option id="#diterima" memunculkan modal eror di bawah
											$('#error_modal').modal("show");
											return false;
										}
									});

									$('#ditolak').click(function(e) {
										// lalu cocokkan dengan fungsi dibawah ini
										// jumalh field yang dichecked harus sama dengan jumalh field
										if ($('.verifikasi:checked').length == numItems) {

											//  jika jumlah field tidak sama, maka option id="#diterima" memunculkan modal eror di bawah
											//$('#error_modal').modal("show");
											alert('sudah dicentang semua kok ditolak?');
											return false;
										}
									});

									$('#revisi').click(function(e) {
										// lalu cocokkan dengan fungsi dibawah ini
										// jumalh field yang dichecked harus sama dengan jumalh field
										if ($('.verifikasi:checked').length == numItems) {

											//  jika jumlah field tidak sama, maka option id="#diterima" memunculkan modal eror di bawah
											//$('#error_modal').modal("show");
											alert('sudah dicentang semua kok ditolak?');
											return false;
										}
									});


								<?php } ?>

								$('#sudahPeriksa').click(function(e) {
									if ($(this).is(':checked')) {

										if (!$("input[name='rev2']:checked").val()) {
											alert('Hasil belum dipilih!');
											return false;
										} else {
											$('#sub1').removeAttr('disabled');
										}

									} else {
										$('#sub1').attr('disabled', 'disabled');
									}
								});
							});
						</script>

					<?php }

					if ($pengajuan['status_id'] == 4 && $this->session->userdata('role') == 2) { ?>
						<div class="form-row pt-3">
							<div class="col-md-12">
								<input type="submit" id="sub1" value="Menunggu perbaikan kelengkapan administrasi" name="submit" class="btn btn-perak btn-md btn-block" disabled>
							</div>
						</div>
					<?php }


					if ($pengajuan['status_id'] == 7 && $this->session->userdata('role') == 6) { ?>
						<div class="form-row pt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										Persetujuan Ketua Program Studi
									</div>
									<div class="card-body">

										<p> Saya selaku Ketua Program Studi <?= $pengajuan['prodi']; ?> memberikan persetujuan pada <strong>Surat <?= $pengajuan['Jenis_Pengajuan']; ?></strong> yang diajukan oleh <strong><?= $pengajuan['FULLNAME']; ?></strong> </p>

										<p>Dengan demikian surat ini dapat diteruskan prosesnya ke tingkat fakultas.</p>

										<p class="mt-3">
											<span class="pl-2 mb-2 d-inline-block"><input type="checkbox" name="" id="sudahPeriksa"> Pernyataan ini dibuat dengan sebenar-benarnya dan dapat dipertanggung jawabkan kebenarannya. <a class="help" data-toggle="tooltip" data-placement="top" title="Centang untuk mengaktifkan tombol verifikasi."><i class="fa fa-info-circle"></i></a></span>
										</p>


										<input type="hidden" name="prodi" value="<?= $pengajuan['id_prodi']; ?>" />
										<input type="submit" id="sub1" value="Beri Persetujuan" name="submit" class="btn btn-<?= $pengajuan['badge']; ?> btn-md btn-block" disabled>
									</div>


								</div>

							</div>
						</div>

						<script>
							$(function() {
								$('#sudahPeriksa').click(function(e) {
									if ($(this).is(':checked')) {
										$('#sub1').removeAttr('disabled');
									} else {
										$('#sub1').attr('disabled', 'disabled');
									}
								});
							});
						</script>
					<?php }

					if ($pengajuan['status_id'] == 8 && $this->session->userdata('role') == 5) { ?>
						<div class="form-row pt-3">
							<div class="col-md-12">

								<div class="card">
									<div class="card-header">
										Persetujuan Direktur Program Pascasarjana
									</div>
									<div class="card-body">

										<p> Saya selaku Direktur Program Pascasarjana UMY memberikan persetujuan pada <strong>Surat <?= $pengajuan['Jenis_Pengajuan']; ?></strong> yang diajukan oleh <strong><?= $pengajuan['FULLNAME']; ?></strong> dari prodi <?= $pengajuan['prodi']; ?>.</p>

										<p>Dengan demikian surat ini dapat diterbitkan.</p>

										<p class="mt-3">
											<span class="pl-2 mb-2 d-inline-block"><input type="checkbox" name="" id="sudahPeriksa"> Pernyataan ini dibuat dengan sebenar-benarnya dan dapat dipertanggung jawabkan kebenarannya. <a class="help" data-toggle="tooltip" data-placement="top" title="Centang untuk mengaktifkan tombol verifikasi."><i class="fa fa-info-circle"></i></a></span>
										</p>

										<input type="submit" id="sub1" value="Beri Persetujuan" name="submit" class="btn btn-<?= $pengajuan['badge']; ?> btn-md btn-block" disabled>
									</div>
								</div>
							</div>
						</div>
						<script>
							$(function() {
								$('#sudahPeriksa').click(function(e) {
									if ($(this).is(':checked')) {
										$('#sub1').removeAttr('disabled');
									} else {
										$('#sub1').attr('disabled', 'disabled');
									}
								});
							});
						</script>

					<?php }
					form_close(); ?>
				</div>
			</div>
		</div>

		<!-- jika surat sudah diacc oleh Direktur pasca, maka atur surat-->
		<?php if ($pengajuan['status_id'] == 9 && $this->session->userdata('role') == 1) { ?>
			<div class="card shadow mt-3">
				<a href="#collterbit" class="d-block card-header pt-3 pb-2 bg-success" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collterbit">
					<p class="h6 font-weight-bold text-white">Terbitkan Surat</p>
				</a>
				<div class="collapse show" id="collterbit">
					<div class="card-body pb-3">

						<p>Lakukan pengaturan di bawah ini sebelum surat diterbitkan</p>
						<?php echo form_open('admin/pengajuan/terbitkan_surat'); ?>

						<div class="form-group row">
							<label class="col-md-4" for="">Nomor Surat
								<small id="emailHelp" class="form-text text-muted">+1 dari nomor sebelumnya dengan kategori yang sama</small>
							</label>
							<div class="col-md-8">

								<?php
								$no_surat = $this->db->query("select max(no_surat) as last_no from no_surat where id_kategori_surat= " . $pengajuan['id_kategori_surat'] . " AND YEAR(tanggal_terbit) =" . date('Y'))->row_array();

								if ($no_surat['last_no'] > 0) {
									$last_no = $no_surat['last_no'] + 1;
								} else {
									$last_no = 1;
								}
								?>

								<input type="hidden" name="id_surat" id="" value="<?= $pengajuan['pengajuan_id']; ?>">
								<input type="hidden" name="id_kategori_surat" id="" value="<?= $pengajuan['id_kategori_surat'] ?>">
								<input type="text" name="no_surat" id="" value="<?= $last_no ?>" class="form-control <?= (form_error('no_surat')) ? 'is-invalid' : ''; ?> ">
								<span class="text-danger"><?php echo form_error('no_surat'); ?></span>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4" for="">Kategori Tujuan Surat</label>
							<div class="col-md-8">

								<?php $tujuan_surat = $this->db->query("select * from kat_tujuan_surat")->result_array(); ?>

								<select name="kat_tujuan_surat" id="kat_tujuan_surat" class="form-control <?= (form_error('kat_tujuan_surat')) ? 'is-invalid' : ''; ?> ">
									<option value="">Pilih Kategori Tujuan Surat</option>
									<?php foreach ($tujuan_surat as $tujuan) { ?>
										<option value="<?= $tujuan['pengajuan_id']; ?>"><?= $tujuan['kat_tujuan_surat']; ?></option>
									<?php } ?>
								</select>
								<span class="text-danger"><?php echo form_error('kat_tujuan_surat'); ?></span>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4" for="">Tujuan Surat</label>
							<div class="col-md-8">

								<select name="tujuan_surat" id="tujuan_surat" class="form-control <?= (form_error('tujuan_surat')) ? 'is-invalid' : ''; ?> ">
									<option value="">Pilih Tujuan</option>
								</select>
								<span class="text-danger"><?php echo form_error('tujuan_surat'); ?></span>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4" for="">Urusan Surat</label>
							<div class="col-md-8">

								<?php $urusan_surat = $this->db->query("select * from urusan_surat")->result_array(); ?>

								<select name="urusan_surat" id="" class="form-control <?= (form_error('urusan_surat')) ? 'is-invalid' : ''; ?> ">
									<option value="">Urusan Surat</option>
									<?php foreach ($urusan_surat as $urusan) { ?>
										<option value="<?= $urusan['kode']; ?>"><?= $urusan['urusan']; ?></option>
									<?php } ?>
								</select>
								<span class="text-danger"><?php echo form_error('urusan_surat'); ?></span>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4" for="">Instansi/Lembaga Tujuan
								<small id="emailHelp" class="form-text text-muted">Tujuan surat bisa diganti jika diperlukan.</small>
							</label>
							<div class="col-md-8">
								<textarea name="instansi" id="" cols="30" rows="3" class="textarea-summernote <?= (form_error('instansi')) ? 'is-invalid' : ''; ?> ">
								<?= ($pengajuan['tujuan_surat']) ? $pengajuan['tujuan_surat'] : get_meta_value('tujuan_surat', $pengajuan['pengajuan_id'], false);  ?>
							</textarea>
								<span class="text-danger"><?php echo form_error('instansi'); ?></span>
							</div>
						</div>

						<input type="submit" id="sub1" value="Terbitkan Sekarang" name="submit" class="btn btn-<?= $pengajuan['badge']; ?> btn-md btn-block">
						<?php form_close(); ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- jika surat sudah diterbitkan --
		<?php if ($pengajuan['status_id'] == 7) { ?>
			<div class="card shadow mt-3">
				<a href="#collterbit" class="d-block card-header pt-3 pb-2 bg-success" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collterbit">
					<p class="h6 font-weight-bold text-white">Surat</p>
				</a>
				<div class="collapse show" id="collterbit">
					<div class="card-body pb-3">
						Download Surat Untuk Mencairkan Dana
						<a href="<?= base_url("admin/pengajuan/tampil_surat/" . $pengajuan['pengajuan_id']); ?>" class="btn btn-success"> <i class="fas fa-file-pdf"></i> PDF</a>
					</div>
				</div>
			</div>
		<?php } ?>-->

	</div>

	<!-- /.col -->
	<div class="col-4">
		<div class="card shadow">
			<a href="#collMhs" class="d-block card-header pt-3 pb-2 bg-warning" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collMhs">
				<p class="h6 font-weight-bold text-white">Pemohon</p>
			</a>
			<div class="collapse show" id="collMhs">
				<div class="card-body pb-3">
					<div class="media">

						<?= profPic($pengajuan['nim'], 60); ?>

						<div class="media-body ml-2">
							<h5 class="mt-0 text-gray-900 mb-0 font-weight-bold"><?= $pengajuan['FULLNAME']; ?></h5>
							<span class="mb-0 badge badge-ijomuda"> <?= $pengajuan['nim']; ?></span>
							<p class="mb-0 text-gray-800"> <?= $pengajuan['NAME_OF_DEPARTMENT']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow mt-3">
			<a href="#collStatus" class="d-block card-header pt-3 pb-2 bg-<?= $pengajuan['badge']; ?>" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collStatus">
				<p class="h5 text-center font-weight-bold text-white"> <?= $pengajuan['status']; ?> </p>
			</a>
			<div class="collapse show" id="collStatus">
				<div class="card-body pl-2">
					<div class="timeline timeline-xs">
						<?php foreach ($timeline as $tl) { ?>
							<div class="timeline-item">
								<div class="timeline-item-marker">
									<div class="timeline-item-marker-text"><?= $tl['date']; ?></div>
									<div class="timeline-item-marker-indicator bg-<?= $tl['badge']; ?>"></div>
								</div>
								<div class="timeline-item-content">
									<?= $tl['status']; ?>
									<span class="badge badge-perak"><?= $tl['time']; ?></span>
								</div>
							</div>
						<?php } ?>
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
<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-header">
				<div class="row">
					<div class="col-md-6">
						<p class="mb-0">Nama mahasiswa yang memperoleh reward pada periode ini</p>
					</div>
					<div class="col-md-6 text-right">
						<?php if ($status_periode == 0) { ?>
							<input type="hidden" name="id_periode" value="<?= $id_periode; ?>">
							<button type="button" class="btn btn-sm btn-success" <?= (count($daftar_pengajuan) > 0) ? '' : 'disabled'; ?> data-toggle="modal" data-target="#confirm-modal"> <i class="fas fa-paper-plane"></i>
								Terbitkan reward periode ini
							</button>
						<?php } ?>


						<a href="<?= base_url('admin/periode/export_excel/' . $id_periode); ?>" class="btn btn-warning btn-sm"><i class="fas fa-file-excel"></i> Export ke Excel</a>
					</div>
				</div>
			</div>

			<div class="card-body">
				<table id="pengajuan-descs" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:1%"><input type="checkbox" name="" id="check_all"></th>
							<th style="width:40%">Perihal</th>
							<th style="width:35%">Judul Kegiatan</th>
							<th style="width:25%">Status</th>
							<th>Mahasiswa</th>
							<th>Nominal (Rp)</th>
							<?php if ($status_periode != 1) { ?>
								<th>&nbsp;</th>
							<?php } ?>
							<th>&nbsp;</th>
							<!-- <th></th> -->
						</tr>
					</thead>
					<tbody>
						<?php

						$total = 0;
						foreach ($daftar_pengajuan as $pengajuan) {
							$nominal = $pengajuan['nominal'];
						?>
							<input type="hidden" name="pengajuan[]" value="<?= $pengajuan['pengajuan_id']; ?>" id="">
							<tr class="<? ($pengajuan['status_id'] == 2) ? 'proses' : ''; ?> <?= ($pengajuan['status_id'] == 4) ? 'perlu-revisi' : ''; ?>">
								<td class="text-center align-middle">
									<input checked type="checkbox" name="pengajuan_id[]" value="<?= $pengajuan['STUDENTID']; ?>" class="check">
								</td>
								<td>
									<a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
										<?= $pengajuan['Jenis_Pengajuan']; ?></a>
								</td>
								<td>
									<?php echo get_meta_value('judul', $pengajuan['pengajuan_id'], false); ?>
								</td>
								<td class="table-<?= $pengajuan['badge']; ?>"><?= $pengajuan['status']; ?>
								</td>
								<td>
									<p class="m-0">
										<?= $pengajuan['FULLNAME']; ?>
									</p>
									<!-- <p class="badge m-0 badge-ijomuda">
										<?= $pengajuan['DEPARTMENT_ID']; ?>
									</p> -->
								</td>
								<td>
									<p class="m-0">
										<?php if ($nominal == 0) {
											echo 'Reward di ketua kelompok';
										} else {
											echo "<span class=' text-right'>" . number_format($nominal, 2) . "</span>";
										}	?>
									</p>
								</td>

								<?php if ($status_periode != 1) { ?>
									<td>
										<!-- <a href="" style="color:#fff;" title="Hapus" class="delete btn btn-sm  btn-circle btn-danger" data-href="<?= base_url('admin/periode/hapus/' . $pengajuan['id_penerbitan_pengajuan'] . '/' . $pengajuan['id_periode'] . '/' . $pengajuan['pengajuan_id']); ?>" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-alt"></i></a> -->

										<?= form_open(base_url('admin/periode/hapus')); ?>
										<input type="hidden" name="command" value="DELETE">
										<input type="hidden" name="id_penerbitan_pengajuan" value="<?= $pengajuan['id_penerbitan_pengajuan']; ?>">
										<input type="hidden" name="id_pengajuan" value="<?= $pengajuan['pengajuan_id']; ?>">
										<input type="hidden" name="id_periode" value="<?= $pengajuan['id_periode']; ?>">
										<button type="submit" style="color:#fff;" class="delete btn btn-sm  btn-circle btn-danger" onclick="return confirmSubmit()">
											<i class="fa fa-trash-alt"></i>
										</button>
										<?= form_close(); ?>
									</td>
								<?php } ?>
								<?php if ($pengajuan['status_id'] === 10) { ?>
									<td>
										<?php
										if ($nominal != 0) {
											if ($pengajuan['tanggal_pencairan'] == '') { ?>
												<button type="button" class="btn btn-primary btn-pencairan" data-toggle="modal" data-target="#pencairanModal" id="<?= $pengajuan['id_penerbitan_pengajuan']; ?>">
													<i class="fas fa-cash-register"></i> Cairkan
												</button>
										<?php } else {
												echo "<i class='fas fa-check-circle text-success'></i> sudah dicairkan";
											}
										}

										?>
									</td>
								<?php } ?>

								<!-- <td>
									<button type="button" data-toggle="modal" id="<?/*= $pengajuan['id_penerbitan_pengajuan']; */ ?>" class="btn btn-primary btn-sm btn-reward" data-target="#exampleModal">
										edit reward
									</button>
								</td> -->
							</tr>
						<?php
							$total  += $nominal;
						} ?>
						<tr>
							<th colspan="4" style="text-align: right;">Total</th>
							<th colspan="2">Rp <span style="float:right;"><?= number_format($total, 2); ?></span></th>
						</tr>
					</tbody>
				</table>

			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal -->

<!-- Modal Pencairan -->
<div class="modal fade" id="pencairanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Data Pencairan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php echo form_open(base_url('admin/periode/pencairan_reward')) ?>
			<div class="modal-body">
				<div class="form-group row">
					<input type="hidden" id="id_penerbitan_pengajuan_field" name="id_penerbitan_pengajuan">
					<input type="hidden" name="id_periode" value="<?= $id_periode; ?>">
					<label for="pegawai" class="col-sm-2 col-form-label">Pegawai</label>
					<div class="col-sm-10 my-2">
						<input type="text" id="pegawai" name="pegawai" class="form-control">
					</div>
					<label for="penerima" class="col-sm-2 col-form-label">Penerima</label>
					<div class="col-sm-10">
						<input type="text" id="penerima" name="penerima" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<input class="btn btn-primary" type="submit" value="Submit">
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- Modal Pencairan -->

<!-- Modal Penerbitan -->
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-labelledby="confirm-modalLabel" aria-hidden="true">
	<?= form_open(base_url('admin/periode/bulan')); ?>
	<?php foreach ($daftar_pengajuan as $pengajuan) {	?>
		<input type="hidden" name="pengajuan[]" value="<?= $pengajuan['pengajuan_id']; ?>" id="">
		<input type="hidden" name="id_periode" value="<?= $id_periode; ?>" id="">
	<?php } ?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="confirm-modalLabel">Konfirmasi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Aksi ini tidak dapat diulang lagi.
				Lanjutkan?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<input type="submit" class="btn btn-danger float-right" <?= (count($daftar_pengajuan) > 0) ? '' : 'disabled'; ?> value="Terbitkan" name="submit">
			</div>
		</div>
	</div>
	<?= form_close(); ?>
</div>
<!-- Modal Penerbitan -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Reward</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> -->
			<?= form_open('admin/periode/set_nominal'); ?>
			<div class="modal-body">
				<div class="form-group text-center">
					<label for="exampleInputEmail1">Masukkan nominal reward</label>
					<span id="emailHelp" class="form-text text-muted">contoh : 200000</span>
					<input type="number" name="nominal" id="nominal_reward" class="form-control">
					<input type="hidden" name="id_prestasi" id="id_prestasi_value">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<!-- /.modal -->



<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable({});
	});

	$(".btn-pencairan").click(function() {
		var id_penerbitan_pengajuan = this.id;
		$("#id_penerbitan_pengajuan_field").val(id_penerbitan_pengajuan);
	});

	function confirmSubmit() {
		var agree = confirm("Yakin ingin menghapus data ini?");
		if (agree)
			return true;
		else
			return false;
	}

	$(".btn-reward").click(function() {
		console.log(this.id);
		var id_prestasi = this.id;
		$.ajax({
			url: "<?= base_url('/admin/periode/reward/'); ?>" + id_prestasi,
			success: function(data) {
				$("#nominal_reward").val(data)
				$("#id_prestasi_value").val(id_prestasi)
			}
		});
	});
</script>
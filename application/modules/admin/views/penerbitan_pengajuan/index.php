<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-header">
				<div class="row">
					<div class="col-md-8">
						<p class="mb-0">Nama mahasiswa yang memperoleh reward pada periode ini</p>
					</div>
					<div class="col-md-4">
						<?php if ($status_periode == 0) { ?>
							<?= form_open(base_url('admin/periode/bulan')); ?>
							<input type="hidden" name="id_periode" value="<?= $id_periode; ?>">
							<button type="button" class="btn btn-sm btn-success mb-2 float-right" <?= (count($daftar_pengajuan) > 0) ? '' : 'disabled'; ?> data-toggle="modal" data-target="#confirm-modal">
								Terbitkan reward periode ini
							</button>


						<?php } ?>
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
							<th>nominal</th>
							<th>Tanggal</th>
							<!-- <th></th> -->
						</tr>
					</thead>
					<tbody>
						<?php foreach ($daftar_pengajuan as $pengajuan) { ?>
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
										<?= $pengajuan['nominal'];	?>
									</p>
								</td>
								<td>
									<p class="m-0">
										<?= $pengajuan['date'];	?>
									</p>
									<p class="badge m-0 badge-warning">
										<?= $pengajuan['time'];	?>
									</p>
								</td>
								<!-- <td>
									<button type="button" data-toggle="modal" id="<?= $pengajuan['id_penerbitan_pengajuan']; ?>" class="btn btn-primary btn-sm btn-reward" data-target="#exampleModal">
										edit reward
									</button>
								</td> -->
							</tr>
						<?php } ?>
					</tbody>
				</table>

			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal -->
<div class="modal fade" id="confirm-modal" tabindex="-1" aria-labelledby="confirm-modalLabel" aria-hidden="true">
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
</div>
<?= form_close(); ?>


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

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
							<th>Nominal (Rp)</th>
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
									<p class="m-0 text-right">
										<?= number_format($nominal, 2);	?>
									</p>
								</td>
								<td>
								<a href="" style="color:#fff;" title="Hapus" class="delete btn btn-sm  btn-circle btn-danger" data-href="<?= base_url('admin/periode/hapus/' . $pengajuan['id_penerbitan_pengajuan'] . '/' . $pengajuan['id_periode'] . '/' . $pengajuan['pengajuan_id'] ); ?>" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-alt"></i></a>

								</td>

								<!-- <td>
									<p class="m-0">
										<?= $pengajuan['date'];	?>
									</p>
									<p class="badge m-0 badge-warning">
										<?= $pengajuan['time'];	?>
									</p>
								</td> -->
								<!-- <td>
									<button type="button" data-toggle="modal" id="<?= $pengajuan['id_penerbitan_pengajuan']; ?>" class="btn btn-primary btn-sm btn-reward" data-target="#exampleModal">
										edit reward
									</button>
								</td> -->
							</tr>
						<?php 
					
					$total  += $nominal; 
					
					} ?>
					<tr>
						<th colspan="4" style="text-align: right;">Total</th>
						<th colspan="2">Rp <span style="float:right;"><?= number_format($total,2); ?></span></th>
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


<div class="modal fade" id="confirm-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Perhatian</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Tutuo">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Yakin ingin menghapus data ini?&hellip;</p>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
				<a class="btn btn-danger btn-ok">Hapus</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
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

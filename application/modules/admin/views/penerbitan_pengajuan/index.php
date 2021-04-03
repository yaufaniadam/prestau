<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			<div class="card-body">
				<?php if ($status_periode == 0) { ?>
					<?= form_open(base_url('admin/periode/bulan')); ?>
					<input type="hidden" name="id_periode" value="<?= $id_periode; ?>">
					<input type="submit" class="btn btn-success mb-2" value="terbitkan" name="submit">
					<?= form_close(); ?>
				<?php } ?>
				<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:1%"><input type="checkbox" name="" id="check_all"></th>
							<th style="width:40%">Perihal</th>
							<th style="width:35%">Judul Kegiatan</th>
							<th style="width:25%">Status</th>
							<th>Mahasiswa</th>
							<th>Tanggal</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($daftar_pengajuan as $pengajuan) { ?>
							<tr class="<? ($pengajuan['status_id'] == 2) ? 'proses' : ''; ?> <?= ($pengajuan['status_id'] == 4) ? 'perlu-revisi' : ''; ?>">
								<td class="text-center align-middle">
									<input type="checkbox" name="pengajuan_id[]" value="<?= $pengajuan['pengajuan_id']; ?>" class="check">
								</td>
								<td>
									<a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
										<?= $pengajuan['Jenis_Pengajuan']; ?></a>
								</td>
								<td class="table-<?= $pengajuan['badge']; ?>">
									<?php echo get_meta_value('judul', $pengajuan['pengajuan_id'], false); ?>
								</td>
								<td class="table-<?= $pengajuan['badge']; ?>"><?= $pengajuan['status_id']; ?> -
									<?= $pengajuan['status']; ?>
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
										<?= $pengajuan['date'];	?>
									</p>
									<p class="badge m-0 badge-warning">
										<?= $pengajuan['time'];	?>
									</p>
								</td>
								<td>
									<button type="button" data-toggle="modal" class="btn btn-primary btn-sm" data-target="#exampleModal">
										edit reward
									</button>
								</td>
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


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Reward</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div> -->
			<?= form_open('email/send'); ?>
			<div class="modal-body">
				<div class="form-group text-center">
					<label for="exampleInputEmail1">Masukkan nominal reward</label>
					<span id="emailHelp" class="form-text text-muted">contoh : 200000</span>
					<input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
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
</script>

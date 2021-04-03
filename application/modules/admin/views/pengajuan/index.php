<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			<div class="card-header">
				<a class="nav-s text-danger" href="<?= base_url("admin/pengajuan/index/" . $this->session->userdata('role')); ?>">
					<i class="fas fa-fw fa-exclamation-circle"></i>
					Tampilkan yang perlu diproses
				</a>
				</a>&nbsp;
				<a class=" nav-lilk" href="<?= base_url("admin/pengajuan/index/"); ?>">
					<i class="fas fa-fw fa-envelope"></i>
					Tampilkan semua pengajuan
				</a>
				<a class=" nav-lilk" href="<?= base_url("admin/pengajuan/verified/"); ?>">
					<i class="fas fa-fw fa-envelope"></i>
					Pengajuan Telah Diverifikasi
				</a>
			</div>
			<div class="card-body">
				<?php echo form_open_multipart(base_url("admin/pengajuan/verified/"), '') ?>
				<?php if ($query) {  ?>
					<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<th style="width:1%"><input type="checkbox" name="" id="check_all"></th>
								<th style="width:30%">Judul</th>
								<th style="width:20%">Kategori</th>
								<th style="width:20%">Status</th>
								<th>Mahasiswa</th>
								<th>Tanggal</th>
							</tr>
						</thead>
						<tbody>
							<?php

							foreach ($query as $pengajuan) {
							?>
								<tr class="<? ($pengajuan['status_id'] == 2) ? 'proses' : ''; ?> <?= ($pengajuan['status_id'] == 4) ? 'perlu-revisi' : ''; ?>">
									<td class="text-center align-middle">
										<input type="checkbox" name="pengajuan_id[]" value="<?= $pengajuan['pengajuan_id']; ?>" class="check">
									</td>
									<td>
										<a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
											<?= get_meta_value('judul', $pengajuan['pengajuan_id'], false) ?></a>
									</td>
									<td>
										<a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
											<?= $pengajuan['Jenis_Pengajuan']; ?></a>
									</td>
									<td class="table-<?= $pengajuan['badge']; ?>">
										<?= $pengajuan['status']; ?>
									</td>
									<td>
										<p class="m-0">
											<?= $pengajuan['FULLNAME']; ?>
										</p>
										<p class="badge m-0 badge-ijomuda">
											<?= $pengajuan['NAME_OF_DEPARTMENT']; ?>
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
									</td>
								</tr>
							<?php  } ?>
						</tbody>
					</table>
					<?php if (isset($verified) == 1) { ?>
						<div class="form-group row ml-0">
							<select name="periode_id" class="form-control col-sm-2 col-form-label mr-2" id="exampleFormControlSelect1">
								<?php foreach ($daftar_periode as $periode) { ?>
									<option value="<?= $periode['id_periode']; ?>"><?= $periode['nama_periode']; ?></option>
								<?php } ?>
							</select>
							<input type="submit" name="submit" class="btn btn-success" value="Simpan">
						</div>
					<?php } ?>
				<?php } ?>
				<?php echo form_close() ?>
			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


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
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				<a class="btn btn-danger btn-ok">Hapus</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		var state = false

		$('#check_all').click(function() {
			if (state == false) {
				state = true
				$('.check').prop('checked', true);
			} else {
				state = false
				$('.check').prop('checked', false);
			}
		});

		<?php if ($title != 'Pengajuan telah diverifikasi') { ?>
			$('#pengajuan-desc').DataTable({});
		<?php } ?>
	});
</script>
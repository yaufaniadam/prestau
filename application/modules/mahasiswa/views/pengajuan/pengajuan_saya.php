<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			<div class="card-header">
				<a class="nav-s text-danger" href="<?= base_url("admin/pengajuan/index/" . $this->session->userdata('role')); ?>">
					<i class="fas fa-fw fa-exclamation-circle"></i> Tampilkan yang perlu diproses</a>
				</a>&nbsp;
				<a class=" nav-lilk" href="<?= base_url("admin/pengajuan/index/"); ?>">
					<i class="fas fa-fw fa-envelope"></i> Tampilkan semua pengajuan</a>
			</div>
			<div class="card-body">asd
				<?php
				if ($query) {  ?>
					<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<th style="width:50%">Judul</th>
								<th style="width:50%">Kategori</th>
								<th style="width:20%">Jenis Pengajuan</th>
								<th>Mahasiswa</th>
								<th>Tanggal</th>
							</tr>
						</thead>
						<tbody>
							<!-- <pre>
								<?php print_r($query) ?>
							</pre> -->
							<?php
							foreach ($query as $pengajuan) {  ?>
								<tr class="<? ($pengajuan['status_id'] == 2) ? 'proses' : ''; ?> <?= ($pengajuan['status_id'] == 4) ? 'perlu-revisi' : ''; ?>">
									<td>
										<a class="judul" href="<?= base_url('mahasiswa/pengajuan/tambah/' . $pengajuan['pengajuan_id']); ?>">
											<?= $pengajuan['Jenis_Pengajuan']; ?></a>
									</td>
									<td class="table-<?= $pengajuan['badge']; ?>"> <?= get_meta_value('judul', $pengajuan['pengajuan_id'], false) ?></a>
									</td>
									<td class="table-<?= $pengajuan['badge']; ?>"><?= $pengajuan['status_id']; ?> -
										<?= $pengajuan['status']; ?>
									</td>
									<td>
										<p class="m-0">
											<?= $pengajuan['FULLNAME']; ?>
										</p>
										<p class="badge m-0 badge-ijomuda">
											<?= $pengajuan['NAME_OF_FACULTY']; ?>
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
						</tfoot>
					</table>
					<?php /* } else { */ ?>


				<?php }
				?>
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
		$('#pengajuan-desc').DataTable({

			<?php if ($this->session->userdata('role') == 1) { ?> "order": [
					[1, "asc"]
				]
			<?php } ?>
			<?php if ($this->session->userdata('role') == 5) { ?> "order": [
					[1, "desc"]
				]
			<?php } ?>


		});
	});
</script>

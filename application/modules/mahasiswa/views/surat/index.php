<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">

			<div class="card-body box-profile">
				<?php
				if ($query) {  ?>
					<table id="surat" class="table tb-surat table-bordered">

						<thead>
							<tr>
								<th>Perihal</th>

								<th style="width:200px;">Tanggal</th>
							</tr>
						</thead>
						<tbody>
							<?php

							foreach ($query as $surat) {
							?>
								<tr class="<?= ($surat['id_status'] == 2) ? 'proses' : ""; ?> <?= ($surat['id_status'] == '4') ? "perlu-revisi" : ""; ?>">
									<td><a class="judul" href="<?= base_url('mahasiswa/surat/tambah/' . $surat['id_surat']); ?>"><?= $surat['kategori_surat']; ?></a>
										<?php echo badge_status($surat['id_status']); ?>
										<br />
										<?= ($surat['id_status'] == '4') ? "<span class='badge badge-" . $surat['badge'] . "'><i class='fas fa-exclamation-triangle'></i> Dokumen persyaratan perlu diperbaiki</span>" : ""; ?>
										<?= ($surat['id_status'] == '1') ? "<span class='badge badge-" . $surat['badge'] . "'><i class='fas fa-exclamation-triangle'></i> Lengkapi Dokumen.</span>" : ""; ?>
									</td>

									<td>
										<p class="m-0"><?= $surat['date_full'];	?></p>
										<p class="badge m-0 badge-warning"><?= $surat['time']; ?></p>
									</td>
								</tr>
							<?php }   ?>
						</tbody>

					</table>
				<?php } else { ?>

					<p class="lead">Belum ada Surat</p>

				<?php } ?>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
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
	$('#surat').dataTable({
		"ordering": false
	});
</script>
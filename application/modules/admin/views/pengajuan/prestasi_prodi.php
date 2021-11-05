<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-body">
				<table id="pengajuan-desc" class="display table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>Judul Pengajuan</th>
							<th>Kategori Reward</th>
							<th>Periode Reward</th>
							<th>Reward</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($prestasi as $prestasi) { ?>
							<tr>
								<td><a href="<?= base_url('mahasiswa/pengajuan/detail_prestasi/' . $prestasi['id_penerbitan_pengajuan']); ?>"><?= get_meta_value('judul', $prestasi['id_pengajuan'], false); ?></a></td>
								<td><?= $prestasi['Jenis_Pengajuan']; ?></td>
								<td><?= $prestasi['nama_periode']; ?></td>
								<td class="text-right"><?= "<span class='float-left'>Rp</span>" . number_format($prestasi['nominal'], 2); ?></td>
								<td><?= $prestasi['status_pencairan'] == 1 ? "Sudah dicairkan pada: <br>" . $prestasi['tanggal_pencairan'] . "<br> oleh: " .$prestasi['penerima']  : "Belum dicairkan"; ?></td>
							</tr>
						<?php }
						?>
					</tbody>
					<tbody>
					</tbody>
				</table>
			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- DataTables -->
<script src=" <?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable();
	});
</script>

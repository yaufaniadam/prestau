<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<div class="card-body">
				<table id="pengajuan-desc" class="display table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>Judul Pengajuan</th>
							<th>Kategori Award</th>
							<th>Periode Award</th>
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
								<td><?= $prestasi['status_pencairan'] == 1 ? "sudah dicairkan" : "belum dicairkan"; ?></td>
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

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
								<td><a href="<?= base_url('mahasiswa/pengajuan/detail_prestasi/' . $prestasi['id_penerbitan_pengajuan']); ?>"><?= $prestasi['judul']; ?></a></td>
								<td><?= $prestasi['Jenis_Pengajuan']; ?></td>
								<td><?= $prestasi['nama_periode']; ?></td>

								<td class="text-right">
								<?php if($prestasi['nominal'] != 0) {
									echo "<span class='float-left'>Rp</span>" . number_format($prestasi['nominal'], 2);									
								} else {
									echo "<p class='text-left'><i class='fas fa-exclamation-triangle text-warning'></i> Reward di ketua kelompok.</p>";	
								}
								?>
									
								</td>
								
								<td><?= $prestasi['status_pencairan'] == 1 ? "<i class='fas fa-check-circle text-success'></i> Dicairkan oleh:<br> " .$prestasi['penerima'] . " (". $prestasi['tanggal_pencairan'] .")"  : "Belum dicairkan"; ?></td>
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

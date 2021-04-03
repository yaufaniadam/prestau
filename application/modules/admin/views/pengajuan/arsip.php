	<?php
	$last = $this->uri->total_segments();
	$id_dprt = $this->uri->segment($last - 1);
	$id_ktg = $this->uri->segment($last);
	?>

	<div class="row">
		<div class="col-12">
			<div class="card card-success card-outline">
				<div class="card-header">
					<div class="row">
						<div class="dropdown">
							<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?= $button_text; ?>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="<?= base_url('admin/pengajuan/arsip/0/' . $id_ktg); ?>">Semua Prodi</a>
								<?php foreach ($departments as $department) { ?>
									<a class="dropdown-item" href="<?= base_url('admin/pengajuan/arsip/' . $department['DEPARTMENT_ID'] . '/' . $id_ktg); ?>"><?= $department['NAME_OF_DEPARTMENT']; ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="ml-2"></div>
						<div class="dropdown">
							<button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButtons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?= $button_text_2; ?>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButtons">
								<a class="dropdown-item" href="<?= base_url('admin/pengajuan/arsip/' . $id_dprt . '/0'); ?>">Semua Kategori</a>
								<?php foreach ($kategories as $kategori) { ?>
									<a class="dropdown-item" href="<?= base_url('admin/pengajuan/arsip/' . $id_dprt . '/' . $kategori['Jenis_Pengajuan_Id']); ?>"><?= $kategori['Jenis_Pengajuan']; ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<?php
					if ($query) {  ?>
						<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
							<thead>
								<tr>
									<th style="width:50%">Perihal</th>
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
											<a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
												<?= $pengajuan['Jenis_Pengajuan']; ?></a>
										</td>
										<td class="table-<?= $pengajuan['badge']; ?>"><?= $pengajuan['status_id']; ?> -
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
							</tfoot>
						</table>
						<?php /* } else { */ ?>

						<p class="lead">Saat ini belum ada pengajuan yang perlu diproses</p>

					<?php }
					?>
				</div><!-- /.card-body -->
			</div><!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->

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

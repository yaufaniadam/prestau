<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			
		<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2 ) { 
			 $uri = $this->uri->uri_string();
			?>

			<ul class="nav nav-tabs pt-3 pl-4  bg-perak">
				<li class="nav-item">
					<a class="nav-link <?= 	($uri == 'admin/pengajuan/index/1') ? 'active' :''; ?>" href="<?= base_url("admin/pengajuan/index/" . $this->session->userdata('role')); ?>"><i class="fas fa-fw fa-exclamation-circle"></i> Perlu Diproses</a>
				</li>
		
				
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url("admin/pengajuan/verified/"); ?>"><i class="fas fa-fw fa-award"></i> Lolos Verifikasi</a>
				</li>

				<li class="nav-item">
					<a class="nav-link <?= 	($uri == 'admin/pengajuan/index') ? 'active' :''; ?>" href="<?= base_url("admin/pengajuan/index/"); ?>"><i class="fas fa-fw fa-envelope"></i> Semua Pengajuan</a>
				</li>
				
				
			</ul>
		
			<?php } ?>

			<div class="card-body">
				<?php echo form_open_multipart(base_url("admin/pengajuan/verified/"), '') ?>
		
					<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<th style="width:1%"><input type="checkbox" name="" id="check_all"></th>
								<th style="width:20%">Judul</th>
								<th class="sortir" style="width:20%">Kategori</th>
								<th class="sortir" style="width:14%">Status</th>
								<th>Mahasiswa</th>
								<th class="sortir">Prodi</th>
								<th>Tanggal</th>
								<th></th>
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
										
											<?= $pengajuan['Jenis_Pengajuan']; ?>
									</td>
									<td class="table-<?= $pengajuan['badge']; ?>">
										<?= $pengajuan['status']; ?>
									</td>
									<td>
											<?= $pengajuan['FULLNAME']; ?>									
									</td>
									<td>
										<?= $pengajuan['NAME_OF_DEPARTMENT']; ?>							
									</td>
									<td>
										<p class="m-0">
											<?php										
												echo konversi_date("j F Y", $pengajuan['date']);
											?>
										</p>
									
									</td>
									<td>
										<?php if ($pengajuan['status_id'] < 3) { ?>
											<a href="" style="color:#fff;" title="Hapus" class="delete btn btn-sm  btn-circle btn-danger" data-href="<?= base_url('admin/pengajuan/hapus/' . $pengajuan['pengajuan_id']); ?>" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-alt"></i></a>
										<?php } ?>
									</td>
								</tr>
							<?php  } ?>
						</tbody>
					</table>
					
				
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

			$(document).ready(function() {
			$('#pengajuan-desc').DataTable({
				initComplete: function() {
					this.api().columns('.sortir').every(function() {
						var column = this;
						var select = $('<select class="form-control"><option value=""></option></select>')
							.appendTo($(column.header()).empty())
							.on('change', function() {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);

								column
									.search(val ? '^' + val + '$' : '', true, false)
									.draw();
							});

						column.data().unique().sort().each(function(d, j) {
							select.append('<option value="' + d + '">' + d + '</option>')
						});
					});
				}
			});
		});



		<?php } ?>
	});
</script>
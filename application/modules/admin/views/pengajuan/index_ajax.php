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
			<div class="card-body">
				<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:50%">Perihal</th>
							<th style="width:20%">Status</th>
							<th>Mahasiswa</th>
							<th>Tanggal</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
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
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?= base_url('admin/pengajuan/getDataPengajuan'); ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"target": [-1],
				"orderable": false
			}]
			<?php /*if ($this->session->userdata('role') == 1) {*/ ?>
			// "order": [
			// 	[1, "asc"]
			// ]
			<?php /*}*/ ?>
			<?php /*if ($this->session->userdata('role') == 5) { */ ?>
			// "order": [
			// 	[1, "desc"]
			// ]
			<?php /*}*/ ?>
		});
	});
</script>

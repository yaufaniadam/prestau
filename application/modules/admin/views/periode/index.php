<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
		<?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2 ) { 
			 $uri = $this->uri->uri_string();
			?>

			<ul class="nav nav-tabs pt-3 pl-4  bg-perak">
				<li class="nav-item">
					<a class="nav-link <?= 	($uri == 'admin/periode/index/1') ? 'active' :''; ?>" href="<?= base_url("admin/periode/index/1"); ?>"><i class="fas fa-fw fa-exclamation-circle"></i> Sudah Diterbitkan</a>
				</li>
		
				<li class="nav-item">
					<a class="nav-link <?= 	($uri == 'admin/periode/index/0') ? 'active' :''; ?>" href="<?= base_url("admin/periode/index/0"); ?>"><i class="fas fa-fw fa-envelope"></i> 	Belum Diterbitkan</a>
				</li>
			
				<li class="nav-item">
					<a class="nav-link <?= 	($uri == 'admin/periode/tambah') ? 'active' :''; ?>" href="<?= base_url("admin/periode/tambah"); ?>"><i class="fas fa-fw fa-plus"></i> 	Tambah Periode</a>
				</li>
			
			</ul>
		
			<?php } ?>

		
			<div class="card-body">
				<table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
					<thead>
						<tr>
							<th style="width:50%">Periode</th>
							<th style="width:20%">Status</th>
							<th>Tanggal Terbit</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($daftar_periode as $periode) { ?>
							<tr>
								<td><a href="<?= base_url('admin/periode/bulan/' . $periode['id_periode']); ?>"><?= $periode['nama_periode']; ?></a></td>
								<td><?= $periode['status'] == 0 ? 'Belum Diterbitkan' : 'Sudah Diterbitkan'; ?></td>
								<td><?= $periode['tanggal'] == '' ? '-' : $periode['tanggal']; ?></td>
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

<!-- /.modal -->



<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable({});
	});
</script>

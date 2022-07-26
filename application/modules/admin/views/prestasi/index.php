<?php
	$last = $this->uri->total_segments();
	$id_dprt = $this->uri->segment($last - 1);
	$id_ktg = $this->uri->segment($last);
?>
<div class="row">
	<div class="col-12">
		<div class="card card-success card-outline">
			<!-- <div class="card-header">
				<div class="row">
					<div class="dropdown">
						<button class="btn btn-ijomuda dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?= $button_text; ?>
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="height:500px; overflow:auto;" >
							<a class="dropdown-item" href="<?= base_url('admin/prestasi/index/0/' . $id_ktg); ?>">Semua Prodi</a>
							<?php foreach ($departments as $department) { ?>
								<a class="dropdown-item" href="<?= base_url('admin/prestasi/index/' . $department['DEPARTMENT_ID'] . '/' . $id_ktg); ?>"><?= $department['NAME_OF_DEPARTMENT']; ?></a>
							<?php } ?>
						</div>
					</div>
					<div class="ml-2"></div>
					<div class="dropdown">
						<button class="btn btn-ijomuda dropdown-toggle" type="button" id="dropdownMenuButtons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?= $button_text_2; ?>
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButtons" style="height:500px; overflow:auto;" >
							<a class="dropdown-item" href="<?= base_url('admin/prestasi/index/' . $id_dprt . '/0'); ?>">Semua Kategori</a>
							<?php foreach ($kategories as $kategori) { ?>
								<a class="dropdown-item" href="<?= base_url('admin/prestasi/index/' . $id_dprt . '/' . $kategori['Jenis_Pengajuan_Id']); ?>"><?= $kategori['Jenis_Pengajuan']; ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div> -->

			<div class="card-body">
				<table id="pengajuan-desc" class="table table-bordered tb-pengajuans table-striped">
					<thead>
						<tr>
							<th style="width:20%">Kategori</th>
							<th style="width:35%">Judul/Nama/Kegiatan/Karya</th>
							<!-- <th style="width:10%">Status</th> -->
							<th>Mahasiswa</th>
							<th>Prodi</th>
							<th>Periode</th>
							<th style="width:10%">Reward</th>
							<th style="width:10px">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($daftar_prestasi as $prestasi) {
							$reward = $prestasi['nominal']; ?>
							<tr>
								<td><?= $prestasi['Jenis_Pengajuan']; ?></td>
								<td><?= get_meta_value('judul', $prestasi['id_pengajuan'], false ); ?></a></td>
								<td><?= $prestasi['FULLNAME']; ?></td>
								<td><?= $prestasi['NAME_OF_DEPARTMENT']; ?></td>
								<td><?= $prestasi['nama_periode']; ?></td>
								<td><?= ($reward > 0) ? number_format($reward) : 'Pada Tim'; ?></td>
								<td><a href="<?= base_url('admin/pengajuan/detail/' . $prestasi['id_pengajuan']); ?>"><i class="fas fa-folder-open"></i></a></td>
							</tr>
						<?php } ?>
				</table>

			</div><!-- /.card-body -->
		</div><!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<!-- Modal -->




<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		$('#pengajuan-desc').DataTable({
				initComplete: function() {
					this.api().columns([0,1,3,4]).every(function() {
						var column = this;
						var select = $('<select class="form-control"><option value=""> '+ + '</option></select>')
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

	$(".btn-pencairan").click(function() {
		var id_penerbitan_pengajuan = this.id;
		$("#id_penerbitan_pengajuan_field").val(id_penerbitan_pengajuan);
	});

	function confirmSubmit() {
		var agree = confirm("Yakin ingin menghapus data ini?");
		if (agree)
			return true;
		else
			return false;
	}

	$(".btn-reward").click(function() {
		console.log(this.id);
		var id_prestasi = this.id;
		$.ajax({
			url: "<?= base_url('/admin/periode/reward/'); ?>" + id_prestasi,
			success: function(data) {
				$("#nominal_reward").val(data)
				$("#id_prestasi_value").val(id_prestasi)
			}
		});
	});
</script>
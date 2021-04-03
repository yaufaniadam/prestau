<pre>
	<?php
	// print_r($query);
	$last = $this->uri->total_segments();
	$record_num = $this->uri->segment($last);
	?>
</pre>

<div class="row">
	<div class="col-12">

		<div class="card card-success card-outline">
			<div class="card-body">
				<table id="pengajuan-desc" class="display table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>Judul</th>
							<th>Status</th>
							<th>NIM</th>
							<th>Terakhir Dilihat Admin</th>
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

<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
	$(document).ready(function() {
		// console.log('asdf');
		// $('#pengajuan-desc').DataTable({
		// 	responsive: true,
		// 	// "destroy": true,
		// 	"processing": true,
		// 	"serverSide": true,
		// 	"order": [],
		// 	ajax: {
		// 		"url": "<?= base_url('mahasiswa/pengajuan/getpengajuansaya'); ?>",
		// 		"type": "POST"
		// 	},
		// 	"columnDefs": [{
		// 		"targets": [-1],
		// 		"orderable": false,
		// 		"width": 5
		// 	}],
		// });
		$('#pengajuan-desc').DataTable({
			ajax: {
				"url": "<?= base_url('mahasiswa/pengajuan/getpengajuansaya/' . $record_num); ?>",
				"type": "POST",
				"dataSrc": ""
			},
			columns: [{
					data: 'judul_karya'
				},
				{
					data: 'status'
				},
				{
					data: 'nim'
				},
				{
					data: 'date',
				},
				// {
				// 	data: 'FULLNAME'
				// },
				// {
				// 	data: 'NAME_OF_FACULTY'
				// }
			]
		});
	});
</script>
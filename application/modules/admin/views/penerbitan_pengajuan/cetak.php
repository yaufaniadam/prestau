<?php 
header("content-type:Application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_semua_data_penelitian.xls");
header("Pragma: no-cache");
header("Expires: 0");

?><!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Program Pascasarjana UMY">
	<meta name="author" content="Program Pascasarjana UMY">
	<meta name="robots" content="noindex, nofollow">

	<title><?= (isset($title)) ? $title : 'SIMKATMAWA UMY'; ?></title>

	<!-- Custom fonts for this template-->
	<link href="<?= base_url() ?>public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?= base_url() ?>public/dist/css/sb-admin-2.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>public/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>/public/vendor/summernote/summernote-bs4.min.css" rel="stylesheet">

	<!-- Bootstrap core JavaScript-->
	<script src="<?= base_url() ?>public/vendor/jquery/jquery.min.js"></script>
	<script src="<?= base_url() ?>public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?= base_url() ?>public/vendor/jquery-easing/jquery.easing.min.js"></script>
</head>

<body>
	<div class="row">
		<div class="col-12">
			<div class="card card-success card-outline">
				<div class="card-header">
					<div class="row">
						<div class="col-12 text-center">
							<h1 class="mb-0">Nama mahasiswa yang memperoleh reward pada periode ini</h1>
						</div>
					</div>
				</div>

				<div class="card-body">
					<table id="pengajuan-descs" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<!-- <th style="width:1%"><input type="checkbox" name="" id="check_all"></th> -->
								<th style="width:30%">Mahasiswa</th>
								<th style="width:20%">Perihal</th>
								<th style="width:35%">Judul Kegiatan</th>
								<th style="width:25%">Dokumen Pengajuan</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($pengajuan as $pengajuan) { ?>
								<tr>
									<td><?= $pengajuan['penerima']; ?></td>
									<td><?= $pengajuan['jenis_pengajuan']; ?></td>
									<td><?= $pengajuan['judul_kegiatan']; ?></td>
									<td>
										<?php foreach ($pengajuan['file'] as $file) { ?>
											<p class="">
												<a class="p-2 badge badge-primary badge-pill" href="<?= $file['file']; ?>"><?= $file['filename']; ?></a>
											</p>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>

				</div><!-- /.card-body -->
			</div><!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
</body>
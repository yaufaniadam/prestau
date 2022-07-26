<div class="col-12">
<div class="row">
		<div class="col-4 col-md-4 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
								Menunggu Diproses
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php if ($pengajuan_perlu_diproses > 0) { ?>
									<?= $pengajuan_perlu_diproses; ?>
								<?php } else { ?>
									-
								<?php } ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-envelope fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4 col-md-4 mb-4">
			<div class="card border-left-danger shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
								Sudah diverifikasi
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php if ($verified > 0) { ?>
									<?= $verified; ?>
								<?php } else { ?>
									-
								<?php } ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-envelope fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4 col-md-4 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Jumlah Prestasi
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php if ($prestasi > 0) { ?>
									<?= $prestasi; ?>
								<?php } else { ?>
									-
								<?php } ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-medal fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">


		<!-- Area Chart -->

		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-success">Per Bulan</h6>
				</div>

				<div class="card-body">

					<!-- <nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Grafik</a>
							<a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Tabel</a>
						</div>
					</nav> -->
					<!-- <div class="tab-content" id="nav-tabContent"> -->
						<!-- <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

							<div class="chart-area">
								<div class="chartjs-size-monitor">
									<div class="chartjs-size-monitor-expand">
										<div class=""></div>
									</div>
									<div class="chartjs-size-monitor-shrink">
										<div class=""></div>
									</div>
								</div>
								<canvas id="myAreaChart" width="668" height="320" class="chartjs-render-monitor" style="display: block; width: 668px; height: 320px;"></canvas>
							</div>


						</div> -->
						<!-- <div class="tab-pane fade pt-4" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"> -->
							<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans table-striped">
								<thead>
									<tr>
										<?php foreach ($nama_bulan as $bulan) { ?>
											<th style="width:20%"><?php echo get_nama_bulan($bulan['bulan']) ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<?php foreach ($nama_bulan as $bulan) { ?>
											<th style="width:20%">
												<i class="fas fa-medal text-warning"></i> <?php echo  get_jumlah_prestasi_perbulan($bulan['bulan'], $selected_year) ?>
											</th>
										<?php } ?>
									</tr>
								</tbody>
							</table>
						<!-- </div> -->

					<!-- </div> -->


				</div>
			</div>

		</div>

	</div>

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-success">Berdasarkan Kategori</h6>
				</div>
				<div class="card-body">
					<div>
						<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans  table-striped">
							<thead>
								<tr>
									<th>Kategori</th>
									<th>Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($jenis_pengajuan as $pengajuan) { ?>
									<tr>
										<th>
											<?= $pengajuan['Jenis_Pengajuan']; ?>
										</th>
										<th>
											<?= get_jumlah_prestasi_per_jenis_pengajuan($pengajuan['Jenis_Pengajuan_Id'], $selected_year); ?>
										</th>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- <div class="card shadow mb-4">
		
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-success">Kategori</h6>
				</div>
				<div class="card-body">
					<canvas id="horizontalBarChartCanvas"></canvas>
				</div>
			</div> -->
		</div>
	</div>

	<?php if ($_SESSION['role'] != 5) { ?>
		<div class="row">
			<div class="col-xl-12 col-lg-12">
				<div class="card shadow mb-4">
					<!-- Card Header - Dropdown -->
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-success">Prodi</h6>
					</div>
					<div class="card-body">
						<div>
							<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans  table-striped">
								<thead>
									<tr>
										<th>
											Prodi
										</th>
										<th>
											Jumlah
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach (get_jumlah_prestasi_per_prodi($selected_year) as $per_prodi) { 
										if($per_prodi['jumlah_pengajuan'] > 0) {?>
										<tr>
											<th style="width:20%">
												<?= $per_prodi['nama_prodi']; ?>
											</th>
											<th style="width:20%">
												<?= $per_prodi['jumlah_pengajuan']; ?>
											</th>
										</tr>
										<?php } //endif
									 } //endforeach
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- <div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-success">Prodi</h6>
					</div>
					<div class="card-body">
						<div class="chart-pie pt-4 pb-2">
							<div class="chartjs-size-monitor">
								<div class="chartjs-size-monitor-expand">
									<div class=""></div>
								</div>
								<div class="chartjs-size-monitor-shrink">
									<div class=""></div>
								</div>
							</div>
							<canvas id="prodi" width="301" height="245" class="chartjs-render-monitor" style="display: block; width: 301px; height: 245px;"></canvas>
						</div>
						<div class="mt-4 text-center small">
						</div>
					</div>
				</div> -->
			</div>
			<!-- <div class="col-xl-6 col-lg-6">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-success">Fakultas</h6>
				</div>
				<div class="card-body">
					<div class="chart-pie pt-4 pb-2">
						<div class="chartjs-size-monitor">
							<div class="chartjs-size-monitor-expand">
								<div class=""></div>
							</div>
							<div class="chartjs-size-monitor-shrink">
								<div class=""></div>
							</div>
						</div>
						<canvas id="fakultas" width="301" height="245" class="chartjs-render-monitor" style="display: block; width: 301px; height: 245px;"></canvas>
					</div>
					<div class="mt-4 text-center small">
					</div>
				</div>
			</div>
		</div> -->
		</div>
	<?php } ?>

</div>


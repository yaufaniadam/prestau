<div class="col-12">
	<div class="row">
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
								Pengajuan Perlu Diproses
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php if ($pengajuan_perlu_diproses > 0) { ?>
									<?= $pengajuan_perlu_diproses; ?> pengajuan
								<?php } else { ?>
									tidak ada
								<?php } ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
								Pengajuan Selesai
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								<?php if ($pengajuan_selesai > 0) { ?>
									<?= $pengajuan_selesai; ?> pengajuan
								<?php } else { ?>
									tidak ada
								<?php } ?>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-calendar fa-2x text-gray-300"></i>
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
					<h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan</h6>
				</div>

				<div>
					<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans">
						<thead>
							<tr>
								<?php foreach ($nama_bulan as $bulan) { ?>
									<th style="width:20%"><?php echo ($bulan['bulan']) ?></th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($nama_bulan as $bulan) { ?>
									<th style="width:20%"><?php echo  get_jumlah_pengajuan_perbulan($bulan['bulan']) ?></th>
								<?php } ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan</h6>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
							<div class="dropdown-header">Menu :</div>
							<a class="dropdown-item" href="#">Pertahun</a>
							<a class="dropdown-item" href="#">Perbulan</a>
						</div>
					</div>
				</div>
				<!-- Card Body -->
				<div class="card-body">
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
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Kategori</h6>
				</div>
				<div class="card-body">
					<div>
						<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans">
							<thead>
								<tr>
									<th>kategori</th>
									<th>jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($jenis_pengajuan as $pengajuan) { ?>
									<tr>
										<th>
											<?= $pengajuan['Jenis_Pengajuan']; ?>
										</th>
										<th>
											<?= get_jumlah_pengajuan_per_jenis_pengajuan($pengajuan['Jenis_Pengajuan_Id']); ?>
										</th>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Kategori</h6>
				</div>
				<div class="card-body">
					<canvas id="horizontalBarChartCanvas"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Prodi</h6>
				</div>
				<div class="card-body">
					<div>
						<table id="data-pengajuan-table" class="table table-bordered tb-pengajuans">
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
								<?php foreach (get_jumlah_pengajuan_per_prodi() as $per_prodi) { ?>
									<tr>
										<th style="width:20%">
											<?= $per_prodi['nama_prodi']; ?>
										</th>
										<th style="width:20%">
											<?= $per_prodi['jumlah_pengajuan']; ?>
										</th>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Prodi</h6>
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
			</div>
		</div>
		<!-- <div class="col-xl-6 col-lg-6">
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Fakultas</h6>
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

</div>

<script src="<?= base_url() ?>public/vendor/chart.js/Chart.min.js"></script>
<!-- PERBULAN -->
<script>
	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	function number_format(number, decimals, dec_point, thousands_sep) {
		// *     example: number_format(1234.56, 2, ',', ' ');
		// *     return: '1 234,56'
		number = (number + '').replace(',', '').replace(' ', '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}

	// Area Chart Example
	var ctx = document.getElementById("myAreaChart");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: {
			// labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			labels: [
				<?php foreach ($nama_bulan as $bulan) { ?> "<?php echo ($bulan['bulan']) ?>",
				<?php } ?>
			],
			datasets: [{
				label: "Surat: ",
				lineTension: 0.3,
				backgroundColor: "rgba(78, 115, 223, 0.05)",
				borderColor: "rgba(78, 115, 223, 1)",
				pointRadius: 3,
				pointBackgroundColor: "rgba(78, 115, 223, 1)",
				pointBorderColor: "rgba(78, 115, 223, 1)",
				pointHoverRadius: 3,
				pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
				pointHoverBorderColor: "rgba(78, 115, 223, 1)",
				pointHitRadius: 10,
				pointBorderWidth: 2,
				data: [<?php foreach ($nama_bulan as $bulan) {
							echo get_jumlah_pengajuan_perbulan($bulan['bulan']) . ",";
						} ?>],
			}],
		},
		options: {
			maintainAspectRatio: false,
			layout: {
				padding: {
					left: 10,
					right: 25,
					top: 25,
					bottom: 0
				}
			},
			scales: {
				xAxes: [{
					time: {
						unit: 'date'
					},
					gridLines: {
						display: false,
						drawBorder: false
					},
					ticks: {
						maxTicksLimit: 7
					}
				}],
				yAxes: [{
					ticks: {
						maxTicksLimit: 5,
						padding: 10,
						// Include a dollar sign in the ticks
						callback: function(value, index, values) {
							return number_format(value);
						}
					},
					gridLines: {
						color: "rgb(234, 236, 244)",
						zeroLineColor: "rgb(234, 236, 244)",
						drawBorder: false,
						borderDash: [2],
						zeroLineBorderDash: [2]
					}
				}],
			},
			legend: {
				display: false
			},
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				titleMarginBottom: 10,
				titleFontColor: '#6e707e',
				titleFontSize: 14,
				borderColor: '#dddfeb',
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				intersect: false,
				mode: 'index',
				caretPadding: 10,
				callbacks: {
					label: function(tooltipItem, chart) {
						var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
						return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
					}
				}
			}
		}
	});
</script>

<!-- PER FAKULTAS -->
<script>
	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	// Pie Chart Example
	var ctx = document.getElementById("fakultas");
	var myPieChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: [<?php foreach ($jenis_pengajuan as $pengajuan) { ?> "<?= $pengajuan['Jenis_Pengajuan']; ?>",
				<?php } ?>
			],
			datasets: [{
				data: [<?php foreach ($jenis_pengajuan as $pengajuan) { ?>
						<?= get_jumlah_pengajuan_per_jenis_pengajuan($pengajuan['Jenis_Pengajuan_Id']); ?>,
					<?php } ?>
				],
				backgroundColor: ['#4e73df', '#1cc88a'],
				hoverBackgroundColor: ['#2e59d9', '#17a673'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				borderColor: '#dddfeb',
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				caretPadding: 10,
			},
			legend: {
				display: false
			},
			cutoutPercentage: 80,
		},
	});
</script>

<!-- PER PRODI -->
<script>
	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	// Pie Chart Example
	var ctx = document.getElementById("prodi");
	var myPieChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: [<?php foreach (get_jumlah_pengajuan_per_prodi() as $per_prodi) { ?> "<?= $per_prodi['nama_prodi']; ?>",
				<?php } ?>
			],
			datasets: [{
				data: [<?php foreach (get_jumlah_pengajuan_per_prodi() as $per_prodi) { ?>
						<?= $per_prodi['jumlah_pengajuan']; ?>,
					<?php } ?>
				],
				backgroundColor: ['#1CC88A', '#1CC88A'],
				hoverBackgroundColor: ['#2e59d9', '#17a673'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: {
			maintainAspectRatio: false,
			tooltips: {
				backgroundColor: "rgb(255,255,255)",
				bodyFontColor: "#858796",
				borderColor: '#dddfeb',
				borderWidth: 1,
				xPadding: 15,
				yPadding: 15,
				displayColors: false,
				caretPadding: 10,
			},
			legend: {
				display: false
			},
			cutoutPercentage: 80,
		},
	});
</script>

<!-- PER KATEGORI -->
<script>
	Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#858796';

	var horizontalBarChart = new Chart(horizontalBarChartCanvas, {
		type: 'horizontalBar',
		data: {
			labels: [<?php foreach ($jenis_pengajuan as $pengajuan) { ?> "<?= $pengajuan['Jenis_Pengajuan']; ?>",
				<?php } ?>
			],
			datasets: [{
				data: [<?php foreach ($jenis_pengajuan as $pengajuan) { ?>
						<?= get_jumlah_pengajuan_per_jenis_pengajuan($pengajuan['Jenis_Pengajuan_Id']); ?>,
					<?php } ?>
				],
				backgroundColor: [
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
					"#1CC88A",
				],
			}]
		},
		options: {
			tooltips: {
				enabled: true
			},
			responsive: true,
			legend: {
				display: false,
				position: 'bottom',
				fullWidth: true,
				labels: {
					boxWidth: 10,
					padding: 50
				}
			},
			scales: {
				yAxes: [{
					barPercentage: 0.75,
					gridLines: {
						display: true,
						drawTicks: true,
						drawOnChartArea: true
					},
					ticks: {
						fontColor: '#555759',
						fontFamily: 'Nunito',
						fontSize: 11
					}
				}],
				xAxes: [{
					gridLines: {
						display: true,
						drawTicks: false,
						tickMarkLength: 1,
						drawBorder: false
					},
					ticks: {
						padding: 5,
						beginAtZero: true,
						fontColor: '#555759',
						fontFamily: 'Nunito',
						fontSize: 11,
						callback: function(label, index, labels) {
							return label / 1;
						}

					},
				}]
			}
		}
	});
</script>

<script>
	$('#data-pengajuan-table').DataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
	});
</script>

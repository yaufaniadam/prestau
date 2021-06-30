<div class="col-12">
	<div class="row">
		<div class="col-6 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
								Pengajuan Perlu Diproses
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
								
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-envelope fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Jumlah Prestasi
							</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">
						
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
					<h6 class="m-0 font-weight-bold text-primary">Dalam Satu Tahun</h6>
				</div>

				<div class="card-body">

					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Grafik</a>
							<a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Tabel</a>
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

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

		</div>

	</div>

	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Berdasarkan Kategori</h6>
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
			labels: [
				<?php foreach ($nama_bulan as $bulan) { ?> "<?php echo get_nama_bulan($bulan['bulan']) ?>",
				<?php } ?>
			],
			
			datasets: [{
				label: "Pengajuan: ",
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
				data: [1,2,3,3,5,6,9,3,6,7,11,11],
			},
			{
				label: "Prestasi: ",
				lineTension: 0.3,
				backgroundColor: "rgba(251, 200, 0, 0.05)",
				borderColor: "rgba(251, 200, 0, 1)",
				pointRadius: 3,
				pointBackgroundColor: "rgba(230, 174, 22, 1)",
				pointBorderColor: "rgba(230, 174, 22, 1)",
				pointHoverRadius: 3,
				pointHoverBackgroundColor: "rgba(251, 200, 0, 1)",
				pointHoverBorderColor: "rgba(251, 200, 0, 1)",
				pointHitRadius: 10,
				pointBorderWidth: 2,
				data: [0,1,3,2,4,4,8,2,6,5,9,10],
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



<!-- <script>
	$('#data-pengajuan-table').DataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
	});
</script> -->
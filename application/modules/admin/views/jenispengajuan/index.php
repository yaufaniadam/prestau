<div class="row">
	<div class="col-md-12">
		<div class="card card-success card-outline">


			<div class="card-body box-profile">
				<table id="kategorisurat" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Jenis Pengajuan</th>
							<th>Induk</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($jenis_pengajuan as $jenis) :
							if ($jenis['parent'] == 0 && check_child($jenis['Jenis_Pengajuan_Id'])->num_rows() == 0) {


								echo "<tr>";

								echo "<td>" . $jenis['Jenis_Pengajuan'];
								// echo "<br>";

								// print_r(check_child($jenis['Jenis_Pengajuan_Id'])->num_rows());

								echo "</td>";
								echo "<td>" . $jenis['parent'] . "</td>";

								echo "<td class='text-center'><a class='btn btn-info btn-sm' href='" . base_url('admin/jenispengajuan/edit/' . $jenis['Jenis_Pengajuan_Id']) . "'><i class='fas fa-pencil-alt'></i> Edit</a></td>";
								echo "</tr>";
							} else if ($jenis['parent'] > 0 || check_child($jenis['Jenis_Pengajuan_Id'])->num_rows() > 0) {
								echo "<tr>";

								echo "<td>" . $jenis['Jenis_Pengajuan'];
								// echo "<br>";

								// print_r(check_child($jenis['Jenis_Pengajuan_Id'])->num_rows());

								echo "</td>";
								echo "<td>" . $jenis['parent'] . "</td>";

								echo "<td class='text-center'><a class='btn btn-info btn-sm' href='" . base_url('admin/jenispengajuan/edit/' . $jenis['Jenis_Pengajuan_Id']) . "'><i class='fas fa-pencil-alt'></i> Edit</a></td>";
								echo "</tr>";
							}
						endforeach;
						?>

					</tbody>

				</table>
			</div>
		</div>
	</div>
</div>

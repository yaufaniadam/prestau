<div class="row">
	<div class="col-md-12">
		<div class="card card-success card-outline">

			<div class="card-body box-profile">
				<table id="kategorisurat" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Jenis Pengajuan</th>
							<th>Nominal</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($penghargaan as $penghargaan) { ?>
							<tr>
								<td><?= $penghargaan['Jenis_Pengajuan']; ?></td>
								<td><?= $penghargaan['nominal']; ?></td>
								<td class='text-center'>
									<a class='btn btn-info btn-sm' href="<?= base_url('admin/jenispengajuan/edit/' . $penghargaan['Jenis_Pengajuan_Id']); ?>"><i class='fas fa-pencil-alt'></i>
										Edit
									</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

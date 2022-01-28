<div class="row">
	<div class="col-md-12">
		<div class="card card-success card-outline">
			<ul class="nav nav-tabs pt-3 pl-4  bg-perak">
				<li class="nav-item">
					<a class="nav-link active" href="<?= base_url('admin/jenispengajuan'); ?>"><i class="fas fa-fw fa-table"></i> Jenis pengajuan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url('admin/jenispengajuan/tambah'); ?>"><i class="fas fa-fw fa-plus"></i> Tambah Jenis pengajuan</a>
				</li>			
			</ul>
			<div class="card-body box-profile">
				<table id="kategorisurat" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Jenis Pengajuan</th>
							<th>Kategori</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($jenis_pengajuan as $jenis) : ?>

							<tr>
								<td><?= $jenis['Jenis_Pengajuan']; ?></td>
								<td><?= $jenis['kategori_pengajuan']; ?></td>
								<td class="text-center"><a class="btn btn-info btn-sm" href="<?= base_url('admin/jenispengajuan/edit/' . $jenis['Jenis_Pengajuan_Id']) ;?>"><i class='fas fa-pencil-alt'></i> Edit</a></td>
							</tr>

						<?php endforeach;	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

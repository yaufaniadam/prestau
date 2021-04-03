<div class="row">
	<div class="col-12">
		<!-- fash message yang muncul ketika proses penghapusan data berhasil dilakukan -->
		<?php if ($this->session->flashdata('msg') != '') : ?>
			<div class="alert alert-success flash-msg alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Sukses!</h4>
				<?= $this->session->flashdata('msg'); ?>
			</div>
		<?php endif; ?>


		<div class="card">
			<div class="card-header py-3">
				<a href="<?= base_url('admin/pengguna/tambah') ?>" class="btn btn-sm btn-perak btn-icon-split">
					<span class="icon text-white-50">
						<i class="fas fa-plus"></i>
					</span>
					<span class="text">Tambah Pengguna</span>
				</a> &nbsp;


				<span class="float-right">
					<select id="selectload">
						<option value="">-- Select Role --</option>
						<?php foreach ($role as $role) {
							echo "<option value='" . $role['role_id'] . "'>" . $role['role'] . "</option>";
						} ?>
					</select>
				</span>

			</div>

			<div class="card-body">

				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th style="width:40%">Username</th>
							<th class="text-center">Email</th>
							<th class="text-center">Role</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pengguna->result_array() as $row) {  ?>
							<tr>
								<td><?= $row['username']; ?></td>
								<td><?= $row['email']; ?></td>
								<td><?= $row['role']; ?></td>

								<td class="text-center">
									<a class="btn btn-warning btn-sm btn-circle" href="<?= base_url('admin/pengguna/detail/' . $row['id']); ?>">
										<i class="fa fa-search"></i>
									</a>
									<a class="btn btn-info btn-sm btn-circle" href="<?= base_url('admin/pengguna/edit/' . $row['id']); ?>">
										<i class="fas fa-pencil-alt"></i>
									</a>
									<a href="" style="color:#fff;" title="Hapus" class="delete btn btn-sm  btn-circle btn-danger" data-href="<?= base_url('admin/pengguna/hapus/' . $row['id']); ?>" data-toggle="modal" data-target="#confirm-delete"> <i class="fa fa-trash-alt"></i></a>
								</td>
							</tr>
						<?php } ?>

					</tbody>
					</tfoot>
				</table>
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->


<div class="modal fade" id="confirm-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Perhatian</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Tutuo">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Yakin ingin menghapus data ini?&hellip;</p>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-dark" data-dismiss="modal">Batal</button>
				<a class="btn btn-danger btn-ok">Hapus</a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

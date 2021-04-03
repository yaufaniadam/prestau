<div class="row">
	<div class="col-md-12">

		<?php if (isset($msg) || validation_errors() !== '') : ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="fa fa-exclamation"></i> Terjadi Kesalahan</h4>
				<?= validation_errors(); ?>
				<?= isset($msg) ? $msg : ''; ?>
			</div>
		<?php endif; ?>

	</div>

	<div class="col-md-6">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">

				<?php echo form_open_multipart(base_url('admin/pengguna/tambah'), '') ?>
				<div class="form-group">
					<label for="username" class="control-label">Username</label>
					<div class="">
						<input type="text" name="username" class="form-control <?= (form_error('username')) ? 'is-invalid' : ''; ?>" id="username" placeholder="" value="<?php if (validation_errors()) {
																																												echo set_value('username');
																																											}  ?>">
						<span class="text-danger"><?php echo form_error('username'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="control-label">Email</label>
					<div>
						<input type="email" name="email" class="form-control <?= (form_error('email')) ? 'is-invalid' : ''; ?>" id="email" placeholder="" value="<?php if (validation_errors()) {
																																										echo set_value('email');
																																									}  ?>">
						<span class="text-danger"><?php echo form_error('email'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label for="password" class="control-label">Password</label>
					<div>
						<input type="password" name="password" class="form-control <?= (form_error('password')) ? 'is-invalid' : ''; ?>" id="password">
						<span class="text-danger"><?php echo form_error('password'); ?></span>
					</div>
				</div>
				<div class="form-group">
					<label for="nama" class="control-label">Nama Lengkap</label>
					<div class="">
						<input type="text" name="nama" class="form-control <?= (form_error('nama')) ? 'is-invalid' : ''; ?>" id="nama" placeholder="" value="<?php if (validation_errors()) {
																																									echo set_value('nama');
																																								}  ?>">
						<span class="text-danger"><?php echo form_error('nama'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label for="role" class="control-label">Role</label>
					<div>
						<select name="role" class="form-control <?= (form_error('role')) ? 'is-invalid' : ''; ?>">
							<option value="">Pilih Role</option>
							<?php foreach ($role as $row) { ?>
								<option value="<?= $row['role_id']; ?>" <?= (set_select('role', $row['role_id'])) ?>><?= $row['role']; ?></option>
							<?php } ?>
						</select>
						<span class="text-danger"><?php echo form_error('role'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<div>
						<input type="submit" name="submit" value="Tambah Pengguna" class="btn btn-md btn-perak">

					</div>
				</div>
				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</div>

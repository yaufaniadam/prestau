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

	<div class="col-md-5">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">

				<?php echo form_open_multipart(base_url('admin/pengguna/edit/' . $user['id']), 'class="form-horizontal"');  ?>

				<div class="form-group">
					<label for="username" class="control-label">Username</label>
					<div class="">
						<input type="text" value="<?= $user['username']; ?>" class="form-control" disabled id="username" placeholder="">

					</div>
				</div>


				<div class="form-group">
					<label for="nama" class="control-label">Nama Lengkap</label>
					<div class="">
						<input type="text" value="<?= $user['fullname']; ?>" name="nama" class="form-control" id="nama" placeholder="">

					</div>
				</div>

				<div class="form-group">
					<label for="email" class="control-label">Email</label>
					<div>
						<input type="email" value="<?= $user['email']; ?>" name="email" class="form-control" id="email" placeholder="">
					</div>
				</div>

				<div class="form-group">
					<label for="password" class="control-label">Password</label>
					<div>
						<input type="password" name="password" class="form-control" id="password" placeholder="">
						<input type="hidden" name="password_hidden" value="<?= $user['password']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="control-label">Role</label>
					<div>
						<select id="selectload" class="form-control" name="role">
							<?php foreach ($role as $role) { ?>
								<option value="<?= $role['role_id'] ?>" <?= ($user['role'] == $role['role_id']) ? "selected" : ""; ?>><?= $role['role'] ?> </option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group prodi">
					<label for="prodi" class="control-label">Pilih Prodi</label>
					<div>
						<select name="prodi" class="prodi form-control <?= (form_error('prodi')) ? 'is-invalid' : ''; ?>">
							<option value="">Pilih Prodi</option>
							<?php foreach ($prodi as $prodi) { ?>				
								<option value="<?= $prodi['DEPARTMENT_ID']; ?>" <?= ($user['prodi'] == $prodi['DEPARTMENT_ID']) ? "selected"  : ""; ?> ><?= $prodi['NAME_OF_DEPARTMENT']; ?> (<?= $prodi['DEPARTMENT_ID']; ?>)</option>
							<?php 
							}
							 ?>
						</select>
						<span class="text-danger"><?php echo form_error('prodi'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<div>
						<input type="submit" name="submit" value="Edit Pengguna" class="btn btn-perak">
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>


</div>

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

				<?php echo form_open_multipart(base_url('admin/periode/tambah'), '') ?>

				<div class="form-group">
					<label for="exampleInputEmail1">Nama Periode</label>
					<input type="text" name="nama_periode" class="form-control <?= (form_error('nama_periode')) ? 'is-invalid' : ''; ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
					<span class="text-danger"><?php echo form_error('nama_periode'); ?></span>
				</div>

				<div class="form-group">
					<div>
						<input type="submit" name="submit" value="Tambah Periode" class="btn btn-md btn-perak">

					</div>
				</div>
				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</div>

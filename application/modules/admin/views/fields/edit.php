<h1 class="h3 mb-4 text-gray-900">Edit Field</h1>

<div class="mr-auto ml-auto">
	<div class="row">
		<div class="col-md-8 mb-4">
			<div class="card shadow">
				<div class="d-block card-header pt-3 pb-2 bg-abumuda" role="button" aria-expanded="true" aria-controls="collKeterangan">
					<p class="h6 font-weight-bold text-white">Keterangan</p>
				</div>
				<div class="card-body">
					<?php echo form_open(base_url('admin/fields/edit/' . $field->field_id)) ?>

					<div class="form-group row">
						<label for="staticEmail" class="col-sm-2 col-form-label">Nama Field</label>
						<div class="col-sm-10">
							<input type="text" name="field" class="form-control <?= (form_error('field') ? 'is-invalid' : ''); ?>" value="<?= $field->field ?>">
							<span class="text-danger"><?= form_error('field'); ?></span>
						</div>
						<?php print_r(form_error()) ?>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Key</label>
						<div class="col-sm-10">
							<input type="text" name="key" class="form-control <?= (form_error('key') ? 'is-invalid' : ''); ?>" value="<?= $field->key ?>">
							<span class="text-danger"><?= form_error('key'); ?></span>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Type</label>
						<div class="col-sm-10">
							<select name="type" class="form-control">
								<option disabled>pilih type</option>
								<option <?= $field->type == 'image' ? 'selected' : ''; ?> value="image">Image</option>
								<option <?= $field->type == 'text' ? 'selected' : ''; ?> value="text">Text</option>
								<option <?= $field->type == 'textarea' ? 'selected' : ''; ?> value="textarea">Textarea</option>
								<option <?= $field->type == 'deskripsi' ? 'selected' : ''; ?> value="date_range">Date Range</option>
							</select> </div>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-2 col-form-label">Deskripsi</label>
						<div class="col-sm-10">
							<input type="text" name="deskripsi" class="form-control <?= (form_error('deskripsi') ? 'is-invalid' : ''); ?>" value="<?= $field->deskripsi ?>">
							<span class="text-danger"><?= form_error('deskripsi'); ?></span>
						</div>
					</div>

					<input type="submit" name="submit" value="simpan">

					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<!-- /.col -->
	</div>
</div>

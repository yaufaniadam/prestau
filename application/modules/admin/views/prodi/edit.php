<?php echo form_open_multipart(base_url('admin/prodi/edit/' . $prodi['id']), 'class="form-horizontal"');  ?>

<div class="row">
	<div class="col-md-12">

		<!-- fash message yang muncul ketika proses penghapusan data berhasil dilakukan -->
		<?php if ($this->session->flashdata('msg') != '') : ?>
			<div class="alert alert-success flash-msg alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Sukses!</h4>
				<?= $this->session->flashdata('msg'); ?>
			</div>
		<?php endif; ?>

	</div>


	<div class="col-md-8">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">


				<div class="form-group row">
					<label for="prodi" class="col-md-3 control-label"><?= ($prodi['id'] != 11) ? 'Program Studi' : 'Nama Program'; ?></label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('prodi') : $prodi['prodi'];  ?>" name="prodi" class="form-control <?= (form_error('prodi')) ? 'is-invalid' : ''; ?>" id="prodi">

						<span class="invalid-feedback"><?php echo form_error('prodi'); ?></span>

					</div>
				</div>
				<div class="form-group row">
					<label for="singkatan" class="col-md-3 control-label">Singkatan</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('singkatan') : $prodi['singkatan'];  ?>" name="singkatan" class="form-control <?= (form_error('singkatan')) ? 'is-invalid' : ''; ?>" id="singkatan">

						<span class="invalid-feedback"><?php echo form_error('singkatan'); ?></span>

					</div>
				</div>


				<div class="form-group row">
					<label for="kode" class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<input type="submit" name="submit" value="Simpan Data" class="btn btn-perak btn-block">
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-4">
		<?php if ($prodi['id'] != 11) { ?>
			<div class="card card-success card-outline">
				<div class="card-body box-profile">

					<label for="kode" class=" control-label">Admin Prodi</label>
					<ul style="list-style: none;" class="admin_prodi list-group pl-0 <?= (form_error('admin_prodi[]')) ? 'is-invalid' : ''; ?>">
						<?php

						$explode = explode(',', $prodi['admin_prodi']);


						foreach ($admin_prodi as $admin) { ?>
							<li class="list-group-item <?= (form_error('admin_prodi[]')) ? 'is-eror' : ''; ?> 
						<?= ((validation_errors()) ? '' : (in_array($admin['id'], $explode))) ? 'active' : ''; ?>">
								<input class="checkbox_admin_prodi" type="checkbox" value="<?= $admin['id']; ?>" name="admin_prodi[]" <?php $check = (in_array($admin['id'], $explode)) ? 'checked' : ''; ?> <?= (validation_errors()) ? set_checkbox('admin_prodi[]', $admin['id']) : $check; ?> />
								<?= $admin['fullname']; ?>
							</li>
						<?php } // endforeach 
						?>
					</ul>
					<span class="text-danger" style="line-height:1.5rem;font-size: 80%;"><?php echo form_error('admin_prodi[]'); ?></span>

				</div>
			</div>
		<?php } ?>
		<div class="card card-success mt-3 card-outline">
			<div class="card-body box-profile">

				<label for="kode" class=" control-label"><?= ($prodi['id'] != 11) ? 'Kepala Prodi' : 'Direktur Pascasarjana'; ?></label>
				<ul style="list-style: none;" class="ka_prodi list-group pl-0 <?= (form_error('ka_prodi[]')) ? 'is-invalid' : ''; ?>">
					<?php

					$explode = explode(',', $prodi['ka_prodi']);


					foreach ($ka_prodi as $ka_prodi) { ?>
						<li class="list-group-item <?= (form_error('ka_prodi[]')) ? 'is-eror' : ''; ?> 
						<?= ((validation_errors()) ? '' : (in_array($ka_prodi['id'], $explode))) ? 'active' : ''; ?>">
							<input class="checkbox_admin_prodi" type="checkbox" value="<?= $ka_prodi['id']; ?>" name="ka_prodi[]" <?php $check = (in_array($ka_prodi['id'], $explode)) ? 'checked' : ''; ?> <?= (validation_errors()) ? set_checkbox('ka_prodi[]', $ka_prodi['id']) : $check; ?> />
							<?= $ka_prodi['fullname']; ?>
						</li>
					<?php } // endforeach 
					?>
				</ul>
				<span class="text-danger" style="line-height:1.5rem;font-size: 80%;"><?php echo form_error('ka_prodi[]'); ?></span>

			</div>
		</div>
	</div>
	<script>
		$(document).on('change', '.checkbox_admin_prodi', function() {
			if (this.checked) {
				$(this).parent('li.list-group-item').addClass('active');
			} else {
				$(this).parent('li.list-group-item').removeClass('active');
			}
		})

		$('.checkbox_admin_prodi:checked').parent('li.list-group-item').addClass('active');
	</script>


	<?php echo form_close(); ?>


</div>
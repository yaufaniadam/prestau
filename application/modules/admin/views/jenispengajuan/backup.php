<?php
list($kat, $result) = $kategori;
$selected_kat = array_column($result, 'field_id');

echo form_open_multipart(base_url('admin/jenispengajuan/edit/' . $kat['Jenis_Pengajuan_Id']), 'class="form-horizontal"');

?>

<div class="row">
	<div class="col-md-12">

		<pre>
		<?php /*print_r($all_fields);*/ ?>
	</pre>

		<!-- fash message yang muncul ketika proses penghapusan data berhasil dilakukan -->
		<?php if ($this->session->flashdata('msg') != '') : ?>
			<div class="alert alert-success flash-msg alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4>Sukses!</h4>
				<?= $this->session->flashdata('msg'); ?>
			</div>
		<?php endif; ?>
		<?php if (isset($msg) || validation_errors() !== '') : ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="fa fa-exclamation"></i> Terjadi Kesalahan</h4>
				<?= validation_errors(); ?>
				<?= isset($msg) ? $msg : ''; ?>
			</div>
		<?php endif; ?>

	</div>

	<div class="col-md-8">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Jenis Pengajuan</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : $kat['Jenis_Pengajuan'];  ?>" name="Jenis_Pengajuan" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
						<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
					</div>
				</div>

				<div class="form-group row">
					<label for="deskripsi" class="col-md-3 control-label">Deskripsi</label>
					<div class="col-md-9">

						<div class="<?= (form_error('deskripsinya')) ? 'summernote-is-invalid' : ''; ?>"><textarea name="deskripsinya" class="textarea-summernote"><?= (validation_errors()) ? set_value('deskripsinya') : $kat['deskripsi'];  ?></textarea>
						</div>
						<span class="text-danger" style="font-size: 80%;"><?php echo form_error('deskripsinya'); ?></span>
					</div>
				</div>

				<div class="form-group row">
					<label for="kode" class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<input type="submit" name="submit" value="Edit Kategori Surat" class="btn btn-perak btn-block">
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">

				<label for="kode" class=" control-label">Formulir Isian</label>
				<ul style="list-style: none;" class="keterangan_surat list-group pl-0 <?= (form_error('kat_keterangan_surat[]')) ? 'is-invalid' : ''; ?>">
					<?php

					// $explode = explode(',', $kat['kat_keterangan_surat']);

					foreach ($all_fields as $field) { ?>
						<li class="list-group-item <?= (form_error('kat_keterangan_surat[]')) ? 'is-eror' : ''; ?>
						<?= ((validation_errors()) ? '' : (in_array($field['field_id'], $selected_kat))) ? 'active' : ''; ?>">
							<input class="checkbox_keterangan_surat" type="checkbox" value="<?= $field['field_id']; ?>" name="kat_keterangan_surat[]" <?php $check = ($field['field_id']) ? 'checked' : ''; ?> <?= (validation_errors()) ? set_checkbox('kat_keterangan_surat[]', $field['field_id']) : $check; ?> />
							<?= $field['field']; ?>
						</li>
					<?php } // endforeach 
					?>
				</ul>
				<span class="text-danger" style="line-height:1.5rem;font-size: 80%;"><?php echo form_error('kat_keterangan_surat[]'); ?></span>

			</div>
		</div>
	</div>
	<script>
		$(document).on('change', '.checkbox_keterangan_surat', function() {
			if (this.checked) {
				$(this).parent('li.list-group-item').addClass('active');
			} else {
				$(this).parent('li.list-group-item').removeClass('active');
			}
		});
		$('.checkbox_keterangan_surat:checked').parent('li.list-group-item').addClass('active');
	</script>

</div>
<?php echo form_close(); ?>

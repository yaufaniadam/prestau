<?php
echo form_open_multipart(base_url('admin/jenispengajuan/tambah/'), 'class="form-horizontal"');
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
	.alert.simpan {
		display: none;
	}

	#sortable1 {
		width: 100%;
	}

	#sortable2 {
		width: 100%;
	}

	#sortable1,
	#sortable2 {
		border: 1px solid #eee;

		min-height: 20px;
		list-style-type: none;
		margin: 0;
		padding: 5px 0 0 0;
		float: left;
		margin-right: 10px;
	}

	#sortable1 li,
	#sortable2 li {
		margin: 0 5px 5px 5px;
		padding: 5px;
		font-size: 14px;
		cursor: move;
	}

	.error {
		color: red;
	}
</style>

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
		<?php if (isset($msg) || validation_errors() !== '') : ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="fa fa-exclamation"></i> Terjadi Kesalahan</h4>
				<?= validation_errors(); ?>
				<?= isset($msg) ? $msg : ''; ?>
			</div>
		<?php endif; ?>

	</div>

	<div class="col-md-12">
		<div class="card card-success card-outline">
			<div class="card-body box-profile">

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Jenis Pengajuan</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : '';  ?>" name="Jenis_Pengajuan" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
						<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
					</div>
				</div>	
				
				<div class="form-group row">
					<label for="kode" class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<input type="submit" name="submit" value="Tambah Kategori Surat" class="btn btn-perak btn-block">
					</div>
				</div>

			</div>
		</div>
	</div>

</div>
<?php echo form_close(); ?>

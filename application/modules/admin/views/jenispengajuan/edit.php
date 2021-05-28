<?php
list($kat, $result) = $kategori;
$selected_kat = array_column($result, 'field_id');

echo form_open_multipart(base_url('admin/jenispengajuan/edit/' . $kat['Jenis_Pengajuan_Id']), 'class="form-horizontal"');

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
						<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : $kat['Jenis_Pengajuan'];  ?>" name="Jenis_Pengajuan" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
						<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
					</div>
				</div>

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Tipe Hadiah</label>
					<div class="col-md-9">
						<select class="form-control" name="tipe_reward" id="exampleFormControlSelect1">
							<option <?= $kat['fixed'] == 1 ? 'selected' : ''; ?> value="1">1. Individu</option>
							<option <?= $kat['fixed'] == 2 ? 'selected' : ''; ?> value="2">2. Kelompok (per individu) </option>
							<option <?= $kat['fixed'] == 3 ? 'selected' : ''; ?> value="3">3. Kelompok </option>
							<option <?= $kat['fixed'] == 0 ? 'selected' : ''; ?> value="0">4. Hak cipta</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="Jenis_Pengajuan" class="col-md-3 control-label">Nominal</label>
					<div class="col-md-9">
						<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : $kat['nominal'];  ?>" name="nominal" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
						<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
					</div>
				</div>

				<div class="form-group row">
					<label for="deskripsi" class="col-md-3 control-label">Deskripsi</label>
					<div class="col-md-9">

						<div class="<?= (form_error('deskripsinya')) ? 'summernote-is-invalid' : ''; ?>">
							<textarea name="deskripsinya" class="textarea-summernote"><?= (validation_errors()) ? set_value('deskripsinya') : $kat['deskripsi'];  ?>
							</textarea>
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



	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
		$(function() {
			$("#sortable1, #sortable2").sortable({
				connectWith: ".connectedSortable"
			}).disableSelection();
		});

		$("#sortable2").sortable({
			placeholder: "ui-state-active",
			update: function(event, ui) {
				var sorted = $("#sortable2").sortable("serialize", {
					key: "sort"
				});
				console.log(sorted);
				$('.field_surat').val(sorted);
				$("#sortable2").css('border-color', '#eeeeee');
				$("#errNm2").html('');
			},
		});

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

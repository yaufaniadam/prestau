<?php
list($kat, $result, $nominal) = $kategori;

$selected_kat = array_column($result, 'field_id');
?>

<link rel="stylesheet" href="<?= base_url('public/vendor/jquery-ui-1.12.1/jquery-ui.min.css'); ?>">

<style>
	.alert.simpan {
		display: none;
	}

	#sortable1,
	#sortable2 {
		width: 100%;
	}

	#sortable1,
	#sortable2 {
		min-height: 20px;
		list-style-type: none;
		margin: 0;
		padding: 15px;
		margin-right: 10px;
	}

	#sortable1 div,
	#sortable2 div {
		padding: 1px 0px;
		cursor: move;
	}

	.error {
		color: red;
	}

	div.is-invalid,
	.tipe_reward.is-invalid {
		border: 1px solid #b0272b !important;
		border-radius: 6px;
	}

	.tipe_reward li input {
		float: left;
		margin-top: 5px;
	}

	.tipe_reward li span {
		margin-left: 8px;
		float: left;
		width: 92%;
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

	<div class="col-md-8">

		<?php echo form_open_multipart(base_url('admin/jenispengajuan/edit/' . $kat['jpi']), 'class="form-horizontal"'); ?>
		<div class="card card-success card-outline">
			<p class="card-header">Jenis Pengajuan</p>
			<div class="card-body box-profile">

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="Jenis_Pengajuan" class="control-label">Kategori *</label>
							<div class="">
								<select name="parent" class="form-control <?= (form_error('parent')) ? 'is-invalid' : ''; ?>" id="parent">
									<option value=''>Pilih Kategori</option>
									<option value="1" <?= (validation_errors()) ? (set_select('parent','1')): ($kat['parent'] == 1 ? 'selected': '') ?>>Rekoginisi</option>
									<option value="2" <?= (validation_errors()) ? (set_select('parent','2')): ($kat['parent'] == 2 ? 'selected': '') ?>>Prestasi</option>
								</select>
								<span class="invalid-feedback"><?php echo form_error('parent'); ?></span>
							</div>
						</div>
					</div>					
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="Jenis_Pengajuan" class="control-label">Jenis Pengajuan *</label>
							<div class="">
								<input type="text" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : $kat['Jenis_Pengajuan'];  ?>" name="Jenis_Pengajuan" class="form-control <?= (form_error('Jenis_Pengajuan')) ? 'is-invalid' : ''; ?>" id="Jenis_Pengajuan">
								<span class="invalid-feedback"><?php echo form_error('Jenis_Pengajuan'); ?></span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="deskripsi" class="control-label">Deskripsi *</label>
							<div class="">
								<textarea name="deskripsinya" class="mb-1 form-control <?= (form_error('deskripsinya')) ? 'is-invalid' : ''; ?>"><?= (validation_errors()) ? set_value('deskripsinya') : $kat['deskripsi'];  ?></textarea>
								<span class="invalid-feedback"><?php echo form_error('deskripsinya'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="template" class="control-label">Form Field *
						<small id="" class="form-text text-muted">Seret lalu lepaskan form field yang tidak aktif ke kolom form field aktif.</small>
					</label>
					<div class="row">
						<div class="col-md-6">
							<div class="card card-success card-outline <?= (form_error('fields')) ? 'is-invalid' : ''; ?>">
								<div class="card-header">Field terpakai</div>
								<div class="card-body p-0 py-3">
									<span style="text-align:center;font-size:12px; color:#918d8d;display:block;">Seret field yang akan digunakan ke sini</span>
									<div id="sortable2" class="connectedSortable errorTxt ">

										<?php

										$jenis_pengajuan_id = $kat['jpi'];
										$query = $this->db->query("SELECT Tr_Pengajuan_Field.*, Mstr_Fields.* FROM Tr_Pengajuan_Field
										LEFT JOIN Mstr_Fields ON Tr_Pengajuan_Field.field_id = Mstr_Fields.field_id 
										WHERE Tr_Pengajuan_Field.Jenis_Pengajuan_Id =" . $jenis_pengajuan_id .
											" AND Tr_Pengajuan_Field.terpakai=1 ORDER BY urutan ASC");
										$results = $query->result_array();

										// echo '<pre>'; print_r($results); echo '</pre>';

										if ($results) {

											$userial_array = array();
											foreach ($result as $row) {
												$unserial_array[] = "sort=" . $row['field_id'];
											}

											$impl = implode('&', $unserial_array);
										}

										foreach ($results as $result) { ?>
											<div class="ui-state-highlights" id="item-<?= $result['field_id']; ?>">
												<p class="nama_field"><span class="nama_field_disini"><?= $result['field']; ?></span></p>
												<div class="p-4">

													<div class="mb-2">
														<input type="hidden" name="required" value=" <?= ($result['required'] == 1) ? '1' : '0'; ?>">
														<input type="checkbox" <?= ($result['required'] == 1) ? 'checked="checked"' : ''; ?> 
														class="checkp" />										
														<label for="exampleFormControlInput1" class="form-label">Centang jika field wajib</label>
													</div>

													<div class="mb-2">
														<label for="field" class="form-label">Nama Field</label>
														<input class="form-control field-field" type="text" value="<?= $result['field']; ?>" name="field" />
													</div>
													<div class="mb-2">
														<label for="placeholder" class="form-label">Placeholder</label>
														<input class="form-control" type="text" value="<?= $result['placeholder']; ?>" name="placeholder" />
													</div>
													
													<div class="mb-2">
														<label for="deskripsi" class="form-label">Deskripsi</label>
														<textarea class="form-control" name="deskripsi" placeholder="Deskripsi singkat penjelasan field" /><?= $result['deskripsi']; ?></textarea>
													</div>

													<div class="mb-2">
														<label for="type" class="form-label">Jenis Field</label>
														<select class="form-control field-type" name="type">
															<option value="">Pilih jenis field</option>
															<option value='text' <?= ($result['type'] == 'text') ? 'selected="selected"' : ''; ?>>Teks singkat</option>
															<option value='judul' <?= ($result['type'] == 'judul') ? 'selected="selected"' : ''; ?>>Judul</option>
															<option value='number' <?= ($result['type'] == 'number') ? 'selected="selected"' : ''; ?>>Angka</option>
															<option value='biaya' <?= ($result['type'] == 'biaya') ? 'selected="selected"' : ''; ?>>Biaya / Uang Rupiah</option>
															<option value='textarea' <?= ($result['type'] == 'textarea') ? 'selected="selected"' : ''; ?>>Teks panjang</option>
															<option value='wysiwyg' <?= ($result['type'] == 'wysiwyg') ? 'selected="selected"' : ''; ?>>Teks editor</option>
															<option value='select_pembimbing' <?= ($result['type'] == 'select_pembimbing') ? 'selected="selected"' : ''; ?>>Pilih Dosen</option>
															<option value='select_mahasiswa' <?= ($result['type'] == 'select_mahasiswa') ? 'selected="selected"' : ''; ?>>Pilih Mahasiswa</option>
															<option value='sem' <?= ($result['type'] == 'sem') ? 'selected="selected"' : ''; ?>>Semester</option>
															<option value='ta' <?= ($result['type'] == 'ta') ? 'selected="selected"' : ''; ?>>Tahun Akademik</option>
															<option value='tahun' <?= ($result['type'] == 'ta') ? 'selected="selected"' : ''; ?>>Tahun</option>
															<option value='date' <?= ($result['type'] == 'date') ? 'selected="selected"' : ''; ?>>Tanggal</option>
															<!-- <option value='date_range' <?= ($result['type'] == 'date_range') ? 'selected="selected"' : ''; ?>>Rentang Tanggal</option> -->
															<option value='url' <?= ($result['type'] == 'url') ? 'selected="selected"' : ''; ?>>Url</option>
															<option value='file' <?= ($result['type'] == 'file') ? 'selected="selected"' : ''; ?>>File/Image</option>
															<option value='select_tingkat' <?= ($result['type'] == 'select_tingkat') ? 'selected="selected"' : ''; ?>>Tingkatan Prestasi</option>
															<option value='select_nasional_internasional' <?= ($result['type'] == 'select_nasional_internasional') ? 'selected="selected"' : ''; ?>>Tingkatan Nasional Internasional</option>
														</select>
													</div>

													<div class="mb-2">
														<label for="key" class="form-label">Key (wajib, tanpa spasi, huruf kecil semua)</label>
														<input class="form-control field-key" type="text" value="<?= $result['key']; ?>" name="key" placeholder="Key sebagai kode identitas field" <?= ($result['type'] == 'judul') ? 'readonly="readonly"' : ''; ?>/>
													</div>

													<div class="mb-2">
														<a class="form-control btn btn-warning simpan simpan-<?= $result['field_id']; ?>" data-id="<?= $result['field_id']; ?>" style="cursor:pointer">
															<span class="loading d-none">
																<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
																<span class="sr-only">Loading...</span>
															</span>
															Simpan
														</a>
														<p class="mt-1 sukses_simpan text-success text-center"><i class="fas fa-check-circle"></i> Berhasil disimpan</p>
													</div>
												</div>
											</div>
										<?php	}
										?>
										<span id="errNm2"></span>
									</div>
									<input type="hidden" name="fields" data-error="#errNm2" class="field_surat" id="" value="<?= ($result) ? $impl : ''; ?>">


								</div>
							</div>
							<span class="text-danger mt-1 d-block" style="font-size: 80%;"><?php echo form_error('fields'); ?></span>
						</div>

						<div class="col-md-6">
							<div class="card card-success card-outline">
								<div class="card-header">Field tidak terpakai</div>
								<div class="card-body p-0 py-3">
									<p class="text-center"><a class="tambah-field btn btn-sm btn-warning" data-id="<?= $jenis_pengajuan_id; ?>" style="cursor:pointer;"><i class="fas fa-plus"></i> Tambah field baru</a></p>
									<span style="text-align:center;font-size:12px; color:#918d8d;display:block;">Seret field yang tidak digunakan ke sini</span>
									<div id="sortable1" class="connectedSortable keterangan_surat list-group">

										<?php


										foreach ($all_fields as $field) {
										?>
											<div class="ui-state-highlights <?= (!in_array($field['field_id'], array_column($results, 'field_id'))) ? "" : "d-none"; ?>" id="item-<?= $field['field_id']; ?>">
												<p class="nama_field"><span class="nama_field_disini"><?= $field['field']; ?></span></p>
												<div class="p-4">

													<div class="mb-2">
														<input type="hidden" name="required" value=" <?= ($field['required'] == 1) ? '1' : '0'; ?>">
														<input type="checkbox" <?= ($field['required'] == 1) ? 'checked="checked"' : ''; ?> 
														class="checkp" />	
														<label for="exampleFormControlInput1" class="form-label">Centang jika field wajib</label>
													</div>

													<div class="mb-2">
														<label for="field" class="form-label">Nama Field</label>
														<input class="form-control field-field" type="text" value="<?= $field['field']; ?>" name="field" />
													</div>
													<div class="mb-2">
														<label for="placeholder" class="form-label">Placeholder</label>
														<input class="form-control" type="text" value="<?= $field['placeholder']; ?>" name="placeholder" />
													</div>
													
													<div class="mb-2">
														<label for="deskripsi" class="form-label">Deskripsi</label>
														<textarea class="form-control" name="deskripsi" placeholder="Deskripsi singkat penjelasan field" /><?= $field['deskripsi']; ?></textarea>
													</div>

													<div class="mb-2">
														<label for="type" class="form-label">Jenis Field</label>
														<select class="form-control field-type" name="type">
															<option value="">Pilih jenis field</option>
															<option value='text' <?= ($field['type'] == 'text') ? 'selected="selected"' : ''; ?>>Teks singkat</option>
															<option value='judul' <?= ($field['type'] == 'judul') ? 'selected="selected"' : ''; ?>>Judul</option>
															<option value='number' <?= ($field['type'] == 'number') ? 'selected="selected"' : ''; ?>>Angka</option>
															<option value='biaya' <?= ($field['type'] == 'biaya') ? 'selected="selected"' : ''; ?>>Biaya / Uang Rupiah</option>
															<option value='textarea' <?= ($field['type'] == 'textarea') ? 'selected="selected"' : ''; ?>>Teks panjang</option>
															<option value='wysiwyg' <?= ($field['type'] == 'wysiwyg') ? 'selected="selected"' : ''; ?>>Teks editor</option>
															<option value='select_pembimbing' <?= ($field['type'] == 'select_pembimbing') ? 'selected="selected"' : ''; ?>>Pilih Dosen</option>
															<option value='select_mahasiswa' <?= ($field['type'] == 'select_mahasiswa') ? 'selected="selected"' : ''; ?>>Pilih Mahasiswa</option>
															<option value='sem' <?= ($field['type'] == 'sem') ? 'selected="selected"' : ''; ?>>Semester</option>
															<option value='ta' <?= ($field['type'] == 'ta') ? 'selected="selected"' : ''; ?>>Tahun Akademik </option>
															<option value='tahun' <?= ($field['type'] == 'tahun') ? 'selected="selected"' : ''; ?>>Tahun </option>
															
															<option value='date' <?= ($field['type'] == 'date') ? 'selected="selected"' : ''; ?>>Tanggal</option>
															<!-- <option value='date_range' <?= ($field['type'] == 'date_range') ? 'selected="selected"' : ''; ?>>Rentang Tanggal</option> -->
															<option value='url' <?= ($field['type'] == 'url') ? 'selected="selected"' : ''; ?>>Url</option>
															<option value='file' <?= ($field['type'] == 'file') ? 'selected="selected"' : ''; ?>>File/Image</option>
															<option value='select_tingkat' <?= ($field['type'] == 'select_tingkat') ? 'selected="selected"' : ''; ?>>Tingkatan Prestasi</option>
															<option value='select_nasional_internasional' <?= ($field ['type'] == 'select_nasional_internasional') ? 'selected="selected"' : ''; ?>>Tingkatan Nasional Internasional</option>
															
														</select>
													</div>

													<div class="mb-2">
														<label for="key" class="form-label">Key (wajib, tanpa spasi, huruf kecil semua)</label>
														<input class="form-control field-key" type="text" value="<?= $field['key']; ?>" name="key" placeholder="Key sebagai kode identitas field"  <?= ($field['type'] == 'judul') ? 'readonly="readonly"' : ''; ?>/>
													</div>

													<div class="mb-2">
														<a class="form-control btn btn-warning simpan simpan-<?= $field['field_id']; ?>" data-id="<?= $field['field_id']; ?>" style="cursor:pointer">
															<span class="loading d-none">
																<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
																<span class="sr-only">Loading...</span>
															</span>
															Simpan
														</a>
														<p class="mt-1 sukses_simpan text-success text-center"><i class="fas fa-check-circle"></i> Berhasil disimpan</p>
													</div>
												</div>
											</div>
										<?php	}	?>
									</div>
								</div>
							</div>

							<span class="text-danger" style="font-size: 80%;"><?php echo form_error('template'); ?></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<input type="submit" name="submit" value="Simpan Jenis Pengajuan" class="btn btn-lg btn-success btn-block">
				</div>
				<?php echo form_close(); ?>


			</div>
		</div>
	</div>

	<div class="col-md-4">

		<div class="card card-danger card-outline">

			<p class="card-header">Nominal Reward</p>
			<div class="card-body box-profile">
				<form name="nominal_reward" id="nominal_reward" action="<?= base_url('admin/jenispengajuan/edit_nominal/' . $kat['jpi']); ?>">
					<div class="form-group">
						<label for="Jenis_Pengajuan" class="control-label">Tipe Nominal Reward</label>
						<ul class="list-group tipe_reward" id="tipe_reward">
							<li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 1 ? 'checked="checked"' : ''; ?> value="1"> <span>Individu</span></li>
							<li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 2 ? 'checked="checked"' : ''; ?> value="2"> <span>Kelompok (Ketua dan anggota memperoleh nominal yang berbeda)</span></li>
							<li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 3 ? 'checked="checked"' : ''; ?> value="3"> <span>Kelompok (Reward diberikan kepada kelompok, bukan kepada tiap anggota)</span></li>
							<li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 4 ? 'checked="checked"' : ''; ?> value="4"> <span>Berdasarkan biaya yang dikeluarkan oleh mahasiswa</span></li>
						</ul>
						<span class="invalid-feedback">Error</span>					
					</div>


					<div class="form-group" id="nominal">
						<label for="Jenis_Pengajuan" class="control-label">Nominal (Rp)</label>
						<div>
							<div class="input-group mb-2 nominal1">
								<div class="input-group-prepend">
									<div class="input-group-text">Ketua Rp</div>
								</div>
								<input type="number" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : ($kat['fixed'] == 2 ? get_nominal_byorder($kat['jpi'], 0) : $kat['nominal']);  ?>" name="nominal1" class="form-control">
								<span class="invalid-feedback">Error Nominal 1</span>			
							</div>
							<div class="input-group mb-2 nominal2 ">
								<div class="input-group-prepend">
									<div class="input-group-text">Anggota Rp</div>
								</div>
								<input type="number" value="<?= (validation_errors()) ? set_value('Jenis_Pengajuan') : ($kat['fixed'] == 2 ? get_nominal_byorder($kat['jpi'], 1)  : '');  ?>" name="nominal2" class="form-control">
								<span class="invalid-feedback">Error Nominal 2</span>				
							</div>
						</div>
					</div>


					<div class="form-group">
						<input type="submit" name="submit" value="Simpan Nominal Reward" class="btn btn-success btn-block simpan_nominal">

						<p class="mt-1 sukses_simpan text-success text-center"><i class="fas fa-check-circle"></i> Berhasil disimpan</p>

					</div>
				</form>


				<script>
					$(document).ready(function() {
						
						$('select[name="type"]').on('change', function() {
							var val = $(this).val();
							var typeId = $(this).closest(".ui-state-highlights").attr("id");						

							if(val == 'judul') {
								$("#" + typeId).find("input[name='key']").attr('readonly', true);
								$("#" + typeId).find("input[name='key']").val('judul');
							} else if(val == 'select_mahasiswa') {
								$("#" + typeId).find("input[name='key']").attr('readonly', true);
								$("#" + typeId).find("input[name='key']").val('anggota');
							} else {
								$("#" + typeId).find("input[name='key']").attr('readonly', false);

								var old_value = $("#" + typeId).find("input[name='key']").val();

								if(old_value !='') {
									$("#" + typeId).find("input[name='key']").val(old_value);
								} else {
									$("#" + typeId).find("input[name='key']").val('');
								}								
							}						
						});

						//sembunyikan alert berhasil simpan
						$('.sukses_simpan').hide();

						var checked = $("input[name='tipe_reward']").is(':checked'),
							checkedVal = $("input[name='tipe_reward']:checked").val();

						if (checked) {
							if ((checkedVal == 1) || (checkedVal == 3)) {
								$('.nominal2').hide();
								$('.nominal2').prop("disabled", true);
							} else if (checkedVal == 4) {
								$('#nominal').hide();
							}
						} else {
							$('#nominal').hide();
							$('.nominal1').prop("disabled", true);
							$('.nominal2').prop("disabled", true);
						}

						$('input:radio[name=tipe_reward]').change(function() {

							if ((this.value == 1) || (this.value == 3)) {
								$('#nominal').slideDown();
								$('.nominal1').prop("disabled", false);
								$('.nominal2').prop("disabled", true);
								$('.nominal2').hide();

							} else if (this.value == 2) {
								$('#nominal').slideDown();
								$('.nominal2').show();
								$('.nominal1').prop("disabled", false);
								$('.nominal2').prop("disabled", false);

							} else if (this.value == 4) {
								$('#nominal').slideUp();
								$('.nominal1').prop("disabled", true);
								$('.nominal2').prop("disabled", true);
							}
						});

					});

					var frm = $('#nominal_reward');

					

					$(frm).bind('submit', function(e) {
						e.preventDefault();

						// $(frm).closest('.invalid-feedback').addClass('d-none');

						$.ajax({
							url: SITEURL + "admin/jenispengajuan/edit_nominal/" + <?= $kat['jpi']; ?>,
							data: frm.serialize(),
							type: "post",
							dataType: 'json',
							success: function(res) {	

								if (res.status == 'Error') {					
									
									// foreach error keynya
									Object.keys(res.error).forEach(function(k) {
										if (res.error[k] !== '') {
									
											if (k == 'tipe_reward') {
												$('#nominal_reward #tipe_reward').addClass('is-invalid');
												$('#nominal_reward #tipe_reward').next('.invalid-feedback').addClass('d-block').html( res.error[k] );
											} else {								
												$('#nominal_reward').find("." + k).children('input').addClass('is-invalid').next('.invalid-feedback').html( res.error[k] );					
											}
										}
									});

								} else {	
															
								// 	$('#nominal_reward').find('.invalid-feedback').hide();
									$('.simpan_nominal').next('.sukses_simpan').fadeIn().delay(500).fadeOut();
									$('#nominal_reward').find("input, select").removeClass('is-invalid');
								}
							},
							error: function(data) {
								console.log('Error:', data);
							}
						});
					});

					// hialngkan eror ketika field diklik
					$('.form-control').on('keypress', function() {
						$(this).removeClass('is-invalid');
					})
					$('#tipe_reward li').on('click', function() {
						$('#tipe_reward').removeClass('is-invalid');
						$('#tipe_reward').next('.invalid-feedback').removeClass('d-block');
					})
				</script>
			</div>
		</div>
	</div>


	<script src="<?= base_url('public/vendor/jquery-ui-1.12.1/jquery-ui.min.js'); ?>"></script>
	<script src="<?= base_url() ?>public/plugins/inputmask/jquery.inputmask.bundle.js"></script>

	<script>
		var SITEURL = '<?php echo base_url(); ?>';

		$(document).ready(function() {

			$('.tambah-field').on('click', function() {
				var id = $(this).data('id');

				$.ajax({
					url: SITEURL + "admin/jenispengajuan/tambah_field/" + id,
					success: function(res) {
						location.reload();
					},
					error: function(data) {
						console.log('Error:', data);
					}
				});
			});

		});


		$(function() {
			$("#sortable1, #sortable2")
				.accordion({
					collapsible: true,
					header: "> div > p",
					active: false
				})
				.sortable({
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

		// Initialize InputMask
		$('#nominal1').inputmask("99-9999999");


		//sembunyikan alert berhasil simpan
		$('.sukses_simpan').hide();

		//centang required field
		$('.checkp').on('click' , function(){

			var check  = $(this).is(':checked');
			if(check === true) {
				$(this).prev().val(1);
			} else {
				$(this).prev().val(0);
			}

		});

		$('.simpan').on('click', function() {
			$('.sukses_simpan').hide();
			var id = $(this).data('id');

			console.log(id)
			//aktifkan preloader setelah button diklik
			// $('.simpan-' + id).children('.loading').removeClass('d-none');

			var req = $('#item-' + id + ' div').children('input[name=required]').val(),
				field = $('#item-' + id + ' div').children('input[name=field]').val(),
				placeholder = $('#item-' + id + ' div').children('input[name=placeholder]').val(),
				deskripsi = $('#item-' + id + ' div').children('textarea[name=deskripsi]').val(),
				key = $('#item-' + id + ' div').children('input[name=key]').val(),
				type = $('#item-' + id + ' div').children('select[name=type]').val();

			console.log(field)

			$.ajax({
				url: SITEURL + "admin/jenispengajuan/edit_field/" + id,
				data: {
					required: req,
					field: field,
					placeholder: placeholder,
					deskripsi: deskripsi,
					key: key,
					type: type
				},
				type: "post",
				dataType: 'json',
				success: function(res) {

					if (res.status == 'Error') {
						// foreach error keynya
						Object.keys(res.error).forEach(function(k) {
							if (res.error[k] !== '') {

								$('#item-' + id + ' div').find(".field-" + k).next('.invalid-feedback').hide();
								$('#item-' + id + ' div').find(".field-" + k).addClass('is-invalid');
								$('#item-' + id + ' div').find(".field-" + k).after('<div class="invalid-feedback">' + res.error[k] + '</div>');
							} else {
								$('#item-' + id + ' div').find(".field-" + k).next('.invalid-feedback').hide();
								$('#item-' + id + ' div').find(".field-" + k).removeClass('is-invalid');
							}
						});

					} else {
						$('#item-' + id + ' div').find('.invalid-feedback').hide();
						$('#item-' + id + ' div').find("input, select").removeClass('is-invalid');
					}

					//sembunyikan preloader
					$('.simpan-' + id).children('.loading').addClass('d-none').delay(3000);
					$('.simpan-' + id).next('.sukses_simpan').fadeIn().delay(500).fadeOut();
					$('#item-' + id + ' p.nama_field').children('.nama_field_disini').html(res.data)

				},
				error: function(data) {
					console.log('Error:', data);
				}
			});

		});

		$('.is-invalid').on('keyup', function() {
			$(this).removeClass('is-invalid');
		})
		$('.is-invalid').on('change', function() {
			$(this).removeClass('is-invalid');
		})
	</script>

</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
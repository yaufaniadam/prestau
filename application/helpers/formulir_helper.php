<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function call_styles()
{
?>
	<link href="<?= base_url() ?>public/plugins/dm-uploader/dist/css/jquery.dm-uploader.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.css" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<?php
}

function call_scripts()
{
?>
	<script src="<?= base_url() ?>/public/plugins/dm-uploader/dist/js/jquery.dm-uploader.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

	<script>
		//aktifkan fungsi edit pada field 

		$('.edit-field').on('click', function(e) {
			e.preventDefault();


			var field_id = $(this).parent().prev().attr('id');
			var isDisabled = $('#' + field_id).prop('disabled');
			console.log(field_id);

			if (isDisabled === true) {
				$('#' + field_id).removeAttr('disabled');
				$(this).removeClass('btn-warning')
				$(this).children('i').removeClass('fa-window-close')
				$(this).children('i').addClass('fa-window-close')
				$(this).addClass('btn-danger')
				$(this).children('span').text('Batal')
				$(this).prev('a.simpan').removeClass('d-none');
				$(this).prev('a.simpan').addClass('d-inline');
			} else {
				$('#' + field_id).attr('disabled', 'true')
				$(this).removeClass('btn-danger')
				$(this).children('i').removeClass('fa-window-close')
				$(this).children('i').addClass('fa-edit')
				$(this).addClass('btn-warning')
				$(this).children('span').text('Edit')
				$(this).prev('a.simpan').removeClass('d-inline');
				$(this).prev('a.simpan').addClass('d-none');
			}

		});
		$('a.simpan').on('click', function(e) {
			e.preventDefault();
			var href = "<?= base_url('admin/pengajuan/editfield/'); ?>";
			var valfield = $(this).parent().prev().val();
			var id = $(this).attr("data-id");
			var pengajuan_id = $(this).attr("data-pengajuan");

			$.ajax({
				url: href,
				type: "POST",
				cache: false,
				data: {
					id: id,
					valfield: valfield,
					pengajuan_id: pengajuan_id
				},
				success: function(dataResult) {

					var dataResult = JSON.parse(dataResult);

					console.log(pengajuan_id)
					$('[data-pengajuan="' + pengajuan_id + '"]').removeClass('d-inline')
					$('[data-pengajuan="' + pengajuan_id + '"]').addClass('d-none')
					$('[data-pengajuan="' + pengajuan_id + '"]').next('a.edit-field').removeClass('btn-danger')
					$('[data-pengajuan="' + pengajuan_id + '"]').next('a.edit-field').addClass('btn-warning')
					$('[data-pengajuan="' + pengajuan_id + '"]').next('a.edit-field').children('i').removeClass('fa-window-close')
					$('[data-pengajuan="' + pengajuan_id + '"]').next('a.edit-field').children('i').addClass('fa-edit')
					$('[data-pengajuan="' + pengajuan_id + '"]').next('a.edit-field').children('span').text('Edit')
					$('[data-pengajuan="' + pengajuan_id + '"]').parent().prev().prop('disabled', 'disabled')

				}
			});
		});
	</script>

	<?php
}

function field($field_id)
{
	$CI = &get_instance();
	return $CI->db->get_where('mstr_fields', array('field_id' => $field_id))->row_array();
}

function get_user_session($session_name)
{
	$ci = &get_instance();
	return $ci->session->userdata($session_name);
}
function get_mahasiswa_by_nim($nim)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('v_mahasiswa', array('STUDENTID' => $nim))->row_array();
	return $query;
}
function get_dosen_by_id($id)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('v_dosen', array('id_pegawai' => $id))->row_array();
	return $query;
}
function get_prodi_by_id($id)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('mstr_department', array('DEPARTMENT_ID' => $id))->row_array();
	return $query;
}

function field_value_checker($required, $field_value, $id, $verifikasi, $pengajuan_status, $array)
{
	if ($required == 1) {

		if (validation_errors()) { // cek adakah eror validasi
			// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain
			if (set_value('dokumen[' . $id . ']')) {
				// error di field lain       
				$value = set_value('dokumen[' . $id . '][]');
				$valid = '';
				$disabled = 'en';
			} else {
				// error di field ini
				$value = set_value('dokumen[' . $id . '][]');
				$valid = 'is-invalid';
				$disabled = 'en';
			}
		} else {
			//tampilan default, saat value field 0, atau field sudah ada isinya dan menunggu verifikasi

			if ($field_value) {

				//field sudah dicek, tapi perlu direvisi
				if ($verifikasi == 0 && $pengajuan_status == 4) {
					$value = $field_value;
					$valid = 'is-invalid';
					$disabled = 'en';
				} else {
					$value = $field_value;
					$valid = 'sasasasa';
					$disabled = 'readonly';
				}
			} else {
				//field kosong
				$value = '';
				$valid = '';
				$disabled = 'en';
			}
		}
	} else {
		if (validation_errors()) { // cek adakah eror validasi
			// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain

			// error di field lain       
			$value = set_value('dokumen[' . $id . '][]');
			$valid = '';
			$disabled = 'en';
		} else {
			if ($field_value) {
				//field sudah dicek, tapi perlu direvisi
				if ($verifikasi == 0 && $pengajuan_status == 4) {
					$value = $field_value;
					$valid = 'is-invalid';
					$disabled = 'en';
				} else {
					$value = $field_value;
					$valid = '';
					$disabled = 'readonly';
				}
			} else {
				//field sudah dicek, tapi perlu direvisi
				if ($verifikasi == 0 && $pengajuan_status == 4) {
					$value = $field_value;
					$valid = 'is-invalid';
					$disabled = 'en';
				} else {
					//field kosong
					$value = '';
					$valid = '';
					$disabled = 'en';
				}
			}
		}
	}

	return array(
		'value' => $value,
		'valid' => $valid,
		'disabled' => $disabled
	);
}

//menampilkan kategori keterangan surat
function generate_form_field($field_id, $pengajuan_id, $pengajuan_status, $fungsi_upload, $jenis_pengajuan_id)
{
	$id = $field_id;

	$CI = &get_instance();
	$fields = $CI->db->select('mf.*')->from('mstr_fields mf')
		->where(array('mf.field_id' => $id))
		->get()->row_array();

	$field_key = ($fields) ? $fields['key'] : '';

	$value = $CI->db->select('fv.value, fv.verifikasi, fv.catatan')->from('tr_field_value fv')
		->where(array('field_id' => $field_id, 'pengajuan_id' => $pengajuan_id))
		->get()->row_array();

	$field_value = ($value) ? $value['value'] : '';
	$verifikasi = ($value) ? $value['verifikasi'] : '';
	$catatan = ($value) ? $value['catatan'] : '';

	/* 											
		FILE UPLOADER 					
	*/

	if ($fields['type'] == 'file') {

		if ($value != 0) {

			$image_id = (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;

			$image = $CI->db->select('*')->from('tr_media')
				->where(array('id' => $image_id))->get()->row_array();

			if ($image) {
				//	$thumb = $image['file'];
				$image = base_url('public/dist/img/document.png');
				//	$exploded = explode("/", $thumb);
				//	$file_name = $exploded[2];
			} else {
				echo $image = '';
				//	echo  $thumb = '';
				$file_name = '';
			}

			if ($fields['required'] == 1) {

				if (validation_errors()) { // cek adakah eror validasi
					// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain
					if (set_value('dokumen[' . $id . ']')) {
						// error di field lain       
						$form = 'd-none';
						$listing = 'd-block';
						$error = '';
						$change = '';
					} else {
						// error di field ini
						$form = '';
						$listing = 'd-none';
						$error = 'is-invalid';
						$change = '';
					}
				} else {
					//tampilan default, saat value field 0, atau field sudah ada isinya dan menunggu verifikasi
					if ($field_value) {

						//field sudah dicek, tapi perlu direvisi
						if ($verifikasi == 0 && $pengajuan_status == 4) {
							//field memiliki isi
							$form = '';
							$listing = '';
							$error = 'is-invalid';
							$change = '';
						} else {
							$form = 'd-none';
							$listing = 'd-block';
							$error = '';
							$change = 'd-none';
						}
					} else {
						//field kosong
						$form = '';
						$listing = 'd-none';
						$error = '';
						$change = '';
					}
				}
			} else {
				if (validation_errors()) { // cek adakah eror validasi

					// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain	
					if (set_value('dokumen[' . $id . ']')) {

						$form = 'd-none';
						$listing = 'd-nones';
						$error = 'is-invalidss';
						$change = '';
					} else {
						// error di field ini

						$form = '';
						$listing = 'd-none';
						$error = '';
						$change = '';
					}
				} else {
					//tampilan default, saat value field 0, atau field sudah ada isinya dan menunggu verifikasi
					if ($field_value) {

						//field sudah dicek, tapi perlu direvisi
						if ($verifikasi == 0 && $pengajuan_status == 4) {
							//field memiliki isi
							$form = '';
							$listing = '';
							$error = 'is-invalid';
							$change = '';
						} else {
							$form = 'd-none';
							$listing = 'd-block';
							$error = '';
							$change = 'd-none';
						}
					} else {
						//field kosong
						$form = '';
						$listing = 'd-none';
						$error = '';
						$change = '';
					}
				}
			}

			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);

	?>

			<input type="hidden" class="id-dokumen-<?= $id; ?> form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />

			<div class="tampilUploader">
				<div id="drag-and-drop-zone-<?= $id; ?>" class="dm-uploader p-3 <?= $form; ?> <?= $error; ?>">
					<h5 class="mb-2 mt-2 text-muted">Seret &amp; lepaskan berkas di sini</h5>

					<div class="btn btn-primary btn-block mb-2">
						<span>Atau klik untuk mengunggah</span>
						<input type="file" title='Klik untuk mengunggah' />
					</div>
				</div><!-- /uploader -->

				<ul class="list-unstyled p-2 d-flex flex-column col" id="files-<?= $id; ?>" style="border:1px solid #ddd; border-radius:4px;">
					<li class="text-muteds text-center empty"></li>

					<li class="media <?= $listing; ?>">
						<div class="media-body mb-1">
							<p class="mb-2">
								<?php
								if (set_value('dokumen[' . $id . ']')) {
									$id_file = set_value('dokumen[' . $id . ']');
								} else {
									$id_file = $field_value;
								}

								$file = get_file($id_file);
								if ($file) {
									$filename = explode('/dokumen/', $file['file']);
								}
								?>
								<strong><?= ($file) ? $filename['1'] : ''; ?></strong> <span class="text-muted"></span>
							</p>
							<div class="buttonedit"> <a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url($file['file']); ?>'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser-<?= $id; ?> btn btn-sm btn-danger <?= $change; ?>' data-id='<?= $file['id']; ?>'> <i class='fas fa-pencil-alt'></i> Ganti</a></div>
						</div>
					</li>
				</ul>
			</div>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">

				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>


			<script>
				// Changes the status messages on our list
				function ui_multi_update_file_status(id, status, message) {
					$('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
				}

				function ui_tampil_eror(message) {
					console.log(message)
				}

				$(function() {
					/*
					 * For the sake keeping the code clean and the examples simple this file
					 * contains only the plugin configuration & callbacks.
					 * 
					 * UI functions ui_* can be located in: demo-ui.js
					 */
					var maxfile = 5000000;
					var maxfile_mb = 5000000 / 1000000;
					$('#drag-and-drop-zone-<?= $id; ?>').dmUploader({ //
						url: '<?= base_url($fungsi_upload); ?>/doupload',
						maxFileSize: maxfile, // 5 Mega
						extFilter: ['jpg', 'jpeg', 'png', 'pdf', 'zip'],

						onDragEnter: function() {
							// Happens when dragging something over the DnD area
							this.addClass('active');
						},
						onDragLeave: function() {
							// Happens when dragging something OUT of the DnD area
							this.removeClass('active');
						},
						onInit: function() {
							// Plugin is ready to use
						},
						onComplete: function() {
							console.log('selesai')
						},
						onNewFile: function(id, file) {
							// When a new file is added using the file selector or the DnD area
							var template = '<li class="media" id="uploaderFile' + id + '"><div class="media-body mb-1"><p class="mb-2"><strong>' + file.name + '</strong> - Status: <span class="text-muted">Waiting</span></p><div class="buttonedit-<?= $id; ?>"></div></div></li>';

							$('#files-<?= $id; ?>').prepend(template);
						},
						onBeforeUpload: function(id) {
							// about tho start uploading a file
							ui_multi_update_file_status(id, 'uploading', '<img width="40px" height="" src="<?= base_url() ?>/public/dist/img/spinners.gif" />');
						},
						onUploadCanceled: function(id) {
							// Happens when a file is directly canceled by the user.
							ui_multi_update_file_status(id, 'warning', 'Canceled by User');

						},
						onUploadProgress: function(id, percent) {
							console.log(percent)
						},
						onUploadSuccess: function(id, data) {
							// A file was successfully uploaded
							ui_multi_update_file_status(id, 'success', '<i class="fas fa-check-circle"></i>');

							var response = JSON.stringify(data);
							var obj = JSON.parse(response);
							$('#files-<?= $id; ?>').find('li.empty').hide();


							$('.id-dokumen-<?= $id; ?>').val(obj.id);
							$('#drag-and-drop-zone-<?= $id; ?>').fadeOut('400');
							$('.deleteUser').removeClass('d-none', '3000');
							var button = "<a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url(); ?>" + obj.orig + "'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser-<?= $id; ?> btn btn-sm btn-danger' data-id='" + obj.id + "'> <i class='fas fa-pencil-alt'></i> Ganti</a>";
							$('.buttonedit-<?= $id; ?>').prepend(button);

						},
						onUploadError: function(id, xhr, status, message) {
							console.log('status')
							console.log('message')
							ui_multi_update_file_status(id, 'warning', message);
						},
						onFileExtError: function(id, file) {

							$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File tidak didukung').removeClass('text-muted').addClass('text-danger');
						},
						onFileSizeError: function(id) {
							$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File melebihi ' + maxfile_mb + 'Mb').removeClass('text-muted').addClass('text-danger');
						}
					});
				});
				$('body').on('click', 'a.deleteUser-<?= $id; ?>', function(e) {
					e.preventDefault();
					var href = $(this).attr("href");
					var ele = $(this).parents('.media');

					$.ajax({
						url: href,
						type: "POST",
						cache: false,
						data: {
							id: $(this).attr("data-id")
						},
						success: function(dataResult) {
							// alert(dataResult);
							var dataResult = JSON.parse(dataResult);
							if (dataResult.statusCode == 200) {
								ele.fadeOut().remove();
								$('#files-<?= $id; ?>').find('div.empty').fadeIn();
								$('#drag-and-drop-zone-<?= $id; ?>').fadeIn('400');
								$('#drag-and-drop-zone-<?= $id; ?>').removeClass('d-none');
								$('#files-<?= $id; ?>').find('li.empty').show();
								$('.id-dokumen-<?= $id; ?>').val('');
							}
						}
					});

				});
			</script>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		} 
		
		/* 											
			TEXT & JUDUL				
		*/

	} elseif (($fields['type'] == 'text') || ($fields['type'] == 'judul')) {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="text" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			URL 					
		*/

	} elseif ($fields['type'] == 'url') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="text" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> placeholder="http://" />
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			BIAYA 					
		*/

	} elseif ($fields['type'] == 'biaya') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupPrepend3">Rp</span>
					</div>
					<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> placeholder="Contoh benar: 30000000" />
				</div>
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>


		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			TEXTAREA 					
		*/

	} elseif ($fields['type'] == 'textarea') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>
			<fieldset>
				<textarea class="form-control <?= $check['valid']; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?>><?= $check['value'];  ?></textarea>
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			DATE RANGE 					
		*/

	} elseif ($fields['type'] == 'date_ranges') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="text" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
			</fieldset>


			<script type="text/javascript">
				$(function() {

					$('#input-<?= $id; ?>').daterangepicker({
						autoUpdateInput: false,
						locale: {
							cancelLabel: 'Clear',
							format: 'MM DD YY'
						}
					});

					$('#input-<?= $id; ?>').on('apply.daterangepicker', function(ev, picker) {
						$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
					});

					$('#input-<?= $id; ?>').on('cancel.daterangepicker', function(ev, picker) {
						$(this).val('');
					});

				});
			</script>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			TAHUN AKADEMIK 					
		*/

	} elseif ($fields['type'] == 'ta') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<select class="form-control <?= $fields['key']; ?> <?= $check['valid']; ?>" name="dokumen[<?= $id; ?>]" id="input-<?= $id; ?>" <?= $check['disabled'];  ?>>
				<option value=""> -- Pilih Tahun Akademik -- </option>
				<?php
				$cur_year = date("Y");
				$cur_semester = (date("n") <= 6) ?  $cur_year - 1 : $cur_year;
				for ($x = $cur_semester; $x <= $cur_year + 1; $x++) {

					$value_select = sprintf("%d / %d", $x, $x + 1); ?>

					<option value="<?= $value_select; ?>" <?php echo (validation_errors()) ? (set_select('dokumen[' . $id . ']', $value_select)) : ($value_select == $check['value'] ? 'selected' : ''); ?>><?= $x; ?> / <?= $x + 1; ?></option>

				<?php  } // endfor
				?>
			</select>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			DATE 					
		*/

	} elseif ($fields['type'] == 'date') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="text" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

			<script>
				$(function() {
					$("#input-<?= $id; ?>").datepicker({
						dateFormat: "d MM yy"
					});
				});
			</script>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			NUMBER 					
		*/

	} elseif ($fields['type'] == 'number') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			SEMESTER 					
		*/

	} elseif ($fields['type'] == 'sem') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> min="1" max="10"/>
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			TAHUN 					
		*/

	} elseif ($fields['type'] == 'tahun') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>

			<fieldset>
				<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> min="2015" max="<?php echo date("Y"); ?>" />
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		} 
		/*
			SELECT MAHASISWA
		*/		
	
	} elseif ($fields['type'] == 'select_mahasiswa') {
		if ($value != 0) {
			
			if (validation_errors()) { // cek adakah eror validasi
				// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain

				echo "ada error";

				if (set_value('dokumen[' . $id . ']')) {

					echo "error di field lain<br>";

					// error di field lain       
					$value = set_value('dokumen[' . $id . '][]');
					$value_explode = explode(',', $value);
					// ambil ketua
					$ketua = array_shift($value_explode);	
					//ambil anggota
					$anggota = $value_explode;
					$valid = '';
					$disabled = 'en';
				} else {
					// error di field ini
					echo "error di field ini";

					$value = set_value('dokumen[' . $id . '][]');
					$value_explode = explode(',', $value);
					// ambil ketua
					$ketua = array_shift($value_explode);	
					//ambil anggota
					$anggota = $value_explode;
					$valid = 'is-invalid';
					$disabled = 'en';
				}
			} else {
				//tampilan default, saat value field 0, atau field sudah ada isinya dan menunggu verifikasi

				if ($field_value) {

					echo "ada value";
					//field sudah dicek, tapi perlu direvisi
					if ($verifikasi == 0 && $pengajuan_status == 4) {
						echo "blm diverifikasi dan pengajuan status 4";
						$value_explode = explode(',', $field_value);
						// ambil ketua
						$ketua = array_shift($value_explode);
						//ambil anggota
						$anggota = $value_explode;
						$valid = 'is-invalid';
						$disabled = 'en';
					} else {
						echo " sudah diverifikasi, nilai akan diexplode";
						$value_explode = explode(',', $field_value);
						// ambil ketua
						$ketua = array_shift($value_explode);
						//ambil anggota
						$anggota = $value_explode;
						$valid = '';
						$disabled = 'readonly';
					}
				} else {
					//field kosong, nilai awal

					$value = '';
					$ketua = get_user_session('studentid');
					$anggota = '';
					$valid = '';
					$disabled = 'en';
				}
			}
		?>
			<style>
				fieldset.is-invalid {
					border:1px solid #b0272b;
					border-radius:6px;
					padding:10px;
				}
			</style>
			<fieldset class="<?= $valid; ?>">
				<div class="form-row">
					<label for="" class="col-md-12 col-2">Ketua </label>
					<div class="col-md-12 col-10">
						<select class="select-mhs ketua form-control mb-4 " name="ketua" >						
								<option value="<?= $ketua ?>"><?php echo get_mahasiswa_by_nim($ketua)['FULLNAME']; ?> (<?php echo get_mahasiswa_by_nim($ketua)['STUDENTID']; ?>)</option>
						</select>	
						<small id="ketualHelp" class="form-text text-muted">Klik untuk mengganti ketua (jika diperlukan).</small>
					</div>
				</div>
			
				<div class="form-row mt-2">
					<label for="" class="col-md-12 col-2">Anggota</label>
					<div class="col-md-12 col-10">
						<select class="select-mhs mhs form-control" name="anggota" multiple required="required">
						<?php  if ($value) {			
								foreach ($anggota as $field) { ?>
									<option value="<?= $field; ?>"><?php echo get_mahasiswa_by_nim($field)['FULLNAME']; ?> (<?php echo get_mahasiswa_by_nim($field)['STUDENTID']; ?>)</option>
								<?php } 
								}  ?>
						</select>	
						<small id="anggotalHelp" class="form-text text-muted">Klik lalu cari nama anggota. Gunakan NIM atau nama mahasiswa.</small>	
					</div>
				</div>

				<!-- nilai dari ketua dan anggota digabung di sini -->		
				<input type="hidden" id="namaanggota" name="dokumen[<?= $id; ?>]" value="<?= $field_value;  ?>" />			

			</fieldset>

			<script>
				$(document).ready(function() {
					
					// get value 
					var selectedValueKetua = [<?= $ketua; ?>];
					var selectedValuesAnggota = [<?php if ($value) {
																					foreach ($anggota as $field) {
																						echo '"' . $field . '"' . ',';
																					}
																				} ?>
																			];					

					$('.select-mhs').select2({
						ajax: {
							url: '<?= base_url('mahasiswa/pengajuan/getanggota'); ?>',
							dataType: 'json',
							type: 'post',
							delay: 250,
							data: function(params) {
								return {
									search: params.term,
								}
							},
							processResults: function(data) {
								return {
									results: data
								};
							},
							cache: true
						},
						placeholder: 'Tuliskan NIM atau Nama Mahasiswa',
						language: {
							inputTooShort: function() {
								return 'Ketikkan lebih dari 3 karakter nama atau NIM';
							}
						},
						minimumInputLength: 3,
						minimumResultsForSearch: Infinity,
					});

					$('.select-mhs.ketua').val(selectedValueKetua).trigger('change');
					$('.select-mhs.mhs').val(selectedValuesAnggota).trigger('change');

					$('.select-mhs').on('change', function() {
						var data = $(".select-mhs option:selected")
												.map(function() {
                            return this.value;
                        }).get(); 

					 $("#namaanggota").val(data);					 
					});			
				});

			

				var classe = $('.is-invalid').next().attr('class');

				

			</script>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			SELECT DOSEN / PEMBIMBING 					
		*/

	} elseif ($fields['type'] == 'select_pembimbing') {
		if ($value != 0) {

			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);

		?>
			<fieldset>
				<select class="ambil-pembimbing form-control form-control-lg <?= $fields['key']; ?> <?= $check['valid']; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled']; ?>>
					<option value="<?= $check['value']; ?>"><?= get_dosen_by_id($check['value'])['nama']; ?></option>

				</select>
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

			<script>
				$(document).ready(function() {

					var selectedValuesTest = ['<?= $check['value']; ?>']

					$('.ambil-pembimbing').select2({
						ajax: {
							url: '<?= base_url('mahasiswa/pengajuan/getpembimbing'); ?>',
							dataType: 'json',
							type: 'post',
							delay: 250,
							data: function(params) {
								return {
									search: params.term,
								}
							},
							processResults: function(data) {
								return {
									results: data
								};
							},
							cache: true
						},
						placeholder: 'Pilih Dosen',
						language: {
							inputTooShort: function() {
								return 'Ketikkan lebih dari 3 karakter nama atau NIM';
							}
						},
						minimumInputLength: 3,
					});
					$('.ambil-pembimbing').val(selectedValuesTest).trigger('change');
				});
			</script>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		} 
		
		/* 											
			SELECT CAPAIAN PRESTASi 					
		*/
		
	} elseif ($fields['type'] == 'select_prestasi') {

		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
				
			$CI = &get_instance();
			$capaian_prestasi = $CI->db->select('*')->from('mstr_penghargaan_rekognisi_mahasiswa')->where(['Jenis_Pengajuan_Id' => $jenis_pengajuan_id])->get()->result_array();
			?>

			<fieldset>
				<select class="form-control <?= $fields['key']; ?> <?= $check['valid']; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?>>
					<option value="">Pilih Prestasi</option>
					<?php foreach ($capaian_prestasi as $capaian_prestasi) { ?>
						<option value="<?= $capaian_prestasi['Penghargaan_Rekognisi_Mahasiswa_Id']; ?>" <?php echo (validation_errors()) ? (set_select('dokumen[' . $id . ']', $capaian_prestasi['Penghargaan_Rekognisi_Mahasiswa_Id'])) : ($capaian_prestasi['Penghargaan_Rekognisi_Mahasiswa_Id'] == $check['value'] ? 'selected' : ''); ?>><?= $capaian_prestasi['keterangan']; ?></option>
					<?php } ?>
				</select>
			</fieldset>


			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			SELECT TINGKATAN / LINGKUP					
		*/

	} elseif ($fields['type'] == 'select_tingkat') {

		if ($value != 0) {
			
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);

			$CI = &get_instance();
			$tingkat_prestasi = $CI->db->get('mstr_tingkat_prestasi')->result();
			?>
			<fieldset>
				<select name="dokumen[<?= $id; ?>]" class="form-control <?= $fields['key']; ?> <?= $check['valid']; ?>" <?= $check['disabled'];  ?>>
					<option value="">Pilih Lingkup Kegiatan</option>
					<?php foreach ($tingkat_prestasi as $tingkat_prestasi) { ?>
						
						<option value="<?= $tingkat_prestasi->Tingkat_Prestasi_Id; ?>"
						<?php echo (validation_errors()) ? (set_select('dokumen[' . $id . ']', $tingkat_prestasi->Tingkat_Prestasi_Id)) : ($tingkat_prestasi->Tingkat_Prestasi_Id == $check['value'] ? 'selected' : ''); ?>
						><?= $tingkat_prestasi->Tingkat_Prestasi; ?></option>					

					<?php } ?>
				</select>
			</fieldset>
			
			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

		/* 											
			SELECT PKM 					
		*/

	} elseif ($fields['type'] == 'select_pkm') {
		if ($value != 0) {
			$check = field_value_checker($fields['required'], $field_value, $id, $verifikasi, $pengajuan_status, false);
		?>
			<?php
			$CI = &get_instance();
			$pkm = $CI->db->select('*')->from('mstr_pimnas')->get()->result_array();
			?>

			<fieldset>
				<select class="form-control <?= $fields['key']; ?> <?= $check['valid']; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?>>
					<option value="">Pilih Kategori PKM</option>
					<?php foreach ($pkm as $pkm) { ?>
						<option value="<?= $pkm['id']; ?>" <?php echo (validation_errors()) ? (set_select('dokumen[' . $id . ']', $pkm['id'])) : ($pkm['id'] == $check['value'] ? 'selected' : ''); ?>><?= $pkm['nama_pkm']; ?></option>
					<?php } ?>
				</select>
			</fieldset>

			<span class="invalid-feedback d-block"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

			<div class="alert alert-danger <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> ">
				<div class="bg-white p-3 rounded-sm">
					<strong>Catatan dari LPKA:</strong>
					<hr>
					<?= $fields['field'] ?> Perlu direvisi. <br>
					<?php echo ($catatan != '') ?  $catatan : ''; ?>
				</div>
			</div>

		<?php  } else {
			echo "<i class='fas fa-exclamation-triangle text-danger'></i> Terdapat kesalahan, silakan lakukan pengajuan kembali.";
		}

	

	}  ?>
<?php }

/*
=====================================

menampilkan kategori keterangan surat

=====================================
*/

function edit_field($id,  $id_pengajuan)
{ ?>
	<span class="d-block mb-2">
		<a href="" class="btn btn-success simpan btn-sm d-none" data-id="<?= $id; ?>" data-pengajuan="<?= $id_pengajuan; ?>"><i class="fas fa-save"></i> Simpan</a>
		<a href="" class="btn btn-warning btn-sm edit-field"><i class="fas fa-edit"></i> <span>Edit</span></a>
	</span>
<?php }


// fungsi untuk menampilkan apakah data sudah sesuai apa belum

function data_sesuai($id, $verifikasi, $catatan)
{
?>

	<div class="d-inline">
		<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
		<label class="switch">
			<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($verifikasi == 1) ? 'checked' : ''; ?> />
			<span class="slider round"></span>
		</label>
	</div>
	<div class="d-inline">
		Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
	</div>

	<div class="mb-2">
		<input class="form-control field-field" type="text" value="<?= ($catatan); ?>" name="catatan[<?= $id; ?>]" placeholder="Beri Catatan " />
	</div>
	<?php
}

function generate_keterangan_surat($field_id, $id_pengajuan, $pengajuan_status, $jenis_pengajuan_id)
{

	$id = $field_id;

	$CI = &get_instance();
	$field = $CI->db->select('mf.*')->from('mstr_fields mf')
		->where(array('mf.field_id' => $id))
		->get()->row_array();

	$field_key = ($field) ? $field['key'] : '';

	$fields = $CI->db->select('fv.value, fv.verifikasi, fv.catatan')->from('tr_field_value fv')
		->where(array('field_id' => $field_id, 'pengajuan_id' => $id_pengajuan))
		->get()->row_array();

	$field_value = ($fields) ? $fields['value'] : '0';
	$verifikasi = ($fields) ? $fields['verifikasi'] : '0';
	$catatan = ($fields) ? $fields['catatan'] : '';


	if ($field['type'] == 'textarea') { ?>

		<textarea class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" disabled><?= $field_value;  ?></textarea>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		}

		?>

	<?php
	} elseif (($field['type'] == 'text') || ($field['type'] == 'judul')) { ?>

		<input type="text" class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" value="<?= $field_value;  ?>" name="dokumen[<?= $id; ?>]" disabled />

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		}

		?>

	<?php
	} elseif (($field['type'] == 'biaya')) { ?>

		<input type="text" class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" value="<?= $field_value;  ?>" name="dokumen[<?= $id; ?>]" disabled />

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		}

		?>

	<?php
	} elseif ($field['type'] == 'date_ranges') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" value="<?= $field_value;  ?>" />

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php
	} elseif ($field['type'] == 'select_prestasi') {
		/* tingkatan prestasi khusus untuk kegiatan kompetisi (ada juaranya), misalnya lomba Pimnas
		tingkatan prestasi diambilkan dari setting tingkatan prestasi di table 'mstr_penghargaan_rekognisi_mahasiswa'
		berdasarkan ID jenis pengajuannya
		*/
	?>

		<?php
		$CI = &get_instance();
		$capaian_prestasi = $CI->db->select('*')->from('mstr_penghargaan_rekognisi_mahasiswa')->where(['Jenis_Pengajuan_Id' => $jenis_pengajuan_id])->get()->result_array();
		?>

		<select class="form-control mb-2" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>][]" disabled>
			<?php foreach ($capaian_prestasi as $capaian_prestasi) { ?>
				<option value="<?= $capaian_prestasi['Penghargaan_Rekognisi_Mahasiswa_Id']; ?>" <?= ($capaian_prestasi['Penghargaan_Rekognisi_Mahasiswa_Id'] == $field_value) ? 'selected' : ''; ?>><?= $capaian_prestasi['keterangan']; ?></option>
			<?php } ?>
		</select>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php
	} elseif ($field['type'] == 'select_tingkat') {
		/* tingkatan wilayah/sekup kegiatan
		*/
	?>
		<?php
		$CI = &get_instance();
		$tingkat_prestasi = $CI->db->get('mstr_tingkat_prestasi')->result();
		?>

		<select class="form-control mb-2" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>][]" disabled>
		<?php foreach ($tingkat_prestasi as $tingkat_prestasi) { ?>
				<option value="<?= $tingkat_prestasi->Tingkat_Prestasi_Id; ?>" <?= ($tingkat_prestasi->Tingkat_Prestasi_Id == $field_value) ? 'selected' : ''; ?>><?= $tingkat_prestasi->Tingkat_Prestasi; ?></option>
			<?php } ?>
		</select>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>


	<?php
	} elseif ($field['type'] == 'select_pkm') {

	?>

		<?php
		$CI = &get_instance();
		$pkm = $CI->db->select('*')->from('mstr_pimnas')->get()->result_array();
		?>


		<select class="form-control mb-2" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>][]" disabled>
			<?php foreach ($pkm as $pkm) { ?>
				<option value="<?= $pkm['id']; ?>" <?= ($pkm['id'] == $field_value) ? 'selected' : ''; ?>><?= $pkm['nama_pkm']; ?></option>
			<?php } ?>
		</select>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php
	} elseif ($field['type'] == 'sem') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" value="<?= $field_value;  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php
	} elseif ($field['type'] == 'ta') { ?>

		<input type="text" class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" value="<?= $field_value;  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php
	} elseif ($field['type'] == 'select_pembimbing') {
		$CI = &get_instance();
		$dosen = $CI->db->get_where('v_dosen', array('id_pegawai' => $field_value))->row_array();

	?>

		<input type="text" class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" value="<?= $dosen['nama'];  ?>"></input>


		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			// edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>


	<?php

	} elseif ($field['type'] == 'select_mahasiswa') { ?>

		<?php
		$CI = &get_instance();
		$query = $CI->db->query("SELECT value FROM tr_field_value WHERE pengajuan_id =  $id_pengajuan AND field_id = $id")->row_array();
		$anggota_string = $query['value'];
		$anggota_array = explode(",", $anggota_string);
		?>


		<table class="table table-striped table-bordered <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>">

			<?php $i = 1;
			foreach ($anggota_array as $anggota) { ?>
				<tr>
					<td><?= $i++ ?> </td>
					<td><strong><?php echo get_mahasiswa_by_nim($anggota)['FULLNAME']; ?></strong><br><?= $anggota; ?> </td>
					<td><?php echo get_prodi_by_id(get_mahasiswa_by_nim($anggota)['DEPARTMENT_ID'])['NAME_OF_DEPARTMENT']; ?> </td>
				</tr>
			<?php } ?>

		</table>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			// edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		}
		?>


	<?php } elseif ($field['type'] == 'file') { ?>
		<?php
		$image_id = (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;

		$image = $CI->db->select('*')->from('tr_media')
			->where(array('id' => $image_id))->get()->row_array();
		if ($image) {
			$thumb = $image['file'];
			$image = base_url('public/dist/img/document.png');
			$exploded = explode("/", $thumb);
			$file_name = $exploded[2];
		} else {
			$image = '';
			$thumb = '';
		}

		?>

		<div class="p-2 mb-2" style="border-radius:5px; <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'border:1px solid red; ' : 'border:1px solid #ddd'; ?>">
			<p><strong><?= isset($file_name) ? $file_name : ''; ?></strong></p>
			<a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url($thumb); ?>'><i class='fas fa-eye'></i> Lihat</a>
		</div>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {

			data_sesuai($id, $verifikasi, $catatan);
		}


		?>
	<?php } elseif ($field['type'] == 'number') {


	?>

		<input type="number" class="form-control mb-2" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" disabled />


		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		}


		?>


	<?php } elseif ($field['type'] == 'url') { ?>

		<input type="url" class="form-control mb-2 <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" disabled />


		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>


	<?php } elseif ($field['type'] == 'tahun') { ?>
		<div class="form-group">
			<input type="number" class="form-control <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" />
		</div>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php } elseif ($field['type'] == 'date') { ?>
		<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<div class="mt-2"></div>

		<script>
			$(function() {
				$("#input-<?= $id; ?>").datepicker({
					dateFormat: "d MM yy"
				});
			});
		</script>

		<?php if ((($pengajuan_status == 2 && $verifikasi == 0) || ($pengajuan_status == 5 && $verifikasi == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) {
			edit_field($id,  $id_pengajuan);
			data_sesuai($id, $verifikasi, $catatan);
		} ?>

	<?php }
	?>

	<div id="fileZoom" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Preview</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<figure class="img_full"></figure>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		$("a.opener").click(function(event) {
			var $gbr = $(this).attr('data-href');
			console.log($gbr);
			$('.img_full').empty();
			$('.img_full').prepend("<img style='width:100%;' src=" + $gbr + " />");
		});
	</script>

<?php }

function get_file_name($file_dir = 0)
{
	$file_name = explode("/", $file_dir);
	echo $file_name[2];
}

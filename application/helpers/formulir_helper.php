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
	<?php
}

function field($field_id)
{
	$CI = &get_instance();
	return $CI->db->get_where('Mstr_Fields', array('field_id' => $field_id))->row_array();
}

function get_user_session($session_name)
{
	$ci = &get_instance();
	return $ci->session->userdata($session_name);
}
function get_mahasiswa_by_nim($nim)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('V_Mahasiswa', array('STUDENTID' => $nim))->row_array();
	return $query;
}
function get_dosen_by_id($id)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('V_Dosen', array('id_pegawai' => $id))->row_array();
	return $query;
}
function get_prodi_by_id($id)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('Mstr_Department', array('DEPARTMENT_ID' => $id))->row_array();
	return $query;
}

function field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, $array)
{
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
				$valid = '';
				$disabled = 'readonly';
			}
		} else {
			//field kosong
			$value = '';
			$valid = '';
			$disabled = 'en';
		}
	}

	return array(
		'value' => $value,
		'valid' => $valid,
		'disabled' => $disabled
	);
}

//menampilkan kategori keterangan surat
function generate_form_field($field_id, $pengajuan_id, $pengajuan_status, $fungsi_upload)
{
	$id = $field_id;

	$CI = &get_instance();
	$fields = $CI->db->select('mf.*')->from('Mstr_Fields mf')
		->where(array('mf.field_id' => $id))
		->get()->row_array();

	$field_key = ($fields) ? $fields['key'] : '';

	$value = $CI->db->select('fv.value, fv.verifikasi')->from('Tr_Field_Value fv')
		->where(array('field_id' => $field_id, 'pengajuan_id' => $pengajuan_id))
		->get()->row_array();

	$field_value = ($value) ? $value['value'] : '0';
	$verifikasi = ($value) ? $value['verifikasi'] : '0';

	if ($fields['type'] == 'file') { ?>

		<?php
		$image_id = (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;

		$image = $CI->db->select('*')->from('Tr_Media')
			->where(array('id' => $image_id))->get()->row_array();

		if ($image) {
			$thumb = $image['file'];
			$image = base_url('public/dist/img/document.png');
			$exploded = explode("/", $thumb);
			$file_name = $exploded[2];
		} else {
			echo $image = '';
			echo  $thumb = '';
			$file_name = '';
		}

		if (validation_errors()) { // cek adakah eror validasi
			// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain
			if (set_value('dokumen[' . $id . ']')) {
				// error di field lain       
				$form = 'd-none';
				$listing = 'd-block';
				$error = '';
			} else {
				// error di field ini
				$form = '';
				$listing = 'd-none';
				$error = 'is-invalid';
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
				} else {
					$form = 'd-none';
					$listing = 'd-block';
					$error = '';
				}
			} else {
				//field kosong
				$form = '';
				$listing = 'd-none';
				$error = '';
			}
		}

		?>

		<!-- pad akondisi default (data value kosong), form dNd muncul, listing tidak muncul -->
		<br>
		<input type="hidden" class="id-dokumen-<?= $id; ?> <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') : (($verifikasi == 0) && ($pengajuan_status == 4) ? $field_value : '');  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />

		<div class="tampilUploader">
			<div id="drag-and-drop-zone-<?= $id; ?>" class="dm-uploader p-3 <?= $form; ?> <?= $error; ?>">
				<h5 class="mb-2 mt-2 text-muted">Seret &amp; lepaskan berkas di sini</h5>

				<div class="btn btn-primary btn-block mb-2">
					<span>Atau klik untuk mengunggah</span>
					<input type="file" title='Klik untuk mengunggah' />
				</div>
			</div><!-- /uploader -->


			<ul class="list-unstyled p-2 d-flex flex-column col" id="files-<?= $id; ?>" style="border:1px solid #ddd; border-radius:4px;">
				<!-- <li class="text-muted text-center empty <?= (validation_errors()) ? (set_value('dokumen[' . $id . ']') ? 'd-none' : 'ga ada value') :  'd-none'  ?>">Belum ada file yang diupload.</li> -->

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
						<div class="buttonedit"> <a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url($file['file']); ?>'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser-<?= $id; ?> btn btn-sm btn-danger <?= $form; ?>' data-id='<?= $file['id']; ?>'> <i class='fas fa-pencil-alt'></i> Ganti</a></div>
					</div>
				</li>
			</ul>
		</div>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>


		<script>
			// Changes the status messages on our list
			function ui_multi_update_file_status(id, status, message) {
				$('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
			}

			$(function() {
				/*
				 * For the sake keeping the code clean and the examples simple this file
				 * contains only the plugin configuration & callbacks.
				 * 
				 * UI functions ui_* can be located in: demo-ui.js
				 */
				$('#drag-and-drop-zone-<?= $id; ?>').dmUploader({ //
					url: '<?= base_url($fungsi_upload); ?>/doupload',
					maxFileSize: 5000000, // 5 Mega
					extFilter: ['jpg', 'jpeg', 'png', 'pdf'],
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
						// All files in the queue are processed (success or error)
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
					onUploadProgress: function(id, percent) {},
					onUploadSuccess: function(id, data) {
						// A file was successfully uploaded
						ui_multi_update_file_status(id, 'success', '<i class="fas fa-check-circle"></i>');

						var response = JSON.stringify(data);
						var obj = JSON.parse(response);

						$('.id-dokumen-<?= $id; ?>').val(obj.id);
						$('#drag-and-drop-zone-<?= $id; ?>').fadeOut('400');
						$('.deleteUser').removeClass('d-none', '3000');
						var button = "<a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url(); ?>" + obj.orig + "'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser-<?= $id; ?> btn btn-sm btn-danger' data-id='" + obj.id + "'> <i class='fas fa-pencil-alt'></i> Ganti</a>";
						$('.buttonedit-<?= $id; ?>').prepend(button);

					},
					onUploadError: function(id, xhr, status, message) {
						ui_multi_update_file_status(id, 'danger', message);
					},
					onFileExtError: function(id, file) {
						$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File tidak didukung').removeClass('text-muted').addClass('text-danger');
					},
					onFileSizeError: function(id, file) {

						$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File terlalu besar').removeClass('text-muted').addClass('text-danger');

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


	<?php } elseif (($fields['type'] == 'text') || ($fields['type'] == 'judul') ) {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
	?>

		<fieldset>
			<input type="text" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
		</fieldset>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'url') {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
	?>

		<fieldset>
			<input type="url" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> placeholder="http://"/>
		</fieldset>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'textarea') {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
	?>
		<fieldset>
			<textarea class="form-control <?= $check['valid']; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?>><?= $check['value'];  ?></textarea>
		</fieldset>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'date_ranges') {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
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

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>


	<?php } elseif ($fields['type'] == 'ta') { ?>
		<select class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" name="dokumen[<?= $id; ?>]" id="input-<?= $id; ?>">
			<option value=""> -- Pilih Tahun Akademik -- </option>
			<?php
			$cur_year = date("Y");
			$cur_semester = (date("n") <= 6) ?  $cur_year - 1 : $cur_year;
			for ($x = $cur_semester; $x <= $cur_year + 1; $x++) {
				$value_select = sprintf("%d / %d", $x, $x + 1); ?>
				<option value="<?= $value_select; ?>" <?= (validation_errors()) ? set_select('dokumen[' . $id . ']', $value_select) : ""; ?> <?= ($field_value == $value_select) ? "selected" : ""; ?>><?= $x; ?> / <?= $x + 1; ?></option>
			<?php  }
			?>
		</select>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'date') { ?>
		
		<fieldset>
			<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $verifikasi == 0 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />
		</fieldset>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>
		<script>
			$(function() {
				$("#input-<?= $id; ?>").datepicker({
					dateFormat: "d MM yy"
				});
			});
		</script>
	<?php } elseif ($fields['type'] == 'number') {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
	?>
		<fieldset>
			<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> />
		</fieldset>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'tahun') {
		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);
	?>
		<fieldset>
			<input type="number" class="form-control <?= $check['valid']; ?>" value="<?= $check['value'];  ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled'];  ?> min="2015" max="<?php echo date("Y"); ?>" />
		</fieldset>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

	<?php } elseif ($fields['type'] == 'multi_select_anggota') {

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
					$value = explode(',', $field_value);
					$valid = 'is-invalid';
					$disabled = 'en';
				} else {
					$value = explode(',', $field_value);
					$valid = '';
					$disabled = 'readonly';
				}
			} else {
				//field kosong
				$value = '';
				$valid = '';
				$disabled = 'en';
			}
		}
	?>

		<fieldset>
			<select class="js-data-example-ajax form-control form-control-lg <?= $fields['key']; ?> form-control <?= $valid; ?>" name="dokumen[<?= $id; ?>][]" multiple <?= $disabled; ?>>
				<?php if ($value) {
					foreach ($value as $anggota) { ?>
						<option value="<?= $anggota; ?>"><?php echo get_mahasiswa_by_nim($anggota)['FULLNAME']; ?> (<?php echo get_mahasiswa_by_nim($anggota)['STUDENTID']; ?>)</option>
					<?php }
				} else { ?>
					<option locked="locked" value="<?= get_user_session('studentid'); ?>"><?php echo get_mahasiswa_by_nim(get_user_session('studentid'))['FULLNAME']; ?> (<?php echo get_mahasiswa_by_nim(get_user_session('studentid'))['STUDENTID']; ?>)</option>

				<?php
				} ?>
			</select>
		</fieldset>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

		<script>
			$(document).ready(function() {

				var selectedValuesTest = [<?= get_user_session('studentid'); ?>,
					<?php if ($value) {
						foreach ($value as $anggota) {
							echo '"' . $anggota . '"' . ',';
						}
					} ?>
				];

				$('.js-data-example-ajax').select2({
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
					minimumInputLength: 3,
					minimumResultsForSearch: Infinity
					// templateResult: formatRepo,
					// templateSelection: formatRepoSelection
				});
				$('.js-data-example-ajax').val(selectedValuesTest).trigger('change');
			});

			var classe = $('.is-invalid').next().attr('class');
			console.log(classe);
		</script>

	<?php } elseif ($fields['type'] == 'select_pembimbing') {

		$check = field_value_checker($field_value, $id, $verifikasi, $pengajuan_status, false);

	?>
		<fieldset>
			<select class="ambil-pembimbing form-control form-control-lg <?= $fields['key']; ?> form-control <?= $check['valid']; ?>" name="dokumen[<?= $id; ?>]" <?= $check['disabled']; ?>>
				<option value="<?= $check['value']; ?>"><?= get_dosen_by_id($check['value'])['nama']; ?></option>

			</select>
		</fieldset>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
		<span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> <?= $fields['field'] ?> Perlu direvisi.</span>

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
					minimumInputLength: 3,

				});
				$('.ambil-pembimbing').val(selectedValuesTest).trigger('change');
			});
		</script>

	<?php } elseif ($fields['type'] == 'select_prestasi') { ?>
		<?php
		$CI = &get_instance();
		$capaian_prestasi = $CI->db->get('Mstr_Capaian_Prestasi')->result();
		?>
		<fieldset>
			<select name="<?= $fields['key']; ?>" class="custom-select">
				<?php foreach ($capaian_prestasi as $capaian_prestasi) { ?>
					<option value="<?= $capaian_prestasi->Capaian_Prestasi_Id; ?>"><?= $capaian_prestasi->Capaian_Prestasi; ?></option>
				<?php } ?>
			</select>
		</fieldset>
	<?php } elseif ($fields['type'] == 'select_tingkat') { ?>
		<?php
		$CI = &get_instance();
		$tingkat_prestasi = $CI->db->get('Mstr_Tingkat_Prestasi')->result();
		?>
		<fieldset>
			<select name="<?= $fields['key']; ?>" class="custom-select">
				<?php foreach ($tingkat_prestasi as $tingkat_prestasi) { ?>
					<option value="<?= $tingkat_prestasi->Tingkat_Prestasi_Id; ?>"><?= $tingkat_prestasi->Tingkat_Prestasi; ?></option>
				<?php } ?>
			</select>
		</fieldset>
	
	<?php } elseif ($fields['type'] == 'select_nasional_internasional') { ?>
	
		<fieldset>
			<select name="<?= $fields['key']; ?>" class="custom-select">
			
					<option value="nasional">Nasional</option>
					<option value="internasional">Internasional</option>
				
			</select>
		</fieldset>
	<?php } ?>

	<?php }

/*
=====================================

menampilkan kategori keterangan surat

=====================================
*/

function generate_keterangan_surat($field_id, $id_surat, $pengajuan_status)
{
	$id = $field_id;
	$CI = &get_instance();
	$field = $CI->db->select('*')->from('Mstr_Fields f')
		->join('Tr_Field_Value fv', 'fv.field_id=f.field_id', 'left')
		->where(array('f.field_id' => $id))
		->where(array('fv.pengajuan_id' => $id_surat))
		->get();

		if($field->num_rows() > 0) {
			echo "ada";
		} else {
			echo "gada";
		}

		$fields= $field->row_array();

	if ($fields['type'] == 'textarea') { ?>

		<textarea class="form-control mb-2" id="input-<?= $id; ?>" ><?= $fields['value'];  ?></textarea>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>
			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif (($fields['type'] == 'text') || ($fields['type'] == 'url') || ($fields['type'] == 'judul')) { ?>

		<input type="text" class="form-control mb-2  <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" value="<?= $fields['value'];  ?>" />

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="mb-2">			
				<input class="form-control field-field" type="text" value="" name="catatan[<?= $id; ?>]" placeholder="Beri Catatan "/>
			</div>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif ($fields['type'] == 'date_ranges') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" value="<?= $fields['value'];  ?>" />

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif ($fields['type'] == 'sem') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>"  value="<?= $fields['value'];  ?>"></input>
		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>
			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif ($fields['type'] == 'ta') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>"  value="<?= $fields['value'];  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>
			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif ($fields['type'] == 'select_pembimbing') {
		$CI = &get_instance();
		$dosen = $CI->db->get_where('V_Dosen', array('id_pegawai' => $fields['value']))->row_array();

		?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>"  value="<?= $dosen['nama'];  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>
			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php }
	} elseif ($fields['type'] == 'multi_select_anggota') { ?>

		<?php
		$CI = &get_instance();
		$query = $CI->db->query("SELECT value FROM Tr_Field_Value WHERE pengajuan_id =  $id_surat AND field_id = $id")->row_array();
		$anggota_string = $query['value'];
		$anggota_array = explode(",", $anggota_string);
		?>


		<table class="table table-striped table-bordered">

			<?php $i = 1;
			foreach ($anggota_array as $anggota) { ?>
				<tr>
					<td><?= $i++ ?> </td>
					<td><strong><?php echo get_mahasiswa_by_nim($anggota)['FULLNAME']; ?></strong><br><?= $anggota; ?> </td>
					<td><?php echo get_prodi_by_id(get_mahasiswa_by_nim($anggota)['DEPARTMENT_ID'])['NAME_OF_DEPARTMENT']; ?> </td>
				</tr>
			<?php } ?>

		</table>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
		) { ?>

			<div class="mt-3"></div>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php } ?>
	<?php } elseif ($fields['type'] == 'file') {  ?>
		<?php
		$image_id = (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];

		$image = $CI->db->select('*')->from('Tr_Media')
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

		<div class="p-2 mb-2" style="border-radius:5px; <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'border:1px solid red; ' : 'border:1px solid #ddd'; ?>">
			<p><strong><?= isset($file_name) ? $file_name : ''; ?></strong></p>
			<a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url($thumb); ?>'><i class='fas fa-eye'></i> Lihat</a>
		</div>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php } ?>
	<?php } elseif ($fields['type'] == 'number') { ?>
		<div class="form-group">
			<input type="number" class="form-control <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]"  />
		</div>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php } ?>
	<?php } elseif ($fields['type'] == 'tahun') { ?>
		<div class="form-group">
			<input type="number" class="form-control <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]"  />
		</div>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php } ?>
	<?php } elseif ($fields['type'] == 'date') { ?>
asdasd
		<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]"  />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<div class="mt-2"></div>

		<script>
			$(function() {
				$("#input-<?= $id; ?>").datepicker( {
					dateFormat: "d MM yy"
				});
			});
		</script>

		

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& (($CI->session->userdata('role') == 2) || ($CI->session->userdata('role') == 1))
		) { ?>

			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : ''; ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>

		<?php } ?>
	<?php } ?>

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

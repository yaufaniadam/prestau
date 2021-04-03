<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function field($field_id)
{
	$CI = &get_instance();
	return $CI->db->get_where('Mstr_Fields', array('field_id' => $field_id))->row_array();
}

function get_mahasiswa_by_nim($nim)
{
	$CI = &get_instance();
	$query = $CI->db->get_where('V_Mahasiswa', array('STUDENTID' => $nim))->row_array();
	echo $query['FULLNAME'];
}

//menampilkan kategori keterangan surat
function generate_form_field($field_id, $pengajuan_id, $pengajuan_status, $fungsi_upload)
{
	$id = $field_id;
?>
	<link href="<?= base_url() ?>public/plugins/dm-uploader/dist/css/jquery.dm-uploader.min.css" rel="stylesheet">
	<?php

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

		?>
		<link href="<?= base_url() ?>public/plugins/dm-uploader/dist/css/jquery.dm-uploader.min.css" rel="stylesheet">


		<?php if (validation_errors()) { // cek adakah eror validasi
			// kondisional di bawah untuk memeriksa, erornya pada field ini ataukah pada field lain
			if (set_value('dokumen[' . $id . ']')) {
				// error di field lain       
				$form = 'd-none';
				$listing = 'd-block';
				$error = '';
			} else {
				// error di field ini
				$form = 'd-block';
				$listing = 'd-none';
				$error = 'is-invalid';
			}
		} else {
			//tampilan default, saat value field 0, atau field sudah ada isinya dan menunggu verifikasi
			if ($field_value) {
				//field sudah dicek, tapi perlu direvisi
				if ($verifikasi == 0 && $pengajuan_status == 4) {
					//field memiliki isi
					$form = 'd-block';
					$listing = 'd-none';
					$error = 'is-invalid';
				} else {
					$form = 'd-none';
					$listing = 'd-block';
					$error = '';
				}
			} else {
				//field kosong
				$form = 'd-block';
				$listing = 'd-none';
				$error = '';
			}
		}
		// (($verifikasi == 0) && ($pengajuan_status == 4) ? '' : $field_value);  
		?>

		<!-- pad akondisi default (data value kosong), form dNd muncul, listing tidak muncul -->
		<br>
		<input type="text" class="id-dokumen-<?= $id; ?> <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') : (($verifikasi == 0) && ($pengajuan_status == 4) ? '' : $field_value);  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />

		<div class="tampilUploader">
			<div id="drag-and-drop-zone-<?= $id; ?>" class="dm-uploader p-3 <?= $form; ?> <?= $error; ?>">
				<h5 class="mb-2 mt-2 text-muted">Seret &amp; lepaskan berkas di sini</h5>

				<div class="btn btn-primary btn-block mb-2">
					<span>Atau klik untuk mengunggah</span>
					<input type="file" title='Klik untuk mengunggah' />
				</div>
			</div><!-- /uploader -->

			<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
			<!-- <span class="<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? '' : 'd-none'; ?> text-danger"><i class="fas fa-exclamation-triangle"></i> Berkas berikut ini perlu direvisi.</span> -->

			<ul class="list-unstyled p-2 d-flex flex-column col" id="files-<?= $id; ?>" style="border:1px solid #ddd; border-radius:4px;">
				<li class="text-muted text-center empty <?= (validation_errors()) ? (set_value('dokumen[' . $id . ']') ? 'd-none' : 'ga ada value') :  'd-none'  ?>">Belum ada file yang diupload.</li>

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
						<!-- <div class="buttonedit"> <a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url($file['file']); ?>'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser btn btn-sm btn-danger <?= ($verifikasi == 0 && $pengajuan_status == 5 || $pengajuan_status == 7) ? 'd-none' : ''; ?>' data-id='<?= $file['id']; ?>'> <i class='fas fa-pencil-alt'></i> Ganti</a></div> -->
					</div>
				</li>

			</ul>


		</div>

		<script src="<?= base_url() ?>/public/plugins/dm-uploader/dist/js/jquery.dm-uploader.min.js"></script>
		<script>
			// Adds an entry to our debug area
			function ui_add_log(message, color) {
				var d = new Date();

				var dateString = (('0' + d.getHours())).slice(-2) + ':' +
					(('0' + d.getMinutes())).slice(-2) + ':' +
					(('0' + d.getSeconds())).slice(-2);

				color = (typeof color === 'undefined' ? 'muted' : color);

				var template = $('#debug-template').text();
				template = template.replace('%%date%%', dateString);
				template = template.replace('%%message%%', message);
				template = template.replace('%%color%%', color);

				$('#debug').find('li.empty').fadeOut(); // remove the 'no messages yet'
				$('#debug').prepend(template);
			}

			// Creates a new file and add it to our list
			function ui_multi_add_file(id, file) {
				var template = $('#files-template-<?= $id; ?>').text();

				console.log(file);

				template = template.replace('%%filename%%', file.name);
				template = $(template);
				template.prop('id', 'uploaderFile' + id);
				template.data('file-id', id);

				$('#files-<?= $id; ?>').find('li.empty').hide(); // remove the 'no files yet'
				$('#files-<?= $id; ?>').prepend(template);
			}

			// Changes the status messages on our list
			function ui_multi_update_file_status(id, status, message) {
				$('#uploaderFile' + id).find('span').html(message).prop('class', 'status text-' + status);
			}

			// Updates a file progress, depending on the parameters it may animate it or change the color.
			function ui_multi_update_file_progress(id, percent, color, active) {
				color = (typeof color === 'undefined' ? false : color);
				active = (typeof active === 'undefined' ? true : active);

				var bar = $('#uploaderFile' + id).find('div.progress-bar');

				bar.width(percent + '%').attr('aria-valuenow', percent);
				bar.toggleClass('progress-bar-striped progress-bar-animated', active);

				if (percent === 0) {
					bar.html('');
				} else {
					bar.html(percent + '%');
				}

				if (color !== false) {
					bar.removeClass('bg-success bg-info bg-warning bg-danger');
					bar.addClass('bg-' + color);
				}
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
					maxFileSize: 3000000, // 3 Megs 
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
						ui_multi_add_file(id, file);
					},
					onBeforeUpload: function(id) {
						// about tho start uploading a file
						ui_multi_update_file_status(id, 'uploading', '<img width="40px" height="" src="<?= base_url() ?>/public/dist/img/spinners.gif" />');
						ui_multi_update_file_progress(id, 0, '', true);
					},
					onUploadCanceled: function(id) {
						// Happens when a file is directly canceled by the user.
						ui_multi_update_file_status(id, 'warning', 'Canceled by User');
						ui_multi_update_file_progress(id, 0, 'warning', false);
					},
					onUploadProgress: function(id, percent) {
						// Updating file progress
						ui_multi_update_file_progress(id, percent);
					},
					onUploadSuccess: function(id, data) {
						// A file was successfully uploaded
						ui_multi_update_file_status(id, 'success', '<i class="fas fa-check-circle"></i>');
						ui_multi_update_file_progress(id, 100, 'success', false);

						var response = JSON.stringify(data);
						var obj = JSON.parse(response);

						alert('sukses');

						$('.id-dokumen-<?= $id; ?>').val(obj.id);
						$('#drag-and-drop-zone-<?= $id; ?>').fadeOut('400');
						$('.deleteUser').removeClass('d-none', '3000');
						var button = "<a class='btn btn-sm btn-warning' target='_blank' href='<?= base_url(); ?>" + obj.orig + "'><i class='fas fa-eye'></i> Lihat</a> <a href='<?= base_url($fungsi_upload); ?>/hapus_file/' class='deleteUser btn btn-sm btn-danger' data-id='" + obj.id + "'> <i class='fas fa-pencil-alt'></i> Ganti</a>";
						$('.buttonedit').prepend(button);

					},
					onUploadError: function(id, xhr, status, message) {
						ui_multi_update_file_status(id, 'danger', message);
						ui_multi_update_file_progress(id, 0, 'danger', false);
					},
					onFileExtError: function(id, file) {
						$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File tidak didukung').removeClass('text-muted').addClass('text-danger');
					},
					onFileSizeError: function(id, file) {

						$('#files-<?= $id; ?>').find('li.empty').html('<i class="fas fa-exclamation-triangle"></i> File terlalu besar').removeClass('text-muted').addClass('text-danger');

					}
				});
			});
			$('body').on('click', 'a.deleteUser', function(e) {
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


		<!-- File item template -->
		<script type="text/html" id="files-template-<?= $id; ?>">
			<li class="media">
				<div class="media-body mb-1">
					<p class="mb-2">
						<strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
					</p>

					<div class="buttonedit"></div>
				</div>
			</li>
		</script>

	<?php } elseif ($fields['type'] == 'text') {  ?>

		<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

	<?php } elseif ($fields['type'] == 'textarea') {  ?>

		<textarea class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?>><?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?></textarea>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

	<?php } elseif ($fields['type'] == 'date_range') {  ?>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

		<script type="text/javascript" src="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.css" />

		<input type="text" class="form-control" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $verifikasi == 0 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />

		<script type="text/javascript">
			$(function() {

				$('#input-<?= $id; ?>').daterangepicker({
					autoUpdateInput: false,
					locale: {
						cancelLabel: 'Clear'
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
		<!--  Piih Pembimbing -->
	<?php } elseif ($fields['type'] == 'select_pembimbing') {  ?>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

		<select type="text" class="<?= $fields['key']; ?> form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?>></select>
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<script>
			$(document).ready(function() {
				$('.<?= $fields['key']; ?>').select2({
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
					placeholder: 'Pilih Dosen Pembimbing',
					minimumInputLength: 3,
					// templateResult: formatRepo,
					// templateSelection: formatRepoSelection
				});
			});
		</script>
	<?php } elseif ($fields['type'] == 'ta') { ?>
		<select class="form-control
<?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> 
<?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" name="dokumen[<?= $id; ?>]" id="input-<?= $id; ?>">
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

	<?php } elseif ($fields['type'] == 'date') { ?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

		<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $verifikasi == 0 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
			$(function() {
				$("#input-<?= $id; ?>").datepicker();
			});
		</script>
	<?php } elseif ($fields['type'] == 'number') { ?>
		<input type="number" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

	<?php } elseif ($fields['type'] == 'multi_select_anggota') { ?>
		<?php
		$CI = &get_instance();
		$query = $CI->db->query("SELECT value FROM Tr_Field_Value WHERE pengajuan_id = $pengajuan_id AND field_id = $id")->row_array();
		$anggota_string = $query['value'];
		$anggota_array = explode(",", $anggota_string);
		?>

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

		<select class="js-data-example-ajax form-control form-control-lg <?= $fields['key']; ?> form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($verifikasi == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" <?= ($pengajuan_status == 1 || $pengajuan_status == 2 || $pengajuan_status == 4 && $verifikasi == 0) ? "" : "disabled"; ?> value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $field_value;  ?>" name="dokumen[<?= $id; ?>][]" multiple>
			<option value="">Pilih</option>
			<?php
			if ($pengajuan_status == 1 && $verifikasi == 0 || $pengajuan_status == 3 && $verifikasi == 0) {
			} else {
			?>
				<?php foreach ($anggota_array as $anggota) { ?>
					<option value="<?= $anggota; ?>"><?php get_mahasiswa_by_nim($anggota); ?></option>
				<?php } ?>
			<?php
			}
			?>
		</select>

		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
		<!-- <script>
      $(document).ready(function() {
        <?php
				if ($pengajuan_status == 1 && $verifikasi == 0 || $pengajuan_status == 3 && $verifikasi == 0) {
				} else {
				?>
          var selectedValuesTest = [
            <?php foreach ($anggota_array as $anggota) {
							echo '"' . $anggota . '"' . ',';
						} ?>
          ];
        <?php
				}
				?>
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
          // templateResult: formatRepo,
          // templateSelection: formatRepoSelection
        });
        $('.js-data-example-ajax').val(selectedValuesTest).trigger('change');
      });
    </script> -->



	<?php
	} // endif file 
}

function fileUploaderModal()
{
}


//menampilkan kategori keterangan surat
function generate_keterangan_surat($field_id, $id_surat, $pengajuan_status)
{
	$id = $field_id;
	$CI = &get_instance();
	$fields = $CI->db->select('*')->from('Mstr_Fields f')
		->join('Tr_Field_Value fv', 'fv.field_id=f.field_id', 'left')
		->where(array('f.field_id' => $id))
		->where(array('fv.pengajuan_id' => $id_surat))
		->get()->row_array();

	if ($fields['type'] == 'image') {
		$image = $CI->db->select('*')->from('Tr_Media')
			->where(array('id' => $fields['value']))->get()->row_array();
		$img_full = $image['file'];
		$thumb = $image['thumb'];
		$image = base_url($thumb);
	?>
		<figure style="background:url('<?= $image; ?>') center center no-repeat" class="d-flex align-items-start justify-content-start preview-dokumen">
			<a data-href="<?= base_url($img_full); ?>" class="opener btn btn-warning btn-md" type="button" data-toggle="modal" data-target="#fileZoom"><i class="fas fa-search-plus" data-toggle="tooltip" data-placement="top" title="Klik untuk memperbesar"></i></a>
		</figure>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))

			&& ($CI->session->userdata('role') == 2)

		) { ?>
			<div class="d-inline">
				<input type="hidden" name="verifikasi[<?= $id; ?>]" value="0" />
				<label class="switch">
					<input type="checkbox" class="verifikasi" name="verifikasi[<?= $id; ?>]" value="1" <?= ($fields['verifikasi'] == 1) ? 'checked' : '';  ?> />
					<span class="slider round"></span>
				</label>
			</div>
			<div class="d-inline">
				Data sudah sesuai? <a class="help" data-toggle="tooltip" data-placement="right" title="Klik tombol di samping jika data sudah sesuai"><i class="fa fa-info-circle"></i></a>
			</div>
		<?php }
	} elseif ($fields['type'] == 'textarea') { ?>

		<textarea class="form-control mb-2" id="input-<?= $id; ?>" disabled><?= $fields['value'];  ?></textarea>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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
	} elseif ($fields['type'] == 'text') { ?>

		<input type="text" class="form-control mb-2  <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" disabled value="<?= $fields['value'];  ?>" />

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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
	} elseif ($fields['type'] == 'date_range') { ?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" disabled value="<?= $fields['value'];  ?>" />

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" disabled value="<?= $fields['value'];  ?>"></input>
		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" disabled value="<?= $fields['value'];  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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
		print_r($dosen);

		?>

		<input type="text" class="form-control mb-2" id="input-<?= $id; ?>" disabled value="<?= $dosen['nama'];  ?>"></input>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

		<!-- <select class="js-data-example-ajax form-control form-control-lg <?= $fields['key']; ?> <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" name="dokumen[<?= $id; ?>][]" multiple>
      <?php
			if ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) {
			} else {
			?>
        <?php foreach ($anggota_array as $anggota) { ?>
          <option value="<?= $anggota; ?>"><?php get_mahasiswa_by_nim($anggota); ?></option>
        <?php } ?>
      <?php
			}
			?>
    </select> -->
		<table class="table table-striped table-bordered">

			<?php $i = 1;
			foreach ($anggota_array as $anggota) { ?>
				<tr>
					<td><?= $i++ ?> </td>
					<td><strong><?php get_mahasiswa_by_nim($anggota); ?></strong><br><?= $anggota; ?> </td>
					<td>Prodi </td>
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
			&& $CI->session->userdata('role') == 2
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
			<input type="number" class="form-control <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" disabled />
		</div>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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

		<input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />
		<span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

		<div class="mt-2"></div>

		<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script>
			$(function() {
				$("#input-<?= $id; ?>").datepicker();
			});
		</script>

		<?php if ((($pengajuan_status == 2 && $fields['verifikasi'] == 0) || ($pengajuan_status == 5 && $fields['verifikasi'] == 0))
			&& $CI->session->userdata('role') == 2
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

function get_meta_name($key)
{
	$CI = &get_instance();
	$name = $CI->db->select("kat_keterangan_surat")
		->from('kat_keterangan_surat')
		->where(array('key' => $key))
		->get()
		->row_array()['kat_keterangan_surat'];

	return $name;
}

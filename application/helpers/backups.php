<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function field($field_id)
{
    $CI = &get_instance();
    return $CI->db->get_where('Mstr_Fields', array('field_id' => $field_id))->row_array();
}

//menampilkan kategori keterangan surat
function generate_form_field($field_id, $pengajuan_id, $pengajuan_status)
{
    $id = $field_id;
?>
    <link href="<?= base_url() ?>public/plugins/dm-uploader/dist/css/jquery.dm-uploader.min.css" rel="stylesheet">
    <?php
    $CI = &get_instance();
    $fields = $CI->db->select('mf.*, fv.value, fv.verifikasi')->from('Mstr_Fields mf')
        ->join('Tr_Field_Value fv', 'fv.field_id=mf.field_id', 'left')
        ->where(array('mf.field_id' => $id))
        ->where(array('fv.pengajuan_id' => $pengajuan_id))
        // ->where(array('mf.type' => 'image'))
        ->get()->row_array();

    // print_r($fields);

    if ($fields['type'] == 'image') { ?>

        <?php
        $image_id = (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];

        $image = $CI->db->select('*')->from('Tr_Media')
            ->where(array('id' => $image_id))->get()->row_array();
        if ($image) {
            $thumb = $image['thumb'];
            $image = base_url($thumb);
        } else {
            $image = "base_url('public/dist/img/logo.png')";
            $thumb = '';
        }
        ?>

        <figure style="background:url('<?= $image; ?>') center center no-repeat" class="d-flex align-items-center justify-content-center upload-dokumen <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>">
            <?php
            if ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) {
                if ($thumb) { ?>
                    <button id="image-opener-<?= $id; ?>" class="opener hapus btn btn-danger btn-md" type="button"><i class="fas fa-trash"></i> Hapus</button>
                <?php } else { ?>
                    <button id="image-opener-<?= $id; ?>" class="opener btn btn-info btn-md" type="button" data-toggle="modal" data-target="#fileUploader"><i class="fas fa-plus"></i> Upload</button>
            <?php }  // $thumb
            } // $pengajuan_status = 1
            ?>

            <input type="hidden" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" class="dokumen" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" />
        </figure>
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

    <?php } elseif ($fields['type'] == 'textarea') {  ?>

        <textarea class="form-control 
		<?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> 
		<?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?>><?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?></textarea>
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

    <?php } elseif ($fields['type'] == 'text') {  ?>

        <input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

    <?php } elseif ($fields['type'] == 'date_range') {  ?>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>

        <script type="text/javascript" src="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/public/plugins/daterangepicker/daterangepicker.css" />

        <input type="text" class="form-control" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />

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

    <?php } elseif ($fields['type'] == 'ta') { ?>
        <select class="form-control
		<?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> 
		<?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" name="dokumen[<?= $id; ?>]" id="input-<?= $id; ?>">
            <option value=""> -- Pilih Tahun Akademik -- </option>
            <?php
            $cur_year = date("Y");
            $cur_semester = (date("n") <= 6) ?  $cur_year - 1 : $cur_year;
            for ($x = $cur_semester; $x <= $cur_year + 1; $x++) {
                $value_select = sprintf("%d / %d", $x, $x + 1); ?>
                <option value="<?= $value_select; ?>" <?= (validation_errors()) ? set_select('dokumen[' . $id . ']', $value_select) : ""; ?> <?= ($fields['value'] == $value_select) ? "selected" : ""; ?>><?= $x; ?> / <?= $x + 1; ?></option>
            <?php  }
            ?>
        </select>
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

    <?php } elseif ($fields['type'] == 'sem') { ?>
        <select class="form-control
		<?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> 
		<?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" name="dokumen[<?= $id; ?>]" id="input-<?= $id; ?>">
            <option value=""> -- Pilih Semester -- </option>
            <?php
            $cur_year = date("Y");
            $cur_semester = (date("n") <= 6) ?  "Genap" : "Ganjil";
            ?>
            <option value="Ganjil" <?= (validation_errors()) ? set_select('dokumen[' . $id . ']', "Ganjil") : ""; ?><?= ($fields['value'] == "Ganjil") ? "selected" : ""; ?>>Ganjil</option>
            <option value="Genap" <?= (validation_errors()) ? set_select('dokumen[' . $id . ']', "Genap") : ""; ?> <?= ($fields['value'] == "Genap") ? "selected" : ""; ?>>Genap</option>
        </select>
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

        <!--  Piih Pembimbing -->
    <?php } elseif ($fields['type'] == 'select_pembimbing') {    ?>
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
    <?php } elseif ($fields['type'] == 'number') { ?>
        <div class="form-group">
            <input type="number" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />
        </div>
    <?php } elseif ($fields['type'] == 'multi_select_anggota') { ?>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

        <select class="js-data-example-ajax form-control form-control-lg <?= $fields['key']; ?> form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" name="<?= $fields['key']; ?>[]" multiple></select>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
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
            });
        </script>
    <?php } elseif ($fields['type'] == 'file') { ?>

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

        /*background:url('<?= $image; ?>')*/
        ?>
        <figure style="background:url('<?= $image; ?>') center no-repeat;" class="d-flex align-items-center justify-content-center upload-dokumen <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>">
            <?php
            if ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) {
                if ($thumb) { ?>
                    <button id="file-opener-<?= $id; ?>" class="opener hapus btn btn-danger btn-md" type="button"><i class="fas fa-trash"></i> Hapus</button>
                <?php } else { ?>
                    <!-- <div class="col-12 text-center"> -->
                    <button id="file-opener-<?= $id; ?>" class="opener btn btn-info btn-md" type="button" data-toggle="modal" data-target="#fileUploader">
                        <i class="fas fa-plus"></i>
                        Upload
                    </button>
                    <p style="display: block;">
                        <span style="display: none;" id="file-url" class="file-opener-<?= $id; ?>">%%nama_file%%</span>
                    </p>
                    <!-- </div> -->
                <?php }
                // $thumb
            } else { ?>
                <div class="col-12 text-center">
                    <a class="btn btn-success btn-md" href="<?= base_url($thumb); ?>">Download</a>
                    <br>
                    <span id="file-url" class="file-opener-<?= $id; ?>"><?= $file_name; ?></span>
                </div>
            <?php } ?>

            <input type="hidden" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" class="dokumen" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" />
        </figure>
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>
    <?php } elseif ($fields['type'] == 'date') { ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <input type="text" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />
        <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

        <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(function() {
                $("#input-<?= $id; ?>").datepicker();
            });
        </script>
    <?php } ?>
<?php } ?>

<?php function fileUploaderModal()
{
    $CI = &get_instance();
    $CI->db->order_by('id', 'DESC');
    $media = $CI->db->get_where('Tr_Media', array('nim' => $CI->session->userdata('studentid')))->result_array();

?>
    <style>
        .container-img {
            position: relative;

        }

        .image {
            display: block;
            width: 100%;
            height: auto;
        }

        .overlay {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background-color: #008CBA;
            overflow: hidden;
            width: 100%;
            height: 0;
            transition: .5s ease;
        }

        .container-img:hover .overlay {
            bottom: 0;
            height: 100%;
        }

        .text {
            color: white;
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            text-align: center;
        }
    </style>

    <div id="fileUploader" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih File</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="alert alert-info alert-sm d-block mt-1 mb-3"><i class="fas fa-info-circle"></i> <strong>Catatan:</strong> File yang sama dapat digunakan berulang kali. (Pdf, Jpg, Png)</span>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-upload-tab" data-toggle="tab" href="#nav-upload" role="tab" aria-controls="nav-upload" aria-selected="true">Upload</a>
                            <a class="nav-item nav-link" id="nav-galeri-tab" data-toggle="tab" href="#nav-galeri" role="tab" aria-controls="nav-galeri" aria-selected="false">Galeri</a>

                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-tab">
                            <form class="mb-3 mt-3 dm-uploader p-5" id="drag-and-drop-zone">
                                <div class="form-row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group mb-2">
                                            <input type="hidden" class="value" value="" />
                                            <input type="hidden" class="form-control" aria-describedby="fileHelp" placeholder="No image uploaded..." readonly="readonly">
                                            <div class="progress mb-2 d-none">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
                                                    0%
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div role="button" class="btn btn-primary">
                                                <i class="fas fa-folder fa-fw"></i> Cari file atau seret ke sini
                                                <input type="file" title="Klik untuk menambahkan file">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="nav-galeri" role="tabpanel" aria-labelledby="nav-galeri-tab">
                            <small class="status text-muted"></small>
                            <div class="row pt-3 pb-3 no-gutters" id="files">
                                <?php
                                foreach ($media as $row) { ?>
                                    <div class="col-md-3">

                                        <div class="container-img">
                                            <img class="img rounded mx-auto d-block" src="<?= base_url($row['thumb']); ?>" width="100%" height="auto" />
                                            <div class="overlay">
                                                <div class="text">
                                                    <a title="Klik pada file yang ingin digunakan" href="#" class="link d-block" id="<?= $row['id']; ?>"><?php get_file_name($row['file']); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <figure class="" style="min-height:200px; border:1px solid #ddd;text-align:center;">
											<a title="Klik pada file yang ingin digunakan" href="#" class="link d-block" id="<?= $row['id']; ?>">
												<img class="img rounded mx-auto d-block" src="<?= base_url($row['thumb']); ?>" width="100%" height="auto" />
											</a>

											<p style="display:block;bottom:0; padding:5px 0;"><?php get_file_name($row['file']); ?></p>
										</figure> -->
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> <i class="fas fa-excla,ation-triangle"></i> Jika file tidak bisa diklik, klik tombol <strong>"Batal"</strong> lalu coba kembali.
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>

                </div>
            </div>
        </div>
    </div>

    <!-- File item template -->
    <script type="text/html" id="files-template">
        <div class="col-md-3 media mb-3">
            <figure class="img-thumbnail" style="min-height:200px; border:1px solid #ddd;text-align:center;">
                <a title="Klik pada file yang ingin digunakan" href="#" class="link d-block" id="%%id%%">
                    <img class="img rounded mx-auto d-block" src="<?= base_url(); ?>%%thumb%%" width="100%" height="auto" />
                </a>

                <p style="display:block;bottom:0; padding:5px 0;">%%filename%%</p>

            </figure>

        </div>
    </script>

    <script src="<?= base_url() ?>/public/plugins/dm-uploader/dist/js/jquery.dm-uploader.min.js"></script>


    <script>
        function ui_single_update_active(element, active) {
            element.find('div.progress').toggleClass('d-none', !active);
            element.find('input[type="text"]').toggleClass('d-none', active);

            element.find('input[type="file"]').prop('disabled', active);
            element.find('.btn').toggleClass('disabled', active);

            element.find('.btn i').toggleClass('fa-circle-o-notch fa-spin', active);
            element.find('.btn i').toggleClass('fa-folder-o', !active);

        }

        function ui_single_update_progress(element, percent, active) {
            active = (typeof active === 'undefined' ? true : active);

            var bar = element.find('div.progress-bar');

            bar.width(percent + '%').attr('aria-valuenow', percent);
            bar.toggleClass('progress-bar-striped progress-bar-animated', active);

            if (percent === 0) {
                bar.html('');
            } else {
                bar.html(percent + '%');
            }
        }

        function ui_single_update_status(element, message, color) {
            color = (typeof color === 'undefined' ? 'muted' : color);

            element.find('small.status').prop('class', 'status text-' + color).html(message);
        }

        $("button.opener").click(function(event) {
            let type = this.id
            let splitted = type.split("-")
            let id_element = splitted[2]
            let file
            // if (type == 'file-opener-' + id_element) {
            // 	console.log('pdf')
            // } else {
            // 	console.log('gambar')
            // }
            $('.value').val(event.target.id);

            $(function() {
                file = (type == 'file-opener-' + id_element ? ['pdf'] : ['jpg', 'jpeg', 'png', 'gif'])
                // console.log(file)
                $('#drag-and-drop-zone').dmUploader({ //
                    url: '<?= base_url('mahasiswa/pengajuan'); ?>/doupload/',
                    maxFileSize: 3000000, // 3 Megs max
                    multiple: false,
                    // allowedTypes: 'image/*',
                    extFilter: this.file,
                    // ['jpg', 'jpeg', 'png', 'gif']
                    onDragEnter: function() {
                        // Happens when dragging something over the DnD area
                        this.addClass('active');
                    },
                    onDragLeave: function() {
                        // Happens when dragging something OUT of the DnD area
                        this.removeClass('active');
                    },
                    onInit: function() {
                        this.find('input[type="text"]').val('');
                    },
                    onComplete: function() {

                    },
                    onNewFile: function(id, file) {

                        if (typeof FileReader !== "undefined") {
                            var reader = new FileReader();
                            var img = this.find('img');

                            reader.onload = function(e) {
                                img.attr('src', e.target.result);
                            }
                            reader.readAsDataURL(file);
                        }

                    },
                    onBeforeUpload: function(id) {
                        ui_single_update_progress(this, 0, true);
                        ui_single_update_active(this, true);

                        ui_single_update_status(this, 'Uploading...');
                    },
                    onUploadProgress: function(id, percent) {
                        // Updating file progress
                        ui_single_update_progress(this, percent);
                    },
                    onUploadSuccess: function(id, data) {
                        // console.log(data);

                        var response = JSON.stringify(data);

                        var obj = JSON.parse(response);

                        // console.log(obj);

                        ui_single_update_active(this, false);

                        this.find('input[type="text"]').val(response);

                        ui_single_update_status(this, 'Upload completed.', 'success');

                        var template = $('#files-template').text();
                        template = template.replace('%%filename%%', obj.filename);
                        template = template.replace('%%id%%', obj.id);
                        template = template.replace('%%thumb%%', obj.thumb);

                        template = $(template);
                        template.prop('id', 'uploaderFile' + id);
                        template.data('file-id', id);

                        $('#files').prepend(template);

                        $('#nav-tab a[href="#nav-galeri"]').tab('show');
                        // alert(response);
                    },
                    onUploadError: function(id, xhr, status, message) {
                        // alert(message);
                        // Happens when an upload error happens
                        ui_single_update_active(this, false);
                        ui_single_update_status(this, 'Error: ' + message, 'danger');
                    },

                    onFileSizeError: function(file) {
                        //   ui_single_update_status(this, 'File excess the size limit', 'danger');

                        // ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
                    },
                    onFileTypeError: function(file) {
                        ui_single_update_status(this, 'File type is not an image', 'danger');
                        // alert('bukan gambar')
                        // ui_add_log('File \'' + file.name + '\' cannot be added: must be an image (type error)', 'danger');
                    },
                    onFileExtError: function(file) {
                        ui_single_update_status(this, 'File extension not allowed', 'danger');
                        // alert('extensi salah')
                        //ui_add_log('File \'' + file.name + '\' cannot be added: must be an image (extension error)', 'danger');
                    }
                });
            });
        });

        $(document).on('click', '.link', function() {

            console.log(this.id)

            var link = $(this).attr('id'); /*ambil id gambar */
            var img = $(this).children().attr('src'); /*ambil id gambar */
            var value = $('.value').val(); /* ambil id button */
            var openerinput = $("#" + value).siblings(".dokumen").attr("id");
            var id_span = $("#file-url").attr('class')

            var file_id = this.id
            $.ajax({
                type: "POST",
                url: '<?= base_url('/mahasiswa/pengajuan/get_used_file_name'); ?>',
                dataType: "json",
                data: {
                    id: file_id
                },
                success: function(response) {
                    var template = $('#files-template').text();
                    var nama_file = response
                    template = template.replace('%%nama_file%%', nama_file);
                },
            });
            // console.log(value);
            // console.log(id_span);
            // console.log(img)

            $("#" + openerinput).val(link); /*insert value ke field hidden */
            $("#" + openerinput).parent("figure").siblings('span.text-danger').hide();
            $("#" + openerinput).parent("figure").removeClass('is-invalid');
            $("#" + openerinput).parent("figure").css('background-image', 'none');
            if (id_span == value) {
                $("#" + openerinput).parent("figure").css('background', 'url("' + "<?= base_url('public/dist/img/document.png'); ?>" + '") center center no-repeat');
            } else {
                $("#" + openerinput).parent("figure").css('background', 'url("' + img + '") center center no-repeat');
            }

            $('#fileUploader').modal('hide');
            $("#" + value).html('<i class="fas fa-trash"></i> Hapus');
            $("#" + value).addClass('btn-danger hapus').removeClass('btn-info');
            $("#" + value).removeAttr('data-toggle data-target');
            $("." + value).show();



            return false;
        })

        $(document).on('click', '.hapus', function() {
            var value = $('.value').val(); /* ambil id button */

            $(this).siblings(".dokumen").val('');
            $(this).parent("figure").css('background', 'none');
            $(this).parent("figure").css('background', 'none');
            $(this).addClass('btn-info').removeClass('btn-danger hapus');
            $(this).html('<i class="fas fa-plus"></i> Upload');
            $(this).attr("data-toggle", "modal").attr("data-target", "#fileUploader");
            $("." + value).hide();

        })
        $('textarea.form-control').on('keyup', function(e) {
            $(this).removeClass('is-invalid');
            $(this).siblings('span.text-danger').hide();
        })
    </script>

    <?php }


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
        $dosen = $CI->db->get_where('users', array('id' => $fields['value']))->row_array();
        ?>

        <input type="text" class="form-control mb-2" id="input-<?= $id; ?>" disabled value="<?= $dosen['fullname'];  ?>"></input>

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
    } ?>

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

<?php } ?>

<?php
function get_meta_value($key, $id_surat, $image)
{
    $CI = &get_instance();
    // $id = $CI->db->select("id")->from('kat_keterangan_surat')->where(array('key' => $key))->get()->row_array()['id'];

    // $value = $CI->db->select("value")->from('keterangan_surat')->where(array('id_kat_keterangan_surat' => $id))->get()->row_array()['value'];

    $value = $CI->db->select("kat_keterangan_surat.id, keterangan_surat.value ")
        ->from('kat_keterangan_surat')
        ->join('keterangan_surat', 'kat_keterangan_surat.id=keterangan_surat.id_kat_keterangan_surat', 'left')
        ->where(array("key" => $key))
        ->get()
        ->row_array();

    if ($image == true) {
        $media = $CI->db->select("file")->from('media')->where(array('id' => $value['value']))->get()->row_array()['file'];
        return $media;
    } else {
        return $value['value'];
    }
}

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

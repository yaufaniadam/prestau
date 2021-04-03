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
    $dosen = $CI->db->get_where('V_Dosen', array('id_pegawai' => $fields['value']))->row_array();
    // print_r($dosen);
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

    <select class="js-data-example-ajax form-control form-control-lg <?= $fields['key']; ?> <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" name="dokumen[<?= $id; ?>][]" multiple>
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
    </select>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
      $(document).ready(function() {
        <?php
        if ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) {
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

          // placeholder: 'Tuliskan NIM atau Nama Mahasiswa',
          // minimumInputLength: 3,
          // // templateResult: formatRepo,
          // // templateSelection: formatRepoSelection
        });
        $('.js-data-example-ajax').val(selectedValuesTest).trigger('change');
      });
    </script>

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

          <p style="display: none;" class="nama-file"></p>
          <!-- </div> -->
        <?php }
        // $thumb
      } else { ?>
        <div class="col-12 text-center">
          <a class="btn btn-success <?= isset($file_name) ? '' : 'disabled'; ?> btn-md" href="<?= base_url($thumb); ?>">Download</a>
          <br>
          <span id="nama-file" class="file-opener-<?= $id; ?>"><?= isset($file_name) ? $file_name : ''; ?></span>
        </div>
      <?php } ?>

      <input type="hidden" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" class="dokumen" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" />

    </figure>
    <span class="text-danger"><?php echo form_error('dokumen[' . $id . ']'); ?></span>

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
      <input type="number" class="form-control <?= (form_error('dokumen[' . $id . ']')) ? 'is-invalid' : ''; ?> <?= (($fields['verifikasi'] == 0) && ($pengajuan_status == 4)) ? 'is-invalid' : ''; ?>" value="<?= (validation_errors()) ? set_value('dokumen[' . $id . ']') :  $fields['value'];  ?>" id="input-<?= $id; ?>" name="dokumen[<?= $id; ?>]" <?= ($pengajuan_status == 1 && $fields['verifikasi'] == 0 || $pengajuan_status == 4 && $fields['verifikasi'] == 0) ? "" : "disabled"; ?> />
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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

<?php } ?>

<img width="40px" height="" src="<?= base_url() ?>/public/dist/img/spinner2.gif" />
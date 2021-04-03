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
          <label for="deskripsi" class="col-md-3 control-label">Deskripsi</label>
          <div class="col-md-9">

            <div class="<?= (form_error('deskripsinya')) ? 'summernote-is-invalid' : ''; ?>"><textarea name="deskripsinya" class="textarea-summernote"><?= (validation_errors()) ? set_value('deskripsinya') : '';  ?></textarea>
            </div>
            <span class="text-danger" style="font-size: 80%;"><?php echo form_error('deskripsinya'); ?></span>
          </div>
        </div>

        <div class="form-group row">
          <label for="template" class="col-md-3 control-label">Form Field
            <small id="" class="form-text text-muted">Seret lalu lepaskan form field yang tidak aktif ke kolom form field aktif.</small>
          </label>
          <div class="col-md-4">
            <div class="card card-success card-outline">
              <div class="card-header">Field terpakai</div>
              <div class="card-body box-profile ">
                <?php
                $default_field = $this->db->get_where('Mstr_Fields', array('key' => 'judul'))->row_array();
                ?>
                <ul id="sortable2" class="connectedSortable errorTxt">
                  <li class="ui-state-highlight" id="item-<?= $default_field['field_id']; ?>">
                    <?= $default_field['field']; ?>
                  </li>
                  <span id="errNm2"></span>
                </ul>
                <input type="hidden" name="fields" data-error="#errNm2" class="field_surat" id="" value="Sort=<?= $default_field['field_id']; ?>">
              </div>
            </div>
          </div>

          <div class="col-md-5">
            <div class="card card-success card-outline">
              <div class="card-header">Field tidak terpakai</div>
              <div class="card-body box-profile">
                <ul id="sortable1" style="list-style: none;" class="connectedSortable keterangan_surat list-group pl-0">
                  <?php
                  foreach ($all_fields as $field) {
                  ?>
                    <li class="ui-state-highlight <?= ($field['field_id'] == 1) ? 'd-none' : ''; ?> static" id="item-<?= $field['field_id']; ?>">
                      <?= $field['field']; ?>
                    </li>
                  <?php  }  ?>
                </ul>
              </div>
            </div>

            <span class="text-danger" style="line-height:1.5rem;font-size: 80%;"><?php echo form_error('kat_keterangan_surat[]'); ?></span>

            <span class="text-danger" style="font-size: 80%;"><?php echo form_error('template'); ?></span>
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
      cancel: ".disable-sort-item",
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
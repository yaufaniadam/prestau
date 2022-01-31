<?php
list($kat, $result, $nominal) = $kategori; ?>

<style>

    input.nominal {
        text-align: right;
    }
    .alert.simpan {
        display: none;
    }

    .error {
        color: red;
    }

    div.is-invalid {
        border: 1px solid #b0272b !important;
        border-radius: 6px;
    }
</style>

<div class="row">
  

    <div class="col-md-8 offset-md-2">
        <div class="card card-success card-outline">
            <ul class="nav nav-tabs pt-3 pl-4  bg-perak">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/jenispengajuan/'); ?>"><i class="fas fa-fw fa-table"></i> Jenis pengajuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/jenispengajuan/edit/' . $kat['jpi']); ?>"><i class="fas fa-fw fa-edit"></i> Edit Jenis pengajuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('admin/jenispengajuan/nominal_reward'); ?>"><i class="fas fa-fw fa-dollar-sign"></i> Nominal Reward</a>
                </li>
            </ul>
            <!-- <p class="card-header">Jenis Pengajuan</p> -->
            <div class="card-body box-profile">
                <div class="form-group">
                    <label for="Jenis_Pengajuan" class="control-label">Jenis Pengajuan</label>
                    <input type="text" class="form-control" value="<?= $kat['Jenis_Pengajuan']; ?>" readonly>

                </div>

                <?php // rekognisi
                if ($kat['parent'] == '1') { ?>

                    <form name="nominal_reward" id="nominal_reward" action="<?= base_url('admin/jenispengajuan/edit_nominal/' . $kat['jpi']); ?>">

                        <div class="form-group">
                            <label for="tipe_reward" class="control-label">Tipe Nominal Reward</label>
                            <ul class="list-group tipe_reward" id="tipe_reward">
                                <li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 1 ? 'checked="checked"' : ''; ?> value="1"> <span>Individu</span></li>
                                <li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 2 ? 'checked="checked"' : ''; ?> value="2"> <span>Kelompok (Ketua dan anggota memperoleh nominal yang berbeda)</span></li>
                                <li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 3 ? 'checked="checked"' : ''; ?> value="3"> <span>Kelompok (Reward diberikan kepada kelompok, bukan kepada tiap anggota)</span></li>
                                <li class="list-group-item"><input type="radio" name="tipe_reward" <?= $kat['fixed'] == 4 ? 'checked="checked"' : ''; ?> value="4"> <span>Berdasarkan biaya yang dikeluarkan oleh mahasiswa</span></li>

                            </ul>
                            <span class="invalid-feedback">Error</span>
                        </div>


                        <div class="form-group" id="nominal">
                            <label for="Jenis_Pengajuan" class="control-label">Nominal:</label>
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
                        var SITEURL = '<?php echo base_url(); ?>';
                        $(document).ready(function() {

                            $('select[name="type"]').on('change', function() {
                                var val = $(this).val();
                                var typeId = $(this).closest(".ui-state-highlights").attr("id");

                                if (val == 'judul') {
                                    $("#" + typeId).find("input[name='key']").attr('readonly', true);
                                    $("#" + typeId).find("input[name='key']").val('judul');
                                } else if (val == 'select_mahasiswa') {
                                    $("#" + typeId).find("input[name='key']").attr('readonly', true);
                                    $("#" + typeId).find("input[name='key']").val('anggota');
                                } else {
                                    $("#" + typeId).find("input[name='key']").attr('readonly', false);

                                    var old_value = $("#" + typeId).find("input[name='key']").val();

                                    if (old_value != '') {
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
                                                    $('#nominal_reward #tipe_reward').next('.invalid-feedback').addClass('d-block').html(res.error[k]);
                                                } else {
                                                    $('#nominal_reward').find("." + k).children('input').addClass('is-invalid').next('.invalid-feedback').html(res.error[k]);
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

                <?php // PKM
                } else if ($kat['parent'] != '1') { ?>

                    <form class="nominal_reward_prestasi" name="nominal_reward" method="POST" action="<?= base_url('admin/jenispengajuan/edit_nominal_prestasi/' . $kat['jpi']); ?>">

                        <label for="Jenis_Pengajuan" class="control-label">Juara & Nominal Reward:</label>

                        <input type="hidden" name="tipe_reward" id="" value="5">

                        <?php

                        $cekvalue = $this->db->select('*')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
                            "Jenis_Pengajuan_Id" => $kat['jpi'],
                        ])->get();



                        if ($cekvalue->num_rows() > 0) {

                            $i = 0;
                            foreach ($cekvalue->result_array() as $field) { ?>

                                <div class="formtoclone" id="form-<?= $i; ?>" data-id="<?= $i; ?>">

                                    <div class="form-group row " id="nominal-pkm">
                                        <label for="Jenis_Pengajuan" class="control-label col-md-7">

                                            <div class="input-group  keterangan">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Keterangan *</div>
                                                </div>
                                                <input type="text" value="<?= $field['keterangan']; ?>" name="keterangan[]" class="form-control keterangan" placeholder="contoh: Juara 1 Poster" id="keterangan-<?= $i; ?>">
                                                <span class="invalid-feedback"></span>
                                            </div>

                                        </label>

                                        <div class="col-md-5">
                                            <div class="input-group  nominal">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp</div>
                                                </div>
                                                <input type="text" value="<?= $field['nominal']; ?>" name="nominal[]" class="form-control nominal" id="nominal-<?= $i; ?>">
                                                <span class="invalid-feedback"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            <?php $i++;
                            }
                        } else {
                            //jika masih baru dan blm ada nilai
                            ?>
                            <div class="formtoclone" id="form-0" data-id="0">

                                <div class="form-group row">
                                    <label for="Jenis_Pengajuan" class="control-label col-md-7">

                                        <div class="input-group  keterangan">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Keterangan *</div>
                                            </div>
                                            <input type="text" value="" name="keterangan[]" class="form-control keterangan" placeholder="contoh: Juara 1 Poster" id="keterangan-0">
                                            <span class="invalid-feedback"></span>
                                        </div>

                                    </label>

                                    <div class="col-md-5">
                                        <div class="input-group nominal">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp</div>
                                            </div>
                                            <input type="text" value="12" name="nominal[]" class="form-control nominal"  id="nominal-0">
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        <?php } ?>

                        <button class="clone btn btn-md btn-warning mb-3"><i class="fas fa-plus"></i> Tambah</button>

                        <input type="submit" name="submit" value="Simpan" class="btn btn-success btn-block simpan_nominal">
                        <p class="mt-1 sukses_simpan text-success text-center"><i class="fas fa-check-circle"></i> Berhasil disimpan</p>
                    </form>

                    <script>

                        $('.sukses_simpan').hide();

                        $(document).ready(function() {

                            $(':input[type="number"]').inputmask();

                        });

                        $(".clone").click(function() {

                            var
                                $self = $(this),
                                $element_to_clone = $self.prev(),
                                $previousID = parseFloat($self.prev().data('id')) + 1;

                            $new_element = $element_to_clone.clone().attr('data-id', $previousID).attr('id', 'form-' + $previousID);

                            $new_element.find(':input[type="number"]').attr('id', 'nominal-' + $previousID).val('');
                            $new_element.find(':input[type="text"]').attr('id', 'keterangan-' + $previousID).val('');

                            $new_element.insertAfter($element_to_clone);

                            return false;

                        });

                        // $new_element.find('.del').removeClass('hidden disabled').addClass('enabled');

                        var SITEURL = '<?php echo base_url(); ?>';


                        var frm = $('.nominal_reward_prestasi');



                        $(frm).bind('submit', function(e) {
                            e.preventDefault();

                            // $(frm).closest('.invalid-feedback').addClass('d-none');

                            $.ajax({
                                url: SITEURL + "admin/jenispengajuan/edit_nominal_prestasi/" + <?= $kat['jpi']; ?>,
                                data: frm.serialize(),
                                type: "post",
                                dataType: 'json',
                                success: function(res) {

                                    if (res.status == 'Error') {

                                        // foreach error keynya
                                        Object.keys(res.error).forEach(function(k) {
                                            if (res.error[k] !== '') {

                                                var idarray = $(".nominal_reward_prestasi")
                                                    .find(':input.' + k) //Find the spans
                                                    .map(function() {
                                                        return this.id;
                                                    }) //Project Ids
                                                    .get(); //ToArray                                  

                                                $.each(idarray, function(key, value) {

                                                    var fieldvalue = $("#" + value).val();

                                                    if (fieldvalue == '') {

                                                        $("#" + value).addClass('is-invalid').next('.invalid-feedback').html(res.error[k]);

                                                    }

                                                });


                                            }
                                        });

                                    } else {

                                        $('.simpan_nominal').next('.sukses_simpan').fadeIn().delay(500).fadeOut();
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
                       
                    </script>

                <?php } ?>



            </div>



        </div>
    </div>



</div>

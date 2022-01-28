<?php
list($kat, $result, $nominal) = $kategori;


// echo "<pre>";
// print_r($kat);
// echo "</pre>";



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

    div.is-invalid {
        border: 1px solid #b0272b !important;
        border-radius: 6px;
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
                } else if ($kat['parent'] == '3') { ?>

                    <form class="nominal_reward_prestasi" name="nominal_reward" method="POST" action="<?= base_url('admin/jenispengajuan/edit_nominal_prestasi/' . $kat['jpi']); ?>">

                        <label for="Jenis_Pengajuan" class="control-label">Juara & Nominal Reward:</label>

                        <input type="hidden" name="tipe_reward" id="" value="5">

                        <?php 
                        
                        $cekvalue = $this->db->select('*')->from('Mstr_Penghargaan_Rekognisi_Mahasiswa')->where([
                            "Jenis_Pengajuan_Id" => $kat['jpi'],
                        ])->get();
                        
                    

                        if($cekvalue->num_rows() > 0) {

                            foreach($cekvalue->result_array() as $field) { ?>
                             
                             <div class="formtoclone" id="form">                           
                           
                                <div class="form-group row " id="nominal-pkm">
                                    <label for="Jenis_Pengajuan" class="control-label col-md-7">

                                        <div class="input-group  keterangan">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Keterangan *</div>
                                            </div>
                                            <input type="text" value="<?=$field['keterangan']; ?>" name="keterangan[]" class="form-control" placeholder="contoh: Juara 1 Poster">
                                            <span class="invalid-feedback"></span>
                                        </div>

                                    </label>

                                    <div class="col-md-5">
                                        <div class="input-group  nominal">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp</div>
                                            </div>
                                            <input type="number" value="<?=$field['nominal']; ?>" name="nominal[]" class="form-control">
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>                             

                                </div>   
                            </div> 


                           <?php }
                           
                        } else {
                           //jika masih baru dan blm ada nilai
                           ?>
                             <div class="formtoclone" id="form">
                           
                           
                           <div class="form-group row " id="nominal-pkm">
                               <label for="Jenis_Pengajuan" class="control-label col-md-7">

                                   <div class="input-group  keterangan">
                                       <div class="input-group-prepend">
                                           <div class="input-group-text">Keterangan *</div>
                                       </div>
                                       <input type="text" value="" name="keterangan[]" class="form-control" placeholder="contoh: Juara 1 Poster">
                                       <span class="invalid-feedback"></span>
                                   </div>

                               </label>

                               <div class="col-md-5">
                                   <div class="input-group  nominal">
                                       <div class="input-group-prepend">
                                           <div class="input-group-text">Rp</div>
                                       </div>
                                       <input type="number" value="" name="nominal[]" class="form-control">
                                       <span class="invalid-feedback"></span>
                                   </div>
                               </div>                             

                           </div>   
                       </div>

                       <?php } ?>                        
                        
                        <button class="clone btn btn-md btn-warning mb-3">Tambah</button>

                        <input type="submit" name="submit" value="Simpan" class="btn btn-success btn-block simpan_nominal">
                        <p class="mt-1 sukses_simpan text-success text-center"><i class="fas fa-check-circle"></i> Berhasil</p>              
                    </form>

                    <script>

                        var cloneCount = 1;      

                        $(".clone").click(function() {
                          
                                var
                                $self = $(this),
                                    $element_to_clone = $self.prev(),
                                    $new_element = $element_to_clone.clone();

                                $new_element.find('.del').removeClass('hidden disabled').addClass('enabled');

                                $new_element.insertAfter($element_to_clone);

                                return false;
                                i++; 
                            });

                        var SITEURL = '<?php echo base_url(); ?>';
                       

                        var frm = $('.nominal_reward_prestasi');



                        // $(frm).bind('submit', function(e) {
                        //     e.preventDefault();

                        //     // $(frm).closest('.invalid-feedback').addClass('d-none');

                        //     $.ajax({
                        //         url: SITEURL + "admin/jenispengajuan/edit_nominal_prestasi/" + <?= $kat['jpi']; ?>,
                        //         data: frm.serialize(),
                        //         type: "post",
                        //         dataType: 'json',
                        //         success: function(res) {

                        //             console.log('sukses');

                        //             // if (res.status == 'Error') {

                        //             //     // foreach error keynya
                        //             //     Object.keys(res.error).forEach(function(k) {
                        //             //         if (res.error[k] !== '') {

                        //             //             if (k == 'tipe_reward') {
                        //             //                 $('#nominal_reward #tipe_reward').addClass('is-invalid');
                        //             //                 $('#nominal_reward #tipe_reward').next('.invalid-feedback').addClass('d-block').html(res.error[k]);
                        //             //             } else {
                        //             //                 $('#nominal_reward').find("." + k).children('input').addClass('is-invalid').next('.invalid-feedback').html(res.error[k]);
                        //             //             }
                        //             //         }
                        //             //     });

                        //             // } else {

                        //             //     // 	$('#nominal_reward').find('.invalid-feedback').hide();
                        //             //     $('.simpan_nominal').next('.sukses_simpan').fadeIn().delay(500).fadeOut();
                        //             //     $('#nominal_reward').find("input, select").removeClass('is-invalid');
                        //             // }
                        //         },
                        //         error: function(data) {
                        //             console.log('Error:', data);
                        //         }
                        //     });
                        // });

                        // hialngkan eror ketika field diklik
                        $('.form-control').on('keypress', function() {
                            $(this).removeClass('is-invalid');
                        })
                        $('#tipe_reward li').on('click', function() {
                            $('#tipe_reward').removeClass('is-invalid');
                            $('#tipe_reward').next('.invalid-feedback').removeClass('d-block');
                        })
                    </script>

                <?php } ?>

                

            </div>



        </div>
    </div>


    <script src="<?= base_url('public/vendor/jquery-ui-1.12.1/jquery-ui.min.js'); ?>"></script>
    <script src="<?= base_url() ?>public/plugins/inputmask/jquery.inputmask.bundle.js"></script>



</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
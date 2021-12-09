<div class="row">
  <div class="col-12">

    <div class="card card-warning">

    <?php if($_SESSION['role'] == 1 || $_SESSION['role'] == 2 ) { 
		
			?>

			<ul class="nav nav-tabs pt-3 pl-4 bg-perak">
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url("admin/pengajuan/index/" . $this->session->userdata('role')); ?>"><i class="fas fa-fw fa-exclamation-circle"></i> Perlu Diproses</a>
				</li>
		
				<li class="nav-item">
					<a class="nav-link" href="<?= base_url("admin/pengajuan/index/"); ?>"><i class="fas fa-fw fa-envelope"></i> Semua Pengajuan</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link active" href="<?= base_url("admin/pengajuan/verified/"); ?>"><i class="fas fa-fw fa-award"></i> Lolos Verifikasi</a>
				</li>
				
			</ul>

			<?php } ?>
     
      <div class="card-body">

      <p class="alert alert-warning"><i class="fas fa-award"></i>
        Berikut ini pengajuan yang lolos Verifikasi. Pilih pengajuan lalu pilih periode penerbitan.
      </p>

        <?php echo form_open_multipart(base_url("admin/pengajuan/verified/"), 'class="lolos" id="lolos"') ?>
        <table id="pengajuan-desc" class="table table-bordered tb-pengajuans">
          <thead>
            <tr>
              <th style="width:1%"><input type="checkbox" name="" id="check_all"></th>
              <th style="width:30%">Judul</th>
              <th style="width:20%">Kategori</th>
              <th>Mahasiswa</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody id="checkArray">
            <?php

            foreach ($query as $pengajuan) {
            ?>
              <tr class="<?= ($pengajuan['status_id'] == 2) ? 'proses' : ''; ?> <?= ($pengajuan['status_id'] == 4) ? 'perlu-revisi' : ''; ?>">
                <td class="text-center align-middle">
                  <input type="checkbox" name="pengajuan_id[]" value="<?= $pengajuan['pengajuan_id']; ?>" class="check">
                </td>
                <td>
                  <a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
                    <?= get_meta_value('judul', $pengajuan['pengajuan_id'], false) ?></a>
                </td>
                <td>
                  <a class="judul" href="<?= base_url('admin/pengajuan/detail/' . $pengajuan['pengajuan_id']); ?>">
                    <?= $pengajuan['Jenis_Pengajuan']; ?></a>
                </td>

                <td>
                  <p class="m-0">
                    <?= $pengajuan['FULLNAME']; ?>
                  </p>
                  <p class="badge m-0 badge-ijomuda">
                    <?= $pengajuan['NAME_OF_DEPARTMENT']; ?>
                  </p>
                </td>
                <td>
                  <p class="m-0">
                    <?= $pengajuan['date'];  ?>
                  </p>
                  <p class="badge m-0 badge-warning">
                    <?= $pengajuan['time'];  ?>
                  </p>
                </td>
                </td>
              </tr>
            <?php  } ?>
          </tbody>
        </table>

        <div class="form-group row ml-0 mt-3">
          <label for="" class="col-md-3">Pilih Periode Penerbitan</label>
          <div class="col-md-5">
            <select name="periode_id" class="form-control" id="exampleFormControlSelect1">
              <?php foreach ($daftar_periode as $periode) { ?>
                <option value="<?= $periode['id_periode']; ?>"><?= $periode['nama_periode']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4">
            <input type="submit" name="submit" class="btn btn-success" value="Simpan" />

          </div>
        </div>


        <?php echo form_close() ?>
      </div><!-- /.card-body -->
    </div><!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->


<!-- DataTables -->
<script src="<?= base_url() ?>/public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
  $(document).ready(function() {
    var state = false

    $('#check_all').click(function() {
      if (state == false) {
        state = true
        $('.check').prop('checked', true);
      } else {
        state = false
        $('.check').prop('checked', false);
      }
    });


    $('#lolos').submit(function() {
      var check = $('#checkArray').find('input[type=checkbox]:checked').length > 0;

      if (check === false) {
        alert('Centang minimal satu Judul pengajuan');
        return false;
      }
    });


    <?php if ($title != 'Pengajuan telah diverifikasi') { ?>
      $('#pengajuan-desc').DataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
      });
    <?php } ?>
  });
</script>
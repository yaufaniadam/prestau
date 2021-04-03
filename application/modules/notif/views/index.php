<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="datatable-desc" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style="width:50%">Subyek</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Role</th>
              <th class="text-center">Kepada</th>
              <th class="text-center">Prodi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($notif->result_array() as $row) {  ?>
              <tr class="<?= ($row['status'] == 1) ? 'light' : 'table-danger'; ?>">
                <td><a class="font-weight-bold text-dark" href=" <?= base_url('notif/detail/' . $row['notif_id']); ?>"><?= $row['judul_notif']; ?></a></td>
                <td class=" text-center">
                  <p class="m-0"><?= $row['date_full'];  ?></p>
                  <p class="badge m-0 badge-warning"><?= $row['time']; ?></p>
                </td>
                <td><?= $row['role'];  ?></td>
                <td><?= $row['kepada'];  ?></td>
                <td><?= $row['id_prodi'];  ?></td>
              </tr>
            <?php } ?>
          </tbody>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
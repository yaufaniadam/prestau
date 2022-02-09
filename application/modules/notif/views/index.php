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
              <!-- <th class="text-center">Prodi</th> -->
            </tr>
          </thead>
          <tbody>
            <?php foreach ($notif->result_array() as $row) {  ?>
              <tr class="<?= ($row['status'] == 1) ? 'light' : 'table-danger'; ?>">
                <td><?= $row['judul_notif']; ?></td>
                <td class=" text-center">
                  <p class="m-0"><?= $row['tanggal_masuk'];  ?></p>
                </td>
                <td><?= $row['role'];  ?></td>
                <td><?= $row['penerima'];  ?></td>
               
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
<div class="row">
	<div class="col-md-12">
		<div class="card">



			<?php

			if (isset($_POST['submit'])) {

				// Buat sebuah tag form untuk proses import data ke database
				echo form_open_multipart(base_url('admin/pengguna/import/' . $file_excel), 'class="form-horizontal"');
			?>

			<div class="card-header py-3">
				<i style="color:red" class="fa fa-exclamation-triangle"></i><strong> Periksa kembali dengan seksama</strong>
			</div>

			<div class="card-body">
				<table id="datatable" class="table table-bordered table-striped">
					<thead>
						<tr>
						
							<th>NIM</th>
							<th>Username</th>
							<th>Password</th>
							<th>Nama Lengkap</th>
							<th>Email</th>
							<th>Status</th>
						</tr>
						<thead>
						<tbody>
							<?php
						$numrow = 1;
						foreach ($sheet as $row) {
							// Ambil data pada excel sesuai Kolom
							if ($numrow > 1) {

								echo "<tr>";
								echo "<td>" . $row['A'] . "</td>";
								echo "<td>" . $row['A'] . "</td>";
								echo "<td>" . $row['A'] . "</td>";
								echo "<td>" . $row['B'] . "</td>";
								echo "<td>" . $row['C'] . "</td>";
								echo "<td>Status</td>";
								echo "</tr>";
							}
							$numrow++; // Tambah 1 setiap kali looping
						}

						?>
					<tbody>
				</table>

				<hr>

				<button class='btn btn-success' type='submit' name='import'>Import</button>
				<a class="btn btn-warning" href="<?= base_url("admin/pengguna/upload"); ?>">Cancel</a>

				<?php echo form_close(); ?>
			</div>

			<?php } else {

				echo form_open_multipart(base_url('admin/pengguna/upload'), 'class="form-horizontal"') ?>

			<div class="card-header py-3">
				<i class="fa fa-exclamation-triangle"></i><strong> Panduan Import
					Pengguna</strong>
			</div>

			<div class="card-body">
				<ol class="panduan-pengisian">
					<li>Ekstensi File yang didukung hanya .xlsx</li>
					<li>Data yang diimport harus mengikuti template yang sudah disediakan. <a
							href="<?= base_url('public/template-excel/import_mahasiswa.xlsx'); ?>"
							class="btn btn-perak btn-sm"><i class="fas fa-file-excel"></i> Unduh Template
							Excel</a></li>
				</ol>
				<div class="form-group">
					<input type="file" name="file" class="form-control">
				</div>

				<div class="form-group">
					<input type="submit" name="submit" value="Upload" class="btn btn-warning pull-right">
				</div>
			</div>

			<?php echo form_close();
			}
			?>

		</div>
	</div>
</div>

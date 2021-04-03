<h1 class="h3 mb-4 text-gray-900"><?= $surat['kategori_surat']; ?> </h1>

<div class="row">
	<div class="col-8">
		<div class="card">
			<div class="card-header py-3">
				Keterangan
			</div>
			<div class="card-body">
				<p>Lengkapi dokumen persyaratan berikut ini:</p>

				<?php echo form_open(base_url('mahasiswa/surat/tambah/' . $surat['id']), '') ?>

				<input type="hidden" name="id_surat" value="<?= $surat['id']; ?>">
				<?php $ket_surat = explode(',', $surat['kat_keterangan_surat']);

				foreach ($ket_surat as $ket_surat => $value) {
					$type = kat_keterangan_surat($value)['type'];
					$kat_keterangan_surat = kat_keterangan_surat($value)['kat_keterangan_surat']; ?>

					<div class="form-group row">
						<label class="col-md-4" for="dokumen[<?= $value; ?>]"><?= kat_keterangan_surat($value)['kat_keterangan_surat']; ?></label>
						<div class="col-md-8">
							<?php generate_form_field($value, $surat['id'],  $surat['id']); ?>
						</div>
					</div>



				<?php } ?>


				<input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Ajukan Surat <?= $surat['kategori_surat']; ?>" />
				<?php echo form_close(); ?>
			</div>


		</div>
	</div>

	<script>
		$(function() {
			$('#sudahPeriksa').click(function(e) {
				if ($(this).is(':checked')) {
					$('#sub1').removeAttr('disabled');
				} else {
					$('#sub1').attr('disabled', 'disabled');
				}
			});
		});
	</script>

	<!-- /.col -->
	<div class="col-4">

		<div class="card">
			<div class="card-header py-3">
				Status Surat
			</div>
			<div class="card-body">
				<p class="h4 text-center pb-2"> <?= $surat['status']; ?> </p>
				<ul class="list-group">

					<li class='list-group-item'>Tanggal masuk
						<span class='badge badge-warning float-right'><?= tgl_status_surat($surat['id'], '2')['date']; ?></span>
					</li>
					<li class='list-group-item'>Tanggal terbit</span>
						<span class='badge badge-success float-right'><?= tgl_status_surat($surat['id'], '5')['date']; ?></span>
					</li>


				</ul>
			</div>
		</div>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->
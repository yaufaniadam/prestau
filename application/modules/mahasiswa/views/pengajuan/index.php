<style>
[data-toggle="collapse"] .fas:before {   
  content: "\f139";
}

[data-toggle="collapse"].collapsed .fas:before {
  content: "\f13a";
}
</style>

<div class="row">
	<div class="col-md-7 offset-md-2">
	<p>Pilih kategori di bawah ini.</p>
		<div class="accordion" id="accordion">

        <?php foreach ($kategori_pengajuan as $kp) { ?>

			<div class="accordion" id="kp-<?= $kp['parent']; ?>">
				<div class="card">
					<div class="card-header" id="heading-<?= $kp['parent']; ?>">
						<h5 class="h6 my-2"> 
							<a role="button" data-toggle="collapse" href="#collapse-<?= $kp['parent']; ?>" aria-expanded="true" aria-controls="collapse-<?= $kp['parent']; ?>">
							<i class="fas"></i>	<?= $kp['kategori_pengajuan']; ?>
							</a>
						</h5>
					</div>
                </div>
                <div id="collapse-<?= $kp['parent']; ?>" class="collapse" data-parent="#accordion" aria-labelledby="heading-<?= $kp['parent']; ?>">
						<div class="card-body">
                            <table class="table table-striped table-bordered">                                    
                                <?php                               
                                
                                $prestasi = $this->pengajuan_model->prestasi($kp['parent']);
                                $i= 1;
                                foreach ($prestasi as $prestasi) { ?>
                                    <tr>
                                        <td>
                                        <?=  $i . '. ' .$prestasi['Jenis_Pengajuan']; ?> <a class="btn btn-sm btn-success float-right" href="<?= base_url('mahasiswa/pengajuan/baru/' . $prestasi['Jenis_Pengajuan_Id']); ?>">Ajukan <i class="fas fa-plus"></i> </a>
                                        </td>
                                    </tr>                                    

                                    <?php                                   
                               $i++; }                                  
                                ?>

                            </table>
                        </div>
                </div>
            </div>

            <?php } ?>

        </div>
    </div>
</div>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function get_jumlah_pengajuan_perbulan($no_urut)
{
	$CI = &get_instance();

	$no = (int)$no_urut;

	$nama_bulan = [
		1 =>
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"Oktober",
		"November",
		"December",
	];

	if ($_SESSION['role'] == 5) {
		$prodi_user = $CI->db->select('prodi')
			->from('users')
			->where([
				'id' => $_SESSION['user_id']
			])
			->get()
			->row_object()
			->prodi;

		return $CI->db->select("*")
			->from("tr_pengajuan p")
			->join('tr_pengajuan_status ps', 'ps.pengajuan_id=pp.id_pengajuan')
			->where([
				// "MONTH(tanggal) =" => $no,
				"ps.status_id" => 9,
				"m.DEPARTMENT_ID" => $prodi_user
			])
			->get()
			->num_rows();
	} else {
		return $CI->db->select("distinct(ps.pengajuan_id) as psid")
		->from("tr_pengajuan p")
		->join('tr_pengajuan_status ps', 'ps.pengajuan_id=p.pengajuan_id')
			->where([

				"ps.status_id > " => 1
			])
			->get()
			->num_rows();
	}
}

function konversi_date($format, $value) {

	$new_date = date($format, strtotime($value));

	return  $new_date;
}

function get_verified()
{
	$CI = &get_instance();

		$jmlstatus =  $CI->db->select("distinct(pengajuan_id)")
		->from("tr_pengajuan_status")
			->where("status_id =", 7)->get()->num_rows();

		$jmlnextstatus =  $CI->db->select("distinct(pengajuan_id)")
		->from("tr_pengajuan_status")
			->where("status_id =", 9 )->get()->num_rows();

		return $jmlstatus-$jmlnextstatus;
	
}

function get_jumlah_prestasi_perbulan($no_urut, $year)
{
	$CI = &get_instance();

	$no = (int)$no_urut;

	if ($_SESSION['role'] == 5) {

		return $CI->db->select("*")
			->from("v_prestasi")		
			->where([
				"MONTH(tanggal) =" => $no,
				"YEAR(tanggal) =" => $year,
				"DEPARTMENT_ID" => $_SESSION['id_prodi']
			])
			->get()
			->num_rows();
	} else {
	
		return $CI->db->select("*")
			->from("v_prestasi")
			->where([				
				"MONTH(tanggal) =" => $no,
				"YEAR(tanggal) =" => $year			
			])
			->get()
			->num_rows();
	}
}

function get_nama_bulan($no_urut)
{
	$no = (int)$no_urut;

	$nama_bulan = [
		1 =>
		"Januari",
		"Februari",
		"Maret",
		"April",
		"Mei",
		"Juni",
		"Juli",
		"Agustus",
		"September",
		"Oktober",
		"November",
		"Desember",
	];

	return $nama_bulan[$no];
}

function get_jumlah_prestasi_per_jenis_pengajuan($jenis_pengajuan_id, $year)
{
	$CI = &get_instance();

	if ($_SESSION['role'] == 5) {

		return $CI->db->select('*')
			->from('v_prestasi')
			->where([
				"status" => 1,
				"Jenis_Pengajuan_Id" => $jenis_pengajuan_id,
				"DEPARTMENT_ID" => $_SESSION['id_prodi'],
				"YEAR(tanggal) =" => $year
			])
			->get()
			->num_rows();

	} else {
		return $CI->db->select('*')
			->from('v_prestasi')
			->where([
				"status" => 1,
				'Jenis_Pengajuan_Id' => $jenis_pengajuan_id,
				"YEAR(tanggal) =" => $year
			])
			->get()
			->num_rows();
	}
}


function get_jumlah_prestasi_per_prodi($year)
{
	$CI = &get_instance();
	
	$department = $CI->db->select('*')
			->from('mstr_department')->get()->result_array();

	foreach ($department as $department) {
		$pengajuan_per_prodi[] = [
			'nama_prodi' => $department['NAME_OF_DEPARTMENT'],
			'jumlah_pengajuan' => $CI->db->select('*')
				->from('v_prestasi')
				->where([
					'DEPARTMENT_ID' => $department['DEPARTMENT_ID'],
					"YEAR(tanggal) =" => $year,
					"status" => 1,
				])
				->get()
				->num_rows()
		];
	}
	return $pengajuan_per_prodi;
}

function profPic($id, $w)
{
	if ($id) {
		$year = substr($id, 0, 4);
		$pic = '<div style="width:' . $w . 'px;height:' . $w . 'px; background:url(https://krs.umy.ac.id/FotoMhs/' . $year . '/' . $id . '.jpg) center top no-repeat; background-size:100%;" class="img-profile rounded-circle"></div>';
	} else {
		$pic = '<div style="width:' . $w . 'px;height:' . $w . 'px; background:url(https://source.unsplash.com/QAB-WJcbgJk/60x60) center top no-repeat; background-size:100%;" class="img-profile rounded-circle"></div>';
	}

	return $pic;
}


function bulan_romawi($bulan)
{
	$bln = array(
		1 =>
		'I',
		'II',
		'III',
		'IV',
		'V',
		'VI',
		'VII',
		'VIII',
		'IX',
		'X',
		'XI',
		'XII'
	);

	return  $bln[$bulan];
}


function tampil_notif()
{

	//cek apakah ada kategori surat yg blm selesai
	$CI = &get_instance();

	if ($_SESSION['role'] == 1) {
		$where = ['n.role' => 1];
	} else if ($_SESSION['role'] == 2) {
		$where = ["n.role" => 2];
	} else if ($_SESSION['role'] == 3) {
		$where = [
			"n.role" => 3,
			"n.penerima" =>  $_SESSION['studentid'],
		];
	} else if ($_SESSION['role'] == 4) {
		$where = [
			"n.role" => 4,
			"n.penerima" => $_SESSION['user_id']
		];
	} else if ($_SESSION['role'] == 5) {
		$where = ["n.role" => 5];
	} else if ($_SESSION['role'] == 6) {
		$where = ["n.role" => 6];
	}

	$notif = $CI->db
		->select("*")
		->from('tr_notif n')
		->join('mstr_status_pesan sp', 'sp.status_pesan_id = n.id_status_notif')
		->join('tr_pengajuan p', 'p.pengajuan_id=n.id_pengajuan')
		->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id=p.Jenis_Pengajuan_Id')
		->join('v_mahasiswa m', 'm.STUDENTID=p.nim')
		->order_by('id_notif', 'DESC')
		->where($where)
		->where(['n.status' => 0])
		->get();

?>
	<!-- Nav Item - Alerts -->
	<li class="nav-item dropdown no-arrow mx-1">
		<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fas fa-bell fa-fw"></i>
			<!-- Counter - Alerts -->
			<?php
			$notif_count = $notif->num_rows();
			if ($notif_count > 0) { ?>
				<span class="badge badge-danger badge-counter"><?php echo $notif->num_rows(); ?></span>
			<?php } ?>
		</a>
		<!-- Dropdown - Alerts -->
		<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
			<h6 class="dropdown-header">
				Notifikasi
			</h6>

			<?php
					
			if ($notif_count > 0) {
				foreach ($notif->result_array() as $notif) {
			?>
					<a class="dropdown-item d-flex align-items-center notif" id="<?= $notif['id_notif']; ?>" name="<?= $notif['pengajuan_id']; ?>" href="#">
						<div>
							<div class="small text-gray-500"><?= $notif['tanggal_masuk']; ?></div>
							<span class="font-weight-bold text-<?= $notif['badge']; ?>"> <i class="fas <?= $notif['icon']; ?>"></i>
								<?= $notif['judul_notif']; ?> 
							</span> &raquo; <span class="font-weight-bold">
								<?= $notif['Jenis_Pengajuan']; ?>
							</span>
							<span class="font-weight-normal">(
								<?= $notif['FULLNAME']; ?>)
							</span>
						</div>
					</a>
				<?php } // end foreach
			} else { ?>
				<a class="dropdown-item d-flex align-items-center" href="#">
					<div>
						<span class="text-gray-500">Belum ada notifikasi</span>
					</div>
				</a>
			<?php	} 	?>

			<!-- <a class="dropdown-item text-center medium text-gray-500" href="<?= base_url('notif'); ?>">Lihat semua Notifikasi</a> -->
		</div>
	</li>
	<script type="text/javascript">
		$(".notif").click(function() {

			var nid = this.id
			var pid = this.name

			$.ajax({
				url: "<?= base_url('notif/read_notif/'); ?>" + nid,
				success: function() {
					<?php if (($_SESSION['role'] == 1) || ($_SESSION['role'] == 2)) { ?>
						window.location.href = "<?= base_url('admin/pengajuan/detail/'); ?>" + pid
					<?php } elseif ($_SESSION['role'] == 3) { ?>
						window.location.href = "<?= base_url('mahasiswa/pengajuan/tambah/'); ?>" + pid
					<?php } ?>
				}
			});
		});
	</script>
<?php
}


//periksa apakah jenis kategori ini memiliki child
function check_child($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM mstr_jenis_pengajuan where parent='$id'");
	return $query;
}

function get_meta_value($key, $id_pengajuan, $file)
{
	$CI = &get_instance();

	$value = $CI->db->select("*")
		->from('mstr_fields mf')
		->join('tr_field_value fv', 'mf.field_id=fv.field_id', 'left')
		->where(array("mf.key" => $key, 'fv.pengajuan_id' => $id_pengajuan))
		->get();

	if ($value->num_rows() > 0) {

		if ($file == true) {
			$media = $CI->db->select("*")->from('tr_media')->where(array('id' => $value->row_array()['value']))->get()->row_array();
			$filename = explode('/dokumen/', $media['file']);
			$value = array(
				'file_id' => $media['id'],
				'file' => $media['file'],
				'thumb' => $media['thumb'],
				'filename' => $filename[1],
			);
		} else {
			$value = $value->row_array()['value'];
		}
	} else {
		$value = "-";
	}

	return $value;
}

function get_meta_value_by_type_field($type, $id_pengajuan, $file)
{
	$CI = &get_instance();

	$value = $CI->db->select("*")
		->from('mstr_fields mf')
		->join('tr_field_value fv', 'mf.field_id=fv.field_id', 'left')
		->where(array("mf.type" => $type, 'fv.pengajuan_id' => $id_pengajuan))
		->get();

	if ($value->num_rows() > 0) {

		if ($file == true) {
			$media = $CI->db->select("*")->from('tr_media')->where(array('id' => $value->row_array()['value']))->get()->row_array();
			$filename = explode('/dokumen/', $media['file']);
			$value = array(
				'file_id' => $media['id'],
				'file' => $media['file'],
				'thumb' => $media['thumb'],
				'filename' => $filename[1],
			);
		} else {
			$value = $value->row_array()['value'];
		}
	} else {
		$value = "-";
	}

	return $value;
}
function get_file($id)
{
	$CI = &get_instance();
	return	$media = $CI->db->select("*")->from('tr_media')->where(array('id' => $id))->get()->row_array();
}

function getUsersbyRole($role, $prodi)
{

	$CI = &get_instance();

	if ($prodi) {
		return  $CI->db->select('*')->from('users')->where(array('role' => $role, 'prodi' => $prodi))->get()->result_array();
	} else {
		return  $CI->db->select('*')->from('users')->where(array('role' => $role))->get()->result_array();
	}
}


function konversiAngkaKeHuruf($angka)
{

	$huruf = array(
		1 =>   'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P'
	);

	return  $huruf[$angka];
}

function pengajuan_verified() {

	$CI = &get_instance();
	return $CI->db->select("*")
				->from("tr_pengajuan p")
				->join("tr_pengajuan_status ps", "ps.pengajuan_id=p.pengajuan_id")
				->where([
					"ps.status_id =" => 7
				])->get()
				->num_rows();
}

//ambil nominal reward dari table berdasarkan urutan/order
//order 0 adalah ketua
//order 1 adlah anggota
//khusus untuk jenis reward ke 2 (ketua kelompok dan anggota dapat nominal reward yg berbeda)
function get_nominal_byorder($id_pengajuan, $order) {

	$CI = &get_instance();

	$nominal = $CI->db->select('nominal')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
		"Jenis_Pengajuan_Id" => $id_pengajuan,
		"urutan" => $order
	])->get()->row_array();

	if( $nominal ) {
		return $nominal['nominal'];
	} else {
		return 0;
	}


}
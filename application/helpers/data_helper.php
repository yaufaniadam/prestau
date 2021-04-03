<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// print_r tool

function get_jumlah_pengajuan_perbulan($nama_bulan)
{
	$CI = &get_instance();

	return $CI->db->query(
		"SELECT * FROM Tr_Pengajuan_Status ps
		LEFT JOIN Tr_Pengajuan p ON p.pengajuan_id = ps.pengajuan_id
		LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
		WHERE ps.status_id = 2 
		-- AND m.DEPARTMENT_ID = '1'
		AND FORMAT (ps.date, 'MMMM') = '$nama_bulan'
		AND FORMAT (ps.date, 'yyyy') = YEAR(getdate())"
	)->num_rows();
}

function get_jumlah_pengajuan_per_jenis_pengajuan($jenis_pengajuan_id)
{
	$CI = &get_instance();

	return $CI->db->query(
		"SELECT * FROM Tr_Pengajuan p 
		LEFT JOIN V_Mahasiswa m ON m.STUDENTID = p.nim
		-- WHERE m.DEPARTMENT_ID = '1'
		WHERE p.Jenis_Pengajuan_Id = '$jenis_pengajuan_id'"
	)->num_rows();
}

function printrs($var)
{
	echo "<pre>";
	print_r($var);
	echo "</pre>";
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
		1 =>   'I',
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


// -----------------------------------------------------------------------------
function getUserbyId()
{
	$CI = &get_instance();
	return  $CI->db->select('*')->from('profil')->where(array('id_user' => $CI->session->userdata("user_id")))->get()->row_array();
}


function getUserPhoto($id)
{
	$CI = &get_instance();
	return $CI->db->get_where('profil', array('id_user' => $id))->row_array()['photo'];
}

function countSurat()
{
	$CI = &get_instance();
	// if ($CI->session->userdata('role') == 1) {
	// 	$prodi = '';
	// 	$in_status = "3,4,5,6,7,8";
	// } else {
	// 	$prodi = "AND p.id_prodi = '" . $CI->session->userdata('id_prodi') . "'";
	// 	if ($CI->session->userdata('role') == 2) { // TU
	// 		$in_status = "3,4,5,6,7";
	// 	} else if ($CI->session->userdata('role') == 5) {
	// 		$in_status = "3,4,5,6,7";
	// 	} else if ($CI->session->userdata('role') == 6) {
	// 		$in_status = "3,4,5,6,7";
	// 	}
	// }
	//WHERE ss.id_surat NOT IN (SELECT ss2.id_surat FROM surat_status ss2 WHERE ss2.id_status IN ($in_status)) AND ss.id_status!='1' $prodi

	// if ($CI->session->userdata('role') == 1) {
	// 	$status = "id_status = 9";
	// 	$prodi = '';
	// } else if ($CI->session->userdata('role') == 2) {
	// 	$status = "(id_status = 2 OR id_status = 5)";
	// 	$prodi = "AND u.id_prodi = '" . $CI->session->userdata('id_prodi') . "'";
	// }

	// $query = $CI->db->query("SELECT COUNT(*) as JUMLAH
	// 	FROM surat_status ss
	// 	LEFT JOIN surat s ON s.id = ss.id_surat
	// 	LEFT JOIN users u ON u.id = s.id_mahasiswa
	// 	WHERE $status $prodi		
	//     ");
	// $result = $query->row_array();

	// return $result['JUMLAH'];

	//return 1;
}
//menampilkan kategori keterangan surat
// function kat_keterangan_surat($id)
// {
// 	$CI = &get_instance();
// 	return $CI->db->get_where('kat_keterangan_surat', array('id' => $id))->row_array();
// }

function badge_status($status)
{
	$CI = &get_instance();
	$status  = $CI->db->get_where('status', array('id' => $status))->row_array();

	return '<span class="float-right badge-sm badge badge-' . $status['badge'] . '"> ' . $status['status'] . ' </span>';
}
function tgl_status_surat($id_surat, $status)
{
	$CI = &get_instance();
	return  $status  = $CI->db->select("DATE_FORMAT(date,'%d %M %Y') as date, DATE_FORMAT(date,'%H:%i') as time")->from('surat_status')->where(array('id_surat' => $id_surat, 'id_status' => $status))->get()->row_array();
}

function cek_verifikasi($id_surat)
{
	$CI = &get_instance();
	$verifikasi  = $CI->db->select("verifikasi")->from('keterangan_surat')->where(array('id_surat' => $id_surat))->get()->result_array();
	if (array_search("0", array_column($verifikasi, 'verifikasi')) !== false) {
		return true;
	}
}





function get_dokumen_syarat($id_surat)
{
	$CI = &get_instance();
	$dokumen = $CI->db->select("kat_keterangan_surat.id, kat_keterangan_surat.kat_keterangan_surat, keterangan_surat.value, media.file")
		->from('kat_keterangan_surat')
		->join('keterangan_surat', 'kat_keterangan_surat.id=keterangan_surat.id_kat_keterangan_surat', 'left')
		->join('media', 'media.id=keterangan_surat.value', 'left')
		->where(array('type' => "image", "id_surat" => $id_surat))
		->get()
		->result_array();

	return $dokumen;



	// $value = $CI->db->select("value")->from('keterangan_surat')->where(array('id_kat_keterangan_surat' => $id))->get()->row_array()['value'];

	// if ($image == true) {

	// 	$media = $CI->db->select("file")->from('media')->where(array('id' => $value))->get()->row_array()['file'];

	// 	return $media;
	// } else {
	// 	return $value;
	// }
}

// fungsi ini memeriksa apakah mhs udah pernah buat surat, jika sudah maka tidak diperkenankan membuat lagi sampai surat tersebut selesai
// cek juga jika mhs mengajukan surat yg berkaitan dgn durasi (contoh cuti kuliah), maka mhs tidak bs mengajukan cuti lagi sampai
// dalam posisi mahasiswa aktif

function cek_semester()
{
	$CI = &get_instance();
	//ambil tahun
	$cur_semester_angka = (date("n") <= 6) ?  2 : 1;
	$semester = date("Y") . $cur_semester_angka;
	$angkatan = substr($CI->session->userdata('username'), 0, 4);

	if ($semester % 2 != 0) {
		$a = (($semester + 10) - 1) / 10;
		$b = $a - $angkatan;
		$c = ($b * 2) - 1;
	} else {
		$a = (($semester + 10) - 2) / 10;
		$b = $a - $angkatan;
		$c = $b * 2;
	}

	return $c;
}

function cek_sudah_buat_surat($id_mahasiswa, $id_kategori_surat, $min_semester)
{
	//cek apakah ada kategori surat yg blm selesai
	$CI = &get_instance();

	$surat = $CI->db->query("SELECT s.*, ks.min_semester, ss.id_status FROM surat s
		LEFT JOIN kategori_surat ks ON ks.id=s.id_kategori_surat
		LEFT JOIN surat_status ss ON ss.id_surat=s.id
		WHERE id_mahasiswa = $id_mahasiswa AND id_kategori_surat = $id_kategori_surat ORDER BY id
		DESC LIMIT 1
		")->row_array();

	//jika ada surat yg belum selesai/ belum pernah mengajukan surat
	if ($surat) {

		$id_surat = $surat['id'];
		// jika sdh mengajukan, cek status surat, jika statusnya blm selesai (>10) maka belum boleh membuat surat yg sama

		$status = $CI->db->query("SELECT MAX(ss.id_status) as id_status, id_surat FROM surat_status ss
  	WHERE id_surat = $id_surat
		")->row_array();

		if (($status['id_status'] == 10) || ($status['id_status']  == 6)) {
			$diperbolehkan = 1;
		} else {
			$diperbolehkan = 2;
		}
	} else {
		//cek apakah option min_semester ada
		if ($min_semester > 0) {
			if (cek_semester() >= $min_semester) {;
				$diperbolehkan = 1;
			} else {
				$diperbolehkan = 3;
			}
		} else {
			$diperbolehkan = 1;
		}
	}
	return $diperbolehkan;
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

	// $notif = $CI->db->query("SELECT n.*, n.id as notif_id, sp.judul_notif, DATE_FORMAT(n.tanggal, '%H:%i') as time,  DATE_FORMAT(n.tanggal, '%d %M') as date_full, sp.badge, sp.icon, s.id_kategori_surat, ks.kategori_surat, u.fullname
	// FROM notif n 	
	// LEFT JOIN status_pesan sp ON sp.id = n.id_status_pesan
	// LEFT JOIN surat s ON s.id = n.id_surat
	// LEFT JOIN kategori_surat ks ON s.id_kategori_surat = ks.id
	// LEFT JOIN users u ON n.kepada = u.id
	// WHERE  $where AND n.status = 0 	
	// ORDER BY id DESC");

	// ,FORMAT (ps.date, 'hh:mm:ss ') as time
	$notif = $CI->db
		->select("*")
		->from('Tr_Notif n')
		->join('Mstr_Status_Pesan sp', 'sp.status_pesan_id = n.id_status_notif')
		->join('Tr_Pengajuan p', 'p.pengajuan_id=n.id_pengajuan')
		->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id=p.Jenis_Pengajuan_Id')
		->join('V_Mahasiswa m', 'm.STUDENTID=p.nim')
		->order_by('id_notif', 'DESC')
		->where($where)
		->where(['n.status' => 0])
		->get();

	// print_r($_SESSION);
	// echo '<pre>';
	// print_r($notif->result_array());
	// echo '</pre>';

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
							<span class="font-weight-bold"> <i class=""></i>
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
			<?php	}	?>

			<a class="dropdown-item text-center medium text-gray-500" href="#<?//= base_url('notif'); ?>">Lihat semua Notifikasi</a>
		</div>
	</li>
	<script type="text/javascript">
		$(".notif").click(function() {
			var nid = this.id
			var pid = this.name
			$.ajax({
				url: "<?= base_url('notif/read_notif/'); ?>" + nid,
				success: function() {
					<?php if ($_SESSION['role'] == 2) { ?>
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

function tampil_alert($status, $role)
{

	$CI = &get_instance();
	$alert = $CI->db->select('s.*,sp.*')->from('status s')
		->join('status_pesan sp', 's.id=sp.id_status', 'left')
		->where(array('s.id =' => $status, 'sp.role' => $role))->get()->row_array();
?>
	<p class="alert alert-<?= $alert['badge']; ?> mb-4"><i class="<?= $alert['icon']; ?>"></i> <?= $alert['alert']; ?></p>
<?php }

//periksa apakah jenis kategori ini memiliki child
function check_child($id)
{
	$CI = &get_instance();
	$query = $CI->db->query("SELECT * FROM dbo.Mstr_Jenis_Pengajuan where parent='$id'");
	return $query;
}

function get_meta_value($key, $id_pengajuan, $file)
{
	$CI = &get_instance();

	$value = $CI->db->select("*")
		->from('Mstr_Fields mf')
		->join('Tr_Field_Value fv', 'mf.field_id=fv.field_id', 'left')
		->where(array("mf.key" => $key, 'fv.pengajuan_id' => $id_pengajuan))
		->get()
		->row_array();

	if ($file == true) {
		$media = $CI->db->select("*")->from('Tr_Media')->where(array('id' => $value['value']))->get()->row_array();
		$filename = explode('/dokumen/', $media['file']);
		return array(
			'file_id' => $media['id'],
			'file' => $media['file'],
			'thumb' => $media['thumb'],
			'filename' => $filename[1],
		);
	} else {
		return $value['value'];
	}
}
function get_file($id)
{
	$CI = &get_instance();
	return	$media = $CI->db->select("*")->from('Tr_Media')->where(array('id' => $id))->get()->row_array();
}

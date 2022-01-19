<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Notif.php 
	Menampilkan seluruh notifikasi

	Catatan :
	- notif muncul pada tiap role yang terlibat
*/

class Notif extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('notif_model', 'notif_model');
	}

	public function index()
	{
		$data['notif'] = $this->notif_model->get_notif();
		$data['title'] = 'Semua Notifikasi';
		$data['view'] = 'notif/index';
		$this->load->view('layout/layout', $data);
	}

	public function detail($id_notif = null)
	{
		//check hak akses dulu
		$query = $this->db->query("SELECT 
		n.id as notif_id, 
		n.pengirim,
		n.kepada,
		n.id_prodi,
		n.id_surat,
		n.role,
		n.id_status_pesan,
		n.status,
		n.tanggal,
		sp.id_status,
		sp.icon,
		sp.alert,
		sp.judul_notif,
		sp.isi_notif,
		sp.badge,
		s.id_kategori_surat, 
		ks.kategori_surat, 
		u.fullname
		FROM notif n
		LEFT JOIN status_pesan sp ON sp.id =  n.id_status_pesan
		LEFT JOIN surat s ON s.id = n.id_surat
		LEFT JOIN kategori_surat ks ON s.id_kategori_surat = ks.id
		LEFT JOIN users u ON n.kepada = u.id
		WHERE n.id = $id_notif")->row_array();

		if ($query) {
			if ($_SESSION['role'] == 1) {
				$akses = ($query['role'] == 1)  ? 1 : 0;
			} else if ($_SESSION['role'] == 2) {
				$akses = ($query['role'] == 2)  ? 1 : 0;
			} else if ($_SESSION['role'] == 3) {
				$akses = ($query['kepada'] == $_SESSION['user_id'] && $query['role'] == 3) ? 1 : 0;
			} else if ($_SESSION['role'] == 4) {
				$akses = ($query['kepada'] == $_SESSION['user_id'] && $query['role'] == 4) ? 1 : 0;
			} else if ($_SESSION['role'] == 5) {
				$akses = ($query['role'] == 5)  ? 1 : 0;
			} else if ($_SESSION['role'] == 6) {
				$akses = ($query['role'] == 6)  ? 1 : 0;
			}

			if ($akses == 1) {
				//set status notif menjadi sudah dibaca
				if ($query['status'] == 0) {
					$this->db->set('status', 1);
					$this->db->set('dibaca', 'NOW()', FALSE);
					$this->db->where('id', $id_notif);
					$this->db->update('notif');
				}

				$data['notif'] = $query;
				$data['title'] = 'Detail Notifikasi';
				$data['view'] = 'notif/detail';
			} else {
				$data['title'] = 'Error';
				$data['view'] = 'error';
			}
		} else {
			$data['title'] = 'Error 404!';
			$data['view'] = 'error404';
		}
		$this->load->view('layout/layout', $data);
	}

	public function read_notif($id_notif)
	{
		date_default_timezone_set('Asia/Jakarta');
		$date = date("Y/m/d h:i:s");
		$data = [
			'status' => 1,
			'tanggal_dibaca'  => $date,
		];
		$this->db->where('id_notif', $id_notif);
		$this->db->update('Tr_Notif', $data);
	}
}

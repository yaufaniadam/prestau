<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Mahasiswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('formulir');
	}

	public function index()
	{
		$notif = $this->db
			->select(
				"*,
				DATENAME(month,n.tanggal_masuk) as bulan_masuk_surat,
				FORMAT (n.tanggal_masuk, 'dd') as tanggal_masuk_surat",
			)
			->from('tr_notif n')
			->join('mstr_status_pesan sp', 'sp.status_pesan_id = n.id_status_notif')
			->join('tr_pengajuan p', 'p.pengajuan_id=n.id_pengajuan')
			->join('mstr_jenis_pengajuan jp', 'jp.Jenis_Pengajuan_Id=p.Jenis_Pengajuan_Id')
			->join('v_mahasiswa m', 'm.STUDENTID=p.nim')
			->limit(10)
			->order_by('id_notif', 'DESC')
			->where(
				[
					// 'n.status' => null,
					// 'n.status' => 0,
					'n.role' => 3,
					'n.penerima' => $_SESSION['studentid']
				]
			)
			->get();

		$pengajuan_saya = $this->db
			->get_where(
				'tr_pengajuan',
				[
					'nim' => $_SESSION['studentid']
				]
			)->num_rows();

		// $is_field_anggota_exist = $this->db->get_where('mstr_pengajuan_field', ['field_id' => 77])->num_rows();

		$prestasi_saya = $this->db
			->select('*')
			->from('tr_penerbitan_pengajuan')
			->join('tr_periode_penerbitan', 'tr_periode_penerbitan.id_periode = tr_penerbitan_pengajuan.id_periode ', 'left' )
			->where(
				[
					'tr_penerbitan_pengajuan.STUDENTID' => $_SESSION['studentid'],
					'tr_periode_penerbitan.status' => '1'
				]
			)->get()
			->num_rows();

		$data['pengajuan_saya'] = $pengajuan_saya;
		$data['prestasi_saya'] = $prestasi_saya;
		$data['notif'] = $notif->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$this->load->view('layout/layout', $data);
	}
}

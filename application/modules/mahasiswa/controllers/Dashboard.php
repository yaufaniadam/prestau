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
			->from('Tr_Notif n')
			->join('Mstr_Status_Pesan sp', 'sp.status_pesan_id = n.id_status_notif')
			->join('Tr_Pengajuan p', 'p.pengajuan_id=n.id_pengajuan')
			->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id=p.Jenis_Pengajuan_Id')
			->join('V_Mahasiswa m', 'm.STUDENTID=p.nim')
			->limit(10)
			->order_by('tanggal_masuk', 'DESC')
			->where(
				[
					'n.status' => null,
					'n.status' => 0,
					'n.role' => 3
				]
			)
			->get();

		// echo "<pre>";
		// print_r($notif->result_array());
		// echo "</pre>";

		$data['notif'] = $notif->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$this->load->view('layout/layout', $data);
	}
}

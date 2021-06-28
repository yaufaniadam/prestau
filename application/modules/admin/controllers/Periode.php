<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Style\Alignment; 

class Periode extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model', 'periode_model');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		$this->load->model('notif/Notif_model', 'notif_model');
		$this->load->library('excel');
		$this->load->library('mailer');
	}

	public function index($status = '')
	{
		$data['daftar_periode'] = $this->periode_model->getPeriode($status);
		$data['title'] = 'Periode Penerbitan';
		$data['view'] = 'admin/periode/index';

		$this->load->view('layout/layout', $data);
	}

	public function tambah()
	{
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules(
				'nama_periode',
				'Nama Periode',
				'trim|required',
				[
					'required' => '%s Wajib Diisi'
				]
			);

			if ($this->form_validation->run() == FALSE) {
				$data['title'] = 'Tambah Periode';
				$data['view'] = 'admin/periode/tambah';
				$this->load->view('layout/layout', $data);
			} else {
				$data = [
					'nama_periode' => $this->input->post('nama_periode'),
					'tanggal' => '',
					'status' => '0'
				];
				if ($this->periode_model->tambahPeriode($data)) {
					redirect(base_url('admin/periode/index/0'));
				}
			}
		} else {
			$data['title'] = 'Tambah Periode';
			$data['view'] = 'admin/periode/tambah';
			$this->load->view('layout/layout', $data);
		}
	}

	public function tambah_nominal($id_periode)
	{
		echo "tambah nomina";
	}

	public function bulan($id_periode = 0)
	{
		if ($this->input->post('submit')) {

			date_default_timezone_set('Asia/Jakarta');
			$tanggal = date("Y/m/d h:i:s");
			$id_periode = $this->input->post('id_periode');
			$data = [
				'tanggal' => $tanggal,
				'status' => 1
			];

			$this->db->where('id_periode', $id_periode);
			$this->db->update('Tr_Periode_Penerbitan', $data);

			$pengajuan = $this->input->post('pengajuan[]');

			$penerima = $this->input->post()['pengajuan_id'];
			$pengajuan = $this->input->post('pengajuan[]');

			for ($i = 0; $i < count($penerima); $i++) {
				$this_pengajuan = $this->db->get_where(
					'Tr_Penerbitan_Pengajuan',
					[
						'STUDENTID' => $penerima[$i],
						'id_periode' => $id_periode,
						'id_pengajuan' => $pengajuan[$i]
					]
				)->row_array();

				// $data_for_notif = [
				// 	'pengirim' => $_SESSION['user_id'],
				// 	'penerima' => $penerima[$i],
				// 	'id_pengajuan' => $this_pengajuan['id_pengajuan'],
				// 	'role' => [3],
				// 	'id_status_notif' => 10,
				// ];

				// $this->notif_model->send_notif($data_for_notif);

					//data utk kirim email & notif ke pegawai
					$data_for_notif = [
						'STUDENTID' => $penerima[$i],
						'STUDENTNAME' => $penerima[$i],
						'penerima' => '',
						'id_pengajuan' => $pengajuan[$i],
						'judul_pengajuan' => $data['title'],
						'role' => [3],
						'link' => base_url('admin/pengajuan/detail/'. $pengajuan[$i]),
						'subjek' => 'Ada Pengajuan Prestasi Baru dari ' . $pengajuan[$i],
						'isi' => 'Ada Pengajuan Prestasi Baru dari <strong>' . $pengajuan[$i] . '</strong> kategori <strong>' . $data['title'] . '</strong> yang perlu diperiksa.',
						'id_status_notif' => 10,
					];
	
					//sendmail & notif
					$this->mailer->send_mail($data_for_notif);			

			}
			// die();

			$this->db->select('id_pengajuan');
			$this->db->distinct();
			$this->db->from('Tr_Penerbitan_Pengajuan');
			$this->db->where(['id_periode' => $id_periode]);
			$this->db->group_by('id_pengajuan');
			$pengajuan = $this->db->get()->result_array();

			foreach ($pengajuan as $pengajuan) {
				$this->db->set('status_id', 10)
					->set('pic', $this->session->userdata('user_id'))
					->set('date', 'getdate()', FALSE)
					->set('pengajuan_id', $pengajuan['id_pengajuan'])
					->insert('Tr_Pengajuan_Status');
			}

			redirect(base_url('/admin/periode/bulan/' . $id_periode));
		} else {
			// die();
			$nama_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
			$status_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->status;
			$data['daftar_pengajuan'] = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);
			$data['title'] = 'Daftar Pengajuan Periode ' . $nama_periode;
			$data['status_periode'] = $status_periode;
			$data['id_periode'] = $id_periode;
			$data['view'] = 'admin/penerbitan_pengajuan/index';

			$this->load->view('layout/layout', $data);
		}
	}

	public function export_excel($id_periode = 0) {

		$nama_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->nama_periode;
		$status_periode = $this->db->get_where('Tr_Periode_Penerbitan', ['id_periode' => $id_periode])->row_object()->status;

		$daftar_pengajuan = $this->pengajuan_model->getPengajuanPerPeriode($id_periode);

		// echo '<pre>'; print_r($daftar_pengajuan); echo '</pre>';

		// echo '<pre>'; print_r($daftar_pengajuan); echo '</pre>';
		// ambil style untuk table dari library Excel.php
		$style_header = $this->excel->style('style_header');
		$style_td = $this->excel->style('style_td');
		$style_td_left = $this->excel->style('style_td_left');
		$style_td_right = $this->excel->style('style_td_right');
		$style_td_bold = $this->excel->style('style_td_bold');

		// create file name

		$fileName = "Mhs Berprestasi Periode - " .$nama_periode . '.xlsx';

		$sebaran = $daftar_pengajuan;
		$maxcolumn = konversiAngkaKeHuruf(count($sebaran) + 1);
		$excel = new Spreadsheet;

		// Settingan awal file excel
		$excel->getProperties()->setCreator('LPKA UMY')
			->setLastModifiedBy('LPKA UMY')
			->setTitle("Mahasiswa Berprestasi UMY Periode " . $nama_periode )
			->setSubject("Mahasiswa Berprestasi UMY Periode " . $nama_periode )
			->setDescription("Mahasiswa Berprestasi UMY Periode " . $nama_periode)
			->setKeywords("Mahasiswa Berprestasi UMY");

		//judul baris ke 1
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Mahasiswa Berprestasi UMY"); // 
		$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai F1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		//judul baris ke 2
		$excel->setActiveSheetIndex(0)->setCellValue('A2', $nama_periode); // 
		$excel->getActiveSheet()->mergeCells('A2:H2'); // Set Merge Cell pada kolom A1 sampai F1
		$excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		$excel->getActiveSheet()->SetCellValue('A4', 'No');
		$excel->getActiveSheet()->SetCellValue('B4', 'NIM');
		$excel->getActiveSheet()->SetCellValue('C4', 'Nama');
		$excel->getActiveSheet()->SetCellValue('D4', 'Prodi');
		$excel->getActiveSheet()->SetCellValue('E4', 'Jenis Pengajuan');
		$excel->getActiveSheet()->SetCellValue('F4', 'Judul Kegiatan/Keterangan/Nama');
		$excel->getActiveSheet()->SetCellValue('G4', 'Nominal (Rp)');
		$excel->getActiveSheet()->SetCellValue('H4', 'Tanggal Pencairan');

		$no = 1;
		$rowCount = 5;
		$last_row = count($sebaran) + 4;
		foreach ($sebaran as $element) {
			$excel->getActiveSheet()->SetCellValue('A' . $rowCount, $no);
			$excel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['STUDENTID']);
			$excel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['FULLNAME']);
			$excel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['NAME_OF_DEPARTMENT']);
			$excel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['Jenis_Pengajuan']);
			$excel->getActiveSheet()->SetCellValue('F' . $rowCount, get_meta_value('judul', $element['pengajuan_id'], false));
			$excel->getActiveSheet()->SetCellValue('G' . $rowCount, number_format($element['nominal']));
			$excel->getActiveSheet()->SetCellValue('H' . $rowCount, $element['tanggal_pencairan']);

			//stile column No
			// $excel->getActiveSheet()->getStyle('A'.$rowCount)->applyFromArray($style_td);

			//header style lainnya
			for ($i = 'A'; $i <=  $excel->getActiveSheet()->getHighestColumn(); $i++) {
				$excel->getActiveSheet()->getStyle($i . $rowCount)->applyFromArray($style_td);
			}

			// rata kanan
			$excel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($style_td_right);
			$excel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($style_td_right);

			$rowCount++;
			$no++;
		}
		//header style
		for ($i = 'A'; $i <=  $excel->getActiveSheet()->getHighestColumn(); $i++) {
			$excel->getActiveSheet()->getStyle($i . '4')->applyFromArray($style_header);
		}
		// // last row style    		
		// for ($i = 'A'; $i <=  $excel->getActiveSheet()->getHighestColumn(); $i++) {
		// 	$excel->getActiveSheet()->getStyle($i . $last_row)->applyFromArray($style_td_bold);
		// }
		//auto column width
		for ($i = 'A'; $i <=  $excel->getActiveSheet()->getHighestColumn(); $i++) {
			$excel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
		}

		// $excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE);


		//auto column width
		for ($i = 'A'; $i <=  $excel->getActiveSheet()->getHighestColumn(); $i++) {
			$excel->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
		}

		$objWriter = IOFactory::createWriter($excel, "Xlsx");
		$objWriter->save('./uploads/excel/' . $fileName);
		// download file
		header("Content-Type: application/vnd.ms-excel");
		redirect('./uploads/excel/' . $fileName);
	
	}


	public function reward($id_prestasi)
	{
		$prestasi = $this->db->get_where('Tr_Penerbitan_Pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])
			->row_object();

		$field_anggota = $this->db->get_where('Tr_Field_Value', [
			'pengajuan_id' => $prestasi->id_pengajuan,
			'field_id' => 77
		]);

		$queryp = $this->db->select('*')
			->from('Tr_Pengajuan p')
			->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id', 'left')
			->where([
				'p.pengajuan_id' => $prestasi->id_pengajuan
			])
			->get()
			->row_object();
		$tipe_reward = $queryp->fixed;

		if (($tipe_reward == 1) || ($tipe_reward == 3)) {
			$reward = $this->db->get_where('Mstr_Penghargaan_Rekognisi_Mahasiswa', [
				'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id
			])->row_object()->nominal;
		} elseif ($tipe_reward == 2) {
			if ($field_anggota->num_rows() > 0) {
				$anggota = explode(',', $field_anggota->row_object()->value);
				$urutan = array_search($prestasi->STUDENTID, $anggota);
				$reward = $this->db->get_where('Mstr_Penghargaan_Rekognisi_Mahasiswa', [
					'Jenis_Pengajuan_Id' => $queryp->Jenis_Pengajuan_Id,
					'order' => $urutan > 0 ? 2 : 1
				])->row_object()->nominal;
			}
		} else {
			$reward = get_meta_value('biaya_pribadi', $prestasi->id_pengajuan, false);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($reward));

		// $query = $this->db->select('*')
		// 	->from('Tr_Penerbitan_Pengajuan pp')
		// 	->join('Tr_Pengajuan p', 'p.pengajuan_id = pp.id_pengajuan')
		// 	->join('Mstr_Jenis_Pengajuan jp', 'jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id')
		// 	->join('Mstr_Penghargaan_Rekognisi_Mahasiswa prm', 'prm.Jenis_Pengajuan_Id = jp.Jenis_Pengajuan_Id')
		// 	->where([
		// 		'pp.id_penerbitan_pengajuan' => $id_prestasi
		// 	])
		// 	->get()
		// 	->row_object();
	}

	public function set_nominal()
	{
		$id_prestasi = $this->input->post('id_prestasi');
		$id_periode = $this->db->get_where('Tr_Penerbitan_Pengajuan', ['id_penerbitan_pengajuan' => $id_prestasi])->row_object()->id_periode;

		$this->db->set('nominal', $this->input->post('nominal'));
		$this->db->where('id_penerbitan_pengajuan', $id_prestasi);
		$this->db->update('Tr_Penerbitan_Pengajuan');
		redirect(base_url('admin/periode/bulan/' . $id_periode));
	}

	public function hapus()
	{
		if ($this->input->post('command') == "DELETE") {
			$id_penerbitan_pengajuan = $this->input->post('id_penerbitan_pengajuan');
			$id_pengajuan = $this->input->post("id_pengajuan");
			$id_periode = $this->input->post("id_periode");

			$this->db->delete('Tr_Penerbitan_Pengajuan', array('id_penerbitan_pengajuan' => $id_penerbitan_pengajuan));
			$this->session->set_flashdata('msg', 'Data berhasil dihapus!');

			$this->db->delete('Tr_Pengajuan_Status', array('pengajuan_id' => $id_pengajuan, 'status_id' => 9));
			redirect(base_url('admin/periode/bulan/' . $id_periode));
		}
	}

	public function pencairan_reward()
	{
		date_default_timezone_set('Asia/Jakarta');

		$id_penerbitan_pengajuan = $this->input->post("id_penerbitan_pengajuan");
		$pegawai = $this->input->post("pegawai");
		$penerima = $this->input->post("penerima");

		$this->db->set([
			'pegawai' => $pegawai,
			'penerima' => $penerima,
			'status_pencairan' => 1,
			'tanggal_pencairan' => date("Y/m/d")
		]);
		$this->db->where('id_penerbitan_pengajuan', $id_penerbitan_pengajuan);
		$this->db->update('Tr_Penerbitan_Pengajuan');
		redirect(base_url('admin/periode/bulan/' . $this->input->post('id_periode')));
	}
}

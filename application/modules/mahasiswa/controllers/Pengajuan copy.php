<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pengajuan extends Mahasiswa_Controller
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->library('mailer');
		$this->load->model('pengajuan_model', 'pengajuan_model');
		// $this->load->model('notif/Notif_model', 'notif_model');
		$this->load->helper('formulir');
	}


	public function detail($id_surat = 0)
	{
		// $data['surat'] = $this->pengajuan_model->get_detail_surat($id_surat);
		// $data['title'] = $data['surat']['id_mahasiswa'];
		// $data['view'] = 'surat/detail';
		// $this->load->view('layout/layout', $data);
	}

	public function index($id_jenis_pengajuan = 0)
	{
		$data['jenis_pengajuan'] = $this->pengajuan_model->get_jenis_pengajuan($id_jenis_pengajuan);
		$data['title'] = 'Ajukan Prestasi';
		$data['view'] = 'pengajuan/index';
		$this->load->view('layout/layout', $data);
	}

	public function baru($id)
	{
		$data = array(
			'Jenis_Pengajuan_Id' => $id,
			'nim' => $this->session->userdata('studentid'),
		);

		$field = $this->db->select('*')->from('Tr_Pengajuan_Field')
			->join('Mstr_Fields', 'Mstr_fields.field_id=Tr_Pengajuan_Field.field_id', 'left')
			->where(array('Jenis_Pengajuan_Id' => $id))->get()
			->result_array();

		$data = $this->security->xss_clean($data);
		$result = $this->pengajuan_model->tambah($data);
		//ambil last id surat yg baru diinsert
		$insert_id = $this->db->insert_id();
		// set status surat
		$data_user = $this->session->userdata('user_id');
		$this->db->set('pengajuan_id', $insert_id)
			->set('status_id', 1)
			->set('pic', $data_user['STUDENTID'])
			->set('date', date('Y-m-d h:m:s'))
			->insert('Tr_Pengajuan_Status');

		// //ambil id surat berdasarkan last id status surat
		$inserted_id = $this->db->select('pengajuan_id')->from('Tr_Pengajuan_Status')->where('status_pengajuan_id=', $this->db->insert_id())->get()->row_array();
		// // ambil keterangan surat berdasar kategori surat
		// $kat_surat = $this->db->select('kat_keterangan_surat')->from('kategori_surat')->where('id=', $id)->get()->row_array();
		$field_id = $this->db->query(
			"SELECT Tr_Pengajuan_Field.field_id  FROM Mstr_Jenis_Pengajuan
			LEFT JOIN Tr_Pengajuan_Field ON Tr_Pengajuan_Field.Jenis_Pengajuan_Id = Mstr_Jenis_Pengajuan.Jenis_Pengajuan_Id
			WHERE Mstr_Jenis_Pengajuan.Jenis_Pengajuan_Id = $id AND Tr_Pengajuan_Field.terpakai = 1"
		)->result_array();

		// // explode kterangan surat

		// // foreach keterangan surat, lalu masukkan nilai awal (nilai kosong) berdasakan keterangan dari kategori surat kedalam field_value
		foreach ($field_id as $key => $id_kat) {
			$this->db->insert(
				'db_owner.Tr_Field_Value',
				array(
					'value' => '',
					'pengajuan_id' =>  $inserted_id['pengajuan_id'],
					'field_id' => $id_kat['field_id'],
				)
			);
		}

		// foreach ($field_id as $ad) {
		// 	print_r($ad['field_id']);
		// }

		// $data_notif = array(
		// 	'id_surat' => $insert_id2['id_surat'],
		// 	'id_status' => 1,
		// 	'kepada' => $_SESSION['user_id'],
		// 	'role' => array(3)
		// );

		// $results = $this->notif_model->send_notif($data_notif);

		// if ($results) {
		// 	$this->session->set_flashdata('msg', 'Berhasil!');
		redirect(base_url('mahasiswa/pengajuan/tambah/' . $insert_id));
		// }
	}

	public function tambah($pengajuan_id = 0)
	{
		$id_notif = $this->input->post('id_notif');

		// $data['kategori_surat'] = $this->pengajuan_model->get_kategori_surat('m');
		// $data['keterangan_surat'] = $this->pengajuan_model->get_keterangan_surat($pengajuan_id);
		// $data['timeline'] = $this->pengajuan_model->get_timeline($pengajuan_id);
		$this->load->helper('formulir');
		$pengajuan_id;

		$data['pengajuan'] = $this->pengajuan_model->get_detail_pengajuan($pengajuan_id);
		$data['pengajuan_fields'] = $this->pengajuan_model->get_spesific_pengajuan_fields($pengajuan_id);
		$data['title'] = 'Ajukan Surat';
		$data['view'] = 'surat/tambah';

		if ($this->input->post('submit')) {
			// validasi form, form ini digenerate secara otomatis
			foreach ($this->input->post('dokumen') as $id => $dokumen) {
				$this->form_validation->set_rules(
					'dokumen[' . $id . ']',
					field($id)['key'],
					'trim|required',
					array('required' => '%s wajib diisi.')
				);
			}

			if ($this->form_validation->run() == FALSE) {
				// $data['title'] = 'Ajukan Surat';
				// $data['view'] = 'surat/tambah';
				$this->load->view('layout/layout', $data);
			} else {
				//cek dulu apakah ini surat baru atau surat revisi
				if ($this->input->post('revisi')) {
					$id_status = 5;
				} else {
					$id_status = 2;
				}

				//tambah status ke tb surat_status
				$data_user = $this->session->userdata('user_id');
				$insert = $this->db->set('id_pengajuan', $pengajuan_id)
					->set('id_status', $id_status)
					->set('pic', $data_user['STUDENTID'])
					->set('date', date('Y-m-d h:m:s'))
					->insert('Tr_Pengajuan_Status');

				//insert field ke tabel keterangan_surat
				if ($insert) {
					foreach ($this->input->post('dokumen') as $id => $dokumen) {
						$this->db->where(array('field_id' => $id, 'pengajuan_id' => $pengajuan_id));
						$this->db->update(
							'db_owner.Tr_Field_Value',
							array(
								'value' => $dokumen
							)
						);
					}

					// kirim notifikasi
					$data_notif = array(
						'id_surat' => $pengajuan_id,
						'id_status' => 2,
						'kepada' => $_SESSION['user_id'],
						'role' => array(2, 3)
					);

					// $this->notif_model->send_notif($data_notif);

					// hapus notifikasi "Lengkapi dokumen"
					// $set_status = $this->db->set('status', 1)
					// 	->set('dibaca',  date('Y-m-d h:m:s'))
					// 	->where(array('id' => $id_notif, 'status' => 0))
					// 	->update('notif');

					//mailer di sini
					// if ($set_status) {
					redirect(base_url('mahasiswa/surat/tambah/' . $pengajuan_id));
					// }
				}
			}
		} else {
			// if ($data['surat']['id_mahasiswa'] == $this->session->userdata('user_id')) {
			// 	$data['title'] = 'Ajukan Surat';
			// 	$data['view'] = 'surat/tambah';
			// } else {
			// 	$data['title'] = 'Forbidden';
			// 	$data['view'] = 'restricted';
			// }

		$data['view'] = 'surat/tambah';
		$this->load->view('layout/layout', $data);
		// }
	}
	// public function edit()
	// {
	//   $data['query'] = $this->pengajuan_model->get_surat();
	//   $data['title'] = 'Ajukan Surat';
	//   $data['view'] = 'surat/tambah';
	//   $this->load->view('layout/layout', $data);
	// }
	// public function hapus($id_surat = 0)
	// {
	//   $surat_exist = $this->pengajuan_model->get_detail_surat($id_surat);
	//   if ($surat_exist['id_status'] == 4) {
	//     $this->db->delete('surat', array('id' => $id_surat));
	//     $this->session->set_flashdata('msg', 'Surat berhasil dihapus');
	//     redirect(base_url('mahasiswa/surat'));
	//   } else {
	//     $this->session->set_flashdata('msg', 'Surat Gagal dihapus');
	//     redirect(base_url('mahasiswa/surat'));
	//   }
	// }

	// public function doupload()
	// {
	//   header('Content-type:application/json;charset=utf-8');
	//   $upload_path = 'uploads/dokumen';

	//   if (!is_dir($upload_path)) {
	//     mkdir($upload_path, 0777, TRUE);
	//   }

	//   $config = array(
	//     'upload_path' => $upload_path,
	//     'allowed_types' => "jpg|png",
	//     'overwrite' => FALSE,
	//   );

	//   $this->load->library('upload', $config);

	//   if (!$this->upload->do_upload('file')) {
	//     $error = array('error' => $this->upload->display_errors());

	//     echo json_encode([
	//       'status' => 'error',
	//       'message' => $error
	//     ]);
	//   } else {
	//     $data = $this->upload->data();

	//     $this->_create_thumbs($data['file_name']);

	//     $result = $this->db->insert(
	//       'media',
	//       array(
	//         'id_user' => $this->session->userdata('user_id'),
	//         'file' =>  $upload_path . '/' . $data['file_name'],
	//         'thumb' =>  $upload_path . '/' . $data['raw_name'] . '_thumb' . $data['file_ext']
	//       )
	//     );

	//     echo json_encode([
	//       'status' => 'Ok',
	//       'id' => $this->db->insert_id(),
	//       // 'path' => $upload_path . '/' . $data['file_name']
	//       'thumb' => $upload_path . '/' . $data['raw_name'] . '_thumb' . $data['file_ext'],
	//       'orig' => $upload_path . '/' . $data['file_name']
	//     ]);
	//   }
	// }

	// public function tampil_surat($id_surat)
	// {
	//   $data['title'] = 'Tampil Surat';
	//   $data['surat'] = $this->pengajuan_model->get_detail_surat($id_surat);
	//   $data['no_surat'] = $this->pengajuan_model->get_no_surat($id_surat);
	//   $kategori = $data['surat']['kategori_surat'];
	//   $nim = $data['surat']['username'];

	//   //$this->load->view('admin/surat/tampil_surat', $data);

	//   $mpdf = new \Mpdf\Mpdf([
	//     'tempDir' => __DIR__ . '/pdfdata',
	//     'mode' => 'utf-8',
	//     // 'format' => [24, 24],
	//     'format' => 'A4',
	//     'margin_left' => 0,
	//     'margin_right' => 0,
	//     'margin_bottom' => 20,
	//     'margin_top' => 30,
	//     'float' => 'left'
	//   ]);

	//   $view = $this->load->view('admin/surat/tampil_surat', $data, TRUE);

	//   $mpdf->SetHTMLHeader('
	// 	<div style="text-align: left; margin-left:2cm">
	// 			<img width="390" height="" src="' . base_url() . '/public/dist/img/logokop-pasca.jpg" />
	// 	</div>');
	//   $mpdf->SetHTMLFooter('

	// 	<div style="text-align:center; background:red;">
	// 		<img width="" height="" src="' . base_url() . '/public/dist/img/footerkop-pasca.jpg" />
	// 	</div>');

	//   $mpdf->WriteHTML($view);

	//   $mpdf->Output('Surat-' . $kategori . '-' . $nim . '.pdf', 'D');
	// }

	// function _create_thumbs($upload_data)
	// {
	//   // Image resizing config
	//   $upload_data = $this->upload->data();
	//   $image_config["image_library"] = "gd2";
	//   $image_config["source_image"] = $upload_data["full_path"];
	//   $image_config['create_thumb'] = true;
	//   $image_config['maintain_ratio'] = TRUE;
	//   $image_config['thumb_marker'] = "_thumb";
	//   $image_config['new_image'] = $upload_data["file_path"];
	//   $image_config['quality'] = "100%";
	//   $image_config['width'] = 320;
	//   $image_config['height'] = 240;
	//   $dim = (intval($upload_data["image_width"]) / intval($upload_data["image_height"])) - ($image_config['width'] / $image_config['height']);
	//   $image_config['master_dim'] = ($dim > 0) ? "height" : "width";

	//   $this->load->library('image_lib');
	//   $this->image_lib->initialize($image_config);

	//   if (!$this->image_lib->resize()) { //Resize image
	//     redirect("errorhandler"); //If error, redirect to an error page
	//   }
	// }
}

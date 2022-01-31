<?php
public function edit_nominal($id)
	{
		$tipe_reward = $this->input->post('tipe_reward');

		$this->form_validation->set_rules(
			'tipe_reward',
			'Tipe Reward',
			'trim|required',
			array('required' => '%s wajib diisi')
		);

		if ($tipe_reward != '') {

			if ($tipe_reward != 4) {

				$this->form_validation->set_rules(
					'nominal1',
					'Nominal 1',
					'trim|required',
					array('required' => '%s wajib diisi')
				);

				if ($tipe_reward == 2) {
					$this->form_validation->set_rules(
						'nominal2',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
				}
				if ($tipe_reward == 5) {
					$this->form_validation->set_rules(
						'nominal2',
						'Nominal 2',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
					$this->form_validation->set_rules(
						'nominal3',
						'Nominal 3',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
					$this->form_validation->set_rules(
						'nominal4',
						'Nominal 4',
						'trim|required',
						array('required' => '%s wajib diisi')
					);
				}
			}
		}

		if ($this->form_validation->run() == FALSE) {

			$error = [
				'nominal1' => form_error('nominal1'),
				'nominal2' => form_error('nominal2'),
				'nominal3' => form_error('nominal3'),
				'nominal4' => form_error('nominal4'),
				'tipe_reward' => form_error('tipe_reward')
			];
			
			echo json_encode(array("status" => "Error", "error" => $error));

		} else {
			//ubah jenis nominal (column:fixed) pada tabel mstr_jenis_pengajuan
			$this->db->update('mstr_jenis_pengajuan', ["fixed" => $tipe_reward], array('Jenis_Pengajuan_Id' => $id));

			$new_nominal = array(
				"0" => $this->input->post('nominal1'),
				"1" => $this->input->post('nominal2'),
				"2" => $this->input->post('nominal3'),
				"3" => $this->input->post('nominal4')
			);

			// echo "<pre>";
			// print_r($new_nominal);
			// echo "</pre>";

			if ($tipe_reward == 2) {

					//cek order awal apkah ada yg nilainya 1
					$nominal_exist = $this->db->select('nominal')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
						"Jenis_Pengajuan_Id" => $id,
						"urutan" => 1
					])->get()->result_array();

					if ($nominal_exist) {

										
						foreach ($new_nominal as $key => $value) {
							$this->db->where([
								"Jenis_Pengajuan_Id" => $id,
								"urutan" => $key
							]);
							$data_nominal = [
								"nominal" => $value
							];

							$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
						}

					} else {

					
						$insdata_penghargaan = [
							"urutan" => 1,
							"Jenis_Pengajuan_Id" => $id,
						];

						// $this->db->insert('mstr_penghargaan_rekognisi_mahasiswa', $insdata_penghargaan);
						foreach ($new_nominal as $key => $value) {
							$this->db->where([
								"Jenis_Pengajuan_Id" => $id,
								"urutan" => $key
							]);
							$data_nominal = [
								"nominal" => $value
							];

							$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
						}
					}
				
			} elseif ($tipe_reward == 5) {

				echo "tiper reward 5";

					//cek order awal apkah ada yg nilainya 1
					$nominal_exist = $this->db->select('nominal')->from('mstr_penghargaan_rekognisi_mahasiswa')->where([
						"Jenis_Pengajuan_Id" => $id,
						"urutan" => 3
					])->get()->result_array();

					if ($nominal_exist) {

						$this->db->delete('mstr_penghargaan_rekognisi_mahasiswa', [
							"Jenis_Pengajuan_Id" => $id,]);

						echo " sudah ada nilai sebelumnya";
						//jika sudah ada nominal sebelumnya, hapus dulu lalu isi dengan yg baru


										
						// foreach ($new_nominal as $key => $value) {
						// 	$this->db->where([
						// 		"Jenis_Pengajuan_Id" => $id,
						// 		"urutan" => $key
						// 	]);
						// 	$data_nominal = [
						// 		"nominal" => $value
						// 	];

						// 	$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
						// }

					} else {

						echo "nilai blm ada";
					
						// $insdata_penghargaan = [
						// 	"urutan" => 1,
						// 	"Jenis_Pengajuan_Id" => $id,
						// ];

						$this->db->insert('mstr_penghargaan_rekognisi_mahasiswa', $insdata_penghargaan);

						foreach ($new_nominal as $key => $value) {
							$this->db->where([
								"Jenis_Pengajuan_Id" => $id,
								"urutan" => $key
							]);
							$data_nominal = [
								"nominal" => $value
							];

							$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_nominal);
						}
					}
				
			} else {

				$new_nominal = $this->input->post('nominal1');

				$data_penghargaan = [
					"nominal" => $new_nominal,
					"urutan" => 0,
				];
			}

			if ($tipe_reward == 1 || $tipe_reward == 3) {

				

				$this->db->where(array(
					'Jenis_Pengajuan_Id' => $id, 
					'order'=>'0'
				));

				$this->db->update('mstr_penghargaan_rekognisi_mahasiswa', $data_penghargaan);
				

			}

			echo json_encode(array("status" => "sukses"));
		}
	}
<?php defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengajuan_model');
	}

	public function index()
	{
		$data['pengajuan_perlu_diproses'] = $this->pengajuan_model->pengajuan_perlu_diproses();
		$data['pengajuan_selesai'] = $this->pengajuan_model->pengajuan_selesai();
		$data['nama_bulan'] = $this->pengajuan_model->getbulan();
		$data['jenis_pengajuan'] = $this->db->query(
			"SELECT 
			DISTINCT(jp.Jenis_Pengajuan),
			jp.Jenis_Pengajuan_Id
			FROM Tr_Pengajuan p 
			LEFT JOIN Mstr_Jenis_Pengajuan jp ON jp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id"
		)->result_array();
		$data['title'] = 'Dashboard';
		$data['view'] = 'dashboard/index';
		$this->load->view('layout/layout', $data);
	}

	public function kirim_email() {

		$mail = new PHPMailer(true); //Argument true in constructor enables exceptions

    $mail->From = 'noreply.simkatmawa@umy.ac.id';  
    $mail->FromName = "LPKA UMY - SIMKATMAWA";
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'pod51003.outlook.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'noreply.simkatmawa@umy.ac.id';                     // SMTP username
    $mail->Password   = 'UMYbsi21';
    // $mail->Password   = decrypt_url($this->get_settings('password_email'));
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    $mail->setFrom('noreply.simkatmawa@umy.ac.id', 'LPKA UMY - SIMKATMAWA');
    $mail->isHTML(true);

    $mail->addAddress('yaufani@gmail.com');

    $mail->Subject = "subject";
    $mail->Body = "asdasdasd";

    $mail->send();

    $mail->ClearAddresses();
	}
}

<?php

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
  private $_CI;
  public function __construct()
  {
    $this->_CI = &get_instance();
    $this->_CI->load->model('notif/notif_model', 'notif_model');
  }
  public function send_mail($data)
  {

    $this->_CI->notif_model->send_notif($data);

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

    $role = $data['role'];

    foreach ($role as $role) {

      $mail->Subject = $data['subjek'];

      if ($role == 2) {
        $users = getUsersbyRole($role, '');

        $studentid = $data['STUDENTID'];
        //ambil email mahasiswa berdasarkan nim

        // $email = '';
        foreach ($users as $user) {

          $isi_email = array(
            'penerima' => $user['fullname'],
            'link' => $data['link'],
            'isi' => $data['isi'],
          );

          $mail->Body = $this->email_template($isi_email);
          $mail->addAddress($user['email']);
          $mail->send();
          $mail->ClearAddresses();
        }
      } elseif ($role == 3) {

        $mail->Subject = $data['subjek'];

        $sp = $this->_CI->notif_model->get_messages($data['id_status_notif'], $role);
        // $subject = $sp['judul_notif'];

        $mail->addAddress('yaufani@gmail.com');

        $mail->Subject = "Pengajuan Baru";

        $isi_email = array(
          'penerima' => $data['STUDENTNAME'],
          'link' => $data['link'],
          'isi' => $data['isi'],
        );
        $mail->Body = $this->email_template($isi_email);

        $mail->send();

        $mail->ClearAddresses();
      } else {
        echo "admin";
      }
    }
  }


  public function get_settings($nama_setting)
  {

    $CI = &get_instance();
    return $settings = $CI->db->select('value_setting')->from('settings')->where(['nama_setting' => $nama_setting])->get()->row_array()['value_setting'];
  }

  private function email_template($data)
  {

    $message = '
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

    <head>
      <!--[if gte mso 9]>
      <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="x-apple-disable-message-reformatting">
      <!--[if !mso]><!-->
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!--<![endif]-->
      <title></title>

      <style type="text/css">
      a{color:#00e;text-decoration:underline}@media only screen and (min-width:620px){.u-row{width:600px!important}.u-row .u-col{vertical-align:top}.u-row .u-col-100{width:600px!important}}@media (max-width:620px){.u-row-container{max-width:100%!important;padding-left:0!important;padding-right:0!important}.u-row .u-col{min-width:320px!important;max-width:100%!important;display:block!important}.u-row{width:calc(100% - 40px)!important}.u-col{width:100%!important}.u-col>div{margin:0 auto}}body{margin:0;padding:0}table,td,tr{vertical-align:top;border-collapse:collapse}p{margin:0}.ie-container table,.mso-container table{table-layout:fixed}*{line-height:inherit}a[x-apple-data-detectors=\'true\']{color:inherit!important;text-decoration:none!important}table.datamhs{border:1px solid #ddd;width:100%;font-size:15px;margin-top:30px}table.datamhs tr td{padding:3px}table.datamhs tr:nth-child(odd) td{background:#eee}
      </style>

    </head>

    <body class="clean-body" style="margin: 0;padding: 0;padding-top:50px;-webkit-text-size-adjust: 100%;background-color: #17714b">
      <!--[if IE]><div class="ie-container"><![endif]-->
      <!--[if mso]><div class="mso-container"><![endif]-->
      <table style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #17714b;width:100%" cellpadding="0" cellspacing="0">
        <tbody>
          <tr style="vertical-align: top">
            <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
              <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f9f9f9;"><![endif]-->


              <div class="u-row-container" style="padding: 30px 0px 0px;background-color: transparent">
                <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
                  <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                    <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                    <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                      <div style="width: 100% !important;">
                        <!--[if (!mso)&(!IE)]><!-->
                        <div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                          <!--<![endif]-->

                          <table style="font-family:georgia,palatino;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="overflow-wrap:break-word;word-break:break-word;padding:0px;font-family:georgia,palatino;" align="left">

                                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td style="padding-right: 0px;padding-left: 0px;" align="center">

                                        <img align="center" border="0" src="http://solusidesain.net/imgppsumy/header-email-simkatmawa.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 600px;" width="600" />

                                      </td>
                                    </tr>
                                  </table>

                                </td>
                              </tr>
                            </tbody>
                          </table>

                          <!--[if (!mso)&(!IE)]><!-->
                        </div>
                        <!--<![endif]-->
                      </div>
                    </div>
                    <!--[if (mso)|(IE)]></td><![endif]-->
                    <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                  </div>
                </div>
              </div>



              <div class="u-row-container" style="padding: 0px;background-color: transparent">
                <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
                  <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                    <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 30px 0px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                    <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                      <div style="width: 100% !important;">
                        <!--[if (!mso)&(!IE)]><!-->
                        <div style="padding: 0px 0px 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                          <!--<![endif]-->

                          <table style="font-family:georgia,palatino; " role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:sans-serif,palatino;" align="left">

                                  <div style="color: #3d3030; line-height: 140%; word-wrap: break-word;padding:10px 40px;">
                                    <h3>Salam, ' . $data['penerima'] . '</h3>
                                  
                                    ' . $data['isi'] . '
                                   
                                    
                                  </div>
                                  <div style="margin-top:30px; margin-bottom:30px; text-align:center;">
                                    <a href="' . $data['link'] . '" style="display:inline-block; background:#a81616; padding:10px 20px; color:white; text-decoration: none; border-radius:20px;">Klik di sini untuk melihat</a>
                                  </div>
                                  <div style="margin-top:20px; margin-bottom:0px; text-align:center; ">
                                    <a style="color:#777777" href="http://simkatmawa.umy.ac.id">Simkatmawa UMY</a> 
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>

                          <!--[if (!mso)&(!IE)]><!-->
                        </div>
                        <!--<![endif]-->
                      </div>
                    </div>
                    <!--[if (mso)|(IE)]></td><![endif]-->
                    <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                  </div>
                </div>
              </div>


              <div class="u-row-container" style="padding: 0px;background-color: transparent">
                <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
                  <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                    <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                    <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                      <div style="width: 100% !important;">
                        <!--[if (!mso)&(!IE)]><!-->
                        <div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                          <!--<![endif]-->

                          <table style="font-family:georgia,palatino;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="overflow-wrap:break-word;word-break:break-word;padding:0px;font-family:georgia,palatino;" align="left">

                                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                      <td style="padding-right: 0px;padding-left: 0px;" align="center">

                                        <img align="center" border="0" src="http://solusidesain.net/imgppsumy/footer-email-simkatmawa.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 600px;" width="600" />

                                      </td>
                                    </tr>
                                  </table>

                                </td>
                              </tr>
                            </tbody>
                          </table>

                          <!--[if (!mso)&(!IE)]><!-->
                        </div>
                        <!--<![endif]-->
                      </div>
                    </div>
                    <!--[if (mso)|(IE)]></td><![endif]-->
                    <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                  </div>
                </div>
              </div>



              <div class="u-row-container" style="padding: 0px;background-color: transparent">
                <div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
                  <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
                    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                    <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                    <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                      <div style="width: 100% !important;">
                        <!--[if (!mso)&(!IE)]><!-->
                        <div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                          <!--<![endif]-->

                          <table style="font-family:georgia,palatino;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tbody>
                              <tr>
                                <td style="overflow-wrap:break-word;word-break:break-word;padding:19px 10px 10px;font-family:georgia,palatino;" align="left">

                                  <div style="color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;">
                                    <p style="font-size: 14px; line-height: 140%;">Copyright 2021 LPKA UMY</p>
                                  </div>

                                </td>
                              </tr>
                            </tbody>
                          </table>

                          <!--[if (!mso)&(!IE)]><!-->
                        </div>
                        <!--<![endif]-->
                      </div>
                    </div>
                    <!--[if (mso)|(IE)]></td><![endif]-->
                    <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                  </div>
                </div>
              </div>


              <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
      <!--[if mso]></div><![endif]-->
      <!--[if IE]></div><![endif]-->
    </body>

    </html>
    ';

    return $message;
  }
}

<?php

namespace app\model\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Mail
 */
class Mail
{

    const HOST = 'smtp.gmail.com';
    const USER = 'eugenioanhampossa@gmail.com';
    const PASS = 'nh@mae123';
    const SECURE = PHPMailer::ENCRYPTION_STARTTLS;
    const PORT = 587;
    const CHARSET = 'UTF-8';


    const FROM_EMAIL = 'eugenioanhampossa@gmail.com';
    const FROM_NAME = 'Eugénio Nhampossa';

    private  $error;


    /**
     * getError
     * this method returns the error if the method send mail generate an 
     * exception
     *
     * @return void
     */
    public function getError()
    {
        return $this->error;
    }

    public  function  sendEmail($adresses, $subject, $body)
    {
        $this->error = '';

        $mail = new PHPMailer(true);
        try {
            SMTP::DEBUG_SERVER;

            //SMTP Settings
            $mail->isSMTP();
            $mail->Host = self::HOST;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = self::SECURE;
            $mail->Port = self::PORT;
            $mail->CharSet = self::CHARSET;

            //user settings
            $mail->Username = self::USER;
            $mail->Password = self::PASS;
            $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);

            $adresses = is_array($adresses) ? $adresses : [$adresses];
            foreach ($adresses as $adress) {
                $mail->addAddress($adress);
            }

            //Adding e-mail components 
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            //Sendig emal
            return $mail->send();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
}

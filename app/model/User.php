<?php

use PHPMailer\PHPMailer\OAuth;
use  PHPMailer\PHPMailer\PHPMailer;

class User
{
    public static function create($userData)
    {
        //User data:
        $username = trim($userData['username']);
        $email = trim($userData['email']);
        $password = password_hash($userData['password'], PASSWORD_BCRYPT);
        $vkey = md5(time() . $username);

        $con = Connection::getConn();
        $sql = "INSERT INTO user (username,email,password,vkey) VALUES (?,?,?,?)";
        $sql = $con->prepare($sql);
        $sql->bindValue(1, $username, PDO::PARAM_STR);
        $sql->bindValue(2, $email, PDO::PARAM_STR);
        $sql->bindValue(3, $password, PDO::PARAM_STR);
        $sql->bindValue(4, $vkey, PDO::PARAM_STR);
        $response = $sql->execute();

        if ($response != 0) {
            $html = "<a href='http://localhost/Login/?page=login&method=confirm'>Verify your email</a>";
            $mailSended = sendEmail($email, $username, "Email Verification", $html);
        }

        return $mailSended && $response;
    }
}



function sendEmail($to, $name, $subject, $html)
{
    $mail = new PHPMailer();
    $mail->isSMTP();

    $mail->From = "eugenioanhampossa@gmail.com";
    $mail->FromName = "Login System";

    $mail->Host = "smtp.gmail.com";
    $mail->Port = "465";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    //$mail->oauthUserEmail = "eugenioanhampossa@gmail.com";
    //$mail->oauthClientId = "";
    //$mail->oauthClientSecret = "";
    $mail->AuthType = "XOUTH2";

    $mail->Username = "eugenioanhampossa@gmail.com";
    $mail->Password = "nh@mae123";

    $mail->addAddress($to, $name);
    $mail->Subject = $subject;
    $mail->AltBody = "To see this message use a program that supports HTML!";
    $mail->msgHTML($html);

    return $mail->send();
}

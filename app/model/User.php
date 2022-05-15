<?php

use app\model\Mail\Mail;

require_once __DIR__ . '/Mail.php';
class User
{

    /**
     * Creates the user end sends the email verification
     *
     * @param  mixed $userData
     * @return void
     */
    public static function create($userData)
    {
        //User data:
        $username = trim($userData['username']);
        $email = trim($userData['email']);
        $password = password_hash($userData['password'], PASSWORD_BCRYPT);
        $vkey = md5(time() . "$username");

        //Connecting with databese
        $con = Connection::getConn();
        $sql = "INSERT INTO user (username,email,password,vkey) VALUES (?,?,?,?)";
        $sql = $con->prepare($sql);
        $sql->bindValue(1, $username, PDO::PARAM_STR);
        $sql->bindValue(2, $email, PDO::PARAM_STR);
        $sql->bindValue(3, $password, PDO::PARAM_STR);
        $sql->bindValue(4, $vkey, PDO::PARAM_STR);
        $response = $sql->execute();
        //Verifying the database insertion
        if ($response > 0) {
            $html = "<a href='http://localhost/Login/?page=login&method=confirm&vkey=$vkey'>Verify your email</a>";

            $mail = new Mail();
            $mailSended = $mail->sendEmail($email, "Email verification", $html);
        }

        return $mailSended && $response > 0;
    }

    public static function confirm($vkey)
    {
        try {
            $con = Connection::getConn();
            $sql = "UPDATE user SET verified = :n WHERE vkey = :vkey";
            $sql = $con->prepare($sql);
            $sql->bindValue(":n", 1, PDO::PARAM_INT);
            $sql->bindValue(":vkey", $vkey, PDO::PARAM_STR);
            $response = $sql->execute();
            return $response > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}

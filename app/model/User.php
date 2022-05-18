<?php

use app\model\Mail\Mail;

require_once __DIR__ . '/Mail.php';
class User
{

    /**
     * create
     * Creates the user end sends the email verification
     *
     * @param  mixed $userData
     * @return bool|string
     */
    public static function create($userData): bool|string
    {
        //User data:
        $username = trim($userData['username']);
        $email = trim($userData['email']);
        $password = password_hash($userData['password'], PASSWORD_BCRYPT);
        $vkey = md5(time() . "$username");

        //Verifying if username exists
        if (self::getUser($username, "username")) {
            return "exists";
        }

        //Verifying if email exists
        if (self::getUser($email, "email")) {
            return "emailExists";
        }

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
            $html = self::verificationHtml($username, $vkey);

            $mailSended = self::sendVerification($email, "Email Verification", $html);
        }

        return $mailSended && $response > 0;
    }

    /**
     * verificationHtml
     *
     * this method returns the html that should be sent to user´s email
     * @param  mixed $username
     * @param  mixed $vkey
     * @return void
     */
    public static function verificationHtml($username, $vkey)
    {
        $html = '<div style = "text-align: center;">';
        $html .= '<img src="https://img.icons8.com/material-rounded/96/000000/user-male-circle.png"/>';
        $html .= "<h2>Hello $username</h2>";
        $html .= "<h4>You can verify your email clicking the link below:</h4>";
        $html .= "<a href='http://localhost/Login/?page=login&method=confirm&vkey=$vkey'>Verify your email</a>";
        $html .= '</div>';

        return $html;
    }

    /**
     * sendVerification
     *
     * this method sends an email
     * @param  mixed $toEmail
     * @param  mixed $title
     * @param  mixed $content
     * @return void
     */
    public static function sendVerification($toEmail, $title, $content)
    {
        $mail = new Mail();
        $mailSended = $mail->sendEmail($toEmail, $title, $content);

        return $mailSended;
    }

    /**
     * confirm
     * Gets de user using the verification key provided and updates the 
     * verified field
     * @param  mixed $vkey
     * @return bool|object
     */
    public static function confirm($vkey): bool|object
    {
        try {
            $con = Connection::getConn();
            $sql = "UPDATE user SET verified = :n WHERE vkey = :vkey";
            $sql = $con->prepare($sql);
            $sql->bindValue(":n", 1, PDO::PARAM_INT);
            $sql->bindValue(":vkey", $vkey, PDO::PARAM_STR);
            $sql->execute();
            return $sql->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * getUser
     * return the user that matches with the username
     * @param  mixed $credential
     * @return bool|object
     */
    public static function getUser($credential, $userData): bool|object
    {
        try {
            $con = Connection::getConn();
            $sql = "SELECT * from user WHERE $userData=:$userData";
            $sql = $con->prepare($sql);
            $sql->bindValue(":$userData", $credential, PDO::PARAM_STR);
            $sql->execute();

            $result = $sql->fetchObject("User");
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * updatePassword
     *
     * this method updates the users password
     * @param  mixed $email
     * @param  mixed $password
     * @return void
     */
    public static function updatePassword($email, $password)
    {
        try {
            $con = Connection::getConn();
            $sql = "UPDATE user SET password = :pass WHERE email = :email";
            $sql = $con->prepare($sql);
            $sql->bindValue(":pass", $password, PDO::PARAM_STR);
            $sql->bindValue(":email", $email, PDO::PARAM_STR);
            $sql->execute();
            return $sql->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}

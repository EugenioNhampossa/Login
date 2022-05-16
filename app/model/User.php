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
        if (self::getUser(array("username" => $username))) {
            return "exists";
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
            $mailSended = self::sendVerification($email, $vkey);
        }

        return $mailSended && $response > 0;
    }

    public static function sendVerification($toEmail, $vkey)
    {
        $html = "<a href='http://localhost/Login/?page=login&method=confirm&vkey=$vkey'>Verify your email</a>";

        $mail = new Mail();
        $mailSended = $mail->sendEmail($toEmail, "Email verification", $html);

        return $mailSended;
    }


    /**
     * confirm
     * Gets de user using the verification key provided and updates the 
     * verified field
     * @param  mixed $vkey
     * @return bool
     */
    public static function confirm($vkey): bool
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

    /**
     * getUser
     * return the user that matches with the username
     * @param  mixed $credentials
     * @return bool|object
     */
    public static function getUser($credentials): bool|object
    {
        try {
            $username = $credentials['username'];
            $con = Connection::getConn();
            $sql = "SELECT * from user WHERE username=:username";
            $sql = $con->prepare($sql);
            $sql->bindValue(":username", $username, PDO::PARAM_STR);
            $sql->execute();

            $result = $sql->fetchObject("User");
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}

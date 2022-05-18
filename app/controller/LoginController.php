<?php

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class LoginController
{

    /**
     * index
     * this method prints the login page after replacing
     * the dinamic area with the login.html content.
     * @return void
     */
    public function index()
    {
        try {

            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('login.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    /**
     * register
     * this method prints the registration page after replacing
     * the dinamic area with the register.html content.
     * @return void
     */
    public function register()
    {
        try {

            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('register.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }


    /**
     * save
     * this methed calls the create method in the users class that is 
     * responsable for saving the user in the database.
     * @return void
     */
    public function save()
    {
        try {
            $resp = false;
            //Creating parameters
            $parameters = array();
            $parameters['message'] = "";

            if (isset($_POST['username'])) {
                $resp = User::create($_POST);
                $parameters['enteredData'] = $_POST;
            }
            //loading twig
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('register.html');


            if ($resp === "exists") { //Verifying if username already exists
                $parameters['message'] = "Username already chosen, enter another one.";
            } else  if ($resp === "emailExists") { //Verifying if email already exists
                $parameters['message'] = "Email already chosen, enter another one.";
            } else  if ($resp) { //redirrecting to "verify your email" page
                $_SESSION['email'] = $_POST['email']; //using session to print the email adress provided
                header("Location:?page=login&method=verify");
            } else { //If an error ocur in the saving process
                header("Location:?page=error");
            }

            //Rendering the page
            $conteudo = $template->render($parameters);
            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
        }
    }

    /**
     * verify
     * this method prints the verification page
     * @return void
     */
    public function verify()
    {
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('verifyEmail.html');
            $parameters = array();

            if (isset($_SESSION['email'])) {
                $parameters['email'] = $_SESSION['email'];
            } else {
                header("Location:?page=error");
            }

            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }


    /**
     * confirm
     * 
     * print the page that confirms the verificarion
     * @return void
     */
    public function confirm()
    {
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('confirmVerification.html');
            $parameters = array();
            $parameters['message'] = "";

            if (isset($_GET['vkey'])) { //verifying if the key is set on url
                if (User::confirm($_GET['vkey'])) { //updating the verification column of the user
                    $parameters['message'] = "Email verifyed!\nYou may now log-in";
                } else {
                    $parameters['message'] = "We went thru an error verifying your email. Try again!";
                }
            } else {
                header("Location:?page=error");
            }
            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    /**
     * login
     * 
     * this method will verify the user credentials, if it exists then
     * the user will be redirected to home page.
     * @return void
     */
    public function login()
    {
        try {
            //Loading twig
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $user = false;
            $parameters = array();

            if (isset($_POST['username'])) {
                //getting the user with the user name provided
                $user = User::getUser($_POST['username'], "username");
                $typedPassword = $_POST['password']; //typed password
                $parameters['enteredData'] = $_POST;
            }

            //the login page will be reloaded if something goes wrong with the user credentials
            $load = "login.html";
            $parameters['message'] = "";
            //$parameters['updated'] = 0;
            $parameters['verified'] = 1;

            if (!$user) { // verifying the authenticity of the user
                $parameters['message'] = "Invalid password or username";
            } else if ($user->verified == 0) { // confirming user verication
                $parameters['vkey'] = $user->vkey;
                $parameters['message'] = "User not not verified";
                $parameters['verified'] = 0;
            } else if (password_verify($typedPassword, $user->password)) { //confirming password
                //Loged user data
                $_SESSION['logedUser'] = [
                    'username' => $user->username,
                    'email' => $user->email,
                    'detecreated' => $user->datecreated
                ];
                header("Location:?page=home"); //Redirecting to home page
            } else {
                $parameters['message'] = "Invalid password or username";
            }

            $template = $twig->load($load);
            $conteudo = $template->render($parameters);
            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    /**
     * resetPassword
     * 
     * this method will print the form for password reset
     * in the form the user have to enter the code sent by email and his/her new password
     * @return void
     */
    public function resetPassword()
    {
        try {

            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('pwdReset.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    /**
     * updatePassword
     * 
     * this method will process the form for password reset, it will compare
     * the code typed and the code sent by email if it matches, then the password
     * will be updated
     * @return void
     */
    public function updatePassword()
    {
        try {
            //loading twig
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);

            $load = 'pwdReset.html'; //by default, the user is redirected to the same page
            $resp = false;
            $parameters = array();
            $parameters['message'] = "";
            /** 
             * if this process succeed, then the updated status will turn to 1, and the user will
             * be redirected to login page that prints the message "updated".
             */
            $parameters['updated'] = 0;

            //Verifying if its all setup to update the password
            if (isset($_POST['newPassword']) && isset($_SESSION['code'])) {
                if ($_SESSION['code'] == $_POST['typedCode']) { //comparing the codes
                    $email = $_SESSION['resetEmail'];
                    $password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
                    $resp = User::updatePassword($email, $password); //updating user´s password

                    if (!$resp) { //if the process succeed
                        $parameters['message'] = "Something went wrong while updating your password";
                    } else {
                        $parameters['message'] = "Your password was updated";
                        $parameters['updated'] = 1;
                        $load = "login.html";
                    }
                } else {
                    $parameters['message'] = "The given code don´t match";
                }
            } else {
                header("Location:?page=error");
            }

            $template = $twig->load($load);
            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    /**
     * pickUser
     *
     * this method will print the form to select the user
     * @return void
     */
    public function pickUser()
    {
        try {

            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('pickUser.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    public function sendResetCode()
    {
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);

            $load = "pickUser.html";
            $parameters = array();
            $parameters['message'] = "";
            $user = false;

            if (isset($_POST['username'])) {
                $user = User::getUser($_POST['username'], "username");
                $parameters['enteredData'] = $_POST;
            }

            if (!$user) {
                $parameters['message'] = "Provided username is not registered";
            } else {
                $email = $user->email;
                $code = self::generateCode();
                $html = self::resetHtml($user->username, $code);
                if (User::sendVerification($email, "Password Reset", $html)) {
                    $_SESSION['resetEmail'] = $email;
                    $_SESSION['code'] = $code;
                    $load = "pwdReset.html";
                } else {
                    $parameters['message'] = "Error sendig email";
                }
            }
            $template = $twig->load($load);
            $conteudo = $template->render($parameters);
            echo $conteudo;
        } catch (Exception $e) {
            header("Location:?page=error");
            echo $e;
        }
    }

    public static function resetHtml($username, $code)
    {
        $html = '<div style = "text-align: center;">';
        $html .= '<img src="https://img.icons8.com/material-rounded/96/000000/user-male-circle.png"/>';
        $html .= "<h2>Hello $username</h2>";
        $html .= "<h4>Password Reset Code:</h4>";
        $html .= '<p style="font-size: xx-large;">' . "$code</p>";
        $html .= '</div>';

        return $html;
    }

    public static function generateCode()
    {
        $code = 0;
        for ($i = 0; $i < 6; $i++) {
            $code = ($code * 10) + rand(0, 9);
        }
        return $code;
    }
}

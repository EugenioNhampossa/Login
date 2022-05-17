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
            //loading twig
            $resp = User::create($_POST);
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('register.html');

            //Creating parameters
            $parameters = array();
            $parameters['message'] = "";


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
     * resendEmail
     * this method send the verification email
     * @param  mixed $email
     * @param  mixed $vkey
     * @return void
     */
    public function resendEmail($email, $vkey)
    {
        if (User::sendVerification($email, $vkey)) {
            $_SESSION['email'] = $email; //using session to print the email adress provided
            header("Location:?page=login&method=verify");
        } else {
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
     * @return void
     */
    public function login()
    {
        try {
            //Loading twig
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);

            //getting the user with the user name providede
            $user = User::getUser($_POST['username'], "username");

            $typedPassword = $_POST['password']; //typed password
            $parameters = array();
            //the login page will be reloaded if something goes wrong with the user credentials
            $load = "login.html";
            $parameters['message'] = "";
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
}

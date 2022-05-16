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
            echo $e;
        }
    }


    /**
     * save
     * this methed calls the create method in the users class that is 
     * responsable for save the user in the database.
     * @return void
     */
    public function save()
    {
        try {
            if (User::create($_POST)) {
                header("Location:?page=login&method=verify");
            } else {
                header("Location:?page=login&page=register&error");
            }
        } catch (Exception $e) {
            header("Location:?page=login&page=register&error");
        }
    }

    public function resendEmail($email, $vkey)
    {
        if (User::sendVerification($email, $vkey)) {
            header("Location:?page=login&method=verify");
        } else {
            header("Location:?page=login&page=register&error");
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
            //$parameters['email'] = $email;

            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
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
            if (isset($_GET['vkey'])) {
                if (User::confirm($_GET['vkey'])) {
                    $parameters['message'] = "Email verifyed";
                } else {
                    $parameters['message'] = "Error verifying your email. Try again!";
                }
            }
            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function login()
    {
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);

            $user = User::getUser($_POST);
            $typedPassword = $_POST['password'];
            $parameters = array();
            $load = "login.html";
            $parameters['message'] = "";
            if (!$user) {
                $parameters['message'] = "invalid";
            } else if ($user->verified == 0) {
                $parameters['vkey'] = $user->vkey;
                $parameters['message'] = "notVerified";
            } else if (password_verify($typedPassword, $user->password)) {
                $_SESSION['loged'] = "";
                $_SESSION['username'] = $user->username;
                $_SESSION['email'] = $user->email;
                $_SESSION['detecreated'] = $user->datecreated;
                header("Location:?page=home");
            } else {
                $parameters['message'] = "invalid";
            }

            $template = $twig->load($load);
            $conteudo = $template->render($parameters);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }
}

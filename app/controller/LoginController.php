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
                header("Location:?page=login&method=verify&email=" . $_POST['email']);
            } else {
                header("Location:?page=login&page=register&error");
            }
        } catch (Exception $e) {
            header("Location:?page=login&page=register&error");
        }
    }


    /**
     * verify
     * this method prints the verification page
     * @return void
     */
    public function verify($email)
    {
        try {
            $loader = new FilesystemLoader('app/view');
            $twig = new Environment($loader);
            $template = $twig->load('verifyEmail.html');

            $parameters = array();
            $parameters['email'] = $email;

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
            $twig = new \Twig\Environment($loader);
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
}

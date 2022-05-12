<?php


class LoginController
{

    public function index()
    {
        try {

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('login.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function register()
    {
        try {

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('register.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function save()
    {
        try {
            if (!User::create($_POST)) {
                header("Location:?page=login&method=verify");
            } else {
                header("Location:?page=login&page=register&error");
            }
        } catch (Exception $e) {
            header("Location:?page=login&page=register&error");
        }
    }

    public function verify()
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('verifyEmail.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function confirm()
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('confirmVerification.html');

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            echo $e;
        }
    }
}

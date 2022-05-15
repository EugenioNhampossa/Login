<?php


class ErrorController
{
    public function index()
    {
        try {

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('error.html');

            $parameters = array();
            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            echo "Nao foi encontrado nenhum registro";
        }
    }
}

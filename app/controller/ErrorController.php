<?php


class ErrorController
{
    public function index()
    {
        try {

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('error.html');

            $parametros = array();

            $conteudo = $template->render();

            echo $conteudo;
        } catch (Exception $e) {
            echo "Nao foi encontrado nenhum registro";
        }
    }
}

<?php


class HomeController
{
    public function index()
    {
        try {

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('home.html');

            $parameters = array();
            if (isset($_SESSION['logedUser'])) {
                $parameters['id'] = $_SESSION['logedUser']['id'];
                $parameters['username'] = $_SESSION['logedUser']['username'];
                $parameters['email'] = $_SESSION['logedUser']['email'];

                $date = date_create($_SESSION['logedUser']['datecreated']);
                $parameters['datecreated'] = date_format($date, 'D d-M-Y H:m');
            }

            $conteudo = $template->render($parameters);

            echo $conteudo;
        } catch (Exception $e) {
            echo "Nao foi encontrado nenhum registro";
        }
    }
}

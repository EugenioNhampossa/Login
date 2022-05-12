<?php
session_start();
//session_destroy();
class Core
{
    public function start($urlGet)
    {

        $action = 'index';

        if (isset($_GET['method'])) {
            $action = $urlGet['method'];
        }

        if (isset($urlGet['page'])) {
            $controller = ucfirst($urlGet['page'] . 'Controller');
        } else if (isset($_SESSION['loged'])) {
            $controller = 'HomeController';
        } else {
            $controller = 'LoginController';
        }




        if (!class_exists($controller)) {
            $controller = 'ErrorController';
        }

        $params = array();
        if (isset($urlGet['id']) && $urlGet != null) {
            $params = array('id' => $urlGet['id']);
        }

        call_user_func_array(array(new $controller, $action), $params);
    }
}

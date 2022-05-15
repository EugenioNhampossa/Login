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

        call_user_func_array(array(new $controller, $action), $this->parameters($urlGet));
    }

    public function parameters($urlGet)
    {
        $params = array();
        if ($urlGet != null) {
            if (isset($urlGet['id'])) {
                global $params;
                $params['id'] = $urlGet['id'];
            }
            if (isset($urlGet['email'])) {
                global $params;
                $params['email'] = $urlGet['email'];
            }
        }
        return $params;
    }
}

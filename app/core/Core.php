<?php

class Core
{
    public function start($urlGet)
    {

        $action = 'index';

        if (isset($_GET['method'])) {
            $action = $urlGet['method'];
        }

        if (isset($urlGet['pages'])) {
            $controller = ucfirst($urlGet['pages'] . 'Controller');
        } else {
            $controller = 'HomeController';
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

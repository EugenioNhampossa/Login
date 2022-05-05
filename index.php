<?php
ini_set('display_errors', 1);

require_once "app/core/Core.php";


require_once "app/controller/ErrorController.php";
require_once "app/controller/HomeController.php";
require_once "app/controller/LoginController.php";

require_once "vendor/autoload.php";


$template = file_get_contents('app/template/structure.html');

ob_start();


$core = new Core;

$core->start($_GET);

$output = ob_get_contents();

ob_end_clean();

$page = str_replace("{{dinamic-area}}", $output, $template);

echo $page;

<?php
session_start();
ini_set('display_errors', 1);

//core
require_once "app/core/Core.php";

//Models
require_once "app/model/User.php";
require_once "app/model/Mail.php";

//Controllers
require_once "app/controller/ErrorController.php";
require_once "app/controller/HomeController.php";
require_once "app/controller/LoginController.php";

//DB connection
require_once "lib/database/Connection.php";


require_once "vendor/autoload.php";


//storring all htlm in the template variable
$template = file_get_contents('app/template/structure.html');

//capturin any output from controllers
ob_start();

$core = new Core;

$core->start($_GET);

$output = ob_get_contents();

ob_end_clean();

//Replacing the dinamic-area with the outputed contet
$page = str_replace("{{dinamic-area}}", $output, $template);

echo $page;

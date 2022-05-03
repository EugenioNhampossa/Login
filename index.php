<?php

$template = file_get_contents('app/template/structure.html');

ob_start();


$core = new Core;

$core->start($_GET);


$output = ob_get_contents();

ob_end_clean();

$page = str_replace("{{dinamin-area}}", $output, $template);

echo $page;

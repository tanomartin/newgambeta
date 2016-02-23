<?php 
include_once "include/config.inc.php";
include_once "include/templateEngine.inc.php";

// Cargo la plantilla
$twig->display('index.html');

?>
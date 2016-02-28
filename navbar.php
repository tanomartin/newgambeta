<?php 
include_once "include/templateEngine.inc.php";

var_dump($_POST);

// Cargo la plantilla
$twig->display('navbar.html', array (torneos => $_POST['torneos']));
?>
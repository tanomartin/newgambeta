<?php
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

$torneo = unserialize($_POST['torneo']);
$categorias = unserialize($_POST['categorias']);

// Cargo la plantilla
$twig->display ( 'conteiner.html', array (
		'torneo' => $torneo,
		'categorias' => $categorias
));
?>
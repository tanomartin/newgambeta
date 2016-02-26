<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

$nombreCategoria = $_POST['nombreCategoria'];
$torneo = unserialize($_POST['torneo']);

// Cargo la plantilla
$twig->display('dafault.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria));

?>
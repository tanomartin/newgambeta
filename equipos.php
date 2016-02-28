<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/equipos.php";

$torneo = unserialize($_POST['torneo']);
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oObj = new Equipos();
$equipos = $oObj->getTorneoCat($idTorneoCat);

// Cargo la plantilla
$twig->display('equipos.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'equipos' => $equipos));

?>
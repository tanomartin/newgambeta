<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";


$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);

// Cargo la plantilla
$twig->display('dafault.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria));

?>
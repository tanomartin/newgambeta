<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/resultados.php";

$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);
$oResultado= new Resultados();
$goleadoras = $oResultado->goleadoras($idTorneoCat);

// Cargo la plantilla
$twig->display('goleadoras.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria, 'goleadoras' => $goleadoras));

?>
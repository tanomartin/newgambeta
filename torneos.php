<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

$idTorneo = $_GET['idTorneo'];
$idCatTor = $_GET['idTorneoCat'];

$oTorneo = new Torneos();
$torneo = $oTorneo->get($idTorneo);
$oTorneoCat = new TorneoCat();
$aTorneoCat = $oTorneoCat->get($idTorneo);


// Cargo la plantilla
$twig->display('torneos.html', array('torneo'=>$torneo[0], 'categorias' => $aTorneoCat,));

?>
<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];

$oTorneo = new Torneos();
$torneo = $oTorneo->get($idTorneo);
$oTorneoCat = new TorneoCat();
$aTorneoCat = $oTorneoCat->get($idTorneo);

// Cargo la plantilla
$twig->display('torneo-izq.html', array('torneo'=>$torneo[0], 'categorias' => $aTorneoCat, 'idTorneoCat' => $idTorneoCat));
?>
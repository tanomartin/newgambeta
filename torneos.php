<?php 
include_once "include/templateEngine.inc.php";

$idTorneo = $_GET['idTorneo'];
$idTorneoCat = $_GET['idTorneoCat'];

// Cargo la plantilla
$twig->display('torneos.html', array('idTorneo'=>$idTorneo, 'idTorneoCat' => $idTorneoCat));

?>
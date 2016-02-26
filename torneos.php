<?php 
include_once "include/templateEngine.inc.php";

$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];

// Cargo la plantilla
$twig->display('torneos.html', array('idTorneo'=>$idTorneo, 'idTorneoCat' => $idTorneoCat));

?>
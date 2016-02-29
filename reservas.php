<?php 
include_once "include/templateEngine.inc.php";
include_once "model/equipos.php";

$torneo = unserialize($_POST['torneo']);
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oEquipo = new Equipos ();
$equipos = $oEquipo->getTorneoCat ( $idTorneoCat );

// Cargo la plantilla
$twig->display('reservas.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'equipos' => $equipos));

?>
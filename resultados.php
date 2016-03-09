<?php 
include_once "include/templateEngine.inc.php";
include_once "model/equipos.php";

$torneo = unserialize(stripslashes($_POST['torneo']));
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oEquipos = new Equipos();
$equipos = $oEquipos->getTorneoCat($idTorneoCat);

// Cargo la plantilla
if (isset($_POST['screen'])) {
	$twig->display('resultadosMobile.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'equipos' => $equipos));
} else {
	$twig->display('resultados.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'equipos' => $equipos));
}
?>
<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/resultados.php";
include_once "model/fixture.php";
include_once "model/equipos.php";

$torneo = unserialize(stripslashes($_POST['torneo']));
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oResultado= new Resultados();
$tabla = $oResultado->armarTabla($idTorneoCat);

// Cargo la plantilla
if (isset($_POST['screen'])) {
	$twig->display('posicionesMobile.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'tabla' => $tabla));
} else {
	$twig->display('posiciones.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'tabla' => $tabla));
}
?>
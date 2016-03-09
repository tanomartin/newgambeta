<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/resultados.php";

$torneo = unserialize(stripslashes($_POST['torneo']));
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oResultado= new Resultados();
$goleadoras = $oResultado->goleadoras($idTorneoCat);

// Cargo la plantilla
if (isset($_POST['screen'])) {
	$twig->display('goleadorasMobile.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'goleadoras' => $goleadoras));
} else {
	$twig->display('goleadoras.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'goleadoras' => $goleadoras));
}
?>
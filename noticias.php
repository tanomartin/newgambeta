<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/noticias.php";

$torneo = unserialize(stripslashes($_POST['torneo']));
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oNoticias = new Noticias();
$aNoticias = $oNoticias->getByCant(5,$idTorneoCat);

// Cargo la plantilla
if (isset($_POST['screen'])) {
	$twig->display('noticiasMobile.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'noticias' => $aNoticias));
} else {
	$twig->display('noticias.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'noticias' => $aNoticias));
}
?>
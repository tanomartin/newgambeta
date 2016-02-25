<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/noticias.php";


$idTorneo = $_GET['idTorneo'];
$idTorneoCat = $_GET['idTorneoCat'];
// Cargo la plantilla

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);
$oTorneoCat = new TorneoCat();
$aTorneoCat = $oTorneoCat->getByIdCompleto($idTorneoCat);

if ($aTorneoCat->nombreCatPagina == NULL) {
	$nombreCategoria = $aTorneoCat->nombrePagina;
} else {
	$nombreCategoria = $aTorneoCat->nombreCatPagina." - " .$aTorneoCat->nombrePagina;
}

$oNoticias = new Noticias();
$aNoticias = $oNoticias->getByCant(5,$idTorneoCat);

$twig->display('noticias.html', array('nombreTorneo'=>$atorneo[0]['nombre'], 'nombreCategoria' => $nombreCategoria, 'noticias' => $aNoticias));

?>
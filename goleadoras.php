<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/resultados.php";

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

$oResultado= new Resultados();
$goleadoras = $oResultado->goleadoras($idTorneoCat);

// Cargo la plantilla
$twig->display('goleadoras.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria, 'goleadoras' => $goleadoras));

?>
<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/noticias.php";


$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);
$oNoticias = new Noticias();
$aNoticias = $oNoticias->getByCant(5,$idTorneoCat);

// Cargo la plantilla
$twig->display('noticias.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria, 'noticias' => $aNoticias));

?>
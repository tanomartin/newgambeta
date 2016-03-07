<?php 
include_once 'include/config.inc.php';
include_once 'include/templateEngine.inc.php';
include_once 'model/torneos.php';
include_once 'model/sedes.php';
include_once 'model/noticias.php';

$oTorneos = new Torneos();
$torneos = $oTorneos->getActivos();
$oSedes = new Sedes();
$sedes = $oSedes->get();
$oNoticias = new Noticias();
$noticia = $oNoticias->getByCant(1);

session_start();
$_SESSION["visits"] = 0;

// Cargo la plantilla
$twig->display('index.html', array("torneos"=> $torneos,"sedes"=> $sedes, "noticia" => $noticia[0]));

?>
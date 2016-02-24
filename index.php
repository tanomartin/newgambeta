<?php 
include_once 'include/config.inc.php';
include_once 'include/templateEngine.inc.php';
include_once 'model/torneos.php';
include_once 'model/sedes.php';
$oTorneos = new Torneos();
$torneos = $oTorneos->get();

$oSedes = new Sedes();
$sedes = $oSedes->get();

// Cargo la plantilla
$twig->display('index.html', array("torneos"=> $torneos,"sedes"=> $sedes));

?>
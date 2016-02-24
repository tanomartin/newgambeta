<?php 
include_once 'include/config.inc.php';
include_once 'include/templateEngine.inc.php';
include_once 'model/torneos.php';
$oTorneos = new Torneos();
$torneos = $oTorneos->get();

// Cargo la plantilla
$twig->display('index.html', array("torneos"=> $torneos) );

?>
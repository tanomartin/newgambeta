<?php 
include_once "include/config.inc.php";
include_once "model/sedes.php"; 
include_once "include/templateEngine.inc.php";

/*
$oObj = new Sedes();
$aSedes = $oObj->get(); 
*/

// Cargo la plantilla
$twig->display('index.html',array("sedes" => $aSedes));

?>
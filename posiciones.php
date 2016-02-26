<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/resultados.php";
include_once "model/fixture.php";
include_once "model/equipos.php";

$idTorneo = $_POST['idTorneo'];
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);
$oResultado= new Resultados();
$tabla = $oResultado->armarTabla($idTorneoCat);

// Cargo la plantilla
$twig->display('posiciones.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria, 'tabla' => $tabla));

?>
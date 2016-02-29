<?php 
include_once "include/templateEngine.inc.php";
include_once "model/fechas.php";

$torneo = unserialize($_POST['torneo']);
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oFechas = new Fechas();
$fechas = $oFechas->getIdTorneoCat($idTorneoCat,'fechaIni');

// Cargo la plantilla
$twig->display('fixture.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'fechas' => $fechas));

?>
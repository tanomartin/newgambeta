<?php 
include_once "include/templateEngine.inc.php";

$torneo = unserialize($_POST['torneo']);
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

// Cargo la plantilla
$twig->display('reservas.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria));

?>
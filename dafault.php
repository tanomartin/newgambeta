<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once 'model/noticias.php';

$nombreCategoria = $_POST['nombreCategoria'];
$torneo = unserialize(stripslashes($_POST['torneo']));
$oNoticias = new Noticias();
$noticias = $oNoticias->getByIdTorneo(5,$torneo['id']);

// Cargo la plantilla
$twig->display('dafault.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'noticias' => $noticias));

?>
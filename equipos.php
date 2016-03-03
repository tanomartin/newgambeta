<?php
include_once "include/templateEngine.inc.php";
include_once 'include/analyticstracking.php';
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/equipos.php";
include_once "model/jugadoras.php";

$torneo = unserialize (stripslashes($_POST ['torneo']));
$idTorneoCat = $_POST ['idTorneoCat'];
$nombreCategoria = $_POST ['nombreCategoria'];

$oEquipo = new Equipos ();
$equipos = $oEquipo->getTorneoCat ( $idTorneoCat );

if ($equipos != null) {
	$oJugadora = new Jugadoras ();
	foreach ( $equipos as $key => $equipo ) {
		$aJugadoras = $oJugadora->getByEquipoTorneo ( $equipo ['id'], $idTorneoCat );
		$equipos [$key] ['jugadoras'] = $aJugadoras;
	}
}

// Cargo la plantilla
$twig->display ( 'equipos.html', array (
		'torneo' => $torneo,
		'nombreCategoria' => $nombreCategoria,
		'equipos' => $equipos,
		'arrayEquipos' => serialize ( $equipos ) 
) );

?>
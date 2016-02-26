<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

$idTorneo = $_POST ['idTorneo'];
$idTorneoCat = $_POST ['idTorneoCat'];

$oTorneo = new Torneos ();
$atorneo = $oTorneo->get ( $idTorneo );
$oTorneoCat = new TorneoCat ();
$aTorneoCat = $oTorneoCat->getByTorneoSub ( $idTorneo );

$index = 0;
foreach ( $aTorneoCat as $categoria ) {
	$nombreCompleto = $oTorneoCat->getCategoriasCompletas ( $categoria ['id'] );
	$nombreCompleto = $nombreCompleto [0];
	if ($nombreCompleto ['nombreCatPagina'] == NULL) {
		$nombreCategoria = $nombreCompleto ['nombrePagina'];
	} else {
		$nombreCategoria = $nombreCompleto ['nombreCatPagina'] . " - " . $nombreCompleto ['nombrePagina'];
	}
	$aTorneoCat [$index] ['nombre'] = $nombreCategoria;
	if ($categoria ['id'] == $idTorneoCat) {
		$nombreCategoriaSelect = $nombreCategoria;
	}
	$index ++;
}


// Cargo la plantilla
$twig->display ( 'torneos.html', array (
		'idTorneo' => $idTorneo,
		'idTorneoCat' => $idTorneoCat,
		'torneo' => serialize ( $atorneo [0] ),
		'categorias' => serialize ( $aTorneoCat ),
		'nombreCategoria' => $nombreCategoriaSelect
) );

?>
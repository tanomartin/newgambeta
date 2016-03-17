<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";

if(sizeof($_POST) == 0 || !isset($_POST ['idTorneo']) || !isset($_POST ['idTorneoCat'])) {
	header('Location: index.php');
}

$idTorneo = $_POST ['idTorneo'];
$idTorneoCat = $_POST ['idTorneoCat'];

$oTorneo = new Torneos ();
$atorneo = $oTorneo->get ( $idTorneo );
$oTorneoCat = new TorneoCat ();
$aTorneoCat = $oTorneoCat->getByTorneoSub ( $idTorneo );

$index = 0;
if ($aTorneoCat != null) {
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
}

if(isset($_POST['idEquipo']) && isset($_POST['password'])) {
	$acceso = "";
	include_once "model/equipos.php";
	if ($_POST['idEquipo'] != 0 && $_POST['password'] != "") {
		$oEquipos = new Equipos();
		$ids = explode("-",$_POST['idEquipo']);
		$ingresa = $oEquipos->accesoCorrecto($ids[0], $ids[1], $_POST['password']);
		if ($ingresa) {
			session_start();
			$_SESSION['equipo'] =$ids[0];
			$_SESSION['equipoTorneo'] = $ids[1];
			$acceso = "ok";
		} else {
			$acceso = "nok";
		}
	} else {
		$acceso = "nok";
	}
} 

$_SESSION["visits"] = $_SESSION["visits"] + 1;
if ($_SESSION["visits"] > 1) {
	unset($_SESSION['equipo']);
	unset($_SESSION['equipoTorneo']);
	$acceso = "";
} 

// Cargo la plantilla
$twig->display ( 'torneos.html', array (
		'idTorneoCat' => $idTorneoCat,
		'torneo' => serialize  ( $atorneo [0] ),
		'categorias' => serialize  ( $aTorneoCat ),
		'nombreCategoria' => $nombreCategoriaSelect,
		'torneoObj' => $atorneo[0],
		'acceso' => $acceso
) );

?>
<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/jugadoras.php";
include_once "model/resultados.php";
include_once "model/equipos.php";

$idTorneo = $_GET['idTorneo'];
$idTorneoCat = $_GET['idTorneoCat'];
// Cargo la plantilla

$oTorneo = new Torneos();
$atorneo = $oTorneo->get($idTorneo);
$oTorneoCat = new TorneoCat();
$aTorneoCat = $oTorneoCat->getByIdCompleto($idTorneoCat);
if ($aTorneoCat->nombreCatPagina == NULL) {
	$nombreCategoria = $aTorneoCat->nombrePagina;
} else {
	$nombreCategoria = $aTorneoCat->nombreCatPagina." - " .$aTorneoCat->nombrePagina;
}

$oObj = new Equipos();
$aEquipos = $oObj->getTorneoCat($idTorneoCat);

$oJugadora = new Jugadoras();
$oResultados = new Resultados();
$index = 0;
for($j=0;$j<count($aEquipos);$j++) {
	$aJugadoras1 = $oJugadora->getByEquipoTorneo($aEquipos[$j]['id'], $idTorneoCat);
	for ($i=0; $i<count($aJugadoras1); $i++) {
		$aJugadora = $oJugadora->get($aJugadoras1[$i][id]);
		$tarj =  $oResultados->getTarjetasByIdJugadoraEquipo($aJugadoras1[$i][idJugadoraEquipo]);
		if ($tarj[0]['rojas']!=0 ||  $tarj[0]['amarillas']!=0) {		
			$tarjetas[$index][nombre] = $aJugadora[0]['nombre'];
			$tarjetas[$index][equipo] = $oJugadora->getEquipoByIdAndTorneoCat($aJugadoras1[$i][idJugadoraEquipo],$idTorneoCat);
			$tarjetas[$index][amarillas] = $tarj[0]['amarillas'];
			$tarjetas[$index][rojas] = $tarj[0]['rojas'];
			$index++;
		}
	}
}

// Cargo la plantilla
$twig->display('tarjetas.html', array('torneo'=>$atorneo[0], 'nombreCategoria' => $nombreCategoria, 'tarjetas' => $tarjetas));

?>
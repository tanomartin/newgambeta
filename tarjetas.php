<?php 
include_once "include/templateEngine.inc.php";
include_once 'model/torneos.php';
include_once "model/torneos.categorias.php";
include_once "model/jugadoras.php";
include_once "model/resultados.php";
include_once "model/equipos.php";

$torneo = unserialize($_POST['torneo']);
$idTorneoCat = $_POST['idTorneoCat'];
$nombreCategoria = $_POST['nombreCategoria'];

$oObj = new Equipos();
$aEquipos = $oObj->getTorneoCat($idTorneoCat);
$oJugadora = new Jugadoras();
$oResultados = new Resultados();
$index = 0;
for($j=0;$j<count($aEquipos);$j++) {
	$aJugadoras = $oJugadora->getByEquipoTorneo($aEquipos[$j]['id'], $idTorneoCat);
	for ($i=0; $i<count($aJugadoras); $i++) {
		$aJugadora = $oJugadora->get($aJugadoras[$i][id]);
		$tarj =  $oResultados->getTarjetasByIdJugadoraEquipo($aJugadoras[$i][idJugadoraEquipo]);
		if ($tarj[0]['rojas']!=0 ||  $tarj[0]['amarillas']!=0) {
			$equipo = $oJugadora->getJugadoraEquipo($aJugadoras[$i][idJugadoraEquipo]);		
			$tarjetas[$index][nombre] = $aJugadora[0]['nombre'];
			$tarjetas[$index][equipo] = $equipo[0]['nombre'];
			$tarjetas[$index][amarillas] = $tarj[0]['amarillas'];
			$tarjetas[$index][rojas] = $tarj[0]['rojas'];
			$index++;
		}
	}
}

// Cargo la plantilla
$twig->display('tarjetas.html', array('torneo'=>$torneo, 'nombreCategoria' => $nombreCategoria, 'tarjetas' => $tarjetas));

?>
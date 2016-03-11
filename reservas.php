<?php
include_once "include/templateEngine.inc.php";
include_once "model/equipos.php";
include_once "model/fechas.php";
include_once "model/fixture.php";
include_once "model/reservas.php";
include_once "include/fechas.php";

$torneo = unserialize (stripslashes($_POST ['torneo']));
$idTorneoCat = $_POST ['idTorneoCat'];
$nombreCategoria = $_POST ['nombreCategoria'];

if (isset($_POST ['acceso'])) {
	$acceso = $_POST ['acceso'];
}

session_start();
$oEquipo = new Equipos ();
// Cargo la plantilla
if ($acceso == "ok") {
	session_start ();
	$equipo = $oEquipo->get ( $_SESSION ['equipo'] );
	
	$oFechas = new Fechas();
	$fecha_activa = $oFechas->getFechaActiva($idTorneoCat);
	if ($fecha_activa != NULL) {
		$oFixture = new Fixture();
		$partidos = $oFixture->getByFechaEquipo($fecha_activa["id"],$_SESSION ['equipoTorneo']);
		if ($partidos == NULL) {
			$idReserva = $oEquipo->tieneReserva($fecha_activa["id"],$_SESSION['equipo']);
			if ($idReserva == 0) {
				$horas_fecha = $oFechas->getHorasCancha($fecha_activa["id"]);
				$fechaLibre = $oEquipo->tieneFechaLibre($idTorneoCat,$_SESSION['equipo']);
			} else {
				$oReserva = new Reservas();
				$reserva = $oReserva->getReservaById($idReserva);
				if ($reserva->fecha_libre == 0) {
					$detalleReserva = $oReserva->getDetalleReservaById($idReserva);
				}
			}
		} else {
			foreach ($partidos as $clave => $partido) {
				$partidos[$clave]['confirmado'] = $oFixture->partidoConfirmado($partido['id'],$_SESSION['equipo']);
			}
		}
	}
	
	
	if (isset($_POST['screen'])) {
		
		if (sizeof($partidos) != 0) {
			foreach($partidos as $key => $partido) {
				$partidos[$key]['fechaPartido'] = substr($partido['fechaPartido'],8,2)."/".substr($partido['fechaPartido'],5,2);
			}
		}
		
		$twig->display ( 'reservasmenuMobile.html', array (
				'torneo' => $torneo,
				'nombreCategoria' => $nombreCategoria,
				'equipo' => $equipo [0],
				'fecha_activa' => $fecha_activa,
				'partidos' => $partidos,
				'horas_fecha' => $horas_fecha,
				'fecha_libre' => $fechaLibre,
				'reserva' => $reserva,
				'idReserva' => $idReserva,
				'detalleReserva' => $detalleReserva,
				'idsession' =>  $_SESSION ['equipoTorneo'] 
		));
	} else {
		
		if (sizeof($partidos) != 0) {
			foreach($partidos as $key => $partido) {
				$partidos[$key]['fechaPartido'] = cambiaf_a_normal($partido['fechaPartido']);
			}
		}
		
		$twig->display ( 'reservasmenu.html', array (
				'torneo' => $torneo,
				'nombreCategoria' => $nombreCategoria,
				'equipo' => $equipo [0],
				'fecha_activa' => $fecha_activa,
				'partidos' => $partidos,
				'horas_fecha' => $horas_fecha,
				'fecha_libre' => $fechaLibre,
				'reserva' => $reserva,
				'idReserva' => $idReserva,
				'detalleReserva' => $detalleReserva,
				'idsession' =>  $_SESSION ['equipoTorneo']
		));
	}
} else {
	$equipos = $oEquipo->getTorneoCat ( $idTorneoCat );
	if (isset($_POST['screen'])) {
		$twig->display ( 'reservasMobile.html', array (
				'torneo' => $torneo,
				'nombreCategoria' => $nombreCategoria,
				'equipos' => $equipos,
				'acceso' => $acceso
		));
	} else {
		$twig->display ( 'reservas.html', array (
				'torneo' => $torneo,
				'nombreCategoria' => $nombreCategoria,
				'equipos' => $equipos,
				'acceso' => $acceso
		));
	}
}
?>
<?
include_once "../model/jugadoras.php";
if (!isset( $_SESSION['usuario'])) {
	header("Location: index.php");
	exit;
}

$jugadorasNuevas = array();
$jugadorasExistentes = array();

foreach ( $_POST as $key => $value ) {
	$posNu = strpos ( $key, "idn" );
	if ($posNu !== false) {
		$nombre = "nombreyapellidon" . $value;
		$dni = "dnin" . $value;
		$numero = "nron" . $value;
		$facnac = "fecnan" . $value;
		$email = "emailn" . $value;
		$telefono = "telefonon" . $value;
		
		if ($_POST [$dni] == '') {
			$dniValue = '11111111';
		} else {
			$dniValue = $_POST [$dni];
		}
		
		$jugadorasNuevas [$value] = array (
				'nombre' => $_POST [$nombre],
				'dni' => $dniValue,
				'numero' => $_POST [$numero],
				'fecnac' => $_POST [$fecnac],
				'email' => $_POST [$email],
				'telefono' => $_POST [$telefono] 
		);
	}
	$posEx = strpos ( $key, "ide" );
	if ($posEx !== false) {
		$nombre = "nombreyapellido" . $value;
		$dni = "dni" . $value;
		$facnac = "fecnac" . $value;
		$email = "email" . $value;
		$telefono = "telefono" . $value;
		
		if ($_POST [$dni] == '') {
			$dniValue = '11111111';
		} else {
			$dniValue = $_POST [$dni];
		}
		
		$jugadorasExistentes [$value] = array (
				'id' => $value,
				'nombre' => $_POST [$nombre],
				'dni' => $dniValue,
				'fecnac' => $_POST [$fecnac],
				'email' => $_POST [$email],
				'telefono' => $_POST [$telefono] 
		);
	}
}

$oJugadora = new Jugadoras ();
$catidadActivas = $oJugadora->getCantidadActivaByEquipoTorneo($_POST["id"], $_POST["idTorneoCat"]);
$cantidadImportadas = $catidadActivas + 1;;
foreach ( $jugadorasNuevas as $jugadora ) {
	$oJugadora->set($jugadora);
	$oJugadora->insertar();	
	if ($cantidadImportadas < 14) {
		$activa = 1;
	} else {
		$activa = 0;
	}
	$asocEquipo = array (
			'id' => $oJugadora->id,
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'numero' => $jugadora ['numero'],
			'activo' => $activa
	);
	$oJugadora->insertarequipo ($asocEquipo);	
	$cantidadImportadas++;
}

foreach ( $jugadorasExistentes as $jugadora ) {	
	$oJugadora->set($jugadora);
	$oJugadora->actualizar();
	if ($cantidadImportadas < 14) {
		$activa = 1;
	} else {
		$activa = 0;
	}
	$asocEquipo = array (
			'id' => $oJugadora->id,
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'activo' => $activa
	);
	$oJugadora->insertarequipo ($asocEquipo);
	$cantidadImportadas++;
}

?>
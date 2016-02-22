<?
include_once "../model/jugadoras.php";
$jugadorasNuevas = array();
$jugadorasExistentes = array();
foreach ( $_POST as $key => $value ) {
	$posNu = strpos ( $key, "idn" );
	if ($posNu !== false) {
		$nombre = "nombreyapellidon" . $value;
		$dni = "dnin" . $value;
		$facnac = "fecnan" . $value;
		$email = "emailn" . $value;
		$telefono = "telefonon" . $value;
		$jugadorasNuevas [$value] = array (
				'nombre' => $_POST [$nombre],
				'dni' => $_POST [$dni],
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
		$jugadorasExistentes [$value] = array (
				'id' => $value,
				'nombre' => $_POST [$nombre],
				'dni' => $_POST [$dni],
				'fecnac' => $_POST [$fecnac],
				'email' => $_POST [$email],
				'telefono' => $_POST [$telefono] 
		);
	}
}

foreach ( $jugadorasNuevas as $jugadora ) {
	$oJugadora = new Jugadoras ();
	$oJugadora->set($jugadora);
	$oJugadora->insertar(NULL);	
	$asocEquipo = array (
			'id' => $oJugadora->id,
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'idPosicion' => 2,
			'activo' => 1
	);
	$oJugadora->insertarequipo ($asocEquipo);
}

foreach ( $jugadorasExistentes as $jugadora ) {
	$oJugadora = new Jugadoras ();
	$oJugadora->set($jugadora);
	$oJugadora->actualizar(NULL);
	$asocEquipo = array (
			'id' => $oJugadora->id,
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'idPosicion' => 2,
			'activo' => 1
	);
	$oJugadora->insertarequipo ($asocEquipo);
}

?>
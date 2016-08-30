<?
include_once "../model/jugadoras.php";
if (!isset( $_SESSION['usuario'])) {
	header("Location: index.php");
	exit;
}

$oJugadora = new Jugadoras();
$jugadoras = $oJugadora->getByIdEquipoTorneo($_POST["idTorneoEquipoSeleccionado"]);

$cantidadImportadas = 1;
foreach ( $jugadoras as $jugadora ) {
	if ($cantidadImportadas < 14) {
		$activa = 1;
	} else {
		$activa = 0;
	}
	$asocEquipo = array (
			'id' => $jugadora['id'],
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'numero' => '',
			'activo' => $activa
	);
	$oJugadora->insertarequipo ($asocEquipo);
	$cantidadImportadas++;
}
?>
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
	$asocEquipo = array (
			'id' => $jugadora['id'],
			'idEquipoTorneo' => $_POST ['idTorneoEquipo'],
			'numero' => $jugadora['numero'],
			'activo' => $jugadora['activa'],
			'email' => $jugadora['envio']
	);
	$oJugadora->insertarequipo ($asocEquipo);
	$cantidadImportadas++;
}

$oEquipo = new Equipos();
$datosPass = $oEquipo->getPassword($_POST["idTorneoEquipoSeleccionado"]);
if ($datosPass != NULL) {
	$oEquipo->setPassword($_POST['idTorneoEquipo'],$datosPass[0]['idEquipo'],$datosPass[0]['password']);
}

?>
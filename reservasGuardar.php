<?  include_once "include/config.inc.php";
include_once "model/reservas.php";

if(!isset($_SESSION['equipo'])){
	header("Location: index.php");
	exit;
}

$reserva = new Reservas();
$valores["id_fecha"] = $_POST['idFecha'];
$valores["id_equipo"] = $_SESSION['equipo'];
$valores["observacion"] = $_POST['observacion'];

if ($valores["id_fecha"] != 0) {
	if ($_POST['fechaLibre'] == 1) {
		$valores["fecha_libre"] = 1;
		$reserva->set($valores);
		$reserva->insertar();
	} else {
		$valores["fecha_libre"] = 0;
		$reserva->set($valores);
		$reserva->insertar();
		if ($reserva->id != 0) {
			foreach($_POST['horas'] as $horas) {
				$valoresDetalle['id_reserva'] = $reserva->id;
				$valoresDetalle['id_horas_cancha'] = $horas;
				$reserva->insertarDetalleReserva($valoresDetalle);
			}
		}
	}
}

$acceso = "ok";
include_once "reservas.php";

?>
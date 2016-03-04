<?  include_once "include/templateEngine.inc.php";
	include_once "model/equipos.php";
	include_once 'model/torneos.php';
	include_once "model/torneos.categorias.php";
	
	session_start();
	if ($_POST['idEquipo'] != 0 && $_POST['password'] != "") {
		$oEquipos = new Equipos();
		$ids = explode("-",$_POST['idEquipo']);
		$ingresa = $oEquipos->accesoCorrecto($ids[0], $ids[1], $_POST['password']);
		if ($ingresa) {
			$_SESSION['equipo'] = $ids[0];
			$_SESSION['equipoTorneo'] = $ids[1];
			$acceso = "ok";
		} else {
			$acceso = "nok";
		}
	} else {
		$acceso = "nok";
	} 	
	
	include_once "reservas.php";
?>
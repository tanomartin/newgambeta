<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/correos.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";
	$idFecha = $_POST['id'];
	$oFecha = new Fechas();
	$fecha = $oFecha -> get($idFecha);
	$asunto = "Recordatorio Confirmacion Partido: ".$fecha[0]['nombre']." - ".$fecha[0]['torneo']." - ".$fecha[0]['categoria'];
	$cuerpo = $_POST['cuerpocorreo'];
	$equiposMail = unserialize(urldecode($_POST['equiposMail']));
	
	foreach($equiposMail as $equipo) {
		$idEquipo = $equipo['id_equipo'];
		$email = $equipo['email'];
		$seenvioanterior = $equipo['seenvio'];
		$nombre = $equipo['nombre'];
		if ((sizeof($equipo['email']) != 0) && (!$seenvioanterior ) && !array_key_exists($idEquipo,$_POST)) {
			foreach ($equipo['email'] as $email) {
				$valores = array('correo' => $email, 'cuerpo' => $cuerpo, 'equipoId' => $idEquipo, 'equipoNombre' => $nombre, 'asunto' => $asunto);
				$emailOb = new Correos($valores);
				$seEnvio += $emailOb->enviar();
			}
			if ($seEnvio > 0) {
				$emailOb -> cargarCorreo($idEquipo, $idFecha, 'c');
			}
		}
	}
	
?>
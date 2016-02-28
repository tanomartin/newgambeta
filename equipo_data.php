<?	include_once "include/config.inc.php";
	include_once "model/equipos.php";	
	
	$respuesta='<option value="0">- Equipo -</option>';
	if(isset($_POST['idTorneoCat'])) {
		$oEquipo = new Equipos();
		$aEquipos = $oEquipo->getByCategoria($_POST['idTorneoCat']);
		if ($aEquipos != NULL) {
			foreach ($aEquipos as $equipo) {
				$respuesta.="<option value=".$equipo["id"].">".$equipo["nombre"]."</option>";
			}
		}
		echo $respuesta;
	} else {
		echo $respuesta;
	}	

?>
	
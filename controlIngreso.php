<?  include_once "include/templateEngine.inc.php";
	include_once "model/equipos.php";
	include_once 'model/torneos.php';
	include_once "model/torneos.categorias.php";
	
	$idTorneo = $_POST ['idTorneo'];
	$idTorneoCat = $_POST ['idTorneoCat'];
	
	$oTorneo = new Torneos ();
	$atorneo = $oTorneo->get ( $idTorneo );
	$oTorneoCat = new TorneoCat ();
	$aTorneoCat = $oTorneoCat->getByTorneoSub ( $idTorneo );
	
	session_start();
	if ($_POST['idEquipo'] != 0 && $_POST['password'] != "") {
		$oEquipos = new Equipos();
		$ids = explode("-",$_POST['idEquipo']);
		$ingresa = $oEquipos->accesoCorrecto($ids[0], $ids[1], $_POST['password']);
		if ($ingresa) {
			$_SESSION['equipo'] =$ids[0];
			$_SESSION['equipoTorneo'] = $ids[1];
			$_SESSION['acceso'] = "ok";
		} else { 
			$_SESSION['acceso'] = "nok";
		}
	} else {
		$_SESSION['acceso'] = "nok";
	}	
	
	$index = 0;
	foreach ( $aTorneoCat as $categoria ) {
		$nombreCompleto = $oTorneoCat->getCategoriasCompletas ( $categoria ['id'] );
		$nombreCompleto = $nombreCompleto [0];
		if ($nombreCompleto ['nombreCatPagina'] == NULL) {
			$nombreCategoria = $nombreCompleto ['nombrePagina'];
		} else {
			$nombreCategoria = $nombreCompleto ['nombreCatPagina'] . " - " . $nombreCompleto ['nombrePagina'];
		}
		$aTorneoCat [$index] ['nombre'] = $nombreCategoria;
		if ($categoria ['id'] == $idTorneoCat) {
			$nombreCategoriaSelect = $nombreCategoria;
		}
		$index ++;
	}
	
	
	// Cargo la plantilla
	$twig->display ( 'torneos.html', array (
			'idTorneoCat' => $idTorneoCat,
			'torneo' => serialize ( $atorneo [0] ),
			'categorias' => serialize ( $aTorneoCat ),
			'nombreCategoria' => $nombreCategoriaSelect,
			'torneoObj' => $atorneo[0],
			'acceso' => $_SESSION['acceso']
	) );
?>
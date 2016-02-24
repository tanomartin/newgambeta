<?	include_once "include/config.inc.php";
	include_once "model/torneos.categorias.php";	
	
	$respuesta='<option value="0">- Categoria -</option>';
	if(isset($_POST['idTorneo'])) {
		$oTorneoCat = new TorneoCat();
		$aTorneoCat = $oTorneoCat->getByTorneoSub($_POST['idTorneo']);
		if ($aTorneoCat != NULL) {
			foreach ($aTorneoCat as $categoria) {
				$nombreCompleto = $oTorneoCat->getCategoriasCompletas($categoria['id']);
				$nombreCompleto = $nombreCompleto [0];
				if ($nombreCompleto ['nombreCatPagina'] == NULL) {
					$nombreCategoria = $nombreCompleto ['nombrePagina'];
				} else {
					$nombreCategoria = $nombreCompleto ['nombreCatPagina'] . " - " . $nombreCompleto ['nombrePagina'];
				}
				$respuesta.="<option value=".$categoria['id'].">".$nombreCategoria."</option>";
			}
		}
		echo $respuesta;
	} else {
		echo $respuesta;
	}	

?>
	
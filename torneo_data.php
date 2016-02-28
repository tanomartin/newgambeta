<?	include_once "include/config.inc.php";
	include_once "model/torneos.php";	
	
	$oTorneos = new Torneos();
	$aTorneos = $oTorneos->get();
	if ($aTorneos != NULL) {
		foreach ($aTorneos as $torneo) {
			$respuesta.="<div class='col-xs-6 col-sm-2'><a href='#' onclick='entrarTorneo(".$torneo['id'].")'><img src='logos/".$torneo['logoPrincipal']."'><p>".$torneo['nombre']."</p></a></div>";
		}
		echo $respuesta;
	} 
	
?>
	
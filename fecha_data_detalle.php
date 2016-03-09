<?
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "model/fixture.php";
include_once "model/equipos.php";
include_once "model/jugadoras.php";
include_once "model/resultados.php";

if (isset ($_POST ['idPartido'])) {
	$oFixture = new Fixture ();
	$partido = $oFixture->get ( $_POST ['idPartido']);
	$oEquipos = new Equipos();
	$aEquipo1  = $oEquipos ->getByIdEquipoTorneo($partido[0]['idEquipoTorneo1']);
	$aEquipo2  = $oEquipos ->getByIdEquipoTorneo($partido[0]['idEquipoTorneo2']);
	
	$oJugadora = new Jugadoras();
	
	$aJugadoras1 = $oJugadora->getByEquipoTorneo($aEquipo1[0]["id"], $partido[0]["idTorneoCat"]);
	$aJugadoras2 = $oJugadora->getByEquipoTorneo($aEquipo2[0]["id"], $partido[0]["idTorneoCat"]);
	
	$oResultado = new Resultados();
	$resultados = $oResultado->get( $_POST ['idPartido']);
	if ($resultados) {
		foreach ($resultados as $key => $valores) {
			$aResultado [$valores['idJugadoraEquipo']] ['goles'] = ($valores ['goles'] > 0) ? $valores ['goles'] : '';
			$aResultado [$valores['idJugadoraEquipo']] ['amarilla'] = ($valores ['tarjeta_amarilla'] > 0) ? $valores ['tarjeta_amarilla'] : '';
			$aResultado [$valores['idJugadoraEquipo']] ['roja'] = ($valores ['tarjeta_roja'] > 0) ? $valores ['tarjeta_roja'] : '';
			$aResultado [$valores['idJugadoraEquipo']] ['mejor'] = $valores ['mejor_jugadora'];
		}
	}
	
	if ($partido != NULL) {
		if (isset($_POST['screen'])) {
			$resultado = "<div class='modal fade' id='myModalMobile".$partido[0]['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>";
		} else {
			$resultado = "<div class='modal fade' id='myModal".$partido[0]['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>";
		}
			$resultado .= "<div class='modal-dialog' role='document'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
										<h4 class='modal-title' id='myModalLabel'>Detalle del Partido</h4>
									</div>
									<div class='modal-body'>
										<table style='float: left;'>
											<thead style='background-color: ".$_POST ['color']."'>
												<tr>
													<th>".$aEquipo1[0]['nombre']."</th>
													<th><span class='ion-ios-football' style='padding:0px 10px'></span></th>
													<th><img src='imagenes/amarilla.png' style='padding:0px 10px'></th>
													<th><img src='imagenes/roja.png' style='padding:0px 10px;'></th>	
												</tr>
											</thead>
											<tbody> ";
							foreach($aJugadoras1 as $jugadora) {
								$resultado .= "<tr> 
													<td style='padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$jugadora['nombre']."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][goles]."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][amarilla]."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][roja]."</td>
												</tr>";
							}
							$resultado .= "</tbody>
										</table>
										<table style='float: right;'>
											<thead style='background-color: ".$_POST ['color']."'>
												<tr>
													<th>".$aEquipo2[0]['nombre']."</th>
													<th><span class='ion-ios-football' style='padding:0px 10px'></span></th>
													<th><img src='imagenes/amarilla.png' style='padding:0px 10px'></th>
													<th><img src='imagenes/roja.png' style='padding:0px 10px'></th>
												</tr>
											</thead>
										<tbody> ";
							foreach($aJugadoras2 as $jugadora) {
								$resultado .= "<tr>
													<td style='padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$jugadora['nombre']."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][goles]."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][amarilla]."</td>
													<td style='text-align:center;padding:4px 0px;border-bottom: solid 1px; border-bottom-color:".$_POST ['color']."'>".$aResultado[$jugadora[idJugadoraEquipo]][roja]."</td>
												</tr>";
							}
							$resultado .= "</tbody>
										</table>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' style='display:none;' data-dismiss='modal'>Cerrar</button>
									</div>
								</div>
							</div>
						</div>";
	}
}
echo $resultado;
?>
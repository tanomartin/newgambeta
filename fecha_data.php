<?php
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "model/fixture.php";

if (isset ($_POST ['idFecha']) &&  $_POST ['idFecha'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByFecha ( $_POST ['idFecha']);
	if ($partidos != NULL) {
		if (isset($_POST['screen'])) {
			$resultado = "<thead>
							<tr class='principal-table' style='background-color: ".$_POST ['color']."' >
								<th><span class='ion-ios-location'></span></th>
								<th><span class='ion-calendar'></span></th>
								<th><span class='ion-ios-people-outline'></span></th>
								<th><span class='ion-ios-people-outline'></span></th>
							</tr>
						 </thead>";
		} else {
			$resultado = "<thead>
							<tr class='principal-table' style='background-color: ".$_POST ['color']."' >
								<th><span class='ion-pound'></span></th>
								<th><span class='ion-ios-location'></span></th>
								<th><span class='ion-calendar'></span></th>
								<th><span class='ion-android-time'></span></th>
								<th><span class='ion-ios-people-outline'></span></th>
								<th colspan='2'><span class='ion-ios-football'></th>
								<th><span class='ion-ios-people-outline'></span></th>
								<th></th>
							</tr>
						 </thead>";
		}
		$resultado .= "<tbody>";
		$n = 1;
		foreach ( $partidos as $partido ) {
			if ($partido['golesEquipo1'] == -1) {
				$goles1 = "-";
			} else {
				$goles1 = $partido['golesEquipo1'];
			}
			if ($partido['golesEquipo2'] == -1) {
				$goles2 = "-";
			} else {
				$goles2 = $partido['golesEquipo2'];
			}
			if (isset($_POST['screen'])) {
				$resultado.= "<tr style='font-size: 16px'>
							  <td>".$partido['sede']."</td>
							  <td>".substr(cambiaf_a_normal($partido['fechaPartido']),0,2)."/".substr(cambiaf_a_normal($partido['fechaPartido']),3,2)." - ".$partido['horaPartido']."</td>
							  <td>".$partido['equipo1']."</td>
							  <td>".$partido['equipo2']."</td>
							  </tr>";
			} else {
				$resultado.= "<tr style='font-size: 16px'>
							  <td>".$n."</td>
							  <td>".$partido['sede']."</td>
							  <td>".cambiaf_a_normal($partido['fechaPartido'])."</td>
							  <td>".$partido['horaPartido']."</td>
							  <td>".$partido['equipo1']."</td>
							  <td>".$goles1."</td>
							  <td>".$goles2."</td>
							  <td>".$partido['equipo2']."</td>
							  <td><button onclick='detallepartido(".$partido['id'].")' style='background-color: ".$_POST ['color']."'><span title='Ver Detalle' class='ion-document-text'></span></button>
							  </tr>";
			}
			$n++;
		}
		$resultado .= "</tbody>";
	} else {
		$resultado.="<tbody><tr><td>No hay partidos para esta fecha</td></tr></tbody>";
	}
} else {
	$resultado.="<tbody><tr><td>Seleccione Fecha</td></tr></tbody>";
}
echo $resultado;
?>
<?php
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "model/fixture.php";

if (isset ($_POST ['idFecha']) &&  $_POST ['idFecha'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByFecha ( $_POST ['idFecha']);
	if ($partidos != NULL) {
		$resultado = "<thead>
						<tr class='principal-table' style='background-color: ".$_POST ['color']."'>
							<th colspan='8'>Si haces click en el partido podes ver el detalle</th>
						</tr>
						<tr class='principal-table' style='background-color: ".$_POST ['color']."' >
							<th><span class='ion-pound'></span></th>
							<th><span class='ion-ios-location'></span></th>
							<th><span class='ion-calendar'></span></th>
							<th><span class='ion-android-time'></span></th>
							<th><img src='imagenes/silbato.png'></th>
							<th>Local</th>
							<th>vs</th>
							<th>Visitante</th>
						</tr>
					 </thead>";
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
			$resultado.= "<tr style='font-size: 16px'>
							  <td>".$n."</td>
							  <td>".$partido['sede']."</td>
							  <td>".cambiaf_a_normal($partido['fechaPartido'])."</td>
							  <td>".$partido['horaPartido']."</td>
							  <td>".$partido['arbitro']."</td>
							  <td>".$partido['equipo1']."</td>
							  <td>vs</td>
							  <td>".$partido['equipo2']."</td>
						  </tr>";
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
<?php
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "model/fixture.php";

if (isset ($_POST ['idFecha']) &&  $_POST ['idFecha'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByFecha ( $_POST ['idFecha']);
	if ($partidos != NULL) {
		$resultado = "<thead>
						<th></th>
						<th>Sede</th>
						<th>C</th>
						<th>Dia</th>
						<th>Hora</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
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
			$resultado.= "<tr>
							  <td>".$n."</td>
							  <td>".$partido['sede']."</td>
							  <td>".$partido['cancha']."</td>
							  <td>".cambiaf_a_normal($partido['fechaPartido'])."</td>
							  <td>".$partido['horaPartido']."</td>
							  <td>".$partido['equipo1']."</td>
							  <td>".$goles1."</td>
							  <td>".$goles2."</td>
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
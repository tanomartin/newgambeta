<?
include_once "include/config.inc.php";
include_once "model/fixture.php";

if (isset ($_POST ['idEquipoTorneo']) &&  $_POST ['idEquipoTorneo'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByEquipoTorneo ( $_POST ['idTorneoCat'], $_POST ['idEquipoTorneo'] );
	if ($partidos != NULL) {
		$resultado = "<tbody>";
		$resultado .= "<tr class='principal-table' style='background-color: ".$_POST ['color']."'>
						<th colspan='8'>Si haces click en el partido podes ver el detalle</th>
						</tr>";
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
							  <td width='40%'>".$partido['equipo1']."</td>
							  <td width='10%'>".$goles1."</td>
							  <td width='10%'>".$goles2."</td>
							  <td width='40%'>".$partido['equipo2']."</td>
						  </tr>";
		}
		$resultado .= "</tbody>";
	} else {
		$resultado.="<tbody><tr><td>No hay partidos para este equipo</td></tr></tbody>";
	}
} else {
	$resultado.="<tbody><tr><td>Seleccione Equipo</td></tr></tbody>";
}
echo $resultado;

?>
	
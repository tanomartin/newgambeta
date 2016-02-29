<?
include_once "include/config.inc.php";
include_once "model/fixture.php";

if (isset ($_POST ['idEquipoTorneo']) &&  $_POST ['idEquipoTorneo'] != 0) {
	$oFixture = new Fixture ();
	$partidos = $oFixture->getByEquipoTorneo ( $_POST ['idTorneoCat'], $_POST ['idEquipoTorneo'] );
	if ($partidos != NULL) {
		$resultado = "<tbody>";
		foreach ( $partidos as $partido ) {
			$resultado.= "<tr>
							  <td width='40%'>".$partido['equipo1']."</td>
							  <td width='10%'>".$partido['golesEquipo1']."</td>
							  <td width='10%'>".$partido['golesEquipo2']."</td>
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
	
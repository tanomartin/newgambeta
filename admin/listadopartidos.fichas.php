<?
include_once "include/config.inc.php";
require_once "include/fpdf.php";
require_once "include/fechas.php";
include_once "../model/fixture.php";
include_once "../model/sedes.php";
include_once "../model/jugadoras.php";
include_once "../model/categorias.php";

if (!isset( $_SESSION['usuario'])) {
	header("Location: index.php");
	exit;
}

$fechaPartidosSql = $_GET ['fecha'];
$id_sede = $_GET ['sede'];

$oFixture = new Fixture ();
$listadoPartidos = $oFixture->getByFechaPartidoSede ( $fechaPartidosSql, $id_sede );
$oSede = new Sedes ();
$sede = $oSede->get ( $id_sede );
$oCetegoria = new Categorias ();
$oJugadora = new Jugadoras();
// 216 x 356 mm
$pdf = new FPDF ( 'L', 'mm', 'Legal' );
foreach ( $listadoPartidos as $partido ) {
	$pdf->AddPage ();
	$pdf->SetFont ( 'Arial', 'B', 14 );
	$pdf->SetXY ( 5, 5 );
	$pdf->Cell ( 80, 15, $partido['equipo1'], 0, 0 );
	$pdf->SetXY ( 89, 5 );
	$pdf->Cell ( 80, 15, "Pago: ", 1, 1 );
	$pdf->SetXY ( 185, 5 );
	$pdf->Cell ( 80, 15, $partido['equipo2'], 0, 0 );
	$pdf->SetXY ( 267, 5 );
	$pdf->Cell ( 80, 15, "Pago: ", 1, 1 );
	$pdf->SetXY ( 178, 5 );
	$pdf->Cell ( 1, 190, "", 1, 1, "", true );
	
	$pdf->SetFont ( 'Arial', 'B', 30 );
	$pdf->SetXY ( 35, 25 );
	$pdf->Cell ( 80, 15, "Gambeta Femenina" );
	$pdf->SetXY ( 215, 25 );
	$pdf->Cell ( 80, 15, "Gambeta Femenina" );
	
	
	if ($partido ['idzona'] != - 1 && $partido ['idzona'] != 0) {
		$categoria = $oCetegoria->get ( $partido ['idzona'] );
		$partido ['zona'] = " - " . $categoria [0] ['nombrePagina'];
	} else {
		$partido ['zona'] = "";
	}
	
	$pdf->SetFont ( 'Arial', 'B', 25 );
	$pdf->SetXY ( 15, 35 );
	$torneo = $partido ["torneo"] . $partido ["zona"]." - ".$partido ["categoria"];
	$pdf->Cell ( 80, 15, $torneo );
	$pdf->SetXY ( 200, 35 );
	$pdf->Cell ( 80, 15, $torneo );
	
	$pdf->SetFont ( 'Arial', 'B', 15 );
	$pdf->SetXY ( 5, 45 );
	$pdf->Cell ( 15, 15, cambiaf_a_normal ( $fechaPartidosSql )." - ".$partido ["horaPartido"]);
	$pdf->SetXY ( 185, 45 );
	$pdf->Cell ( 15, 15, cambiaf_a_normal ( $fechaPartidosSql )." - ".$partido ["horaPartido"]);
	
	$pdf->SetFont ( 'Arial', 'B', 15 );
	$pdf->SetXY ( 125, 45 );
	$pdf->Cell ( 15, 15, $partido ['nombreFecha'] );
	$pdf->SetXY ( 305, 45 );
	$pdf->Cell ( 15, 15, $partido ['nombreFecha'] );
	
	$pdf->SetFont ( 'Arial', 'B', 12 );
	$declaracion = "Declaramos que las personas que figuran en la presente planilla entienden que el";
	$pdf->SetXY ( 5, 55 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "f�tbol es un deporte de contacto f�sico, pudi�ndose generar lesiones de diversa";
	$pdf->SetXY ( 5, 60 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "�ndole,  y se hacen responsables por cualquier tipo de accidente o lesi�n que";
	$pdf->SetXY ( 5, 65 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "puedan sufrir como consecuencia de su pr�ctica en este torneo sin reclamo";
	$pdf->SetXY ( 5, 70 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "alguno a La Organizaci�n";
	$pdf->SetXY ( 5, 75 );
	$pdf->Cell ( 15, 15, $declaracion);
	
	$pdf->SetFont ( 'Arial', 'B', 12 );
	$declaracion = "Declaramos que las personas que figuran en la presente planilla entienden que el";
	$pdf->SetXY ( 185, 55 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "f�tbol es un deporte de contacto f�sico, pudi�ndose generar lesiones de diversa";
	$pdf->SetXY ( 185, 60 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "�ndole,  y se hacen responsables por cualquier tipo de accidente o lesi�n que";
	$pdf->SetXY (185, 65 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "puedan sufrir como consecuencia de su pr�ctica en este torneo sin reclamo";
	$pdf->SetXY (185, 70 );
	$pdf->Cell ( 15, 15, $declaracion);
	$declaracion = "alguno a La Organizaci�n";
	$pdf->SetXY (185, 75 );
	$pdf->Cell ( 15, 15, $declaracion);

	$pdf->SetFont ( 'Arial', 'B', 10 );
	$pdf->SetXY (5, 90 );
	$pdf->Cell (77, 7, utf8_decode($partido['equipo1']),1,1,"C");
	$pdf->SetXY (82, 90 );
	$pdf->Cell (13, 7, "VS",1,1,"C");
	$pdf->SetXY (95, 90 );
	$pdf->Cell (77, 7, utf8_decode($partido['equipo2']),1,1,"C");
	
	$pdf->SetXY (185, 90 );
	$pdf->Cell (77, 7, utf8_decode($partido['equipo1']),1,1,"C");
	$pdf->SetXY (262, 90 );
	$pdf->Cell (13, 7, "VS",1,1,"C");
	$pdf->SetXY (275, 90 );
	$pdf->Cell (77, 7, utf8_decode($partido['equipo2']),1,1,"C");

	$referentes1 = $oJugadora->getReferentesByIdEquipoTorneo($partido['idEquipoTorneo1']);
	$pdf->SetFont ( 'Arial', 'B', 12 );
	$pdf->SetXY (5, 98 );
	$pdf->Cell (13, 7, "Capitana: ".utf8_decode($referentes1[0]['nombre'])."   ".$referentes1[0]['telefono']);
	$pdf->SetXY (5, 104 );
	$pdf->Cell (13, 7, "Subcapitana: ".utf8_decode($referentes1[1]['nombre'])."    ".$referentes1[1]['telefono']);
	$pdf->SetXY (5, 110 );
	$pdf->Cell (13, 7, "DT: ".utf8_decode($partido['dt1'])."    ".$partido['dttelefono1']);
	$pdf->SetXY (143, 110 );
	$pdf->Cell (13, 7, "Jugadoras: ".$oJugadora->getActivasByIdEquipoTorneo($partido['idEquipoTorneo1']));
	
	$referentes2 = $oJugadora->getReferentesByIdEquipoTorneo($partido['idEquipoTorneo2']);
	$pdf->SetXY (185, 98 );
	$pdf->Cell (13, 7, "Capitana: ".utf8_decode($referentes2[0]['nombre'])."   ".$referentes2[0]['telefono']);
	$pdf->SetXY (185, 104 );
	$pdf->Cell (13, 7, "Subcapitana: ".utf8_decode($referentes2[1]['nombre'])."   ".$referentes2[1]['telefono']);
	$pdf->SetXY (185, 110 );
	$pdf->Cell (13, 7, "DT: ".utf8_decode($partido['dt2'])."    ".$partido['dttelefono2']);
	$pdf->SetXY (323, 110 );
	$pdf->Cell (13, 7, "Jugadoras: ".$oJugadora->getActivasByIdEquipoTorneo($partido['idEquipoTorneo2']));
	
	$pdf->SetXY(5, 118);
	$pdf->Cell(80, 7, "NOMBRE",1,1,"C");
	$pdf->SetXY(85, 118);
	$pdf->Cell(30, 7, "DNI",1,1,"C");
	$pdf->SetXY(115, 118);
	$pdf->Cell(10, 7, "N�",1,1,"C");
	$pdf->SetXY(125, 118);
	$pdf->Cell(37, 7, "TELEFONO",1,1,"C");
	$pdf->SetXY(162, 118);
	$pdf->Cell(10, 7, "G",1,1,"C");

	$pdf->SetXY(185, 118);
	$pdf->Cell(80, 7, "NOMBRE",1,1,"C");
	$pdf->SetXY(265, 118);
	$pdf->Cell(30, 7, "DNI",1,1,"C");
	$pdf->SetXY(295, 118);
	$pdf->Cell(10, 7, "N�",1,1,"C");
	$pdf->SetXY(305, 118);
	$pdf->Cell(37, 7, "TELEFONO",1,1,"C");
	$pdf->SetXY(342, 118);
	$pdf->Cell(10, 7, "G",1,1,"C");
	
	$pdf->SetFont ( 'Arial', 'B', 10 );
	$fila = 125;
	for ($i=0; $i<13; $i++) {
		$pdf->SetXY(5, $fila);
		$pdf->Cell(80, 5, "",1,1,"C");
		$pdf->SetXY(85, $fila);
		$pdf->Cell(30, 5, "",1,1,"C");
		$pdf->SetXY(115, $fila);
		$pdf->Cell(10, 5, "",1,1,"C");
		$pdf->SetXY(125, $fila);
		$pdf->Cell(37, 5, "",1,1,"C");
		$pdf->SetXY(162, $fila);
		$pdf->Cell(10, 5, "",1,1,"C");
		$fila = $fila + 5;
	}
	
	$fila = 125;
	for ($i=0; $i<13; $i++) {
		$pdf->SetXY(185, $fila);
		$pdf->Cell(80, 5, "",1,1,"C");
		$pdf->SetXY(265, $fila);
		$pdf->Cell(30, 5, "",1,1,"C");
		$pdf->SetXY(295, $fila);
		$pdf->Cell(10, 5, "",1,1,"C");
		$pdf->SetXY(305, $fila);
		$pdf->Cell(37, 5, "",1,1,"C");
		$pdf->SetXY(342, $fila);
		$pdf->Cell(10, 5, "",1,1,"C");
		$fila = $fila + 5;
	}
	
	$fila = 125;
	$jugadoras = $oJugadora->getByIdEquipoTorneo($partido['idEquipoTorneo1']);
	if ($jugadoras != NULL) {
		foreach ($jugadoras as $jugadora) {
			if ($jugadora['activa'] == 1) {
				$pdf->SetXY(5, $fila);
				$pdf->Cell(80, 5, utf8_decode($jugadora['nombre']),1,1,"C");
				$pdf->SetXY(85, $fila);
				if ($jugadora['dni'] != '11111111') {
					$pdf->Cell(30, 5, $jugadora['dni'],1,1,"C");
				} else {
					$pdf->Cell(30, 5, "",1,1,"C");
				}
				$pdf->SetXY(115, $fila);
				$pdf->Cell(10, 5, $jugadora['numero'],1,1,"C");
				$pdf->SetXY(125, $fila);
				$pdf->Cell(37, 5, $jugadora['telefono'],1,1,"C");
				$pdf->SetXY(162, $fila);
				$pdf->Cell(10, 5, "",1,1,"C");
				$fila = $fila + 5;
			}
		}
	}
	
	$fila = 125;
	$jugadoras2 = $oJugadora->getByIdEquipoTorneo($partido['idEquipoTorneo2']);
	if ($jugadoras2 != NULL) {
		foreach ($jugadoras2 as $jugadora) {
			if ($jugadora['activa'] == 1) {
				$pdf->SetXY(185, $fila);
				$pdf->Cell(80, 5, utf8_decode($jugadora['nombre']),1,1,"C");
				$pdf->SetXY(265, $fila);
				if ($jugadora['dni'] != '11111111') {
					$pdf->Cell(30, 5, $jugadora['dni'],1,1,"C");
				} else {
					$pdf->Cell(30, 5, "",1,1,"C");
				}
				$pdf->SetXY(295, $fila);
				$pdf->Cell(10, 5, $jugadora['numero'],1,1,"C");
				$pdf->SetXY(305, $fila);
				$pdf->Cell(37, 5, $jugadora['telefono'],1,1,"C");
				$pdf->SetXY(342, $fila);
				$pdf->Cell(10, 5, "",1,1,"C");
				$fila = $fila + 5;
			}
		}
	}
}

$pdf->Output ();
exit ();

?>

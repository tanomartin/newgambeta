<?	include_once "include/config.inc.php";
	require_once "include/fpdf.php";
	include_once "../model/fixture.php";
	include_once "../model/sedes.php";
	if (! session_is_registered ( "usuario" )) {
		header ( "Location: index.php" );
		exit ();
	}
	
	$fechaPartidosSql = $_GET['fecha'];
	$id_sede = $_GET['sede'];
	
	
	$oFixture = new Fixture ();
	$listadoPartidos = $oFixture->getByFechaPartidoSede ( $fechaPartidosSql, $id_sede );
	$oSede = new Sedes ();
	$sede = $oSede->get ( $id_sede );
	
	
 	$pdf=new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $titulo = "Fichas de Partidos del ".$fechaPartidosSql." - Sede ".$sede[0]['nombre'];
    $pdf->Cell(40,10,$titulo);
    $pdf->Output();
	
	exit;	
	
?>

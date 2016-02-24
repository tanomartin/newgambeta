<?
include_once "include/config.inc.php";
require_once "include/PHPExcel/PHPExcel.php";
include_once "../model/torneos.php";
include_once "../model/torneos.categorias.php";
include_once "../model/equipos.php";
include_once "../model/jugadoras.php";

if (! isset ( $_SESSION ['usuario'] )) {
	header ( "Location: index.php" );
	exit ();
}

$oTorneo = new Torneos ( $_POST ['id'] );
$oTorCat = new TorneoCat ();
$aCategorias = $oTorCat->getByTorneoSub ( $_POST ['id'] );
$oEquipo = new Equipos ();
$oJugadoras = new Jugadoras ();

$excelName = "Facutracion de Torneo - " . $oTorneo->nombre . ".xls";
$index = 0;
$arrayFinal = array ();
if ($aCategorias != NULL) {
	foreach ( $aCategorias as $categoria ) {
		$nombreCompleto = "";
		$nombreCompleto = $oTorCat->getCategoriasCompletas ( $categoria ['id'] );
		$nombreCompleto = $nombreCompleto [0];
		if ($nombreCompleto ['nombreCatPagina'] == NULL) {
			$nombreTorneo = $nombreCompleto ['nombreTorneo'] . " - " . $nombreCompleto ['nombrePagina'];
		} else {
			$nombreTorneo = $nombreCompleto ['nombreTorneo'] . " [" . $nombreCompleto ['nombreCatPagina'] . " - " . $nombreCompleto ['nombrePagina'] . "]";
		}
		$equipos = $oEquipo->getByCategoria ( $categoria ['id'] );
		if ($equipos != NULL) {
			foreach ( $equipos as $equipo ) {
				$referentes = $oJugadoras->getReferentesByIdEquipoTorneo ( $equipo ['idEquipoTorneo'] );
				if ($referentes != NULL) {
					foreach ( $referentes as $jugadora ) {
						$arrayFinal [$index] = array (
								'torneo' => $nombreTorneo,
								'equipo' => $equipo ['nombre'],
								'referente' => $jugadora ['nombre'],
								'dni' => $jugadora ['dni'],
								'email' => $jugadora ['email'],
								'telefono' => $jugadora ['telefono'] 
						);
						$index++;
					}
				} else {
					$arrayFinal [$index] = array (
							'torneo' => $nombreTorneo,
							'equipo' => $equipo ['nombre'],
							'referente' => "",
							'dni' => "",
							'email' => "",
							'telefono' => "" 
					);
					$index++;
				}
			}
		}
	}
}

$objPHPExcel = new PHPExcel ();
$objPHPExcel->getProperties ()->setCreator ( "Codedrinks" )->// Nombre del autor
setLastModifiedBy ( "Codedrinks" )->// Ultimo usuario que lo modificó
setTitle ( "Reporte Excel con PHP y MySQL" )->// Titulo
setSubject ( "Reporte Excel con PHP y MySQL" )->// Asunto
setCategory ( "Reporte excel" ); // Categorias

$phpColor = new PHPExcel_Style_Color ();
$phpColor->setRGB ( 'FFFFFF' );

// HORAS EN PRIMERA LINEA

$columna = "A";
$celda = "A1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 30 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "TORNEO" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );
$columna = "B";
$celda = "B1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 30 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "EQUIPO" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );
$columna = "C";
$celda = "C1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 50 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "REFERENTE" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );
$columna = "D";
$celda = "D1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 15 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "DNI" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );
$columna = "E";
$celda = "E1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 30 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "MAIL" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );
$columna = "F";
$celda = "F1";
$objPHPExcel->getActiveSheet ()->getColumnDimension ( $columna )->setWidth ( 15 );
$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, "TELEFONO" );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFill ()->getStartColor ()->setRGB ( 'CE6C2B' );
$objPHPExcel->getActiveSheet ()->getStyle ( $celda )->getFont ()->setColor ( $phpColor );

$objPHPExcel->getActiveSheet ()->getStyle ( "A1:F1" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
// **********************************/

$i = 2;
foreach ( $arrayFinal as $linea ) {
	$columna = "A";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["torneo"] );
	$columna = "B";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["equipo"] );
	$columna = "C";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["referente"] );
	$columna = "D";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["dni"] );
	$columna = "E";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["email"]);
	$columna = "F";
	$celda = $columna . $i;
	$objPHPExcel->getActiveSheet ()->setCellValue ( $celda, $linea["telefono"] );
	$i ++;
}

$columnaFinal = "F";
$filaFinal = sizeof ($arrayFinal) + 1;
$celdaFinal = $columnaFinal . $filaFinal;
$objPHPExcel->getActiveSheet ()->getStyle ( "A1:$celdaFinal" )->getBorders ()->getAllBorders ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );

// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
header ( "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" );
header ( "Content-Disposition: attachment;filename=$excelName" );
header ( "Cache-Control: max-age=0" );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
exit ();

?>

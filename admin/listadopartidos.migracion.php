<?	include_once "include/config.inc.php";
	require_once "include/PHPExcel/PHPExcel.php";
	include_once "../model/sedes.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$oSede= new Sedes();
	$sede = $oSede->get($_POST['idSede']);
	$excelName = "Listado de Partidos del -".$_POST['fechaPartido']."- sede ".$sede[0]['nombre'].".xls";

	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->getProperties()->setCreator("Codedrinks") // Nombre del autor
    			->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
    			->setTitle("Reporte Excel con PHP y MySQL") // Titulo
    			->setSubject("Reporte Excel con PHP y MySQL") //Asunto
    			->setCategory("Reporte excel"); //Categorias
	
	
	$phpColor = new PHPExcel_Style_Color();
	$phpColor->setRGB('FFFFFF');
	
	//HORAS EN PRIMERA LINEA
	
	$columna = "A";
	$celda = "A1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Hora Partido");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	$columna = "B";
	$celda = "B1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(30);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Torneo");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	$columna = "C";
	$celda = "C1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(50);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Equipo 1 vs Equipo 2");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	$columna = "D";
	$celda = "D1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(30);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Cancha");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	$columna = "E";
	$celda = "E1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Juez");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	$columna = "F";
	$celda = "F1";
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(30);
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  "Confirmacion");
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);

	$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//**********************************/
	
	$listadoPartidos = unserialize(urldecode($_POST['listadoPartidos']));
	
	$i=2;
	foreach ($listadoPartidos as $partido) { 
		$columna = "A";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $partido["horaPartido"]);
		$columna = "B";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $partido["torneo"]."-".$partido["categoria"].$partido["zona"]);
		$columna = "C";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $partido["equipo1"]." <VS> ".$partido["equipo2"]);
		$columna = "D";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $partido["cancha"]);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$columna = "E";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  "");
		$columna = "F";
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $partido["confirmacion"]);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$i++;
	}

	$columnaFinal = "F"; 
	$filaFinal = sizeof($listadoPartidos)+1;
	$celdaFinal = $columnaFinal.$filaFinal;
	
	$objPHPExcel->getActiveSheet()->getStyle("A1:$celdaFinal")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	$celdaFinalpartido = "C".$filaFinal;
	$objPHPExcel->getActiveSheet()->getStyle("C1:$celdaFinalpartido")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment;filename=$excelName");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;	
	
	?>

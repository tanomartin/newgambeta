<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/reservas.php";
	include_once "../model/fixture.php";
	include_once "../model/equipos.php";
	require_once "include/PHPExcel/PHPExcel.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";
	
	$oFecha = new Fechas();
	$fecha = $oFecha -> get($_POST['id']);
	
	$oFixture = new Fixture();
	$partidos = $oFixture -> getByFecha($_POST['id']);
	
	$oEquipo = new Equipos();
	$equiposTorneo = $oEquipo -> getTorneoCat($fecha[0]['idTorneoCat']);
	
	$oReservas = new Reservas();
	$reservasLibres = $oReservas -> getReservaLibresByIdFecha($_POST['id']);
	$reservas = $oReservas -> getReservaByIdFecha($_POST['id']);
	
	$i = 0;
	foreach ($equiposTorneo as $equipo) {
		$tienePartido = false;
		$tieneLibre = false;
		$id = $equipo['id'];
		if ($partidos!= NULL){
			foreach ($partidos as $partido) {
				if ($id == $partido['idEquipo1'] || $id == $partido['idEquipo2']) {
					$tienePartido = true;	
				}
			}
		}
		if ($reservas != NULL) {
			foreach ($reservas as $reserva) {
				if ($id == $reserva['id_equipo'] && $reserva['fecha_libre'] != 0) {
					$tieneLibre = true;
				}
			}
		}
		if (!$tienePartido && !$tieneLibre){
			$equiposSinDefinir[$i] = array('nombre' => $equipo['nombre']);
			$i++;
		}
	}
	
	$cruce = array();
	foreach ($equiposTorneo as $equipo1) {
		foreach ($equiposTorneo as $equipo2) {
			$jugaron = $oFixture -> jugaronEnContra($equipo1['idEquipoTorneo'], $equipo2['idEquipoTorneo'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$juegaEstaFecha = $oFixture -> juegaEstaFecha($equipo1['idEquipoTorneo'], $equipo2['idEquipoTorneo'], $fecha[0]['idTorneoCat'], $_POST['id']);
			$id = $equipo1['idEquipoTorneo'].$equipo2['idEquipoTorneo'];
			if ($jugaron) {
				$cruce[$id] = "CCCCCC";
			}
			if ($juegaEstaFecha) {
				$cruce[$id] = "0000CC";
			}
			if ($jugaron && $juegaEstaFecha) {
				$cruce[$id] = "FF0000";
			}
		}
	}
	
	$excelName ="Cruces-".$fecha[0]['nombre']."-".$fecha[0]['torneo']."-".$fecha[0]['categoria'].".xls";
	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->getProperties()->setCreator("Codedrinks") // Nombre del autor
    			->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
    			->setTitle("Reporte Excel con PHP y MySQL") // Titulo
    			->setSubject("Reporte Excel con PHP y MySQL") //Asunto
    			->setDescription("Reporte de alumnos") //Descripción
    			->setKeywords("reporte alumnos carreras") //Etiquetas
    			->setCategory("Reporte excel"); //Categorias
	
	//CELDA DE EQUIPOS
	$celda = 'A1';
	$phpColor = new PHPExcel_Style_Color();
	$phpColor->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->setCellValue($celda,  'EQUIPOS');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	
	//EQUIPOS EN PRIMERA LINEA
	$i = 66;
	foreach($equiposTorneo as $equipo) {
		$columna = chr($i); 
		$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(20);
		$celda = $columna.'1';
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $equipo['nombre']);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
		$i++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("B1:$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//**********************************//
	
	//CRUCES
	$i = 2;
	foreach ($equiposTorneo as $equipo1) { 
		$c = 65;
		$columna = chr($c); 
		$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(20);
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $equipo1['nombre']);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
		foreach ($equiposTorneo as $equipo2) { 
			$c++;
			$columna = chr($c); 
			$celda = $columna.$i;
			if ($equipo1['idEquipoTorneo'] == $equipo2['idEquipoTorneo']) {
				$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
			} else { 
				$id = $equipo1['idEquipoTorneo'].$equipo2['idEquipoTorneo']; 
				if (array_key_exists($id,$cruce)) { 
					$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB($cruce[$id]);
				} 
			} 
			
		}
		$i++;
	}
	//*********************************//
	
	$objPHPExcel->getActiveSheet()->getStyle("A1:$celda")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment;filename=$excelName");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;	 
	
	?>


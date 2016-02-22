<?	include_once "include/config.inc.php";
	include_once "../model/fechas.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/reservas.php";
	include_once "../model/equipos.php";
	require_once "include/PHPExcel/PHPExcel.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$oReserva = new Reservas();
	$reservas = $oReserva -> getReservaByIdFecha($_POST['id']);
	
	$oFecha = new Fechas();
	$fecha = $oFecha -> get($_POST['id']);
	$horasFecha = $oFecha -> getHorasCancha($_POST['id']);
	
	$oEquipo = new Equipos();
	$equiposTorneo = $oEquipo -> getTorneoCat($fecha[0]['idTorneoCat']);

	$equiposConReserva = array();
	$equiposSinReserva = array();
	foreach($equiposTorneo as $equipo) {
		$tiene = 0;
		$tuvo_libre = $oEquipo-> tieneFechaLibre($fecha[0]['idTorneoCat'], $equipo['id']);
		if ($reservas != NULL) {
			foreach($reservas as $reserva) {
				if ($reserva['id_equipo'] == $equipo['id']) {
					$detalle = $oReserva -> getDetalleReservaById($reserva['id_reserva']);
					$r = $reserva['id_equipo'];
					$equiposConReserva[$r] = array('id_reserva' => $reserva['id_reserva'],'id_equipo' => $reserva['id_equipo'], 'nombre' => $reserva['nombre'], 'fecha_libre' => $reserva['fecha_libre'], 'observacion' =>  $reserva['observacion'] ,'tuvo_libre' => $tuvo_libre, 'detalle' => $detalle);
					$tiene = 1;
				} 
			}
		}
		if ($tiene == 0) {
			$s = $equipo['id'];
			$equiposSinReserva[$s] = array('id_equipo' => $equipo['id'], 'nombre' => $equipo['nombre'], 'tuvo_libre' => $tuvo_libre);
		}
	}
	
	$excelName = "Reservas-".$fecha[0]['nombre']."-".$fecha[0]['torneo']."-".$fecha[0]['categoria'].".xls";

	$objPHPExcel = new PHPExcel();
   	$objPHPExcel->getProperties()->setCreator("Codedrinks") // Nombre del autor
    			->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
    			->setTitle("Reporte Excel con PHP y MySQL") // Titulo
    			->setSubject("Reporte Excel con PHP y MySQL") //Asunto
    			->setDescription("Reporte de alumnos") //Descripción
    			->setKeywords("reporte alumnos carreras") //Etiquetas
    			->setCategory("Reporte excel"); //Categorias
	
	
	$phpColor = new PHPExcel_Style_Color();
	$phpColor->setRGB('FFFFFF');
	$celda = 'A1';
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
	$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
	//HORAS EN PRIMERA LINEA
	$i = 66;
	foreach($horasFecha as $horas) {
		$columna = chr($i); 
		$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(15);
		$celda = $columna.'1';
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $horas['descripcion']);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
		$i++;
	}
	$objPHPExcel->getActiveSheet()->getStyle("B1:$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//**********************************//
	
	$i = 2;
	foreach ($equiposTorneo as $equipo) { 
		$c = 65;
		$id = $equipo['id'];
		$columna = chr($c); 
		$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(20);
		$celda = $columna.$i;
		$objPHPExcel->getActiveSheet()->setCellValue($celda,  $equipo['nombre']);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFill()->getStartColor()->setRGB('CE6C2B');
		$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColor);
		if (array_key_exists($id,$equiposConReserva)) { 
			$reserva = $equiposConReserva[$id];
			if ($reserva['fecha_libre'] == 1) {
				$c++;
				$columna = chr($c); 
				$celda = $columna.$i;
				$cfinal = $c + sizeof($horasFecha) - 1;
				$columnaFinal = chr($cfinal);
				$celdaFinal =  $columnaFinal.$i;
				$objPHPExcel->getActiveSheet()->mergeCells("$celda:$celdaFinal");	
				$objPHPExcel->getActiveSheet()->setCellValue($celda,  "FECHA LIBRE EQUIPO");
				$objPHPExcel->getActiveSheet()->getStyle("$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$phpColorFLEquipo = new PHPExcel_Style_Color();
				$phpColorFLEquipo->setRGB('000099');
				$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColorFLEquipo);
			}
			if ($reserva['fecha_libre'] == 2) {
				$c++;
				$columna = chr($c); 
				$celda = $columna.$i;
				$cfinal = $c + sizeof($horasFecha) - 1;
				$columnaFinal = chr($cfinal);
				$celdaFinal =  $columnaFinal.$i;
				$objPHPExcel->getActiveSheet()->mergeCells("$celda:$celdaFinal");	
				$objPHPExcel->getActiveSheet()->setCellValue($celda,  "FECHA LIBRE GAMBETA");
				$objPHPExcel->getActiveSheet()->getStyle("$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$phpColorFLGambeta = new PHPExcel_Style_Color();
				$phpColorFLGambeta->setRGB('FF6699');
				$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColorFLGambeta);
			}
			if ($reserva['fecha_libre'] == 0) { 
				if (sizeof($reserva['detalle']) != 0) {
					foreach ($horasFecha as $horas) {
						$c++;
						$columna = chr($c); 
						$celda = $columna.$i;
						$detalle = $reserva['detalle'];
						$marca = false;
						foreach($detalle as $horasreservada) {
							if ($horasreservada['id_horas_cancha'] == $horas['id_horas_cancha']) {
								$marca = true;
							}
						}
						if ($marca) {
							$objPHPExcel->getActiveSheet()->setCellValue($celda,  "X");
							$objPHPExcel->getActiveSheet()->getStyle("$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						} 
					}
				} else {
					$c++;
					$columna = chr($c); 
					$celda = $columna.$i;
					$cfinal = $c + sizeof($horasFecha) - 1;
					$columnaFinal = chr($cfinal);
					$celdaFinal =  $columnaFinal.$i;
					$objPHPExcel->getActiveSheet()->mergeCells("$celda:$celdaFinal");	
					$objPHPExcel->getActiveSheet()->setCellValue($celda,  "ERROR AL GRABAR DETALLE");
					$objPHPExcel->getActiveSheet()->getStyle("$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$phpColorFLGambeta = new PHPExcel_Style_Color();
					$phpColorFLGambeta->setRGB('FF0000');
					$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColorFLGambeta);
				}
			}
	
		} else {
			$c++;
			$columna = chr($c); 
			$celda = $columna.$i;
			$cfinal = $c + sizeof($horasFecha) - 1;
			$columnaFinal = chr($cfinal);
			$celdaFinal =  $columnaFinal.$i;
			$objPHPExcel->getActiveSheet()->mergeCells("$celda:$celdaFinal");	
			$objPHPExcel->getActiveSheet()->setCellValue($celda,  "SIN RESERVA");
			$objPHPExcel->getActiveSheet()->getStyle("B1:$celda")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpColorSR = new PHPExcel_Style_Color();
			$phpColorSR->setRGB('FF0000');
			$objPHPExcel->getActiveSheet()->getStyle($celda)->getFont()->setColor($phpColorSR);
		}
		$i++;
	}
	
	foreach ($equiposConReserva as $reserva) { 
		if ($reserva['observacion'] != "") {
				$c = 65;
				$columna = chr($c);
				$celda = $columna.$i;
				$cfinal = $c + sizeof($horasFecha);
				$columnaFinal = chr($cfinal);
				$celdaFinal =  $columnaFinal.$i;
				$objPHPExcel->getActiveSheet()->mergeCells("$celda:$celdaFinal");	
				$objPHPExcel->getActiveSheet()->setCellValue($celda, $reserva['nombre'].": ".$reserva['observacion']);
				$i++;
		}
	}
	
	$c = 65;
	$letraFinal = $c + sizeof($horasFecha);
	$columnaFinal = chr($letraFinal); 
	$filaFinal = $i-1;
	$celdaFinal = $columnaFinal.$filaFinal;
	
	$objPHPExcel->getActiveSheet()->getStyle("A1:$celdaFinal")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment;filename=$excelName");
	header("Cache-Control: max-age=0");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;	 
	
	?>

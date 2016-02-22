<?	
	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/fechas.php";
	include_once "../model/reservas.php";
	include_once "../model/fixture.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/jugadoras.php";	

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$oEquipo= new Equipos();
	$equipo = $oEquipo->get($_POST["id"]); 
	$datosTorneo = $oEquipo->getRelacionTorneo($_POST["idTorneoEquipo"]);	
	$oFechas = new Fechas();
	$fechas = $oFechas->getIdTorneoCat($_POST["idTorneoCat"],'fechaIni');
	$oFixture = new Fixture();

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<title>Panel de Control</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="description" content="Panel de Control."/>
	<meta name="keywords" content=""/>
	<meta name="robots" content="index,follow"/>
	
	<? include("encabezado.php"); ?>
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script>
			
		function volver(){
			document.frm_listado.accion.value = "torneos";
			document.frm_listado.submit();
		}

	</script>
</head>

<body id="top" class="home">
	<div id="wrapper">
		<div id="header">
			<div class="inside">
			<? include("top_menu.php"); ?>
			<div id="logo">
				<h1><a href="index.php" title="Volver al incio"> Panel de Control</a></h1>
			</div>
			<? include("menu.php");?>
			</div>
		</div>
		
		<div id="container">
		    <div id="main">    
		        <div class="inside">
		            <? include("path.php"); ?>
					<div class="mod_article block" id="register">
						<div class="ce_text block">
							<h1>Historial de <font color="#e4790f"><?=$equipo[0]['nombre']." [".$datosTorneo[0]['torneo'] ." - ".$datosTorneo[0]['categoria']."]" ?></font></h1>
						</div>
						<div class="mod_listing ce_table listing block" id="partnerlist">
							<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="idTorneoEquipo" value="<?=$_POST["idTorneoEquipo"]?>" />
								<input type="hidden" name="idTorneoCat" value="<?=$_POST["idTorneoCat"]?>" />
								<input type="hidden" name="accion" value="" />
								<input type="hidden" name="idJugadoraEquipo" value="" />
				                <input type="hidden" name="activa" value="" />
								<input type="hidden" name="id" value="<?=$_POST["id"]?>" />
								<!-- Parametros menu -->
			        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
			                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
			                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
			                    <!--     -->
			                    <!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input name="femail" type="hidden"  value="<?=$_POST["femail"]?>"  />                           
				                <!-- Fin filtros -->
			                    
			                    <table style="width: 928px">
			                    	<tr>
										<th>Fecha</th>
										<th>Reserva</th>
										<th>Partido</th> 
										<th>W.O.?</th>   
										<th>Confirmo?</th>        
									</tr>
			                    	<? if (count($fechas) == 0) { ?>
									<tr>
											<td colspan="6" align="center">No hay fechas para mostrar</td>
								    </tr>
									<? } else { 
										 	$total = count($fechas);	
											$tt = $total - 1;
											for ( $i = 0; $i < $total; $i++ ) { ?>
												<tr>
							                     <td align="left"><?=$fechas[$i]["nombre"]?><br><?=cambiaf_a_normal($fechas[$i]["fechaIni"])." al ".cambiaf_a_normal($fechas[$i]["fechaFin"])?></td>
												 <td><? $oReserva = new Reservas();
														$reserva = $oReserva->getReservaByIdFechaIdEquipo($fechas[$i]["id"],$_POST["id"]);
												 		if ($reserva == NULL) { 
												 			echo ("SIN RESERVA");
												 		} else { 
													 		if ($reserva[0]['fecha_libre'] != 0) { 
													 			if ($reserva[0]['fecha_libre'] == 1) echo ("Fecha Libre Equipo"); 
													 		 	if ($reserva[0]['fecha_libre'] == 2) echo ("Fecha Libre Gambeta");
													 		} else {
																$detalleReserva = $oReserva->getDetalleReservaById($reserva[0]['id_reserva']);
																if ($detalleReserva == NULL) { 
																	echo "SIN DETALLE DE RESERVA"; 
																} else { 
																	foreach ($detalleReserva as $detalle) {
																		echo ("* ".$detalle['descripcion']."<br>");
																	}
																}
															}
														} ?>
												 </td>
												 <td><? $oFixture = new Fixture();
												 		$partido = $oFixture->getByFechaEquipo($fechas[$i]["id"], $_POST["idTorneoEquipo"]);
												 		if ($partido == NULL) {
															echo ("SIN PARTIDO");
														} else {
															echo ("<b>".$partido[0]['equipo1'] ."</b> (".$partido[0]['golesEquipo1'].") vs <b>".$partido[0]['equipo2']."</b> (".$partido[0]['golesEquipo2'].")");
															echo ("<br>");
															echo (cambiaf_a_normal($partido[0]['fechaPartido'])." | ".$partido[0]['horaPartido']. " | ".$partido[0]['arbitro']." | C: ".$partido[0]['cancha']);
														} ?></td>
												 <td><? if ($partido[0]['suspendido'] == 1) { echo "SI"; } else { echo "NO"; } ?></td>
												 <td><? $confirmado = $oFixture->partidoConfirmado($partido[0]['id'],$_POST["id"]);
														if ($confirmado) { echo "SI"; } else { echo "NO";} 
												 	  ?>
												 </td>
							 				    </tr>
										<? } 
										}?>
			                    
			                    </table>  
								<div class="submit_container">
		    						<input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
		    					</div>
	    					</form>
    					</div>
					</div>
				</div>
			</div>
		</div>
		<? include("pie.php")?>
	</div>
</body>

</html>
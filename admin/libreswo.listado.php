<?	include_once "include/config.inc.php";
	include_once "../model/equipos.php";
	include_once "../model/fechas.php";
	include_once "../model/reservas.php";
	include_once "../model/fixture.php";
	include_once "include/fechas.php";
	
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";

	$oFecha = new Fechas();
	$fechas = $oFecha -> getIdTorneoCat($_POST['id']);
	
	$oEquipo = new Equipos();
	$equipos = $oEquipo -> getTorneoCat($_POST['id']);
	
	$oFixture = new Fixture();
	$oReserva = new Reservas();
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
					<div class="mod_article block" id="home">
						<div class="ce_text block">
							<div class="mod_listing ce_table listing block" id="partnerlist">
		                        <form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
			                        <div class="formbody">
			                        	Nombre: <input name="fnombre" type="text" style="width:100px" value="<?=$_POST["fnombre"]?>"/>
			                        	<input class="submit" value="Buscar" type="submit" style="font-size:11px" />
			                          	<input class="submit" value="Limpiar" type="button" style="font-size:11px" onclick="javascript:limpiar('frm_busqueda'); document.frm_busqueda.submit();" />
			                        </div>
		                        </form>
                    			<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
				                    <input type="hidden" name="_pag" value="<?=$pag?>" />
				                    <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
				                    <input type="hidden" name="activo" value="" />
				                    <input type="hidden" name="pos" value="" />
				                    <input type="hidden" name="orden" value="" />
				                    <input type="hidden" name="accion" value="" />
				        			<!-- Parametros menu -->
				        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
				                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
				                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
				                    <!--     -->
				                    <!-- Filtros -->
				                    <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                    <!-- Fin filtros -->
									
									<div class="mod_listing ce_table listing block" id="partnerlist">
									<div align="center">
										<?  if ($equipos != NULL) { ?>
											<h1 align="left">Listado de Libres y W.O. <?=" - ".$fechas[0]['torneo']." - ".$fechas[0]['categoria']?></h1>
											<table id="cruces" style="font-size: 11px">
												<tr>
													<td style="background-color: #CE6C2B; color: #FFFFFF"><b>FECHA\EQUIPOS</b></td>
													<? foreach ($equipos as $equipo) {  ?>
														  <td style="background-color: #CE6C2B; color: #FFFFFF"><?=$equipo['nombre'] ?></td>
													<? } ?>
												</tr>
												<? foreach ($fechas as $fecha) { ?>
												   <tr>
														<td style="background-color: #CE6C2B; color: #FFFFFF"><?=$fecha['nombre']."<br> (".cambiaf_a_normal($fecha['fechaIni'])." al<br>".cambiaf_a_normal($fecha['fechaFin']).")" ?></td>
														<? foreach ( $equipos as $equipo ) { 
																$wo = $oFixture->tienewo($equipo['idEquipoTorneo'], $fecha['id']);
															 	if ($wo) { ?>
															 		<td>W.O.</td>	
													  		<?  } else { 
													  				$reserva = $oReserva->getReservaByIdFechaIdEquipo($fecha['id'], $equipo['id']);
													  				if ($reserva == null) {?>
													  					<td></td>	
													  			<?  } else {
													  					if ($reserva[0][fecha_libre] == 0) { ?>
													  					 	<td></td>
																    <?  } else { 
																    		if ($reserva[0][fecha_libre] == 1) { ?>
																				<td>Libre Equipo</td>	
																   		 <?	} else { ?>
																   		 		<td>Libre Gambeta</td>	
																   	    <? }
																    	}	
																    } ?>
													  		<?  }
															} ?>
												   </tr>
												<? } ?>
											</table>
											<? } ?>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<? include("pie.php")?>
	</div>
</body>
</html>
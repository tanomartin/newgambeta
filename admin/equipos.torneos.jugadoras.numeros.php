<?	
	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/jugadoras.php";	

	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	
	$oEquipo= new Equipos();
	$equipo = $oEquipo->get($_POST["id"]); 
	$datosTorneo = $oEquipo->getRelacionTorneo($_POST["idTorneoEquipo"]);	
	$oJugadora = new Jugadoras();
	$jugadoras = $oJugadora->getByEquipoTorneo($_POST["id"], $_POST["idTorneoCat"]);
	$catidadActivas = $oJugadora->getCantidadActivaByEquipoTorneo($_POST["id"], $_POST["idTorneoCat"]);
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

	<script>
			
		function volver(){
			document.frm_listado.accion.value = "jugadoras";
			document.frm_listado.submit();
		}

		function guardarNumeros(){
			document.frm_listado.accion.value = "guardarNumeros";
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
							<h1>Jugadoras de
							 <font color="#e4790f"><?=$equipo[0]['nombre']." [".$datosTorneo[0]['torneo'] ." - ".$datosTorneo[0]['categoria']."]" ?></font> - Activas: <font color="#e4790f"><?=$catidadActivas?></font></h1>
						</div>
						<div class="mod_listing ce_table listing block" id="partnerlist">
							<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="idTorneoEquipo" value="<?=$_POST["idTorneoEquipo"]?>" />
								<input type="hidden" name="idTorneoCat" value="<?=$_POST["idTorneoCat"]?>" />
								<input type="hidden" name="accion" value="" />
								<input type="hidden" name="idJugadoraEquipo" value="" />
				                <input type="hidden" name="activa" value="" />
				                <input type="hidden" name="envio" value="" />
								<input type="hidden" name="id" value="<?=$_POST["id"]?>" />
								<!-- Parametros menu -->
			        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
			                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
			                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
			                    <!--     -->
			                    <!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input type="hidden" name="femail"  value="<?=$_POST["femail"]?>"  />                           
				                <!-- Fin filtros -->
				                <table style="width: 400px;">
				                    	<tr>
				                    		<th>Nro</th>
											<th>Nombre</th>
										</tr>
				                    	<? if (count($jugadoras) == 0) { ?>
										<tr>
												<td colspan="9" align="center">Este equipo no tiene jugadoras asignadas</td>
									    </tr>
										<? } else { 
											 	$total = count($jugadoras);	
												$tt = $total - 1;
												for ( $i = 0; $i < $total; $i++ ) {
													$estadisticasJugadora =  $oJugadora->getEstadisticas($jugadoras[$i]["idJugadoraEquipo"]);?>
													<tr>
													 	<td style="text-align: center;"><input type="text" name="numero-<?=$jugadoras[$i]["idJugadoraEquipo"]?>" id="numero-<?=$jugadoras[$i]["idJugadoraEquipo"]?>" size="4" value="<?=$jugadoras[$i]["numero"]?>" /></td>
								                     	<td align="left"><?=$jugadoras[$i]["nombre"]?></td>
								 				    </tr>
											<? } 
											}?>
				                </table>  
								<div class="submit_container">
		    						<input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
		    						<input class="submit" type="button" value="Guardar" onclick="javascript:guardarNumeros();" />
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
<?	
	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/equipos.php";	
    include_once "../model/fckeditor.class.php" ;
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	
	$operacion = "Importar";
	
	$oEquipo= new Equipos();
	$equipo = $oEquipo->get($_POST["id"]);
	$datosTorneo = $oEquipo->getRelacionTorneo($_POST["idTorneoEquipo"]);
    $torneos = $oEquipo->getTorneos($_POST["id"]);	
    $oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();
	
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
	
	<script type="text/javascript" src="../include/js/jquery.js"></script>
	<script>
			
	function volver(){
		document.frm_listado.accion.value = "jugadoras";
		document.frm_listado.submit();
	}

	function importarJugadorasTorneo(idTorneoEquipo){
		document.frm_listado.accion.value = "guardarImportarTorneo";
		document.frm_listado.idTorneoEquipoSeleccionado.value = idTorneoEquipo;
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
						<h1>Importacion de Jugadoras de Torneo para
							 <font color="#e4790f"><?=$equipo[0]['nombre']." [".$datosTorneo[0]['torneo'] ." - ".$datosTorneo[0]['categoria']."]" ?></font></h1>
						</div>
						<div class="mod_listing ce_table listing block" id="partnerlist">
							<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="" />
								<input type="hidden" name="idTorneoEquipo" value="<?=$_POST["idTorneoEquipo"]?>" />
								<input type="hidden" name="idTorneoCat" value="<?=$_POST["idTorneoCat"]?>" />
								<input type="hidden" name="idTorneoEquipoSeleccionado" value="<?=$_POST["idTorneoEquipo"]?>" />
								<!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input type="hidden"  name="femail" value="<?=$_POST["femail"]?>"  />                           
				                <!-- Fin filtros -->
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->
			                    
			                    <table style="width: 928px">
			                    	<tr>
										<th>Torneo</th>
										<th>Categoria</th> 
										<th width="10%">Opciones</th>
									</tr>
			                    	<? if (count($torneos) <= 1) { ?>
									<tr>
										<td colspan="5" align="center">Este equipo no esta inscripto en ningun Torneos o solo esta inscripto en uno solo</td>
								    </tr>
									<? } else { 
										 	$total = count($torneos);	
											$tt = $total - 1;
											for ( $i = 0; $i < $total; $i++ ) { 
												if ($torneos[$i]['id'] != $datosTorneo[0]['id']) {?>
												<tr>
							                     <td align="left"><?=$torneos[$i]["torneo"]?></td>
												 <td align="left"><?=$torneos[$i]["categoria"]?></td>
							                     <td nowrap>
							                     	<a href="javascript:importarJugadorasTorneo(<?=$torneos[$i]['id']?>);"><img border="0" src="images/icono-up.gif" alt="importar" title="importar" /></a>
							                     </td>
							 				    </tr>
											<? } 
											}
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
<?
include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "../model/equipoideal.php";
include_once "../model/torneos.categorias.php";
if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}
$menu = "Gestion";
$total = 0;
$oObj = new Equipoideal ();
$datos = $oObj->getByIdTorneoCat ( $_POST ['id'] );
$oObj = new TorneoCat ();
$datos1 = $oObj->getByIdCompleto ( $_POST ['id'] );
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<title>Panel de Control</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="Panel de Control." />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />	
	<? include("encabezado.php"); ?>
	<script>
	
		function nuevaJugadora(){
			document.frm_listado.accion.value = "editar";
			document.frm_listado.submit();
		}
	
		function volver(){
			document.frm_listado.accion.value = "";
			document.frm_listado.submit();
		}
		
		function borrar(id){
			document.frm_listado.idPosicion.value = id;
			document.frm_listado.accion.value = "borrar";
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
					<h1>
						<a href="index.php" title="Volver al incio"> Panel de Control</a>
					</h1>
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
								<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
									<input type="hidden" name="_pag" value="<?=$pag?>" /> 
									<input type="hidden" name="id" value="<?=$_POST["id"]?>" /> 
									<input type="hidden" name="idPosicion" value="<?=$_POST["idPosicion"]?>" /> <input type="hidden" name="idEquipo" value="<?=$_POST["idEquipo"]?>" /> 
									<input type="hidden" name="idJugadora" value="<?=$_POST["idJugadora"]?>" /> <input type="hidden" name="activo" value="" /> 
									<input type="hidden" name="pos" value="" /> <input type="hidden" name="orden" value="" /> 
									<input type="hidden" name="accion" value="" />
									<!-- Parametros menu -->
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
									<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
									<!--     -->
									<!-- Filtros -->
									<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" /> 
									<input name="ftorneo" type="hidden" style="width: 100px" value="<?=$_POST["ftorneo"]?>" /> 
									<input name="fcategoria" type="hidden" style="width: 100px" value="<?=$_POST["fcategoria"]?>" />
									<!-- Fin filtros -->
									<div style="margin-left: 20px; float: left">
										<b>Torneo:</b> <?= $datos1 ->torneo ?> - <b>Categor&iacute;a:</b> <?= $datos1 ->nombrePagina ?> <? if ( $datos1 ->nombreCatPagina != "") { echo " - ". $datos1 ->nombreCatPagina; } ?>			  
									</div>
									<div align="right" style="margin-right: 10px; margin-bottom: 10px">
										<input class="button" onclick="javascript:nuevaJugadora()" type="button" value="Nueva Jugadora" />
									</div>
									<table style="width: 928px">
										<tr>
											<th width="15%">Equipo</th>
											<th width="15%">Jugadora</th>
											<th width="15%">Posici&oacute;n</th>
											<th width="1%">Opciones</th>
										</tr>
										<? if (count($datos) == 0) { ?>
											<tr><td colspan="7" align="center">No existen jugadoras para el equipo ideal</td></tr>
										<? } else {
												$total = count ( $datos );
												for($i = 0; $i < $total; $i ++) { ?>
													<tr>
														<td align="left"><?=$datos[$i]["equipo"]?></td>
														<td align="left"><?=$datos[$i]["jugadora"]?></td>
														<td align="left"><?=$datos[$i]["posicion"]?></td>
														<td nowrap>
															<a href="javascript:borrar(<?=$datos[$i]["id"]?>);">
																<img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" />
															</a>
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
		</div>
		<? include("pie.php")?>
	</div>
</body>
</html>
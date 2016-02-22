<?

include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "../model/fechas.php";
include_once "../model/reservas.php";
include_once "../model/fixture.php";
include_once "../model/equipos.php";
include_once "../model/torneos.categorias.php";

if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}

$menu = "Secciones";

switch ($_POST ["accion"]) {
	
	case "eliminarEnvio" :
		$idFecha = $_POST ['id'];
		$idEquipo = $_POST ['id_equipo'];
		$equipoOb = new Equipos ();
		$equipoOb->eliminarCorreo ( $idEquipo, $idFecha, 'c' );
		include ("confirmaciones.mail.php");
		exit ();
		break;
	
	case "enviarcorreo" :
		include ("confirmaciones.enviarcorreo.php");
		include ("confirmaciones.mail.php");
		exit ();
		break;
	
	case "mail" :
		include ("confirmaciones.mail.php");
		exit ();
		break;
	
	case "migrar" :
		include ("confirmaciones.migracion.php");
		exit ();
		break;
	
	case "confirmaciones" :
		include ("confirmaciones.listado.php");
		exit ();
		break;
	
	case "confirmar" :
		$idPartido = $_POST ['id_partido'];
		$idEquipo = $_POST ['id_equipo'];
		$oFixture = new Fixture ();
		$oFixture->confirmarPartido ( $idPartido, $idEquipo );
		include ("confirmaciones.listado.php");
		exit ();
		break;
	
	case "eliminar" :
		$idPartido = $_POST ['id_partido'];
		$idEquipo = $_POST ['id_equipo'];
		$oFixture = new Fixture ();
		$oFixture->eliminarcConfirmacionPartido ( $idPartido, $idEquipo );
		include ("confirmaciones.listado.php");
		exit ();
		break;
}

// Paginacion
$cant = 10;
$pag = ($_POST ['_pag'] > 0) ? $_POST ['_pag'] : 1;
$inicio = ($pag - 1) * $cant;
$fin = $inicio + $cant;
// fin Paginacion

$total = 0;
$oObj = new Fechas();
$datos = $oObj->getPaginado( $_REQUEST, $inicio, $cant, $total );
$esultimo = (count ( $datos ) == 1) ? "S" : "N";

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
	
		function verConfirmaciones(id){
			document.frm_listado.accion.value = "confirmaciones";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
	
		function cambiarActivo(id,activo) {
			document.frm_listado.activo.value=activo;
			document.frm_listado.id.value = id;
			document.frm_listado.accion.value = "cambiarActivo";
			document.frm_listado.submit();
		}
		
		function cambiarOrden(pos,orden) {
			document.frm_listado.pos.value=pos;
			document.frm_listado.orden.value =orden;
			document.frm_listado.accion.value = "cambiarOrden";
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
					<div class="mod_article block" id="home">
						<div class="ce_text block">
							<div class="mod_listing ce_table listing block" id="partnerlist">
								<div>
									<form name="frm_busqueda" id="frm_busqueda"
										action="<?=$_SERVER['PHP_SELF']?>" method="post">
										<!-- Parametros menu -->
										<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
										<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> 
										<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
										<!--     -->
										<div class="formbody">
											Nombre: <input name="fnombre" type="text" style="width: 100px" value="<?=$_POST["fnombre"]?>" />
											Torneo: <input name="ftorneo" type="text" style="width: 100px" value="<?=$_POST["ftorneo"]?>" />
											Categor&iacute;a: <input name="fcategoria" type="text" style="width: 100px" value="<?=$_POST["fcategoria"]?>" /> 
											<input class="submit" value="Buscar" type="submit" style="font-size: 11px" /> 
											<input class="submit" value="Limpiar" type="button" style="font-size: 11px" onclick="javascript:limpiar('frm_busqueda'); document.frm_busqueda.submit();" />
										</div>
									</form>
								</div>
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
									<input name="ftorneo" type="hidden" style="width: 100px" value="<?=$_POST["ftorneo"]?>" /> 
									<input name="fcategoria" type="hidden" style="width: 100px" value="<?=$_POST["fcategoria"]?>" />
									<!-- Fin filtros -->
									
									<div style="margin-left: 20px; float: left">
									  <? if ($total != 0) {	
											 if ( $pag > 1 ) {?>
											 	<a href="javascript: document.frm_listado._pag.value=<?=$pag-1?>;  document.frm_listado.submit();">
											 		<img src="images/icono-anterior-on.gif" alt="anterior" title="anterior" />
											 	</a>
										  <? } else {?>
											 	<img src="images/icono-anterior-off.gif" alt="anterior" title="anterior" />
										  <? }?>
										  <? if ( $pag < $total ) {?>						 
											 	<a href="javascript: document.frm_listado._pag.value=<?=$pag+1?>;  document.frm_listado.submit();">
											 		<img src="images/icono-siguiente-on.gif" alt="siguiente" title="siguiente" />
											 	</a>
										  <? } else {?>
											 	<img src="images/icono-siguiente-off.gif" alt="siguiente" title="siguiente" />
										  <? }?>										 									  
						               		 <span>P&aacute;gina <? echo $pag; ?> de <? echo $total; ?> &nbsp;&nbsp;N&uacute;mero de p&aacute;gina 
										    	<select style="width: 40px" name="nro_pag" id="nro_pag" onchange="document.frm_listado._pag.value=this.value;  document.frm_listado.submit();">
												  <? for($p = 1; $p <= $total; $p ++) { ?>
												 		<option <? if ($p == $pag) echo "selected"; ?> value="<?=$p?>"><?=$p?></option>
												  <? } ?>
										 		</select>
											 </span>
									<? } ?>							  
									</div>
									<div align="right" style="margin-right: 10px; margin-bottom: 10px">&nbsp;</div>
									<table style="width: 928px">
										<tr>
											<th>Nombre</th>
											<th>Fecha I.</th>
											<th>Fecha F.</th>
											<th width="15%">Torneo</th>
											<th width="15%">Categor&iacute;a</th>
											<th width="3%"></th>
										</tr>
										<? if (count($datos) == 0) { ?>	
											<tr><td colspan="6" align="center">No existen fechas</td></tr>
										<? } else {
												$total = count ( $datos );
												$tt = $total - 1;
												for($i = 0; $i < $total; $i ++) { ?>
													<tr>
														<td align="left"><?=$datos[$i]["nombre"]?></td>
														<td align="left"><?=cambiaf_a_normal($datos[$i]["fechaIni"])?></td>
														<td align="left"><?=cambiaf_a_normal($datos[$i]["fechaFin"])?></td>
														<td align="left"><?=$datos[$i]["torneo"]?></td>
														<td align="left"><?=$datos[$i]["categoria"]?></td>
														<td nowrap>
															<a href="javascript:verConfirmaciones(<?=$datos[$i]["id"]?>);">
																<img border="0" src="images/confirmacion-icon.png" alt="Confirmaciones" title="Confirmaciones" width="20px" height="20px" />
															</a>
														</td>
													</tr>
											   <? } 
										   } ?>
									</table>
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
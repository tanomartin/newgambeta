<?
	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/fixture.php";
	include_once "../model/resultados.php";
	include_once "../model/jugadoras.php";
	if (! session_is_registered ( "usuario" )) {
		header ( "Location: index.php" );
		exit ();
	}
	$menu = "Gestion";
	
	switch ($_POST ["accion"]) {
		
		case "editar" :
			include ("fixture.edit.php");
			exit ();
			break;
		
		case "ver" :
			include ("fixture.edit.php");
			exit ();
			break;
		
		case "resultado" :
			include ("fixture.resultado.php");
			exit ();
			break;
		
		case "guardar" :
			$data = $_POST;
			$files = $_FILES;
			$oObj = new Fixture ();
			$oObj->set ( $data );
			if ($_POST ["id"] == "-1") {
				$oObj->insertar ();
			} else {
				$oObj->actualizar ( $files );
			}
			break;
		
		case "guardarResultado" :
			$i = 0;
			foreach($_POST as $clave => $resul) {	
				$pos = strpos($clave, '_id1');
				if ($pos !== false) {
					$jugadoras1[$i] = $resul;
					$i++;
				}
				$pos = strpos($clave, '_id2');
				if ($pos !== false) {
					$jugadoras2[$i] = $resul;
					$i++;
				}
			}
			
			$golesT1 = 0;
			$golesT2 = 0;
			foreach($jugadoras1 as $jugadoraEquipo) {
				$idGoles = $jugadoraEquipo."_goles1";		
				$goles = ($_POST [$idGoles]) ? $_POST [$idGoles] : 0;
				$idTarAma = $jugadoraEquipo."_amarillas1";
				$amarillas = ($_POST [$idTarAma]) ? $_POST [$idTarAma] : 0;
				$idTarRoj = $jugadoraEquipo."_rojas1";
				$rojas = ($_POST [$idTarRoj]) ? $_POST [$idTarRoj] : 0;
				$mejor_jugadora = ( $jugadoraEquipo == $_POST['mejor_jugadora'])? 'S' : 'N';
				$valores[$jugadoraEquipo] = array('idFixture' => $_POST ['idFixture'], 
												  'idJugadoraEquipo' => $jugadoraEquipo, 
												  'goles' => (int)$goles,
												  'tarjeta_amarilla' => (int)$amarillas,
												  'tarjeta_roja' => (int)$rojas,
												  'mejor_jugadora' => $mejor_jugadora);
			
				$golesT1 += ($goles>0)?$goles:0;
			}
			foreach($jugadoras2 as $jugadoraEquipo) {	
				$idGoles = $jugadoraEquipo."_goles2";
				$goles = ($_POST [$idGoles]) ? $_POST [$idGoles] : 0;
				$idTarAma = $jugadoraEquipo."_amarillas2";
				$amarillas = ($_POST [$idTarAma]) ? $_POST [$idTarAma] : 0;
				$idTarRoj = $jugadoraEquipo."_rojas2";
				$rojas = ($_POST [$idTarRoj]) ? $_POST [$idTarRoj] : 0;
				$mejor_jugadora = ( $jugadoraEquipo == $_POST['mejor_jugadora'])? 'S' : 'N';
				$valores[$jugadoraEquipo] = array('idFixture' => $_POST ['idFixture'],
						'idJugadoraEquipo' => $jugadoraEquipo,
						'goles' => (int)$goles,
						'tarjeta_amarilla' => (int)$amarillas,
						'tarjeta_roja' => (int)$rojas,
						'mejor_jugadora' => $mejor_jugadora);
				
				$golesT2 += ($goles>0)?$goles:0;
			}
			$oObj = new Resultados();
			$oObj->borrarByIdFixture($_POST ['idFixture']);
			foreach ($valores as $valor) {
				$oObj->set($valor);
				$oObj->insertar();
			}	
			$oFix = new Fixture ();
			$oFix->modicarCampoValor ( "golesEquipo1", $golesT1, "id", $_POST['idFixture'] );
			$oFix->modicarCampoValor ( "puntajeEquipo1", $_POST ['puntajeEquipo1'], "id", $_POST['idFixture'] );
			$oFix->modicarCampoValor ( "golesEquipo2", $golesT2, "id", $_POST['idFixture'] );
			$oFix->modicarCampoValor ( "puntajeEquipo2", $_POST['puntajeEquipo2'], "id", $_POST['idFixture'] );
			break;
		
		case "borrar" :
			$data = $_POST;
			$oObj = new Resultados();
			$oObj->borrarByIdFixture($data['id']);
			$oObj = new Fixture();		
			$oObj->set($data);	
			$oObj->eliminar();
			$_POST ["_pag"] = ($_POST ["ult"] == "S") ? $_POST ["_pag"] - 1 : $_POST ["_pag"];
			break;
	}
	
	// Paginacion
	$cant = 10;
	$pag = ($_POST ['_pag'] > 0) ? $_POST ['_pag'] : 1;
	$inicio = ($pag - 1) * $cant;
	$fin = $inicio + $cant;
	// fin Paginacion
	
	$total = 0;
	$oObj = new Fixture ();
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
	
		function nuevo(){
			document.frm_listado.accion.value = "editar";
			document.frm_listado.id.value = "-1";
			document.frm_listado.submit();
			
		}
	
		function editar(id){	
			document.frm_listado.accion.value = "editar";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
			
		}
		
		function ver(id){	
			document.frm_listado.accion.value = "ver";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
			
		}
		
		function resultado(id){	
			document.frm_listado.accion.value = "resultado";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
			
		}
		
		function borrar(id){
			if (confirm('Confirma que quiere eliminar el partido')) {
				document.frm_listado.accion.value = "borrar";
				document.frm_listado.id.value = id;
				document.frm_listado.submit();
			}
			
		}
	
		function info(id){	
			document.frm_listado.accion.value = "info";
			document.frm_listado.id.value = id;
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
								<form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
									<!-- Parametros menu -->
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
									<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> 
									<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
									<!--     -->
									<div class="formbody">
										Nombre Equipo: <input name="fnombre" type="text" style="width: 100px" value="<?=$_POST["fnombre"]?>" />
										Torneo: <input name="ftorneo" type="text" style="width: 100px" value="<?=$_POST["ftorneo"]?>" />
										Categor&iacute;a: <input name="fcategoria" type="text" style="width: 100px" value="<?=$_POST["fcategoria"]?>" /> 
										<input class="submit" value="Buscar" type="submit" style="font-size: 11px" /> 
										<input class="submit" value="Limpiar" type="button" style="font-size: 11px" onclick="javascript:limpiar('frm_busqueda'); document.frm_busqueda.submit();" />
									</div>
								</form>

								<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
									<input type="hidden" name="_pag" value="<?=$pag?>" /> 
									<input type="hidden" name="id" value="<?=$_POST["id"]?>" /> 
									<input type="hidden" name="activo" value="" /> 
									<input type="hidden" name="pos" value="" /> 
									<input type="hidden" name="orden" value="" /> <input type="hidden" name="accion" value="" />
									<!-- Parametros menu -->
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
									<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> 
									<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
									<!--     -->
									<!-- Filtros -->
									<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" /> 
									<input name="ftorneo" type="hidden" value="<?=$_POST["ftorneo"]?>" /> 
									<input name="fcategoria" type="hidden" value="<?=$_POST["fcategoria"]?>" />
									<!-- Fin filtros -->
									<div style="margin-left: 20px; float: left">
										<? if ($total != 0) {	
												if ( $pag > 1 ) {?>
						 							<a	href="javascript: document.frm_listado._pag.value=<?=$pag-1?>;  document.frm_listado.submit();">
						 								<img src="images/icono-anterior-on.gif" alt="anterior" title="anterior" />
						 							</a>
											 <? } else {?>
													<img src="images/icono-anterior-off.gif" alt="anterior" title="anterior" />
											 <? }
												if ( $pag < $total ) {?>						 
						 							<a href="javascript: document.frm_listado._pag.value=<?=$pag+1?>;  document.frm_listado.submit();">
						 								<img src="images/icono-siguiente-on.gif" alt="siguiente" title="siguiente" />
						 							</a>
											 <? } else { ?>
								 					<img src="images/icono-siguiente-off.gif" alt="siguiente" title="siguiente" />
											 <? } ?>					 									  
	               							 <span>P&aacute;gina <? echo $pag; ?> de <? echo $total; ?> &nbsp;&nbsp;N&uacute;mero de p&aacute;gina 
						   			 			 <select style="width: 40px" name="nro_pag" id="nro_pag" onchange="document.frm_listado._pag.value=this.value;  document.frm_listado.submit();">
						 						<? for($p = 1; $p <= $total; $p ++) { ?>
						 							<option <? if ($p == $pag) echo "selected"; ?> value="<?=$p?>"><?=$p?></option>
						 						<? } ?>
						 						</select>
											</span>
										<? } ?>							  
									</div>
									<div align="right" style="margin-right: 10px; margin-bottom: 10px">
										<input class="button" onclick="javascript:nuevo()" type="button" value="Nuevo Partido" />
									</div>
									<table style="width: 928px">
										<tr>
											<th width="15%">Torneo</th>
											<th width="15%">Categor&iacute;a</th>
											<th width="15%">Equipo 1</th>
											<th width="15%">Resultado</th>
											<th width="15%">Equipo 2</th>
											<th width="15%">Fecha</th>
											<th width="15%">Hora</th>
											<th width="10%">Opciones</th>
										</tr>
									 <? if (count($datos) == 0) { ?>
										<tr><td colspan="8" align="center">No existen fixture</td></tr>
									<? } else {
											$total = count ( $datos );
											$tt = $total - 1;
											for($i = 0; $i < $total; $i ++) { ?>
												<tr>
													<td align="left"><?=$datos[$i]["torneo"]?></td>
													<td align="left"><?=$datos[$i]["categoria"]?></td>
													<td align="left"><?=$datos[$i]["equipo1"]?></td>
													<? if ($datos[$i]["golesEquipo1"] != -1 and $datos[$i]["golesEquipo2"] != -1) { ?>
														<td style="text-align: center;"><?=$datos[$i]["golesEquipo1"]?> - <?=$datos[$i]["golesEquipo2"]?></td>
													<? } else { ?>
														<td style="text-align: center;">SR</td>
													<? } ?>
													<td align="left"><?=$datos[$i]["equipo2"]?></td>
													<td align="left"><?=cambiaf_a_normal($datos[$i]["fechaPartido"])?></td>
													<td align="left"><?=$datos[$i]["horaPartido"]?></td>
													<td nowrap>
														<a href="javascript:ver(<?=$datos[$i]["id"]?>);"><img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a> 
														<a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a> 
														<a href="javascript:borrar(<?=$datos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a> 
														<a href="javascript:resultado(<?=$datos[$i]["id"]?>);"><img border="0" src="images/infoLoc.jpg" alt="Detalle Resultado" title="Detalle Resultado" width="20px" height="20px" /></a>
													</td>
												</tr>
										  <? } 
										}?>
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
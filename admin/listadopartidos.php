<?

include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "../model/sedes.php";
include_once "../model/fixture.php";
include_once "../model/categorias.php";

if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}

$menu = "Secciones";

$fechaPartidos = $_POST ['fechaPartido'];
$id_sede = $_POST ['idSede'];
$fechaPartidosSql = cambiaf_a_mysql ( $fechaPartidos );

switch ($_POST ["accion"]) {
	case "migrar" :
		include ("listadopartidos.migracion.php");
		break;
}

$oSede = new Sedes ();
$aSedes = $oSede->get ();
$oCetegoria = new Categorias ();

$oFixture = new Fixture ();
$listadoPartidos = $oFixture->getByFechaPartidoSede ( $fechaPartidosSql, $id_sede );
for($i = 0; $i < sizeof ( $listadoPartidos ); $i ++) {
	$confEquipo1 = $oFixture->partidoConfirmado ( $listadoPartidos [$i] ['idPartido'], $listadoPartidos [$i] ['id1'] );
	$confEquipo2 = $oFixture->partidoConfirmado ( $listadoPartidos [$i] ['idPartido'], $listadoPartidos [$i] ['id2'] );
	$confirmacion = "";
	if ($confEquipo1 && $confEquipo2) {
		$confirmacion = "OK";
	} else {
		if ($confEquipo1) {
			$confirmacion = $listadoPartidos [$i] ["equipo1"];
		}
		if ($confEquipo2) {
			$confirmacion = $listadoPartidos [$i] ["equipo2"];
		}
	}
	$listadoPartidos [$i] ['confirmacion'] = $confirmacion;
	if ($listadoPartidos [$i] ['idzona'] != - 1 && $listadoPartidos [$i] ['idzona'] != 0) {
		$categoria = $oCetegoria->get ( $listadoPartidos [$i] ['idzona'] );
		$listadoPartidos [$i] ['zona'] = "-" . $categoria [0] ['nombrePagina'];
	} else {
		$listadoPartidos [$i] ['zona'] = "";
	}
}
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

		function migrar(){
			document.frm_busqueda.accion.value = "migrar";		
			document.frm_busqueda.submit();
		}
	
		function limpiarAction() {
			document.frm_busqueda.accion.value = "";	
			document.frm_busqueda.submit();	
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
							<form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<div class="formbody">
									<input type="hidden" name="listadoPartidos" value="<?=urlencode(serialize($listadoPartidos)) ?>" /> 
									<input type="hidden" name="accion" value="" /> 
									Fecha: <input name="fechaPartido" type="text" id="fechaPartido" size="11" value="<?=$_POST["fechaPartido"] ?>" readonly="readonly" />
									<a href="javascript:show_calendar('document.frm_busqueda.fechaPartido', document.frm_busqueda.fechaPartido.value);">
										<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
									</a> Sede: <select name="idSede" id='idSede' <?= $disabled ?> style="width: 200px">
													<option value="-1">Seleccione una Sede...</option>
													<?php for($i=0;$i<count($aSedes);$i++) { ?>	
														<option value="<?php echo $aSedes[$i]['id'] ?>"
															<?php if ($_POST["idSede"] == $aSedes[$i]['id'] ) echo "selected"; ?>><?php echo $aSedes[$i]['nombre']?>
														</option>
													<?php } ?>	   
								  				</select> 
								  	<input class="submit" value="Listar" type="submit" style="font-size: 11px" onclick="javascript:limpiarAction();" /> 
									<input class="submit" value="Limpiar" type="button" style="font-size: 11px" onclick="javascript:limpiar('frm_busqueda'); javascript:limpiarAction();document.frm_busqueda.submit();" />
								</div>
							</form>
                    	<? 	if ($listadoPartidos != NULL) {
								$sede = $oSede->get ( $id_sede ); ?>
						 		<div class="ce_text block">
									<h1>Listado de Partidos del <?=$fechaPartidos?> - Sede <?=$sede[0]['nombre']?></h1>
									<img width="75" border="0" alt="exportar" title="Exportar Excel" onclick="javascript:migrar();" style="cursor: pointer; float: right;" src="images/xls-icon.png" />
									<a href="listadopartidos.fichas.php?fecha=<?=$fechaPartidos?>&sede=<?=$id_sede?>>" target="_blanck"><img width="75" border="0" alt="fichas" title="Obtener Fichas" style="float: right;" src="images/pdf-icon.png" /></a>
								</div>
								<div class="mod_listing ce_table listing block" id="partnerlist">
									<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
										<table style="width: 930px">
											<tr>
												<th>Hora</th>
												<th>Torneo</th>
												<th style="text-align: center;">Equipo 1 vs Equipo 2</th>
												<th width="5%">Cancha</th>
												<th>Arbitro</th>
												<th>Conf.</th>
											</tr>
											<? foreach ($listadoPartidos as $partido) {?>
												<tr>
													<td><?=$partido["horaPartido"]?></td>
													<td><?=$partido["torneo"]."-".$partido["categoria"].$partido["zona"]?></td>
													<td style="text-align: center;"><?=$partido["equipo1"]." vs ".$partido["equipo2"]?></td>
													<td style="text-align: center"><?=$partido["cancha"]?></td>
													<td><?=$partido["arbitro"]?></td>
													<td style="text-align: center">
													 <? if ($partido["confirmacion"] == "OK") { ?>
													 		<img width="25" border="0" alt="reserva" title="Confirmado" src="../img/check.ico" />
													 <?	} elseif ($partido["confirmacion"] == "") { ?>
													 		<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/forbidden.ico" />
													 <?	} else { ?> 
															<font color="#0000FF"><?=$partido["confirmacion"]?></font> 
															<img width="25" border="0" alt="reserva" title="Sin Confirmacion" src="../img/blue-check-icon.png" />
													 <?	} ?>
										 			</td>
												</tr>
											<? }?>
										</table>
									</form>
								</div>
						<?	} elseif ($fechaPartidos != NULL && $id_sede != - 1) {
								$sede = $oSede->get ( $id_sede ); ?>
								<h1>No hay de Partidos Cargado para la Fecha <?=$fechaPartidos?> - Sede <?=$sede[0]['nombre'] ?></h1>
						<?  } ?>	
						</div>
					</div>
				</div>
			</div>
		</div>
	<? include("pie.php")?>
	</div>
</body>
</html>
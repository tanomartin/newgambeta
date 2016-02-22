<?

include_once "include/fechas.php";
include_once "../model/fixture.php";
include_once "../model/fechas.php";
include_once "../model/equipos.php";
include_once "../model/jugadoras.php";
include_once "../model/resultados.php";
if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}
$operacion = "Resultado";
$oFixture = new Fixture();
$datos = $oFixture->get($_POST["id"]);
$oFechas = new Fechas();
$aFechas = $oFechas->get($datos[0]["idFecha"]);
$oEquipos = new Equipos();
$aEquipos1 = $oEquipos->getByIdEquipoTorneo($datos[0]["idEquipoTorneo1"]);
$aEquipos2 = $oEquipos->getByIdEquipoTorneo($datos[0]["idEquipoTorneo2"]);
$oJugadora = new Jugadoras();
$aJugadoras1 = $oJugadora->getByEquipoTorneo($aEquipos1[0]["id"], $datos[0]["idTorneoCat"]);
$aJugadoras2 = $oJugadora->getByEquipoTorneo($aEquipos2[0]["id"], $datos[0]["idTorneoCat"]);

$oResultado = new Resultados();
$resultados = $oResultado->get($_POST["id"]);
if ($resultados) {
	foreach ($resultados as $key => $valores) {
		$aResultado [$valores['idJugadoraEquipo']] ['goles'] = ($valores ['goles'] > 0) ? $valores ['goles'] : '';
		$aResultado [$valores['idJugadoraEquipo']] ['amarilla'] = ($valores ['tarjeta_amarilla'] > 0) ? $valores ['tarjeta_amarilla'] : '';
		$aResultado [$valores['idJugadoraEquipo']] ['roja'] = ($valores ['tarjeta_roja'] > 0) ? $valores ['tarjeta_roja'] : '';
		$aResultado [$valores['idJugadoraEquipo']] ['mejor'] = $valores ['mejor_jugadora'];
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
		function volver(){
			document.form_alta.accion.value = "volver";		
			document.form_alta.submit();
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
					<div class="mod_article block" id="register">
						<div class="ce_text block">
							<h1><?=$operacion?> del Fixture</h1>
						</div>
						<div class="mod_registration  tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
								<input name="id" id="id" value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag" value="<?=$_POST["_pag"]?>" type="hidden" /> 
								<input name="idFixture" id="idFixture" value="<?= $datos[0]["id"]?>" type="hidden" /> 
								<input name="idEquipoTorneo1" id="idEquipoTorneo1" value="<?= $datos[0]["idEquipoTorneo1"]?>" type="hidden" /> 
								<input name="idEquipoTorneo2" id="idEquipoTorneo2" value="<?= $datos[0]["idEquipoTorneo2"]?>" type="hidden" /> 
								<input type="hidden" name="accion" value="guardarResultado" />
								<!-- Filtros -->
								<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" /> 
								<input name="ftorneo" type="hidden" value="<?=$_POST["ftorneo"]?>" /> 
								<input name="fcategoria" type="hidden" value="<?=$_POST["fcategoria"]?>" />
								<!-- Fin filtros -->
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> 
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->

								<div class="formbody">
									<div class="ce_table">
										<fieldset><legend>Datos del Fixture </legend></fieldset>
										<table summary="Personal data">
											<tbody>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Torneo</label></td>
													<td class="col_1 col_last"><?= $aFechas[0]['torneo']?></td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Categoría</label></td>
													<td class="col_1 col_last"><?= $aFechas[0]['categoria']?> </td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Fecha</label></td>
													<td class="col_1 col_last"> <?= $aFechas[0]['nombre']?></td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Equipo #1 </label></td>
													<td class="col_1 col_last"><?= $aEquipos1[0]["nombre"] ?></td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Puntaje Juez Equipo #1 </label><span class="mandatory">*</span></td>
													<td class="col_1 col_last"><input type="text" id="puntajeEquipo1" name="puntajeEquipo1" value="<?= $datos[0]["puntajeEquipo1"]?>" size="5" /></td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Detalle Por Jugadoras</label></td>
													<td class="col_1 col_last">
														<table style="width: 100%">
															<tr>
																<td>Jugadora</td>
																<td>Goles</td>
																<td>Amariila</td>
																<td>Roja</td>
																<td>M.J.</td>
															</tr>
                    									 <? for($i=0;$i<count($aJugadoras1);$i++) { ?>
	                   											<tr>
																	<td><?= $aJugadoras1[$i][nombre]?><input name="<?= $aJugadoras1[$i][idJugadoraEquipo]?>_id1" style="visibility: hidden;" type="text" size="5" value="<?= $aJugadoras1[$i][idJugadoraEquipo] ?>"/></td>
																	<td><input name="<?= $aJugadoras1[$i][idJugadoraEquipo]?>_goles1" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][idJugadoraEquipo]][goles]  ?>" /></td>
																	<td><input name="<?= $aJugadoras1[$i][idJugadoraEquipo]?>_amarillas1" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][idJugadoraEquipo]][amarilla]  ?>" /></td>
																	<td><input name="<?= $aJugadoras1[$i][idJugadoraEquipo]?>_rojas1" type="text" size="5" value="<?= $aResultado[$aJugadoras1[$i][idJugadoraEquipo]][roja]  ?>" /></td>
																	<td><input type="radio"  name="mejor_jugadora" value="<?= $aJugadoras1[$i][idJugadoraEquipo]?>" <? if ($aResultado[$aJugadoras1[$i][idJugadoraEquipo]][mejor] ==  'S') echo  'checked="checked"'; ?> ></td>
																</tr>
                    									  <? } ?>   
                										 </table>
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Equipo #2 </label><span class="mandatory">*</span></td>
													<td class="col_1 col_last"><?= $aEquipos2[0]["nombre"]?> </td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Puntaje Juez Equipo #2 </label><span class="mandatory">*</span></td>
													<td class="col_1 col_last"><input type="text" id="puntajeEquipo2" name="puntajeEquipo2" value="<?= $datos[0]["puntajeEquipo2"]?>" size="5"/> </td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Detalle Por Jugadoras</label></td>
													<td class="col_1 col_last">
														<table style="width: 100%">
															<tr>
																<td>Jugadora</td>
																<td>Goles</td>
																<td>Amariila</td>
																<td>Roja</td>
																<td>M.J.</td>
															</tr>
                    										<? for($i=0;$i<count($aJugadoras2);$i++) { ?>
	                   											<tr>
																	<td><?= $aJugadoras2[$i][nombre]?><input name="<?= $aJugadoras2[$i][idJugadoraEquipo]?>_id2" style="visibility: hidden;" type="text" size="5" value="<?= $aJugadoras2[$i][idJugadoraEquipo] ?>"/></td>
																	<td><input name="<?= $aJugadoras2[$i][idJugadoraEquipo]?>_goles2" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][idJugadoraEquipo]][goles]  ?>" /></td>
																	<td><input name="<?= $aJugadoras2[$i][idJugadoraEquipo]?>_amarillas2" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][idJugadoraEquipo]][amarilla]  ?>" /></td>
																	<td><input name="<?= $aJugadoras2[$i][idJugadoraEquipo]?>_rojas2" type="text" size="5" value="<?= $aResultado[$aJugadoras2[$i][idJugadoraEquipo]][roja]  ?>" /></td>
																	<td><input type="radio"  name="mejor_jugadora" value="<?= $aJugadoras2[$i][idJugadoraEquipo]?>" <? if ($aResultado[$aJugadoras2[$i][idJugadoraEquipo]][mejor] ==  'S') echo  'checked="checked"'; ?> ></td>                 
																</tr>
                    										<? } ?>   
                 										</table>
													</td>
												</tr>
											</tbody>
										</table>
										<div class="submit_container">
											<input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" />
											<input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
										</div>
									</div>
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
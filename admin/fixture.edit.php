<?
	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/fixture.php";
	include_once "../model/fechas.php";
	include_once "../model/equipos.php";
	include_once "../model/sedes.php";
	include_once "../model/arbitros.php";
	include_once "../model/fckeditor.class.php";
	if (! session_is_registered ( "usuario" )) {
		header ( "Location: index.php" );
		exit ();
	}
	$operacion = "Alta";
	if ($_POST ["id"] != - 1) {
		$operacion = "Modificaci&oacute;n";
		$oFixture = new Fixture ();
		$datos = $oFixture->get($_POST ["id"]);
	}
	$oFCKeditor = new FCKeditor ( "texto" );
	$oFCKeditor->BasePath = '_js/FCKeditor/';
	$oFCKeditor->Height = 250;
	$oFCKeditor->Width = 450;
	$oFCKeditor->ToolbarSet = "custom2";
	$oFCKeditor->InstanceName = "observaciones";
	$oFCKeditor->Value = $datos [0] ['observaciones'];
	$disabled = "";
	if ($_POST ['accion'] == 'ver')
		$disabled = "disabled";
	$oTorneo = new Torneos();
	$aTorneos = $oTorneo->get();
	$oSede = new Sedes();
	$aSedes = $oSede->get();
	$oArbitro = new Arbitros();
	$aArbitros = $oArbitro->get();
	
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
								<input type="hidden" name="accion" value="guardar" />
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
													<td class="col_0 col_first"><label for="nombre">Torneo</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat');clearFecha('idFecha');clearEquipo1('idEquipoTorneo1');clearEquipo2('idEquipoTorneo2'); return listOnChange('idTorneo', '','categoriaList','categoria_data1.php','advice1','idTorneoCat','idTorneoCat');">
															<option value="-1">Seleccione un Torneo...</option>
		 													<? for($i=0;$i<count($aTorneos);$i++) { ?>	
																<option value="<?php echo $aTorneos[$i]['id'] ?>"
																	<? if ($datos[0]["id_torneo"] ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre']?>
                												</option>
             												<? } ?>	   
         												</select>
         											</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Categoría</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<span id="categoriaList"> 
															<select name="idTorneoCat" id="idTorneoCat" <?= $disabled?> class="validate-selection" onChange="clearFecha('idFecha');clearEquipo1('idEquipoTorneo1');clearEquipo2('idEquipoTorneo2'); listOnChange('idTorneoCat', '', 'fechaList','fecha_data.php','advice2','idFecha','idFecha');">
																<option value="-1">Seleccione antes un Torneo...</option>
																	<? if ($datos [0] ["id_torneo"]) {
																			$oTorneoCat = new TorneoCat ();
																			$aTorneoCat = $oTorneoCat->getByTorneoSub ( $datos [0] ["id_torneo"] );
																			for($i = 0; $i < count ( $aTorneoCat ); $i ++) { ?>	
																			 <option <? if($aTorneoCat[$i]["id"] == $datos[0]["idTorneoCat"]) echo "selected"; ?> value="<?=$aTorneoCat[$i]["id"]?>">
																			 	<?=$aTorneoCat[$i]["nombrePagina"]?> <? if ( $aTorneoCat[$i]["nombreCatPagina"] != "" ){ echo "- ". $aTorneoCat[$i]["nombreCatPagina"]; } ?>
												                             </option>
																		 <? }
																	   } ?>
															</select> 
															<span id="advice1"> </span>
														</span>
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Fecha</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<span id="fechaList"> 
															<select name="idFecha" id="idFecha" <?= $disabled?> class="validate-selection" onchange="clearEquipo1('idEquipoTorneo1');clearEquipo2('idEquipoTorneo2');listOnChange('idTorneoCat', '', 'Equipo1List','equipo1_data.php','advice3','idEquipoTorneo1','idEquipoTorneo1');">
																<option value="-1">Seleccione antes una Categor&iacute;a...</option>								
																<?	if ($datos [0] ["idFecha"]) {
																		$oFechas = new Fechas ();
																		$aFechas = $oFechas->getIdTorneoCat ( $datos [0] ["idTorneoCat"] );
																		for($i = 0; $i < count ( $aFechas ); $i ++) { ?>	
																		 	<option <? if($aFechas[$i]["id"] == $datos[0]["idFecha"]) echo "selected"; ?> value="<?=$aFechas[$i]["id"]?>"><?=$aFechas[$i]["nombre"]?></option>	
																	<? }
																	} ?>
															</select> 
															<span id="advice2"> </span>
														</span>
													</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Fecha Partido</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<input name="fechaPartido" type="text" id="fechaPartido" value="<?php echo cambiaf_a_normal($datos[0]["fechaPartido"]); ?>" size="10" readonly="readonly" class="required" /> 
														<a href="javascript:show_calendar('document.form_alta.fechaPartido', document.form_alta.fechaPartido.value);">
															<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
														</a>
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Hora del Partido</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<input name="horaPartido" type="text" id="horaPartido" value="<?php echo $datos[0]["horaPartido"]; ?>" class="required" size="5" <?= $disabled ?> />
													</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Sede</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<select name="idSede" id='idSede' <?= $disabled ?> class="validate-selection">
															<option value="-1">Seleccione una Sede...</option>
													 	<?php for($i=0;$i<count($aSedes);$i++) { ?>	
															<option value="<?php echo $aSedes[$i]['id'] ?>" <?php if ($datos[0]["idSede"] ==   $aSedes[$i]['id'] ) echo "selected"; ?>>
																<?php echo $aSedes[$i]['nombre']?>
											                </option>
											             <?php } ?>	   
											         	</select>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Cancha</label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<input name="cancha" type="text" id="cancha" value="<?php echo $datos[0]["cancha"]; ?>" class="required" size="5" <?= $disabled ?> />
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Arbitro</label></td>
													<td class="col_1 col_last">
														<select name="idArbitro" id='idArbitro' <?= $disabled ?>>
														<?php for($i=0;$i<count($aArbitros);$i++) { ?>	
																<option value="<?php echo $aArbitros[$i]['id'] ?>" <?php if ($datos[0]["idArbitro"] == $aArbitros[$i]['id'] ) echo "selected"; ?>>
																	<?php echo $aArbitros[$i]['nombre'] ?>
											                	</option>
											             <?php } ?>	   
														</select>
													</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Equipo #1 </label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<span id="Equipo1List"> 
															<select name="idEquipoTorneo1" id="idEquipoTorneo1" <?= $disabled?> class="validate-selection" onChange="clearEquipo2('idEquipoTorneo2');return listOnChange('idEquipoTorneo1', '', 'Equipo2List','equipo2_data.php','advice4','idEquipoTorneo2','idEquipoTorneo2');">
																<option value="-1">Seleccione antes una Fecha...</option>
															 <? if ($datos [0] ["idEquipoTorneo1"]) {
																	$oEquipos = new Equipos ();
																	$aEquipos = $oEquipos->getTorneoCat ( $datos [0] ["idTorneoCat"] );
																	for($i = 0; $i < count ( $aEquipos ); $i ++) { ?>	
							 									<option <? if($aEquipos[$i]["idEquipoTorneo"] == $datos[0]["idEquipoTorneo1"]) echo "selected"; ?> value="<?=$aEquipos[$i]["idEquipoTorneo"]?>"><?=$aEquipos[$i]["nombre"]?></option>
																<? }
																} ?>
															</select> 
															<span id="advice3"> </span>
														</span>
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Goles Equipo #1</label></td>
													<td class="col_1 col_last">
														<input name="golesEquipo1" id="golesEquipo1" class="validation-digits" maxlength="2" type="text" value="<? if ($datos[0]["golesEquipo1"] != -1){ echo $datos[0]["golesEquipo1"]; }?>" size="8" <?= $disabled ?>/>
													</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Equipo #2 </label><span class="mandatory">*</span></td>
													<td class="col_1 col_last">
														<span id="Equipo2List"> 
															<select name="idEquipoTorneo2" id="idEquipoTorneo2" <?= $disabled?> class="validate-selection">
																<option value="-1">Seleccione antes un Equipo #1...</option>
								                                <? if ($datos[0]["idEquipoTorneo2"]) {
																		$oEquipos = new Equipos ();
																		$aEquipos = $oEquipos->getByEquipoTorneo($datos[0]["idEquipoTorneo1"]);
																		for($i = 0; $i < count ( $aEquipos ); $i ++) { ?>	
                                    							 <option <? if($aEquipos[$i]["idEquipoTorneo"] == $datos[0]["idEquipoTorneo2"]) echo "selected"; ?> value="<?=$aEquipos[$i]["idEquipoTorneo"]?>"><?=$aEquipos[$i]["nombre"]?></option>
									                                <? }
																	} ?>
                               								</select> 
                               								<span id="advice4"> </span>
														</span>
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Goles Equipo #2</label></td>
													<td class="col_1 col_last">
														<input name="golesEquipo2" id="golesEquipo2" class="validation-digits" maxlength="2" type="text" value="<? if ($datos[0]["golesEquipo2"] != -1){ echo $datos[0]["golesEquipo2"]; }?>" size="8" <?= $disabled ?>/>
													</td>
												</tr>
												<tr class="even">
													<td class="col_0 col_first"><label for="nombre">Walkover</label></td>
													<td class="col_1 col_last">
														<input name="suspendido" id="walkover" type="checkbox" <?= $disabled?>
														<? if ($datos[0]['suspendido'] == 1 ){ ?> checked="checked" <? } ?>/> Si
													</td>
												</tr>
												<tr class="odd">
													<td class="col_0 col_first"><label for="nombre">Descripción</label></td>
													<td class="col_1 col_last"><?= $oFCKeditor -> Create( ) ; ?></td>
												</tr>
											</tbody>
										</table>
										<div class="submit_container">
   									 <? if ( $disabled  == "" ) { ?>
   	 										<input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
   									 <? } ?>
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
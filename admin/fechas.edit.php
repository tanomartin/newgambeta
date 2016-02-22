<?
include_once "../model/torneos.php";
include_once "../model/torneos.categorias.php";
include_once "../model/fechas.php";
if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}
$operacion = "Alta";
if ($_POST ["id"] != - 1) {
	$operacion = "Modificaci&oacute;n";
	$oFecha = new Fechas ();
	$datos = $oFecha->get ( $_POST ["id"] );
}
$disabled = "";
if ($_POST ['accion'] == 'ver')
	$disabled = "disabled";
$oTorneo = new Torneos ();
$aTorneos = $oTorneo->get ();
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
							<h1><?=$operacion?> del Equipo</h1>
						</div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
								<input name="id" id="id" value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag" value="<?=$_POST["_pag"]?>" type="hidden" /> 
								<input type="hidden" name="accion" value="guardar" />
								<!-- Filtros -->
								<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
								<input type="hidden" name="ftorneo" value="<?=$_POST["ftorneo"]?>"  />
				                <input type="hidden" name="fcategoria" value="<?=$_POST["fcategoria"]?>"  />
								<!-- Fin filtros -->
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu"
									value="<?=$_POST["submenu"]?>" /> <input type="hidden"
									name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->
								<div class="formbody">
									<div class="ce_table">
										<fieldset>
											<legend>Datos del Equipo </legend>
											<table summary="Personal data">
												<tbody>
													<tr class="even">
														<td class="col_0 col_first"><label for="nombre">Nombre</label><span class="mandatory">*</span></td>
														<td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50" <?= $disabled ?>></td>
													</tr>
													<tr class="odd">
														<td class="col_0 col_first"><label for="nombre">Torneo</label><span class="mandatory">*</span></td>
														<td class="col_1 col_last">
															<select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat'); return listOnChange('idTorneo', '','categoriaList','categoria_data2.php','advice1','idTorneoCat','idTorneoCat');">
																<option value="-1">Seleccione un Torneo...</option>
		 													 <? for($i=0;$i<count($aTorneos);$i++) { ?>	
																	<option value="<?php echo $aTorneos[$i]['id'] ?>" <? if ($datos[0]["id_torneo"] ==  $aTorneos[$i]['id'] ) echo "selected"; ?>>
																		<?php echo $aTorneos[$i]['nombre']?>
                													</option>
             												 <? } ?>	   
         													</select>
         												</td>
													</tr>
													<tr class="even">
														<td class="col_0 col_first"><label for="nombre">Categoría</label><span class="mandatory">*</span></td>
														<td class="col_1 col_last">
															<span id="categoriaList"> 
																<select name="idTorneoCat" id="idTorneoCat" <?= $disabled?> class="validate-selection">
																	<option value="-1">Seleccione antes un Torneo...</option>
																 <? if ($datos [0] ["id_torneo"]) {
																		$oTorneoCat = new TorneoCat ();
																		$aTorneoCat = $oTorneoCat->getByTorneoFechas ( $datos [0] ["id_torneo"] );
																		for($i = 0; $i < count ( $aTorneoCat ); $i ++) { ?>	
																		 <option <? if($aTorneoCat[$i]["id"] == $datos[0]["idTorneoCat"]) echo "selected"; ?> value="<?=$aTorneoCat[$i]["id"]?>"><?=$aTorneoCat[$i]["nombrePagina"]?> <? if ( $aTorneoCat[$i]["nombrePagina"] != "" ){ echo "- ". $aTorneoCat[$i]["nombrePagina"]; } ?></option>
																	 <? }
																	} ?>
																</select> 
																<span id="advice1"> </span>
															</span>
														</td>
													</tr>
													<tr class="odd">
														<td class="col_0 col_first"><label for="nombre">Fecha Inicio<span class="mandatory">*</span></label></td>
														<td class="col_1 col_last"><input name="fechaIni" type="text" id="fechaIni" value="<?php echo cambiaf_a_normal($datos[0]['fechaIni']);?>" size="10" readonly="readonly" /> 
															<a href="javascript:show_calendar('document.form_alta.fechaIni', document.form_alta.fechaIni.value);">
																<? if ( $disabled == "" ) { ?>
																	<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
																<? } ?>
															</a>
														</td>
													</tr>
													<tr class="even">
														<td class="col_0 col_first"><label for="nombre">Fecha Fin</label><span class="mandatory">*</span></td>
														<td class="col_1 col_last"><input name="fechaFin" type="text" id="fechaFin" value="<?php echo cambiaf_a_normal($datos[0]['fechaFin'])?>" size="10" readonly="readonly" /> 
															<a href="javascript:show_calendar('document.form_alta.fechaFin', document.form_alta.fechaFin.value);">
																<? if ( $disabled == "" ) { ?>
																	<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
																<? } ?>
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</fieldset>
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
						<div class="ce_text g4 xpln block">
							<p><strong>Datos del Equipo</strong><br />Ingrese los datos del Equipo.</p>
							<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	<? include("pie.php")?>
	</div>
</body>
</html>
<?	include_once "include/fechas.php";
	include_once "../model/jugadoras.php";
	include_once "../model/equipos.php";	
	include_once "../model/posiciones.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$operacion = "Alta";
	$oJugadora= new Jugadoras();
	$jugadora = $oJugadora->get($_POST["id"]);
	if ($_POST["idJugadoraEquipo"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oJugadora= new Jugadoras();
		$datos = $oJugadora->getJugadoraEquipo($_POST["idJugadoraEquipo"]);	
	}
	$disabled = "";
	if( $_POST['accion'] == 'verequipos')
		$disabled = "disabled";
	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();
	$oPosicion= new Posiciones();
	$aPosicion = $oPosicion->get();
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
			document.form_alta.accion.value = "equipos";		
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
						<div class="ce_text block"><h1><?=$operacion?> Equipo Jugadora <font color="#e4790f"><?=$jugadora[0]["nombre"]?></font> </h1></div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="idJugadoraEquipo" id="idJugadoraEquipo"  value="<?=$_POST["idJugadoraEquipo"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="guardarequipo" />
								<!-- Filtros -->
								<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
								<input type="hidden" name="fdni" value="<?=$_POST["fdni"]?>"  />
								<!-- Fin filtros -->
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->

								<div class="formbody">
									<div class="ce_table">
										<fieldset>
											<legend>Datos del Equipo</legend>
											<table summary="Personal data">
											  	<tbody>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="torneo">Torneo</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last">
											         	<select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat'); clearEquipo('idEquipo');
											         	return listOnChange('idTorneo', '','categoriaList','categoria_data.php','advice1','idTorneoCat','idTorneoCat');" >
											            	<option value="-1">Seleccione un Torneo...</option>
															 <?php for($i=0;$i<count($aTorneos);$i++) { ?>	
																<option value="<?php echo $aTorneos[$i]['id'] ?>" <?php if ($datos[0]["idTorneo"] ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre'] ?> </option>
													          <?php } ?>	   
											         	</select>
											        </td>
											      </tr> 
											      <tr class="even">
											        <td class="col_0 col_first"><label for="categoria">Categoria</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last"> 
														<span id="categoriaList">
															<select name="idTorneoCat" id="idTorneoCat" <?= $disabled ?> class="validate-selection" onChange="clearEquipo('idEquipo');
															return listOnChange('idTorneoCat', '','equipoList','equipo_data.php','advice2','idEquipo','idEquipo');">
																<option value="-1">Seleccione antes un Torneo...</option>
																	<? if($datos[0]["idTorneo"]) {
																		  $oTorneoCat = new TorneoCat();
																		  $aTorneoCat = $oTorneoCat->getByTorneoSub($datos[0]["idTorneo"]);
																		  for ($i=0;$i<count($aTorneoCat);$i++) {?>	
																			 <option <? if($aTorneoCat[$i]["id"] == $datos[0]["idTorneoCat"]) echo "selected"; ?> value="<?=$aTorneoCat[$i]["id"]?>">
																			 			<?=$aTorneoCat[$i]["nombrePagina"]?> <? if ($aTorneoCat[$i]["nombreCatPagina"] != "" ){ echo "- ". $aTorneoCat[$i]["nombreCatPagina"]; 
																			 	   } ?>
																			 </option>
																	   <? }
																	  } ?>
															</select> 
															<span id="advice1"> </span>
														</span>	
											        </td> 
											      </tr>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="equipo">Equipo</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last">
												        <span id="equipoList">
												         	<select name="idEquipo" id='idEquipo' <?= $disabled ?> class="validate-selection" >
													            <option value="-1">Seleccione un Equipo...</option>
																 	 <? if($datos[0]["idTorneoCat"]) {
																 	 		$oEquipo= new Equipos();
																 	 		$aEquipos = $oEquipo->getByCategoria($datos[0]["idTorneoCat"]);
																 			for($i=0;$i<count($aEquipos);$i++) { ?>	
																				<option <?php if ($datos[0]["idEquipo"] == $aEquipos[$i]['id'] ) echo "selected"; ?> value="<?php echo $aEquipos[$i]['id'] ?>">
																					<?php echo $aEquipos[$i]['nombre'] ?>
																				</option>
														             <?php } 
														             } ?>	   
												         	</select>
												        	 <span id="advice2"> </span>
												       </span>												        
												     </td>
											      </tr>  
											      <tr class="even">
											        <td class="col_0 col_first"><label for="nombre">Posici&oacute;n</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last">
											         <select name="idPosicion" id='idPosicion' <?= $disabled ?> class="validate-selection" >
											            <option value="-1">Seleccione una Posici&oacute;n...</option>
													 	<?php for($i=0;$i<count($aPosicion);$i++) { ?>	
															<option value="<?php echo $aPosicion[$i]['id'] ?>" <?php if ($datos[0]["idPosicion"] ==   $aPosicion[$i]['id'] ) echo "selected"; ?>><?php echo $aPosicion[$i]['nombre'] ?>
											                </option>
											             <?php } ?>	   
											         	</select>
											         </td>   
											      </tr>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="activa">Activa</label></td>
											        <td class="col_1 col_last"><input type="checkbox"  <? if ($datos[0]["activa"] == "1" ) { ?> checked="checked" <? } ?> name="activo" id="activo" <?= $disabled ?> ></td>
											      </tr>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="correo">Email</label></td>
											        <td class="col_1 col_last"><input type="checkbox"  <? if ($datos[0]["email"] == "1" ) { ?> checked="checked" <? } ?> name="email" id="email" <?= $disabled ?> ></td>
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
							<p><strong>Datos de la Jugadora</strong></p>
							<p>Ingrese los datos de la Jugadora</p>
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
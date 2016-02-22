<?	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/equipos.php";	
    include_once "../model/fckeditor.class.php" ;
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	
	$oEquipo= new Equipos();
	if ($_POST["idTorneoEquipo"] != -1) {
		$datos = $oEquipo->getRelacionTorneo($_POST["idTorneoEquipo"]);
	} else {
		$datos = $oEquipo->get($_POST["id"]);
	}
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
	
	<script>
		function volver() {
			document.form_alta.accion.value = "torneos";		
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
						<div class="ce_text block">
							<h1>Relacion Torneo Equipo <font color="#e4790f"> <?=$datos[0]["nombre"]?> </font></h1>
						</div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"> 
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="idTorneoEquipo" id="idTorneoEquipo"  value="<?=$_POST["idTorneoEquipo"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="guardarTorneo" />
		
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->
								<!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input name="femail" type="hidden"  value="<?=$_POST["femail"]?>"  />                           
				                <!-- Fin filtros -->
		
								<div class="formbody">
									<div class="ce_table">
										<fieldset>
											<legend>Asignacion Torneo equipo <?=$datos[0]["nombre"]?></legend>
											<table summary="Personal data">
											  	<tbody>
											   		<tr>
											        	<td class="col_0 col_first"><label for="nombre">Torneo</label><span class="mandatory">*</span></td>
											        	<td class="col_1 col_last">
											         		<select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat');
											         		return listOnChange('idTorneo', '','categoriaList','categoria_data.php','advice1','idTorneoCat','idTorneoCat');" >
											            		<option value="-1">Seleccione un Torneo...</option>
															 	<?php for($i=0;$i<count($aTorneos);$i++) { ?>	
																	<option value="<?php echo $aTorneos[$i]['id'] ?>" <?php if ($datos[0]["id_torneo"] ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre'] ?>
													                </option>
													             <?php } ?>	   
											         		</select>
											         	</td>   
													</tr>  
											        <tr class="even">
											        	<td class="col_0 col_first"><label for="nombre">Categoria</label><span class="mandatory">*</span></td>
											        	<td class="col_1 col_last"> 
															<span id="categoriaList">
																<select name="idTorneoCat" id="idTorneoCat" <?= $disabled ?> class="validate-selection" >
																	<option value="-1">Seleccione antes un Torneo...</option>
																		<?
																		 if($datos[0]["id_torneo"]) {
																			$oTorneoCat = new TorneoCat();
																			$aTorneoCat = $oTorneoCat->getByTorneoSub($datos[0]["id_torneo"]);
																			for ($i=0;$i<count($aTorneoCat);$i++) {?>	
																			 <option <? if($aTorneoCat[$i]["id"] == $datos[0]["idTorneoCat"]) echo "selected"; ?> value="<?=$aTorneoCat[$i]["id"]?>"><?=$aTorneoCat[$i]["nombrePagina"]?> <? if ( $aTorneoCat[$i]["nombreCatPagina"] != "" ){ echo "- ". $aTorneoCat[$i]["nombreCatPagina"]; } ?></option>
																		 <? }
																		 } ?>
																</select> 
																<span id="advice1"> </span>
															</span>	
											        	</td>    
											      	</tr>  
											      	<tr class="odd">
												        <td class="col_0 col_first"><label for="nombre">Descuentos Puntos</label></td>
												        <td class="col_1 col_last"> 
													       <input name="descuento_puntos" type="text" id="descuento_puntos" value="<?php echo $datos[0]["descuento_puntos"]; ?>" class="validate-digits" size="3"  <?= $disabled ?>/>
												        </td>
											       	</tr>    
												</tbody>
											</table>
										</fieldset>
		
		    							<div class="submit_container">
										   	<input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
										    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
									    </div>
		    						</div>
								</div>
							</form>
						</div>
						<div class="ce_text g4 xpln block">
							<p><strong>Asigancion de Torneos a Equipo</strong><br/>Ingrese los datos</p>
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
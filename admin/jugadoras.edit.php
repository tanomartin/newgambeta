<?	include_once "include/fechas.php";
	include_once "../model/jugadoras.php";
	include_once "../model/equipos.php";	
	
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	$operacion = "Alta";
	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oJugadora= new Jugadoras();
		$datos = $oJugadora->get($_POST["id"]);	
	}

	$disabled = "";
	if( $_POST['accion'] == 'ver')
		$disabled = "disabled";
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
						<div class="ce_text block"><h1><?=$operacion?> del Jugador</h1></div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="guardar" />
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
											<legend>Datos de Jugadora</legend>
											<span style="color: red;"><b><?=$error?></b></span>
											<table summary="Personal data">
											  	<tbody>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="nombre">Nombre</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50"  <?= $disabled ?>></td>
											      </tr> 
											      <tr class="even">
											        <td class="col_0 col_first"><label for="dni">D.N.I.</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last"><input name="dni" id="dni" class="required text" maxlength="50" type="text" value="<?=$datos[0]["dni"]?>" size="50"  <?= $disabled ?>></td>
											      </tr>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="email">Email</label></td>
											        <td class="col_1 col_last"><input name="email" id="email" maxlength="50" type="text" value="<?=$datos[0]["email"]?>" size="50"  <?= $disabled ?>></td>
											      </tr>  
											      <tr class="even">
											        <td class="col_0 col_first"><label for="fnac">Fecha Nacimiento:</label></td>
											        <td class="col_1 col_last">
											            <input name="fechaNac" type="text" id="fechaNac" value="<?php echo cambiaf_a_normal($datos[0]["fechaNac"]); ?>" size="10" <?= $disabled ?>/>
											            <? if ( $disabled  == "" ) { ?>
												            <a href="javascript:show_calendar('document.form_alta.fechaNac', document.form_alta.fechaNac.value);">
												            	<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
															</a>
														<? } ?>  
											        </td> 
											      </tr>
											      <tr class="even">
											        <td class="col_0 col_first"><label for="telefono">Telefono</label></td>
											        <td class="col_1 col_last"><input name="telefono" id="telefono" maxlength="50" type="text" value="<?=$datos[0]["telefono"]?>" size="50"  <?= $disabled ?>></td>
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
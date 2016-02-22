<?	include_once "include/fechas.php";
    include_once "../model/jugadoras.php";	
	
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$operacion = "Importar";
	
	$oEquipo= new Equipos();
	$equipo = $oEquipo->get($_POST["id"]);
	$datosTorneo = $oEquipo->getRelacionTorneo($_POST["idTorneoEquipo"]);
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
			document.form_alta.accion.value = "jugadoras";		
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
						<h1>Importacion de Jugadoras de
							 <font color="#e4790f"><?=$equipo[0]['nombre']." [".$datosTorneo[0]['torneo'] ." - ".$datosTorneo[0]['categoria']."]" ?></font></h1>
						</div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="importar" />
								<input type="hidden" name="idTorneoEquipo" value="<?=$_POST["idTorneoEquipo"]?>" />
								<input type="hidden" name="idTorneoCat" value="<?=$_POST["idTorneoCat"]?>" />
								<!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input type="hidden"  name="femail" value="<?=$_POST["femail"]?>"  />                           
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
											        <td class="col_0 col_first"><label for="nombre">Ficha Inscripcion</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last">
											        	<input class="required" name="ficha" id="ficha" type="file"/>
											        </td>
											      </tr>  
												</tbody>
											</table>
										</fieldset>
    									<div class="submit_container">
									   		<input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Importar" /> 
									   		<input class="submit" type="button" value="Volver" onclick="javascript:volver();" />									    
    									</div>
    								</div>
								</div>
							</form>
						</div>
						<div class="ce_text g4 xpln block">
							<p><strong>Importar Jugadoras</strong><br/>Seleccione la ficha de inscripcion del equipo</p>
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
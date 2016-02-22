<?	include_once "include/config.inc.php";
	include_once "../model/categorias.php";
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$menu = "Parametros";
	$operacion = "Alta";
	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oCategorias= new Categorias();
		$datos = $oCategorias->get($_POST["id"]);
	}
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
						<div class="ce_text block"><h1><?=$operacion?> de Categor&iacute;as</h1></div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
								<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
								<input type="hidden" name="accion" value="guardar" />
								<!-- Filtros -->
								<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
								<!-- Fin filtros -->
								<!-- Parametros menu -->
								<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
								<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								<!--     -->
								
								<div class="formbody">
									<div class="ce_table">
										<fieldset>
											<legend>Datos de la Categor&iacute;a</legend>
											<table summary="Personal data" >
										  		<tbody>
										       		<tr class="even">
										        		<td class="col_0 col_first"><label for="id_opcion">Nombre P&aacute;gina </label><span class="mandatory">*</span></td>
										        		<td class="col_1 col_last"><input name="nombrePagina" id="nombrePagina" class="required text" maxlength="20"  size="20" type="text" value="<?=$datos[0]["nombrePagina"]?>"></td>
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
							<p><strong>Datos de la Categor&iacute;a </strong><br/>
							Ingrese los datos de la Categor&iacute;a</p>
							<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>
							<p>EL campo <b>Nombre P&aacute;gina</b> se utiliza para el menú superior de Categor&iacute;as del las páginas internas</p><p><span class="mandatory">*</span> son de ingreso obligatorio.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<? include("pie.php")?>
	</div>
</body>
</html>
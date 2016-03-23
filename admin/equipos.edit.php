<?	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/equipos.php";	
    include_once "../model/fckeditor.class.php" ;
	
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	
	$operacion = "Alta";
	if ($_POST["id"] != -1) {
		$operacion = "Modificaci&oacute;n";
		$oEquipo= new Equipos();
		$datos = $oEquipo->get($_POST["id"]);
	}
    $oFCKeditor = new FCKeditor( "texto" ) ;
    $oFCKeditor -> BasePath = '_js/FCKeditor/' ;
	$oFCKeditor -> Height = 250 ;
	$oFCKeditor -> Width = 350 ;
	$oFCKeditor -> ToolbarSet = "custom2" ;
    $oFCKeditor -> InstanceName = "descripcion" ;
    $oFCKeditor -> Value = $datos[0]['descripcion'] ;
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
						<div class="ce_text block"><h1><?=$operacion?> del Equipo</h1></div>
						<div class="mod_registration g8 tableform block">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post"  enctype="multipart/form-data"> 
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
											<legend>Datos del Equipo</legend>
											<table summary="Personal data">
											  	<tbody>
											      <tr class="odd">
											        <td class="col_0 col_first"><label for="nombre">Nombre</label><span class="mandatory">*</span></td>
											        <td class="col_1 col_last"><input name="nombre" id="nombre" class="required text" maxlength="50" type="text" value="<?=$datos[0]["nombre"]?>" size="50"  <?= $disabled ?>></td>
											      </tr> 
											      <tr class="even">
											        <td class="col_0 col_first"><label for="dt">D.T.</label></td>
											        <td class="col_1 col_last"><input name="dt" id="dt" maxlength="50" type="text" value="<?=$datos[0]["dt"]?>" size="50"  <?= $disabled ?>></td>
											      </tr> 
											      
											      <tr class="odd">
											        <td class="col_0 col_first"><label for="dtemail">D.T. Email</label></td>
											        <td class="col_1 col_last"><input name="dtemail" id="dtemail"  maxlength="50" type="text" value="<?=$datos[0]["dtemail"]?>" size="50"  <?= $disabled ?>></td>
											      </tr> 
											      
											      <tr class="even">
											        <td class="col_0 col_first"><label for="dttelefono">D.T. Telefono</label></td>
											        <td class="col_1 col_last"><input name="dttelefono" id="dttelefono" maxlength="50" type="text" value="<?=$datos[0]["dttelefono"]?>" size="50"  <?= $disabled ?>></td>
											      </tr> 
											      
											      <tr class="odd">
											        <td class="col_0 col_first"><label for="nombre">Foto</label></td>
											        <td class="col_1 col_last">
											        	<? if ( $disabled  == "" ) { ?>
											        		<input name="foto" id="foto" class="" type="file"  <?= $disabled ?> />
											        	<? } 
											        	 if ($datos[0]["foto"] != "") { ?>
											        		<img style="margin-top: 10px" src="../fotos_equipos/<?= $datos[0]['foto']?>" width="100" height="69"/>
											        	<? } ?>  
											        </td>
											      </tr>  
											      <tr class="even">
											        <td class="col_0 col_first"><label for="nombre">Descripción</label></td> 
											         <? if ( $disabled  == "" ) { ?>
											         	<td class="col_1 col_last"><?= $oFCKeditor -> Create( ) ; ?></td> 
											         <? } else { ?>     
											         	<td class="col_1 col_last"><textarea rows="4" cols="50" <?= $disabled ?>><?=$datos[0]["descripcion"] ?> </textarea></td> 
											         <? } ?> 
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
							<p><strong>Datos del Equipo</strong><br/>Ingrese los datos del Equipo.</p>
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
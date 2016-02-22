<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/equipoideal.php";
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$menu = "Gestion";
	
	switch ($_POST["accion"]) {

		case "editar":
			include("equipoideal.edit.php");
			exit;
			break;
			
		case "ver":
			include("equipoideal.encabezado.php");
			exit;
			break;

		case "volver":
			include("equipoideal.encabezado.php");
			exit;
			break;

		case "guardar":	
			$data =  $_POST;
			$oObj = new Equipoideal();
			$oObj->set($data);
			$oObj->idTorneoCat = $data['id'];
			$oObj->agregar();	
			include("equipoideal.encabezado.php");
			exit;		
			break;

		case "borrar":	
			$data =   $_POST;
			$oObj = new Equipoideal();
			$oObj->set($data);
			$oObj->id = $data['idPosicion'];
			$oObj->eliminar();	
			include("equipoideal.encabezado.php");
			exit;		
			break;
	}
	
	//Paginacion
	$cant   = 10;
  	$pag    = ($_POST['_pag']>0) ? $_POST['_pag'] : 1;
	$inicio = ($pag - 1) * $cant;
    $fin    = $inicio + $cant;
	// fin Paginacion

	$total = 0;
	$oObj = new TorneoCat();
	$datos = $oObj->getCategoriasCompletas();
	$esultimo = (count($datos) == 1)? "S" : "N" ;
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
		function ver(id){
			
			document.frm_listado.accion.value = "ver";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
			
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
							<div class="mod_listing ce_table listing block" id="partnerlist">
				            	<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
					            	<input type="hidden" name="_pag" value="<?=$pag?>" />
					                <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
					                <input type="hidden" name="activo" value="" />
					                <input type="hidden" name="pos" value="" />
					                <input type="hidden" name="orden" value="" />  
					                <input type="hidden" name="accion" value="" />
					        		<!-- Parametros menu -->
					        		<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
					                <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
					                <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
					                <!--     -->
					                <!-- Filtros -->
					                <input name="ftorneo" type="hidden" style="width:100px" value="<?=$_POST["ftorneo"]?>"  />
					                <!-- Fin filtros -->
									<table style="width: 928px">
										<tr>
											<th width="15%">Torneo</th>                                        
											<th width="15%">Categor&iacute;a</th>     
											<th width="1%">Opciones</th>
										</tr>
										<? if (count($datos) == 0) { ?>
										<tr>
											<td colspan="3" align="center">No existen categor&iacute;as</td>
										</tr>
										<? } else { 
											 	$total = count($datos);	
												$tt = $total - 1;
												for ( $i = 0; $i < $total; $i++ ) {?>
													 <tr>
									                     <td align="left"><?=$datos[$i]["nombreTorneo"]?></td>
									                     <td align="left"><?=$datos[$i]["nombrePagina"]?> <? if ($datos[$i]["nombreCatPagina"] != "" ) { echo " - ". $datos[$i]["nombreCatPagina"];} ?></td>
									                     <td nowrap>
									                        <a href="javascript:ver(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
									                     </td>   
								  					 </tr>
											  <? } 
											  }?>
									</table>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<? include("pie.php")?>
	</div>
</body>

</html>
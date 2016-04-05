<?	include_once "include/config.inc.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/categorias.php";
	
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	
	$menu = "Secciones";

	switch ($_POST["accion"]) {
		case "listar":
			include("libreswo.listado.php");
			exit;
			break;
	}

	$total = 0;
	$oObj = new Torneos();
	$datos = $oObj->getPaginado($_REQUEST);
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
		function listar(id){
			document.frm_listado.accion.value = "listar";
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
		                        <form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
			                        <div class="formbody">
			                        	Nombre: <input name="fnombre" type="text" style="width:100px" value="<?=$_POST["fnombre"]?>"/>
			                        	<input class="submit" value="Buscar" type="submit" style="font-size:11px" />
			                          	<input class="submit" value="Limpiar" type="button" style="font-size:11px" onclick="javascript:limpiar('frm_busqueda'); document.frm_busqueda.submit();" />
			                        </div>
		                        </form>
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
				                    <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                    <!-- Fin filtros -->
									
									<table style="width: 928px">
										<tr>
											<th>Nombre</th>
											<th>Categoria [-SubCategoria]</th>                   
											<th width="10%">Opciones</th>
										</tr>
									 <? if (count($datos) == 0) { ?>
											<tr><td colspan="5" align="center">No existen torneos</td></tr>
									 <? } else { 
										 	$total = count($datos);	
											$tt = $total - 1;
											$oObj = new TorneoCat();
											for ( $i = 0; $i < $total; $i++ ) {
												$aCategorias = $oObj->getByTorneoSub($datos[$i]["id"]);
												if(sizeof($aCategorias) > 0) {
								                     foreach ($aCategorias as $categoria) { ?>
								                     	<tr>
				                    						<td align="left" style="text-align: inherit; color: <?=$datos[$i]["rgb"]?>"><b><?=$datos[$i]["nombre"]?></b></td>
				                    						<td align="left">
								                      <?  if ($categoria[nombreCatPagina] != "") { 	
								                     	   		echo $categoria[nombreCatPagina]." - ".$categoria[nombrePagina]."<br>";
								                     	   } else {
																echo $categoria[nombrePagina]."<br>";
														   } ?>
														   </td>
									                       <td nowrap>
									                  	    	<a href="javascript:listar(<?=$categoria["id"]?>);"><img border="0" src="images/categorias.gif" alt="Listar Libre y WO" title="Listar Libre y WO"  width="20" height="20"/></a>
									                       </td>
									                    </tr>
													 <?	}
							                     	}                       
											    } 
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
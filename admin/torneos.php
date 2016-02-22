<?	include_once "include/config.inc.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";
	include_once "../model/categorias.php";
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Secciones";

	switch ($_POST["accion"]) {

		case "editar":
			include("torneos.edit.php");
			exit;
			break;
					
		case "ver":
			include("torneos.edit.php");
			exit;
			break;
			
		case "guardar":	
			$data =   $_POST;
			$files = $_FILES;
			$oObj = new Torneos();
			$oObj->set($data);	
			if($_POST["id"] == "-1") {
				$oObj->insertar($files);
			} else {
				$oObj->actualizar($files);
			}		
			break;

		case "info":
			include("torneos.categorias.php");
			exit;
			break;				
		
		case "borrar":
			$data =   $_POST;
			$oObj = new Torneos();
			$oObj->set($data);
			$oObj->eliminar();
			$_POST["_pag"] = ($_POST["ult"] == "S") ? $_POST["_pag"] - 1 : $_POST["_pag"];
			break;

		case "editarCategoria":
			include("torneos.categorias.edit.php");
			exit;
			break;

		case "infoSubcategoria":
			include("torneos.subcategorias.php");
			exit;
			break;
			
		case "editarSubcategorias":
			include("torneos.subcategorias.edit.php");
			exit;
			break;

		case "guardarCategoria":	
			$data =   $_POST;
			$oObj = new TorneoCat();
			$oObj->set($data);
			$id_torneo_categoria = $_POST["id_torneo_categoria"];
			if($_POST["id_torneo_categoria"] == -1) {
				$id_torneo_categoria = $oObj->insertar();
			} else {
				$oObj->actualizar();
			}
			include("torneos.categorias.php");
			exit;
			break;

		case "guardarSubCategoria":	
			$oObj = new TorneoCat();
			$oObj -> id_torneo = $_POST['id_torneo'];
			$oObj -> id_categoria = $_POST['id_subcategoria'];
			$oObj -> id_padre = $_POST['id_categoria'];			
			$id_torneo_categoria = $oObj->insertar();
			$oObj->actualizarPadre();				
			include("torneos.subcategorias.php");
			exit;
			break;


		case "guardarPagina":	
			$files = $_FILES;
			$data =   $_POST;
			$oObj = new Pantallas();
			$oObj->idTorneoCat = $_POST["id_torneo_categoria"];
			$id_torneo_categoria = $_POST["id_torneo_categoria"];
			if($_POST["idPagina"] == -1) {
				 $oObj->insertar($files );
			} else {
				$oObj->actualizar($files);
			}
			include("torneos.categorias.php");
			exit;
			break;

		case "borrarCategoria":			
			$data =   $_POST;
			$oObj = new TorneoCat();
			$oObj->id = $_POST['id_torneo_categoria'];			
			$oObj->eliminar();
			include("torneos.categorias.php");
			exit;
			break;
			
		case "cambiarActivo":	
			$oObj = new Torneos();
			$oObj->cambiarActivo($_POST["id"],$_POST['activo']);		
			break;
			
		case "cambiarOrden":	
			$oObj = new Torneos();
			$oObj->cambiarOrden($_POST["pos"],$_POST['orden']);			
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
	
		function nuevo(){
			document.frm_listado.accion.value = "editar";
			document.frm_listado.id.value = "-1";
			document.frm_listado.submit();
			
		}
	
		function editar(id){
			document.frm_listado.accion.value = "editar";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
		
		function ver(id){
			document.frm_listado.accion.value = "ver";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
			
		
		function idioma(id){
			document.frm_listado.accion.value = "idioma";
			document.frm_listado.submenu.value = "Idiomas";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
		
		function borrar(id){
			if (confirm('Confirma que quiere eliminar el torneo')) {
				document.frm_listado.accion.value = "borrar";
				document.frm_listado.id.value = id;
				document.frm_listado.submit();
			}
		}
	
		function info(id){
			document.frm_listado.accion.value = "info";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
		}
	
		function cambiarActivo(id,activo) {
			document.frm_listado.activo.value=activo;
			document.frm_listado.id.value = id;
			document.frm_listado.accion.value = "cambiarActivo";
			document.frm_listado.submit();
		   
		}
		function cambiarOrden(pos,orden) {
			document.frm_listado.pos.value=pos;
			document.frm_listado.orden.value =orden;
			document.frm_listado.accion.value = "cambiarOrden";
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
									
									<div align="right" style="margin-right:10px; margin-bottom:10px" >
						            	<input class="button" onclick="javascript:nuevo()" type="button" value="Nuevo Torneo" />
						            </div>
									<table style="width: 928px">
										<tr>
											<th>Nombre</th>
											<th>Categoria [-SubCategoria]</th>
											<th width="5%">Orden</th>                                        
											<th width="5%">Activo</th>                    
											<th width="10%">Opciones</th>
										</tr>
									 <? if (count($datos) == 0) { ?>
											<tr><td colspan="5" align="center">No existen torneos</td></tr>
									 <? } else { 
										 	$total = count($datos);	
											$tt = $total - 1;
											for ( $i = 0; $i < $total; $i++ ) {
												$oObj = new TorneoCat();
												$aCategorias = $oObj->getByTorneoSub($datos[$i]["id"]);?>
											<tr>
                    							<td align="left" style="text-align: inherit; color: <?=$datos[$i]["rgb"]?>"><img align="middle" width="80px" height="80px" src="../logos/<?=$datos[$i]["logoPrincipal"]?>" /><b><?=$datos[$i]["nombre"]?></b></td>
                    							<td align="left">
							                     <? if(sizeof($aCategorias) > 0) {
								                     	foreach ($aCategorias as $categoria) {
								                     	   if ($categoria[nombreCatPagina] != "") { 	
								                     	   		echo $categoria[nombreCatPagina]." - ".$categoria[nombrePagina]."<br>";
								                     	   } else {
																echo $categoria[nombrePagina]."<br>";
														   }
													 	}
							                     	}  ?>
							                     </td>
                    							 <td>
							                     <? if ( $i>0) { ?>
							                     	 <div style="float:left"><img border="0" src="images/icono-up.gif" alt="Subir" title="Subir" style="cursor:pointer" onclick="cambiarOrden('<?=$datos[$i]['orden']?>','-1')"/></div>                 
												<? } ?>
												 <? if ( $i< $tt) { ?>
								                     <div style="float:right"><img border="0" src="images/icono-down.gif" alt="Bajar" title="Bajar" style="cursor:pointer" onclick="cambiarOrden('<?=$datos[$i]['orden']?>','1')"/></div>
							                     <? } ?>
							                     </td>
                     							 <td align="center">
                     								<? if($datos[$i]["activo"] == '1') {?><div align="center"><img border="0" src="images/check.ico" alt="Activo" title="Activo" style="cursor:pointer"   onclick="cambiarActivo('<?=$datos[$i]['id']?>','0')"/></div> <? } else { ?><div align="center"><img border="0" src="images/forbidden.ico" alt="No Activo" title="No Activo" style="cursor:pointer" onclick="cambiarActivo('<?=$datos[$i]['id']?>','1')"/></div><? } ?>
							                     </td>
							                      <td nowrap>
							                        <a href="javascript:ver(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
							                        <a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
												    <a href="javascript:info(<?=$datos[$i]["id"]?>);"><img border="0" src="images/categorias.gif" alt="Categorias" title="Categorias"  width="20" height="20"/></a>
							                        <a href="javascript:borrar(<?=$datos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
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
<?	include_once "include/config.inc.php";
	include_once "../model/equipos.php";
	include_once "../model/jugadoras.php";
	include_once "../model/resultados.php";
	include_once "../model/torneos.categorias.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$menu = "Secciones";
	
	switch ($_POST["accion"]) {
		
		case "password":
			include("equipos.torneos.password.php");
			exit;
			break;

		case "editar":
			include("equipos.edit.php");
			exit;
			break;
			
		case "ver":
			include("equipos.edit.php");
			exit;
			break;
			
		case "guardarPassword":
			$oEquipo = new Equipos();
			$oEquipo->setPassword($_POST['idTorneoEquipo'],$_POST['id'],$_POST['pass']);
			include("equipos.torneos.php");
			exit;
			break;
			
		case "torneos":
			include("equipos.torneos.php");
			exit;
			break;
			
		case "jugadoras":
			include("equipos.torneos.jugadoras.php");
			exit;
			break;
		
		case "relacionarTorneo":	
			include("equipos.torneos.edit.php");
			exit;
			break;
			
		case "historial":
			include("equipos.torneos.historial.php");
			exit;
			break;
			
		case "cambiaractiva":
			$oObj = new Jugadoras();
			$oObj->cambiarActiva($_POST["idJugadoraEquipo"],$_POST['activa']);
			include("equipos.torneos.jugadoras.php");
			exit;
			break;
			
		case "activarenvio":
			$oObj = new Jugadoras();
			$oObj->activarEnvioMail($_POST["idJugadoraEquipo"],$_POST['envio']);
			include("equipos.torneos.jugadoras.php");
			exit;
			break;

		case "borrarjugadora":
			$oObj = new Resultados();
			$oObj->borrarByIdJugadoraEquipo($_POST["idJugadoraEquipo"]);
			$oObj = new Jugadoras();
			$oObj->borrarEquipo($_POST["idJugadoraEquipo"]);
			include("equipos.torneos.jugadoras.php");
			exit;
			break;
			
		case "guardar":	
			$data =   $_POST;
			$files = $_FILES;
			$oObj = new Equipos();
			$oObj->set($data);
			if($_POST["id"] == "-1") { 
				$oObj->insertar($files);
			} else {
				$oObj->actualizar($files);
			}
			break;

		case "borrar":
			$data =   $_POST;
			$oObj = new Equipos();
			$oObj->set($data);
			$oObj->eliminar();
			$_POST["_pag"] = ($_POST["ult"] == "S") ? $_POST["_pag"] - 1 : $_POST["_pag"];
			break;
			
		case "eliminarTorneo":
			$data = $_POST['idTorneoEquipo'];
			$oObj = new Equipos();
			$oObj->eliminarRelacionTorneo($data);
			include("equipos.torneos.php");
			exit;
			break;
		
		case "guardarTorneo":
			$oObj = new Equipos();
			if($_POST["idTorneoEquipo"] == "-1") { 
				$oObj->guardarRelacionTorneo($_POST);
			} else {
				$oObj->actualizarRelacionTorneo($_POST);
			}
			include("equipos.torneos.php");
			exit;
			break;
			
		case "importarJugadoras":
			include("equipos.torneos.importar.php");
			exit;
			break;	
			
		case "importar":
			include("equipos.torneos.seleccionarImp.php");
			exit;
			break;
			
		case "guardarImportar":
			include("equipos.torneos.guardarImp.php");
			include("equipos.torneos.jugadoras.php");
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
	$oObj = new Equipos();
	$datos = $oObj->getPaginado($_REQUEST, $inicio, $cant, $total);
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
		
		function torneos(id){
			document.frm_listado.accion.value = "torneos";
			document.frm_listado.id.value = id;
			document.frm_listado.submit();
			
		}
		
		function borrar(id){
			if (confirm('Confirma que quiere eliminar el equipo')) {
				document.frm_listado.accion.value = "borrar";
				document.frm_listado.id.value = id;
				document.frm_listado.submit();
			}
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
			        			<!-- Parametros menu -->
			        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
			                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
			                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
			                    <!--     -->
		                        
		                        <div class="formbody">
		                          Nombre: <input name="fnombre" type="text" style="width:100px" value="<?=$_POST["fnombre"]?>"  />
		                          Email: <input name="femail" type="text" style="width:100px" value="<?=$_POST["femail"]?>"  />
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
			                    <input type="hidden" name="idTorneoEquipo" value="" />

			        			<!-- Parametros menu -->
			        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
			                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
			                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
			                    <!--     -->
			                    <!-- Filtros -->
			                    <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
			                    <input name="femail" type="hidden"  value="<?=$_POST["femail"]?>"  />                        
			                    <!-- Fin filtros -->

								<div style="margin-left:20px; float:left" >
								<? if ($total != 0) {
									   if ( $pag > 1 ) {?>
										 <a href="javascript: document.frm_listado._pag.value=<?=$pag-1?>;  document.frm_listado.submit();"><img src="images/icono-anterior-on.gif" alt="anterior" title="anterior" /></a>
									<? } else {?>
										 <img src="images/icono-anterior-off.gif" alt="anterior" title="anterior" />
									<? }
								   if ( $pag < $total ) {?>						 
										 <a href="javascript: document.frm_listado._pag.value=<?=$pag+1?>;  document.frm_listado.submit();"><img src="images/icono-siguiente-on.gif" alt="siguiente" title="siguiente" /></a>
								    <? } else {?>
										 <img src="images/icono-siguiente-off.gif" alt="siguiente" title="siguiente" />
									<? }?>																 									  
               						<span>P&aacute;gina <? echo $pag; ?> de <? echo $total; ?>&nbsp;&nbsp;N&uacute;mero de p&aacute;gina 
					    				<select style="width:40px"  name="nro_pag" id="nro_pag" onchange="document.frm_listado._pag.value=this.value;  document.frm_listado.submit();" >
										 <? for($p=1; $p<=$total; $p++) { ?>
											 	<option <? if ($p == $pag) echo "selected"; ?> value="<?=$p?>"><?=$p?></option>
										<? } ?>
										</select>
                    				</span>
								<? } ?>							  
								</div>
								<div align="right" style="margin-right:10px; margin-bottom:10px" >
					            	<input class="button" onclick="javascript:nuevo()" type="button" value="Nuevo Equipo" />
					            </div>

								<table style="width: 928px">
									<tr>
										<th>Nombre</th>    
										<th>Foto</th>            
										<th width="10%">Opciones</th>
									</tr>
								 <? if (count($datos) == 0) { ?>
									<tr><td colspan="5" align="center">No existen equipos</td></tr>         
								 <? } else { 
				 						$total = count($datos);	
										$tt = $total - 1;
										for ( $i = 0; $i < $total; $i++ ) { ?>
									<tr>
					                     <td align="left"><?=$datos[$i]["nombre"]?></td>
										 <td width="100">
										  <? if($datos[$i]['foto']) { ?>
								          	<img src="../thumb/phpThumb.php?src=../fotos_equipos/<?= $datos[$i]['foto']?>" width="100" height="69"/>
								          <? } else { ?>
								          		SIN FOTO
								          <? } ?>
										 </td>
					                      <td nowrap>
					                        <a href="javascript:ver(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
					                        <a href="javascript:editar(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
										    <a href="javascript:torneos(<?=$datos[$i]["id"]?>);"> <img border="0" src="images/categorias.gif" alt="torneos" title="torneos"  width="20" height="20"/></a>
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
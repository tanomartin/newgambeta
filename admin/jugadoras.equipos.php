<?	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/jugadoras.php";
	include_once "../model/torneos.categorias.php";
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}

	$oObj = new Jugadoras();
	$jugadora = $oObj->get($_POST['id']);
	$datos = $oObj->getEquiposById($_POST['id']);
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
		function nuevo(id){
			document.frm_listado.accion.value = "editarequipos";
			document.frm_listado.id.value = id;
			document.frm_listado.idJugadoraEquipo.value = -1;
			document.frm_listado.submit();	
		}

		function editar(id, idJugadoraEquipos){
			document.frm_listado.accion.value = "editarequipos";
			document.frm_listado.id.value = id;
			document.frm_listado.idJugadoraEquipo.value = idJugadoraEquipos;
			document.frm_listado.submit();	
		}

		function ver(id, idJugadoraEquipos){
			document.frm_listado.accion.value = "verequipos";
			document.frm_listado.id.value = id;
			document.frm_listado.idJugadoraEquipo.value = idJugadoraEquipos;
			document.frm_listado.submit();	
		}

		function cambiaractiva(idJugadoraEquipos, activa){
			document.frm_listado.accion.value = "cambiaractiva";
			document.frm_listado.activa.value = activa;
			document.frm_listado.idJugadoraEquipo.value = idJugadoraEquipos;
			document.frm_listado.submit();	
		}

		function borrar(idJugadoraEquipos){
			if (confirm('Confirma que quiere eliminar la relacion con el equipo')) {
				document.frm_listado.accion.value = "borrarerquipo";
				document.frm_listado.idJugadoraEquipo.value = idJugadoraEquipos;
				document.frm_listado.submit();
			}
		}
	
		function volver(){
			document.frm_listado.accion.value = "volver";
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
							<h1>Equipos donde juega <font color="#e4790f"><?=$jugadora[0]["nombre"]?></font></h1>
						</div>
						<div class="ce_text block">
							<div class="mod_listing ce_table listing block" id="partnerlist">
                    			<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                    				<input type="hidden" name="_pag" value="<?=$pag?>" />
				                    <input type="hidden" name="id" value="<?=$_POST["id"]?>" />
				                    <input type="hidden" name="idJugadoraEquipo" value="" />
				                    <input type="hidden" name="activa" value="" />
				                    <input type="hidden" name="pos" value="" />
				                    <input type="hidden" name="accion" value="" />
				        			<!-- Parametros menu -->
				        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
				                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
				                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
				                    <!--     -->
				                    <!-- Filtros -->
									<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
									<input type="hidden" name="fdni" value="<?=$_POST["fdni"]?>"  />
									<!-- Fin filtros -->
									
									<div align="right" style="margin-right:10px; margin-bottom:10px" >
						            	<input class="button" onclick="javascript:nuevo(<?=$_POST["id"]?>)" type="button" value="Asignar Nuevo Equipo" />
						            </div>
									<table style="width: 928px">	
										<tr>
											<th>Torneo</th>
											<th>Categoria</th>                                        
											<th>Equipo</th>                                        
											<th>Posicion</th>  
											<th>Activa</th>   
											<th width="10%">Opciones</th>
										</tr>
									<? if (count($datos) == 0) { ?>	
											<tr><td colspan="6" align="center">No tiene equipos asignados</td> </tr>
									<? } else { 
										 	$total = count($datos);	
											$tt = $total - 1;
											for ( $i = 0; $i < $total; $i++ ) { ?>
												 <tr>
							                     <td align="left"><?=$datos[$i]["torneo"]?></td>
							                     <td align="left"><?=$datos[$i]["categoria"]?></td>
							                     <td align="left"><?=$datos[$i]["nombreEquipo"]?></td>
							                     <td align="left"><?=$datos[$i]["posicion"]?></td>
							                     <? if($datos[$i]["activa"] == '1') {?>
							                     		<td style="text-align: center;"><img border="0" src="../img/check.ico" alt="activa" title="activa" style="cursor:pointer" onclick="cambiaractiva('<?=$datos[$i]["idJugadoraEquipo"]?>','0')"/></td>
							                     <? } else { ?>
														<td style="text-align: center;"><img border="0" src="../img/forbidden.ico" alt="No activa" title="No activa" style="cursor:pointer" onclick="cambiaractiva('<?=$datos[$i]["idJugadoraEquipo"]?>','1')"/></td>
												 <? } ?>
							                     <td nowrap>
							                     	<a href="javascript:ver(<?=$_POST["id"]?>,<?=$datos[$i]["idJugadoraEquipo"]?>);"> <img border="0" src="images/find-icon.png" alt="ver" title="ver" width="20px" height="20px" /></a>
							                        <a href="javascript:editar(<?=$_POST["id"]?>,<?=$datos[$i]["idJugadoraEquipo"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
												    <a href="javascript:borrar(<?=$datos[$i]["idJugadoraEquipo"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
							 					 </tr>
										 <? } 
									 	}?>
									</table>
									<div class="submit_container">
										<input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
								 	</div>
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
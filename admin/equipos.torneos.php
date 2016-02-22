<?	
	include_once "include/config.inc.php";
	include_once "include/fechas.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
	include_once "../model/equipos.php";	
    include_once "../model/fckeditor.class.php" ;
	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}
	$oEquipo= new Equipos();
	$datos = $oEquipo->get($_POST["id"]);
    $torneos = $oEquipo->getTorneos($_POST["id"]);	
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
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script>
			
		function volver(){
			document.form_alta.submit();
		}
	
		function borrarTorneo(idTorneo) {
			if (confirm('Confirma que quiere eliminar el torneo en el que juega')) {
				document.form_alta.accion.value = "eliminarTorneo";
				document.form_alta.idTorneoEquipo.value = idTorneo;
				document.form_alta.submit();
			}
		}
	
		function editarTorneo(idTorneo) {
			document.form_alta.accion.value = "relacionarTorneo";
			document.form_alta.idTorneoEquipo.value = idTorneo;
			document.form_alta.submit();
		}
	
		function relacionarTorneo(idTorneo) {
			document.form_alta.accion.value = "relacionarTorneo";
			document.form_alta.idTorneoEquipo.value = -1;
			document.form_alta.submit();
		}

		function jugadoras(idTorneo, idTorneoCat){
			document.form_alta.accion.value = "jugadoras";
			document.form_alta.idTorneoEquipo.value = idTorneo;
			document.form_alta.idTorneoCat.value = idTorneoCat;
			document.form_alta.submit();	
		}
		
		function password(idTorneo){
			document.form_alta.accion.value = "password";
			document.form_alta.idTorneoEquipo.value = idTorneo;
			document.form_alta.submit();	
		}

		function historial(idTorneo, idTorneoCat){
			document.form_alta.accion.value = "historial";
			document.form_alta.idTorneoEquipo.value = idTorneo;
			document.form_alta.idTorneoCat.value = idTorneoCat;
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
							<h1>Listado de Torneos del equipo <font color="#e4790f"><?=$datos[0]['nombre']?></font></h1>
						</div>
						<div align="right" style="margin-right:20px" >
            				<input class="button" onclick="javascript:relacionarTorneo()" type="button" value="Relacionar Torneo" />
           				</div>
						<div class="mod_listing ce_table listing block" id="partnerlist">
							<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="idTorneoEquipo" value="" />
								<input type="hidden" name="idTorneoCat" value="" />
								<input type="hidden" name="accion" value="" />
								<input type="hidden" name="id" value="<?=$_POST["id"]?>" />
								<!-- Parametros menu -->
			        			<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
			                    <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
			                    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
			                    <!--     -->
			                    <!-- Filtros -->
				                <input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				                <input name="femail" type="hidden"  value="<?=$_POST["femail"]?>"  />                           
				                <!-- Fin filtros -->
			                    
			                    <table style="width: 928px">
			                    	<tr>
										<th>Torneo</th>
										<th>Categoria</th> 
										<th>Descuento Puntos</th>   
										<th>Clave</th>        
										<th width="10%">Opciones</th>
									</tr>
			                    	<? if (count($torneos) == 0) { ?>
									<tr>
											<td colspan="5" align="center">Este equipo no esta inscripto en ningun Torneos</td>
								    </tr>
									<? } else { 
										 	$total = count($torneos);	
											$tt = $total - 1;
											for ( $i = 0; $i < $total; $i++ ) {
												$pass = $oEquipo->getPassword($torneos[$i]["id"]);?>
												<tr>
							                     <td align="left"><?=$torneos[$i]["torneo"]?></td>
												 <td align="left"><?=$torneos[$i]["categoria"]?></td>
												 <td align="left"><?=$torneos[$i]["descuento_puntos"]?></td>
							                     <td align="left"><?=$pass[0]['password'];?></td>
							                     <td nowrap>
							                        <a href="javascript:editarTorneo(<?=$torneos[$i]["id"]?>);"> <img border="0" src="images/icono-editar.gif" alt="editar" title="editar" /></a>
							                        <a href="javascript:borrarTorneo(<?=$torneos[$i]["id"]?>);"><img border="0" src="images/icono-eliminar.gif" alt="eliminar" title="eliminar" /></a>
													<a href="javascript:jugadoras(<?=$torneos[$i]["id"]?>,<?=$torneos[$i]["idTorneoCat"]?>);"><img border="0" width="22" height="22" src="images/Person-Female-Light-icon.png" alt="jugadoras" title="jugadoras" /></a>
												 	<a href="javascript:password(<?=$torneos[$i]["id"]?>);"><img border="0" src="images/icono-pass.png" alt="contraseÃ±a" title="contraseÃ±a" /></a>	
												 	<a href="javascript:historial(<?=$torneos[$i]["id"]?>,<?=$torneos[$i]["idTorneoCat"]?>);"><img border="0" src="images/History-icon.png"  width="23" height="23" alt="historial" title="historial" /></a>									 	
												 </td>
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
		<? include("pie.php")?>
	</div>
</body>

</html>
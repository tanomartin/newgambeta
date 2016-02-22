<?

include_once "include/config.inc.php";
include_once "../model/torneos.categorias.php";
include_once "../model/equipos.php";

if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}

$menu = "Secciones";

if ($_POST ['id_torneo_categoria'] != '-1') {
	$oTorCat = new TorneoCat ();
	$torneo = $oTorCat->getByIdCompleto ( $_POST ['id_torneo_categoria'] );
	
	$oEquipo = new Equipos ();
	$equipos = $oEquipo->getTorneoCat ( $_POST ['id_torneo_categoria'] );
	
	if ($equipos != NULL) {
		foreach ( $equipos as $equipo ) {
			$id = $equipo ['id'];
			$i = 0;
			$arrayCorreos = array();
			$correos = $oEquipo->getCorreos($equipo ['idEquipoTorneo']);
			if ($correos != NULL) {
				foreach ($correos as $correo) {
					$arrayCorreos[$i] = $correo[email];
					$i++;
				}
			}
			$equiposMail [$id] = array (
					'id_equipo' => $id,
					'nombre' => $equipo ['nombre'],
					'email' => $arrayCorreos
			);
		}
	}
} else {
	$oEquipo = new Equipos ();
	$equipos = $oEquipo->getEquiposSinTorneo ();
	if ($equipos != NULL) {
		foreach ( $equipos as $equipo ) {
			$id = $equipo ['id'];
			$i = 0;
			$arrayCorreos = array();
			$correos = $oEquipo->getCorreos($equipo ['idEquipoTorneo']);
			if ($correos != NULL) {
				foreach ($correos as $correo) {
					$arrayCorreos[$i] = $correo[email];
					$i++;
				}
			}
			$equiposMail [$id] = array (
					'id_equipo' => $id,
					'nombre' => $equipo ['nombre'],
					'email' => $arrayCorreos 
			);
		}
	}
}

?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<title>Panel de Control</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="Panel de Control." />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	
	<? include("encabezado.php"); ?>
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.blockUI.js"></script>
	<script>
		
		function enviar() {
			var cuerpo = document.frm_listado.cuerpocorreo.value.length;
			var asunto = document.frm_listado.asuntocorreo.value;
			if (cuerpo == 0) {
				$('#errorcuerpo').html("* Debe ingresar un cuerpo del mensaje");
			}
			if (asunto == "") {
				$('#errorasunto').html("* Debe ingresar el asunto del mensaje");
			}
			if (cuerpo == 0 || asunto == "") {
				return false;
			} else {
				$.blockUI({ message: "<h1>Enviando Correos.<br> Aguarde por favor</h1>" });
				document.frm_listado.accion.value = "enviarcorreo";		
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
					<div class="ce_text block">
						<h1>Mailing: <?=$torneo->torneo." - ".$torneo->nombrePagina ?> <? if ($torneo->nombreCatPagina != "" ) { echo " - ".$torneo->nombreCatPagina;}?></h1>
						<img width="75" border="0" alt="volver" title="volver" onclick="javascript:volver();" style="cursor: pointer; float: right;" src="images/back-icon.png" />
						<img width="75" border="0" alt="enviar" title="Enviar" onclick="javascript:enviar();" style="cursor: pointer; float: right;" src="images/send_mail.png" /> 
					</div>
					<div class="mod_article block" id="home">
						<div class="mod_listing ce_table listing block" id="partnerlist" align="center">
							<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<input type="hidden" name="id_torneo_categoria" value="<?=$_POST["id_torneo_categoria"]?>" /> 
							 	<input type="hidden" name="accion" value="<?=$_POST["action"]?>" /> 
							 	<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" /> 
							 	<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
								<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" /> 
								<input type="hidden" name="equiposMail" value="<?=urlencode(serialize($equiposMail)) ?>" />
								<div align="center" style="float: left">
									<table id="mail" style="width: 450px">
										<tr>
											<th>Equipos</th>
											<th>Email</th>
											<th width="8%">Sacar</th>
										</tr>
								<? 	if (sizeof ( $equipos ) == 0) { ?>
											<tr><td colspan='3'>No hay equipos</td></tr>
								<?	} else {
										foreach ( $equiposMail as $equipo ) { ?>
											<tr>
												<td style="font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 17px"><?=$equipo['nombre'] ?></td>
												<td><? if (sizeof($equipo['email']) != 0) {
														foreach ($equipo['email'] as $correo) {
															echo "- ".$correo."<br>";
														}
													   }  ?>
												</td>
												<td><? if (sizeof($equipo['email']) != 0) { ?> 
													<input type="checkbox" id="<?=$equipo['id_equipo'] ?>" name="<?=$equipo['id_equipo'] ?>" value="<?=$equipo['id_equipo'] ?>" /> <? } ?>
												</td>
											</tr>	
									 <? }
									} ?>
									</table>
								</div>
								<div align="center" style="float: right; width: 470px">
									<table id="cuerpo">
										<tr>
											<th>Correo</th>
										</tr>
										<tr>
											<td>
												<div align="center" style="float: right">
													<div align="left">
														<h2>Asunto</h2>
														<div id="errorasunto" style="color: #CC3300; font-weight: bold" align="left"></div>
														<input type="text" id="asuntocorreo" name="asuntocorreo" size="55" />
														<h2>Cuerpo</h2>
														<div id="errorcuerpo" style="color: #CC3300; font-weight: bold" align="left"></div>
														<textarea id="cuerpocorreo" name="cuerpocorreo" cols="50"></textarea>
													</div>
												</div>
											</td>
										</tr>
									</table>
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
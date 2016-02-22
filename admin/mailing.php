<?

include_once "include/config.inc.php";
include_once "../model/torneos.categorias.php";
include_once "../model/torneos.php";

if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}

$menu = "Secciones";
$oObj = new TorneoCat ();
$torneosCategoria = $oObj->getCategoriasCompletas ();

switch ($_POST ["accion"]) {
	case "listado" :
		include ("mailing.listado.php");
		exit ();
		break;
	
	case "enviarcorreo" :
		include ("mailing.enviarcorreo.php");
		exit ();
		break;
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
	
	<script>
		
		function listado(id){
			document.frm_listado.accion.value = "listado";
			document.frm_listado.id_torneo_categoria.value = id;
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
							<div class="mod_listing ce_table listing block" id="partnerlist" align="center">
								<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
									<input type="hidden" name="id_torneo_categoria" value="" /> 
									<input type="hidden" name="accion" value="" /> 
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" /> 
									<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
									<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
								</form>
								<table style="width: 800px">
									<tr>
										<th>Torneo</th>
										<th>Categor&iacute;a</th>
										<th width="40px"></th>
									</tr>
									<tr>
										<td>SIN TORNEO</td>
										<td>SIN CATEGORIA</td>
										<td nowrap>
											<a href="javascript:listado('-1');"> 
												<img border="0" src="images/eml-icon.png" alt="Crear Correo" title="Crear Correo" width="40px" height="40px" />
											</a>
										</td> 
									</tr>
										<? if (count($torneosCategoria) == 0) { ?>
											<tr>
												<td colspan="3" align="center">No existen categor&iacute;as</td>
											</tr>
										<? } else {
												foreach ( $torneosCategoria as $torneo ) {
													$oTorneo = new Torneos ();
													$verficAct = $oTorneo->get ( $torneo ["id_torneo"] ); ?>
														<tr>
															<td align="left"><?=$torneo["nombreTorneo"]?></td>
															<td align="left"><?=$torneo["nombrePagina"]?> <? if ($torneo["nombreCatPagina"] != "" ) { echo " - ". $torneo["nombreCatPagina"];} ?></td>
															<td nowrap>
																<a href="javascript:listado(<?=$torneo["id"]?>);">
																	<img border="0" src="images/eml-icon.png" alt="Crear Correo" title="Crear Correo" width="40px" height="40px" />
																</a>
															</td>
														</tr>
											 <? }
											}?>
								</table>
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
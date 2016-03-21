<?

include_once "include/config.inc.php";
include_once "include/fechas.php";
include_once "../model/sedes.php";
include_once "../model/fixture.php";
include_once "../model/categorias.php";
include_once "../model/arbitros.php";

if (!isset( $_SESSION['usuario'])) {
	header("Location: index.php");
	exit;
}
$menu = "Secciones";

$fechaPartidos = $_POST ['fechaPartido'];
$fechaPartidosSql = cambiaf_a_mysql ( $fechaPartidos );
$idArbitro = $_POST ['id'];

$oArbitro = new Arbitros();
$arbitro = $oArbitro->get($idArbitro);

$oSede = new Sedes ();
$aSedes = $oSede->get ();
$oCetegoria = new Categorias ();

$oFixture = new Fixture ();
$listadoPartidos = $oFixture->getByIdArbitro ( $fechaPartidosSql, $idArbitro );
for($i = 0; $i < sizeof ( $listadoPartidos ); $i ++) {
	
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
	
		function listarPartidos() {
			document.frm_busqueda.accion.value = "puntaje";	
			document.frm_busqueda.submit();	
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
							<form name="frm_busqueda" id="frm_busqueda" action="<?=$_SERVER['PHP_SELF']?>" method="post">
								<div class="formbody">
									<input type="hidden" name="id" value="<?=$_POST["id"]?>" />
									<input type="hidden" name="listadoPartidos" value="<?=urlencode(serialize($listadoPartidos)) ?>" /> 
									<input type="hidden" name="accion" value="" /> 
									Fecha: <input name="fechaPartido" type="text" id="fechaPartido" size="11" value="<?=$_POST["fechaPartido"] ?>" readonly="readonly" />
									<a href="javascript:show_calendar('document.frm_busqueda.fechaPartido', document.frm_busqueda.fechaPartido.value);">
										<img src="_js/calendario2/cal.gif" width="16" height="16" border="0" />
									</a>
								  	<input class="submit" value="Listar" type="submit" style="font-size: 11px" onclick="javascript:listarPartidos();" /> 
								</div>
							</form>
                    	<? 	if ($listadoPartidos != NULL) { ?>
						 		<div class="ce_text block">
									<h1>Listado de Partidos de <?php echo $arbitro[0]['nombre'] ?> </h1>
								</div>
								<div class="mod_listing ce_table listing block" id="partnerlist">
									<form name="frm_listado" id="frm_listado" action="<?=$_SERVER['PHP_SELF']?>" method="post">
										<table style="width: 930px">
											<tr>
												<th>Hora</th>
												<th>Torneo</th>
												<th>Equipo 1 (G)</th>
												<th>P1</th>
												<th>Equipo 2 (G)</th>
												<th>P2</th>
												<th>Promedio</th>
											</tr>
											<? foreach ($listadoPartidos as $partido) {
													$promedio = round(($partido["puntaje2"]+$partido["puntaje1"])/2,2);
													$sumaPromedio += $promedio?>
												<tr>
													<td><?=$partido["horaPartido"]?></td>
													<td><?=$partido["torneo"]."-".$partido["categoria"].$partido["zona"]?></td>
													<td style="text-align: center;"><?=$partido["equipo1"]." (".$partido["goles1"].")"?></td>
													<td style="text-align: center;"><?=$partido["puntaje1"]?></td>
													<td style="text-align: center;"><?=$partido["equipo2"]." (".$partido["goles2"].")"?></td>
													<td style="text-align: center;"><?=$partido["puntaje2"]?></td>
													<td style="text-align: center;"><?=$promedio?></td>
												</tr>
											<? }?>
												<tr>
													<td colspan="6" style="text-align: right;"><b>Promedio del Dia</b></td>
													<td style="text-align: center;"><b><?= round($sumaPromedio/sizeof($listadoPartidos),2) ?></b></td>
												</tr> 
										</table>
									</form>
								</div>
						<?	} elseif ($fechaPartidos != NULL && $id_sede != - 1) {
								$sede = $oSede->get ( $id_sede ); ?>
								<h1>No hay de Partidos para <?php echo $arbitro[0]['nombre'] ?> para la Fecha <?=$fechaPartidos?></h1>
						<?  } ?>	
						</div>
					</div>
				</div>
			</div>
		</div>
	<? include("pie.php")?>
	</div>
</body>
</html>
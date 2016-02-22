<?

include_once "include/config.inc.php";
include_once "../model/fechas.php";
include_once "../model/torneos.categorias.php";
include_once "../model/reservas.php";
include_once "../model/equipos.php";

if (! session_is_registered ( "usuario" )) {
	header ( "Location: index.php" );
	exit ();
}

$oFecha = new Fechas ();
$fecha = $oFecha->get ( $_POST ['id'] );

$oEquipo = new Equipos ();
$equipo = $oEquipo->get ( $_POST ['id_equipo'] );

$fechaLibre = $oEquipo->tieneFechaLibre ( $fecha [0] ['idTorneoCat'], $_POST ['id_equipo'] );
$horas_fecha = $oFecha->getHorasCancha ( $_POST ['id'] );

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
	<script>
	
		function cargarReserva(equipo,fecha) {
			document.form_alta.accion.value = "cargar";
			document.form_alta.id.value = id;
			document.form_alta.submit();
		}
	
		function volver(){
			document.form_alta.accion.value = "reservas";		
			document.form_alta.submit();
		}
		
		function seleccionarTodo() {
			var selectall = document.getElementById("form_alta").selectall;
			var checkall = false;
			if (selectall.checked) {
				checkall = true;
			} 
			var grupo = document.getElementById("form_alta").horas;
			for (var i = 0; lcheck = grupo[i]; i++) {
				lcheck.checked = checkall;
			}	
			controlLibre();
		}
		
		function controlLibre() {
			$('#error').html("");
			var grupo = document.getElementById("form_alta").horas;
			var controlCheck = 0;
			var libre = document.getElementById("libre");
			var libregambeta = document.getElementById("libregambeta");
			
			for (var i = 0; lcheck = grupo[i]; i++) {
				if (lcheck.checked) {
					controlCheck = 1;
				}
			}
			if (controlCheck == 1) {
				if (libre != null) {
					libre.disabled = true;
				}
				libregambeta.disabled = true;
			} else {
				if (libre != null) {
					libre.disabled = false;
				}
				libregambeta.disabled = false;
			}
		}
	
		function controlHoras() {
			$('#error').html("");
			var libre = document.getElementById("libre");
			var libregambeta = document.getElementById("libregambeta");
			var grupo = document.getElementById("form_alta").horas;
			var selectall = document.getElementById("form_alta").selectall;
			var total = grupo.length;
			
			var libreChecked = 0;
			if (libre != null) {	
				if (libre.checked || libregambeta.checked) { 
					libreChecked = 1;
				}	
			} else {
				if (libregambeta.checked) { 
					libreChecked = 2;
				}
			}
			
			if(libreChecked != 0) {
				selectall.disabled = true;
				if (total == null) {
					grupo.disabled = true;
				} else {
					for (var i = 0; lcheck = grupo[i]; i++) {
						lcheck.disabled = true;
					}
				}
					
				if (libreChecked == 1) {
					if (libre.checked) {
						libregambeta.disabled = true;
					} else {
						libre.disabled = true;
					}	
				} 
			} else {
				if (libre != null) {	
					libre.disabled = false;
				}
				libregambeta.disabled = false;
				selectall.disabled = false;
				if (total == null) {
					grupo.disabled = false;
				} else {
					for (i = 0; lcheck = grupo[i]; i++) {
						lcheck.disabled = false;
					}
				}
			}
		}
		
		function validar() {
			$('#error').html("");
			var libre = document.getElementById("libre");
			var grupo = document.getElementById("form_alta").horas;
			var libregambeta = document.getElementById("libregambeta");
			if (libregambeta.checked){
				document.form_alta.accion.value = "guardarNueva";		
				document.form_alta.submit();
				return true;
			}	
			if (libre != null) {
				if (libre.checked) {
					document.form_alta.accion.value = "guardarNueva";		
					document.form_alta.submit();
					return true;
				} else {
					var controlCheck = 0;
					for (var i = 0; lcheck = grupo[i]; i++) {
						if (lcheck.checked) {
							controlCheck++;
						}
					}
					if (controlCheck < 1) {
						$('#error').html("* Debe seleccionar como mínimo 1 horas");
						return false;
					} else {
						document.form_alta.accion.value = "guardarNueva";		
						document.form_alta.submit();
						return true;
					}
				}	
			} else {
				var controlCheck = 0;
				for (i = 0; lcheck = grupo[i]; i++) {
					if (lcheck.checked) {
						controlCheck++;
					}
				}
				if (controlCheck < 1) {
					$('#error').html("* Debe seleccionar como mínimo 1 horas");
					return false;
				} else {
					document.form_alta.accion.value = "guardarNueva";		
					document.form_alta.submit();
					return true;
				}
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
		 			<div class="ce_text block">
						<h1>Nueva Reserva: <?= $fecha[0]['nombre']." - ".$fecha[0]['torneo']." - ".$fecha[0]['categoria']?> (<?= $equipo[0]['nombre'] ?>)</h1>
						<img width="75" border="0" alt="reserva" title="volver" onclick="javascript:volver();" style="cursor: pointer; float: right;" src="images/back-icon.png" />
					</div>
					<div class="mod_article block" id="home">
						<div class="ce_text block">
							<div class="mod_listing ce_table listing block" id="partnerlist">
								<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return validar()" method="post" enctype="multipart/form-data">
									<input name="id" id="id" value="<?=$_POST["id"]?>" type="hidden" /> 
									<input name="_pag" id="_pag" value="<?=$_POST["_pag"]?>" type="hidden" /> 
									<input type="hidden" name="id_equipo" id="id_equipo" value="<?=$_POST["id_equipo"]?>" /> 
									<input type="hidden" name="accion" value="guardar" />
									<!-- Filtros -->
									<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" /> 
									<input name="ftorneo" type="hidden" style="width: 100px" value="<?=$_POST["ftorneo"]?>" /> 
									<input name="fcategoria" type="hidden" style="width: 100px" value="<?=$_POST["fcategoria"]?>" />
									<!-- Fin filtros -->
									<!-- Parametros menu -->
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
									<input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" /> 
									<input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
									<!--     -->

									<div align="center" style="float: left">
									<?	if ($horas_fecha != NULL) { ?>
											<div style="float: left">
												<b>Horarios Disponibles [ CheckALL: <input type="checkbox" id="selectall" name="selectall" onclick="seleccionarTodo()" /> ]</b>
											</div>
											<br>
											<div id="error" style="color: #CC3300; font-weight: bold; margin-bottom: 10px" align="left"></div>
											<div style="text-align: left">		
											<?	$i = 1;
												foreach ( $horas_fecha as $hora ) {
													print ("<input type='checkbox' id='horas' name='hora" . $hora ["id_horas_cancha"] . "' value='" . $hora ["id_horas_cancha"] . "' onclick='controlLibre()'> " . $hora ["descripcion"] . " | </input>") ;
													$resto = $i % 4;
													if ($resto == 0) {
														print ("<br>") ;
													}
													$i ++;
												} ?>							
											</div>
					  				<?	}
							 			if (!$fechaLibre) { ?>
							 				<br>
											<div style="float: left">
												<b>Fecha Libre Equipo </b><input type='checkbox' name='libre' id='libre' value='libre' onclick='controlHoras()'></input>
											</div>
									<? } else { ?>
											<br>
											<div style="float: left; color: #CC3300">
												<b>El equipo ya pidio fecha libre</b>
											</div>
									<? } ?> 
										<br>
										<div style="float: left;">
											<b>Fecha Libre Gambeta </b><input type='checkbox' name='libregambeta' id='libregambeta' value='libregambeta' onclick='controlHoras()'></input>
										</div>
										<br>
										<div style="float: left;">
											<textarea name="observacion" id="observacion" placeholder="Observacion" cols="62" rows="4" style="float: left;"></textarea>
										</div>
										<br>
										<div style="float: left;">
											<input type="submit" name="reservar" value="Reservar" />
										</div>
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
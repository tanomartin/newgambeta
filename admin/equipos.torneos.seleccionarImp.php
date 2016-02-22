<?
include_once "include/config.inc.php";
require_once "include/PHPExcel/PHPExcel.php";
$inputFileName = $_FILES ['ficha'] ['tmp_name'];
$objPHPExcel = PHPExcel_IOFactory::load ( $inputFileName );

$oEquipo = new Equipos ();
$equipo = $oEquipo->get ( $_POST ["id"] );
$datosTorneo = $oEquipo->getRelacionTorneo ( $_POST ["idTorneoEquipo"] );
for($fila = 18; $fila < 31; $fila ++) {
	$nombre = $objPHPExcel->getActiveSheet ()->getCell ( 'A' . $fila )->getValue ();
	$nombre = trim($nombre);
	$apellido = $objPHPExcel->getActiveSheet ()->getCell ( 'B' . $fila )->getValue ();
	$apellido = trim($apellido);
	$dni = $objPHPExcel->getActiveSheet ()->getCell ( 'C' . $fila )->getValue ();
	$dni = trim($dni);
	$fecnac = $objPHPExcel->getActiveSheet ()->getCell ( 'E' . $fila )->getFormattedValue ();
	$fecnac = trim($fecnac);
	$telefono = $objPHPExcel->getActiveSheet ()->getCell ( 'F' . $fila )->getValue ();
	$telefono = trim($telefono);
	$email = $objPHPExcel->getActiveSheet ()->getCell ( 'H' . $fila )->getValue ();
	$email = trim($email);
	if ($dni != "" || $apellido != "") {
		$jugadorasNueva [$fila] = array (
				'id' => $fila,
				'nombre' => $nombre . " " . $apellido,
				'dni' => $dni,
				'fecnac' => $fecnac,
				'telefono' => $telefono,
				'email' => $email
		);
		$oJugadora = new Jugadoras ();
		
		if ($dni != "") {
			$jugadora = $oJugadora->getByDocumento ( $dni );
			if ($jugadora != NULL) {
				$jugadorasExisteDni[$jugadora[0]['id']] = array (
						'id' => $jugadora[0]['id'],
						'nombre' => $nombre . " " . $apellido,
						'dni' => $dni,
						'fecnac' => $fecnac,
						'telefono' => $telefono,
						'email' => $email 
				);
			}
		} else {
			$jugadora = NULL;
		}
		
		if ($jugadora == NULL && $apellido != "") {
			$jugadoras = $oJugadora->getByApellido ($apellido, $nombre );
			if ($jugadoras != NULL) {
				foreach ($jugadoras as $jugadora) {
					$jugadorasExisteNombre [$jugadora['id']] = array (
						'id' => $jugadora['id'],
						'nombre' => $nombre . " " . $apellido,
						'dni' => $dni,
						'fecnac' => $fecnac,
						'telefono' => $telefono,
						'email' => $email 
					);
				}
			}
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
	
	<script>
	function volver(){
		document.form_alta.accion.value = "importarJugadoras";		
		document.form_alta.submit();
	}

	function actioninput(id, tipo) {
		if (tipo == 'n') {
			var checkname = "accionn"+id;
			var idname = "idn"+id;
			var name = "nombreyapellidon"+id;
			var dni = "dnin"+id;
			var fecnac = "fecnan"+id;
			var email = "emailn"+id;
			var telefono = "telefonon"+id;
		} else {
			var checkname = "accion"+id;
			var idname = "ide"+id;
			var name = "nombreyapellido"+id;
			var dni = "dni"+id;
			var fecnac = "fecna"+id;
			var email = "email"+id;
			var telefono = "telefono"+id;
		}
		var inputcheck = document.getElementById(checkname);
		if (inputcheck.checked) {
			document.getElementById(idname).disabled = false;
			document.getElementById(name).disabled = false;
			document.getElementById(dni).disabled = false;
			document.getElementById(fecnac).disabled = false;
			document.getElementById(email).disabled = false;
			document.getElementById(telefono).disabled = false;
		} else {
			document.getElementById(idname).disabled = true;
			document.getElementById(name).disabled = true;
			document.getElementById(dni).disabled = true;
			document.getElementById(fecnac).disabled = true;
			document.getElementById(email).disabled = true;
			document.getElementById(telefono).disabled = true;
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
					<h1>
						<a href="index.php" title="Volver al incio"> Panel de Control</a>
					</h1>
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
							<h1>
								Importacion de Jugadoras de <font color="#e4790f"><?=$equipo[0]['nombre']." [".$datosTorneo[0]['torneo'] ." - ".$datosTorneo[0]['categoria']."]" ?></font>
							</h1>
						</div>
						<div class="ce_text block">
							<div class="mod_listing ce_table listing block" id="partnerlist">
								<form name="form_alta" id="form_alta"
									action="<?=$_SERVER['PHP_SELF']?>" method="post">
									<input name="id" id="id" value="<?=$_POST["id"]?>"
										type="hidden" /> <input name="_pag" id="_pag"
										value="<?=$_POST["_pag"]?>" type="hidden" /> <input
										type="hidden" name="accion" value="guardarImportar" /> <input
										type="hidden" name="idTorneoEquipo"
										value="<?=$_POST["idTorneoEquipo"]?>" /> <input type="hidden"
										name="idTorneoCat" value="<?=$_POST["idTorneoCat"]?>" /> 
									<!-- Filtros -->
				                	<input type="hidden" name="fnombre" value="<?=$_POST["fnombre"]?>" />
				               	 	<input type="hidden" name="femail" value="<?=$_POST["femail"]?>"  />                           
				               		<!-- Fin filtros -->
									<!-- Parametros menu -->
									<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
									<input type="hidden" name="submenu"
										value="<?=$_POST["submenu"]?>" /> <input type="hidden"
										name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
									<!--     -->
									
									<? if (sizeof($jugadorasNueva) != 0) {?>
										<div class="ce_text block"><h2>Nuevas Jugadoras</h2></div>
										<table style="width: 928px">
											<tr>
												<th>Id</th>
												<th>Nombre</th>
												<th>D.N.I.</th>
												<th>Fecha Nacimiento</th>
												<th>Email</th>
												<th>Telefono</th>
												<th width="5%"></th>
											</tr>
										<? 	foreach($jugadorasNueva as $jugadora) { ?>
											<tr>
												<td>X<input size=3 type="hidden" disabled="disabled" name="idn<? echo $jugadora['id']?>" id="idn<? echo $jugadora['id']?>" value="<?=$jugadora['id']?>"/></td>
												<td align="left"><?=$jugadora['nombre']?><input type="hidden" disabled="disabled" name="nombreyapellidon<? echo $jugadora['id']?>" id="nombreyapellidon<? echo $jugadora['id']?>" value="<?=$jugadora['nombre']?>"/> </td>
												<td align="left"><?=$jugadora['dni']?><input size=1  type="hidden" disabled="disabled" name="dnin<? echo $jugadora['id']?>" id="dnin<? echo $jugadora['id']?>" value="<?=$jugadora['dni'] ?>"/> </td>
												<td align="left"><?=$jugadora['fecnac']?><input type="hidden" disabled="disabled" name="fecnan<? echo $jugadora['id']?>" id="fecnan<? echo $jugadora['id']?>" value="<?=$jugadora['fecnac']?>"/> </td>
												<td align="left"><?=$jugadora['email']?><input type="hidden" disabled="disabled" name="emailn<? echo $jugadora['id']?>" id="emailn<? echo $jugadora['id']?>" value="<?=$jugadora['email']?>"/> </td>
												<td align="left"><?=$jugadora['telefono']?><input type="hidden" disabled="disabled" name="telefonon<? echo $jugadora['id']?>" id="telefonon<? echo $jugadora['id']?>" value="<?=$jugadora['telefono']?>"/> </td>
												<td style="text-align: center"><input type="checkbox" name="accionn<? echo $jugadora['id']?>" id="accionn<? echo $jugadora['id']?>" onclick="actioninput(<? echo $jugadora['id']?>,'n')"/></td>	
										 <?	} ?>
											</tr>
									</table>	
									<? } 
										if (sizeof($jugadorasExisteDni) != 0) {?>
										<div class="ce_text block"><h2>Encontra Por D.N.I.</h2></div>
										<table style="width: 928px">
											<tr>
												<th>Id</th>
												<th>Nombre</th>
												<th>D.N.I.</th>
												<th>Fecha Nacimiento</th>
												<th>Email</th>
												<th>Telefono</th>
												<th width="5%"></th>
											</tr>
										<? 	foreach($jugadorasExisteDni as $jugadora) { 
											$objJugadora = new Jugadoras();
											$juega = $objJugadora->jugadoraEnEquipo($jugadora['id'],$_POST['idTorneoEquipo']);?>
											<tr>
												<td><?=$jugadora['id']?><input size=1 type="hidden" disabled="disabled" name="ide<? echo $jugadora['id']?>" id="ide<? echo $jugadora['id']?>" value="<?=$jugadora['id']?>"/></td>
												<td align="left"><?=$jugadora['nombre']?><input type="hidden" disabled="disabled" name="nombreyapellido<? echo $jugadora['id']?>" id="nombreyapellido<? echo $jugadora['id']?>" value="<?=$jugadora['nombre']?>"/> </td>
												<td align="left"><?=$jugadora['dni']?><input size=1 type="hidden" disabled="disabled" name="dni<? echo $jugadora['id']?>" id="dni<? echo $jugadora['id']?>" value="<?=$jugadora['dni'] ?>"/> </td>
												<td align="left"><?=$jugadora['fecnac']?><input type="hidden" disabled="disabled" name="fecna<? echo $jugadora['id']?>" id="fecna<? echo $jugadora['id']?>" value="<?=$jugadora['fecnac']?>"/> </td>
												<td align="left"><?=$jugadora['email']?><input type="hidden" disabled="disabled" name="email<? echo $jugadora['id']?>" id="email<? echo $jugadora['id']?>" value="<?=$jugadora['email']?>"/> </td>
												<td align="left"><?=$jugadora['telefono']?><input type="hidden" disabled="disabled" name="telefono<? echo $jugadora['id']?>" id="telefono<? echo $jugadora['id']?>" value="<?=$jugadora['telefono']?>"/> </td>
												<?php if (!$juega) { ?>
													<td style="text-align: center"><input type="checkbox" id="accion<? echo $jugadora['id']?>" name="accion<? echo $jugadora['id']?>" id="accion<? echo $jugadora['id']?>" onclick="actioninput(<? echo $jugadora['id']?>,'e')"/></td>	
										 	<?	} else { ?>
										 			<td></td>
										 	<?	}
											} ?>
											</tr>
									</table>	
									<? } 
									  if (sizeof($jugadorasExisteNombre) != 0) {?>
										<div class="ce_text block"><h2>Encontra Por Nombre y Apellido</h2></div>
										<table style="width: 928px">
											<tr>
												<th>Id</th>
												<th>Nombre</th>
												<th>D.N.I.</th>
												<th>Fecha Nacimiento</th>
												<th>Email</th>
												<th>Telefono</th>
												<th width="5%"></th>
											</tr>
										<? 	foreach($jugadorasExisteNombre as $jugadora) {
												$objJugadora = new Jugadoras();
												$juega = $objJugadora->jugadoraEnEquipo($jugadora['id'],$_POST['idTorneoEquipo']);
												?>
											<tr>
												<td><?=$jugadora['id']; ?><input size=1 type="hidden" disabled="disabled" name="ide<? echo $jugadora['id']?>" id="ide<? echo $jugadora['id']?>" value="<?=$jugadora['id']?>"/></td>
												<td align="left"><?=$jugadora['nombre']?><input type="hidden" disabled="disabled" name="nombreyapellido<? echo $jugadora['id']?>" id="nombreyapellido<? echo $jugadora['id']?>" value="<?=$jugadora['nombre']?>"/> </td>
												<td align="left"><?=$jugadora['dni']?><input size=1 type="hidden" disabled="disabled" name="dni<? echo $jugadora['id']?>" id="dni<? echo $jugadora['id']?>" value="<?=$jugadora['dni'] ?>"/> </td>
												<td align="left"><?=$jugadora['fecnac']?><input type="hidden" disabled="disabled" name="fecna<? echo $jugadora['id']?>" id="fecna<? echo $jugadora['id']?>" value="<?=$jugadora['fecnac']?>"/> </td>
												<td align="left"><?=$jugadora['email']?><input type="hidden" disabled="disabled" name="email<? echo $jugadora['id']?>" id="email<? echo $jugadora['id']?>" value="<?=$jugadora['email']?>"/> </td>
												<td align="left"><?=$jugadora['telefono']?><input type="hidden" disabled="disabled" name="telefono<? echo $jugadora['id']?>" id="telefono<? echo $jugadora['id']?>" value="<?=$jugadora['telefono']?>"/> </td>
												<?php if (!$juega) { ?>
													<td style="text-align: center"><input type="checkbox" id="accion<? echo $jugadora['id']?>" name="accion<? echo $jugadora['id']?>" id="accion<? echo $jugadora['id']?>" onclick="actioninput(<? echo $jugadora['id']?>,'e')"/></td>	
										 	<?	} else { ?>
										 			<td></td>
										 	<?	}
										 	} ?>
											</tr>
									</table>	
									<? } ?>
									
									<div class="submit_container">
										<input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
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
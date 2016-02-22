<?
	include_once "include/config.inc.php";
	include_once "../model/fechas.php";	
	header("Content-Type:text/html; charset=utf-8"); 
	$oFechas = new Fechas();
	$aFechas = $oFechas->getIdTorneoCat($_REQUEST['id']);
?>
	<select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection" onChange="clearEquipo1('idEquipoTorneo1');clearEquipo2('idEquipoTorneo2');return listOnChange('idTorneoCat', '', 'Equipo1List','equipo1_data.php','advice3','idEquipoTorneo1','idEquipoTorneo1');" >			
	<?  if($_REQUEST["id"]==-1) { ?>
		<option value="-1">Seleccione antes una Categor&iacute;a...</option>
	<?  } else {?>
		<option value="-1">Seleccione una Fecha...</option>
	<?  }
		for ($i=0;$i<count($aFechas);$i++) { ?>		
			<option value="<?=$aFechas[$i]["id"]?>"><?=$aFechas[$i]["nombre"]?></option>
	<?	} ?>
	</select>
	<span id="<?=$_REQUEST["id_status"]?>"> </span>

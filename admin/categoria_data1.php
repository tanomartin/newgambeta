<?
	include_once "include/config.inc.php";
	include_once "../model/torneos.categorias.php";	
	header("Content-Type:text/html; charset=utf-8"); 
	$oTorneoCat = new TorneoCat();
	$aTorneoCat = $oTorneoCat->getByTorneoSub($_REQUEST['id']);
?>
	<select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection"  onChange="clearFecha('idFecha');clearEquipo1('idEquipoTorneo1');clearEquipo2('idEquipoTorneo2');return listOnChange('idTorneoCat', '', 'fechaList','fecha_data.php','advice2','idFecha','idFecha');" >
	<?  if($_REQUEST["id"]==-1) { ?>
		<option value="-1">Seleccione antes un Torneo...</option>
	<?  } else {?>
		<option value="-1">Seleccione una Categor&iacute;a...</option>
	<?  } 
		for ($i=0;$i<count($aTorneoCat);$i++) { ?>		
			<option value="<?=$aTorneoCat[$i]["id"]?>"><?=$aTorneoCat[$i]["nombrePagina"]?> <? if ( $aTorneoCat[$i]["nombreCatPagina"] != "" ){ echo "- ". $aTorneoCat[$i]["nombreCatPagina"]; } ?></option>
	<?	} ?>
	</select>
	<span id="<?=$_REQUEST["id_status"]?>"> </span>

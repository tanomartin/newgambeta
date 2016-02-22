<?
	include_once "include/config.inc.php";
	include_once "../model/equipos.php";	
	header("Content-Type:text/html; charset=utf-8"); 
	$oEquipos = new Equipos();
	$aEquipos = $oEquipos->getTorneoCat($_REQUEST['id']);
?>
	<select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection" onChange="clearEquipo2('idEquipoTorneo2');return listOnChange('<?=$_REQUEST["id_sublista"]?>', '', 'Equipo2List','equipo2_data.php','advice4','idEquipoTorneo2','idEquipoTorneo2');" >			
	<? if($_REQUEST["id"]==-1) { ?>
			<option value="-1">Seleccione antes una Fecha...</option>
	<? } else {?>
			<option value="-1">Seleccione un Equipo...</option>
	<? } 
	   for ($i=0;$i<count($aEquipos);$i++) { ?>		
			<option value="<?=$aEquipos[$i]["idEquipoTorneo"]?>"><?=$aEquipos[$i]["nombre"]?></option>
	<? } ?>
	</select>
	<span id="<?=$_REQUEST["id_status"]?>"> </span>
<?	include_once "include/config.inc.php";
	include_once "../model/equipos.php";	
	header("Content-Type:text/html; charset=utf-8"); 
	$oEquipo= new Equipos();
	$aEquipos = $oEquipo->getByCategoria($_REQUEST['id']);
	if ($_REQUEST['id']!= 0) { ?>
    	<select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection">				
     <? if($_REQUEST["id"]==-1) { ?>
        	<option value="-1">Seleccione antes un Torneo...</option>
     <? } else {?>
        	<option value="-1">Seleccione un Equipo</option>
     <? } 
        for ($i=0;$i<count($aEquipos);$i++) { ?>		
            <option value="<?=$aEquipos[$i]["id"]?>"><?=$aEquipos[$i]["nombre"]?></option>
     <?	}?>
        </select>
<? } else { ?>
     	<select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>">				
        	<option value="0">Home</option>
     	</select>
<? } ?>
	<span id="<?=$_REQUEST["id_status"]?>"> </span>
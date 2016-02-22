<?
	include_once "include/config.inc.php";
	include_once "../model/torneos.categorias.php";	
	
	header("Content-Type:text/html; charset=utf-8"); 


	$oTorneoCat = new TorneoCat();
	$aTorneoCat = $oTorneoCat->getByTorneo($_REQUEST['id']);

	if ($_REQUEST['id']!= 0) {
?>

     <select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>" class="validate-selection" >				
    <? if($_REQUEST["id"]==-1) { ?>
        <option value="-1">Seleccione antes un Torneo...</option>
    <? } else {?>
        <option value="-1">Seleccione una Categor&iacute;a...</option>
    <? } ?>    
    <?
        for ($i=0;$i<count($aTorneoCat);$i++) 
        {
    ?>		
            <option value="<?=$aTorneoCat[$i]["id"]?>"><?=$aTorneoCat[$i]["nombrePagina"]?></option>
    <?	
        }
    ?>
        </select>
<? } else { ?>

     <select name="<?=$_REQUEST["id_sublista"]?>" id="<?=$_REQUEST["id_sublista"]?>">				
        <option value="0">Home</option>
    </select>

<? } ?>

	<span id="<?=$_REQUEST["id_status"]?>"> </span>

<div class="mod_breadcrumb">


<a href="Paneldecontrol.php" title="Panel de Control">Panel de Control</a> 

<? 
if ($_POST["menu"] && $_POST["menu"] != "Panel de Control") { ?>

 	&gt;<a href="#" onclick="javascript:ir('<?=$_POST["menu"]?>', '<?=$_POST["_pag"]?>', '<?=$_POST["pag_submenu"]?>')" title="<?=$_POST["menu"]?>"><?=$_POST["menu"]?></a>

<? } ?>

<? if ($_POST["submenu"] && !($_POST["submenu3"] )) { ?>

 &gt; <span class="active"><?=$_POST["submenu"]?></span>

<? } ?>

<? if ($_POST["submenu3"] ) { ?>

 	&gt;<a href="#" onclick="javascript:ir1('<?=$_POST["menu"]?>', '<?= $_POST["submenu"]?>', '<?=$_POST["_pag"]?>', '<?=$_POST["pag_menu"]?>', '<?=$_POST["pag_submenu"]?>')" title="<?=$_POST["submenu"]?>"><?=$_POST["submenu"]?></a>

 &gt; <span class="active"><?=$_POST["submenu3"]?></span>

<? } ?>
</div>

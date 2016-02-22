<form name="frm_mainmenu" action="" method="post">
	
    <input name="menu" value="" type="hidden" />
    <input name="_pag" value="" type="hidden" />
    <input name="submenu" value="" type="hidden" />
    <input name="pag_submenu" value="" type="hidden"/>
    <input name="pag_menu" value="" type="hidden"/>
    
</form>

<!-- indexer::stop -->
<div>
	<? if ($modulo == 'noticias' ) { ?>
		<img src="../img/menu/submenu_NOTICIAS_selecc.jpg" />
    <? } else { ?>
    		<img  src="../img/menu/submenu_NOTICIAS.jpg" onmouseover="../img/menu/submenu_NOTICIAS_over.jpg" />
	<? } ?>
</div>

<!-- indexer::continue -->
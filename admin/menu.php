<script>
	function ir(menu, _pag, pag_menu) {
		document.frm_mainmenu.menu.value = menu;	
		document.frm_mainmenu._pag.value = _pag;	
		document.frm_mainmenu.pag_submenu.value = pag_menu;	
		document.frm_mainmenu.pag_menu.value = pag_menu;	
		document.frm_mainmenu.action = pag_menu;
		document.frm_mainmenu.submit();
	}

	function ir1(menu,submenu, _pag, pag_menu,pag_submenu) {
		document.frm_mainmenu.menu.value = menu;	
		document.frm_mainmenu.submenu.value = submenu;	
		document.frm_mainmenu._pag.value = _pag;	
		document.frm_mainmenu.pag_submenu.value = pag_menu;	
		document.frm_mainmenu.pag_menu.value = pag_menu;	
		document.frm_mainmenu.action = pag_submenu;
		document.frm_mainmenu.submit();	
	}
</script>

<form name="frm_mainmenu" action="" method="post">
    <input name="menu" value="" type="hidden" />
    <input name="_pag" value="" type="hidden" />
    <input name="submenu" value="" type="hidden" />
    <input name="pag_submenu" value="" type="hidden"/>
    <input name="pag_menu" value="" type="hidden"/>
    
</form>

<div class="mod_navigation block" id="mainmenu">
	<a href="#skipNavigation1" class="invisible">Skip navigation</a>
	<ul class="level_1">
		<li class="<? if( $_POST["menu"] == "Inicio") echo "active"; ?> home first" onclick="this.blur(); ir('Panel de Control', '', 'paneldecontrol.php')" >
	    	<span class=" home first" style="cursor:pointer">Inicio</span>
	    </li>
		<? if (strpos($_SERVER['PHP_SELF'], "index.php") == 0 ) {?>
		<li class="<? if( $menu == "General") echo "active"; ?>  submenu "><span class="submenu">General</span>
		  <ul style="visibility: visible; opacity: 1;" class="level_2">
		    <li class="first"> 
		      <a href="#" title="Mailing"  class="first"  onclick="this.blur(); ir('Mailing', '', 'mailing.php', 'mailing.php')">Mailing</a>
	        </li>           
	      </ul>
	    </li> 
		<li class=<? if( $menu == "Parametros") echo "active"; ?>><span class="submenu">Par&aacute;metros</span>
		  <ul style="visibility: visible; opacity: 1;" class="level_2">
		    <li class="first">
		      <a href="#" title="Categor&iacute;as" class="first" onclick="this.blur(); ir('Categor&iacute;as','','categorias.php','categorias.php')">Categor&iacute;as</a>
	        </li>
		    <li>
		      <a href="#" title="Sedes"  onclick="this.blur(); ir('Sedes', '', 'sedes.php', 'sedes.php')">Sedes</a>
	        </li>
	        <li>
		      <a href="#" title="Arbitros"  onclick="this.blur(); ir('Arbitros', '', 'arbitros.php', 'arbitros.php')">Arbitros</a>
	        </li>
			<li>
		      <a href="#" title="Noticias"    onclick="this.blur(); ir('Noticias', '', 'noticias.php', 'noticias.php')">Noticias</a>
	        </li>
	        <li>
		      <a href="#" title="Colores"    onclick="this.blur(); ir('Colores', '', 'colores.php', 'colores.php')">Colores</a>
	        </li>             
	      </ul>
	    </li>
		<li class="<? if( $_POST["menu"]== "Secciones") echo "active"; ?>  submenu "><span class="submenu">Conf. del Torneo</span>
		  <ul style="visibility: visible; opacity: 1;" class="level_2">
		    <li class="first">
		      <a href="#" title="Torneos"  class="first"  onclick="this.blur(); ir('Torneos', '', 'torneos.php', 'torneos.php')">Torneos</a>
	        </li>
		    <li>
		      <a href="#" title="Equipos" class="first" onclick="this.blur(); ir('Equipos', '', 'equipos.php', 'equipos.php')">Equipos</a>
	        </li>
		    <li>
		      <a href="#" title="Jugadoras" class="first" onclick="this.blur(); ir('Jugadoras', '', 'jugadoras.php', 'jugadoras.php')">Jugadoras</a>
	        </li>
		    <li>
		      <a href="#" title="Fechas" class="first" onclick="this.blur(); ir('Fechas', '', 'fechas.php', 'fechas.php')">Fechas</a>
	        </li>        
	      </ul>
	    </li> 
	    <li class="<? if( $menu == "Reservas") echo "active"; ?>  submenu "><span class="submenu">Gesti&oacute;n de Fecha</span>
		  <ul style="visibility: visible; opacity: 1;" class="level_2">
		    <li class="first"> 
		      <a href="#" title="Reservas"  class="first"  onclick="this.blur(); ir('Reservas', '', 'reservas.php', 'reservas.php')">Reservas</a>
	        </li>        
		    <li >
		      <a href="#" title="Confirmaciones"  class="first"  onclick="this.blur(); ir('Confirmaciones', '', 'confirmaciones.php', 'confirmaciones.php')">Confirmaciones</a>
	        </li>        
			<li >
		      <a href="#" title="Listado Fecha"  class="first"  onclick="this.blur(); ir('Listado Fecha', '', 'listadopartidos.php', 'listadopartidos.php')">Listado de Partidos</a>
	        </li>        
	      </ul>
	    </li> 
		<li class="<? if( $_POST["menu"]== "Gestion") echo "active"; ?>  submenu "><span class="submenu">Gesti&oacute;n del Torneo</span>
		  <ul style="visibility: visible; opacity: 1;" class="level_2">
		    <li class="first">
		      <a href="#" title="Fixture"  class="first"  onclick="this.blur(); ir('Fixture', '', 'fixture.php', 'fixture.php')">Fixture</a>
	        </li>   
	        <li >
		      <a href="#" title="Listado Libre-WO"  class="first"  onclick="this.blur(); ir('Listado Libre', '', 'libreswo.php', 'libreswo.php')">Listado Libre y W.O.</a>
	        </li>     
	      </ul>
	    </li> 
	 <? } ?>
	</ul>
 
	<a id="skipNavigation1" class="invisible">&nbsp;</a>
</div>
<!-- indexer::continue -->
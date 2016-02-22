<!-- indexer::stop -->
<div class="mod_customnav block" id="topmenu">


<ul class="level_1">
    <li class="first">
        <a href="index.php" title="Ingresar al Panel de Control" class="first" onclick="this.blur();">Ingreso</a>
    </li>

	<li>
    	<a href="../index.php" title="Ir al sitio Web" onclick="this.blur();">Sitio Web</a>
   </li>
   
   <li class="last">
   		<a href="logout.php" title="Cerrar la Cesión" class="last" onclick="this.blur();">Cerrar la Sesi&oacute;n</a>
  </li>
<? if (!isset($flag)) { ?>
   <li class="last">
   		<a href="clave.edit.php" title="Cambiar Clave" class="last" onclick="this.blur();">Cambiar Clave</a>
  </li>  
<?  } ?>  
  
</ul>
 

</div>
<!-- indexer::continue -->
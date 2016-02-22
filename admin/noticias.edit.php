<?
	include_once "../model/noticias.php";
	include_once "../model/torneos.php";
	include_once "../model/torneos.categorias.php";	
   //instancio editor


  $operacion = "Alta";
  $fecha = date("j/n/Y");

	$id_torneo ="";
	$datos[0]["idTorneoCat"] = -1;
	$oTorneoCat= new TorneoCat();

	if ($_POST["id"] != -1) {
	
		$operacion = "Modificaci&oacute;n";

		$oNoticia= new Noticias();
		$datos = $oNoticia->get($_POST["id"]);
		$fecha = $datos[0]['fecha'];

		$torneo = $oTorneoCat->get($datos[0]['idTorneoCat']);

		$id_torneo = $torneo[0][id_torneo];
	}


	$oTorneo= new Torneos();
	$aTorneos = $oTorneo->get();
?>
	
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="Panel de Control."/>
<meta name="keywords" content=""/>
<meta name="robots" content="index,follow"/>

<? include("encabezado.php"); ?>

<title>Panel de Control</title>

<script>

	function volver(){
	
		document.form_alta.accion.value = "novedades";		
		document.form_alta.submit();
	}

</script>

</head>

<body id="top" class="home">

<div id="wrapper">

<!-- Header -->

<div id="header">
	<div class="inside">

<? include("top_menu.php"); ?>

<!-- indexer::stop -->
<!--
<div id="search">
<form action="search.html" method="get">
<div class="formbody">
  <label for="keywords" class="invisible">Search</label>
  <input name="keywords" id="keywords" class="text" type="text"><input src="index_archivos/search.png" alt="Search" value="Search" class="submit" type="image">
</div>
</form>
</div>
-->
<!-- indexer::continue -->

<!-- indexer::stop -->
<div id="logo">
	<h1><a href="index.php" title="Volver al incio"> Panel de Control</a></h1>
</div>
<!-- indexer::continue -->

<? include("menu.php");?>

 
	</div>

</div>

<!-- Header -->


<div id="container">

    <div id="main">
        
        <div class="inside">
    
            <? include("path.php"); ?>




<div class="mod_article block" id="register">

<div class="ce_text block">
	<h1><?=$operacion?> de Novedades </h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">

			<input name="id" id="id"  value="<?=$_POST["id"]?>" type="hidden" />
			<input name="_pag" id="_pag"  value="<?=$_POST["_pag"]?>" type="hidden" />
			<input type="hidden" name="accion" value="guardar" />
			<input name="idtemporal" id="idtemporal"  value="<?=$id_novedad?>" type="hidden" />
          
   			<!-- Parametros menu -->
   				<input type="hidden" name="menu" value="<?=$_POST["menu"]?>" />
	            <input type="hidden" name="submenu" value="<?=$_POST["submenu"]?>" />
        	    <input type="hidden" name="pag_submenu" value="<?=$_POST["pag_submenu"]?>" />
            <!--     -->
	<div class="ce_table">
	
	<fieldset>
   <table style="width: 100%" summary="Personal data">
  	<tbody>
          <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Fecha</label></td>
            <td class="col_1 col_last"> 
                 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha;?>" size="8" readonly="readonly" />
                <a href="javascript:show_calendar('document.form_alta.fecha', document.form_alta.fecha.value);">
                        <img src="../_js/calendario2/cal.gif" width="16" height="16" border="0" />
				</a>                        
            </td>
      </tr>
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Importante</label></td>
        <td class="col_1 col_last"> 
	        <input type="radio"  name="posicion" value="1" <? if( $datos[0]['posicion'] == 1 ) {?> checked="checked" <? } ?> /> Importante 	<input name="posicion"  type="radio" value="0" <? if( $datos[0]['posicion'] == 0 ) {?> checked="checked" <? } ?> /> Normal
		</td>
	</tr>
        <tr class="even">
        <td class="col_0 col_first"><label for="nombre">T&iacute;tulo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
			 <input type="text" name="titulo" value="<?= $datos[0]['titulo'] ?>"  size="50"/>
		</td>
      </tr>
      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Torneo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last">
         <select name="idTorneo" id='idTorneo' <?= $disabled ?> class="validate-selection" onChange="clearCategoria('idTorneoCat');
         	return listOnChange('idTorneo', '','categoriaList','categoria_data_noticias.php','advice1','idTorneoCat','idTorneoCat');" >
            <option value="-1">Seleccione un Torneo...</option>
            <option value="0"  <?php if ($datos[0]["idTorneoCat"] == 0) echo "selected"; ?>> Home </option>            
		 	<?php for($i=0;$i<count($aTorneos);$i++) { ?>	
				<option value="<?php echo $aTorneos[$i]['id'] ?>" <?php if ($id_torneo ==   $aTorneos[$i]['id'] ) echo "selected"; ?>><?php echo $aTorneos[$i]['nombre'] ?>
                </option>
             <?php } ?>	   
         	</select>
         </td>   
      </tr>  

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Categoría</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"> 
		<span id="categoriaList">
				<select name="idTorneoCat" id="idTorneoCat" <?= $disabled ?> class="validate-selection" >
					<option value="-1">Seleccione antes un Torneo...</option>
						<?
						 if($datos[0]["idTorneoCat"]>=0) {
						  if($datos[0]["idTorneoCat"] != 0) {
							$oTorneoCat = new TorneoCat();
							$aTorneoCat = $oTorneoCat->getByTorneo($id_torneo);

							for ($i=0;$i<count($aTorneoCat);$i++) 
							{
						?>	
							 <option <? if($aTorneoCat[$i]["id"] == $datos[0]["idTorneoCat"]) echo "selected"; ?> value="<?=$aTorneoCat[$i]["id"]?>"><?=$aTorneoCat[$i]["nombrePagina"]?></option>
							
						<?							
							}
						 } else { ?>
                                <option value="0"  <?php if ($datos[0]["idTorneoCat"] == 0) echo "selected"; ?>> Home </option>            
					<? }
					
					 }?>
						</select> 
            <span id="advice1"> </span>
			</span>	
        </td>    
      </tr>        
      <tr class="odd">
        <td class="col_0 col_first"><label for="descripcion">Desarrollo</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><textarea rows="20" name="desarrollo"><?= $datos[0]['desarrollo'] ?></textarea></td>
      </tr>      
	</tbody>
	</table>
	</fieldset>

    <div class="submit_container">
   
    <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
    <input class="submit" type="button" value="Limpiar" onclick="javascript:limpiar('form_alta');" />
    <input class="submit" type="button" value="Volver" onclick="javascript:volver();" />
    
    
    
    </div>
    </div>
</form>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">

	<p><strong>Novedades</strong><br/>
	Ingrese las novedades.</p>
	<p>Los campos marcados con <span class="mandatory">*</span> son de ingreso obligatorio.</p>

</div>

<div class="clear"></div>

</div>

</div>

	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>

</div>
</body>

</html>

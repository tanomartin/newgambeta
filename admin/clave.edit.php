<?	include_once "include/config.inc.php";
	include_once CLASS_MODEL_ . "usuario_adm.php";

	if(!session_is_registered("usuario")){
		header("Location: index.php");
		exit;
	}


	if(isset($_POST['ok'])) {
		$oObj = new Usuario_adm();
		$oObj -> usuario = $_SESSION['usuario'];
		$datos = $oObj->cambiarClave($_POST["clave"], $_POST["clave1"]);
    	header ("Location: paneldecontrol.php");
		exit; 
	}
	
	$operacion = "Modificaci&oacute;n";



?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Panel de Control."/>
<meta name="keywords" content=""/>
<meta name="robots" content="index,follow"/>

<? include("encabezado.php"); ?>

<script>

	function volver(){
	
		document.form_alta.accion.value = "volver";		
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
	<h1> <a href="index.php" title="Volver al incio">Panel de Control</a></h1>
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
	<h1><?=$operacion?> de Clave</h1>
</div>

<!-- indexer::stop -->
<div class="mod_registration g8 tableform block">

<form name="form_alta" id="form_alta" action="<?=$_SERVER['PHP_SELF']?>" method="post">


<input name="ok" id="ok"  value="0" type="hidden" />


<div class="formbody">

	<div class="ce_table">
	
	<fieldset>
	<legend>Clave del Usuario <?=$_SESSION[usuario]?></legend>

	<table summary="Personal data">
  	<tbody>

      <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Ingrese la Clave Actual</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input type="password" name="clave" id="clave"  maxlength="8"class="required text" /></td>
      </tr>

      <tr class="even">
        <td class="col_0 col_first"><label for="nombre">Ingrese la Nueva Clave</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input type="password" name="clave1" id="clave1"  maxlength="8"class="required text" /></td>
      </tr>
      
       <tr class="odd">
        <td class="col_0 col_first"><label for="nombre">Ingrese nuevamente la Nueva Clave</label><span class="mandatory">*</span></td>
        <td class="col_1 col_last"><input type="password" name="clave2" id="clave2"  maxlength="8"class="required validate-password text" /></td>
      </tr>

	</tbody>
	</table>
	</fieldset>

    <div class="submit_container">
   
    <input class="submit" onclick="valirdarForm_submit('form_alta')" type="button" value="Guardar" /> 
    <input class="submit" type="button" value="Limpiar" onclick="javascript:limpiar('form_alta');" />
    
    </div>
</div>
</div>
</form>

<script>

	document.form_alta.clave1.focus();

</script>

</div>
<!-- indexer::continue -->


<div class="ce_text g4 xpln block">

	<p><strong>Datos de la Clave</strong><br/>
	Ingrese el valor de la nueva Clave</p>
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

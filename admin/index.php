<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>

<!-- base href="http://www.typolight.org/" -->
<title>Panel de Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<meta name="description" content="Login de Ingreso al Panel de Control"/>
<meta name="keywords" content=""/>
<meta name="robots" content="noindex,follow"/>

<? include("encabezado.php"); ?>



<script type="text/javascript">

function validarClave() {
	var url = "validar.clave.php";
	new Ajax.Request( url, { method: 'get' ,
	parameters : Form.serialize($("frm_login")),
	onSuccess: function(transport){
		if (transport.responseText.substr(0,10) != "nopermisos")  {
			//document.location.href=transport.responseText;
			document.frm_login.submit();	
		} else {
			$('errorclave').innerHTML = "Usuario o Clave incorrecta ";
		}
	},     
	onFailure: function(){ 	alert('Error...'); } 
	});

}	





</script>


</head>

<body id="top">
<div id="wrapper">

<div id="header">
<div class="inside">
<? 
	$flag =0;

include("top_menu.php"); ?>



<!-- indexer::stop -->
<div id="logo">
<h1><a href="index.php" title="Volver al incio">Panel de Control</a></h1>
</div>
<!-- indexer::continue -->


<? include("menu.php");?>
 
</div>
</div>

<div id="container">

<div id="main">
<div class="inside">

<div class="mod_article block" id="login">

<div class="ce_text block">

<h1>Ingreso </h1>

<p>Ingrese con su usuario y contraseña.</p>

</div>

<!-- indexer::stop -->
<div class="mod_login one_column tableless login block">

<form name="frm_login" id="frm_login" action="paneldecontrol.php" method="post">
<input name="menu" value="Inicio" type="hidden" />
<input name="pag_menu" value="paneldecontrol.php" type="hidden" />
    
<div class="formbody">
	<input name="FORM_SUBMIT" value="tl_login" type="hidden"/>
	<label for="usuario">Usuario</label>
	<input name="usuario" id="usuario" class="text" maxlength="64" type="text"><br/>
	<label for="clave">Contrase&ntilde;a</label>
	<input name="clave" id="clave" class="text password" maxlength="64" value="" type="password">
    <br/>
	
    <div class="submit_container">

	<input class="button" onclick="validarClave()" type="button" value="Ingresar" /> 
    <input class="button"  value="Limpiar"  type="reset" />
    
	</div>
</div>
</form>

<script> document.frm_login.usuario.focus(); </script>

<div id="errorclave" style="color:#FF0000; font-weight:bold"></div>

</div>
<!-- indexer::continue -->

<!--
<div class="ce_text block">

<p><a title="Sign up for a contributor's account" href="http://www.typolight.org/register.html">Click here to sign up for an account</a><br><a title="Request a new password" href="http://www.typolight.org/request-password.html">Click here if you have lost your password</a></p>

</div>
-->
</div>
 
</div>
 
<div id="clear"></div>
</div>

</div>

<? include("pie.php"); ?>


</div>

</body>
</html>
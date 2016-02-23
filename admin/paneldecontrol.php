<?
	include_once "include/config.inc.php";
	if (!isset( $_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	

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

		
        <div style="height:350px">&nbsp;</div>
        
 
</div>
 
	<div id="clear"></div>

</div>

</div>

<? include("pie.php")?>

</div>
</body>

</html>
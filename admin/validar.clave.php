<? 	
	include "include/config.inc.php" ; 
	include_once CLASS_MODEL_ . "usuario_adm.php";
	
	$oUsr = new Usuario_adm();
	$usuario = $oUsr->getByUsrPass($_REQUEST[ "usuario" ], $_REQUEST[ "clave" ]); 

	$_SESSION["usuario"] = $usuario->usuario;
	
	if ($usuario) {			
		echo "paneldecontrol.php";
	} else {
		echo "nopermisos";
	}
		
?>

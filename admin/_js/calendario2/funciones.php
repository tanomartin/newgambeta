<? session_start();
// transforma la fecha en formato mysql
function mysql_fecha($fech)
{
$fech1= explode("/",$fech);
	if(strlen(trim($fech1[1]))==1)
	{
		$fech1[1]="0".$fech1[1];
	}
	if(strlen(trim($fech1[0]))==1)
	{
		$fech1[0]="0".$fech1[0];
	}
return trim($fech1[2])."/".trim($fech1[1])."/".trim($fech1[0]);
}

//transforma la fecha en formato para el usuario
function php_fecha($fech)
{
$fech1= explode("-",$fech);
return trim($fech1[2])."/".trim($fech1[1])."/".trim($fech1[0]);
}

function puede()
{
	if ($_SESSION['nivel_usu']!=9)
	{
	$_SESSION['imagen']=$_SESSION['pathini']."/images/stop.png";
	$_SESSION['descr']="Usuario no Autorizado!!!";
	$_SESSION['mensaje']="No Tiene Autorizacion Para\nReailzar Esta Operacion!!!";
	$_SESSION['ref']="/index1.php";
	header("location: ".$_SESSION['pathini']."/result.php");
	}
}

?>
<?
//////////////////////////////////////////////////// 
//Convierte fecha de mysql a normal 
//////////////////////////////////////////////////// 
function cambiaf_a_normal($fecha){ 
	$lafecha = $fecha;

	if (strpos($fecha,"-") > 0) {
	    ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	}
		
	return $lafecha; 
} 

//////////////////////////////////////////////////// 
//Convierte fecha de normal a mysql 
//////////////////////////////////////////////////// 

function cambiaf_a_mysql($fecha){ 
	$lafecha = $fecha;

	if (strpos($fecha,"/") > 0) {
	    ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
	    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    }
	
	return $lafecha; 
} 




// Una función que compara dos fechas devolviendo un valor positivo, negativo o nulo si la primera fecha es respectivamente mayor, menor o igual que la segunda. 
// Para complicar las cosas un poco, la función usa expresiones regulares para que admita fechas tanto en formato "dd-mm-aaaa" como con formato "dd/mm/aaaa", dotando a la función de algo más de inteligencia. 




function compara_fechas($fecha1,$fecha2)  {
            
 
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
            
 
              list($dia1,$mes1,$año1)=split("/",$fecha1);
            
 
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
            
 
              list($dia1,$mes1,$año1)=split("-",$fecha1);
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
            
 
              list($dia2,$mes2,$año2)=split("/",$fecha2);
            
 
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
            
 
              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);                         
            
 
}

function restaFechas($dFecIni, $dFecFin)
{
$dFecIni = str_replace("-","",$dFecIni);
$dFecIni = str_replace("/","",$dFecIni);
$dFecFin = str_replace("-","",$dFecFin);
$dFecFin = str_replace("/","",$dFecFin);


ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);

return round(($date2 - $date1) / (60 * 60 * 24));
} 

function sumaDia($fecha,$dia)
{	list($day,$mon,$year) = explode('/',$fecha);
	return date('d/m/Y',mktime(0,0,0,$mon,$day+$dia,$year));		
}
 

?>